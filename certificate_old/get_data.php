<?php
require_once('include/siteconstants.php');
require_once('include/commonfunctions.php');

//changed to GET rather than post
//08-01-09 - added motherG in form_fields

session_set_cookie_params ( 0,"/." , "", true);

	$clean=array();
	$error=false;
	$query_string="error=1";
	$errors=array();
	$_SESSION['errors']="";

	
	$form_fields=array( array ("name" => "recordType","display_name" => "recordType", "length" => "1",  "reg_ex" => "","required" => true),
						array ("name" => "returnUrl","display_name" => "returnUrl", "length" => "255",  "reg_ex" => "","required" => false),
						array ("name" => "yearReg","display_name" => "yearReg", "length" => "4",  "reg_ex" => YEAR_REG_EX,"required" => false,"error" => "Invaild value - Expects a 4 digit year"),
						array ("name" => "monthReg","display_name" => "monthReg", "length" => "2",  "reg_ex" => MONTH_REG_EX,"required" => false,"error" => "Invaild value - Expects a 2 digit month (01-12)"),
						array ("name" => "qReg","display_name" => "qReg", "length" => "1",  "reg_ex" => "(^[1-4])","required" => false,"error" => "Invaild value - Expects 1-4"),
						array ("name" => "district","display_name" => "district", "length" => "45",  "reg_ex" => "","required" => false),
						array ("name" => "volume","display_name" => "volume", "length" => "5",  "reg_ex" => "(^[1-9])","required" => false,"error" => "Invaild value - Expects a number starting at 1"),
						array ("name" => "page","display_name" => "page", "length" => "4",  "reg_ex" => "(^[1-9])","required" => false,"error" => "Invaild value - Expects a number starting at 1"),
						array ("name" => "surname","display_name" => "surname", "length" => "45",  "reg_ex" => "","required" => false),
						array ("name" => "given","display_name" => "given", "length" => "45",  "reg_ex" => "","required" => false),
						array ("name" => "by","display_name" => "by", "length" => "4",  "reg_ex" => YEAR_REG_EX,"required" => false,"error" => "Invaild value - Expects a 4 digit year"),
						array ("name" => "bd","display_name" => "bd", "length" => "2",  "reg_ex" => DAY_REG_EX,"required" => false,"error" => "Invaild value - Expects a 2 digit day (01-31)"),
						array ("name" => "bm","display_name" => "bm", "length" => "2",  "reg_ex" => MONTH_REG_EX,"required" => false,"error" => "Invaild value - Expects a 2 digit month (01-12)"),
        				array ("name" => "bp","display_name" => "bp", "length" => "45",  "reg_ex" => "","required" => false),
        				array ("name" => "fatherS","display_name" => "fatherS", "length" => "45",  "reg_ex" => "","required" => false),
           				array ("name" => "fatherG","display_name" => "fatherG", "length" => "45",  "reg_ex" => "","required" => false),
           				array ("name" => "motherS","display_name" => "motherS", "length" => "45",  "reg_ex" => "","required" => false),
           				array ("name" => "motherG","display_name" => "motherG", "length" => "45",  "reg_ex" => "","required" => false),
           				array ("name" => "age","display_name" => "age", "length" => "3",  "reg_ex" => "","required" => false)           				
						);
	  
	
	//$errors = process_form($form_fields,$_GET,&$clean);
	
	$errors=array();
	
	/*foreach($form_fields as $field)
	{
		if(isset($_GET[$field['name']]))
		{
			if(isset($field['remove']))
				$clean[$field['name']]=form_clean($_GET[$field['name']],$field['length'],$field['remove']);
			else
				$clean[$field['name']]=form_clean($_GET[$field['name']],$field['length']);
				
			$_SESSION[$field['name']]=$clean[$field['name']];
			if(strlen($clean[$field['name']])==0 )
			{
				if($field['required']==true)
				{
					
					if(isset($field['error']))
						$errors[$field['name']]=$field['display_name']. " - " . $field['error'];
					else
					$errors[$field['name']]=$field['display_name']. " is a required field";
					//$errors[$field['name']]=$field['display_name']. " is a required field";
					
				}
			}
			elseif($field['reg_ex']!='' && !eregi($field['reg_ex'],$clean[$field['name']]))
			{
				if(isset($field['error']))
					$errors[$field['name']]=$field['display_name']. " - " . $field['error'];
				else
					$errors[$field['name']]="Invalid Value for " . $field['display_name'];
			}	
		}
		else
		{
			unset($_SESSION[$field['name']]);
			if($field['required']==true)
			{
				if(isset($field['error']))
					$errors[$field['name']]=$field['error'];
				else
					$errors[$field['name']]=$field['display_name']. " is a required field";
			}
		}
	}*/
	
	//changed so it just ignores any parameters with incorrect values
	foreach($form_fields as $field)
	{
		if(isset($_GET[$field['name']]))
		{
			if(isset($field['remove']))
				$clean[$field['name']]=form_clean($_GET[$field['name']],$field['length'],$field['remove']);
			else
				$clean[$field['name']]=form_clean($_GET[$field['name']],$field['length']);
				
			$_SESSION[$field['name']]=$clean[$field['name']];
			if(strlen($clean[$field['name']])==0 )
			{
				if($field['required']==true)
				{
					
					if(isset($field['error']))
						$errors[$field['name']]=$field['display_name']. " - " . $field['error'];
					else
					$errors[$field['name']]=$field['display_name']. " is a required field";
					//$errors[$field['name']]=$field['display_name']. " is a required field";
					
				}
			}
			elseif($field['reg_ex']!='' && !eregi($field['reg_ex'],$clean[$field['name']]))
			{
				/*if(isset($field['error']))
					$errors[$field['name']]=$field['display_name']. " - " . $field['error'];
				else
					$errors[$field['name']]="Invalid Value for " . $field['display_name'];*/
				//Dont store an error
				//Just unset the session variable
				unset($_SESSION[$field['name']]);
				unset($clean[$field['name']]);
			}	
		}
		else
		{
			unset($_SESSION[$field['name']]);
			if($field['required']==true)
			{
				if(isset($field['error']))
					$errors[$field['name']]=$field['error'];
				else
					$errors[$field['name']]=$field['display_name']. " is a required field";
			}
		}
	}
	
	//clear the session data generated by this as it is not needed
	unset($_SESSION["returnUrl"]);
	unset($_SESSION["yearReg"]);
	unset($_SESSION["monthReg"]);
	unset($_SESSION["qReg"]);
	unset($_SESSION["district"]);
	unset($_SESSION["volume"]);
	unset($_SESSION["page"]);
	unset($_SESSION["surname"]);
	unset($_SESSION["given"]);
	unset($_SESSION["by"]);
	unset($_SESSION["bd"]);
	unset($_SESSION["bm"]);
    unset($_SESSION["bp"]);
    unset($_SESSION["fatherS"]);
    unset($_SESSION["fatherG"]);
    unset($_SESSION["motherS"]);
    unset($_SESSION["age"]);
           				
    
	//if anything is wrong with the data then just redirect to the homepage
	if(sizeof($errors)>0)
	{
		//reenable for live
		header('location:index.php');
		exit();
		
		//code for outputting errors
		//only use for development
		
		/*echo "<h1>Error</h1><br />";
		
		
		echo "<h3>Errors</h3>";
		foreach ($errors as $error)
		{
			echo $error. "<br />";
		}
		
		echo "<h3>GET data received</h3>";
				
		$keys = array_keys($_GET);
		
		foreach ($keys as $key)
		{
			echo "<strong>" . $key . "</strong> = " . $_GET[$key] ."<br />";
		}*/
		
		
	
		
	}
	else
	{
		
		//GRO fields
	
		$_SESSION['GROI_year']=$clean['yearReg'];
		$_SESSION['GROI_quarter']=$clean['qReg'];
		$_SESSION['GROI_district']=$clean['district'];
		$_SESSION['GROI_volume_number']=$clean['volume'];
		$_SESSION['GROI_page_number']=$clean['page'];
		$_SESSION['GROI_reg_month']=$clean['monthReg'];
		$_SESSION['GROI_reg_year']=$clean['yearReg'];
		$_SESSION['GROI_known']=1;
		
		$_SESSION['doe_month']=$clean['monthReg'];
		$_SESSION['doe_year']=$clean['yearReg'];
		
		
		unset($_SESSION['cert_id']);
		
		switch($clean['recordType'])
		{
			case b:
				$_SESSION['birth_year']=$clean['yearReg'];
				$_SESSION['birth_surname']=$clean['surname'];
				$_SESSION['birth_forename']=$clean['given'];
				$_SESSION['dob_day']=$clean['bd'];
				$_SESSION['dob_month']=$clean['bm'];
				$_SESSION['dob_year']=$clean['by'];
				$_SESSION['birth_place']=$clean['bp'];
				$_SESSION['fathers_surname']=$clean['fatherS'];
				$_SESSION['fathers_forename']=$clean['fatherG'];
				$_SESSION['mothers_maiden_surname']=$clean['motherS'];
				$_SESSION['mothers_forename']=$clean['motherG'];
				
				$_SESSION['cert_choice']=BIRTH;
				
				header('location:birth_certificate.php');
				exit();
				
			break;
			case d:
				$_SESSION['registered_year']=$clean['yearReg'];
				$_SESSION['surname_deceased']=$clean['surname'];
				$_SESSION['forenames_deceased']=$clean['given'];
				$_SESSION['dod_year']=$clean['yearReg'];
				$_SESSION['death_age']=$clean['age'];
				
				$_SESSION['cert_choice']=DEATH;
				
				header('location:death_certificate.php');
				exit();
			break;
			case m:
			//no fields are pre-populated bar the GRO reference
				$_SESSION['registered_year']=$clean['yearReg'];
				$_SESSION['cert_choice']=MARRIAGE;
				
				header('location:marriage_certificate.php');
				exit();
			break;
			default:
				//renable for live
				header('location:index.php');
				exit();
				
				//code for outputting errors in development
				/*
				echo "<h1>Error</h1><br />";
				
				
				echo "<h3>Errors</h3>";
				
				echo "recordType not recognised<br />";
				echo "recordType must be b,d or m";
				
				foreach ($errors as $error)
				{
					echo $error. "<br />";
				}
				
				echo "<h3>POST data received</h3>";
						
				$keys = array_keys($_GET);
				
				foreach ($keys as $key)
				{
					echo "<strong>" . $key . "</strong> = " . $_GET[$key] ."<br />";
				}*/
		
			break;
		}
	}


?>