<?php
	include("include/siteconstants.php");
	include("include/commonfunctions.php");
	
	session_set_cookie_params ( 0,"/." , "", true);

$clean=array();

if(isset($_POST['cardtype']))
	$clean['cardtype']=form_clean($_POST['cardtype'],2);
	
if(isset($_POST['cardnum']))	
	$clean['cardnum']=form_clean($_POST['cardnum'],20);
	
if(isset($_POST['expirymonth']))
	$clean['expirymonth']=form_clean($_POST['expirymonth'],2);
	
if(isset($_POST['expiryyear']))
	$clean['expiryyear']=form_clean($_POST['expiryyear'],2);
	
if(isset($_POST['issue']))
	$clean['issue']=form_clean($_POST['issue'],3);
	
if(isset($_POST['startmonth']))
	$clean['startmonth']=form_clean($_POST['startmonth'],2);
	
if(isset($_POST['startyear']))
	$clean['startyear']=form_clean($_POST['startyear'],2);


if(isset($_POST['dpmail']))
	$clean['dpmail']=form_clean($_POST['dpmail'],1);
if(isset($_POST['dprent']))
	$clean['dprent']=form_clean($_POST['dprent'],1);
if(isset($_POST['dpemail']))
	$clean['dpemail']=form_clean($_POST['dpemail'],1);

if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{

	
	include("include/final.php");
	
	/*	function gpgencrypt($card) {
		$gpg = 'C:\GNUPG\gpg.exe';
		$recipient = 'daniel@bowettsolutions.com';
		$order = $card;

		$tempfile = 'C:\Inetpub\wwwroot\RosemarysGardenDev\tmp\pre_' . md5(uniqid(time())) . ".txt";

		$file = fopen($tempfile, "w");
		fwrite($file, $order);
		fclose($file);

		$newtempfile = $tempfile . ".asc";

		$cmd = "$gpg --armor --output $newtempfile --recipient $recipient --encrypt $tempfile";

		exec($cmd);

		$fp = fopen($newtempfile, "r");
		$contents = fread($fp, filesize($newtempfile));
		fclose($fp);

		unlink($tempfile);

		return $newtempfile;
	}
*/
	function validate_cardnum($cardnum) {
		$checkdigit=substr($cardnum,-1);
		$remainingcardnum=substr($cardnum,0,strlen($cardnum)-1);

		$i=0;

	    	$checksum = 0;

		while($i < strlen($remainingcardnum)) {
			if($i%2==0)
				$remaing_array[$i]=substr($remainingcardnum,($i+1)*-1,1) * 2;
			else
				$remaing_array[$i]=substr($remainingcardnum,($i+1)*-1,1);
			if($remaing_array[$i]>=10)
				$checksum=$checksum+1;

			$checksum=$checksum+($remaing_array[$i]%10);
			$i++;
		}

		$calculatedcheckdigit=(10-($checksum%10))%10;
		if($calculatedcheckdigit==$checkdigit)
			return "ok";

		else
			return "not ok";
	}

		
	//check to make sure all the other info is set

	
	if(CheckoutSessionCheck()!=true)
	{
		header('Location: checkouterror.php');
	}

	$querystring   = "?error=1&";
	$querystring1  = "";
	$querystring2  = "";
	$querystring3  = "";
	$querystring4  = "";

	// Check the required billing address fields are filled in and valid
	
	
	if ( (isset($clean['cardnum'])!=true) || (strlen($clean['cardnum']) == 0) ) {
		$querystring1 = "mcardnum=1&";
	}

	else {
		if(is_numeric($clean['cardnum']))
		{
			if (validate_cardnum($clean['cardnum']) == "not ok") {

				$querystring3 = "errorcard=1&";
			}
		}
		else
			$querystring3 = "errorcard=1&";
	}
	
	$Month=date("m");
	$Year=date("y");
	
	
	if ( (isset($clean['expirymonth'])!=true) || (isset($clean['expiryyear'])!=true) || (strlen($clean['expirymonth']) == 0) || (strlen($clean['expiryyear']) ==0 ))  {
		$querystring2 = "mexpiry=1" . "&";
	}
	else
	{
		if($clean['expiryyear']==$Year)
		{
			if($clean['expirymonth']<$Month)
			{
				//Expiry date in past 
				$querystring2 = "mexpiry=2" . "&";
			}
		}
	}

	//check issue number is valid
	if ( (isset($clean['issue'])) && ($clean['issue']!="") )
	{
		if(is_numeric($clean['issue'])==False)
			$querystring4 = "errorissue=1" . "&";
	}

	$fullquerystring = $querystring . $querystring1 . $querystring2 . $querystring3 . $querystring4;

	// If there have been errors return them to the previous screen

	if (strlen($fullquerystring) != 9) {
		
		// Redirect back to the checkout with errors
		
		//add the token onto the string
		//Generate a unique token
		//to be used to check the input is 
		//from this form on the proccessing page
		$token=UniqueToken();//will be passed in a hidden form element to proccessing page
		
		//Store the token in the session so it can
		//be checked on the proccessing page
		$_SESSION['token']=$token;
			
		//Also store the time 
		//which can be used to check
		//the lifetime of the token
		
		$_SESSION['token_time']=time();
		$fullquerystring=$fullquerystring."&token=".$token;
		header('Location:' . 'checkout2.php' . $fullquerystring);
		exit();
	}

	// Commit all info to the database, kill the session and forward them onto the receipt

	else {
		//put start date together

		if ( ($clean['startmonth']==0) || ($clean['startyear']==0) )
		{
			$StartDate="";
		}
		else
		{
			$StartDate=$clean['startmonth'] . "/" . $clean['startyear'];
		}
		$ExpiryDate=$clean['expirymonth'] ."/". $clean['expiryyear'];

		$orderdate = date("Y-m-d");
		
		//Set for OPT - OUT 
		if(isset($clean['dpmail']))
			$nomail = 1;
		else
			$nomail = 0;

		if(isset($clean['dprent']))
			$norent = 1;
		else
			$norent = 0;

		if(isset($clean['dpemail']))
			$noemail = 1;
		else
			$noemail = 0;


		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		$mysql=array();
		$mysql['session_id']=mysql_real_escape_string(session_id(),$link);
		$mysql['orderdate']=mysql_real_escape_string($orderdate,$link);
		$mysql['nomail']=mysql_real_escape_string($nomail,$link);
		$mysql['norent']=mysql_real_escape_string($norent,$link);
		$mysql['noemail']=mysql_real_escape_string($noemail,$link);
		
		$query = "INSERT INTO tbl_order_header
			  SET ordernumber = '" . $mysql['session_id'] . "',
			      order_date = '" . $mysql['orderdate'] . "',
			      firstname = '" . mysql_clean($_SESSION['c_name'], 15,$link) . "',
			      lastname = '" . mysql_clean($_SESSION['c_sname'], 20,$link) . "',
			      address1 = '" . mysql_clean($_SESSION['c_add1'], 40,$link) . "',
			      address2 = '" . mysql_clean($_SESSION['c_add2'], 40,$link) . "',
			      city = '" . mysql_clean($_SESSION['c_city'], 20,$link) . "',
			      state ='" . mysql_clean($_SESSION['c_state'], 2,$link) . "',
			      zipcode = '" . FormatPostCode(  mysql_clean($_SESSION['c_postcode'], 10,$link),$_SESSION['c_country']) . "',
			      phone = '" . mysql_clean($_SESSION['c_phone'], 14,$link) . "',
			      email = '" . mysql_clean($_SESSION['c_email'], 50,$link) . "',
			      sfirstname = '" . mysql_clean($_SESSION['cs_name'], 15,$link) . "',
			      slastname = '" . mysql_clean($_SESSION['cs_sname'], 20,$link) . "',
			      saddress1 = '" . mysql_clean($_SESSION['cs_add1'], 40,$link) . "',
			      saddress2 = '" . mysql_clean($_SESSION['cs_add2'], 40,$link) . "',
			      scity = '" . mysql_clean($_SESSION['cs_city'], 20,$link) . "',
			      sstate ='" . mysql_clean($_SESSION['cs_state'], 2,$link) . "',
			      szipcode = '" . FormatPostCode(  mysql_clean($_SESSION['cs_postcode'], 10,$link),$_SESSION['cs_country']) . "',
			      cardtype = '" . encrypt(mysql_clean($clean['cardtype'], 2,$link)) . "',
			      cardnum = '" . encrypt(mysql_clean($clean['cardnum'], 100,$link)) . "',
		          expires = '" . encrypt(mysql_clean($clean['expirymonth'] ."/". $clean['expiryyear'], 5,$link)) . "',
			      issue_num = '" . encrypt(mysql_clean($clean['issue'], 5,$link)) . "',
			      frdate = '" . encrypt(mysql_clean($StartDate, 5,$link)) . "',
			      shipvia = '" . mysql_clean($_SESSION['c_shiptype'], 3,$link) . "',
			      country = '" . mysql_clean($_SESSION['c_country'],3,$link) ."',
			      scountry= '" . mysql_clean($_SESSION['cs_country'],3,$link) ."',
			      nomail = {$mysql['nomail']},
			      norent = {$mysql['norent']},
			      noemail = {$mysql['noemail']};
			      ";

		mysql_query($query) or die('Query failed: ' . mysql_error());

		// Closing connection
		mysql_close($link);

		//Update the products table with stock changes
		UpdateStock(session_id(),DB_NAME,DB_USER,DB_PASS,DB_HOST);
		
		session_unset();
		session_destroy();
		session_start();
		
		
		
		//Generate a unique token
		//to be used to check the input is 
		//from this form on the proccessing page
		if(!isset($token))
		{
			$token=UniqueToken();//will be passed in a hidden form element to proccessing page
		}
		//Store the token in the session so it can
		//be checked on the proccessing page
		$_SESSION['token']=$token;
		
		//Also store the time 
		//which can be used to check
		//the lifetime of the token
		$_SESSION['token_time']=time();
		
		// Redirect to the receipt stage
			
		header('Location: receipt.php?id=' . session_id() . '&token=' .$_SESSION['token']);


		//session_regenerate_id();

		exit();
	}
}
else
{
	//problem with token
	header('location:checkout2.php');
}
?>