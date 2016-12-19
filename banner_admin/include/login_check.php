<?php
require_once("include/siteconstants.php");
require_once("../include/commonfunctions.php");

if(isset($_SERVER['PHP_SELF']))
{
	$PAGE=basename($_SERVER['PHP_SELF']);
	$PAGE=substr($PAGE,0,strlen($PAGE)-4);
}

if( ( (!isset($_SESSION['logged_in'])) || ($_SESSION['logged_in']==false)) && ($PAGE!="index") )
{
	header('location:index.php');
	exit();
}

?>