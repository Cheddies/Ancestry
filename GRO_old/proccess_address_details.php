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
		
	//set different characters to remove from email field
	//as . and - are allowed
	$email_remove=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");
	
	$form_fields=array( array ("name" => "title","display_name" => "Title","length"=>"45","reg_ex"=>"","required"=>true),
						array ("name" => "forenames","display_name" => "Forenames", "length"=>"45","reg_ex"=>"","required"=>true),
						array ("name" => "surname","display_name" => "Surname","length"=>"45","reg_ex"=>"","required"=>true),
						array ("name" => "address_line_1","display_name" => "Address Line 1" ,"length"=>"45","reg_ex"=>"","required"=>true),
						array ("name" => "address_line_2","display_name" => "Address Line 2","length"=>"45","reg_ex"=>"","required"=>false), 
						array ("name" => "town","display_name" => "Town" ,"length"=>"45","reg_ex"=>"","required"=>true),
						array ("name" => "county","display_name" => "County" ,"length"=>"45","reg_ex"=>"","required"=>true),
						array ("name" => "country","display_name" => "Country" ,"length"=>"45","reg_ex"=>"","required"=>true),
						array ("name" => "postcode","display_name" => "Postcode" ,"length"=>"9","reg_ex"=>POSTCODE_REG_EX,"required"=>true),
						array ("name" => "email","display_name" => "Email" ,"length"=>"50","reg_ex"=>EMAIL_REG_EX,"required"=>true, "remove"=>$email_remove),
						array ("name" => "phone","display_name" => "Phone" ,"length"=>"14","reg_ex"=>"(^[0-9])","required"=>true),
						array ("name" => "delivery_same","display_name" => "Delivery Same" ,"length"=>"1","reg_ex"=>"","required"=>false)
						);
	
	if(!isset($_POST['delivery_same']))
	{
		$more_fields=array( array ("name" => "delivery_title","display_name" => "Title","length"=>"45","reg_ex"=>"","required"=>true),
							array ("name" => "delivery_forenames","display_name" => "Forenames","length"=>"45","reg_ex"=>"","required"=>true),
							array ("name" => "delivery_surname","display_name" => "Surname" ,"length"=>"45","reg_ex"=>"","required"=>true),
							array ("name" => "delivery_address_line_1","display_name" => "Address Line 1","length"=>"45","reg_ex"=>"","required"=>true),
							array ("name" => "delivery_address_line_2","display_name" => "Address Line 2","length"=>"45","reg_ex"=>"","required"=>false),
							array ("name" => "delivery_town","display_name" => "Town" ,"length"=>"45","reg_ex"=>"","required"=>true),
							array ("name" => "delivery_county","display_name" => "County" ,"length"=>"45","reg_ex"=>"","required"=>true),
							array ("name" => "delivery_country","display_name" => "Country" ,"length"=>"45","reg_ex"=>"","required"=>true),
							array ("name" => "delivery_postcode","display_name" => "Postcode" ,"length"=>"9","reg_ex"=>POSTCODE_REG_EX,"required"=>true)
						);
						
		$form_fields=array_merge($form_fields ,$more_fields);
						
	}
	
	$errors = process_form($form_fields,$_POST,&$clean);
	
	if(sizeof($errors)>0)
	{
		$_SESSION['errors']=serialize($errors);
		header('location:address_details.php');
		exit();
	}
	else
	{
		if(isset($_POST['delivery_same']))
		{
			$_SESSION['delivery_title']=$_SESSION['title'];
			$_SESSION['delivery_forenames']=$_SESSION['forenames'];
			$_SESSION['delivery_surname']=$_SESSION['surname'];
			$_SESSION['delivery_address_line_1']=$_SESSION['address_line_1'];
			$_SESSION['delivery_address_line_2']=$_SESSION['address_line_2'];
			$_SESSION['delivery_town']=$_SESSION['town'];
			$_SESSION['delivery_county']=$_SESSION['county'];
			$_SESSION['delivery_country']=$_SESSION['country'];
			$_SESSION['delivery_postcode']=$_SESSION['postcode'];
		}
		
		//get names of countries for later usage
		$countrydetails=$DB->getData("GRO_tbl_countries",array("country"),array("code={$_SESSION['country']}"));
		$_SESSION['country_name']=$countrydetails[0]['country'];
		$countrydetails=$DB->getData("GRO_tbl_countries",array("country"),array("code={$_SESSION['delivery_country']}"));
		$_SESSION['delivery_country_name']=$countrydetails[0]['country'];
		
		if(isset($_SESSION['billing_address_id']))
		{
			$fields=array("GRO_addresses_id","title","first_name","surname","line_1","line_2","city","county","postcode","country","type");
			$data=array($_SESSION['billing_address_id'],$_SESSION['title'],$_SESSION['forenames'],$_SESSION['surname'],$_SESSION['address_line_1'],$_SESSION['address_line_2'],$_SESSION['town'],$_SESSION['county'],$_SESSION['postcode'],$_SESSION['country'],"B");
			$DB->storeData("GRO_addresses",$fields,$data);
		}
		else
		{
			$fields=array("title","first_name","surname","line_1","line_2","city","county","postcode","country","type");
			$data=array($_SESSION['title'],$_SESSION['forenames'],$_SESSION['surname'],$_SESSION['address_line_1'],$_SESSION['address_line_2'],$_SESSION['town'],$_SESSION['county'],$_SESSION['postcode'],$_SESSION['country'],"B");
			$DB->storeData("GRO_addresses",$fields,$data);
			
			//mysql_insert_id - can be used to get the id generated for the last insert
			//need to confirm that this is ok to use if more than 1 update is being performed
			$_SESSION['billing_address_id']=mysql_insert_id();
		}
		if(isset($_SESSION['delivery_address_id']))
		{
			$fields=array("GRO_addresses_id","title","first_name","surname","line_1","line_2","city","county","postcode","country","type");
			$data=array($_SESSION['delivery_address_id'],$_SESSION['delivery_title'],$_SESSION['delivery_forenames'],$_SESSION['delivery_surname'],$_SESSION['delivery_address_line_1'],$_SESSION['delivery_address_line_2'],$_SESSION['delivery_town'],$_SESSION['county'],$_SESSION['delivery_postcode'],$_SESSION['delivery_country'],"D");
			$DB->storeData("GRO_addresses",$fields,$data);
		}
		else
		{
			$fields=array("title","first_name","surname","line_1","line_2","city","county","postcode","country","type");
			$data=array($_SESSION['delivery_title'],$_SESSION['delivery_forenames'],$_SESSION['delivery_surname'],$_SESSION['delivery_address_line_1'],$_SESSION['delivery_address_line_2'],$_SESSION['delivery_town'],$_SESSION['delivery_county'],$_SESSION['delivery_postcode'],$_SESSION['delivery_country'],"D");
			$DB->storeData("GRO_addresses",$fields,$data);
			
			//mysql_insert_id - can be used to get the id generated for the last insert
			//need to confirm that this is ok to use if more than 1 update is being performed
			$_SESSION['delivery_address_id']=mysql_insert_id();
		}
		
		header('location:choose_delivery.php');
		exit();
	}
		
}
else
{
	//problem with token
	header('location:index.php');
	exit();
}
?>
