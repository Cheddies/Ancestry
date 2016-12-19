<?php
require_once('include/siteconstants.php');
require_once('include/commonfunctions.php');

session_set_cookie_params ( 0,"/." , "", true);
if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	$clean=array();

	if( isset($_POST['promo_code_pt1']) && strlen($_POST['promo_code_pt1'])>0 &&
		isset($_POST['promo_code_pt2']) && strlen($_POST['promo_code_pt2'])>0 &&
		isset($_POST['promo_code_pt2']) && strlen($_POST['promo_code_pt3'])>0 
	 ) 
	{
		$clean['promo_code_pt1']=form_clean($_POST['promo_code_pt1'],3); 
		$clean['promo_code_pt2']=form_clean($_POST['promo_code_pt2'],3);
		$clean['promo_code_pt3']= form_clean($_POST['promo_code_pt3'],3);
		
		$clean['promo_code']=$clean['promo_code_pt1'] . "-"  . $clean['promo_code_pt2'] ."-" .$clean['promo_code_pt3'];
	}
	else
	{
		header('location:index.php?error=1');
		exit();
	}
	
	//lookup the promo code in the database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect:' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$mysql=array();
	$mysql['promo_code']=mysql_real_escape_string($clean['promo_code'],$link);
	
	$query="SELECT promo_code,offer,used,expiry FROM promo_codes_whsmith WHERE promo_code='{$mysql['promo_code']}'";
	$result=mysql_query($query);
	
	if($line = mysql_fetch_array($result))
	{
		//can check for things like expiry date, if the offer is the correct one and if it is used
		
		if($line['used']==true)
		{
			header('location:index.php?error=2');
			exit();
		}
		
		if($line['expiry']!='0000-00-00')
		{
			$expiry=strtotime($line['expiry']);
			if(time() > $expiry)
			{
				header('location:index.php?error=4');
				exit();
			}	
		}
		
		if(trim($line['offer'])!='')
		{
			if($_SESSION['promo_type']!=$line['offer'])
			{
				header('location:index.php?error=3');
				exit();
			}
		}
		
				
		//code is valid
		$_SESSION['promo_code']=$clean['promo_code'];
		$_SESSION['promo_code_pt1']=$clean['promo_code_pt1'];
		$_SESSION['promo_code_pt2']=$clean['promo_code_pt2'];
		$_SESSION['promo_code_pt3']=$clean['promo_code_pt3'];
		
		//Addition to WH Smith page
		//record the current SESSION ID being used with this code
		//this will be checked at the final stage to ensure they match
		$mysql['sessionid']=mysql_real_escape_string(session_id());
		
		$update="UPDATE promo_codes_whsmith SET orderid='{$mysql['sessionid']}' WHERE promo_code='{$mysql['promo_code']}'";
		$result=mysql_query($update);
		
		header('location:enter_details.php');
		exit();		
	}
	else
	{
		header('location:index.php?error=3');
		exit();	
	}
			
}
else
{
	//problem with token
	//echo $_POST['token'] . "<Br />";
	//echo $_SESSION['token'];
	header('location:index.php');
}
?>