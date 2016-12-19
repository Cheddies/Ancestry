<?php require_once("include/login_check.php") ?>
<?php
	require_once("include/siteconstants.php");
	require_once("../include/commonfunctions.php");
	//$clean=array();
	//$mysql=array();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Chris Gan" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?php escaped_echo (str_replace(">" , "|" , $title)) ;?></title>
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<link href="style/ancestry_global_styles.css" rel="stylesheet" type="text/css" />
	<link href="style/main.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="style/admin.css" rel="stylesheet" type="text/css" media="screen" />
	<!--[if IE]>
	<style type="text/css">
	#f-content legend {margin-left: -7px;}
	#f-content fieldset .checkbox {width:13px;height:13px;}
	</style>
	<![endif]-->	
	<?php
	if   (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')
	{
		//don't cache https pages
	?>
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="no-cache" />	
	<?php
	}
	?>	
</head>
<body>
<div id="container">
<div id="main_top_bar">
	
</div>
<div id="logo_section">
	<h1><img src="images/ancestryshop.jpg" alt="the ancestry shop" /></h1>	
	<?php
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)
	{
	?>
	<div id="logout">
		<form action="logout.php" method="post">
			<input type="submit" value="Logout" />
		</form>
	</div>
	<?php
	}
	?>
</div>
<div id="admin_header">
<h1>Ancestry Banner Admin Site</h1>
</div>