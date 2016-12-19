<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
	//$session = session_id();

	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	// Performing SQL query
	$query = "SELECT firstname,lastname,niceordernum,ROUND(SUM(quantity*price),2) Total,date,ordernumber FROM referer,tbl_order_header,tbl_baskets WHERE tbl_baskets.sessionid=referer.sessionid AND referer.sessionid=ordernumber AND authorised=1 GROUP BY referer.sessionid ORDER by date ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	// Closing connection
	mysql_close($link);

	$totalorders = mysql_num_rows($result);
?>

<html>
	<head>
		<link rel="stylesheet" href="style/adminstyle.css" type="text/css" />
	</head>
	<body topmargin="0">
		<form action="checkpass.php" method="post">
			<div align="center">
				
				<?php include("menu.php"); ?>
				<h3>Froogle Orders</h3>
				<h3>Total: <?php Escaped_echo( $totalorders);?></h3>
				
				<table align="center" id="froogle" cellspacing="0" cellpadding="2">
				
					<tr>
						<th>Order Number</th>
						<th>First Name</th>
						<th>Surname</th>
						<th>Order Total</th>
						<th>Order Date</th>
					</tr>
					<?php
						while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					?>
						<tr>
						<td><a href="orderdetails.php?ordernumber=<?php Escaped_echo( $line['ordernumber'])?>"><?php Escaped_echo( $line['niceordernum']);?></a></td>
						<td><?php Escaped_echo( $line['firstname']);?></td>
						<td><?php Escaped_echo( $line['lastname']);?></td>
						<td>£<?php Escaped_echo( $line['Total']);?></td>
						<td><?php Escaped_echo( $line['date']);?></td>
						</tr>
					<?php
						}
					?>
				</table>
			</div>
		</form>
	</body>
</html>