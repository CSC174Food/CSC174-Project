<?php
    session_start();
  include('config/db_con.php');
    
    $c_id =$_SESSION['pid'];
   // query for all customer
   $sql = "SELECT * FROM receipt
            WHERE custid='$c_id'" ;

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

    <?php include('templates/header.php'); ?>

   <h4 class="center grey-text">Thank you for your purchase</h4>
   
   <div class="container">

   <div class="card z-depth-0">
                 <div class="card-content cneter">
                    <h6 ><?php echo htmlspecialchars($cid['name']); ?></h6>
                    <div><?php echo htmlspecialchars($cid['email']); ?></div>
                  </div>
                  <div class="card-action right-align"></div>
                </div>

    </div>
   </div>


    <?php include('templates/footer.php'); ?>
</html>

