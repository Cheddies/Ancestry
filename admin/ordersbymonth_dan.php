<?php
	
include "logincheck.php";
include("../include/siteconstants.php"); 
include "graph.php";

//Store the passed Year-Month
$PassedOrderYM=$_GET['passedOrderYM'];

//store year + month seperate for use in mktime
$Year=substr($PassedOrderYM,0,4);
$Month=substr($PassedOrderYM,5,2);

//store the passed month in a timestamp
//for use in date() function
$MonthTimeStamp=  mktime(0, 0, 0, $Month  , 1, $Year);

//use date() to get the days in this month
//and the start day of this month(0-6(Sun-Sat))

$DaysinMonth=date("t",$MonthTimeStamp);
$StartDayofMonth=date("w",$MonthTimeStamp);

//create an array to represent the days of the month
$MonthArray=array();
$MonthArray=array_pad($MonthArray,$DaysinMonth+1,0);

//create an array to store the money spent each day
$MonthPriceArray=array();
$MonthPriceArray=array_pad($MonthArray,$DaysinMonth+1,0);

//also work out next/last month for links at bottom
//could possibly just list all months ?

$NextMonth=mktime(0, 0, 0, date("m",$MonthTimeStamp)+1, date("d",$MonthTimeStamp),  date("Y",$MonthTimeStamp));
$LastMonth=mktime(0, 0, 0, date("m",$MonthTimeStamp)-1, date("d",$MonthTimeStamp),  date("Y",$MonthTimeStamp));

$NextMonth=date("Y-m",$NextMonth);
$LastMonth=date("Y-m",$LastMonth);


//Get rows from DB

// Connecting, selecting database
$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

// Performing SQL query
//Query
//Selects all the orders from the DB where the month and year match the one needed
//groups these by the Day
//Joins to the Basket table on SessionId/Order Number
//Counts the distinct order numbers returned from this - this gives number of orders
//A rounding of the sum of quantity * price for each line of the order gives a total for that day
$query = "SELECT ordernumber, order_date, firstname, lastname,count(DISTINCT(ordernumber)) as NumberofOrders,DAY(order_date) as Day,ROUND(SUM(quantity*price),2) as Total FROM tbl_order_header,tbl_baskets WHERE ordernumber=sessionid AND MONTH(order_date)=$Month  AND YEAR(order_date)=$Year GROUP BY day ORDER by Day ASC;";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

//get number of rows to know when to output last year in loop
$NumRows = mysql_num_rows($result);

//Get what day it is today, this is to ensure averages are only worked out on days passed
//not the total of days in month
$Today=date("Y-m-d");

//work out how many days since start of month til today

//first check it is still this month
//if not then just use the total days in the month

if( date("mY")=="{$Month}{$Year}")
{
	$DoneDays=(strtotime($Today)-$MonthTimeStamp);
	$DoneDays=date("d",$DoneDays);
}	
else
	$DoneDays=$DaysinMonth;
//echo $DoneDays;

//Check to see if this is higher than the days in month
//this will happen when today is after this month
//in this case , just set DoneDays to the total in month


if($DoneDays>$DaysinMonth)
	$DoneDays=$DaysinMonth;

//fill array for all days

$alldaysorders=array($DaysinMonth);
$alldaysorders=array_fill ( 1, $DaysinMonth,0 );
$alldaystotals=array($DaysinMonth);
$alldaystotals=array_fill ( 1, $DaysinMonth,0 );

while ($line=mysql_fetch_array($result,MYSQL_ASSOC))
{
	
	$day=$line['Day'];
	
	$alldaysorders[$day]=$line['NumberofOrders'];
	$alldaystotals[$day]=$line['Total'];
	
}
	
	

?>
<html>
	<head>
		<link rel="stylesheet" href="style/adminstyle.css" type="text/css" />
	</head>
	<body topmargin="0">
		<form action="checkpass.php" method="post">
			<div align="center">
				
				<?php include("menu.php"); ?>
				
						
				
					<a href="ordersbymonth.php?passedOrderYM=<?php echo $LastMonth;?>"><?php echo "< Previous Month";?></a>
				
					<a href="ordersbymonth.php?passedOrderYM=<?php echo $NextMonth;?>"><?php echo "Next Month >";?></a>
					<br />
				
				
				
				<a href="ordersbyYear.php?passedOrderYear=<?php echo $Year;?>">View All Months</a>
				<table><th colspan="3" align="center"><h4>Orders for <?php echo date("M-Y",$MonthTimeStamp)?></h4></th>
				<tr>
				<td align="center" width="750" colspan="2">
				<?php
							
					echo "<table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
					echo "<th bgcolor=\"gray\" width=\"60\">Sun</th> <th bgcolor=\"gray\" width=\"60\" >Mon</th> <th bgcolor=\"gray\" width=\"60\">Tue</th> <th bgcolor=\"gray\" width=\"60\">Wed</th> <th bgcolor=\"gray\" width=\"60\">Thur</th> <th bgcolor=\"gray\" width=\"60\">Fri</th> <th bgcolor=\"gray\" width=\"60\">Sat</th>";
					echo "<tr>";
					$newrow=0;
					
					//first decide where to start the first number
					for ($start=0;$start<$StartDayofMonth;$start++)
					{
						echo "<td> -</td>";
						$newrow++;
					}
					
					$TotalOrders=0;
					$TotalSpend=0;
					
					for ($count=1;$count<$DaysinMonth+1;$count++)
					{
							//$line=mysql_fetch_array($result,MYSQL_ASSOC);
							
							echo "<td align=\"left\">";
							echo "<table align=\"center\" align=\"left\">";
						
							//echo ("<tr><td width=\"100\" align=\"left\" bgcolor=\"green\" height=\"20\"><h3> <a href=\"ordersbyday.php?passedOrderDate=". $PassedOrderYM . "-". $count ."\">".  $count . "</a></h3>". "</td></tr><tr><td bgcolor=\"\">".  "<b>O:</b>:".  $MonthArray[$count] ."</a>" ."</td></tr><tr><td bgcolor=\"\">".  "<b>T:</b>" ."&pound;".$MonthPriceArray[$count] . "</td></tr>");
							//echo ("<tr><td width=\"100\" align=\"left\" bgcolor=\"green\" height=\"20\"><h3> <a href=\"ordersbyday.php?passedOrderDate=". $PassedOrderYM . "-". $count ."\">".  $count . "</a></h3>". "</td></tr><tr><td bgcolor=\"\">".  "<b>O:</b>:".  $line['NumberofOrders'] ."</a>" ."</td></tr><tr><td bgcolor=\"\">".  "<b>T:</b>" ."&pound;". $line['Total'] . "</td></tr>");
							echo ("<tr><td width=\"100\" align=\"left\" bgcolor=\"green\" height=\"20\"><h3> <a href=\"ordersbyday.php?passedOrderDate=". $PassedOrderYM . "-". $count ."\">".  $count . "</a></h3>". "</td></tr><tr><td bgcolor=\"\">".  "<b>O:</b>:".  $alldaysorders[$count] ."</a>" ."</td></tr><tr><td bgcolor=\"\">".  "<b>T:</b>" ."&pound;". $alldaystotals[$count] . "</td></tr>");
							
							
							echo "</table>";
							echo "</td>";
						
						
						$newrow++;
						if ($newrow==7)
						{
							echo "</tr><tr>";
							$newrow=0;
						}
						//$MonthArray[$count]=$MonthArray[$count]+$line['NumberofOrders'];
						//$MonthPriceArray[$count]=$MonthPriceArray[$count]+$line['Total'];
						
						//$TotalOrders=$TotalOrders+$line['NumberofOrders'];
						//$TotalSpend=$TotalSpend+$line['Total'];
						$MonthArray[$count]=$MonthArray[$count]+$alldaysorders[$count];
						$MonthPriceArray[$count]=$MonthPriceArray[$count]+$alldaystotals[$count];
						
						$TotalOrders=$TotalOrders+$alldaysorders[$count] ;
						$TotalSpend=$TotalSpend+$alldaystotals[$count];
					}
					
					echo "<tr>";
					echo "<td align=\"left\" colspan=\"3\" bgcolor=\"lightblue\">";
					echo "<h4>Total Orders : ".$TotalOrders;
					
					//$AvgOrders=$NumRows/$DoneDays;
					$AvgOrders=($TotalOrders/$DoneDays);
					
					$AvgOrders=sprintf('%.2f', $AvgOrders);
					
					echo "<br />";
					
					echo "Avg per day: ". $AvgOrders;
					echo "</h4></td>";
					
					echo "<td align=\"left\" colspan=\"4\" bgcolor=\"#6699CC\">";
					
					echo "<h4>Total Spent : &pound;" .$TotalSpend;
				
					$AvgSpend=($TotalSpend/$DoneDays);
					$AvgSpend=sprintf('%.2f', $AvgSpend);
					
					echo "<br />";
					
					echo "Avg Per Day: &pound;";
					echo $AvgSpend;
				
					echo "</h4></td>";
					echo "</tr>";
					echo "</table>";
					
				?>
				</td>
		</tr>
		<tr>	
				<?php 	
				$GraphFile="graph" . $MonthTimeStamp  .".gif";
				$GraphFile2="graph" . $MonthTimeStamp  ."2.gif";
				
				//BarGraph(array_slice($MonthArray,1,$DaysinMonth),"Day","Orders",200,350,5,$GraphFile);
				//BarGraph(array_slice($MonthPriceArray,1,$DaysinMonth),"Day","Pounds's",200,350,5,$GraphFile2);
				
				BarGraph(array_slice($MonthArray,1,$DaysinMonth),"Day","Orders",350,470,7,$GraphFile,2.5);
				BarGraph(array_slice($MonthPriceArray,1,$DaysinMonth),"Day","Pounds's",350,470,7,$GraphFile2,2.5);
				?>
		
						<!--- //display the image using the file name + ?currenttime. this will force the browser to reload the image ---->
			
		
			<td align="center" valign="top">
			<img src="<?php echo $GraphFile . "?" . time() ?>">	
			</td>
		</tr>
		<tr>
			<td align="center" valign="top" >
				<img src="<?php echo $GraphFile2 . "?" . time() ?>">
			</td>	
				
				
			
		</tr>
		</table>
				
				
					<a href="ordersbymonth.php?passedOrderYM=<?php echo $LastMonth;?>"><?php echo "< Previous Month";?></a>
				
					<a href="ordersbymonth.php?passedOrderYM=<?php echo $NextMonth;?>"><?php echo "Next Month >";?></a>
					<br />
				
				
				
				<a href="ordersbyYear.php?passedOrderYear=<?php echo $Year;?>">View All Months</a>
			<?php // Closing connection
			mysql_close($link);
			?>					
			</div>
		</form>
	</body>
</html>


