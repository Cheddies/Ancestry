<?php

	require_once('include/siteconstants.php');
	require_once('include/commonfunctions.php');
	
	
	
	$mysql=array();
	
	if(isset($_SERVER['HTTP_REFERER']))
	{
		$referer=$_SERVER['HTTP_REFERER'];
		if(strrpos ( $referer, "froogle"))
		{
		
		$page=$_SERVER['PHP_SELF'] . $_SERVER['QUERY_STRING'];
		
		$date=date("Y-m-d");
		$id="froogle";
		$sessionid=session_id();
		
		$mysql['page']=mysql_real_escape_string($page,$link);
		$mysql['date']=mysql_real_escape_string($date,$link);
		$mysql['sessionid']=mysql_real_escape_string($sessionid,$link);
		$mysql['id']=mysql_real_escape_string($id,$link);
		
		$insert="INSERT into referer VALUES('','{$mysql['page']}','{$mysql['id']}','{$mysql['date']}','{$mysql['sessionid']}')";
		
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
		$result = mysql_query($insert) or die('Query failed: ' . mysql_error());
		}
 	}
	if(isset($_GET['ref']))
	{
		$page=$_SERVER['PHP_SELF'] . $_SERVER['QUERY_STRING'];
		
		$date=date("Y-m-d");
		$id="froogle-no-ref";
		$sessionid=session_id();
		
		$mysql['page']=mysql_real_escape_string($page,$link);
		$mysql['date']=mysql_real_escape_string($date,$link);
		$mysql['sessionid']=mysql_real_escape_string($sessionid,$link);
		$mysql['id']=mysql_real_escape_string($id,$link);
		
		$insert="INSERT into referer VALUES('','{$mysql['page']}','{$mysql['id']}','{$mysql['date']}','{$mysql['sessionid']}')";
		
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
		$result = mysql_query($insert) or die('Query failed: ' . mysql_error());
	}
	
	if(isset($_GET['number'])) {
	//"-" removed from list of characters to remove as products have this in code
		
	$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");	
			
	$clean['productupc']=form_clean($_GET['number'],20,$badchars);
		
	if(CheckProduct($clean['productupc'])==false)
	{
		$page="products.php";
		if(isset($_GET['master']) && isset($_GET['dept']) )
		{
			$clean['master']=form_clean($_GET['master'],4);
			$clean['dept']=form_clean($_GET['dept'],4);
			$page = $page . "?master={$clean['master']}" . "?dept={$clean['dept']}" ;
		}
		else
		{
			$page="index.php";
		}
		header ("location:$page");
	}	
	include ('include/header.php');  
?>


<?php include("include/productdetaildisplay.php"); ?>

<?php
}//End of Checking if product exsits
?>

<?php include ('include/footer.php');?>