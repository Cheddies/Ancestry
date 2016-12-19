<?php
require_once('include/edgeward/global_functions.inc.php');
//clean POST data
$safeFields =array('token','formset','formtype','btn','isAjax','email','tel','title','forename','surname','address_1','address_2',
	'town','county','postcode','country','billing_different','b_title','b_forename','b_surname','b_address_1','b_address_2','b_town',
	'b_postcode','b_county','b_country','noemail','norent','b_street_name','street_name','house_no','b_house_no');
$post = cleanData($_POST,$safeFields);

//set error reporting
if($post['isAjax']) error_reporting(E_ERROR | E_PARSE);

$alertFlash = '';

//create array with session values
$order = isset($_SESSION['cert']) ? $_SESSION['cert'] : array();
if(empty($order)){
	header('Location:index.php');
	exit;
} 
//set page suffix
$pageSuffix = $order['page_suffix'];
$nextPage = $post['btn'] == 'Save' ? "summary{$pageSuffix}.php" : "delivery{$pageSuffix}.php";
//check the security tokens match
if(!empty($post)){
	if( empty($post['token']) || $post['token'] != $_SESSION['token'] ){
		$_SESSION['flash'] = 'There was a problem with the security tokens, please retry';
		header("Location:{$nextPage}");
		exit();
	}
}
//generate new token
$token = setNewToken();


$response = "";
//set the addresses
//email and phone
$order['orders']['email'] = $post['email'];
$order['orders']['phone'] = $post['tel'];
//address data
$order['address']['first_name'] = $post['forename'];
$order['address']['surname'] = $post['surname'];
$order['add_house_no'] = $post['house_no'];
$order['add_street_name'] = $post['street_name'];
$order['address']['line_1'] = $post['address_1'];
if($order['add_house_no'] && $order['add_street_name']){
	$order['address']['line_1'] = $order['add_house_no'] . ' ' . $order['add_street_name'];
}
$order['address']['line_2'] = $post['address_2'];
$order['address']['city'] = $post['town'];
$order['address']['county'] = $post['county'];
$order['address']['postcode'] = $post['postcode'];
$order['address']['country'] = $post['country'];
$order['address']['title'] = $post['title'];
$order['billing_different'] = $post['billing_different'];

//set address type
$order['address']['type'] = 'D';
//set address type
$order['billing_address']['type'] = 'B';
//billing address data
if($order['billing_different'] === '1'){
	$order['billing_address']['first_name'] = $post['b_forename'];
	$order['billing_address']['surname'] = $post['b_surname'];	
	$order['add_b_house_no'] = $post['b_house_no'];
	$order['add_b_street_name'] = $post['b_street_name'];
	$order['billing_address']['line_1'] = $post['b_address_1'];
	if($order['add_b_house_no'] && $order['add_b_street_name']){
		$order['billing_address']['line_1'] = $order['add_b_house_no'] . ' ' . $order['add_b_street_name'];
	}
	$order['billing_address']['line_2'] = $post['b_address_2'];
	$order['billing_address']['city'] = $post['b_town'];
	$order['billing_address']['county'] = $post['b_county'];
	$order['billing_address']['postcode'] = $post['b_postcode'];
	$order['billing_address']['country'] = $post['b_country'];
	$order['billing_address']['title'] = $post['b_title'];
} else {
	$order['billing_address']['first_name'] = $order['address']['first_name'];
	$order['billing_address']['surname'] = $order['address']['surname'];
	$order['add_b_house_no'] = $post['house_no'];
	$order['add_b_street_name'] = $post['street_name'];
	$order['billing_address']['line_1'] = $order['address']['line_1'];
	$order['billing_address']['line_2'] = $order['address']['line_2'];
	$order['billing_address']['city'] = $order['address']['city'];
	$order['billing_address']['county'] = $order['address']['county'];
	$order['billing_address']['postcode'] = $order['address']['postcode'];
	$order['billing_address']['country'] = $order['address']['country'];
	$order['billing_address']['title'] = $order['address']['title'];
	
	//Change to set the post variables the same as well
	//else validation fails for postcode field when blank, even though this field is never used
	$post['b_forename'] = $post['forename'];
	$post['b_surname'] = $post['surname'];
	if($post['house_no']) $post['b_house_no'] = $post['house_no'];
	if($post['street_name']) $post['b_street_name'] = $post['street_name'];
	$post['b_address_1'] = $post['address_1'];
	$post['b_address_2'] = $post['address_2'];
	$post['b_town'] = $post['town'];
	$post['b_county'] = $post['county'];
	$post['b_postcode'] = $post['postcode'];
	$post['b_country'] = $post['country'];
	$post['b_title'] = $post['title'];
}
//no email checkboxes
if($post['isAjax']){
	$order['orders']['noemail'] = $post['noemail'];
	$order['orders']['norent'] = $post['norent'];
} else {
	$order['orders']['noemail'] = $post['noemail'] ? 0 : 1;
	$order['orders']['norent'] = $post['norent'] ? 0 : 1;
}

//save the session
//$_SESSION['cert'] = $order;


//validate fields
if(!empty($post)) $invalidFields = checkValidation($post);
$_SESSION['inv'] = $invalidFields;

$nextPage = "summary{$pageSuffix}.php";
//if validation error, redirect back
if(!empty($invalidFields) || $alertFlash){
	$nextPage = "delivery{$pageSuffix}.php";
	$_SESSION['flash'] = $alertFlash;
}

//moved to after call to checkValidation
//else invalidFields is allways 1
//save order to db
$saved = false;
if(empty($invalidFields) && !$alertFlash){
	$saved = saveOrder($order);
} 

$response .= $saved ? 'saved=1&' : 'saved=0&';

//save the session
$_SESSION['cert'] = $order;

//remove edit flag
unset($_SESSION['editset']);

if($post['isAjax']){
    saveToken($token,$order['orders']['GRO_orders_id']);
	echo $response."token={$token}&";
} else { //not an ajax request
	header('Location:'.$nextPage);
}
?>