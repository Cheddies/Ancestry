<?php   
include("include/siteconstants.php"); 
include("include/commonfunctions.php");
	
if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$clean=array();
	$mysql=array();
	$discount_update="";
	
	//get discount code
	if(isset($_POST['discount_code']))
	{
		$clean['discount_code']=form_clean($_POST['discount_code'],20);
		$mysql['discount_code']=mysql_real_escape_string($clean['discount_code'],$link);
			
		//$discount_query="SELECT discount FROM discount_codes WHERE code = '{$mysql['discount_code']}'";
		$discount_query="SELECT discount FROM discount_codes WHERE code = '{$mysql['discount_code']}' AND (CURDATE() BETWEEN start_date AND end_date OR (start_date='0000-00-00' && end_date='0000-00-00')) ";
		$result=mysql_query($discount_query) or die('Query failed: ' . mysql_error());
			
		if(mysql_num_rows($result))
		{
			
			$line=mysql_fetch_array($result,MYSQL_ASSOC);
						
			$discount_amount=$line['discount'];
			$discount_update=", discount={$discount_amount}";
			
			$_SESSION['discount_code']=$clean['discount_code'];
			$_SESSION['discount_amount']=$discount_amount;
						
		}	
		
		$lookup_restrictions="SELECT product_code FROM discount_restriction WHERE discount_code='{$mysql['discount_code']}'";
		$restriction_result= mysql_query($lookup_restrictions) or die('Query failed: ' . mysql_error());
		
		if(mysql_num_rows($restriction_result)>0)
		{
			$discount_valid=false;
			$discount_restiction_products=array();
			$count=0;
			while($line=mysql_fetch_array($restriction_result))
			{
				$discount_restriction_products[$count]=$line['product_code'];
				$count++;
			}
		}
		else	
			$discount_valid=true;
			
	}
	$form_fields = array_keys($HTTP_POST_VARS);
	$session = session_id();
		
	for ($i = 0; $i < sizeof($form_fields); $i++) {
		
		$mysql=array();
		$mysql['session']=mysql_real_escape_string($session,$link);
		
		$thisfield = $form_fields[$i];
		$thisvalue = $HTTP_POST_VARS[$thisfield];
		
		//"-" removed from list of characters to remove as products have this in code
		$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");	
		
		$clean['thisfield']=form_clean($form_fields[$i],22,$badchars);//3 more than max length of product code to allow for UPC prefix
		$clean['thisvalue']=form_clean($HTTP_POST_VARS[$clean['thisfield']],4);
			
		if(substr($clean['thisfield'],0,3) == "UPC") 
		{
			$clean['upcchange']=substr($clean['thisfield'], 3, (strlen($clean['thisfield'])-3));
			$mysql['upcchange']=mysql_real_escape_string($clean['upcchange'],$link);
		}
					
		if(substr($clean['thisfield'],0,4) == "CUST") 
		{
		
			$clean['custchange']=substr($clean['thisfield'], 4, (strlen($clean['thisfield'])-4));
			$mysql['custchange']=mysql_real_escape_string($clean['custchange'],$link);
				
		}
		else
		{
		
		if(substr($clean['thisfield'],0,2) == "ID")
		{
			if(is_numeric($clean['thisvalue'])==False)
			{
				$clean['thisvalue']=1;
			}
			else{
				if($clean['thisvalue']<1)
				{
					$clean['thisvalue']=1;
			
				}
			}
		}
		
		}
		if (substr($clean['thisfield'], 0, 2) == "ID") {
			$clean['idchange'] = substr($clean['thisfield'], 2, (strlen($clean['thisfield'])-2));
						
			$mysql['thisvalue']=mysql_real_escape_string($clean['thisvalue'],$link);
			$mysql['idchange']=mysql_real_escape_string($clean['idchange'],$link);
			
			
			//check to see if there are restrictions on discount code
			if(isset($discount_restriction_products))
			{
				$discount_valid=false;
				foreach($discount_restriction_products as $product_code)
				{
					if($clean['upcchange']==$product_code)
						$discount_valid=true;
				}
			}
			
			
			if(isset($mysql['custchange']))
			{
				if(isset($discount_valid)&& $discount_valid==true)
				{
					$sql = "UPDATE tbl_baskets SET tbl_baskets.quantity = {$mysql['thisvalue']} " .$discount_update ." WHERE tbl_baskets.basketid = '{$mysql['idchange']}' AND tbl_baskets.sessionid = '{$mysql['session']}' AND custom='{$mysql['custchange']}'  AND sale_item!=1;";
					mysql_query($sql) or die('Query failed: ' . mysql_error());
					
					$sql = "UPDATE tbl_baskets SET tbl_baskets.quantity = {$mysql['thisvalue']} WHERE tbl_baskets.id = '{$mysql['idchange']}' AND tbl_baskets.sessionid = '{$mysql['session']}' AND custom='{$mysql['custchange']}'  AND sale_item=0;";
					mysql_query($sql) or die('Query failed: ' . mysql_error());
				}
				else
				{
					$sql = "UPDATE tbl_baskets SET tbl_baskets.quantity = {$mysql['thisvalue']}, discount='0' WHERE tbl_baskets.id = '{$mysql['idchange']}' AND tbl_baskets.sessionid = '{$mysql['session']}' AND custom='{$mysql['custchange']}'  ";
					mysql_query($sql) or die('Query failed: ' . mysql_error());
				}
			}
			else
			{
				if(isset($discount_valid)&& $discount_valid==true)
				{	
					
					//1 to update none sale items which includes the discount code
					$sql = "UPDATE tbl_baskets SET tbl_baskets.quantity = {$mysql['thisvalue']}" .$discount_update . " WHERE tbl_baskets.basketid = '{$mysql['idchange']}' AND tbl_baskets.sessionid = '{$mysql['session']}' AND sale_item!=1 ;";
					mysql_query($sql) or die('Query failed: ' . mysql_error());
					//2 to update sale items and not include the discount code
					$sql = "UPDATE tbl_baskets SET tbl_baskets.quantity = {$mysql['thisvalue']} WHERE tbl_baskets.basketid = '{$mysql['idchange']}' AND tbl_baskets.sessionid = '{$mysql['session']}' AND sale_item=1 ;";
					mysql_query($sql) or die('Query failed: ' . mysql_error());
				}
				else
				{
				
					$sql = "UPDATE tbl_baskets SET tbl_baskets.quantity = {$mysql['thisvalue']} , discount='0'  WHERE tbl_baskets.basketid = '{$mysql['idchange']}' AND tbl_baskets.sessionid = '{$mysql['session']}'  ;";
					mysql_query($sql) or die('Query failed: ' . mysql_error());
					
					
				}
			}
		}
	}

	// Closing connection
	mysql_close($link);

	// Redirect to the basket
	header('Location: basket.php');
	exit();
}
else
{
	//problem with token
 	header('location:index.php');
}

?>