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
	<head>
		<link rel="stylesheet" href="style/adminstyle.css" type="text/css" />
	</head>
	<body topmargin="0">
		<form action="checkpass.php" method="post">
			<div align="center">
				
				
				<?php include("menu.php"); ?>
				<br />
				<table>
				
				<h3>Visit Stats Page</h3>
</div>
<?php

//Number of unique visits to site
//should just be able to count all the First pages
$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

$query = "SELECT IP,Browser,Date,Time,Page,Referer,SessionID FROM visits WHERE First='1' GROUP BY SessionId ORDER BY  Date DESC,Time DESC;";
$visitresult = mysql_query($query) or die('Query failed: ' . mysql_error());

$UniqueVisits=mysql_num_rows($visitresult);

$query = "SELECT Page, COUNT(DISTINCT SessionId) as 'PageCount' From visits WHERE Page LIKE '%products.php?master=____' GROUP BY Page ORDER BY 'PageCount' DESC";
$catresult= mysql_query($query) or die ('Query failed: ' . mysql_error());

$query = "SELECT Page, COUNT(DISTINCT SessionId) as 'PageCount' From visits WHERE Page LIKE '%products.php?master=____&dept=____' GROUP BY Page ORDER BY 'PageCount' DESC";
$subcatresult= mysql_query($query) or die ('Query failed: ' . mysql_error());


?>
<div class="visits">

<?php Escaped_echo( "Number of Visits: $UniqueVisits");?>
<table  class="visitstable" summary="List of departments and visits">
<tr>
	<th>Key</th><th>Dept</th><th>Visits</th><th>View</th>
</tr>

<?php
$Count=0;
while ($line=mysql_fetch_array($catresult,MYSQL_BOTH))
{
	
	//find master dept
	//find where the = is
	$Start=stripos($line['Page'],"=");
	$dept=substr($line['Page'],$Start+1,4);
	
	//Get a color array for the chart
 	$colorarray[$Count]=randomcolor();
	 		
	$randnum=rand(0,1);
	if ($randnum==1)
	{
		$colorarray[$Count]=randomcolor();
 	}
 	$CatStats[$Count]=$line[1];
?>
	<tr>
		<td STYLE="background:#<?php Escaped_echo( $colorarray[$Count])?>"></td><td><?php Escaped_echo( $dept);?></td><td><?php Escaped_echo( $line['PageCount'])?></td>
		<td><a href="<?php Escaped_echo( $line['Page'])?>">View</a></td>
	</tr>
<?php	
	$Count++;
}

?>

</table>
<?php
	$GraphFile="catstats.gif";
	PieChart($CatStats,"Page","No. Hits",300,300,5,$GraphFile,8,$colorarray);
	if(is_file($GraphFile))
	{
?>
<img  class="visitsimg" src="<?php Escaped_echo( $GraphFile ."?" . time()) ?>">
<?php
	}
?>

<table class="visitstable" summary="List of departments and visits">
<tr>
	<th>Key</th><th>Dept</th><th>Visits</th><th>View</th>
</tr>

<?php
$Count=0;
while ($line=mysql_fetch_array($subcatresult,MYSQL_BOTH))
{
	
	//find master dept
	//find where the = is
	$Start=stripos($line['Page'],"dept=");
	$dept=substr($line['Page'],$Start+5,4);
	
	//Get a color array for the chart
 	$colorarray[$Count]=randomcolor();
	 		
	$randnum=rand(0,1);
	if ($randnum==1)
	{
		$colorarray[$Count]=randomcolor();
 	}
 	$SubCatStats[$Count]=$line[1];
?>
	<tr>
		<td STYLE="background:#<?php Escaped_echo( $colorarray[$Count])?>"></td><td><?php Escaped_echo( $dept);?></td><td><?php Escaped_echo( $line['PageCount'])?></td>
		<td><a href="<?php Escaped_echo( $line['Page'])?>">View</a></td>
	</tr>
<?php	
	$Count++;
}

?>

</table>
<?php
	$GraphFile="subcatstats.gif";
	PieChart($SubCatStats,"Page","No. Hits",300,300,5,$GraphFile,8,$colorarray);
?>
<img class="visitsimg"  src="<?php Escaped_echo( $GraphFile ."?" . time() )?>">


</div>


			</div>
		</form>
	</body>
</html>
