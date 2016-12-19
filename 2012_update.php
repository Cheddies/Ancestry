<?php 
/*Uncomment the below for testing*/

/*$mailbody= print_r($_POST, TRUE);  


$from="chris.gan@internetlogistics.com";

mail('chris.gan@elmbank-logistics.co.uk', 'Test Data', $mailbody,"From: " . $from );
*/
/* ENF OF testing */
?>
<?php include("include/siteconstants.php"); ?>
<?php include("include/commonfunctions.php"); ?>
<?php require_once("certificate/admin/include/admin_functions.php"); ?>
<?php

define('HASHPASSWORD','brePuTunUDrUjeZaFr2s4ust2mekUkAD');
$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");
$badchars_amount=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");
$mysql=array();
$clean=array();

//check for all the post fields that must be sent
if(isset($_POST['authcode']) &&
isset($_POST['baseamount']) &&
isset($_POST['errorcode']) &&
isset($_POST['gro']) &&
isset($_POST['mainamount']) &&
isset($_POST['orderreference']) &&
isset($_POST['securityresponsesecuritycode']) &&
isset($_POST['session_id']) &&
isset($_POST['settlestatus']) &&
isset($_POST['status']) &&
isset($_POST['transactionreference']))
{
	//mail('chris.gan@elmbank-logistics.co.uk', 'Test Data', 'Past 1',"From: " . $from );
	
	//then check the hash before going any further
	//this check ensures that the callback is from ST 
	//replaces the needed for the old 'password' field
	//also can not see point in having a token check in place, as that tended to cause issues anyway
	
	$string_to_hash=
	$_POST['authcode'].
	$_POST['baseamount'].
	$_POST['errorcode'].
	$_POST['gro'].
	$_POST['mainamount'].
	$_POST['orderreference'].
	$_POST['securityresponsesecuritycode'].
	$_POST['session_id'].
	$_POST['settlestatus'].
	$_POST['status'].
	$_POST['transactionreference'].
	HASHPASSWORD;

	$hash=hash('sha256',$string_to_hash);

	//check the hash is correct
	//continue if they match
	//else will drop through the default response
	if($_POST['responsesitesecurity']==$hash)
	{
		//mail('chris.gan@elmbank-logistics.co.uk', 'Test Data', 'Past 2',"From: " . $from );
		// Connecting, selecting database
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		//get the variables needed for update

		/*
		****IMPORTANT****
		Should be fixed now
		Fundemental flaw noticed, the notification can contain any niceordernumber and it will therefore send an email for this order number, even though the order hasn't been proccessed correctly.
		Just need to check everything matches up in terms of sessionid/ordernumber/niceordernumber before doing anything else.
		****IMPORTANT****
		*/
	
		//clean up the variables we will use
		$clean['niceodrnum']=form_clean($_POST['orderreference'],50);
		$clean['stauthcode']=form_clean($_POST['authcode'],45);
		$clean['streference']=form_clean($_POST['transactionreference'],45,$badchars);
		$clean['ordernumber']=form_clean($_POST['session_id'],32);
		$clean['amount']=form_clean($_POST['mainamount'],10,$badchars_amount);//amount now comes through as original value,so don't cut out the .
	
		$mysql['stauthcode']=mysql_real_escape_string($clean['stauthcode'],$link);
		$mysql['streference']=mysql_real_escape_string($clean['streference'],$link);
		$mysql['ordernumber']=mysql_real_escape_string($clean['ordernumber'],$link);
		$mysql['niceordernum']=mysql_real_escape_string($clean['niceodrnum'],$link);
		$mysql['amount']=mysql_real_escape_string($clean['amount'],$link);
	
	
		//check and update GRO table
		if(isset($_POST['gro']) && $_POST['gro']!='' && $_POST['gro']!='No Value')
		{
			//order number needs splitting out for GRO
			$ordnum_exploded = explode("_", $clean['niceodrnum']);
			$justnumber_niceodrnum = $ordnum_exploded[2];
			$mysql['niceordernum']=mysql_real_escape_string($justnumber_niceodrnum,$link);	
			
			$find_order="SELECT GRO_orders_id FROM GRO_orders WHERE order_number='{$mysql['ordernumber']}' AND GRO_orders_id='{$mysql['niceordernum']}'";
			$mailbody=$find_order ."\n";
			//mail('chris.gan@elmbank-logistics.co.uk', 'Test Data', $mailbody,"From: " . $from );
			$order_result = mysql_query($find_order) or die('Query failed: ' . mysql_error());
			
			/*$mailbody = print_r($order_result, TRUE);
			$mailbody = $mailbody ."\n" . mysql_num_rows($order_result);
			mail('chris.gan@elmbank-logistics.co.uk', 'Test Data', $mailbody,"From: " . $from );*/
			
			if(mysql_num_rows($order_result)>0)
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
				
				
				//un comment this for email
				SendConfirmationEmail($justnumber_niceodrnum);
				
				//this was commented out so not needed
				//GRO_email($clean['ordernumber'],$clean['niceodrnum']);
				
				//a response needs to be sent from the page else it will either be retried later (won't work properly for that session stuff, or can will hang in 'online mode'
				//"HTTP/1.1 200 OK
				header('Content-Type: text/html');
				header("Response",true,'200') ;
				exit();
			}	
		}//end of GRO update
		else
		{
			//check and update shop order table
		
			//check to ensure the niceordernumber matches up with the session id ordernumber
			//this will only be incorrect if an update from a test site happens to go to a live site or vice versa
			//and then the email could be triggered errounsly
			$find_order="SELECT niceordernum FROM tbl_order_header WHERE ordernumber='{$mysql['ordernumber']}' AND niceordernum='{$mysql['niceordernum']}'";
			
			$mailbody=$find_order ."\n";
			//mail('chris.gan@elmbank-logistics.co.uk', 'Test Data', $mailbody,"From: " . $from );
			
			$order_result = mysql_query($find_order) or die('Query failed: ' . mysql_error());
			
			$mailbody = print_r($order_result, TRUE);
			$mailbody = $mailbody ."\n" . mysql_num_rows($order_result);
			//mail('chris.gan@elmbank-logistics.co.uk', 'Test Data', $mailbody,"From: " . $from );
			
			if(mysql_num_rows($order_result)>0)
			{
				
				//mail('chris.gan@elmbank-logistics.co.uk', 'Test Data', 'Past 3',"From: " . $from );
				//continue if a result is returned
				
			
				//this is the basic update the needs to happen
				//just to be sure this updates only a row where the ordernumber and niceordernum match
				$query="UPDATE tbl_order_header SET authorised=1,st_ref='{$mysql['streference']}',st_autho='{$mysql['stauthcode']}',total_paid={$mysql['amount']} WHERE ordernumber='{$mysql['ordernumber']}' AND niceordernum='{$mysql['niceordernum']}'";
				
				//mail('chris.gan@elmbank-logistics.co.uk', 'Test Data', $query,"From: " . $from );
				
				$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
				//site stuff that needs to happen to clean up the order and finish off
		
				//set the SESSION to the order number (this ensures the session data is the correct one
		
				session_id($clean['ordernumber']);
		
				//set a variable to state the whole checkout proccess is complete
				//this is then checked in siteconstants.php, if it is set then the session
				//is cleared and a new one started
				
				$_SESSION['checkout_finished']=true;
		
				// Closing connection
				mysql_close($link);
				
				//Update the products table with stock changes
				UpdateStock($clean['ordernumber'],DB_NAME,DB_USER,DB_PASS,DB_HOST);
			
				//this needs to have the variable $clean['niceodrnum'] set
				include("include/sendemail.php");
		
				
				//a response needs to be sent from the page else it will either be retried later (won't work properly for that session stuff, or can will hang in 'online mode'
				//"HTTP/1.1 200 OK
				header('Content-Type: text/html');
				header("Response",true,'200') ;
				exit();
			}
		}//end of shop update
	}
}

//default response on the page will be to send bad request
//this will only be hit if the update does not go through
header('Content-Type: text/html');
header("Response",true,'400') ;
exit();	

?>