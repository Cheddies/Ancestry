<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");

//check for user level
if(!isset($_SESSION['user_level']) || $_SESSION['user_level']!=ADMIN_USER)
{
	header('location:index.php');
	exit();
}

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();


$clean=array();
$error=false;
$query_string="error=1";
$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$errors=array();
$_SESSION['errors']="";
	

$form_fields=array( array ("name" => "order_id","display_name" => "Order ID","length"=>"6","reg_ex"=>"","required"=>true));
	
$errors = process_form($form_fields,$_GET,&$clean);
	
if(sizeof($errors)>0)
{
	$_SESSION['errors']=serialize($errors);
	header('location:index.php');
	exit();
}
else
{
	include ('include/header.php');

	$fields=array("GRO_order_events_id","time","event","order_id","user_id");
	$where=array("order_id={$clean['order_id']}");
	$order_events=$DB->getData("GRO_order_events",$fields,$where,"","","");
	
	
?>
<div id="admin_page">
<h2>Order History - Order number - <?php echo $clean['order_id']?></h2>

<?php if($order_events)
{
?>

	<table>
	<th>Date</th><th>Time</th><th>Event</th><th>User Id</th>
	<?php
	foreach ($order_events as $event) 
	{
		$count++;
	?>
		<tr class="<?php if($count%2==0) echo "odd"; else echo "even";?>">
		<td><?php echo date("d/m/Y",strtotime($event['time']))?></td>
		<td><?php echo date("H:i:s",strtotime($event['time']))?></td>
		<td><?php echo descriptive_event($event['event'])?></td>
		<td><?php echo $event['user_id']?></td>
		</tr>
	<?php
	}
	?>
	</table>
<?php
}
else
{
	echo "No Order History";
}
?>
</div>

<?php
}
include ('include/footer.php');


?>