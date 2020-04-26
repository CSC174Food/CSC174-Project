<?php

    $errors =array('name'=>'', 'phone'=>'', 'steet'=>'', 'city'=>'','state'=>'','zip'=>'','email'=>'');



    include('config/db_con.php');

    if(isset($_POST['submit'])){
        if(empty($_POST['name'])){
            $errors['name'] ='Name is required <br />';
        }
        else {
            $name = $_POST['name'];
           //if(filter_var($name, FILTER_VALI)) 
        }

        if(empty($_POST['phone'])){
            $errors['phone'] ='phone number is required <br />';
        }
        else {
            $name = $_POST['phone'];
           //if(filter_var($name, FILTER_VALI)) 
        }

        if(empty($_POST['street'])){
            $errors['street'] ='street is required <br />';
        }
        else {
            $name = $_POST['street'];
           //if(filter_var($name, FILTER_VALI)) 
        }

        if(empty($_POST['city'])){
            $errors['city'] ='city is required <br />';
        }
        else {
            $name = $_POST['city'];
           //if(filter_var($name, FILTER_VALI)) 
        }

        if(empty($_POST['state'])){
            $errors['state'] ='state is required <br />';
        }
        else {
            $name = $_POST['state'];
           //if(filter_var($name, FILTER_VALI)) 
        }

        if(empty($_POST['zip'])){
            $errors['zip'] ='zip is required <br />';
        }
        else {
            $name = $_POST['zip'];
           //if(filter_var($name, FILTER_VALI)) 
        }

        if(empty($_POST['email'])){
            $errors['email'] ='email is required <br />';
        }
        else {
            $name = $_POST['email'];
           //if(filter_var($name, FILTER_VALI)) 
        }
       
       
    }
    if(array_filter($errors)){

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

        //save to DB
        if(mysqli_query($conn, $sql)){
            header('Location: index.php');
        }
        else{
            echo 'error:' . mysqli_error($conn);
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
        <input type="text" name="name">
        
        <label for="">phone:</label>
        <input type="text" name="phone">

        <label for="">street:</label>
        <input type="text" name="street">

        
        <label for="">city:</label>
        <input type="text" name="city">

        <label for="">state:</label>
        <input type="text" name="state">

        <label for="">zip:</label>
        <input type="text" name="zip">

        <label for="">Your Email:</label>
        <input type="text" name="email">

        <div class="center">
            <!-- name="submit" is sent to $_GET to check if the button has been press-->
            <input type="submit" name="submit" value="submit" class="btn">
        </div>
        </form>
    </section>
 <!-----------    END information about the customer   -------------------->

    <?php include('templates\footer.php'); ?>
</html>

