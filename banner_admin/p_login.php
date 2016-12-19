<?php
require_once('include/siteconstants.php');
require_once('../include/commonfunctions.php');

//session_set_cookie_params ( 0,"/." , "", true);
if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	$clean=array();
	$error=false;
	$query_string="error=1";
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	$errors=array();
	$_SESSION['errors']="";
		
	$email_remove=unserialize(EMAIL_REMOVE);
	$password_remove=unserialize(PASSWORD_REMOVE);
	
	$form_fields=array( array ("name" => "username","display_name" => "Username","length"=>"50","reg_ex"=>"","required"=>true,"error" =>"","remove"=>$email_remove),
						array ("name" => "password","display_name" => "Password","length"=>"50","reg_ex"=>"","required"=>true, "return" => false, "remove"=>$password_remove),
					);
	
					
	//need to stop the password being stored in session data during this function
	//need to communicate to the user any characters that are not allowed

	if (get_magic_quotes_gpc()) {
		  if(isset($_POST['password']))
  		 	 $_POST['password'] = stripslashes($_POST['password']);
	} 
	
	$errors = process_form($form_fields,$_POST,&$clean);
	
	
	if(sizeof($errors)>0)
	{
		$_SESSION['errors']=serialize($errors);
		header('location:index.php');
		exit();
	}
	else
	{
		
		$fields=array("user_id","username","password","active","user_level","email");
		$password_md5=md5($clean['password']);
		$where = array("username={$clean['username']}","password={$password_md5}","active=1");
		$table="user";
		
		$user_data=$DB->getData($table,$fields,$where,"",$join,"",false);
		if($user_data)
		{
			$_SESSION['user_id']=$user_data[0]['user_id'];
			$_SESSION['user_level']=$user_data[0]['user_level'];
			$_SESSION['username']=$user_data[0]['username'];
			$_SESSION['email']=$user_data[0]['email'];
			$_SESSION['logged_in']=true;
					
			header('location:controls.php');
			exit();
		}
		else
		{
			$errors['other']="Username and password are incorrect";
			$_SESSION['errors']=serialize($errors);
			header('location:index.php');
			exit();
		}
		
	}
}
else
{
	header('location:index.php');
	exit();
}