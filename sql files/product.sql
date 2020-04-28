use heroku_fea7079ade0abaf;
create table PRODUCT
(
	pid INT NOT NULL AUTO_INCREMENT,
	product_name varchar(100),
	price decimal(10,2),
    product_type char,
    size char,
    photo mediumblob,
	primary key(pid)
);