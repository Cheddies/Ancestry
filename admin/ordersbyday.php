<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
if(isset($_GET['passedOrderDate']))
{
	//$session = session_id();
 	$clean=array();
	$mysql=array();
	$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");
	$clean['PassedOrderDate']=form_clean($_GET['passedOrderDate'],10,$badchars);
	
		
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql['PassedOrderDate']=mysql_real_escape_string($clean['PassedOrderDate']);
	
	// Performing SQL query
	$query = "SELECT niceordernum,ordernumber, order_date, firstname, lastname FROM tbl_order_header WHERE order_date = '{$mysql['PassedOrderDate']}' AND authorised=1 ORDER by order_date DESC;";
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
				<h2>Orders listed by day - <?php Escaped_echo($clean['PassedOrderDate']);?> </h3>
				Below are all the orders for this day, listed in the order they occurred. Click on an order to view the details of that order
				<br />
				<table align="center" cellpadding="2" cellspacing="2" border="2">
				<th align="center" bgcolor="gray">Order Num</th><th align="center" bgcolor="gray">Date</th><th align="center" bgcolor="gray">Customer</th>
					<tr><td colspan="3" border="0"><p>&nbsp;</p></td></tr>
					<?php
						while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					?>
						<tr>
							<td align="center"><a href="orderdetails.php?ordernumber=<?php Escaped_echo( $line["ordernumber"]); ?>"><?php Escaped_echo( $line["niceordernum"]); ?></a></td>
							<td align="center"><a href="orderdetails.php?ordernumber=<?php Escaped_echo( $line["ordernumber"]); ?>"><?php Escaped_echo( $line["order_date"]); ?></a></td>
							
							<td align="center"><a href="orderdetails.php?ordernumber=<?php Escaped_echo( $line["ordernumber"]); ?>"><?php Escaped_echo( $line["firstname"]); ?> <?php Escaped_echo( $line["lastname"]); ?></a></td>
							
						</tr>
					<?php
						}
					?>
				</table>
			</div>
		</form>
	</body>
</html>
<?php
}
?>