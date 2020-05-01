<?php
  session_start();
  include('config/db_con.php');
    //echo $_SESSION['pid'];
    $c_id =$_SESSION['pid'];

   // query for all customer
   $sql = "SELECT * FROM product ";

   // make query & get result

   $result = mysqli_query($conn, $sql);

   //fetch the result rows as array
   $product = mysqli_fetch_all($result, MYSQLI_ASSOC);

   $item= "SELECT * FROM s_cart where cid='$c_id'";
    $cart = mysqli_query($conn,$item);
    $shopping_cart = mysqli_fetch_array($cart, MYSQLI_ASSOC);
 


//---------------------------add an item ----------------------------------------------\\
   if(isset($_POST['add-cart'])){
        
        $quantity = $_POST['quantity'];
        $pid = $_POST['prod_id'];

        $quantity=  mysqli_real_escape_string($conn, $_POST['quantity']);
        $pid=  mysqli_real_escape_string($conn, $_POST['prod_id']);
        $c_id=  mysqli_real_escape_string($conn, $_SESSION['pid']);

        $sql = "INSERT INTO cart (product_id, item_quantity,cid)
                VALUES('$pid', '$quantity','$c_id')";

        if(mysqli_query($conn, $sql)){
            
            header('Location: product.php');
        }
        else{
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            echo'query error: '. mysqli_error($conn);
        }       
    }

    if(isset($_POST['remove'])){

        $delete_id = mysqli_real_escape_string($conn, $_POST['delete_id']);

        $remove= "DELETE FROM cart WHERE cart_id= '$delete_id'";

        if(mysqli_query($conn, $remove)){
            header('Location: product.php');
        }
        else{
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            echo'query error: '. mysqli_error($conn);
        }  
    
    }
//----------------------END OF ADD ITEM----------------------------------------------//
?>

<!DOCTYPE html>
<html >

    <?php include('templates/header.php'); ?>
   <h4 class="center grey-text">Product</h4>
   <!-- Product table -->
   <div class="container">
        <div class="row">

        <?php foreach($product as $pid): ?>

            <div class="col s4 md3">
                <div class="card z-depth-0">
                 <div class="card-content center">
                 <form action="product.php" method="POST">
                 <div class="product-image"><img src="<?php echo $pid['photo']; ?>"></div> 
                 <div><h5><?php echo ($pid['product_name']); ?></h5></div>
                 <div class="cart-action" style ="width:10%">
                     <input type="text" class="product-quantity" name="quantity" value="1" />
                     <input type="submit" value="add" name="add-cart" class="btn" /></div>
                     <input type="hidden" name="prod_id" value="<?php echo ($pid['pid']); ?>">
                 </div>
                  </form>
                </div>
            </div>
        <?php endforeach;  ?>

        </div>
    </div>
    <!--END of Product table -->

    <!-- cart table -->
    <?php
    if(mysqli_num_rows($cart)>0){
     
    $total_quantity = 0;
    $total_price = 0;
    ?>
        <table class="tbl-cart" cellpadding="10" cellspacing="1">
        <tbody>
        <tr>
        <th style="text-align:left;">Name</th>
        <th style="text-align:right;" width="5%">Quantity</th>
        <th style="text-align:right;" width="10%">Unit Price</th>
        <th style="text-align:right;" width="10%">Price</th>
        <th style="text-align:center;" width="5%">Remove</th>
    </tr>
  
    <?php foreach($cart as $shop): 
      
    ?>
        <tr>
        <td>
        <?php echo $shop["product_name"]; ?></td>
            <td style="text-align:right;"><?php echo $shop["item_quantity"]; ?></td>
            <td  style="text-align:right;"><?php echo "$ ".$shop["price"]; ?></td>
            <td  style="text-align:right;"><?php echo "$ ". $shop["value"]; ?></td>
            <td style="text-align:center;">
            <form action="product.php" method="POST">
                <input type="hidden" name="delete_id" value= "<?php echo $shop["cart_id"]; ?>">
                <input type="submit" name="remove" value="remove" class="btn danger z-depth-0">
            </form>
            </td>        
        </tr>
        
   <?php 
        $total_quantity += $shop["item_quantity"];
        $total_price += $shop["value"];
        $_SESSION['total']=$total_price;
    endforeach;?>
   <tr>
   <td>
    <td>
        <div class="center">
            <!-- name="submit" is sent to $_GET to check if the button has been press-->
            <ul id="nav-mobile" >
                    <!-- this is the go to cart button --> 
                    <li><a href="order.php" class="btn ">Pay</a></li>
                </ul>
        </div>
     </td>
     <td >Total:</td>
    <td align="right"><?php echo $total_quantity; ?></td>
    <td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
   </td>
   </tr>
       
    <?php
    }
    ?>
        
   <!-- END of cart table -->
</html>

