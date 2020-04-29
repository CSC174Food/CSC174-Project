create table CART(
cart_id int not null auto_increment,
product_id int not null,
item_quantity int,
cid int not null,
primary key(cart_id),
 foreign key(product_id)
		references product(pid) ,
foreign key(cid)
		references customer(custid)
);