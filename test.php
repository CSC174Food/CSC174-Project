<?php


date_default_timezone_set('America/Los_Angeles');

echo date("H:i:sa", strtotime("now")) . "\n";
echo date("H:i:sa", strtotime("+30 minutes"));

?>
