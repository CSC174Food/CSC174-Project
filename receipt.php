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