<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");
include ('include/header.php');

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);

$fields=array("count(GRO_orders_id)");
$where=array("authorised=1");

$total_orders=$DB->getData("GRO_orders",$fields,$where,"","",$order_by);
$total=$total_orders[0][0];

$where=array("authorised=1","order_status=1");
$total_orders=$DB->getData("GRO_orders",$fields,$where,"","",$order_by);
$total_1=$total_orders[0][0];


$where=array("authorised=1","order_status=2");
$total_orders=$DB->getData("GRO_orders",$fields,$where,"","",$order_by);
$total_2=$total_orders[0][0];

$where=array("authorised=1","order_status=3");
$total_orders=$DB->getData("GRO_orders",$fields,$where,"","",$order_by);
$total_3=$total_orders[0][0];
?>
<div id="admin_page">
<h2>Order Stats</h2>
<p>
Total Orders: <strong><?php echo $total; ?></strong><br />
Total Awaiting Proccessing by IL: <strong><?php echo $total_1; ?></strong><br />
Total Pending with GRO: <strong><?php echo  $total_2; ?></strong><br />
Total Completed Orders: <strong><?php echo  $total_3; ?></strong><br />

</p>
</div>

<?php
include ('include/footer.php');
?>