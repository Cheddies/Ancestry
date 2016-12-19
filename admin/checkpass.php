<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php 

if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	
if(isset($_POST['password']) && isset($_POST['username'])) 
{
$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');


	$clean['password']=MD5(form_clean($_POST['password'],50));
	$clean['username']=form_clean($_POST['username'],50);
	
	$mysql['password']=mysql_real_escape_string($clean['password']);
	$mysql['username']=mysql_real_escape_string($clean['username']);
	
	$query="SELECT * FROM admin_access WHERE username='{$mysql['username']}' AND password='{$mysql['password']}'";

	$result=mysql_query($query) or die('Query failed: ' . mysql_error());
	
	if(mysql_num_rows($result)==1)
	{		
		$_SESSION['adminlogin_shop'] = true;
		header('Location: admin.php');
		exit();
	}
	
	else {
		header('Location: error.php');
		exit();
	}
}
else
{
	header('location:error.php');
	exit();
}
}
else
{
	header('location:error.php');
	exit();
}
?>