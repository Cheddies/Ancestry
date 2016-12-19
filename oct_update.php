<?php include("include/siteconstants.php"); ?>
<?php include("include/commonfunctions.php"); ?>
<?php require_once("certificate/admin/include/admin_functions.php"); ?>

<?php


$mysql=array();
$clean=array();

echo print_r($_POST);

if(isset($_POST['amount']) && is_numeric($_POST['amount']) && isset($_POST['stresult']) && isset( $_POST['orderref']) && isset($_POST['stauthcode'])&& isset($_POST['streference']) && isset($_POST['session_id']) && isset($_POST['token']) && isset($_POST['stLUjlePHoAtrLUqoUyO4soEgluPROuToExlurleCrIutlED8leswiaThleThoad']) )
{

	
$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");
$clean['stresult'] = form_clean($_POST['stresult'],1);
$clean['niceodrnum']=	form_clean($_POST['orderref'],50);
$clean['stauthcode']=form_clean($_POST['stauthcode'],45);
$clean['streference']=form_clean($_POST['streference'],45,$badchars);
$clean['ordernumber']=form_clean($_POST['session_id'],32);
$clean['token']=form_clean($_POST['token'],32);
$clean['amount']=form_clean($_POST['amount'],10);
$clean['amount']=$clean['amount']/100;

	

// Connecting, selecting database
$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

		
$mysql['niceodrnum']=mysql_real_escape_string($clean['niceodrnum'],$link);
$mysql['stauthcode']=mysql_real_escape_string($clean['stauthcode'],$link);
$mysql['streference']=mysql_real_escape_string($clean['streference'],$link);
$mysql['ordernumber']=mysql_real_escape_string($clean['ordernumber'],$link);
$mysql['amount']=mysql_real_escape_string($clean['amount'],$link);

/*Changed for RAF website as previous method wasn't working*/


//set the SESSION to the order number (this ensures the session data is the correct one

session_id($clean['ordernumber']);

//set a variable to state the whole checkout proccess is complete
//this is then checked in siteconstants.php, if it is set then the session
//is cleared and a new one started

$_SESSION['checkout_finished']=true;

/*echo "Test Text: ";
echo $_SESSION['TEST'];

session_unset();
session_destroy();
echo "Test Text after destroy: ";
echo $_SESSION['TEST'];

unset($_SESSION['TEST']);
echo "Test Text after unset TEST: ";
echo $_SESSION['TEST'];
unset($_SESSION['new']);
*/
/*session_start();*/

if(isset($_POST['gro']) && $_POST['gro']!='' && $_POST['gro']!='No Value')
{
	//UPdate the GRO_orders DB
	$query="SELECT token,token_time,st_ref,st_autho FROM GRO_orders WHERE order_number='{$mysql['ordernumber']}'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	$line=mysql_fetch_array($result);
	if(Valid_ST_Ref($clean['streference'])&& $clean['stresult']==1 && ValidToken($_POST['token'],$line['token'],$line['token_time'],0) && ( (strlen(trim($line['st_ref'])) +  strlen(trim($line['st_autho'])))==0) )
	{
		$query="UPDATE GRO_orders SET authorised=1,st_ref='{$mysql['streference']}',st_autho='{$mysql['stauthcode']}',total_paid={$mysql['amount']} WHERE order_number='{$mysql['ordernumber']}'";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			
			
		// Closing connection
		mysql_close($link);
		
		//send an alternative confirmation email
		include("include/GRO_email_functions.php"); 
		
				
		//GRO_email($justnumber_niceodrnum);
		//changed to use the ordernumber (i.e. session_id) rather than nice ordernumber
		//niceodrnum is still passed as displayed to customer, this is then displayed on the email
		
		//SendConfirmationEmail($clean['niceodrnum']);
		$ordnum_exploded = explode("_", $clean['niceodrnum']);
		$justnumber_niceodrnum = $ordnum_exploded[2];
		SendConfirmationEmail($justnumber_niceodrnum);
		//GRO_email($clean['ordernumber'],$clean['niceodrnum']);
	}
}

if(isset($_POST['promo_type']) && isset($_POST['promo_code']) && $_POST['promo_code']!='' && $_POST['promo_type']!='' && $_POST['promo_code']!='No Value' && $_POST['promo_type']!='No Value' )
{
	
	
	$query="SELECT token,token_time,st_ref,st_autho FROM tbl_order_header_promo WHERE ordernumber='{$mysql['ordernumber']}'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	$line=mysql_fetch_array($result);
	
	if(Valid_ST_Ref($clean['streference'])&& $clean['stresult']==1 && ValidToken($_POST['token'],$line['token'],$line['token_time'],0) && ( (strlen(trim($line['st_ref'])) +  strlen(trim($line['st_autho'])))==0) )
	{
		$clean['promo_code']=form_clean($_POST['promo_code'],40);
		$clean['promo_type']=form_clean($_POST['promo_type'],40);
		
		$mysql['promo_code']=mysql_real_escape_string($clean['promo_code'],$link);
		
		//different proccess for the upgrade offer checkout
		
		//update the promo header table tbl_order_header_promo
		$query="UPDATE tbl_order_header_promo SET authorised=1,st_ref='{$mysql['streference']}',st_autho='{$mysql['stauthcode']}',total_paid={$mysql['amount']} WHERE ordernumber='{$mysql['ordernumber']}'";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
		//update the promo code table to mark the code as used
		$update="UPDATE promo_codes SET used=1,orderid='{$mysql['ordernumber']}' WHERE promo_code='{$mysql['promo_code']}'";
		$result = mysql_query($update) or die('Query failed: ' . mysql_error());
				
		//send an alternative confirmation email
		include("include/promo_email_functions.php"); 
		
		promo_email($clean['niceodrnum'],$clean['promo_type']);
	}
		
}	
else
{
	
	$query="SELECT token,token_time,st_ref,st_autho FROM tbl_order_header WHERE ordernumber='{$mysql['ordernumber']}'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	$line=mysql_fetch_array($result);
	
	if(Valid_ST_Ref($clean['streference'])&& $clean['stresult']==1 && ValidToken($_POST['token'],$line['token'],$line['token_time'],0) && ( (strlen(trim($line['st_ref'])) +  strlen(trim($line['st_autho'])))==0) )
	{
				
			$query="UPDATE tbl_order_header SET authorised=1,st_ref='{$mysql['streference']}',st_autho='{$mysql['stauthcode']}',total_paid={$mysql['amount']} WHERE ordernumber='{$mysql['ordernumber']}'";
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			
			
			// Closing connection
			mysql_close($link);
			
			//Update the products table with stock changes
			UpdateStock($clean['ordernumber'],DB_NAME,DB_USER,DB_PASS,DB_HOST);
		
			include("include/sendemail.php");
		
	}
}

}
?>