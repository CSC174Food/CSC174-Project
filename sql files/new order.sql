
DELIMITER //
CREATE PROCEDURE new_order (c_id int, c_name varchar(100), number int(16), expire int(4), o_type char, total decimal(10,2))
BEGIN
DECLARE c_time datetime;
    declare new_time datetime;
	SELECT @@global.time_zone;
	SET time_zone = '-7:00';
	SET c_time = now();
    SET new_time =(select date_add(c_time, interval 30 minute));
    IF o_type = 'p'	then
	 INSERT INTO p_order (payment_name, card_number, expire_date, purchase_date, customer_id, total_price, order_type, pickup_time)
            VALUES(c_name, number,expire, c_time, c_id,total, o_type, new_time);
	elseif o_type = '' then
	INSERT INTO p_order (payment_name, card_number, expire_date, purchase_date, customer_id, total_price, order_type, estimate_arrival)
            VALUES(c_name, number,expire, c_time, c_id,total, o_type, new_time);
    end if;
   
END //
DELIMITER ;    
