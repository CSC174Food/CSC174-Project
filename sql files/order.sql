create table P_ORDER
(
	order_id INT NOT NULL AUTO_INCREMENT,
	payment_name varchar(100),
    expire_date int(4),
    card_number int(16),
    purchase_date timestamp,
    order_type boolean,
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