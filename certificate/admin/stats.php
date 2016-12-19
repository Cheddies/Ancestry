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


//total completed scan and send
$total_4=0;
$base_join="LEFT JOIN GRO_certificate_ordered ON GRO_certificate_ordered.order_number=GRO_orders.order_number";
$base_where=array("scan_and_send>0","authorised=1","order_status=3");

$birth_join=$base_join .  " LEFT JOIN GRO_birth_certificates ON certificate_id=GRO_birth_certificates_id";
$marriage_join=$base_join .  " LEFT JOIN GRO_marriage_certificates ON certificate_id=GRO_marriage_certificates_id";
$death_join=$base_join .  " LEFT JOIN GRO_death_certificates ON certificate_id=GRO_death_certificates_id";

$birth_where=array_merge($base_where,array("certificate_type=1"));
$marriage_where=array_merge($base_where,array("certificate_type=2"));
$death_where=array_merge($base_where,array("certificate_type=3"));

$scan_and_send_birth=$DB->getData("GRO_orders",$fields,$birth_where,"",$birth_join,"",false);
$total_4=$total_4 + $scan_and_send_birth[0][0];

$scan_and_send_marriage=$DB->getData("GRO_orders",$fields,$marriage_where,"",$marriage_join,"",false);
$total_4=$total_4 + $scan_and_send_marriage[0][0];

$scan_and_send_death=$DB->getData("GRO_orders",$fields,$death_where,"",$death_join,"",false);
$total_4=$total_4 + $scan_and_send_death[0][0];


//total scan and send (any status)
$total_5=0;
$base_where=array("scan_and_send>0","authorised=1");
$birth_where=array_merge($base_where,array("certificate_type=1"));
$marriage_where=array_merge($base_where,array("certificate_type=2"));
$death_where=array_merge($base_where,array("certificate_type=3"));

$scan_and_send_birth=$DB->getData("GRO_orders",$fields,$birth_where,"",$birth_join,"",false);
$total_5=$total_5 + $scan_and_send_birth[0][0];

$scan_and_send_marriage=$DB->getData("GRO_orders",$fields,$marriage_where,"",$marriage_join,"",false);
$total_5=$total_5 + $scan_and_send_marriage[0][0];

$scan_and_send_death=$DB->getData("GRO_orders",$fields,$death_where,"",$death_join,"",false);
$total_5=$total_5 + $scan_and_send_death[0][0];

//total Australia (Any Status)
$total_6=0;
$where=array("authorised=1","country=012");
$join="LEFT JOIN GRO_addresses ON GRO_addresses_id=shipping_address";
$total_orders=$DB->getData("GRO_orders",$fields,$where,"",$join,"",false);
$total_6=$total_orders[0][0];



//total Aus Scan and Send
$total_7=0;
$base_where=array("authorised=1","country=012","scan_and_send>0");
$birth_where=array_merge($base_where,array("certificate_type=1"));
$marriage_where=array_merge($base_where,array("certificate_type=2"));
$death_where=array_merge($base_where,array("certificate_type=3"));

$base_join=$base_join . " LEFT JOIN GRO_addresses ON GRO_addresses_id=shipping_address";

$birth_join=$base_join .  " LEFT JOIN GRO_birth_certificates ON certificate_id=GRO_birth_certificates_id";
$marriage_join=$base_join .  " LEFT JOIN GRO_marriage_certificates ON certificate_id=GRO_marriage_certificates_id";
$death_join=$base_join .  " LEFT JOIN GRO_death_certificates ON certificate_id=GRO_death_certificates_id";

$scan_and_send_birth=$DB->getData("GRO_orders",$fields,$birth_where,"",$birth_join,"",false);
$total_7=$total_7 + $scan_and_send_birth[0][0];

$scan_and_send_marriage=$DB->getData("GRO_orders",$fields,$marriage_where,"",$marriage_join,"",false);
$total_7=$total_7 + $scan_and_send_marriage[0][0];

$scan_and_send_death=$DB->getData("GRO_orders",$fields,$death_where,"",$death_join,"",false);
$total_7=$total_7 + $scan_and_send_death[0][0];

//total US (Any Status)
$total_8=0;
$where=array("authorised=1","country=001");
$join="LEFT JOIN GRO_addresses ON GRO_addresses_id=shipping_address";
$total_orders=$DB->getData("GRO_orders",$fields,$where,"",$join,"",false);
$total_8=$total_orders[0][0];



//total US Scan and Send
$total_9=0;
$base_where=array("authorised=1","country=001","scan_and_send>0");
$birth_where=array_merge($base_where,array("certificate_type=1"));
$marriage_where=array_merge($base_where,array("certificate_type=2"));
$death_where=array_merge($base_where,array("certificate_type=3"));

//allready done in AUS query
//$base_join=$base_join . " LEFT JOIN GRO_addresses ON GRO_addresses_id=shipping_address";

$birth_join=$base_join .  " LEFT JOIN GRO_birth_certificates ON certificate_id=GRO_birth_certificates_id";
$marriage_join=$base_join .  " LEFT JOIN GRO_marriage_certificates ON certificate_id=GRO_marriage_certificates_id";
$death_join=$base_join .  " LEFT JOIN GRO_death_certificates ON certificate_id=GRO_death_certificates_id";

$scan_and_send_birth=$DB->getData("GRO_orders",$fields,$birth_where,"",$birth_join,"",false);
$total_9=$total_9 + $scan_and_send_birth[0][0];

$scan_and_send_marriage=$DB->getData("GRO_orders",$fields,$marriage_where,"",$marriage_join,"",false);
$total_9=$total_9 + $scan_and_send_marriage[0][0];

$scan_and_send_death=$DB->getData("GRO_orders",$fields,$death_where,"",$death_join,"",false);
$total_9=$total_9 + $scan_and_send_death[0][0];


//total CA (Any Status)
$total_10=0;
$where=array("authorised=1","country=034");
$join="LEFT JOIN GRO_addresses ON GRO_addresses_id=shipping_address";
$total_orders=$DB->getData("GRO_orders",$fields,$where,"",$join,"",false);
$total_10=$total_orders[0][0];



//total CA Scan and Send
$total_11=0;
$base_where=array("authorised=1","country=034","scan_and_send>0");
$birth_where=array_merge($base_where,array("certificate_type=1"));
$marriage_where=array_merge($base_where,array("certificate_type=2"));
$death_where=array_merge($base_where,array("certificate_type=3"));

$birth_join=$base_join .  " LEFT JOIN GRO_birth_certificates ON certificate_id=GRO_birth_certificates_id";
$marriage_join=$base_join .  " LEFT JOIN GRO_marriage_certificates ON certificate_id=GRO_marriage_certificates_id";
$death_join=$base_join .  " LEFT JOIN GRO_death_certificates ON certificate_id=GRO_death_certificates_id";

$scan_and_send_birth=$DB->getData("GRO_orders",$fields,$birth_where,"",$birth_join,"",false);
$total_11=$total_11 + $scan_and_send_birth[0][0];

$scan_and_send_marriage=$DB->getData("GRO_orders",$fields,$marriage_where,"",$marriage_join,"",false);
$total_11=$total_11 + $scan_and_send_marriage[0][0];

$scan_and_send_death=$DB->getData("GRO_orders",$fields,$death_where,"",$death_join,"",false);
$total_11=$total_11 + $scan_and_send_death[0][0];


$figures=array($total,$total_6,$total_8,$total_10,$total_5,$total_7,$total_9,$total_11,$total_1,$total_2,$total_3,$total_4);
$labels=array("Total Orders","Total Australian Orders","Total US Orders","Total CA Orders","Total Scan and Send Orders","Total Australian Scan and Send Orders","Total US Scan and Send Orders","Total CA Scan and Send Orders","Total Awaiting Proccessing by IL","Total Pending with GRO","Total Completed Orders","Total Completed Scan and Send");


?>
<div id="admin_page">
<h2>Order Stats</h2>
<p>
<table cellpadding = "3" style="width:40%">
<?php 
	$count=0;
	foreach ($labels as $label)
	{
		
	?>
		<tr class="<?php if($count%2==0) echo "odd"; else echo "even";?>">
			<td style="width:75%"><strong><?php echo $label?></strong></td>
			<td style="width:25%; text-align:center;" ><?php echo $figures[$count]?></td>
		</tr>
	<?php 
		$count++;
	}
?>
</table>
</p>
</div>

<?php
include ('include/footer.php');
?>