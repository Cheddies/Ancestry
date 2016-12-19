<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
if(isset($_GET['date']))
{
?>
	<html>
	<table>
	<th width="100">IP</th><th width="100">Referer</th><th width="100">Date</th><th  width="100">Total</th>
	<?php
	include "graph.php";
	include "randomcolor.php";
	
	$clean=array();
	$mysql=array();
	
	$clean['date']=form_clean($_GET['date'],10);
	
	
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$mysql['date']=mysql_real_escape_string($clean['date'],$link);
	
	$query = "SELECT SessionID,IP,Browser,Date,Time,Page,Referer,Count(DISTINCT SessionID) as Total FROM visits WHERE First='1' AND Date='{$mysql['date']}' GROUP BY Time ORDER BY  Date DESC,Time ASC;";
	$visitresult = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	
	$Total=0;
	while ($line=mysql_fetch_array($visitresult,MYSQL_ASSOC))
	{
		
	?>
	
	<tr>
		<td><a href="visitordetails.php?visitor=<?php Escaped_echo( $line['SessionID'])?>">View</a></td><td><a href="<?php Escaped_echo( $line['Referer'])?>"><?php Escaped_echo( $line['Referer'])?></a></td><td ><a href="visitsbyday.php?date=<?php Escaped_echo( $line['Date'])?>"><?php Escaped_echo( $line['Date'])?></a></td> <td align="right"><?php Escaped_echo( $line['Total'])?></td><td><?php Escaped_echo( $line['Time'])?></td>
	</tr>
	
	
	<?php
	
	$Total=$line['Total']+$Total;
	}
	?>
	
	
	<tr>
		<td>Total</td><td align="right"><?php Escaped_echo( $Total)?></td>
	</tr>
	
	</table>
	
	</html>
<?php
}
?>