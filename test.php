<?php


include('config/db_con.php');
date_default_timezone_set('America/Los_Angeles');

$time = date("Y-m-d H:i:s", strtotime("now"));

//$sql ="INSERT INTO test values ('$time')";


if(isset($_GET['submit'])){
    $radio = $_GET['order_type'];
    if($radio==='d'){
        echo 'deliever';
    }
    elseif($radio==='p'){
        echo 'pick up';
    }
    else{

        echo 'wrong ==';
    }
    echo $radio;
}

?>

<!DOCTYPE html>
<html >
<section class="container grey-text">
        <h4 class="center"> Payment Order</h4> 
        <form class="white" action="test.php" method="GET">
      
        <p>
            <label>
                <input name="order_type" type="radio" value="d"  />
                <span>deliever</span>
            </label>
        </p>

        <p>
            <label>
                <input name="order_type" type="radio" value="p" />
                <span>pick up</span>
            </label>
        </p>
        
        <div class="center">
            <!-- name="submit" is sent to $_GET to check if the button has been press-->
            <input type="submit" name="submit" value="submit" class="btn">
        </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>
</html>
