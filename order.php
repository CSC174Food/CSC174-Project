<?php
    session_start();
    include('config/db_con.php');
    date_default_timezone_set('America/Los_Angeles');
    

      echo $_SESSION['pid'];
      $c_id =$_SESSION['pid'];
      $total= $_SESSION['total'];

    $name = $number = $expire = '';
    $errors =array('name'=>'', 'number'=>'', 'expire'=>'');


    include('config/db_con.php');

    if(isset($_POST['submit'])){
        // check name
        if(empty($_POST['name'])){
            $errors['name']= 'A name is required <br />';
        }
        else {
            $name = $_POST['name'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $name))
            {
                $errors['name']= 'Name must be valid address';
            }
        }

           // check phone
           if(empty($_POST['number'])){
            $errors['number']= 'Card number is required <br />';
        }
        else {
            $number = $_POST['number'];
            if(!preg_match("/^[0-9]{16}$/", $number))
            {
                $errors['number']= 'card number must be valid 16 digit number';
            }
        }
        
           // check expire
           if(empty($_POST['expire'])){
            $errors['expire']= 'Card expiration date is required <br />';
        }
        else {
            $expire = $_POST['expire'];
            if(!preg_match("/^[0-9]{4}$/", $expire))
            {
                $errors['expire']= 'date  must be valid';
            }
        }


        //error checking
       if(array_filter($errors)){
        //echo 'there are errrors';
       }
       else{

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $expire = mysqli_real_escape_string($conn, $_POST['expire']);
        $radio = mysqli_real_escape_string($conn, $_POST['order_type']);
        $time = date("Y-m-d H:i:s", strtotime("now"));
        $new_time = date("Y-m-d H:i:s", strtotime("30 minutes"));
        
        //create sql
        if($radio === 'p'){
            $insert ="INSERT INTO p_order (payment_name, card_number, expire_date, purchase_date, customer_id, total_price, order_type, pickup_time)
            VALUES('$name', '$number','$expire','$time','$c_id','$total', '$radio', '$new_time')";
        } 
        elseif($radio==='d'){
            $insert ="INSERT INTO p_order (payment_name, card_number, expire_date, purchase_date, customer_id, total_price, order_type, estimate_arrival)
            VALUES('$name', '$number','$expire','$time','$c_id','$total', '$radio', '$new_time')";
        }
       

        if(mysqli_query($conn, $insert)){

            $last_id = mysqli_insert_id($conn);
            echo "New record created successfully. Last inserted ID is: " . $last_id;
            $_SESSION['total'] = $last_id;
            header('Location: receipt.php');
        }
        else{
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            echo'query error: '. mysqli_error($conn);
        }
       }
       
    }

?>

<!DOCTYPE html>
<html >

    <?php include('templates/header.php'); ?>

<!-----------    Contains information about the customer   -------------------->
    <section class="container grey-text">
        <h4 class="center"> Payment Order</h4> 
        <form class="white" action="order.php" method="POST">
      
        <label for="">Name on card:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name)?>">
        <div class=red-text><?php echo $errors['name']; ?></div>

        <label for="">card number:</label>
        <input type="text" name="number" value="<?php echo htmlspecialchars($number) ?>">
        <div class=red-text><?php echo $errors['number']; ?></div>

        <label for="">expire date:</label> 
        <input type="text" name="expire" value="<?php echo htmlspecialchars($expire) ?>">
        <div class=red-text><?php echo $errors['expire']; ?></div>

        <label for="">Please choose order type</label> 
        <p>
            <label>
                <input name="order_type" type="radio" value ="d"  />
                <span>deliever</span>
            </label>
        </p>

        <p>
            <label>
                <input name="order_type" type="radio" value="p" checked />
                <span>pick up</span>
            </label>
        </p>
        
        
        <div class="center">
            <!-- name="submit" is sent to $_GET to check if the button has been press-->
            <input type="submit" name="submit" value="submit" class="btn">
        </div>
        </form>
    </section>
 <!-----------    END information about the customer   -------------------->

    <?php include('templates/footer.php'); ?>
</html>

