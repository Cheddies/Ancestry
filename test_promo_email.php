<?php include("include/siteconstants.php"); ?>
<?php include("include/commonfunctions.php"); ?>
<?php include("include/promo_email_functions.php"); 

$clean['niceodrnum']='1000011';
$clean['promo_type']='FREEUPGRADE';

promo_email($clean['niceodrnum'],$clean['promo_type']);


?>