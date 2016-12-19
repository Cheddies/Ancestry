<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
include "graph.php";
include "randomcolor.php";


function BrowserType($BrowserString)
{
	//echo $BrowserString;
	if (stripos ( $BrowserString,"MSIE" )!== false)
	{
		//browser is Internet Explorer
		return "Internet Explorer";
	}
	else
	{
		if (stripos ( $BrowserString,"Mozilla" )!== false)
		{
			
			if( stripos( $BrowserString,"Firefox")!== false)
			{
				return "Firefox";
			}
			else
			{
				return "Mozilla";
			}
		}	
	}
		
	
	//$browser = get_browser($BrowserString, true);
	//return $browser[2];
	
}

function OSType ($OSString)
{
	if (stripos ( $OSString,"Windows" )!== false)
	{
		return "Windows";
		
	}
	else
	{
		if (stripos ( $OSString,"Linux" )!== false)
		{
			return "Linux";
		}
		else
		{
			if (stripos ( $OSString,"Mac" )!== false)
			{
				return "Mac";
			}
			else
			{
				return "Other";
			}
		}
	}
	
}


?>
<html>
<head></head>
<body>

<h2>Visit Stats</h2>
<?php

//Number of unique visits to site
//should just be able to count all the First pages
$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');
$query = "SELECT IP,Browser,Date,Time,Page,Referer,SessionID FROM visits WHERE First='1' GROUP BY SessionId ORDER BY  Date DESC,Time DESC;";
$visitresult = mysql_query($query) or die('Query failed: ' . mysql_error());

$UniqueVisits=mysql_num_rows($visitresult);


$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

//$query = "SELECT Count(Page),Page From visits WHERE page!='/wgdev/index.php' Order by Count(Page) Group By Page ;";


//Live site

$query ="SELECT Page, COUNT(*) as 'PageCount'
FROM Visits WHERE page NOT LIKE '%index.php%'
GROUP BY Page
ORDER BY 'PageCount' DESC LIMIT 10";


//Test Site
/*
$query ="SELECT Page, COUNT(*) as 'PageCount'
FROM Visits WHERE page!='/wgdev/index.php'
GROUP BY Page
ORDER BY 'PageCount' DESC LIMIT 10";
*/
$pageresult = mysql_query($query) or die('Query failed: ' . mysql_error());


$query ="SELECT Page, COUNT(DISTINCT SessionId) as 'PageCount'
FROM Visits WHERE page NOT LIKE '%index.php%'
GROUP BY Page
ORDER BY 'PageCount' DESC LIMIT 10";

$pageUresult = mysql_query($query) or die('Query failed: ' . mysql_error());


$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

$query = "SELECT Count(Browser) as Number, Browser From visits WHERE First='1'  Group by Browser ORDER BY Number DESC LIMIT 10;";

$osresult = mysql_query($query) or die('Query failed: ' . mysql_error());



$query = "SELECT Page, COUNT(DISTINCT SessionId) as 'PageCount' From visits WHERE Page LIKE '%search.php%' GROUP BY Page ORDER BY 'PageCount' DESC LIMIT 10 ";
$searchresult= mysql_query($query) or die ('Query failed: ' . mysql_error());


$query = "SELECT Referer, COUNT(DISTINCT SessionId) as 'PageCount' From visits WHERE Referer NOT LIKE '%www.aardmarket.com%' AND Referer NOT LIKE '%Unknown%' GROUP BY Referer ORDER BY 'PageCount' DESC LIMIT 10 ";
$refresult= mysql_query($query) or die ('Query failed: ' . mysql_error());


$query = "SELECT Page, COUNT(DISTINCT SessionId) as 'PageCount' From visits WHERE Page LIKE '%productdetail.php%' GROUP BY Page ORDER BY 'PageCount' DESC LIMIT 10 ";
$productresult= mysql_query($query) or die ('Query failed: ' . mysql_error());


$query = "SELECT Referer,  COUNT(DISTINCT SessionId) as 'PageCount' From visits WHERE Referer LIKE '%google%' GROUP BY Referer ORDER BY 'PageCount' DESC LIMIT 10 ";
$googleresult= mysql_query($query) or die ('Query failed: ' . mysql_error());



$query = "SELECT Referer,  COUNT(DISTINCT SessionId) as 'PageCount' From visits WHERE Referer LIKE '%yahoo%' GROUP BY Referer ORDER BY 'PageCount' DESC LIMIT 10 ";
$yahooresult= mysql_query($query) or die ('Query failed: ' . mysql_error());


$query = "SELECT Referer,  COUNT(DISTINCT SessionId) as 'PageCount' From visits WHERE Referer LIKE '%msn%' GROUP BY Referer ORDER BY 'PageCount' DESC LIMIT 10 ";
$msnresult= mysql_query($query) or die ('Query failed: ' . mysql_error());

?>

<h3>Number of unique visits to site</h3>

<h3><?php Escaped_echo( $UniqueVisits);?></h3>

<table width="750">
<tr>
	<td colspan="7"><a href="allvisits.php">View Total Visits By Day</a></td>
</tr>
<tr>
<td colspan="7"><h3>Last 10 Visitor's Information<h3></td>
</tr>
<tr>
	<td><h4>Ip</h4></td><td><h4>OS/Browser</h4></td><td><h4>Date</h4></td><td><h4>Time</h4></td><td><h4>Enter Page</h4></td><td><h4>Referer</h4></td><td><h4>More</h4></td>
</tr>
<?php
  $Count=0;
  while($row=mysql_fetch_row($visitresult))
 {
 $Count++;
 if($Count==11)
 	break;
?>
<tr>
<td><?php Escaped_echo( $row[0]) ?></td><td><?php Escaped_echo(  OSType($row[1]) . "/"  . BrowserType($row[1]) )?></td><td><?php Escaped_echo( $row[2] )?></td><td><?php Escaped_echo( $row[3] )?></td><td><?php Escaped_echo( $row[4] )?></td><td><?php Escaped_echo( $row[5]) ?></td><td><a href="visitordetails.php?visitor=<?php Escaped_echo( $row[6])?>">View</a></td>
</tr>
<?php
 }
?>
</table>


<table align="center" valign="top" align="right">
	<tr>
		<td align="center" colspan="2"><h3>Top Ranked Pages (not including index.php)</h3></td>
	</tr>
	<tr>
	<td valign="center">
		<table>
		<tr>
			<td>Key</td><td>Page</td><td>None Unique Hits</td>
		</tr>
		<?php 

			 $VistStats=array(mysql_num_rows($pageresult));
 
			 //get some colors
			 $colorarray=array(mysql_num_rows($pageresult));
			
			 $Count=0;
 			
 			while($row=mysql_fetch_row($pageresult))
 			{
 				//Get a color array for the chart
 				$colorarray[$Count]=randomcolor();
	 		
				$randnum=rand(0,1);
				if ($randnum==1)
				{
					$colorarray[$Count]=randomcolor();
 				}
 				$VistStats[$Count]=$row[1];
			?>	
			<tr>
				<td STYLE="background:#<?php Escaped_echo( $colorarray[$Count])?>"></td><td><a href="<?php Escaped_echo( $row[0]);?>"><?php Escaped_echo( $row[0]);?></a></td><td><?php Escaped_echo( $row[1]); ?></td>
			</tr>
	
		<?php
			$Count++;
       		       }
?>

		</table>
	</td>
	<td  valign="top" align="left" rowspan="2">
		<?php 	
		$GraphFile="pagehitstats.gif";
		//BarGraph($countryarray,"Country","No. Orders",370,300,5,$GraphFile,3,$colorarray,true);
		PieChart($VistStats,"Page","No. Hits",300,300,5,$GraphFile,8,$colorarray);
		if(is_file($GraphFile))
		{												
		?>
									
		<!--- //display the image using the file name + ?currenttime. this will force the browser to reload the image ---->
		<td align="center" valign="middle"><img src="<?php Escaped_echo( $GraphFile ."?" . time() )?>"></td>	
		<?php
		}
		?>
	</td>
	</tr>
</table>

<table align="center" valign="top" align="right">
	<tr>
		<td align="center" colspan="2"><h3>Top Ranked Pages (not including index.php)</h3></td>
	</tr>
	<tr>
	<td valign="center">
		<table>
		<tr>
			<td>Key</td><td>Page</td><td>Unique Visits</td>
		</tr>
		<?php 

			 $VistStats=array(mysql_num_rows($pageUresult));
 
			 //get some colors
			 $colorarray=array(mysql_num_rows($pageUresult));
			
			 $Count=0;
 			
 			while($row=mysql_fetch_row($pageUresult))
 			{
 				//Get a color array for the chart
 				$colorarray[$Count]=randomcolor();
	 		
				$randnum=rand(0,1);
				if ($randnum==1)
				{
					$colorarray[$Count]=randomcolor();
 				}
 				$VistStats[$Count]=$row[1];
			?>	
			<tr>
				<td STYLE="background:#<?php Escaped_echo( $colorarray[$Count])?>"></td><td><a href="<?php Escaped_echo( $row[0]);?>"><?php Escaped_echo( $row[0]);?></a></td><td><?php Escaped_echo( $row[1]); ?></td>
			</tr>
	
		<?php
			$Count++;
       		       }
?>
		<tr>
			<td><a href="allpagevisits.php">View all page visits</a></td>
		</tr>
		</table>
	</td>
	<td  valign="top" align="left" rowspan="2">
		<?php 	
		$GraphFile="pageUhitstats.gif";
		//BarGraph($countryarray,"Country","No. Orders",370,300,5,$GraphFile,3,$colorarray,true);
		PieChart($VistStats,"Page","No. Hits",300,300,5,$GraphFile,8,$colorarray);
														
		?>
									
		<!--- //display the image using the file name + ?currenttime. this will force the browser to reload the image ---->
		<td align="center" valign="middle"><img src="<?php Escaped_echo( $GraphFile ."?" . time()) ?>"></td>	
	</td>
	</tr>
</table>


<h3>Top Ten Search Terms</h3>

<table>
<th>Search</th><th>Times</th>
<?php while($row=mysql_fetch_row($searchresult))
 {
 
?>	
	<tr>
	<td><?php Escaped_echo( $row[1]);?></td><td><a href="<?php Escaped_echo( $row[0]); ?>"><?php Escaped_echo( $row[0]); ?></a></td>
	</tr>
	
<?php
 }
?>

</table>



<h3>Top Ten Looked at Products</h3>

<table>
<th>Product</th><th>Unique Views</th><th>Page</th><th>Number Sold</th><th>Conversion %</th>
<?php while($row=mysql_fetch_row($productresult))
 {
 
	 //Find out how many sold
	 
	 //Split item code out of page url
	 $Length=strlen($row[0]);
	 $Start=strpos ( $row[0], "=" );
	 $ProductCode = substr($row[0], $Start+1, $Length);
	 
	 $query = "SELECT count(itemcode),itemcode,name FROM tbl_baskets,tbl_order_header WHERE tbl_order_header.ordernumber=tbl_baskets.sessionid  AND tbl_baskets.itemcode='{$ProductCode}' GROUP BY tbl_baskets.itemcode;";
	
	 //echo $query;
	 
	 $productsoldresult = mysql_query($query) or die('Query failed: ' . mysql_error());
	 
	 $productsoldrow=mysql_fetch_row($productsoldresult);
	 
	 $NumberSold=$productsoldrow[0];
	 
	 $Ratio=$NumberSold/$row[1];
	 
	 $Ratio=round($Ratio*100,2);
	 
?>	
	<tr>
	<td><?php Escaped_echo( $productsoldrow[2])?></td><td><?php Escaped_echo( $row[1]);?></td><td><a href="<?php Escaped_echo( $row[0]); ?>"><?php Escaped_echo( $row[0]); ?></a></td><td><?php Escaped_echo( $NumberSold)?></td><td><?php Escaped_echo( $Ratio)?>%</td>
	</tr>
	
<?php
 }
?>

</table>

<h3>Top Ten Referers</h3>

<table>
<th>Referer</th><th>Visits</th>
<?php while($row=mysql_fetch_row($refresult))
 {
 
?>	
	<tr>
	<td><a href="<?php Escaped_echo( $row[0]); ?>"><?php Escaped_echo( $row[0]);?></a></td><td><?php Escaped_echo( $row[1]); ?></td>
	</tr>
	
<?php
 }
?>

</table>

<h3>Top Ten Google Referers</h3>

<table>
<th>Referer</th><th>Visits</th>
<?php while($row=mysql_fetch_row($googleresult))
 {
 
?>	
	<tr>
	<td><a href="<?php Escaped_echo( $row[0]); ?>"><?php Escaped_echo( $row[0]);?></a></td><td><?php Escaped_echo( $row[1]); ?></td>
	</tr>
	
<?php
 }
?>

</table>

<h3>Top Ten Yahoo Referers</h3>

<table>
<th>Referer</th><th>Visits</th>
<?php while($row=mysql_fetch_row($yahooresult))
 {
 
?>	
	<tr>
	<td><a href="<?php Escaped_echo( $row[0]); ?>"><?php Escaped_echo( $row[0]);?></a></td><td><?php Escaped_echo( $row[1]); ?></td>
	</tr>
	
<?php
 }
?>

</table>

<h3>Top Ten MSN Referers</h3>

<table>
<th>Referer</th><th>Visits</th>
<?php while($row=mysql_fetch_row($msnresult))
 {
 
?>	
	<tr>
	<td><a href="<?php Escaped_echo( $row[0]); ?>"><?php Escaped_echo( $row[0]);?></a></td><td><?php Escaped_echo( $row[1]); ?></td>
	</tr>
	
<?php
 }
?>

</table>

<h3>Top 10 - OS / Browser</h3>

<table>
<th>OS + Browser</th><th>Visits</th>
<?php while($row=mysql_fetch_row($osresult))
 {
 
?>	
	<tr>
	<td><?php Escaped_echo( $row[1]);?></td><td><?php Escaped_echo( $row[0]); ?></td>
	</tr>
	
<?php
 }
?>

</table>

</body>



</html>
