<?php

//DB stuff
/*define ('TEST',false);

if(TEST)
{
	define ('DB_HOST',"10.0.0.136");
	define ('DB_USER',"webuser2");
	define('DB_PASS',"v7UtHOes");
	define ('DB_NAME',"ancestry");
}
else
{
	//change to admin?
	define ('DB_HOST',"ancestry.cunnpkb38eda.eu-west-1.rds.amazonaws.com");
	define ('DB_USER',"webuser");
	define('DB_PASS',"steRahe9");
	define ('DB_NAME',"ancestry");
}
*/

if  ( $_SERVER['HTTP_HOST']=="10.0.0.10"  || $_SERVER['HTTP_HOST']=="213.2.72.242" || $_SERVER['HTTP_HOST']=="10.0.0.136") 
{
	define ('DB_HOST',"10.0.0.136");
	define ('DB_USER',"webuser2");
	define('DB_PASS',"v7UtHOes");
	define ('DB_NAME',"ancestry");
	define ('FCK_BASEPATH','/ancestry/certificate/admin/fckeditor/');
}
else
{
	define ('DB_HOST',"ancestry.cunnpkb38eda.eu-west-1.rds.amazonaws.com");
	define ('DB_USER',"webuser");
	define('DB_PASS',"steRahe9");
	define ('DB_NAME',"ancestry");
	define ('FCK_BASEPATH','/certificate/admin/fckeditor/');
}

require_once ("db_session.php");
//session_start();

//Check to ensure this isn't a session that has allready been cleared.
//Ensures a basket will not be assoiciated with a user after the checkout proccess has cleared all 
//session variables.
if(isset($_SESSION['checkout_finished']))
{
	session_unset();
	session_regenerate_id(true);
	$_SESSION['new_customer']=true;
	
	/*session_destroy();*/
	/*session_start();*/
	
}
elseif(!isset($_SESSION['new_customer']) || $_SESSION['new_customer']!=true)
{
	//if the 'new customer' var set
	session_unset();
	session_regenerate_id(true);
	$_SESSION['new_customer']=true;
	
}
else
{
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
		
		$lookup_sessionid=mysql_real_escape_string(session_id());
		$session_lookup_query="SELECT * FROM GRO_orders where order_number='{$lookup_sessionid}' AND authorised=1 ";
		$session_lookup_result=mysql_query($session_lookup_query);
		if(mysql_num_rows($session_lookup_result)>0)
		{
			session_unset();
			session_regenerate_id(true);
			$_SESSION['new_customer']=true;
		}
}

/*if(!isset($_SESSION['new']))
{
	session_unset();
	session_regenerate_id(true);
	$_SESSION['new']=true;
}*/


$_SESSION['TEST']="Some Test Text";

?>
<?php
	$productsperpage =  10000;//large number to stop being split into pages
	$subdeptsperrow  = 3;
	$siteaddress     = "localhost";
	$sitename        = "Ancestry";
	$productsperrow = 2;
	
	define ('PRODUCTS_PER_ROW',"3");
	
	
	
	
	
	//Features ON / OFF
	
	//1 is auction on, i.e. link displayed at top
	//0 is off, i.e. no link at top. Going to auction.php will still display auction details
		
	define ('AUCTION',"0");
	
	//define ('AUCTIONUPC',"04464");
	
	
	//International Prices or not
	//Display Euros/Dollars with prices
	//1 on
	//0 off
	
	//define ('PRICES',"1");
	define ('PRICES',"0");	
	define ('RELATED_ITEMS',"0");

	
	define ('DOLLAR',"1");
	define ('EURO',"2");
	define ('NONE',"0");
	
	//Stock Text
	define ('NOCHARGE',"");
	define ('DELIVERYTIME',"Select our express shipping option to receive within 2 working days (UK delivery only)");
	
	
	//For making a certain size the default choice
	define ('DEFAULTSIZE',"");
	
	//Used to title each page
	define ('PAGE_HEADER_TEXT',"Ancestry Shop");
	//Extra text for bar at top of page
	define ('PAGE_HEADER_TEXT_EXTRA',"");
	

	/* GRO constants */
	define ('BIRTH','1');
	define ('MARRIAGE','2');
	define ('DEATH','3');
	
	define('CERT_COST','10.00');
	
	/*Regular Expressions for validating input*/
	
	define ('POSTCODE_REG_EX','^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$');
	define ('EMAIL_REG_EX','^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$');
	
	define ('YEAR_REG_EX','[1-2][0-9][0-9][0-9]');
	define ('MONTH_REG_EX','(0[1-9]|1[012])');
	define ('DAY_REG_EX','(0[1-9]|1[0-9]|2[0-9]|3[0-1])');
	
	
	define('ORDERS_PER_PAGE',200);
?>