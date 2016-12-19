<?php
require_once('include/siteconstants.php');
require_once('include/commonfunctions.php');


session_set_cookie_params ( 0,"/." , "", true);
if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	$clean=array();
	$error=false;
	$query_string="error=1";
	$errors=array();
	$_SESSION['errors']="";

	
	$form_fields=array( array ("name" => "registered_year","display_name" => "Year Death Registered", "length" => "4",  "reg_ex" => YEAR_REG_EX,"required" => true),
						array ("name" => "surname_deceased","display_name" => "Surname of Deceased","length" => "45", "numeric"=>false, "reg_ex" =>"","required" => true),
						array ("name" => "forenames_deceased","display_name" => "Forename of Deceased", "length" => "45", "numeric"=>false, "reg_ex" =>"","required" => true),
						array ("name" => "dod_day","display_name" => "Date of Death day ","length" => "2",  "reg_ex" =>DAY_REG_EX,"required" => false),
						array ("name" => "dod_month","display_name" => "Date of Death  month","length" => "2",  "reg_ex" =>MONTH_REG_EX,"required" => false),
						array ("name" => "dod_year","display_name" => "Date of Death  year","length" => "4",  "reg_ex" =>YEAR_REG_EX,"required" => false),
						array ("name" => "relationship_to_deceased", "display_name" => "Relationship", "length" =>"45", "reg_ex"=>"","required"=>false),
						array ("name" => "death_age","display_name" => "Age at Death","length" => "3", "reg_ex" =>"(^[1-9])","required" => false),
						array ("name" => "fathers_surname","display_name" => "Fathers Surname","length" => "45", "numeric"=>false, "reg_ex" =>"","required" => false),
						array ("name" => "fathers_forenames","display_name" => "Fathers Forename","length" => "45", "numeric"=>false, "reg_ex" =>"","required" => false),
						array ("name" => "mothers_surname","display_name" => "Mothers surname","length" => "45", "numeric"=>false, "reg_ex" =>"","required" => false),
						array ("name" => "mothers_forenames","display_name" => "Mothers Forename","length" => "45", "numeric"=>false, "reg_ex" =>"","required" => false),
						array ("name" => "no_of_certs","display_name" => "Number of Certificates","length" => "3",  "reg_ex" =>"(^[1-9])","required" => true)
						);
	  
	if(isset($_SESSION['GROI_known']) && $_SESSION['GROI_known']==1)
	{
		//GROI fields should be set
		$GRO_form_fields=array( array ("name" => "GROI_year","display_name" => "Year","length" => "4",  "reg_ex" =>YEAR_REG_EX,"required" => false),
								array ("name" => "GROI_quarter","display_name" => "Quarter","length" => "4",  "reg_ex" =>"[1-4]","required" => true),
								array ("name" => "GROI_district","display_name" => "District Name","length" => "45", "numeric"=>false, "reg_ex" =>"","required" => true),
								array ("name" => "GROI_volume_number","display_name" => "Volume","length" => "5",  "reg_ex" =>"(^[1-9])","required" => true),
								array ("name" => "GROI_page_number","display_name" => "Page number","length" => "5",  "reg_ex" =>"(^[1-9])","required" => true),
								array ("name" => "GROI_reg_month","display_name" => "Reg Month","length" => "2",  "reg_ex" =>MONTH_REG_EX,"required" => false),
								array ("name" => "GROI_reg_year","display_name" => "Reg year","length" => "4",  "reg_ex" =>YEAR_REG_EX,"required" => false)
							);
		$form_fields=array_merge($form_fields, $GRO_form_fields);
	}
	
	$errors = process_form($form_fields,$_POST,&$clean);
	
	if(sizeof($errors)>0)
	{
		$_SESSION['errors']=serialize($errors);
		header('location:death_certificate.php');
		exit();
	}
	else
	{
	
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
		
		$mysql=array();
			
		$mysql['registered_year']=mysql_real_escape_string($clean['registered_year']);
		$mysql['surname_deceased']=mysql_real_escape_string($clean['surname_deceased']);
		$mysql['forenames_deceased']=mysql_real_escape_string($clean['forenames_deceased']);
		
		//mysql DATE field -YYYY-MM-DD
		$clean['DOD']=$clean['dod_year']."-".$clean['dod_month']."-".$clean['dod_day'];
		$mysql['DOD']=mysql_real_escape_string($clean['DOD']);
	
		
		$mysql['relationship_to_deceased']=mysql_real_escape_string($clean['relationship_to_deceased']);
		$mysql['death_age']=mysql_real_escape_string($clean['death_age']);
		$mysql['fathers_surname']=mysql_real_escape_string($clean['fathers_surname']);
		$mysql['fathers_forenames']=mysql_real_escape_string($clean['fathers_forenames']);
		$mysql['mothers_surname']=mysql_real_escape_string($clean['mothers_surname']);
		$mysql['mothers_forenames']=mysql_real_escape_string($clean['mothers_forenames']);
		$mysql['GROI_year']=mysql_real_escape_string($clean['GROI_year']);
		$mysql['GROI_quarter']=mysql_real_escape_string($clean['GROI_quarter']);
		$mysql['GROI_district']=mysql_real_escape_string($clean['GROI_district']);
		$mysql['GROI_volume_number']=mysql_real_escape_string($clean['GROI_volume_number']);
		$mysql['GROI_page_number']=mysql_real_escape_string($clean['GROI_page_number']);
		
		//just add a day of 01 as the day is not taken on the form
		if(isset($clean['GROI_reg_year']) && isset($clean['GROI_reg_month']))
			$clean['GROI_reg_date']=$clean['GROI_reg_year']."-".  $clean['GROI_reg_month']."-01";
		else
			$clean['GROI_reg_date']='';
		$mysql['GROI_reg_date']=mysql_real_escape_string($clean['GROI_reg_date']);
			
		$mysql['no_of_certs']=mysql_real_escape_string($clean['no_of_certs']);
		
		$mysql['order_number']=mysql_real_escape_string(session_id());
		
		$mysql['GROI_known']=mysql_real_escape_string($_SESSION['GROI_known']);
		
		if(isset($_SESSION['cert_id']))
		{
			//allready inserted into DB once, so update
			$mysql['cert_id']=mysql_real_escape_string($_SESSION['cert_id']);
						
			$UPDATE="UPDATE GRO_death_certificates SET
			copies ='{$mysql['no_of_certs']}',
			registered_year ='{$mysql['registered_year']}',
			surname_deceased ='{$mysql['surname_deceased']}',
			forenames_deceased ='{$mysql['forenames_deceased']}',
			death_date ='{$mysql['DOD']}',
			relationship_to_deceased='{$mysql['relationship_to_deceased']}',
			death_age='{$mysql['death_age']}',
			fathers_surname='{$mysql['fathers_surname']}',
			fathers_forenames='{$mysql['fathers_forenames']}',
			mothers_surname='{$mysql['mothers_surname']}',
			mothers_forenames='{$mysql['mothers_forenames']}',
			GRO_index_year='{$mysql['GROI_year']}',
			GRO_index_quarter='{$mysql['GROI_quarter']}',
			GRO_index_district='{$mysql['GROI_district']}',
			GRO_index_volume='{$mysql['GROI_volume_number']}',
			GRO_index_page='{$mysql['GROI_page_number']}',
			GRO_index_reg='{$mysql['GROI_reg_date']}',
			GROI_known='{$mysql['GROI_known']}'
			WHERE GRO_death_certificates_id ='{$mysql['cert_id']}'";
			
			$result=mysql_query($UPDATE) or die (mysql_error());
			if($result)
			{
				header('location:address_details.php');
				exit();
			}
			
		}
		else
		{
			//insert a fresh cert into DB and get the ID back
			$INSERT="INSERT GRO_death_certificates SET
			order_number ='{$mysql['order_number']}',
			copies ='{$mysql['no_of_certs']}',
			registered_year ='{$mysql['registered_year']}',
			surname_deceased ='{$mysql['surname_deceased']}',
			forenames_deceased ='{$mysql['forenames_deceased']}',
			death_date ='{$mysql['DOD']}',
			relationship_to_deceased='{$mysql['relationship_to_deceased']}',
			death_age='{$mysql['death_age']}',
			fathers_surname='{$mysql['fathers_surname']}',
			fathers_forenames='{$mysql['fathers_forenames']}',
			mothers_surname='{$mysql['mothers_surname']}',
			mothers_forenames='{$mysql['mothers_forenames']}',
			GRO_index_year='{$mysql['GROI_year']}',
			GRO_index_quarter='{$mysql['GROI_quarter']}',
			GRO_index_district='{$mysql['GROI_district']}',
			GRO_index_volume='{$mysql['GROI_volume_number']}',
			GRO_index_page='{$mysql['GROI_page_number']}',
			GRO_index_reg='{$mysql['GROI_reg_date']}',
			GROI_known='{$mysql['GROI_known']}'";
			
			$result=mysql_query($INSERT) or die (mysql_error());
			if($result)
			{
				//get the ID back
				/*$SELECT="SELECT GRO_death_certificates_id FROM GRO_death_certificates WHERE order_number ='{$mysql['order_number']}'";
				
				$result=mysql_query($SELECT);
				
				if($line=mysql_fetch_array($result))
				{
					$_SESSION['cert_id']=$line['GRO_death_certificates_id'];
				}*/
				
				$_SESSION['cert_id']=mysql_insert_id ($link);
				
				//redirect to delivery details
				header('location:address_details.php');
				exit();
			}
			
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