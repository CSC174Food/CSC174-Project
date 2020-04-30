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
            $item= "SELECT p.product_name, p.price, c.item_quantity, p.photo
                    FROM cart as c JOIN product as p 
                    WHERE c.product_id = p.pid and  cid='$c_id'";
            $cart = mysqli_query($conn,$item);
            $shopping_cart = mysqli_fetch_array($cart, MYSQLI_ASSOC);
            $_SESSION['cart']=true;
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
    if(isset($_SESSION["cart"])){
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
  
    <?php foreach($shopping_cart as $shop): 
        $item_price = $item['quantity']*$item['price'];
    ?>
        <tr>
        <td><img src="<?php echo $shop["photo"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
            <td style="text-align:right;"><?php echo $shop["quantity"]; ?></td>
            <td  style="text-align:right;"><?php echo "$ ".$shop["price"]; ?></td>
            <td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
            <td style="text-align:center;"><a href="index.php?action=remove&code=<?php echo $item["cart_id"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
            
        </tr>
   <?php endforeach;?>

    <?php
    }
    ?>
   <!-- END of cart table -->

    <?php include('templates/footer.php'); ?>
</html>

