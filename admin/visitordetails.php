<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
include "graph.php";
include "randomcolor.php";
?>
<?php
if(isset($_GET['visitor']))
{
?>


<html>
<head></head>
<body>

<h2>Visitor Stats</h2>
<?php

$clean=array();
$mysql=array();

$clean['visitor']=form_clean($_GET['visitor'],50);

//Number of unique visits to site
//should just be able to count all the First pages
$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

$mysql['visitor']=mysql_real_escape_string($clean['visitor'],$link);

$query = "SELECT IP,Browser,Date,Time,Page,Referer,SessionID FROM visits WHERE SessionID='{$mysql['visitor']}';";
$visitresult = mysql_query($query) or die('Query failed: ' . mysql_error());

$row=mysql_fetch_array($visitresult,MYSQL_ASSOC);

?>
<table>
<th>IP</th><th>OS/Browser</th><th>Date</th><th>Time</th><th>Entry Page</th><th>Referer</th>
<tr>

<td><?php Escaped_echo( $row['IP']);?></td>
<td><?php Escaped_echo( $row['Browser']);?></td>
<td><?php Escaped_echo( $row['Date']);?></td>
<td><?php Escaped_echo( $row['Time']);?></td>
<td><?php Escaped_echo( $row['Page']);?></td>
<td><?php Escaped_echo( $row['Referer']);?></td>
</tr>
</table>

<?php

$EntryTime=$row['Date'] . " " .$row['Time'];

?>

<table>
<th>Time</th><th>Page</th>
<?php
while ($line= mysql_fetch_array($visitresult,MYSQL_ASSOC))
{
	
	echo "<tr>";
	echo "<td>";
	$EndTime=$line['Date'] . " " .  $line['Time'];
	Escaped_echo( $line['Time']);
	echo '<td align="right">';
?>
	<a href="<?php Escaped_echo($line['Page'])?>"><?php Escaped_echo($line['Page'])?></a>
<?php
	
	echo "</tr>";
}


//echo $EntryTime;
//echo $EndTime;

$EntryTime=strtotime($EntryTime);
$EndTime=strtotime($EndTime);

$Diff=$EndTime-$EntryTime;


//echo $Diff;

?>

<table>
<th>Total Time In Store</th>
<tr>
<td><?php Escaped_echo( date("H-i-s",$Diff))?></td>
</tr>
</table>


</table>
</body>
</html>
<?php
}
?>