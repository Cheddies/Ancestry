<?php

require_once('include/siteconstants.php');
require_once('include/commonfunctions.php');

session_set_cookie_params ( 0,"/." , "", true);



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
						array ("name" => "stref","display_name" => "secure trading reference","length"=>"50","reg_ex"=>"","required"=>false,"remove"=>$email_remove),
						array ("name" => "firstname","display_name" => "Firstname","length"=>"50","reg_ex"=>"","required"=>false),	
						array ("name" => "surname","display_name" => "Surname","length"=>"50","reg_ex"=>"","required"=>false),	
						array ("name" => "GROI_year","display_name" => "GRO Index Registered Year", "length"=>"4","reg_ex"=>YEAR_REG_EX,"required"=>false),		
						array ("name" => "scan_and_send", "display_name" => "Scan and Send","length"=>"1","reg_ex"=>"","required"=>false)		
					);
	
	echo nl2br("process form .......\r\n");

		
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
		
		if(isset($clean['stref'])&& strlen($clean['firstname'])>0 )
			$QUERY=$QUERY. "&firstname=" . $clean['firstname'];	
			
		if(isset($clean['stref'])&& strlen($clean['surname'])>0 )
			$QUERY=$QUERY. "&surname=" . $clean['surname'];	
				
		if(isset($clean['GROI_year']) && strlen($clean['GROI_year'])>0)
			$QUERY=$QUERY. "&GROI_year=" . $clean['GROI_year'];
		
		if(isset($clean['scan_and_send']) && strlen($clean['scan_and_send'])>0)
			$QUERY=$QUERY. "&scan_and_send=" . $clean['scan_and_send'];
				
		header("location:list_orders_peter.php?{$QUERY}");
		exit();				

