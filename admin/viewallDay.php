<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
	$session = session_id();
         
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	// Performing SQL query
	
	//$query = "SELECT DISTINCT order_date FROM tbl_order_header;";
	$query = "SELECT  order_date,COUNT(*) FROM tbl_order_header WHERE authorised=1 GROUP BY order_date;";
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
				<h3>View all by Day</h3>
				<table>
				<th>Date</th><th>Orders</th>
				<?php
					while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
						
					
				 ?>
				 <tr>
				 	<td align="center"><a href="ordersbyday.php?passedOrderDate=<?php Escaped_echo($line["order_date"]); ?>"><?php Escaped_echo($line["order_date"]); ?></a></td> 
				 	<td align="center"><a href="ordersbyday.php?passedOrderDate=<?php Escaped_echo($line["order_date"]); ?>"><?php Escaped_echo($line["COUNT(*)"]); ?></a></td>
				 </tr>	 		
					       
				<?php
			
				}
				?>
					
				
			</div>
		</form>
	</body>
</html>