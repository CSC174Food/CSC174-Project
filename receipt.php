<?php
    session_start();
  include('config/db_con.php');
    
    $c_id =$_SESSION['pid'];
    echo $c_id;
   // query for all customer
   $sql = "SELECT * FROM receipt
            WHERE custid='$c_id'" ;

   // make query & get result

   $finish = mysqli_query($conn, $sql);

   //fetch the result rows as array
   $product = mysqli_fetch_all($finish, MYSQLI_ASSOC);

   //clear result
   mysqli_free_result($finish);

   //close connection
   mysqli_close($conn);


?>

<!DOCTYPE html>
<html >

    <?php include('templates/header.php'); ?>

   <h3 class="center grey-text">Thank you for your purchase</h3>
   
   <div class="container">

   <div class="card z-depth-0">
                 <div class="card-content cneter">
                 <?php foreach($product as $prod): ?>
                  <h4 class="center grey-text"><?php echo ($prod['name']); ?></h4>
                  <h3 class="center grey-text"><?php echo "total price is: $". $prod['total_price']; ?></h3>

                  <?php if($prod['order_type']=='d'){ ?>
                    <h3 class="center grey-text"> <?php echo "Estimated arrival is: " . $prod['estimate_arrival']; ?></h3>
                  <?php } else{ ?>
                      <h3 class="center grey-text"> <?php echo "Your order is ready to pick up on: " . $prod['pickup_time']; ?></h3>
                  <?php } ?>
                  <?php endforeach; ?>  
                  <div class="card-action right-align"></div>
                </div>

    </div>
   </div>


    <?php include('templates/footer.php'); ?>
</html>

