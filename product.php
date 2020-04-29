<?php
    session_start();
  include('config/db_con.php');
    echo $_SESSION['pid'];
   // query for all customer
   $sql = 'SELECT * FROM product';

   // make query & get result

   $result = mysqli_query($conn, $sql);

   //fetch the result rows as array
   $product = mysqli_fetch_all($result, MYSQLI_ASSOC);

   //clear result
   mysqli_free_result($result);

   //close connection
   mysqli_close($conn);


?>

<!DOCTYPE html>
<html >

    <?php include'templates/header.php'; ?>

   <h4 class="center grey-text">Customer</h4>
   
   <div class="container">
    <div class="row">

        <?php foreach($product as $pid): ?>

            <div class="col s4 md3">
                <div class="card z-depth-0">
                 <div class="card-content center">
                 <div class="product-image"><img src="<?php echo $pid['photo']; ?>"></div> 
                    <div><h5><?php echo ($pid['product_name']); ?></h5></div>
                    <div class="cart-action" style ="width:10%">
                    <input type="text" class="product-quantity" name="quantity" value="1" />
                    <input type="submit"  value="add" name="add" class="btn" /></div>
                  </div>
                  <div class="card-action right-align"></div>
                  <a href="#" class="brand-text">more info</a>
                </div>
            </div>

        <?php endforeach;  ?>

    </div>
   </div>


    <?php include('templates/footer.php'); ?>
</html>

