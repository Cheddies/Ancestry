<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
	$clean=array();
	$mysql=array();
	if(isset($_GET['number1']) && 
	isset($_GET['number2']) && 
	isset($_GET['number3']) && 
	isset($_GET['number4']) && 
	isset($_GET['number5']) && 
	isset($_GET['desc1']) && 
	isset($_GET['desc2']) && 
	isset($_GET['desc3']) && 
	isset($_GET['desc4']) && 
	isset($_GET['desc5']) &&
	isset($_GET['dept'])
	)
	{
	$clean['dept']=form_clean($_GET['dept'],4);
	
	
	$clean['number1']=form_clean($_GET['number1'],20);
	$clean['number2']=form_clean($_GET['number2'],20);
	$clean['number3']=form_clean($_GET['number3'],20);
	$clean['number4']=form_clean($_GET['number4'],20);
	$clean['number5']=form_clean($_GET['number5'],20);
	
	$clean['desc1']=ltrim(rtrim(form_clean($_GET['desc1'],255)));
	$clean['desc2']=ltrim(rtrim(form_clean($_GET['desc2'],255)));
	$clean['desc3']=ltrim(rtrim(form_clean($_GET['desc3'],255)));
	$clean['desc4']=ltrim(rtrim(form_clean($_GET['desc4'],255)));
	$clean['desc5']=ltrim(rtrim(form_clean($_GET['desc5'],255)));
		
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$mysql['number1']=mysql_real_escape_string($clean['number1'],$link);
	$mysql['number2']=mysql_real_escape_string($clean['number2'],$link);
	$mysql['number3']=mysql_real_escape_string($clean['number3'],$link);
	$mysql['number4']=mysql_real_escape_string($clean['number4'],$link);
	$mysql['number5']=mysql_real_escape_string($clean['number5'],$link);
	
	$mysql['desc1']=mysql_real_escape_string($clean['desc1'],$link);
	$mysql['desc2']=mysql_real_escape_string($clean['desc2'],$link);
	$mysql['desc3']=mysql_real_escape_string($clean['desc3'],$link);
	$mysql['desc4']=mysql_real_escape_string($clean['desc4'],$link);
	$mysql['desc5']=mysql_real_escape_string($clean['desc5'],$link);
	
	$mysql['dept']=mysql_real_escape_string($clean['dept'],$link);
	
	$update="UPDATE featured SET number = '{$mysql['number1']}' , featured.desc = \"{$mysql['desc1']}\" WHERE featnum=1 AND dept={$mysql['dept']}";
	$result = mysql_query($update) or die('Query failed: ' . mysql_error());
	
	//echo "Line 1:";
	//echo mysql_affected_rows ($link);
	//echo "<br />";
	
	if (mysql_affected_rows ($link) ==0 )
	{
		$insert = "REPLACE INTO featured VALUES('{$mysql['number1']}',{$mysql['dept']},\"{$mysql['desc1']}\",1)";
		$result = mysql_query($insert) or die('Query failed: ' . mysql_error());
	}
	
	$update="UPDATE featured SET number='{$mysql['number2']}',featured.desc=\"{$mysql['desc2']}\" WHERE featnum=2 AND dept={$mysql['dept']}";
	$result = mysql_query($update) or die('Query failed: ' . mysql_error());
	
	//echo "Line 2:";
	//echo mysql_affected_rows ($link);
	//echo "<br />";
	
	if (mysql_affected_rows ($link) ==0 )
	{
		$insert = "REPLACE INTO featured VALUES('{$mysql['number2']}',{$mysql['dept']},\"{$mysql['desc2']}\",2)";
		$result = mysql_query($insert) or die('Query failed: ' . mysql_error());
	}
	
	$update="UPDATE featured SET number = '{$mysql['number3']}' , featured.desc = \"{$mysql['desc3']}\" WHERE featnum=3 AND dept={$mysql['dept']}";
	$result = mysql_query($update) or die('Query failed: ' . mysql_error());
	
	//echo "Line 3:";
	//echo mysql_affected_rows ($link);
	//echo "<br />";
	
	if (mysql_affected_rows ($link) ==0 )
	{
		$insert = "REPLACE INTO featured VALUES('{$mysql['number3']}',{$mysql['dept']},\"{$mysql['desc3']}\",3)";
		$result = mysql_query($insert) or die('Query failed: ' . mysql_error());
	}
	
	$update="UPDATE featured SET number = '{$mysql['number4']}' , featured.desc = \"{$mysql['desc4']}\" WHERE featnum=4 AND dept={$mysql['dept']}";
	$result = mysql_query($update) or die('Query failed: ' . mysql_error());
	
	//echo "Line 4:";
	//echo mysql_affected_rows ($link);
	//echo "<br />";
	
	if (mysql_affected_rows ($link) ==0 )
		{
			$insert = "REPLACE INTO featured VALUES('{$mysql['number4']},{$mysql['dept']},\"{$mysql['desc4']}\",4)";
			//echo $insert;
			$result = mysql_query($insert) or die('Query failed: ' . mysql_error());
		}
	
	$update="UPDATE featured SET number = '{$mysql['number5']}',featured.desc = \"{$mysql['desc5']}\" WHERE featnum=5 AND dept={$mysql['dept']}";
	$result = mysql_query($update) or die('Query failed: ' . mysql_error());
	
	//echo "Line 5:";
	//echo mysql_affected_rows ($link);
	//echo "<br />";
	
	if (mysql_affected_rows ($link) ==0 )
		{
			$insert = "REPLACE INTO featured VALUES('{$mysql['number5']}',{$mysql['dept']},\"{$mysql['desc5']}\",5)";
			//echo $insert;
			$result = mysql_query($insert) or die('Query failed: ' . mysql_error());
		}
		echo "Redirecting to 2 with Dept set";
	header ("location : editFeatured2.php?dept={$clean['dept']}");
?>
<?php
}
else
{
	echo "Redirecting to 2 with nothing set";
	header('location:editFeatured2.php');
	exit();
}
?>
