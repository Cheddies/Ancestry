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
$query = "SELECT count(itemcode),itemcode,name FROM tbl_baskets,tbl_order_header WHERE tbl_order_header.ordernumber=tbl_baskets.sessionid AND authorised=1 GROUP BY tbl_baskets.itemcode;";

$result = mysql_query($query) or die('Query failed: ' . mysql_error());

//get number of rows to know when to output last year in loop
$NumRows = mysql_num_rows($result);

$countryarray=array($NumRows);
$colorarray=array($NumRows);

// Closing connection
mysql_close($link);

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


?>
<html>
	<head>
		<link rel="stylesheet" href="style/adminstyle.css" type="text/css" />
	</head>
	<body topmargin="0">
		<form action="checkpass.php" method="post">
			<div align="center">
				
				<?php include("menu.php"); ?>
				<table><th colspan="2" align="center"><h4>Number of each product bought</h4></th>
				<tr>
				<td>
				
				<table border="1" cellspacing="2" cellpadding="2">
				<th>Product</th><th>Orders</th>
				<?php
					$count=0;
					$Total=0;
					while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					
					echo "<tr>";
					echo "<td>";
					echo "<b>";
					echo "<font color=\"";
					Escaped_echo( $colorarray[$count]);
					echo "\">";
						Escaped_echo( $line['itemcode']);Escaped_echo( $line['name']);
						
					echo "</font>";
					echo "</td>";
					
					echo "<td>";
						echo "<font color=\"";
						Escaped_echo( $colorarray[$count]);
						echo "\">";
						Escaped_echo( $line['count(itemcode)']); 
						echo "</font>";
						
						$countryarray[$count]=$line['count(itemcode)'];
						$Total=$Total+$line['count(itemcode)'];
					echo "</b>";
					echo "</td>";
					echo "</tr>";
					$count++;
					}//end while
				?>
				<tr><td>Total</td><td><?php Escaped_echo($Total)?></td></tr>;
				?>
				
				</table>
				
				</td>
				<td>
				<table>
				<td align="left">
					<?php 	
						$GraphFile="countrystatsgraph.gif";
						//BarGraph($countryarray,"Product","No. Orders",370,300,5,$GraphFile,3,$colorarray,true);
						PieChart($countryarray,"Country","No. Orders",300,300,5,$GraphFile,8,$colorarray);
						if(is_file($GraphFile))
						{
					?>
						
					<td align="center" valign="bottom"><img src="<?php Escaped_echo( $GraphFile ."?" . time())?>"></td>	
					<?php
						}
					?>					
								
					</td>
				</tr>
				</table>
				</td>
				</tr>
				
			</div>
		</form>
	</body>
</html>


