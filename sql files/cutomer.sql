use heroku_fea7079ade0abaf;
create table CUSTOMER
(
	custid INT NOT NULL AUTO_INCREMENT,
	name varchar(100),
    phone char(15) not null,
	street varchar(50) not null,
	city varchar(30) not null,
	state char(2) not null,
	zip char(9) not null,
    email varchar(100),
	primary key(custid)
);
