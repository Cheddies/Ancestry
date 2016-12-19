<?php

if  ( (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS']!='on') && ($_SERVER['HTTP_HOST']!="10.0.0.3" ) && ($_SERVER['HTTP_HOST']!="212.38.95.165") && ($_SERVER['HTTP_HOST']!="10.0.0.136" )  ) 
{
	//if the page is not accessed via SSL
	//redirect to the SSL version
	//this is done for all pages to ensure the site is always accessed over SSL
	//this is important to ensure the secure session will work as well
	
	
	$page = $_SERVER['PHP_SELF'];
	$page = str_replace(" ","%20",$page);
	
	$page =  "https://" . $_SERVER['HTTP_HOST'] . $page ;	

	$page = "Location:" . $page;
	header($page);
	exit();
}
else
{
	//once the check for SSL connection has been past
	//check again (in case the host was allowed to be no-secure
	//if the page is still SSL secured, then ensure the cookie is only accessed securely
	//session_set_cookie_params is called BEFORE session_start() to enable this
	
	//note domain added after issues with the session_regenerate_id call in complete checkout
	//see comments on http://uk2.php.net/session_regenerate_id
	
	if ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'))
	{
		//session_set_cookie_params ( 0,"/." , "", true);
		session_set_cookie_params ( 0,"/" , "www.ancestryshop.co.uk", true);
	}
}

//DB stuff

if  ( $_SERVER['HTTP_HOST']=="10.0.0.10"  || $_SERVER['HTTP_HOST']=="213.2.72.242" || $_SERVER['HTTP_HOST']=="10.0.0.136") 
{
	define ('DB_HOST',"10.0.0.136");
	define ('DB_USER',"webuser2");
	define('DB_PASS',"v7UtHOes");
	define ('DB_NAME',"ancestry");
}
else
{
	define ('DB_HOST',"ancestry.cunnpkb38eda.eu-west-1.rds.amazonaws.com");
	define ('DB_USER',"webuser");
	define('DB_PASS',"steRahe9");
	define ('DB_NAME',"ancestry");
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
	
	/*Special cases where characters to remove are defined*/
	
	define ('EMAIL_REMOVE',serialize(array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"")));
	
	define ('PASSWORD_REMOVE',serialize(array("\n","\r")));
	
	
	
	
?>