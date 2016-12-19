<?php
	include("include/siteconstants.php");
	include("include/commonfunctions.php");
if( isset($_GET['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_GET['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{	
	$clean=array();
	$clean['session'] = session_id();
	//"-" removed from list of characters to remove as products have this in code
	$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");	
	$clean['upc'] = form_clean($_GET['number'], 20,$badchars);
	
	if(is_numeric($_GET['id']))
	{
		$clean['basketid']=$_GET['id'];
			
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
				
		$mysql=array( );
		//check for custom infomation for this item
		//this can then be used to find the unique item in the basket
		if(isset($_GET['custom']))
		{
			$clean['custom']=form_clean($_GET['custom'],240);
			$mysql['custom']=mysql_real_escape_string($clean['custom'],$link);
		}
		
		$mysql['session']=mysql_real_escape_string($clean['session'],$link);
		$mysql['upc']=mysql_real_escape_string($clean['upc'],$link);
		$mysql['basketid']=mysql_real_escape_string($clean['basketid'],$link);
		
		
		if(isset($mysql['custom']))
			$query = "DELETE FROM tbl_baskets WHERE basketid='{$mysql['basketid']}' AND sessionid = '{$mysql['session']}' AND itemcode = '{$mysql['upc']}' AND custom='{$mysql['custom']}';";
		else
			$query = "DELETE FROM tbl_baskets WHERE basketid='{$mysql['basketid']}' AND sessionid = '{$mysql['session']}' AND itemcode = '{$mysql['upc']}';";
			
		mysql_query($query) or die('Query failed: ' . mysql_error());
	
		// Closing connection
		mysql_close($link);
	}
	// Redirect to the basket
	header('Location: basket.php');
	exit();
}
else
{
	//token problem
	header('location:index.php');
	exit();
}
?>