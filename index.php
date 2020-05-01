<?php
  include('config/db_con.php');

   // query for all customer
   $sql = 'SELECT * FROM customer';

   // make query & get result

   $result = mysqli_query($conn, $sql);

   //fetch the result rows as array
   $customer = mysqli_fetch_all($result, MYSQLI_ASSOC);

   //clear result
   mysqli_free_result($result);

   //close connection
   mysqli_close($conn);

   //print_r($customer);

?>

<!DOCTYPE html>
<html >

    <?php include('templates/header.php'); ?>

   <h4 class="center grey-text">Customer</h4>
   
   <div class="container">
    <div class="row">

        <?php foreach($customer as $cid): ?>

            <div class="col s6 md3">
                <div class="card z-depth-0">
                 <div class="card-content cneter">
                    <h6 ><?php echo($cid['name']); ?></h6>
                    <div><?php echo ($cid['email']); ?></div>
                  </div>
                  <div class="card-action right-align"></div>
                </div>
            </div>

        <?php endforeach;  ?>

    </div>
   </div>


    <?php include('templates/footer.php'); ?>
</html>

