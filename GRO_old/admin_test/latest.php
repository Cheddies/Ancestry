<?php

require_once("include/siteconstants.php");

function getData($query) {
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	// Performing SQL query
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	$output = "";

	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$output = $output . $line['numorders'];
		$output = $output . " &pound;" . $line['paid'];
	}

	return $output;
}

$zero  = date("Y-m-d");
$one   = date("Y-m-d",strtotime("-1 days"));
$two   = date("Y-m-d",strtotime("-2 days"));
$three = date("Y-m-d",strtotime("-3 days"));
$four  = date("Y-m-d",strtotime("-4 days"));
$five  = date("Y-m-d",strtotime("-5 days"));
$six   = date("Y-m-d",strtotime("-6 days"));

$query0 = "SELECT count(GRO_orders_id) as numorders, SUM(total_paid) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $zero . "' GROUP BY order_date;";
$query1 = "SELECT count(GRO_orders_id) as numorders, SUM(total_paid) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $one . "' GROUP BY order_date;";
$query2 = "SELECT count(GRO_orders_id) as numorders, SUM(total_paid) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $two . "' GROUP BY order_date;";
$query3 = "SELECT count(GRO_orders_id) as numorders, SUM(total_paid) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $three . "' GROUP BY order_date;";
$query4 = "SELECT count(GRO_orders_id) as numorders, SUM(total_paid) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $four . "' GROUP BY order_date;";
$query5 = "SELECT count(GRO_orders_id) as numorders, SUM(total_paid) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $five . "' GROUP BY order_date;";
$query6 = "SELECT count(GRO_orders_id) as numorders, SUM(total_paid) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $six . "' GROUP BY order_date;";

echo "0: ". getData($query0) . "<br />";
echo "1: ". getData($query1) . "<br />";
echo "2: ". getData($query2) . "<br />";
echo "3: ". getData($query3) . "<br />";
echo "4: ". getData($query4) . "<br />";
echo "5: ". getData($query5) . "<br />";
echo "6: ". getData($query6);


?>