<?php
require_once('include/edgeward/global_functions.inc.php');
//clean POST data
//IL change 
//$safeKeys = array('offer_code','token','delivery_method','isAjax','formtype','btn');
$safeKeys=array();
$i=0;
if(!isset($_SESSION['shop']['baskets'])){
	header('Location:basket.php');
	exit();
}
foreach($_SESSION['shop']['baskets'] as $item){
	$safeKeys[] = 'lnqty_'.$item['itemcode'];
	$safeKeys[] = 'lnitem_'.$i;
	$i++;
}
$post = cleanData($_POST,$safeKeys);

/*IL change*/
if(isset($_POST['offer_code']))
	$post['offer_code']= form_clean($_POST['offer_code'], 20);
if(isset($_POST['token']))
	$post['token']= form_clean($_POST['token'], 32);
if(isset($_POST['delivery_method']))	
	$post['delivery_method']= form_clean($_POST['delivery_method'], 3);
if(isset($_POST['isAjax']))
	$post['isAjax']= form_clean($_POST['isAjax'], 10);
if(isset($_POST['formtype']))
	$post['formtype']= form_clean($_POST['formtype'], 50);
if(isset($_POST['btn']))
	$post['btn']= form_clean($_POST['btn'], 50);
/* End of IL change */ 

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

$response = "";
$alertFlash = "";
//get the session data
$order = $_SESSION['shop'];
//discount code
$order['offer_code'] = $post['offer_code'];
//delivery option
$order['delivery_method'] = $post['delivery_method'];
//add up the discount
$discountTotal = 0;
//update line item quantities from POST
foreach($post as $k => $v){
	//if field is line item qty, update line item
	if(strpos($k,'lnqty_')!==false){
		$id = substr($k,6);
		//if the qty is a positive integer
		if(ctype_digit($v) && ($v*1) >=1){			
			//check the stock levels
			$pInfo = getProductInfo($id);
			if(!empty($pInfo)){
				if($pInfo[0]['units'] < $v){
					//not enough stock
					$v = $pInfo[0]['units'];
					$pName = $pInfo[0]['name'];
					$alertFlash .= "<p>The available quantity of \"$pName\" currently in stock is $v.</p>";
				}
			}
			$order['baskets'][$id]['quantity'] = $v;
			$response .= $response ? '&'.$id.'-quantity='.$v : $id.'-quantity='.$v;
		} else {
			$alertFlash .= "<p>Item quantity must be a whole number greater than 0.</p>";
			$response .= $response ? '&'.$id.'-quantity='.$order['baskets'][$id]['quantity'] : $id.'-quantity='.$order['baskets'][$id]['quantity'];
		}
	}
	//get the other line items info	
	if(strpos($k,'lnitem_')!==false){
		$lineArray = explode('|',$v); //code,qty,price,name,discount
		$order['baskets'][$lineArray[0]]['sessionid'] = session_id();
		$order['baskets'][$lineArray[0]]['itemcode'] = cleanString($lineArray[0]);
		$order['baskets'][$lineArray[0]]['name'] = cleanString($lineArray[3]);
		$order['baskets'][$lineArray[0]]['price'] = cleanString($lineArray[2]);
		$order['baskets'][$lineArray[0]]['quantity'] = $order['baskets'][$lineArray[0]]['quantity']? $order['baskets'][$lineArray[0]]['quantity'] : cleanString($lineArray[1]);
		$order['baskets'][$lineArray[0]]['discount'] = 0;
		//check offer code, if given
		$order['baskets'][$lineArray[0]]['discount'] = getDiscount($order['offer_code'],cleanString($lineArray[0]));
		$discountTotal += $order['baskets'][$lineArray[0]]['discount'];
	}
}

/**********************************************************
Goes through same process as process_cart.inc.php
to check which shipping methods should be avaliable
whether a shipping method needs to be forced based on discount code
or whether the currently selected delivery method is valid
This is because a user can change the code and leave the delivery method the same, which can then make their current selection invalid
/***********************************************************/
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


/**************************************************************/


//if order code is valid, store it
$order['valid_offer_code'] = (!$discountTotal && $order['offer_code']) ? "" : $order['offer_code'];

//or if the shipping methiod is being forced by a discount code
if($forceShippingValid){
	$order['valid_offer_code'] = $order['offer_code'];
}
	
//if summary page sent the data, update order header
if($post['formtype'] == 'summary'){
	$order['order_header']['shipvia'] = $order['delivery_method'];
	$order['order_header']['source_key'] = $order['valid_offer_code'];
}

//validate fields
if(!empty($post)) $invalidFields = checkValidation($post);
$_SESSION['inv'] = $invalidFields;
if(!empty($invalidFields)) $alertFlash = "<p>Some of the fields are invalid.</p>";

//save the session
$_SESSION['shop'] = $order;
//save order to db
saveOrder($order);

//get any alerts generated
if($alertFlash){
	$_SESSION['flash'] = $alertFlash;
	$response .= $response ? '&alert='.$alertFlash : 'alert='.$alertFlash;
}
//remove edit flag
unset($_SESSION['editset']);
//which page should the user be directed to?
$redirPage = $post['formtype'] == 'summary' ? 'order_summary.php': 'basket.php';
$nextPage = $post['btn'] == 'Continue' && !$alertFlash ? 'delivery.php' : $redirPage;

if($post['isAjax']){
	saveToken($token,$order['order_header']['ordernumber']);
	echo $response."&token={$token}&";
} else { //not an ajax request
	header('Location:'.$nextPage);
}

?>

