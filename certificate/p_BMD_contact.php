<?php
require_once('include/siteconstants.php');
require_once('include/commonfunctions.php');

session_set_cookie_params ( 0,"/." , "", true);

if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	$clean=array();
	$_SESSION['errors']="";
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	
	//set different characters to remove from email field
	//as . and - are allowed
	$email_remove=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");
	
	$form_fields=array( array ("name" => "contact_title","display_name" => "Title", "length" => "45",  "reg_ex" => "","required" => true),
						array ("name" => "contact_firstname","display_name" => "Firstname", "length" => "45",  "reg_ex" => "","required" => true),
						array ("name" => "contact_surname","display_name" => "Surname", "length" => "45",  "reg_ex" => "","required" => true),
						array ("name" => "contact_email","display_name" => "Email", "length" => "50",  "reg_ex" => EMAIL_REG_EX,"required" => true,"remove"=>$email_remove),
						array ("name" => "contact_country","display_name" => "Country", "length" => "3",  "reg_ex" => "","required" => true),
						
					 );
	$errors = process_form($form_fields,$_POST,&$clean);
	
	if(sizeof($errors)>0)
	{
		$_SESSION['errors']=serialize($errors);
		header('location:BMD_contact.php');
		exit();
	}
	else
	{
		$table="BMD_contact";
		$fields=array("title","firstname","surname","email","country");
		$values=array($clean['contact_title'],$clean['contact_firstname'],$clean['contact_surname'],$clean['contact_email'],$clean['contact_country']);
		
		if($DB->storeData($table,$fields,$values))
		{
			$_SESSION['BMD_contact_complete']=true;
			header('location:c_BMD_contact.php');
			exit();
		}
		else
		{
			$errors['general']="Could not save your details. Please try again.";
			$_SESSION['errors']=serialize($errors);
			header('location:BMD_contact.php');
			exit();
		}
	}
}
header('location:BMD_contact.php');
exit(); 	