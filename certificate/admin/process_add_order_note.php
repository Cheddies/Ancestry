<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");

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
$email_remove=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");

//certain characters removed to allow for basic html code

$note_remove=array("\n","\r","#","\$","}","{","^","~","?","*","|","`",",","\\","(",")","!","[","]","'","\"");	

$form_fields=array(	array ("name" => "order_id","display_name" => "order number","length"=>"10","reg_ex"=>"","required"=>true),
					array ("name" => "note","display_name" => "Note","length"=>"65000","reg_ex"=>"","required"=>true,"remove"=>$note_remove)
					);

//$errors = process_form($form_fields,$_POST,&$clean);
	foreach($form_fields as $field)
	{
				$clean[$field['name']]=$_POST[$field['name']];
				//echo ($_GET[$field['name']]);
	}

//if(sizeof($errors)>0)
//{
//	$_SESSION['errors']=serialize($errors);
//	header("location:add_order_note.php?token={$token}&order_id={$clean['order_id']}");
//	exit();
//}
//else
//{
	
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	
	$fields=array("GRO_orders_id","note");
	$values=array($clean['order_id'],$clean['note']);

	$DB->storeData("GRO_orders_notes",$fields,$values);

	$fields=array("event","order_id","user_id");
	$values=array("5",$clean['order_id'],"1");
	
	$DB->storeData("GRO_order_events",$fields,$values);
	
	
	header("location:view_order_note.php?order_id={$clean['order_id']}&token={$token}");
	exit();
	//redirect back to the original page
	//header("location:{$_SESSION['last_page']}");
	
//}

}
else
{
	header('location:index.php');
	exit();
}

?>