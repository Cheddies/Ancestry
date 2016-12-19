<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
	$session = session_id();
        
        //Variables for displaying by year
        
        $CurrentYear=""; //store the current year in the loop
        $LastYear=""; //store the last year in the loop
        $NumOrders=0; //store number of orders for current year
        $LoopStart=1;// used to determine if the loop has just started
        $NumRows=0;//stores number of rows in query
        $Count=0;//used to count number of times looped, needed to know when to output last year
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	// Performing SQL query
	
	$query = "SELECT order_date FROM tbl_order_header WHERE authorised=1 ORDER by order_date DESC;";
	
	//query to get details from order_header
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	//get number of rows to know when to output last year in loop
	$NumRows = mysql_num_rows($result);
	
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
				<h3>Orders By Year</h3>
				<table>
				<th>Year</th><th>Orders</th>
				<?php
					while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					
					//get the year from this row
					$CurrentYear=substr($line["order_date"],0,4);
					
					//Check to see if this is the start of the loop
					//if it is then the $LastYear variable is not set, so set it to
					//the current year
					if($LoopStart==1)
					{
						$LastYear=$CurrentYear;
						$LoopStart=0;
									
					}
					//if this row has the same year as the last
					//then increase the count of number of orders
					//set LastYear variable at this point 
					
					if ($Count!=$NumRows-1)
					{
						if ($CurrentYear==$LastYear) 
						{
							//increase number of orders for this year
							$NumOrders++;
							$LastYear=$CurrentYear;
						}
						//else the year in this row is new
						//so output a link for the last year
						else
						{
							//$NumOrders++;
							
						?>
							<tr>
								<td><a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo( $LastYear);?>"><?php Escaped_echo( $LastYear);?></a></td>
								<td><a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo( $LastYear);?>"><?php Escaped_echo( $NumOrders);?></a></td>
							</tr>
						<?php
							$LastYear=$CurrentYear;	
							$NumOrders=1;
						}
						?>
				<?php
					}//end If not end of rows
					else
					{
					//end of rows,ouput the current year
						if($CurrentYear!=$LastYear)
						{
												
					?>	
						
						
						<tr>
							<td><a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo( $LastYear);?>"><?php Escaped_echo( $LastYear);?></a></td>
							<td><a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo( $LastYear);?>"><?php Escaped_echo( $NumOrders);?></a></td>
						</tr>
						<?php
							$NumOrders=0;
							
						}
						
						 $NumOrders++; 
						?>
						<tr>
							<td><a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo( $CurrentYear);?>"><?php Escaped_echo( $CurrentYear);?></a></td>
							<td><a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo( $CurrentYear);?>"><?php Escaped_echo( $NumOrders);?></a></td>
						</tr>
					<?php
						$NumOrders=1;
					}
					
				$Count++;
				}//end while
				?>
				</table>	
				
			</div>
		</form>
	</body>
</html>