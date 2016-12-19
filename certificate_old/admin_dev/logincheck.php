<?php require_once("include/siteconstants.php"); ?>
<?php require_once("include/commonfunctions.php"); ?>
<?php

	if(isset($_SESSION['adminlogin']))
	{
		if ($_SESSION['adminlogin'] != true)
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