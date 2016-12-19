<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
?>
<html>
	<head>
		<link rel="stylesheet" href="style/adminstyle.css" type="text/css" />
	</head>
	<body topmargin="0">
		
			<div align="center">
				
				
				<?php include("menu.php"); ?>
				<br />
				<h3>Edit Featured Products</h3>
				<p>
				
				Pick a Department
				<?php
					$query = "SELECT deptcode,name FROM tbl_departments WHERE under=0 ORDER BY deptcode ASC";
					$result = mysql_query($query) or die('Query failed: ' . mysql_error());
				?>
				<form action="editFeatured2.php" method="get">
					<select name="dept">
					<?php
						while($line = mysql_fetch_array($result,MYSQL_ASSOC))
						{
					?>
							<option value="<?php Escaped_echo( $line['deptcode']);?>"><?php Escaped_echo( $line['name'])?></option>
					<?php
							
						}
					?>
					</select>
									
					<input type="submit" value="Select"> 
										
				</form>
				
				</table>
				
				
				</p>
				<p>
						
			
				<?php
				
				
				?>
			</div>
	
	</body>
</html>