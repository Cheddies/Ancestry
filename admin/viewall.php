<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php

	//Get the current date and put into variables
	$CurrentYear=date("Y");
	$CurrentMonth=date("m");
	$CurrentDay=date("d");
	
	$session = session_id();
         
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	// Performing SQL query
	
	$query = "SELECT DISTINCT order_date FROM tbl_order_header WHERE authorised=1;";
	
	//query to get details from order_header
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	// Closing connection
	mysql_close($link);

	
?>

<html>
	<head>
		<link rel="stylesheet" href="style/adminstyle.css" type="text/css" />
	</head>
	<body topmargin="0">
		<form action="checkpass.php" method="post">
			<div align="center">
				
				<?php include("menu.php"); ?>
				<h3>View All Orders</h3>
				<table>
				<tr>
					<td align="right" ><a href="viewallYear.php">List by Year</a></td>
				</tr>
				<tr>
					<td></td><td align="left" colspan="2"><a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo( $CurrentYear);?>"> - List by Month (This Year)</a></td>
				</tr>
				<tr>
					<td></td><td></td><td align="left" colspan="3"> <a href="ordersbymonth.php?passedOrderYM=<?php Escaped_echo( $CurrentYear ."-" .$CurrentMonth)?>"> - List by Day(This Month)</a></td>
				</tr>
				<tr>
					<td></td><td></td><td></td><td align="left" colspan="4"><a href="ordersbyday.php"> - List by Day (This Week)</a></td>
				</tr>
				</table>
				
			</div>
		</form>
	</body>
</html>