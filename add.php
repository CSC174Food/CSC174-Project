<?php
    $name = $email = $street = $state =$phone =$zip =$city = '';
    $errors =array('name'=>'', 'phone'=>'', 'street'=>'', 'city'=>'','state'=>'','zip'=>'','email'=>'');


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
           if(empty($_POST['phone'])){
            $errors['phone']= 'Phone number is required <br />';
        }
        else {
            $phone = $_POST['phone'];
            if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone))
            {
                $errors['phone']= 'phone number must be valid address';
            }
        }
        
           // check Street
           if(empty($_POST['street'])){
            $errors['street']= 'A Street is required <br />';
        }
        else {
            $street = $_POST['street'];
         //   if(!preg_match('/^[a-zA-Z\s]+$/', $street))
          //  {
           //     $errors['street']= 'Street must be valid address';
            //}
        }

           // check city
           if(empty($_POST['city'])){
            $errors['city']= 'A city is required <br />';
        }
        else {
            $city = $_POST['city'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $city))
            {
                $errors['city']= 'City must be valid address';
            }
        }

        
           // check state
           if(empty($_POST['state'])){
            $errors['state']= 'A state is required <br />';
        }
        else {
            $state = $_POST['state'];
            if(!preg_match('/^[a-zA-Z]{2}$/', $state))
            {
                $errors['state']= 'State must be valid address';
            }
        }

             // check zip
           if(empty($_POST['zip'])){
            $errors['zip']= 'Zip code is required <br />';
        }
        else {
            $zip = $_POST['zip'];
            if(!preg_match("/^[0-9]{5}$/", $zip))
            {
                $errors['zip']= 'zip code must be valid address';
            }
        }

        //check email
        if(empty($_POST['email'])){
            $errors['email'] = 'An email is required <br />';
        }
        else {
            $email = $_POST['email'];
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $errors['email'] = 'Email must be valid address';
            }
        }

        //error checking
       if(array_filter($errors)){
        //echo 'there are errrors';
       }
       else{

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $street = mysqli_real_escape_string($conn, $_POST['street']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $state = mysqli_real_escape_string($conn, $_POST['state']);
        $zip = mysqli_real_escape_string($conn, $_POST['zip']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        //create sql

        $sql ="INSERT INTO customer (name, phone, street, city, state, zip, email)
        VALUES('$name', '$phone','$street','$city','$state','$zip','$email')";

        if(mysqli_query($conn, $sql)){
            header('Location: index.php');
        }
        else{
            echo'query error: '. mysqli_error($conn);
        }
       }
       
    }

?>

<!DOCTYPE html>
<html >

    <?php include('templates\header.php'); ?>

<!-----------    Contains information about the customer   -------------------->
    <section class="container grey-text">
        <h4 class="center"> New customer</h4> 
        <form class="white" action="add.php" method="POST">
      
        <label for="">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name)?>">
        <div class=red-text><?php echo $errors['name']; ?></div>

        <label for="">phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone) ?>">
        <div class=red-text><?php echo $errors['phone']; ?></div>

        <label for="">street:</label> 
        <input type="text" name="street" value="<?php echo htmlspecialchars($street) ?>">
        <div class=red-text><?php echo $errors['street']; ?></div>
        
        <label for="">city:</label>
        <input type="text" name="city" value="<?php echo htmlspecialchars($city) ?>">
        <div class=red-text><?php echo $errors['city']; ?></div>

        <label for="">state:</label>
        <input type="text" name="state" value="<?php echo htmlspecialchars($state) ?>">
        <div class=red-text><?php echo $errors['state']; ?></div>

        <label for="">zip:</label>
        <input type="text" name="zip" value="<?php echo htmlspecialchars($zip) ?>">
        <div class=red-text><?php echo $errors['zip']; ?></div>
        
        <label for="">Your Email:</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($email) ?>">
        <div class=red-text><?php echo $errors['email']; ?></div>
        <div class="center">
            <!-- name="submit" is sent to $_GET to check if the button has been press-->
            <input type="submit" name="submit" value="submit" class="btn">
        </div>
        </form>
    </section>
 <!-----------    END information about the customer   -------------------->

    <?php include('templates\footer.php'); ?>
</html>

