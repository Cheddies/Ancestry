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
$email_remove=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");#
	
$form_fields=array( array ("name" => "status","display_name" => "status","length"=>"1","reg_ex"=>"[1-9]","required"=>true),
					array ("name" => "order_id","display_name" => "order number","length"=>"10","reg_ex"=>"","required"=>true)
					);

//$errors = process_form($form_fields,$_GET,&$clean);
	foreach($form_fields as $field)
	{
				$clean[$field['name']]=$_GET[$field['name']];
				//echo ($_GET[$field['name']]);
	}

//if(sizeof($errors)>0)
//{
//	$_SESSION['errors']=serialize($errors);
//	header('location:index.php');
//	exit();
//}
//else
//{
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	
	$fields=array("order_status");
	$where=array("GRO_orders_id={$clean['order_id']}");
	$values=array($clean['status']);

	$DB->updateData("GRO_orders",$fields,$values,$where);
	

	if($clean['status']==1)
	{
		//reset the extra order info
		//can be used to remove the data if required
			
		$where=array("GRO_orders_id={$clean['order_id']}");
		$DB->removeDataWhere("GRO_orders_extra",$where);
	}
	
	if($clean['status']=='3')
	{
	
		$fields=array("GRO_orders_id","completed_date");
		$where=array("GRO_orders_id={$clean['order_id']}");
		$values=array($clean['order_id'],date("Y-m-d"));
		
		$current_line=$DB->getData("GRO_orders_extra",$fields,$where,"","","",false);
	
		if($current_line)
		{
			$DB->updateData("GRO_orders_extra",$fields,$values,$where);
		}	
		else
		{
			$DB->storeData("GRO_orders_extra",$fields,$values);
		}
		
		//email customer to say order complete
		SendCompletedEmail($clean['order_id']);
		
	}
	
	$fields=array("event","order_id","user_id");
	$values=array($clean['status'],$clean['order_id'],"1");
	
	$DB->storeData("GRO_order_events",$fields,$values);
	
	//redirect back to the original page
	header("location:{$_SESSION['last_page']}");
	exit();
//}

}
else
{
	header('location:index.php');
	exit();
}

?>