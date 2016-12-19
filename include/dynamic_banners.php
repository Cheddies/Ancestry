<?php 

include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");


$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);

$table="banners";
$fields=array("name","link","file","file_type","width","height","weight");
$where=array("enabled=1");

$banner_data=$DB->getData($table,$fields,$where);


if($banner_data)
{
	$banner=Banner_choose($banner_data);
	
	echo display_banner($banner);
	
}


?>