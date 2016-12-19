<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>

<html>
<table>
<th width="100">Date</th><th  width="100">Total</th>
<?php
include "graph.php";
include "randomcolor.php";

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');
$query = "SELECT IP,Browser,Date,Time,Page,Referer,Count(DISTINCT SessionID) as Total FROM visits WHERE First='1' GROUP BY Date ORDER BY  Date DESC,Time DESC;";
$visitresult = mysql_query($query) or die('Query failed: ' . mysql_error());


$Total=0;
while ($line=mysql_fetch_array($visitresult,MYSQL_ASSOC))
{
	
?>

<tr>
	<td ><a href="visitsbyday.php?date=<?php Escaped_echo( $line['Date'])?>"><?php Escaped_echo( $line['Date'])?></a></td> <td align="right"><?php Escaped_echo( $line['Total'])?></td>	
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