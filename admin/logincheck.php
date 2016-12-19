<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php

	if(isset($_SESSION['adminlogin_shop']))
	{
		if ($_SESSION['adminlogin_shop'] != true)
		{
			 header('Location: index.php');
			 exit();
		}
	}
	else
	{
	 		header('Location: index.php');
			exit();	
	}
	
?>