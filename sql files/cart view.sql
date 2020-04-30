create view s_cart as
(
SELECT cart.cid,product.product_name, cart.item_quantity, product.price, cart.item_quantity*product.price as value
from cart inner join product  
where cart.product_id = product.pid
);