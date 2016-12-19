<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");


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
	
$form_fields=array(	array ("name" => "order_id","display_name" => "order number","length"=>"10","reg_ex"=>"","required"=>true),
					array ("name" => "GRO_ref","display_name" => "GRO reference","length"=>"45","reg_ex"=>"","required"=>true)
					);

$errors = process_form($form_fields,$_POST,&$clean);

if(sizeof($errors)>0)
{
	$_SESSION['errors']=serialize($errors);
	header("location:add_GRO_ref.php?token={$token}&order_id={$clean['order_id']}");
	exit();
}
else
{
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	
	$fields=array("GRO_orders_id","GRO_ref","GRO_date");
	$where=array("GRO_orders_id={$clean['order_id']}");
	$values=array($clean['order_id'],$clean['GRO_ref'],date("Y-m-d"));
	
	$current_line=$DB->getData("GRO_orders_extra",$fields,$where,"","","",false);

	if($current_line)
	{
		$DB->updateData("GRO_orders_extra",$fields,$values,$where);		
	}	
	else
	{
		$DB->storeData("GRO_orders_extra",$fields,$values);
	}
	
	header("location:update_order_status.php?order_id={$clean['order_id']}&token={$token}&status=2");
	$_SESSION['last_page']="view_order.php?order_id={$clean['order_id']}";
	exit();
	//redirect back to the original page
	//header("location:{$_SESSION['last_page']}");
	
}

}
else
{
	header('location:index.php');
	exit();
}

?>