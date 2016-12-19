<?php 

require_once("include/siteconstants.php");
require_once("../include/commonfunctions.php");
	
if(isset($_SESSION['logged_in']))
{
	session_unset();
	session_regenerate_id(true);
	header('location:index.php');
	exit();
}

header('location:index.php');


?>