<?php
  session_start();
  include('config/db_con.php');
    echo $_SESSION['pid'];
    $c_id =$_SESSION['pid'];
   // query for all customer
   $sql = "SELECT * FROM product ";

   // make query & get result

   $result = mysqli_query($conn, $sql);

   //fetch the result rows as array
   $product = mysqli_fetch_all($result, MYSQLI_ASSOC);

    

   if(isset($_POST['add-cart'])){
        
       echo 'reeee';
            header('Location: product.php');
        
    
   }

   


?>

<!DOCTYPE html>
<html >

    <?php include'templates/header.php'; ?>

   <h4 class="center grey-text">Product</h4>
<!-- cart table -->
   <table class="tbl-cart" cellpadding="10" cellspacing="1">
    <tbody>
        <tr>
        <th style="text-align:left;">Name</th>
        <th style="text-align:right;" width="5%">Quantity</th>
        <th style="text-align:right;" width="10%">Unit Price</th>
        <th style="text-align:right;" width="10%">Price</th>
        <th style="text-align:center;" width="5%">Remove</th>
    </tr>

    <?php 
    
    
    ?>
   <!-- END of cart table -->
   <!-- Product table -->
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
                    <input type="submit"  value="add" name="add-cart" class="btn" /></div>
                  </div>
                  <div class="card-action right-align"></div>
                  <a href="#" class="brand-text">more info</a>
                </div>
                </form>
            </div>
        <?php endforeach;  ?>

    </div>
   </div>
    <!--END of Product table -->

    <?php include('templates/footer.php'); ?>
</html>

