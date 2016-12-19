<?php
require_once('include/siteconstants.php');
require_once('include/commonfunctions.php');

session_set_cookie_params ( 0,"/." , "", true);
if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	// Set the session variables using the data supplied

	
	//. is removed from usual list as it is allowed for email addresses
	//this list is only used for email addresses
	//all others use function default
	$badchars=array("\n","\r","#","$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","-","[","]","/","'","\"");
	
	// Set the billing details
	//$_SESSION['c_title'] = $_POST['title']; 
	

	if(isset($_POST['fname']))
		$_SESSION['c_name'] = form_clean($_POST['fname'],15);       //required
	else
		$_SESSION['c_name']="";
			
	if(isset($_POST['surname']))
		$_SESSION['c_sname'] =  form_clean($_POST['surname'],20);       //required
	else
		$_SESSION['c_sname'] = "";
	if(isset($_POST['add1']))
		$_SESSION['c_add1'] =  form_clean($_POST['add1'],40);           //required
	else
		$_SESSION['c_add1'] = "";
	if(isset($_POST['add2']))
		$_SESSION['c_add2'] =  form_clean($_POST['add2'],40);
	else
		$_SESSION['c_add2'] = "";
	if(isset($_POST['city']))
		$_SESSION['c_city'] =  form_clean($_POST['city'],20);           //required
	else
		$_SESSION['c_city'] = "";
	if(isset($_POST['postcode']))
		$_SESSION['c_postcode'] =  form_clean($_POST['postcode'],10);   //required
	else
		$_SESSION['c_postcode'] = "";
	if(isset($_POST['phone']))
		$_SESSION['c_phone'] =  form_clean($_POST['phone'],14);		//required
	else
		$_SESSION['c_phone'] = "";
	if(isset($_POST['email']))
		$_SESSION['c_email'] =  form_clean($_POST['email'],50,$badchars);         //required
	else
		$_SESSION['c_email'] = "";
	if(isset($_POST['state']))
		$_SESSION['c_state'] =  form_clean($_POST['state'],2);         //required
	else
		$_SESSION['c_state'] = "";
	if(isset($_POST['country']))
		$_SESSION['c_country'] = form_clean($_POST['country'],3); //required
	else
		$_SESSION['c_country'] = "";
	// Set the shipping details
	//$_SESSION['cs_title'] = $_POST['stitle']; 
	if(isset($_POST['sfname']))
		$_SESSION['cs_name'] = form_clean($_POST['sfname'],15);        //required
	else
		$_SESSION['cs_name'] = "";
	if(isset($_POST['ssurname']))
		$_SESSION['cs_sname'] = form_clean($_POST['ssurname'],20);     //required
	else
		$_SESSION['cs_sname'] ="";
	if(isset($_POST['sadd1']))
		$_SESSION['cs_add1'] = form_clean($_POST['sadd1'],40);         //required
	else
		$_SESSION['cs_add1'] = "";
	if(isset($_POST['sadd2']))
		$_SESSION['cs_add2'] = form_clean($_POST['sadd2'],40);
	else
		$_SESSION['cs_add1'] ="";
	if(isset($_POST['scity']))
		$_SESSION['cs_city'] = form_clean($_POST['scity'],20);         //required
	else
		$_SESSION['cs_add1'] ="";
	if(isset($_POST['spostcode']))
		$_SESSION['cs_postcode'] = form_clean($_POST['spostcode'],10); //required	
	else
		$_SESSION['cs_add1'] ="";
	if(isset($_POST['scountry']))
		$_SESSION['cs_country']=form_clean($_POST['scountry'],3); //required
	else
		$_SESSION['cs_add1'] ="";
	if(isset($_POST['sstate']))
		$_SESSION['cs_state'] = form_clean($_POST['sstate'],2);         //required
	else
		$_SESSION['cs_add1'] ="";
	
	if (isset($_POST['same'])) {
		$_SESSION['c_same'] = "on";
	}
	
	else {
		$_SESSION['c_same'] = "off";
	}
	
	if (isset($_POST['same'])) {
		if ($_SESSION['c_same'] == "on") {
			$_SESSION['cs_name'] = $_SESSION['c_name'] ;
			$_SESSION['cs_sname'] = $_SESSION['c_sname'];
			$_SESSION['cs_add1'] = $_SESSION['c_add1'];
			$_SESSION['cs_add2'] = $_SESSION['c_add2'];
			$_SESSION['cs_city'] = $_SESSION['c_city'];
			$_SESSION['cs_postcode'] = $_SESSION['c_postcode'];
			$_SESSION['cs_country']=$_SESSION['c_country'];
			$_SESSION['cs_state']= $_SESSION['c_state'];
			//$_SESSION['cs_title']=$_POST['title'];
		}
	}
	
	$querystring   = "?error=1&";
	$querystring1  = "";
	$querystring2  = "";
	$querystring3  = "";
	$querystring4  = "";
	$querystring5  = "";
	$querystring6  = "";
	$querystring7  = "";
	$querystring8  = "";
	$querystring9  = "";
	$querystring10 = "";
	$querystring11 = "";
	$querystring12 = "";
	$querystring13 = "";
	$querystring14 = "";
	$querystring15 = "";
	$querystring16 = "";
	$querystring17 = "";
	$querystring18 = "";
	$querystring19 = "";
	// Check the required billing address fields are filled in and valid
	
	if (strlen($_SESSION['c_name']) == 0) {
		$querystring1 = "billfname=1&";
	}
	if (strlen($_SESSION['c_sname']) == 0) {
		$querystring2 = "billsurname=1&";
	}
	if (strlen($_SESSION['c_add1']) == 0) {
		$querystring3 = "billadd1=1&";
	}
	if (strlen($_SESSION['c_city']) == 0) {
		$querystring4 = "billcity=1&";
	}
	if (strlen($_SESSION['c_postcode']) == 0) {
		$querystring5 = "billpostcode=1&";
	}
	if (strlen($_SESSION['c_email']) == 0) {
		$querystring6 = "billemail=1&";
	}
	
	else {
		if (validateemail($_SESSION['c_email']) == false) {
			$querystring12 = "valemail=1&";
		}
	}
	
	
	
	if (strlen($_SESSION['c_state']) == 0){
		//if country is equal to US or Canada then state is required
		if ( ($_SESSION['c_country']=="001")||($_SESSION['c_country']=="034"))
			$querystring17= "billstate=1&";
	}
	

	// Check the required billing address fields are filled in and valid
	
	if (strlen($_SESSION['cs_name']) == 0) {
		$querystring7 = "shipfname=1&";
	}
	if (strlen($_SESSION['cs_sname']) == 0) {
		$querystring8 = "shipsurname=1&";
	}
	if (strlen($_SESSION['cs_add1']) == 0) {
		$querystring9 = "shipadd1=1&";
	}
	if (strlen($_SESSION['cs_city']) == 0) {
		$querystring10 = "shipcity=1&";
	}
	if (strlen($_SESSION['cs_postcode']) == 0) {
		$querystring11 = "shippostcode=1&";
	}
	if (strlen($_SESSION['c_country']) == 0)
	{
		$querystring13 = "billcountry=1&";
	}
	if (strlen($_SESSION['cs_country']) == 0)
	{
		$querystring14 = "shipcountry=1&";
	}	
	
	
	if (strlen($_SESSION['cs_state']) == 0){
		//if country is equal to US or Canada then state is required
		if ( ($_SESSION['cs_country']=="001")||($_SESSION['cs_country']=="034"))
			$querystring18= "shipstate=1&";
	}
	
	
	//trim white space from start + end of phone string
	//this ensures if the user has left spaces at the end 
	//that it will still validate
	
	$_SESSION['c_phone'] = trim ($_SESSION['c_phone']);
	
	if (strlen($_SESSION['c_phone']) == 0)
	{
		$querystring15 = "billphone=1&";
	}
	else{
	
		if (is_numeric($_SESSION['c_phone']))
		{
				
		}
		else
		{
			$querystring16 = "valphone=1&";
		}
	}
	
	//Check if product/s can be shipped to the chosen country
	if(isset($_SESSION['cs_country']))
	{
		if(Restricted_shipping(session_id(),$_SESSION['cs_country']) ==false)
			$querystring19="shipping_restrict=1";	
		
	}	
	
	$fullquerystring = $querystring . $querystring1 . $querystring2 . $querystring3 . $querystring4 . $querystring5 . $querystring6. $querystring7 . $querystring8 . $querystring9 . $querystring10 . $querystring11 . $querystring12 . $querystring13 . $querystring14 . $querystring15 . $querystring16 .$querystring17 . $querystring18 .$querystring19;
	
	

	if (strlen($fullquerystring) != 9) {
		// Redirect back to the checkout with errors
		header('Location:' . 'checkout.php' . $fullquerystring);
		exit();
	}
	
	else {
		// Redirect to the next checkout stage
		header('Location: checkout1a.php');
		exit();
	}
}
else
{
	//problem with token
	header('location:checkout.php');
}
?>