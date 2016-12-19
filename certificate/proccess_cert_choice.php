<?php
require_once('include/siteconstants.php');
require_once('include/commonfunctions.php');


session_set_cookie_params ( 0,"/." , "", true);
if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	$clean=array();
	$error=false;
	$query_string="error=1";
	$_SESSION['errors']="";
	
	
	//changed - 23-02-09
	//GROI is allways needed
	/*$form_fields=array( array ("name" => "cert_choice","display_name" => "Certificate Choice", "length" => "1",  "reg_ex" => "","required" => true,"error" =>"Please select a certificate type"),
						array ("name" => "death_age","display_name" => "Age of Death", "length" => "3",  "reg_ex" => "(^[1-9])","required" => false),
						array ("name" => "GROI_known","display_name" => "GROI Known", "length" => "1",  "reg_ex" => "","required" => true,"error" =>"Please select either Yes or No"),
						array ("name" => "GROI_reg_month","display_name" => "Date of Entry Month", "length" => "2",  "reg_ex" => MONTH_REG_EX,"required" => false),
						array ("name" => "GROI_reg_year","display_name" => "Date of Entry Year", "length" => "4",  "reg_ex" => YEAR_REG_EX,"required" => false),
						);
						
	if(isset($_POST['GROI_known']) && $_POST['GROI_known']==1)
	{
		$more_fields=array(	array ("name" => "GROI_reg_month","display_name" => "Date of Entry Month", "length" => "2",  "reg_ex" => MONTH_REG_EX,"required" => true),
						array ("name" => "GROI_reg_year","display_name" => "Date of Entry Year", "length" => "4",  "reg_ex" => YEAR_REG_EX,"required" => true),
						);
		
		$form_fields=array_merge($form_fields ,$more_fields);	
	}*/
	
	$form_fields=array( array ("name" => "cert_choice","display_name" => "Certificate Choice", "length" => "1",  "reg_ex" => "","required" => true,"error" =>"Please select a certificate type"),
						array ("name" => "death_age","display_name" => "Age of Death", "length" => "3",  "reg_ex" => "(^[1-9])","required" => false),
						array ("name" => "GROI_reg_month","display_name" => "Date of Entry Month", "length" => "2",  "reg_ex" => MONTH_REG_EX,"required" => true),
						array ("name" => "GROI_reg_year","display_name" => "Date of Entry Year", "length" => "4",  "reg_ex" => YEAR_REG_EX,"required" => true)
						);
					
	//set GROI_known to true in session
	$_SESSION['GROI_known']=1;
	
	//Age of death can't be a required field as it is not allways needed
	
	/*if(isset($_POST['cert_choice']) && $_POST['cert_choice']==DEATH)
	{
		$more_fields=array( array ("name" => "death_age","display_name" => "Age of Death", "length" => "3",  "reg_ex" => "(^[1-9])","required" => true));
		$form_fields=array_merge($form_fields ,$more_fields);
	}*/
					
	
	
	$errors = process_form($form_fields,$_POST,&$clean);
	
	if(sizeof($errors)>0)
	{
		$_SESSION['errors']=serialize($errors);
		header('location:index.php');
		exit();
	}
	else
	{
		//final check of the entry date
		if(!check_entry_date($_SESSION['GROI_reg_month'],$_SESSION['GROI_reg_year']))
		{
			$errors['GROI_reg_month']='The entry date is not older than 18th Months';
			$_SESSION['errors']=serialize($errors);
			header('location:index.php');
			exit();
		}
		
		unset($_SESSION['cert_id']);
		switch($_SESSION['cert_choice'])
		{
			CASE BIRTH:
				header('location:birth_certificate.php');
				exit();
			break;
			CASE DEATH:
				header('location:death_certificate.php');
				exit();
			break;
			CASE MARRIAGE:
				header('location:marriage_certificate.php');
				exit();
			break;
			default:
				header('location:index.php');
			break;
		}
	}

	
}
else
{
	//problem with token
	header('location:index.php');
	exit();
}
?>