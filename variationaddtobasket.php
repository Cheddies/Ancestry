<?php
include("include/siteconstants.php"); 
include("include/commonfunctions.php");
//check that the data is coming from the correct form
//0 is used as the LifeTime of the token, this means it will never timeout
if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{
	$clean=array();
	
	if(isset($_POST['upc']))
	{
		//"-" removed from list of characters to remove as products have this in code
		
		$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");	
		$clean['upc'] = form_clean($_POST['upc'], 20, $badchars);
		
		if(isset($_POST['qty']))
		{
			$clean['quantitywanted'] = form_clean($_POST['qty'],4);
			if(is_numeric($clean['quantitywanted'])==False)
			{
				$clean['quantitywanted']=1;
			}
			else
			{
				if($clean['quantitywanted']<1)
					$clean['quantitywanted']=1;
			}
		}
		else
		{
			$clean['quantitywanted']=1;
		}
		
		

	
		if(isset($_POST['per_name'])&& strlen(trim($_POST['per_name']))>0)
		{
			//If its a personalized item
			$clean['custom'] = form_clean ($_POST['per_name'],10);
		}
		
		
	 	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
	
		$mysql=array();
		$mysql['upc']=mysql_real_escape_string($clean['upc'],$link);
		
		
		// Performing SQL query
		$query = "SELECT name, price, inetthumb,mastersub,compareprice FROM tbl_products WHERE number= '{$mysql['upc']}';";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
		// Closing connection
		mysql_close($link);
		if(mysql_num_rows($result)>0)
		{
			$line = mysql_fetch_array($result, MYSQL_ASSOC);
			$returnname = $line['name'];
			$returnprice = $line['price'];
			$thumb=$line['inetthumb'];
			$mastersub=$line['mastersub'];
			//$returnthumb = GetImageName($line['inetthumb']);
			$returnthumb = GetImageName("$thumb","Small","images/products/",$clean['upc'],"$mastersub");
			$compareprice = $line['compareprice'];
				
			
	
			mysql_free_result($result);
		
			$clean['session'] = session_id();
			$clean['compareprice']=$compareprice;
			$clean['price'] = $returnprice;
			$clean['name']  = $returnname;
			$clean['thumb'] = $returnthumb;
			
			$found = 0;
		
			// Search to see if the product is already in the basket
			$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
			mysql_select_db(DB_NAME) or die('Could not select database');
		
			
			$mysql['session']=mysql_real_escape_string($session,$link);
			
			// Performing SQL query
			$query = "SELECT itemcode, quantity, custom FROM tbl_baskets WHERE sessionid= '{$mysql['session']}';";
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
			// Closing connection
			mysql_close($link);
		
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if (array_search($clean['upc'], $line))
				{
					if(isset($clean['custom']))
					{	
						/*if($clean['custom']==$line['custom'])
						{
							$found=1;
							$quantity = $line['quantity'];
						}*/
					}
					elseif( strlen(trim($line['custom']))==0)
					{
						$found=1;
						$quantity = $line['quantity'];
					}
					
				}
			}
			mysql_free_result($result);
		
			$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
			mysql_select_db(DB_NAME) or die('Could not select database');
			$clean['name']=CleanQuotes($clean['name']);
			
			
			$mysql['quantitywanted']=mysql_real_escape_string($clean['quantitywanted'],$link);
			$mysql['name']=mysql_real_escape_string($clean['name'],$link);
			$mysql['thumb']=mysql_real_escape_string($clean['thumb'],$link);
			$mysql['price']=mysql_real_escape_string($clean['price'],$link);
			$mysql['discount']=mysql_real_escape_string("0",$link);
			$mysql['sale_item']=mysql_real_escape_string("0",$link);
			
			
			if(isset($_SESSION['discount_code']))
			{
				
				$mysql['discount_code']=mysql_real_escape_string($_SESSION['discount_code'],$link);
				
				$lookup_restrictions="SELECT product_code FROM discount_restriction WHERE discount_code='{$mysql['discount_code']}'";
				
				$restriction_result= mysql_query($lookup_restrictions) or die('Query failed: ' . mysql_error());
				if(mysql_num_rows($restriction_result)>0)
				{
					$valid=false;
					while($line=mysql_fetch_array($restriction_result))
					{
						if($line['product_code']==$clean['upc'])
							$valid=true;
					}
				}
				
				if(isset($valid) && $valid==false)
				{
					$mysql['discount']=mysql_real_escape_string("0",$link);
				}
				else
				{
				
					if(isset($_SESSION['discount_amount']))
						$mysql['discount']=mysql_real_escape_string($_SESSION['discount_amount'],$link);
					else
						$mysql['discount']=mysql_real_escape_string("0",$link);
				}	
				
				//Sale item system disabled
					//check to see if this item is in the sale
					//if so then mark in the basket as a sale item
					//this stops the item being discounted again
				
					/*if($clean['compareprice']<=0 || $clean['compareprice']==$clean['price'] || $clean['compareprice']<$clean['price'])
					{
						$mysql['sale_item']=0;
					}
					else
					{
						$mysql['sale_item']=1;
						//Reset discount as well
						$mysql['discount']=0;
					}	*/
				
			}
			
			
			
			if(isset($clean['custom']))
			{
				//If its a personalized item
				$mysql['custom']=mysql_real_escape_string($clean['custom'],$link);
				
				//increase price
				$clean['price']=$clean['price'] + PERSONALISE_EXTRA;
				$mysql['price']=mysql_real_escape_string($clean['price'],$link);
			
				$query = "INSERT INTO tbl_baskets(sessionid, itemcode, name, inetthumb, quantity, price,custom,discount, sale_item) VALUES ('{$mysql['session']}', '{$mysql['upc']}', '{$mysql['name']}' , '{$mysql['thumb']}', {$mysql['quantitywanted']}, {$mysql['price']},'{$mysql['custom']}'), '{$mysql['discount']}', '{$mysql['sale_item']}';";
			}
			else
			{// If it is already in the basket, update the quantity, else add the product
					
				if ($found == 1)
				{
					$newqty=$quantity+$clean['quantitywanted'];
					$mysql['newqty']=mysql_real_escape_string($newqty,$link);
					
					$query = "UPDATE tbl_baskets SET quantity=". $mysql['newqty'] . " WHERE sessionid='{$mysql['session']}' AND itemcode = '{$mysql['upc']}' AND custom='' ;";
				}		
				else
					$query = "INSERT INTO tbl_baskets(sessionid, itemcode, name, inetthumb, quantity, price ,discount, sale_item) VALUES ('{$mysql['session']}', '{$mysql['upc']}', '{$mysql['name']}' , '{$mysql['thumb']}', '{$mysql['quantitywanted']}', '{$mysql['price']}', '{$mysql['discount']}', '{$mysql['sale_item']}');";
			}
			
			mysql_query($query) or die('Query failed: ' . mysql_error());
			
		}//End of IF product code exists
	}//End of IF product code is set
	// Closing connection
	mysql_close($link);
	
	// Redirect to the basket
	header('Location: basket.php');
	exit();
}
else
{
	//problem verifying token
	header('location:index.php');
	
}
?>