<?php
require_once('include/siteconstants.php');
require_once('include/commonfunctions.php');
require_once("include/promo_functions.php");

session_set_cookie_params ( 0,"/." , "", true);
if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	
	//set a variable to state the whole checkout proccess is complete
	//this is then checked in siteconstants.php, if it is set then the session
	//is cleared and a new one started
	
	$_SESSION['checkout_finished']=true;
	
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
			
	$mysql['niceodrnum']=mysql_real_escape_string($_SESSION['niceodrnum'],$link);
	$mysql['stauthcode']=mysql_real_escape_string('NO_AUTHCODE',$link);
	$mysql['streference']=mysql_real_escape_string('NO_STREF',$link);
	$mysql['ordernumber']=mysql_real_escape_string(session_id(),$link);
	$mysql['amount']=mysql_real_escape_string('0',$link);
	$mysql['promo_code']=mysql_real_escape_string($_SESSION['promo_code'],$link);
			
		
	//additional check added to WHSmith site
	//check the promo code table to ensure the current ordernumber/session id matches the one stored agaisnt this promocode
	$select="SELECT * FROM promo_codes_whsmith WHERE promo_code='{$mysql['promo_code']}' AND orderid='{$mysql['ordernumber']}'";
	$result=mysql_query($select)  or die('Query failed: ' . mysql_error());
	
	if(mysql_num_rows($result)==0)
	{
		//the current order does not match with the session used to enter the site last
		//redirect to an error page 
		header('location:complete_error.php');
		exit();
	}
	
		
	//update the whsmith header table tbl_order_header_promo
	$query="UPDATE tbl_order_header_whsmith SET authorised=1,st_ref='{$mysql['streference']}',st_autho='{$mysql['stauthcode']}',total_paid={$mysql['amount']} WHERE ordernumber='{$mysql['ordernumber']}'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			
	//update the whsmith promo code table to mark the code as used
	$update="UPDATE promo_codes_whsmith SET used=1,orderid='{$mysql['ordernumber']}' WHERE promo_code='{$mysql['promo_code']}'";
	$result = mysql_query($update) or die('Query failed: ' . mysql_error());
					
	//send an alternative confirmation email
	include("include/whsmith_email_functions.php"); 
			
	whsmith_email($_SESSION['niceodrnum']);
	
	include ('include/header.php');
	include "include/checkout_header.php";
	?>
	<h2>Registration Complete</h2>
	<div class="checkout_box full">
		<h3>Your registration been received successfully</h3>
		<p>
			For your reference your reference number is:<b> <?php  echo $_SESSION['niceodrnum'] ?> </b>
			<br />
			An email has been sent to confirm your registration
		</p>
	</div>
	<?php
	include ('include/footer.php');
}
else
{
	header('location:confirm_details.php');
	exit();
}

 
?>