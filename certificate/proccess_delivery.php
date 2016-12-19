<?php
require_once('include/siteconstants.php');
require_once('include/commonfunctions.php');


session_set_cookie_params ( 0,"/." , "", true);
if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	$clean=array();
	$error=false;
	$query_string="error=1";
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	$errors=array();
	$_SESSION['errors']="";
	

	$form_fields=array( array ("name" => "delivery_method","display_name" => "Delivery Method","length"=>"1","reg_ex"=>"","required"=>true,"error" =>"Please choose a delivery method"),
						array ("name" => "dpemail","display_name" => "","length"=>"1","reg_ex"=>"","required"=>false),
						array ("name" => "dprent","display_name" => "","length"=>"1","reg_ex"=>"","required"=>false),
						array ("name" => "tcs","display_name" => "Terms and Conditions","length"=>"1","reg_ex"=>"","required"=>true ,"error" =>"In order to proceed with your order you need to accept the terms and conditions"),
						array ("name" => "tcs_2","display_name" => "Terms and Conditions","length"=>"1","reg_ex"=>"","required"=>true ,"error" =>"In order to proceed with your order you need to agree to this")    
					);
	
	$errors = process_form($form_fields,$_POST,&$clean);
	
	
	if(sizeof($errors)>0)
	{
		$_SESSION['errors']=serialize($errors);
		header('location:choose_delivery.php');
		exit();
	}
	else
	{
		$today=date("Y-m-d");
		//insert everything into order_table 
		
		//set the no fields dependant on the questions asked
		//a field is allways set if the box is ticked
		
		if(isset($_SESSION['dpemail']))
			$noemail=0;
		else
			$noemail=1;
			
		if(isset($_SESSION['dprent']))
			$norent=0;
		else
			$norent=1;
	
		//allways 0 as question is not asked	
		$nomail=0;
		
		
		if(isset($_SESSION['GRO_orders_id']))
		{
			$fields=array("GRO_orders_id","order_number","billing_address","shipping_address","order_date","delivery_method","email","phone","nomail","norent","noemail");
			$data=array($_SESSION['GRO_orders_id'],session_id(),$_SESSION['billing_address_id'],$_SESSION['delivery_address_id'],$today,$_SESSION['delivery_method'],$_SESSION['email'],$_SESSION['phone'],$nomail,$norent,$noemail);
			$DB->storeData("GRO_orders",$fields,$data);
		}
		else
		{
			$fields=array("order_number","billing_address","shipping_address","order_date","delivery_method","email","phone","nomail","norent","noemail");
			$data=array(session_id(),$_SESSION['billing_address_id'],$_SESSION['delivery_address_id'],$today,$_SESSION['delivery_method'],$_SESSION['email'],$_SESSION['phone'],$nomail,$norent,$noemail);
			$DB->storeData("GRO_orders",$fields,$data);
			
			//mysql_insert_id - can be used to get the id generated for the last insert
			//need to confirm that this is ok to use if more than 1 update is being performed
			$_SESSION['GRO_orders_id']=mysql_insert_id();
		}	
		
		header('location:order_summary.php');
		exit();
	}
}
else
{
	header('location:choose_delivery.php');
	exit();
}