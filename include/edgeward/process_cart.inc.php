<?php
require_once('include/edgeward/global_functions.inc.php');

//clean POST data
//$post = cleanData($_POST,array('token'));
//IL change
$post=array();
if(!empty($_POST) && isset($_POST['token']))
	$post['token']= form_clean($_POST['token'], 32);
	
//end of IL change
//check the security tokens match
if(!empty($post)){
	if( empty($post['token']) || $post['token'] != $_SESSION['token'] ){
		header('Location:basket.php');
		exit();
	}
}
// Unset the token, so that it cannot be used again
unset($_SESSION['token']);
//gen new token
$token = md5(uniqid(rand(), true));
$_SESSION['token'] = $token;

//create array with session values
$order = isset($_SESSION['shop']) ? $_SESSION['shop'] : array();
//get any validation errors
$invalidFields = isset($_SESSION['inv']) ? $_SESSION['inv'] : array();
//reset alert flash
$alertFlash = isset($_SESSION['flash']) ? $_SESSION['flash'] : '';

//get countries
$countries = getCountries();
$countryCount = count($countries);
$showDelOptions = ($countryCount == 1);
//get shipping options
$disableCart = true;
if($countryCount == 1){
	//if only one country available, make that one default
	$k = array_keys($countries);
	$c = $countries[$k[0]];	
	$disableCart = ($formtype == 'summary');
} else {
	//country = whichever one is chosen
	$c = $order['order_header']['scountry'];
	//dont disable the cart if summary page only shows the delivery options
	$disableCart = false;
}
if($c){
	openConn();
	$sql = sprintf("SELECT s.code AS code, s.price AS price, s.description AS description,s.notes AS notes FROM tbl_shipping AS s, tbl_country_shipping AS c WHERE s.code = c.shipping AND c.country = '%s';",
				   mysql_real_escape_string($c));
	$shipData = sqlQuery($sql);
	mysql_close();
}

//allways reset to false, so previously valid forced shipping methods do not stick if they are no longer valid
$forceShippingValid=false;
//check to force a shipping method based on offer code
if(isset($order['offer_code']) && (strlen(trim($order['offer_code']))>0))
{
	//return forced shipping data if offer code has a forced shipping method that is valid
	//$forceShippingValid is set to true when there is a valid forced shipping method
	$new_delivery_method=CheckForceShipping($order['offer_code'],$order['baskets'],&$shipData,&$forceShippingValid);	
	if($new_delivery_method)
	{
		$order['delivery_method']=$new_delivery_method;
	}
}

//if delivery method not yet set, check first option
if(!isset($order['delivery_method'])) $order['delivery_method'] = $shipData[0]['code'];
else
{
	//if a shippig method is allread set
	//ensure it is still a valid selection
	
	$shipping_valid=false;
	//check for invalid shipping method currently being set
	foreach($shipData as $validShip)
	{
		if($order['delivery_method']==$validShip['code'])
		{
			$shipping_valid=true;
			break;
		}
	}
	
	//reset to first option if not valid
	if(!$shipping_valid)
		$order['delivery_method'] = $shipData[0]['code'];
}

//if update or not summary page, remove editset
if($formtype != 'summary') unset($_SESSION['editset']);
$editset = $_SESSION['editset'];

//update basket qty, discount and shipping
$lineItems = $order['baskets'];
$lineTotal = 0;
$orderTotal = 0.00;
$itemTotal = 0.00;
$qtyTotal = 0;
$discTotal = 0;
//get the shipping price from db
openConn();
$sql = sprintf("SELECT price FROM tbl_shipping WHERE code = '%s';",
			   mysql_real_escape_string($order['delivery_method']));
$sResult = sqlQuery($sql);
mysql_close();
$shipTotal = !empty($sResult) ? $sResult[0]['price'] : 0;

//update totals for display purposes
if(!empty($order['baskets'])){
	foreach($order['baskets'] as $item){
		$q = $item['quantity']*1;
		$p = $item['price']*1;
		$lineTotal = number_format($p*$q, 2, '.', '');
		$discount = $lineTotal*($item['discount']/100);
		$lineTotal = number_format($lineTotal-$discount,2, '.', '');
		$qtyTotal += $item['quantity'];
		$itemTotal += $lineTotal;
		$discTotal += $discount;	
	}
}
$discTotal = number_format($discTotal,2, '.', '');
$itemTotal = number_format($itemTotal,2, '.', '');
$orderTotal = number_format($itemTotal + $shipTotal,2, '.', '');

//if an invalid offer code entered
$invalidOffer = ($discTotal == 0 && $order['offer_code'] && !$forceShippingValid);

if($formtype == 'summary'){	
	//if in edit mode enable cart fields
	$disableCart = ($editset == 'cart') ? false : true; 
	//make address fields editable
	$disableAddress = $editset == 'add' ? false: true ;
	//always display delivery options on summary
	$showDelOptions = true;
	//info for secure trading form
	$stSession = $order['order_header']['ordernumber'];
	$stEmail = $order['order_header']['email'];
	$stPhone = $order['order_header']['phone'];
	$stName = $order['order_header']['firstname'].' '.$order['order_header']['lastname'];
	$stAddress = $order['order_header']['address1'].','.$order['order_header']['address2'];
	$stTown = $order['order_header']['city'];
	$stCounty = $order['order_header']['state'];
	$stPostcode = $order['order_header']['zipcode'];
	$stCountry = $order['order_header']['country'];
	$stAmount = $orderTotal*100;
	$stOrderref = $order['order_header']['niceordernum'];
	$stToken = $order['order_header']['token'];
}

//page titles
$pageTitle = 'Ancestry Shopping Basket';

//save the session
$_SESSION['shop'] = $order;



//set currency symbol (these will be dynamic depending on future dev)
$curSymbol = "&pound;";
//any flash alerts?
$_SESSION['flash'] = $alertFlash;

//if nothing in basket on summary form, redirect
if(empty($order['baskets']) && $formtype == 'summary'){
	header('Location:basket.php');
	exit();
}
//empty session flash
$_SESSION['flash'] = '';
//wrap alert flash with div for display
$alertFlash = $alertFlash ? "<div class=\"alert_flash\">$alertFlash</div>" : '';



?>