<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
if(isset($_GET['passedOrderDate']))
{
	$session = session_id();
 	$clean=array();
	$mysql=array();
	
	$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");
 	$clean['PassedOrderDate']=form_clean($_GET['passedOrderDate'],10,$badchars);
 	
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$mysql['PassedOrderDate']=mysql_real_escape_string($clean['PassedOrderDate'],$link);
	
	// Performing SQL query
	$query = "SELECT ordernumber, order_date, firstname, lastname FROM tbl_order_header WHERE order_date = '{$mysql['PassedOrderDate']}' AND authorised=1;";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	// Closing connection
	mysql_close($link);

	$neworders = mysql_num_rows($result);
?>

<html>
	<head>
		<link rel="stylesheet" href="style/adminstyle.css" type="text/css" />
	</head>
	<body topmargin="0">
		<form action="checkpass.php" method="post">
			<div align="center">
				
				<?php include("menu.php"); ?>
				<h3>New Orders</h3>
				<table align="center">
					<tr><td colspan="3"><p>&nbsp;</p></td></tr>
					<?php
						while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					?>
						<tr>
						
							<td align="center"><a href="orderdetails.php?ordernumber=<?php Escaped_echo( $line["ordernumber"]); ?>"><?php Escaped_echo( $line["order_date"]); ?></a></td>
							<td align="center" width="20">&nbsp;</td>
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