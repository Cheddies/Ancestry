<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
include "graph.php";
include "randomcolor.php";

//Get rows from DB

// Connecting, selecting database
$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

// Performing SQL query
$query = "SELECT count(tbl_order_header.country), tbl_order_header.country,tbl_countries.country,code FROM tbl_order_header,tbl_countries WHERE tbl_order_header.country=tbl_countries.code AND authorised=1 GROUP BY tbl_order_header.country ASC;";

$result = mysql_query($query) or die('Query failed: ' . mysql_error());

//get number of rows to know when to output last year in loop
$NumRows = mysql_num_rows($result);

$countryarray=array($NumRows);

// Performing SQL query
$squery = "SELECT count(tbl_order_header.scountry), tbl_order_header.scountry,tbl_countries.country,code FROM tbl_order_header,tbl_countries WHERE tbl_order_header.scountry=tbl_countries.code AND authorised=1 GROUP BY tbl_order_header.scountry ASC;";

$sresult = mysql_query($squery) or die('Query failed: ' . mysql_error());

//get number of rows to know when to output last year in loop
$sNumRows = mysql_num_rows($sresult);

$scountryarray=array($sNumRows);


if($sNumRows>$NumRows)
{
	
	$colorarray=array($sNumRows);
	//create an array of random colors same size as the list of countrys
	for ($Count=0;$Count<$sNumRows;$Count++)
	{		
		$colorarray[$Count]=randomcolor();
		
		$randnum=rand(0,1);
		if ($randnum==1)
		{
			$colorarray[$Count]=randomcolor();
		}
	}
}
	
else
{
	$colorarray=array($NumRows);
	//create an array of random colors same size as the list of countrys
	
	for ($Count=0;$Count<$NumRows;$Count++)
	{		
		$colorarray[$Count]=randomcolor();
		
		$randnum=rand(0,1);
		if ($randnum==1)
		{
			$colorarray[$Count]=randomcolor();
		}
	}
}


// Performing SQL query
$stquery = "SELECT count(tbl_order_header.shipvia),description,tbl_order_header.shipvia,code FROM tbl_order_header,tbl_shipping WHERE tbl_order_header.shipvia=tbl_shipping.code AND authorised=1 GROUP BY tbl_order_header.shipvia ASC;";

$stresult = mysql_query($stquery) or die('Query failed: ' . mysql_error());

//get number of rows to know when to output last year in loop
$stNumRows = mysql_num_rows($stresult);
//echo $stNumRows;
$scountryarray=array($sNumRows);


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
				<table><th colspan="2" align="center"><h3>Number of orders per country</h3></th>
				<tr>
				<td align="center" colspan="2"><h4>Click a country to view all the orders for that country</h4></td>
				</tr>
				<tr><td colspan="2" align="center"><h3><font color="red">Billing Country</font></h3></td></tr>
				<tr>
				<td>
				
				<table>
				<th>Country</th><th>Orders</th>
				<?php
					$count=0;
					while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					
					echo "<tr>";
					echo "<td>";
					
					echo "<a href=\"ordersbycountry.php?passedOrderCountry=";
					Escaped_echo( $line['code']);
					echo "&passedOrderCountryName=";
					Escaped_echo( $line['country']);
					echo "&shipping=false";
					echo "\">";
					
					echo "<b>";
					echo "<font color=\"";
					Escaped_echo( $colorarray[$count]);
					echo "\">";
						Escaped_echo( $line['country']);
						
					echo "</font>";
					
					echo "</a>";
					echo "</td>";
					
					echo "<td>";
					
					echo "<a href=\"ordersbycountry.php?passedOrderCountry=";
					Escaped_echo( $line['code']);
					echo "&passedOrderCountryName=";
					Escaped_echo( $line['country']);
					echo "\">";
					
						echo "<font color=\"";
						Escaped_echo( $colorarray[$count]);
						echo "\">";
						Escaped_echo( $line['count(tbl_order_header.country)']); 
						echo "</font>";
					echo "</a>";	
						$countryarray[$count]=$line['count(tbl_order_header.country)'];
					echo "</b>";
					echo "</td>";
					echo "</tr>";
					$count++;
					}//end while
				?>
				</table>
				
				</td>
				<td>
				<table>
				<td align="left">
					<?php 	
						$GraphFile="countrystatsgraph.gif";
						$GraphFile1="countrystatsgraph1.gif";
						//BarGraph($countryarray,"Country","No. Orders",370,300,5,$GraphFile1,3,$colorarray,true);
						PieChart($countryarray,"Country","No. Orders",300,300,5,$GraphFile,8,$colorarray);
						if(is_file($GraphFile) && is_file($GraphFile))
						{				
					?>
					
					<!--- //display the image using the file name + ?currenttime. this will force the browser to reload the image ---->
					
					<td align="center" valign="middle"><img src="<?php Escaped_echo( $GraphFile ."?" . time()) ?>"></td>	
					
					<!--- <td align="center" valign="bottom" colspan="2"><img src="<?php echo $GraphFile1 ."?" . time() ?>"></td>	
					--->
					<?php
						}
					?>
				</tr>
				</table>
				</td>
				</tr>
				<tr><td colspan="2" align="center"><h3><font color="blue">Shipping Country</font></h3></td></tr>
				<tr>
								<td>
								
								<table>
								<th>Country</th><th>Orders</th>
								<?php
									$count=0;
									while ($line = mysql_fetch_array($sresult, MYSQL_ASSOC)) {
									
									echo "<tr>";
									echo "<td>";
									
									echo "<a href=\"ordersbycountry.php?passedOrderCountry=";
									Escaped_echo( $line['code']);
									echo "&passedOrderCountryName=";
									Escaped_echo( $line['country']);
									echo "&shipping=true";
									echo "\">";
									
									echo "<b>";
									echo "<font color=\"";
									Escaped_echo( $colorarray[$count]);
									echo "\">";
										Escaped_echo( $line['country']);
										
									echo "</font>";
									
									echo "</a>";
									echo "</td>";
									
									echo "<td>";
									
									echo "<a href=\"ordersbycountry.php?passedOrderCountry=";
									Escaped_echo( $line['code']);
									echo "&passedOrderCountryName=";
									Escaped_echo( $line['country']);
									echo "\">";
									
										echo "<font color=\"";
										Escaped_echo( $colorarray[$count]);
										echo "\">";
										Escaped_echo( $line['count(tbl_order_header.scountry)']); 
										echo "</font>";
									echo "</a>";	
										$scountryarray[$count]=$line['count(tbl_order_header.scountry)'];
									echo "</b>";
									echo "</td>";
									echo "</tr>";
									$count++;
									}//end while
								?>
								</table>
								
								</td>
								<td>
								<table>
								<td align="left">
									<?php 	
										$GraphFile="countrystatsgraph2.gif";
										$GraphFile1="countrystatsgraph1.gif";
										//BarGraph($countryarray,"Country","No. Orders",370,300,5,$GraphFile1,3,$colorarray,true);
										PieChart($scountryarray,"Country","No. Orders",300,300,5,$GraphFile,8,$colorarray);
														
									?>
									
									<!--- //display the image using the file name + ?currenttime. this will force the browser to reload the image ---->
									
									<td align="center" valign="middle"><img src="<?php Escaped_echo( $GraphFile ."?" . time()) ?>"></td>	
									
									<!--- <td align="center" valign="bottom" colspan="2"><img src="<?php echo $GraphFile1 ."?" . time() ?>"></td>	
									--->
								</tr>
								</table>
								</td>
				</tr>
				
				
				<tr><td colspan="2" align="center"><h3><font color="blue">Shipping Type</font></h3></td></tr>
				<tr>
								<td>
								
								<table>
								<th>Shiptype</th><th>Orders</th>
								<?php
									$count=0;
									while ($line = mysql_fetch_array($stresult, MYSQL_ASSOC)) {
									
									echo "<tr>";
									echo "<td>";
									
									//echo "<a href=\"ordersbycountry.php?passedOrderCountry=";
									//echo $line['description'];
									//echo "&passedOrderCountryName=";
									//echo $line['description'];
									//echo "&shipping=true";
									//echo "\">";
									
									echo "<b>";
									echo "<font color=\"";
									echo $colorarray[$count];
									echo "\">";
										echo $line['description'];
										
									echo "</font>";
									
									//echo "</a>";
									echo "</td>";
									
									echo "<td>";
									
									//echo "<a href=\"ordersbycountry.php?passedOrderCountry=";
									//echo $line['code'];
									//echo "&passedOrderCountryName=";
									//echo $line['country'];
									//echo "\">";
									
									echo "<font color=\"";
										echo $colorarray[$count];
									echo "\">";
										echo $line['count(tbl_order_header.shipvia)']; 
										echo "</font>";
									//echo "</a>";	
										$stcountryarray[$count]=$line['count(tbl_order_header.shipvia)'];
									echo "</b>";
									echo "</td>";
									echo "</tr>";
									$count++;
									}//end while
								?>
								</table>
								
								</td>
								<td>
								<table>
								<td align="left">
									<?php 	
										$GraphFile="countrystatsgraph3.gif";
										$GraphFile1="countrystatsgraph1.gif";
										//BarGraph($countryarray,"Country","No. Orders",370,300,5,$GraphFile1,3,$colorarray,true);
										PieChart($stcountryarray,"Country","No. Orders",300,300,5,$GraphFile,8,$colorarray);
														
									?>
									
									<!--- //display the image using the file name + ?currenttime. this will force the browser to reload the image ---->
									
									<td align="center" valign="middle"><img src="<?php echo $GraphFile ."?" . time() ?>"></td>	
									
									<!--- <td align="center" valign="bottom" colspan="2"><img src="<?php echo $GraphFile1 ."?" . time() ?>"></td>	
									--->
								</tr>
								</table>
								</td>
				</tr>				
				
				
			</div>
		</form>
	</body>
</html>


