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
	
	$email_remove=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");#

	$form_fields=array( array ("name" => "order_number","display_name" => "Order Number","length"=>"45","reg_ex"=>"","required"=>false),
						array ("name" => "GRO_ref","display_name" => "GRO Reference","length"=>"45","reg_ex"=>"","required"=>false),
						array ("name" => "email","display_name" => "Email","length"=>"50","reg_ex"=>"","required"=>false,"remove"=>$email_remove),
						array ("name" => "order_date","display_name" => "Order date","length"=>"10","reg_ex"=>"","required"=>false),
						array ("name" => "status","display_name" => "Status","length"=>"1","reg_ex"=>"","required"=>false),
						array ("name" => "stref","display_name" => "secure trading reference","length"=>"50","reg_ex"=>"","required"=>false,"remove"=>$email_remove)
										
					);
	
	$errors = process_form($form_fields,$_POST,&$clean);
	
	
	if(sizeof($errors)>0)
	{
		$_SESSION['errors']=serialize($errors);
		header('location:search.php');
		exit();
	}
	else
	{
		
		if(isset($clean['order_number'])&& strlen($clean['order_number'])>0)
			$QUERY=$QUERY. "&order_id=" . $clean['order_number'];
			
		if(isset($clean['GRO_ref'])&& strlen($clean['GRO_ref'])>0)
			$QUERY=$QUERY. "&GRO_ref=" . $clean['GRO_ref'];
			
		if(isset($clean['email'])&& strlen($clean['email'])>0)
			$QUERY=$QUERY. "&email=" . $clean['email'];
			
		if(isset($clean['order_date'])&& strlen($clean['order_date'])>0)
			$QUERY=$QUERY. "&date=" . $clean['order_date'];
			
		if(isset($clean['status'])&& strlen($clean['status'])>0 && $clean['status']!=0)
			$QUERY=$QUERY. "&status=" . $clean['status'];
			
		
		if(isset($clean['stref'])&& strlen($clean['stref'])>0 )
			$QUERY=$QUERY. "&stref=" . $clean['stref'];
			
		header("location:list_orders.php?{$QUERY}");
		exit();				
	}
}
else
{
	header('location:search.php');
	exit();
}