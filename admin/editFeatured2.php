<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
?>
<?php
if(isset($_GET['dept']))
{
?>
<html>
<head>
	<link rel="stylesheet" href="style/adminstyle.css" type="text/css" />
</head>
<body topmargin="0">

<div align="center">
	<?php include("menu.php"); ?>
	<h3>Edit Featured Products</h3>
	<a href="editFeatured.php">Change Departments</a>
	<p>
		<table border="1" cellspacing="1" cellpadding="1" width="200">
	<tr>
		<th width="10">Number</th><th>Code</th><th>Update</th>
	</tr>
	<form action="editFeatured3.php" method="get">
	<?php
	$clean=array();
	$clean['dept']=form_clean($_GET['dept'],4);
	
	?>
	<input type="hidden" value="<?php Escaped_echo( $clean['dept']);?>" name="dept">
	<?php
		$mysql=array();
		$mysql['dept']=mysql_real_escape_string($clean['dept'],$link);
		$query = "SELECT * FROM featured WHERE dept={$mysql['dept']} ORDER BY featnum ASC";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$count=1;
		while($line = mysql_fetch_array($result,MYSQL_ASSOC))
		{
	?>
			<tr>
				<td valign="top" width="10"><?php Escaped_echo( $line['featnum']);?></td>
				<td valign="top" width="50">
				<?php
					$query ="SELECT tbl_products.name,tbl_products_departments.number 
					FROM tbl_departments 
					RIGHT JOIN tbl_products_departments
					ON tbl_departments.deptcode=tbl_products_departments.deptcode
					LEFT JOIN tbl_products
					ON tbl_products.number=tbl_products_departments.number
					WHERE under={$mysql['dept']}";
	
				//	$result2 = mysql_query($query) or die('Query failed: ' . mysql_error());
				?>
				<!--<select name="number<?php echo $count?>">
				<option value="">No Product</option>
				<?php
				/*	while($line2 = mysql_fetch_array($result2,MYSQL_ASSOC))
					{
						if($line2['number']==$line['number'])
						{
				?>
					<option selected="true" value="<?php echo $line2['number']?>"><?php echo  "(" . $line2['number'] . ")" .  substr($line2['name'],0,25). "..." ?></option>
				<?php
						}
						else
						{
				?>
					<option  value="<?php echo $line2['number']?>"><?php echo  "(" . $line2['number'] . ")" . substr($line2['name'],0,25) ."..." ?></option>
				<?php
						}
					}*/
				?>
				</select>-->
				<input type="text" maxlength="20" size="20" value="<?php Escaped_echo( $line['number'])?>" name="number<?php Escaped_echo( $count)?>">
				</td>
				<td valign="top">
				<input type="submit" value="Update">
				</td>
			</tr>
			<tr>
				<td colspan="3" width="100" valign="top"><textarea cols="44" rows="8" name="desc<?php Escaped_echo( $count)?>"/><?php Escaped_echo( $line['desc']);?></textarea></td>
			</tr>
	<?php
		$count++;
	}
	if($count<=5)
	{
		for($i=$count;$i<6;$i++)
		{
		?>
		<tr>
			<td><?php Escaped_echo( $i);?></td>
			<td valign="top" width="50">
				<!--<select name="number<?php echo $i?>">
				<option value="" selected="true">No Product</option>
				<?php
					//output blank lines 
					$query ="SELECT tbl_products.name,tbl_products_departments.number 
					FROM tbl_departments 
					RIGHT JOIN tbl_products_departments
					ON tbl_departments.deptcode=tbl_products_departments.deptcode
					LEFT JOIN tbl_products
					ON tbl_products.number=tbl_products_departments.number
					WHERE under={$mysql['dept']}";
			
				/*$result2 = mysql_query($query) or die('Query failed: ' . mysql_error());
				while($line2 = mysql_fetch_array($result2,MYSQL_ASSOC))
				{
				?>
					<option value="<?php echo $line2['number']?>"><?php echo  "(" . $line2['number'] . ")" . $line2['name'] ?></option>
				<?php
				}*/
				?>
				</select> -->
			<input type="text" maxlength="20" size="20" value="" name="number<?php Escaped_echo( $count)?>">
					
			</td>
			<td valign="top">
				<input type="submit" value="Update">
			</td>
			</tr>
			<tr>
				<td colspan="3" width="100" valign="top"><textarea cols="50" rows="8" name="desc<?php Escaped_echo( $i)?>"/><?php Escaped_echo( $line['desc']);?></textarea></td>
			</tr>
							
							<?php
							
							}
					}
				?>
							</table>
				
				
				</p>
				<p>
						
			
				<?php
				
				
				?>
			</div>
	
	</body>
</html>
</form>
<?php
}
else
{
	header('location:editFeatured.php');
	exit();
	
}
?>