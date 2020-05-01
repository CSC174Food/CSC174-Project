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
create table PRODUCT
(
	pid INT NOT NULL AUTO_INCREMENT,
	product_name varchar(100),
	price decimal(10,2),
    product_type char,
    size char,
    photo text,
	primary key(pid)
);
create table P_ORDER
(
	order_id INT NOT NULL AUTO_INCREMENT,
	payment_name varchar(100),
    expire_date int(4),
    card_number int(16),
    purchase_date timestamp,
    order_type char,
	estimate_arrival datetime,
    pickup_time datetime,
    customer_id int,
    total_price decimal(10,2),
    constraint order_pk
		primary key(order_id),
	constraint customer_id  
		foreign key(customer_id)
			references customer(custid)
);
create table CART(
cart_id int not null auto_increment,
product_id int not null,
item_quantity int,
cid int not null,
primary key(cart_id),
constraint prod_id_fk
 foreign key(product_id)
		references product(pid) ,
constraint customer_id_fk
	foreign key(cid)
		references customer(custid)
);

create view s_cart as
(
SELECT cart.cart_id, product.product_name, cart.item_quantity, product.price, cart.item_quantity*product.price as value, cart.cid
from cart inner join product  
where cart.product_id = product.pid
);

create view receipt as
( SELECT customer.custid, customer.name, p_order.order_type, p_order.pickup_time, p_order.estimate_arrival, p_order.total_price
	FROM p_order inner join	customer
    where p_order.customer_id = customer.custid
);

DELIMITER //
CREATE PROCEDURE view_receipt (IN c_id int)
BEGIN
	SELECT * FROM receipt
            WHERE custid=c_id;
END //
DELIMITER ;