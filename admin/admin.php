<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
	$mysql=array();
	//Get the current date and put into variables
	$CurrentYear=date("Y");
	$CurrentMonth=date("m");
	$CurrentDay=date("d");

	//Work out the start and end dates of this month
	$DayofWeek=date("w");//0=sun, 6=sat
	$DaysfromStart=$DayofWeek;
	$DaysfromEnd=6-$DaysfromStart;
	
	//use mktime to calculate the dates of the start and end of week			
	$EndofWeek  = mktime(0, 0, 0, date("m")  , date("d")+$DaysfromEnd, date("Y"));
	$StartofWeek = mktime(0, 0, 0, date("m")  , date("d")-$DaysfromStart, date("Y"));
	//convert the times in to usable strings in format Year-Month-Day	
	$sStartofWeek=date("Y-m-d",$StartofWeek);
	$sEndofWeek=date("Y-m-d",$EndofWeek);


	$session = session_id();
         
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	// Performing SQL query to get current stats on day,month,year
	
	$mysql['CurrentYear']=mysql_real_escape_string($CurrentYear,$link);
	$mysql['CurrentMonth']=mysql_real_escape_string($CurrentMonth,$link);
	$mysql['CurrentDay']=mysql_real_escape_string($CurrentDay,$link);
	
	$mysql['sStartofWeek']=mysql_real_escape_string($sStartofWeek,$link);
	$mysql['sEndofWeek']=mysql_real_escape_string($sEndofWeek,$link);
	//get orders today and count them
	$query = "SELECT order_date  FROM tbl_order_header WHERE order_date = '{$mysql['CurrentYear']}-{$mysql['CurrentMonth']}-{$mysql['CurrentDay']}' AND authorised=1;";
print_r($query);	
	//query to get details from order_header
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
	$OrdersToday=mysql_num_rows($result);
	
	//get orders this month and count
	
	$query = "SELECT order_date FROM tbl_order_header WHERE order_date LIKE '{$mysql['CurrentYear']}-{$mysql['CurrentMonth']}%' AND authorised=1;";
		
	//query to get details from order_header
		
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	$OrdersMonth=mysql_num_rows($result);
	
	
	//get orders this year and count
	$query = "SELECT order_date FROM tbl_order_header WHERE order_date LIKE '{$mysql['CurrentYear']}-%' AND authorised=1;;";
			
	//query to get details from order_header
			
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
	$OrdersYear=mysql_num_rows($result);
	
	
	//query to get number of orders for this week
	
	$query = "SELECT order_date FROM tbl_order_header WHERE order_date BETWEEN '{$mysql['sStartofWeek']}' AND '{$mysql['sEndofWeek']}' AND authorised=1;";
		
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			
	$OrdersWeek=mysql_num_rows($result);
	
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
				<br />
				<table>
				
				<tr>
					<td align ="center"><h2><a href="ordersbyday.php?passedOrderDate=<?php Escaped_echo($CurrentYear ."-" . $CurrentMonth . "-" . $CurrentDay) ;?>">Orders Today - <?php Escaped_echo( $OrdersToday); ?></a></h2></td>
				</tr>
				<tr>
					<td><br /></td>
				</tr>
				<tr>
					<td align ="center"><h2><a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo( $CurrentYear) ?>"> Orders This Week <?php Escaped_echo("(" . $sStartofWeek . "-" . $sEndofWeek .")");?> -  <?php Escaped_echo( $OrdersWeek); ?></a></h2></td>
				</tr>
				
				<tr>
					<td><br /></td>
				</tr>
				<tr>
				
					<td align ="center"><h2><a href="ordersbymonth.php?passedOrderYM=<?php Escaped_echo($CurrentYear ."-" . $CurrentMonth);?> "> Orders This Month -<?php Escaped_echo( $OrdersMonth); ?></a></h2></td>
				</tr>
				<tr>
					<td><br /></td>
				</tr>
				<tr>
					<td align ="center"><h2><a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo( $CurrentYear) ?>"> Orders This Year -  <?php Escaped_echo( $OrdersYear); ?></a></h2></td>
				
				</tr>
				
				
				</table>
				<?php
				
				
				?>
			</div>
		</form>
	</body>
</html>
