<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");
include_once("fckeditor/fckeditor.php") ;

//check for user level
if(!isset($_SESSION['user_level']) || $_SESSION['user_level']!=ADMIN_USER)
{
	header('location:index.php');
	exit();
}

session_set_cookie_params ( 0,"/." , "", true);
if( ( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
||(isset($_GET['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_GET['token'],$_SESSION['token'],$_SESSION['token_time'],0))
)
{

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();
//set different characters to remove from email field
//as . and - are allowed
$email_remove=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");#
	
$form_fields=array(	array ("name" => "note_id","display_name" => "note id","length"=>"10","reg_ex"=>"","required"=>true),
			 array ("name" => "order_id","display_name" => "order number","length"=>"10","reg_ex"=>"","required"=>true)
					);

$errors = process_form($form_fields,$_GET,&$clean);

if(sizeof($errors)>0)
{
	$_SESSION['errors']=serialize($errors);
	header('location:index.php');
	exit();
}
else
{
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	
	$where=array("GRO_orders_notes_id={$clean['note_id']}");
	$values=array($clean['order_id'],$clean['note']);

	$DB->removeDataWhere("GRO_orders_notes",$where);
	
	
	$where=array("GRO_orders_id={$clean['order_id']}");
	$fields=array("event","order_id","user_id");
	$values=array("6",$clean['order_id'],"1");
	
	$DB->storeData("GRO_order_events",$fields,$values);
	
	header("location:{$_SESSION['last_page']}&token={$token}");
}

}
else
{
	header('location:index.php');
	exit();
}

?>