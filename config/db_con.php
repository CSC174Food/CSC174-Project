<?php
//                     URL connection                      Username           password   database
   $conn = mysqli_connect('us-cdbr-iron-east-01.cleardb.net', 'ba0d9295eb8228', '260c111a','heroku_fea7079ade0abaf');

   //check connection
   if(!$conn){
        echo 'Connection error:'. mysqli_connect_error();
   }
?>