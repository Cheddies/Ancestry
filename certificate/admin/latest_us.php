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
		$output = $output . " &#36;" . $line['paid'];
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
$seven = date("Y-m-d",strtotime("-7 days"));
$eight = date("Y-m-d",strtotime("-8 days"));
$nine = date("Y-m-d",strtotime("-9 days"));
$ten = date("Y-m-d",strtotime("-10 days"));
$eleven = date("Y-m-d",strtotime("-11 days"));
$twelve = date("Y-m-d",strtotime("-12 days"));

$query0 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $zero . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query1 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $one . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query2 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $two . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query3 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $three . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query4 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $four . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query5 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $five . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query6 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $six . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query7 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $seven . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query8 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $eight . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query9 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $nine . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query10 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $ten . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query11 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $eleven . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";
$query12 = "SELECT count(GRO_orders_id) as numorders, round(SUM(total_paid),2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $twelve . "' and delivery_method in (7,8,9,10) GROUP BY order_date;";

echo "0: ". getData($query0) . "<br />";
echo "1: ". getData($query1) . "<br />";
echo "2: ". getData($query2) . "<br />";
echo "3: ". getData($query3) . "<br />";
echo "4: ". getData($query4) . "<br />";
echo "5: ". getData($query5) . "<br />";
echo "6: ". getData($query6) . "<br />";
echo "7: ". getData($query7) . "<br />";
echo "8: ". getData($query8) . "<br />";
echo "9: ". getData($query9) . "<br />";
echo "10: ". getData($query10) . "<br />";
echo "11: ". getData($query11) . "<br />";
echo "12: ". getData($query12);

?>