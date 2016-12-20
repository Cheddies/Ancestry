<?php

//DB stuff
//live

if  ( $_SERVER['HTTP_HOST']=="10.0.0.10"  || $_SERVER['HTTP_HOST']=="213.2.72.242" || $_SERVER['HTTP_HOST']=="10.0.0.136") 
{
	//test
	define ('DB_HOST',"10.0.0.136");
	define ('DB_USER',"webuser2");
	define('DB_PASS',"v7UtHOes");
	define ('DB_NAME',"ancestry_test");
	define ('SECURE_TRADING_ACCOUNT','testelmbank9808');
	define('WEBROOT', '/new_ancestry/');
	
}
elseif($_SERVER['HTTP_HOST']=="ancestry.internetlogistics.com")
{
	define ('DB_HOST',"localhost");
	define ('DB_USER',"webuser");
	define('DB_PASS',"?A_AnUY@7e");
	define ('DB_NAME',"ancestry");
	define ('SECURE_TRADING_ACCOUNT','testelmbank9808');
	define('WEBROOT', '/');

}
else
{
	//live
	//define ('DB_HOST',"ancestry.cunnpkb38eda.eu-west-1.rds.amazonaws.com");
	//define ('DB_HOST',"78.31.106.175");
	define ('DB_HOST',"db.ancestry.aws");
	define ('DB_USER',"webuser");
	define('DB_PASS',"steRahe9");
	define ('DB_NAME',"ancestry");
	//define ('SECURE_TRADING_ACCOUNT','elmancestry11634');
	define ('SECURE_TRADING_ACCOUNT','testelmbank9808');
	define('WEBROOT', '/');
}


/*

//local user
define ('DB_HOST',"127.0.0.1");
define ('DB_USER',"admin");
define('DB_PASS',"123");
//client server user
/*define ('DB_HOST',"localhost");
define ('DB_USER',"anc");
define('DB_PASS',"wrinkly");*/
/*
define ('DB_NAME',"ancestry_dev");
define ('SECURE_TRADING_ACCOUNT','elmancestry11634');

//change webroot to '/' on live
define('WEBROOT', '/');*/

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
		$session_lookup_query="SELECT * FROM tbl_order_header where ordernumber='{$lookup_sessionid}' AND authorised=1 ";
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
	define ('PRODUCTS_PER_PAGE',$productsperpage);
	
	
	
	
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
	
	/* GRO constants */
	define ('BIRTH','1');
	define ('MARRIAGE','2');
	define ('DEATH','3');
	
	define('SEND_EMAIL_FROM','service@ancestryshop.co.uk');
		
	//For making a certain size the default choice
	define ('DEFAULTSIZE',"");
	
	//Used to title each page
	define ('PAGE_HEADER_TEXT',"Ancestry Shop");
	//Extra text for bar at top of page
	define ('PAGE_HEADER_TEXT_EXTRA',"");
	
	
	
	
	
?>
