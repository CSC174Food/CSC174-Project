<?php
  include('config/db_con.php');

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

   //print_r($customer);

?>

<!DOCTYPE html>
<html >

    <?php include('templates\header.php'); ?>

   <h4 class="center grey-text">Customer</h4>
   
   <div class="container">
    <div class="row">

        <?php foreach($product as $pid): ?>

            <div class="col s6 md3">
                <div class="card z-depth-0">
                 <div class="card-content cneter">
                 <div class="product-image"><img src="<?php echo $pid['photo']; ?>"></div>
                    <h6 ><?php echo $pid['photo']; ?></h6>
                    <div><?php echo htmlspecialchars($pid['product_name']); ?></div>
                  </div>
                  <div class="card-action right-align"></div>
                  <a href="#" class="brand-text">more info</a>
                </div>
            </div>

        <?php endforeach;  ?>

    </div>
   </div>


    <?php include('templates\footer.php'); ?>
</html>

