<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
if(isset($_GET['passedOrderCountry']) && isset($_GET['passedOrderCountryName']) && isset($_GET['shipping']) )
{
	//$session = session_id();
	$clean=array();
	$mysql=array();
	
	$clean['PassedCountry']=form_clean($_GET['passedOrderCountry'],3);
	$clean['PassedCountryName']=form_clean($_GET['passedOrderCountryName'],50);
	$clean['Shipping']=form_clean($_GET['shipping'],3);
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql['PassedCountry']=mysql_real_escape_string($clean['PassedCountry'],$link);
	
	// Performing SQL query
	if($clean['Shipping']="true")
	{
		$query = "SELECT ordernumber, order_date, firstname, lastname FROM tbl_order_header WHERE scountry={$mysql['PassedCountry']} AND authorised=1;";
	}
	else
	{
		$query = "SELECT ordernumber, order_date, firstname, lastname FROM tbl_order_header WHERE country={$mysql['PassedCountry']} AND authorised=1;";
	}
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
				<h3>All orders for <?php Escaped_echo( $clean['PassedCountryName']);?></h3>
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