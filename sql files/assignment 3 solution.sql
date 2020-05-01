/*
    Tables, attributes, primary keys, and foreign keys are taken from the relational diagram.
    All attributes are assumed to be nullable unless they are prime attributes or NOT NULL is
    needed to enforce a total participation constraint.
 */

create table CUSTOMER
(
	custid int not null
		primary key,
	name varchar(100),
	street varchar(50) not null,
	city varchar(30) not null,
	state char(2) not null,
	zip char(9) not null,
	constraint CUSTOMER_uk
		unique (street, city, state, zip)
);

create table BUSINESS
(
	custid int not null,
	shipmgr varchar(100),
	acct_num int,
	constraint BUSINESS_pk
		primary key (custid),
	constraint BUSINESS_CUSTOMER_custid_fk
		foreign key (custid) references CUSTOMER (custid)
			on update cascade
);

-- phone is char(15) for international numbers. char(10) is fine for US/Canada
create table CUSTOMER_PHONE
(
	custid int not null,
	phone char(15) not null,
	constraint CUSTOMER_PHONE_pk
		primary key (phone, custid),
	constraint CUSTOMER_PHONE_CUSTOMER_custid_fk
		foreign key (custid) references CUSTOMER (custid)
			on update cascade
);

-- Several ways to do service_type, such as int, enum, varchar
-- Photo is mediumblob instead of blob, since blob only stores 64K and mediumblob holds 16MB.
create table PACKAGE
(
	tracking_num int not null,
	width int,
	length int,
	ship_date datetime,
	notes text,
	delivery_date datetime,
	photo mediumblob,
	shipper int not null,
	receiver int not null,
	service_type int,
	height int,
	weight int,
	package_type char not null,
	constraint PACKAGE_pk
		primary key (tracking_num),
	constraint PACKAGE___fk_receive
		foreign key (receiver) references CUSTOMER (custid)
			on update cascade,
	constraint PACKAGE___fk_ship
		foreign key (shipper) references CUSTOMER (custid)
			on update cascade
);

/*
 We'll create materialized views to show all the attributes of a business and the attributes of a
 letter. For contrast, we'll create a PARCEL named query. In real life I'd probably create all of
 these as named queries, since there will likely be fewer selects on the views than inserts/updates,
 and the queries the views are based on are lightweight.
 */

 create table BUSINESS_VIEW
(
	custid int not null
		primary key,
	name varchar(100) 
	street varchar(50) not null,
	city varchar(30) not null,
	state char(2) not null,
	zip char(9) not null,
	shipmgr varchar(100),
	acct_num int,
	constraint BUSINESS_VIEW_uk
		unique (street, city, state, zip),
	constraint BUSINESS_VIEW_CUSTOMER_custid_fk
		foreign key (custid) references CUSTOMER (custid)
			on update cascade
);

create table LETTER
(
	tracking_num int not null,
	width int null,
	length int null,
	ship_date datetime,
	notes text,
	delivery_date datetime,
	photo mediumblob,
	shipper int not null,
	receiver int not null,
	service_type int,
	constraint LETTER_pk
		primary key (tracking_num),
	constraint LETTER___fk_receive
		foreign key (receiver) references CUSTOMER (custid)
			on update cascade,
	constraint LETTER___fk_ship
		foreign key (shipper) references CUSTOMER (custid)
			on update cascade
);

/*
 Even though the assignment asked for everything to be a materialized view, I'm creating
 this as a named query just to show the difference.
*/
create view PARCEL as
( SELECT tracking_num, width, length, ship_date, notes, delivery_date,
	photo, shipper, receiver, height, weight
	FROM PACKAGE
	WHERE package_type = 'P'
);


/*
 We need to create triggers to enforce disjoint and total participation for the subclasses of PACKAGE, and to
 populate our materialized view. Because we're lazy, we'll define a reusable function that checks the data for us.
 */

 delimiter //
 CREATE FUNCTION check_type (package_type CHAR,
                             service_type INT,
                             height INT,
                             weight INT) RETURNS BOOLEAN
     BEGIN
         DECLARE isGood BOOLEAN default FALSE;

         CASE (package_type)
            WHEN 'P' THEN SET isGood = (service_type IS NULL);
            WHEN 'L' THEN SET isGood = (height IS NULL AND weight IS NULL);

         END CASE;

         RETURN isGood;
     end //
  delimiter ;

 delimiter //
 CREATE TRIGGER package_insert_trigger BEFORE INSERT ON planet_express.PACKAGE
     for each row
     begin
         DECLARE isGood BOOLEAN;

         -- check participation and disjoint
         SET isGood = check_type(new.package_type, new.service_type, new.height, new.weight);

         IF (!isGood) THEN
             signal sqlstate '45000'
             SET MESSAGE_TEXT = 'Incorrect attribute values for service type';
         end if;
     end //
delimiter ;

delimiter //
 CREATE TRIGGER package_insertafter_trigger AFTER INSERT ON planet_express.PACKAGE
     for each row
     begin
         IF new.package_type = 'L' then
            INSERT INTO planet_express.LETTER value (new.tracking_num, new.width, new.length, new.ship_date, new.notes,
                                                  new.delivery_date, new.photo, new.shipper, new.receiver,
                                                  new.service_type);
         end if;
     end //
delimiter ;

 delimiter //
 CREATE TRIGGER package_update_trigger BEFORE UPDATE ON planet_express.PACKAGE
     for each row
     begin
         DECLARE isGood BOOLEAN;

         SET isGood = check_type(new.package_type, new.service_type, new.height, new.weight);

         IF (!isGood) THEN
             signal sqlstate '45000'
             SET MESSAGE_TEXT = 'Incorrect attribute values for service type';
             END IF;

     end //
delimiter ;

delimiter //
 CREATE TRIGGER package_updateafter_trigger AFTER UPDATE ON planet_express.PACKAGE
     for each row
     begin

         -- delete old row from materialized view then insert new row
         if old.package_type = 'L' then
            DELETE FROM planet_express.LETTER WHERE LETTER.tracking_num = old.tracking_num;
         end if;

         if new.package_type = 'L' then
            INSERT INTO planet_express.LETTER value (new.tracking_num, new.width, new.length, new.ship_date, new.notes,
                                                  new.delivery_date, new.photo, new.shipper, new.receiver,
                                                  new.service_type);
            end if;
     end //
delimiter ;

delimiter //
 CREATE TRIGGER package_delete_trigger BEFORE DELETE ON planet_express.PACKAGE
     for each row
     begin
         -- delete old row from materialized view
         if old.tracking_num in (select tracking_num from LETTER) then
            DELETE FROM planet_express.LETTER WHERE LETTER.tracking_num = old.tracking_num;
         end if;
     end //
delimiter ;

/*
 Create triggers to update BUSINESS_VIEW. This is made up of data from BUSINESS and CUSTOMER so we need to handle
 changes on either table.
 */
delimiter //
CREATE TRIGGER customer_update_trigger AFTER UPDATE on planet_express.CUSTOMER
    FOR EACH ROW
    BEGIN
        DECLARE newshipmgr varchar(100);
        DECLARE newacct_num int;

        if old.custid in (select custid from BUSINESS_VIEW) then
            DELETE FROM BUSINESS_VIEW WHERE custid = old.custid;
        end if;

        if new.custid in (select custid from BUSINESS) then
            SELECT shipmgr, acct_num INTO newshipmgr, newacct_num
            FROM BUSINESS
            WHERE custid = new.custid;

            INSERT INTO BUSINESS_VIEW values (new.custid, new.name, new.street, new.city, new.state, new.zip,
                                              newshipmgr, newacct_num);
        end if;
    end //
delimiter ;

-- we don't need triggers for insert or delete on CUSTOMER. These will be controlled by insert or delete on BUSINESS.

delimiter //
CREATE TRIGGER business_update_trigger AFTER UPDATE on planet_express.BUSINESS
    FOR EACH ROW
    BEGIN
        DECLARE newname varchar(100);
        DECLARE newstreet varchar(50);
        DECLARE newcity varchar(30);
        DECLARE newstate char(2);
        DECLARE newzip char(9);

        if old.custid in (select custid from BUSINESS_VIEW) then
            DELETE FROM BUSINESS_VIEW WHERE custid = old.custid;
        end if;

        SELECT name, street, city, state, zip INTO newname, newstreet, newcity, newstate, newzip
        FROM CUSTOMER
        WHERE custid = new.custid;

        INSERT INTO BUSINESS_VIEW values (new.custid, newname, newstreet, newcity, newstate, newzip,
                                          new.shipmgr, new.acct_num);
    end //
delimiter ;

delimiter //
CREATE TRIGGER business_delete_trigger AFTER DELETE on planet_express.BUSINESS
    FOR EACH ROW
    BEGIN
        if old.custid in (select custid from BUSINESS_VIEW) then
            DELETE FROM BUSINESS_VIEW WHERE custid = old.custid;
        end if;
    end //
delimiter ;

delimiter //
CREATE TRIGGER business_insert_trigger AFTER INSERT on planet_express.BUSINESS
    FOR EACH ROW
    BEGIN
        DECLARE newname varchar(100);
        DECLARE newstreet varchar(50);
        DECLARE newcity varchar(30);
        DECLARE newstate char(2);
        DECLARE newzip char(9);

        SELECT name, street, city, state, zip INTO newname, newstreet, newcity, newstate, newzip
        FROM CUSTOMER
        WHERE custid = new.custid;

        INSERT INTO BUSINESS_VIEW values (new.custid, newname, newstreet, newcity, newstate, newzip,
                                          new.shipmgr, new.acct_num);
    end //
delimiter ;



/*
 PART 2 -- insert rows into tables
 */
INSERT INTO CUSTOMER VALUES (27, 'Alex Kamal', '6000 J St', 'Sacramento', 'CA', '95819');
INSERT INTO CUSTOMER VALUES (42, 'Naomi Nagata', '48370 Kato Rd', 'Fremont', 'CA', '94538');
INSERT INTO BUSINESS values (42, 'Bobbie Draper', 12345);
INSERT INTO CUSTOMER_PHONE VALUES (27, '1234567890');
INSERT INTO PACKAGE VALUES (123, 5, 8, now(), 'How now brown cow?', NULL, NULL,
                            27, 42, 1, NULL, NULL, 'L');
INSERT INTO PACKAGE VALUES (45, 10, 8, now(), NULL, NULL, NULL,
                            42, 27, NULL, 10, 15, 'P');

-- test our disjoint trigger. Should throw an error
INSERT INTO PACKAGE VALUES (145, 10, 8, now(), NULL, NULL, NULL,
                            42, 27, 2, 10, 15, 'P');


/*
 Dropping a table drops its triggers, so we don't need to explicitly drop triggers. We do need to drop our function
 and our materialized views though.
 */

DROP FUNCTION check_type;
DROP VIEW parcel;
DROP TABLE BUSINESS_VIEW;
DROP TABLE LETTER;
DROP TABLE CUSTOMER_PHONE;
DROP TABLE PACKAGE;
DROP TABLE BUSINESS;
DROP TABLE CUSTOMER;
