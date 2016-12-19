<?php

include_once("include/siteconstants.php");
include_once("../include/commonfunctions.php");

include("include/admin_header.php");
	
$clean=array();
$errors=array();
$_SESSION['errors']="";
$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);

$clean['banner_id']=form_clean($_GET['banner_id'],10);

if(!isset($clean['banner_id']) || !is_numeric($clean['banner_id']))
{
	echo "Banner not found";
}
else
{

	$table="banners";
	$fields=array("banners_id","name","link","file","file_type","width","height","weight","enabled","weight");
	$where=array("banners_id={$clean['banner_id']}");
	$banner_data=$DB->getData($table,$fields,$where);
	if($banner_data)
		echo display_banner($banner_data[0],"../banners/");
	else
		echo "Banner not found";
}					
	include("include/admin_footer.php");
?>