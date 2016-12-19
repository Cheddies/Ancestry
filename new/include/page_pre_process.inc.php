<?php
require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
//Generate a unique token
//to be used to check the input is 
//from this form on the proccessing page
if(!isset($search_token))
{
	if(isset($_GET['search_token']))
	{
		$search_token=form_clean($_GET['search_token'],32);
	}
	else
		$search_token=UniqueToken();//will be passed in a hidden form element to proccessing page
	
}

//Store the token in the session so it can
//be checked on the proccessing page
$_SESSION['search_token']=$search_token;

//Also store the time 
//which can be used to check
//the lifetime of the token
$_SESSION['search_token_time']=time();

$clean=array();
$mysql=array();



if(isset($_SERVER['PHP_SELF']) && !$Page)
{
	$Page=basename($_SERVER['PHP_SELF']);
	$Page=substr($Page,0,strlen($Page)-4);
}

if($Page=='search')
{
	header("Pragma: no-cache");
	header("Expires: 0");
}
	
$checkout_page=false;
//any new checkout pages should be added to this list
if($Page=='checkout' || $Page=='checkout1a' || $Page=='checkout1b' || $Page=='checkout2' || $Page=='basket'){
	$checkout_page=true;
}

$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');
		
	
//meta tags	
$keywords="";
$description="";
$tite="";
$bc_title="";
$variable="";
	
//special case index page - is used on 'sub' sites (e.g. certificate pages)
if($Page=='index')
{
	$variable = "shop";
}		

//lookup meta tags
if(!isset($pageData['meta'])){
	get_page_meta_tags($Page,$keywords,$description,$title,$variable,$bc_title);
	if(!$title){
		$title=PAGE_HEADER_TEXT;
	}	
	//set the page data
	$pageData['meta']['description'] = $description;
	$pageData['meta']['keywords'] = $keywords;
	$pageData['meta']['title'] = $title;
}
	
//view my basket link
$mysql['session_id']=mysql_real_escape_string(session_id(),$link);

$items_query="SELECT COUNT(*) as items_in_basket FROM tbl_baskets WHERE sessionid='{$mysql['session_id']}'";
$items_result=mysql_query($items_query);
if($line=mysql_fetch_array($items_result,MYSQL_ASSOC)){
	$clean['items_in_basket']=$line['items_in_basket'];
	$plur = $clean['items_in_basket'] >1 ? 'items' : 'item';
	$basketText = "<a href=\"" . WEBROOT . "basket\" title=\"View My Basket\">My Basket ({$clean['items_in_basket']} $plur)</a>";
} else {
	$clean['items_in_basket']=0;
}
if($clean['items_in_basket'] <1){
	$basketText = 'My Basket (Empty)';
}

//== currency conversion =====================
/*
if(isset($_GET['cur'])){
	$curCode = strtoupper(form_clean($_GET['cur'],3));
	if(!isset($_SESSION['currency']['code']) || $_SESSION['currency']['code'] != $curCode){
		$curInfo = getCurrencyInfo($curCode);
		if(!empty($curInfo) && $curCode != 'GBP'){
			$_SESSION['currency']['code'] = $curInfo['code'];
			$_SESSION['currency']['rate'] = $curInfo['rate'];
			$_SESSION['currency']['symbol'] = $curInfo['symbol'];
		} else {
			unset($_SESSION['currency']);
		}
	}
} 
if(isset($_SESSION['currency']['code']) && isset($_SESSION['currency']['rate']) && isset($_SESSION['currency']['symbol'])){
	//get rate from session if its been set
	$curCode = $_SESSION['currency']['code'];
	$curRate = $_SESSION['currency']['rate'];
	$curSymbol = $_SESSION['currency']['symbol'];
} else {
	//default to GBP
	$curCode = 'GBP';
	$curRate = 1;
	$curSymbol = "&pound;";
	$_SESSION['currency']['code'] = $curCode;
	$_SESSION['currency']['rate'] = $curRate;
	$_SESSION['currency']['symbol'] = $curSymbol;
}
*/
?>