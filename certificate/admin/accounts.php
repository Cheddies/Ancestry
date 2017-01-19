<?php 

include "logincheck.php";

function selectCreator($selectname, $maxnum) {

	$start_statement = "<select name=\"" . $selectname . "\">";

	$options = "";

	for ( $counter = 1; $counter <= $maxnum; $counter += 1) {
		$options = $options . "<option>" . $counter . "</option>";
	}

	$end_statement = "</select>";

	$complete_statement = $start_statement . $options . $end_statement;

	return $complete_statement;
}

function selectYearCreator($selectname) {

	$start_statement = "<select name=\"" . $selectname . "\">";

	$options = "";

	$options = $options . "<option>2007</option>";
	$options = $options . "<option>2008</option>";
	$options = $options . "<option>2009</option>";
	$options = $options . "<option>2010</option>";
	$options = $options . "<option>2011</option>";
	$options = $options . "<option>2012</option>";
	$options = $options . "<option>2013</option>";
	$options = $options . "<option>2014</option>";
	$options = $options . "<option>2015</option>";
	$options = $options . "<option>2016</option>";
	$options = $options . "<option>2017</option>";
	$options = $options . "<option>2018</option>";
	
	$end_statement = "</select>";

	$complete_statement = $start_statement . $options . $end_statement;

	return $complete_statement;
}

function dateSelector($dayname, $monthname, $yearname) {

	$day   = selectCreator($dayname, 31);
	$month = selectCreator($monthname, 12);
	$year  = selectYearCreator($yearname);
	
	$dateselector = $day . $month . $year;

	return $dateselector;
}

function getData($query) {
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	// Performing SQL query
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	$output = "<td>";

	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$output = $output . $line['numorders'];
		$output = $output . "</td><td>";
		$output = $output . " &pound;" . $line['paid'] . "</td>";
	}

	return $output;
}

function selectScanFromMySQL($sql) {
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	// Performing SQL query
	$result = mysql_query($sql) or die('Query failed: ' . mysql_error());

	$number = 0;

	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$number = $line['quantity'];
	}

	return $number;
}

function getScanData($shipping_methods, $the_date) {

	$types = array('birth', 'marriage', 'death');
	
	$total_quantity = 0;
	
	foreach ($types as $eachtype) {
	
		$sql = "SELECT COUNT(GRO_{$eachtype}_certificates_id) as quantity FROM GRO_{$eachtype}_certificates JOIN GRO_orders ON GRO_{$eachtype}_certificates.order_number = GRO_orders.order_number WHERE GRO_{$eachtype}_certificates.scan_and_send <> 0 AND GRO_orders.authorised = 1 AND GRO_orders.delivery_method in {$shipping_methods} AND GRO_orders.order_date = '{$the_date}' ";
			
		$total_quantity = $total_quantity + selectScanFromMySQL($sql);
	}		
	return "<td>" . $total_quantity . "</td>";

}

function getSTRefData($strefquery) {
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	// Performing SQL query
	$result = mysql_query($strefquery) or die('Query failed: ' . mysql_error());

	$output = "<td>";

	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$output = $output . $line['dupstref'];

	}

	return $output;
}

?>

<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");
include ('include/header.php');

if (isset($_POST['reporttype'])) {

	$the_day   = $_POST['GRODailyStartDay'];
	$the_month = $_POST['GRODailyStartMonth'];
	$the_year  = $_POST['GRODailyStartYear'];
		
	$the_date = $the_year . "-" . $the_month . "-" . $the_day;

}

?>

<div id="admin_page">
<h2>Accounts Reports</h2>
<p>
<table cellpadding = "3" style="width:80%">
	<tr>
		<th style="width:20%">Report</th>
		<th style="width:30%">Start Date</th>
		<th style="width:30%">End Date</th>
		<th style="width:20%">Generate Report</th>
	</tr>
	<form name="groplaced" action="data_output.php" method="post">
		<input type="hidden" name="reporttype" value="groplaced" />
		<tr class="odd">
			<td style="width:20%">GRO Placed</td>
			<td style="width:30%"><?php echo dateSelector("GROPlaceStartDay", "GROPlaceStartMonth", "GROPlaceStartYear"); ?></td>
			<td style="width:30%"><?php echo dateSelector("GROPlaceEndDay", "GROPlaceEndMonth", "GROPlaceEndYear"); ?></td>
			<td style="width:20%"><input type="submit" name="Generate" value="Generate"></td>
		</tr>
	</form>
	<form name="grocompleted" action="data_output.php" method="post">
		<input type="hidden" name="reporttype" value="grocompleted" />
		<tr class="even">
			<td style="width:20%">GRO Completed</td>
			<td style="width:30%"><?php echo dateSelector("GROCompletedStartDay", "GROCompletedStartMonth", "GROCompletedStartYear"); ?></td>
			<td style="width:30%"><?php echo dateSelector("GROCompletedEndDay", "GROCompletedEndMonth", "GROCompletedEndYear"); ?></td>
			<td style="width:20%"><input type="submit" name="Generate" value="Generate"></td>
		</tr>
	</form>
	<form name="grocompleted" action="data_output.php" method="post">
		<input type="hidden" name="reporttype" value="grocancelled" />
		<tr class="odd">
			<td style="width:20%">GRO Cancelled</td>
			<td style="width:30%"><?php echo dateSelector("GROCancelledStartDay", "GROCancelledStartMonth", "GROCancelledStartYear"); ?></td>
			<td style="width:30%"><?php echo dateSelector("GROCancelledEndDay", "GROCancelledEndMonth", "GROCancelledEndYear"); ?></td>
			<td style="width:20%"><input type="submit" name="Generate" value="Generate"></td>
		</tr>
	</form>	
	<form name="grooutstanding" action="data_output.php" method="post">
		<input type="hidden" name="reporttype" value="grooutstanding" />
		<tr class="even">
			<td style="width:20%">GRO Outstanding</td>
			<td style="width:30%">N/A</td>
			<td style="width:30%">N/A</td>
			<td style="width:20%"><input type="submit" name="Generate" value="Generate"></td>
		</tr>
	</form>
	<form name="groall" action="data_output.php" method="post">
		<input type="hidden" name="reporttype" value="groall" />
		<tr class="odd">
			<td style="width:20%">GRO All Orders</td>
			<td style="width:30%">N/A</td>
			<td style="width:30%">N/A</td>
			<td style="width:20%"><input type="submit" name="Generate" value="Generate"></td>
		</tr>
	</form>
	<form name="grodaily" action="accounts.php" method="post">
		<input type="hidden" name="reporttype" value="grodaily" />
		<tr class="even">
			<td style="width:20%">GRO Daily Stats</td>
			<td style="width:30%"><?php echo dateSelector("GRODailyStartDay", "GRODailyStartMonth", "GRODailyStartYear"); ?></td>
			<td style="width:30%">N/A</td>
			<td style="width:20%"><input type="submit" name="Generate" value="Generate"></td>
		</tr>
	</form>
	<form name="grostref" action="accounts.php" method="post">
		<input type="hidden" name="reporttype" value="grostref" />
		<tr class="even">
			<td style="width:20%">GRO Duplicate ST ref</td>
			<td style="width:30%"><?php echo dateSelector("GRODailyStartDay", "GRODailyStartMonth", "GRODailyStartYear"); ?></td>
			<td style="width:30%">N/A</td>
			<td style="width:20%"><input type="submit" name="Generate" value="Generate"></td>
		</tr>
	</form>			
</table>
</p>
<?php 



if (isset($_POST['reporttype'])) {

	$the_day   = $_POST['GRODailyStartDay'];
	$the_month = $_POST['GRODailyStartMonth'];
	$the_year  = $_POST['GRODailyStartYear'];
		
	$the_date = $the_year . "-" . $the_month . "-" . $the_day;
	
	$uk_sql_st  = "SELECT count(GRO_orders_id) as numorders, ROUND(SUM(total_paid), 2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $the_date . "' and delivery_method in (1) GROUP BY order_date;";
	$uk_sql_ex  = "SELECT count(GRO_orders_id) as numorders, ROUND(SUM(total_paid), 2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $the_date . "' and delivery_method in (2) GROUP BY order_date;";
	$aus_sql_st = "SELECT count(GRO_orders_id) as numorders, ROUND(SUM(total_paid), 2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $the_date . "' and delivery_method in (3,5) GROUP BY order_date;";
	$aus_sql_ex = "SELECT count(GRO_orders_id) as numorders, ROUND(SUM(total_paid), 2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $the_date . "' and delivery_method in (4,6) GROUP BY order_date;";
	$us_sql_st  = "SELECT count(GRO_orders_id) as numorders, ROUND(SUM(total_paid), 2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $the_date . "' and delivery_method in (7,9) GROUP BY order_date;";
	$us_sql_ex  = "SELECT count(GRO_orders_id) as numorders, ROUND(SUM(total_paid), 2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $the_date . "' and delivery_method in (8,10) GROUP BY order_date;";
	$can_sql_st = "SELECT count(GRO_orders_id) as numorders, ROUND(SUM(total_paid), 2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $the_date . "' and delivery_method in (11,13) GROUP BY order_date;";
	$can_sql_ex = "SELECT count(GRO_orders_id) as numorders, ROUND(SUM(total_paid), 2) as paid FROM GRO_orders WHERE authorised = 1 and order_date = '" . $the_date . "' and delivery_method in (12,14) GROUP BY order_date;";
	$stref_sql = "SELECT GRO_orders_id, order_date, st_ref as dupstref from GRO_orders GROUP BY st_ref having count(*) >= 2 and order_date = '" . $the_date . "' ORDER BY GRO_orders_id;";

?>
<h2>Daily Results</h2>
<p>Date: <?php echo $the_date; ?></p>
<p><b>Sales:</b></p>
<table style="width:40%">

<?php

echo "<tr><td>UK Standard</td>" . getData($uk_sql_st) . "</tr>";
echo "<tr><td>UK Express</td>" . getData($uk_sql_ex) . "</tr>";
echo "<tr><td>Australia Standard</td>" . getData($aus_sql_st) . "</tr>";
echo "<tr><td>Australia Express</td>" . getData($aus_sql_ex) . "</tr>";
echo "<tr><td>United States Standard</td>" .  getData($us_sql_st) . "</tr>";
echo "<tr><td>United States Express</td>" .  getData($us_sql_ex) . "</tr>";
echo "<tr><td>Canada Standard</td>" . getData($can_sql_st) . "</tr>";
echo "<tr><td>Canada Express</td>" . getData($can_sql_ex) . "</tr>";

?>
</table>

<p><b>Scan and Send:</b></p>
<table style="width:30%">

<?php

echo "<tr><td>UK Standard</td>" . getScanData('(1)', $the_date) . "</tr>";
echo "<tr><td>UK Express</td>" . getScanData('(2)', $the_date) . "</tr>";
echo "<tr><td>Australia Standard</td>" . getScanData('(3,5)', $the_date) . "</tr>";
echo "<tr><td>Australia Express</td>" . getScanData('(4,6)', $the_date) . "</tr>";
echo "<tr><td>United States Standard</td>" .  getScanData('(7,9)', $the_date) . "</tr>";
echo "<tr><td>United States Express</td>" .  getScanData('(8,10)', $the_date) . "</tr>";
echo "<tr><td>Canada Standard</td>" . getScanData('(11,13)', $the_date) . "</tr>";
echo "<tr><td>Canada Express</td>" . getScanData('(12,14)', $the_date) . "</tr>";

?>
</table>

<p><b>Duplicate ST Ref:</b></p>
<table style="width:30%">

<?php

echo "<tr><td>Duplicate ST Ref</td>" . getSTRefData($stref_sql) . "</tr>";

?>
</table>
<?php
}
?>
<?php
include ('include/footer.php');
?>
