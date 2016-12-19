<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php

include "graph.php";
include "randomcolor.php";

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

$query ="SELECT Page, COUNT(DISTINCT SessionId) as 'PageCount'
FROM Visits WHERE page NOT LIKE '%receipt%' AND page NOT LIKE '%search%' AND page NOT LIKE '%wgdev%'
GROUP BY Page
ORDER BY 'PageCount' DESC";

$pageUresult = mysql_query($query) or die('Query failed: ' . mysql_error());
?>

<html>
<style>
#visittable
{
	border:1px solid;
	padding:2px;
	
}

#visittable td{
	border:1px solid;
}


</style>
<table align="center" valign="top" align="right" id="visittable">
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
				<td STYLE="background:#<?php Escaped_echo ( $colorarray[$Count])?>"></td><td><a href="<?php Escaped_echo ( $row[0]);?>"><?php Escaped_echo ( $row[0]);?></a></td><td><?php Escaped_echo ( $row[1]); ?></td>
			</tr>
	
		<?php
			$Count++;
       		       }
?>
		

		</table>
<!-- <table>
	<tr>
	<td  valign="top" align="left" rowspan="2">
		<?php 	
		//$GraphFile="pagehitstats.gif";
		//BarGraph($VistStats,"Page","No. Hits",300,300,5,$GraphFile,3,$colorarray,true);
		//PieChart($VistStats,"Page","No. Hits",300,300,5,$GraphFile,8,$colorarray);
														
		?>
									
		<!--- //display the image using the file name + ?currenttime. this will force the browser to reload the image ---->
<!--		<td align="center" valign="middle"><img src="<?php echo $GraphFile ."?" . time() ?>"></td>	
	</td>
	</tr>
</table>
-->

</html>