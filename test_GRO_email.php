<?php include("include/siteconstants.php"); ?>
<?php include("include/commonfunctions.php"); ?>
<?php include("include/GRO_email_functions.php"); 

$clean['ordernumber']="23025eaccaca4a226998f91f5ecdc66a";
$clean['niceodrnum']="114807";

GRO_email($clean['ordernumber'],$clean['niceodrnum']);


?>