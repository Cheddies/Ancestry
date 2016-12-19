<?php
require_once("include/siteconstants.php");

function outputToFile($sql, $fields, $filename) {
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
	mysql_select_db(DB_NAME);

	$db_result = mysql_query($sql, $link);

	$out = '';

	$columns = sizeof($fields);

	// Put the name of all fields
	for ($i = 0; $i < $columns; $i++) {
		$l = $fields[$i];
		$out .= '"'.$l.'",';
	}
	
	$out .="\n";

	// Add all values in the table
	while ($l = mysql_fetch_array($db_result, MYSQL_NUM)) {
		for ($i = 0; $i < $columns; $i++) {
			$out .='"'.$l["$i"].'",';
		}
		$out .="\n";
	}
	
	// Output to browser with appropriate mime type, you choose ;)
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=" . $filename);
	echo $out;
	exit;
	

}

if (isset($_POST['reporttype'])) {
	
	if ($_POST['reporttype'] == 'groplaced') {
	
		$start_day   = $_POST['GROPlaceStartDay'];
		$start_month = $_POST['GROPlaceStartMonth'];
		$start_year  = $_POST['GROPlaceStartYear'];
		
		$start_date = $start_year . "-" . $start_month . "-" . $start_day;
		
		$end_day   = $_POST['GROPlaceEndDay'];
		$end_month = $_POST['GROPlaceEndMonth'];
		$end_year  = $_POST['GROPlaceEndYear'];
		
		$end_date = $end_year . "-" . $end_month . "-" . $end_day . " 23:59:59";
		
		$sql      = "SELECT GRO_orders_id, order_number, billing_address, shipping_address, order_date, authorised, delivery_method, phone, st_ref, st_autho, total_paid, nomail, norent, noemail, token, token_time, order_status, discount, discount_code FROM GRO_orders WHERE order_date >= '" . $start_date . "' AND order_date <= '" . $end_date . "' AND authorised = 1;";
		$fields   = array('GRO_orders_id', 'order_number', 'billing_address', 'shipping_address', 'order_date', 'authorised', 'delivery_method', 'phone', 'st_ref', 'st_autho', 'total_paid', 'nomail', 'norent', 'noemail', 'token', 'token_time', 'order_status', 'discount', 'discount_code');
		$filename = "gro_placed.csv";
		
		outputToFile($sql, $fields, $filename);	
	}
	
	if ($_POST['reporttype'] == 'grocompleted') {
		$start_day   = $_POST['GROCompletedStartDay'];
		$start_month = $_POST['GROCompletedStartMonth'];
		$start_year  = $_POST['GROCompletedStartYear'];
		
		$start_date = $start_year . "-" . $start_month . "-" . $start_day;
		
		$end_day   = $_POST['GROCompletedEndDay'];
		$end_month = $_POST['GROCompletedEndMonth'];
		$end_year  = $_POST['GROCompletedEndYear'];
		
		$end_date = $end_year . "-" . $end_month . "-" . $end_day . " 23:59:59";
		
		$sql      = "SELECT GRO_order_events.GRO_order_events_id, GRO_order_events.time, GRO_order_events.event, GRO_order_events.order_id, GRO_order_events.user_id, GRO_orders.order_date, GRO_orders.total_paid, GRO_orders.token_time, GRO_orders.delivery_method FROM GRO_order_events JOIN GRO_orders ON GRO_order_events.order_id = GRO_orders.GRO_orders_id WHERE time >= '" . $start_date . "' AND time <= '" . $end_date . "' AND event = 3;";
		$fields   = array('GRO_order_events_id', 'time', 'event', 'order_id', 'user_id', 'order_date', 'total_paid', 'token_time', 'delivery_method');
		$filename = "gro_completed.csv";
		
		outputToFile($sql, $fields, $filename);		
	}

	if ($_POST['reporttype'] == 'grocancelled') {
		$start_day   = $_POST['GROCancelledStartDay'];
		$start_month = $_POST['GROCancelledStartMonth'];
		$start_year  = $_POST['GROCancelledStartYear'];
		
		$start_date = $start_year . "-" . $start_month . "-" . $start_day;
		
		$end_day   = $_POST['GROCancelledEndDay'];
		$end_month = $_POST['GROCancelledEndMonth'];
		$end_year  = $_POST['GROCancelledEndYear'];
		
		$end_date = $end_year . "-" . $end_month . "-" . $end_day . " 23:59:59";
		
		$sql      = "SELECT GRO_order_events.GRO_order_events_id, GRO_order_events.time, GRO_order_events.event, GRO_order_events.order_id, GRO_order_events.user_id, GRO_orders.order_date, GRO_orders.delivery_method FROM GRO_order_events JOIN GRO_orders ON GRO_order_events.order_id = GRO_orders.GRO_orders_id WHERE time >= '" . $start_date . "' AND time <= '" . $end_date . "' AND event = 4;";
		$fields   = array('GRO_order_events_id', 'time', 'event', 'order_id', 'user_id', 'order_date', 'delivery_method');
		$filename = "gro_cancelled.csv";
		
		outputToFile($sql, $fields, $filename);		
	}

	if ($_POST['reporttype'] == 'grooutstanding') {
		
		$sql      = "SELECT GRO_orders_id, order_number, order_date, order_status FROM GRO_orders WHERE authorised = 1 AND (order_status = 1 OR order_status = 2);";
		$fields   = array('GRO_orders_id', 'order_number', 'order_date', 'order_status');
		$filename = "gro_outstanding.csv";

		outputToFile($sql, $fields, $filename);
	}

	if ($_POST['reporttype'] == 'groall') {
		
		$sql = "SELECT GRO_orders_id, order_number, order_date, order_status FROM GRO_orders WHERE GRO_orders_id > 250000 AND authorised = 1;";
		$fields = array('GRO_orders_id', 'order_number', 'order_date', 'order_status');
		$filename = "gro_all_orders.csv";
		
		outputToFile($sql, $fields, $filename);
	}
	
		
	
}

?>