<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
include ("graph.php");

if(isset($_GET['passedOrderYear']))
{

	$clean=array();
	$mysql=array();
	
	//setup array to store order values in months
	
	$YearArray=array();
	$YearArray=array_pad($YearArray,13,0);
	
	$YearPriceArray=array();
	$YearPriceArray=array_pad($YearArray,13,0);
	
	$TotalSpend=0;
	
	// Connecting, selecting database
	 	
	$clean['PassedOrderYear']=form_clean($_GET['passedOrderYear'],4);
	
	if(is_numeric($clean['PassedOrderYear']) && $clean['PassedOrderYear']>1900 && $clean['PassedOrderYear']<9999)
	{
		 
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$mysql['PassedOrderYear']=mysql_real_escape_string($clean['PassedOrderYear'],$link);
	
	// Performing SQL query
	$query = "SELECT  Distinct IP, Date FROM visits WHERE First='1' AND Date LIKE '{$mysql['PassedOrderYear']}%' ORDER by Date ASC;";
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	//get number of rows to know when to output last year in loop
	$NumRows = mysql_num_rows($result);
	
	
		
	?>
	
	<html>
		<head>
			<link rel="stylesheet" href="style/adminstyle.css" type="text/css" />
		</head>
		<body topmargin="0">
			<form action="checkpass.php" method="post">
				<div align="center">
					
					<?php include("menu.php"); ?>
					<h3>Visits By Month for <?php Escaped_echo ($clean['PassedOrderYear']);?></h3>
					
					<table>
					<tr>
						<td>
						Click a month header to view that months details	
						</td>
					</tr>
					<?php
					while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
							
						//get the day from this row
						$CurrentMonth=substr($line["Date"],5,2);
						$CurrentMonth=intval($CurrentMonth);
						$YearArray[$CurrentMonth]++;
						/*
						$OrderNumber=$line["ordernumber"];
					
					
						//query to get a total of baskets for this day
						$querybasket = "SELECT sessionid,itemcode,name,price,quantity FROM tbl_baskets WHERE sessionid='$OrderNumber';";
		
						$resultbasket = mysql_query($querybasket) or die('Query failed: ' . mysql_error());
						
						$linebasket = mysql_fetch_array($resultbasket,MYSQL_ASSOC);
						
						$TotalPrice=$linebasket['quantity']*$linebasket['price'];
						$TotalSpend=$TotalSpend+$TotalPrice;
						
						
						$YearPriceArray[$CurrentMonth]=$YearPriceArray[$CurrentMonth]+$TotalPrice;
						*/		
						$YearPriceArray[$CurrentMonth]=0;
					}
					?>
					
					<table border="2" cellpadding="1">
					<th> </th>
					<th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-01")?>">Jan</a></th><th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-02")?>">Feb</a></th><th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-03")?>">Mar</a></th>
					<th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-04")?>">April</a></th><th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-05")?>">May</a></th><th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-06")?>">June</a></th>
					<th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-07")?>">July</a></th><th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-08")?>">August</a></th><th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-09")?>">Sept</a></th>
					<th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-10")?>">Oct</a></th><th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-11")?>">Nov</a></th><th bgcolor="gray"><a href="visitsbymonth.php?passedOrderYM=<?php Escaped_echo( $clean['PassedOrderYear'] ."-12")?>">Dec</a></th>
					<th bgcolor="gray">Total</th><th bgcolor="gray">Total  Visits</th><th bgcolor="gray">Avg Per Month</th>
					<th bgcolor="gray">Avg Visits Per Month</th>
					
					
					<tr>
					<td>
					<table>
					<tr>
					<td>
					<b>Visits</b>
					</td>
					</tr>
					<tr>
					<td>					
					<b>Page Hits</b>
					</tr>
					</td>
					</table>
					</td>
					<?php 
						for ($monthcount=1;$monthcount<13;$monthcount++)
						{
						?>
						<td align="center">
					<?php
						if ($monthcount<10)
						{
					?>
						<!--- <a href="visitsbymonth.php?passedOrderYM=<?php echo $clean['PassedOrderYear'] ."-0". $monthcount;?>">
						--->
					<?php	
						}
						else
						{
					?>
						<!--- <a href="visitsbymonth.php?passedOrderYM=<?php echo $clean['PassedOrderYear'] ."-". $monthcount;?>">
						--->
					<?php	
						}
						echo "<table>";
						echo "<tr>";
						echo "<td align=\"center\">";
						Escaped_echo( $YearArray[$monthcount]);
						echo "</td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td align=\"center\">";					
						Escaped_echo( "£"  . $YearPriceArray[$monthcount]);
						echo "</tr>";
						echo "</td>";
						echo "</table>";
					?>
						
						</a></td>
					<?php	}
						$Avg=$NumRows/12;//average the number of orders for each month
						$Avg=sprintf('%.2f', $Avg);
						$AvgSpend=$TotalSpend/12;//average the total spend for each month
						$AvgSpend=sprintf('%.2f', $AvgSpend);
					?>
						<td align="center"><b><?php Escaped_echo( $NumRows);?></b></td>
						<td align="center"><b><?php Escaped_echo( "£" . $TotalSpend);?></b></td>
						<td align="center"><b><?php Escaped_echo( $Avg);?></b></td>
						<td align="center"><b><?php Escaped_echo( "£" . $AvgSpend);?></b></td>
					</tr>
						
					
					
					
				<?php 	
				$GraphFile="graph" . basename($clean['PassedOrderYear']) . ".gif";
				$GraphFile2="graph" . basename($clean['PassedOrderYear']) . "2.gif";
				
				BarGraph(array_slice($YearArray,1,12),"Month","No. Visits",250,400,10,$GraphFile);
				//BarGraph(array_slice($YearPriceArray,1,12),"Month","Total Pounds",250,200,10,$GraphFile2);
				
				?>
				<?php
				if(is_file($GraphFile) && is_file($GraphFile2) )
				{
				?>
					<tr>
					
					<!--- //display the image using the file name + ?currenttime. this will force the browser to reload the image ---->
					<td align="center" valign="bottom" colspan="17"><img src="<?php Escaped_echo( $GraphFile ."?" . time()) ?>">	
					
					<!--- <img src="<?php echo $GraphFile2 ."?" . time() ?>"></td>	--->
					
					</tr>
				<?php
				}
				?>
				</table>					
				<a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo($clean['PassedOrderYear']-1);?>"><?php Escaped_echo( "<-- Previous Year");?></a>
							
				<a href="ordersbyyear.php?passedOrderYear=<?php Escaped_echo($clean['PassedOrderYear']+1);?>"><?php Escaped_echo( "Next Year -->");?></a>
							
				<br />
				<a href="viewallYear.php">View All Years</a>	
				
			<?php
			// Closing connection
			mysql_close($link);
			?>
				</div>
			</form>
			
		</body>
	</html>
<?php
	}
}
?>
