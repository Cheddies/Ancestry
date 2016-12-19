<?php include "logincheck.php"?>

<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php


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
				
				<h3>Stats Page</h3>
				<p>
				<a href="statscountry.php">Number of orders per country stats</a>
				</p>
				<p>
				<a href="statsprod.php">Products stats</a>
				</p>
				<p>
				<a href="newvisitstats.php">Visit stats</a> 
				</p>
				<p>
				<a href="frooglestats.php">Froogle Stats</a>
				</p>
				</table>
				<?php
				
				
				?>
			</div>
		</form>
	</body>
</html>