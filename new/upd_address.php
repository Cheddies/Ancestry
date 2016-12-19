<?php
require_once('include/edgeward/global_functions.inc.php');
//clean POST data
//$post = cleanData($_POST,array('token','formset','formtype','btn','isAjax','email','tel','forename','surname','address_1','address_2','town','county','postcode','country','billing_different','b_forename','b_surname','b_address_1','b_address_2','b_town','b_postcode','b_country','noemail','norent'));
//IL change
//for use in email clean
$badchars=array("\n","\r","#","$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");

if(isset($_POST['token']))
	$post['token']= form_clean($_POST['token'], 32);
if(isset($_POST['formset']))
	$post['formset'] = form_clean($_POST['formset'],20);
if(isset($_POST['formtype']))
	$post['formtype'] =form_clean($_POST['formtype'],20);
if(isset($_POST['btn']))
	$post['btn'] = form_clean($_POST['btn'],20);
if(isset($_POST['isAjax']))
	$post['isAjax'] = form_clean($_POST['isAjax'],20);
if(isset($_POST['email']))
	$post['email'] = form_clean($_POST['email'],50,$badchars);
if(isset($_POST['tel']))
	$post['tel'] = form_clean($_POST['tel'],14);
if(isset($_POST['forename']))
	$post['forename'] = form_clean($_POST['forename'],15);
if(isset($_POST['surname']))
	$post['surname'] = form_clean($_POST['surname'],20);
if(isset($_POST['address_1']))
	$post['address_1'] = form_clean($_POST['address_1'],40);
if(isset($_POST['address_2']))
	$post['address_2'] = form_clean($_POST['address_2'],40); 
if(isset($_POST['town']))
	$post['town'] = form_clean($_POST['town'],20);
//not required
//$post['county'] = form_clean($_POST['county'],;
if(isset($_POST['postcode']))
	$post['postcode'] = form_clean($_POST['postcode'],10);
if(isset($_POST['country']))
	$post['country'] = form_clean($_POST['country'],3);
if(isset($_POST['billing_different']))
	$post['billing_different'] = form_clean($_POST['billing_different'],1);
if(isset($_POST['b_forename']))
	$post['b_forename'] = form_clean($_POST['b_forename'],15);
if(isset($_POST['b_surname']))
	$post['b_surname'] = form_clean($_POST['b_surname'],20);
if(isset($_POST['b_address_1']))
	$post['b_address_1'] = form_clean($_POST['b_address_1'],40);
if(isset($_POST['b_address_2']))
	$post['b_address_2'] = form_clean($_POST['b_address_2'],40);
if(isset($_POST['b_town']))
	$post['b_town'] = form_clean($_POST['b_town'],20);
if(isset($_POST['b_postcode']))
	$post['b_postcode'] = form_clean($_POST['b_postcode'],10);
if(isset($_POST['b_country']))
	$post['b_country'] = form_clean($_POST['b_country'],3);
if(isset($_POST['noemail']))
	$post['noemail'] = form_clean($_POST['noemail'],1);
if(isset($_POST['norent']))
	$post['norent'] = form_clean($_POST['norent'],1);
//end of IL change

//check the security tokens match
if(!empty($post)){
	if( (empty($post['token']) || $post['token'] != $_SESSION['token']) || !isset($_SESSION['shop']) ){
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
//map order variables
//order number from session id
$order['order_header']['ordernumber'] = session_id();
//token, change this for production
$order['order_header']['token'] = $token;
//order date
$order['order_header']['order_date'] = date('Y-m-d');
//update shipvia
$order['order_header']['shipvia'] = $order['delivery_method'];
//update offer code
$order['order_header']['source_key'] = $order['valid_offer_code'];
//set the addresses
if($post['formset'] == 'address'){
	//order number from session id
	$order['order_header']['ordernumber'] = session_id();
	//email and phone
	$order['order_header']['email'] = $post['email'];
	$order['order_header']['phone'] = $post['tel'];
	//address data
	$order['order_header']['sfirstname'] = $post['forename'];
	$order['order_header']['slastname'] = $post['surname'];
	$order['order_header']['saddress1'] = $post['address_1'];
	$order['order_header']['saddress2'] = $post['address_2'];
	$order['order_header']['scity'] = $post['town'];
	$order['order_header']['sstate'] = $post['county'];
	$order['order_header']['szipcode'] = $post['postcode'];
	$order['order_header']['scountry'] = $post['country'];
	$order['billing_different'] = $post['billing_different'];
	//billing address data
	if($order['billing_different'] === '1'){
		$order['order_header']['firstname'] = $post['b_forename'];
		$order['order_header']['lastname'] = $post['b_surname'];
		$order['order_header']['address1'] = $post['b_address_1'];
		$order['order_header']['address2'] = $post['b_address_2'];
		$order['order_header']['city'] = $post['b_town'];
		$order['order_header']['state'] = $post['b_county'];
		$order['order_header']['zipcode'] = $post['b_postcode'];
		$order['order_header']['country'] = $post['b_country'];
	} else {
		$order['order_header']['firstname'] = $order['order_header']['sfirstname'];
		$order['order_header']['lastname'] = $order['order_header']['slastname'];
		$order['order_header']['address1'] = $order['order_header']['saddress1'];
		$order['order_header']['address2'] = $order['order_header']['saddress2'];
		$order['order_header']['city'] = $order['order_header']['scity'];
		$order['order_header']['state'] = $order['order_header']['sstate'];
		$order['order_header']['zipcode'] = $order['order_header']['szipcode'];
		$order['order_header']['country'] = $order['order_header']['scountry'];
	}
	//no email checkboxes
	if($post['isAjax'] || $post['btn'] == 'Save'){
		$order['order_header']['noemail'] = $post['noemail'];
		$order['order_header']['norent'] = $post['norent'];
	} else {
		$order['order_header']['noemail'] = $post['noemail'] ? 0 : 1;
		$order['order_header']['norent'] = $post['norent'] ? 0 : 1;
	}
}

//validate fields
if(!empty($post)) $invalidFields = checkValidation($post);
$_SESSION['inv'] = $invalidFields;
if(!empty($invalidFields)) $alertFlash = "<p>Some of the fields are invalid.</p>";

//get countries
$countries = getCountries();
$countryCount = count($countries);
//if there is more than one country to pick from:
if($countryCount > 1){
	//how many shipping options are there for our country?
	$c = $order['order_header']['scountry'];
	openConn();
	$sql = sprintf("SELECT s.code AS code, s.price AS price, s.description AS description,s.notes AS notes FROM tbl_shipping AS s, tbl_country_shipping AS c WHERE s.code = c.shipping AND c.country = '%s';",
				   mysql_real_escape_string($c));
	$shipData = sqlQuery($sql);
	mysql_close();
	$shipOptionCount = count($shipData);
	if($shipOptionCount >0){
		//if shipping options
		$order['delivery_method'] = $shipData[0]['code'];
		$order['order_header']['shipvia'] = $order['delivery_method'];
	} else {
		$order['delivery_method'] = 1;
		$order['order_header']['shipvia'] = 1;
	}
}

//save the session
$_SESSION['shop'] = $order;
//save order to db
saveOrder($order);
//get any alerts generated
if($alertFlash) $_SESSION['flash'] = $alertFlash;
//remove edit flag
unset($_SESSION['editset']);

//which page should the user be directed to?
$redirPage = $post['formtype'] == 'summary' ? 'order_summary.php': 'delivery.php';
$nextPage = $post['btn'] == 'Continue' && !$alertFlash ? 'order_summary.php' : $redirPage;
//if there is more than one shipping option, redirect to shipping page
if($shipOptionCount >1) $nextPage = "shipping.php";
saveToken($token,$order['order_header']['ordernumber']);

header('Location:'.$nextPage);

?>