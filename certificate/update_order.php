<?php

//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors','On');
require_once('include/edgeward/global_functions.inc.php');

//clean POST data
$acceptedFields = array('isAjax','token','btn','no_of_certs','delivery_method','scan_and_send','GROI_year','GROI_quarter','GROI_district',
	'GROI_volume_number','GROI_page_number','GROI_reg_year','GROI_reg_month','birth_year','birth_surname','birth_forename','birth_place','marriage_year',
	'marriage_man_surname','marriage_man_forename','marriage_woman_surname','marriage_woman_forename','death_year','death_surname',
	'death_forename','death_date','death_age','GROI_reg_number','GROI_entry_number','GROI_district_number','user_country','discount_code','apply_discount',
	'record_type','email','tel','title','forename','surname','address_1','address_2',
	'town','county','postcode','country','billing_different','b_title','b_forename','b_surname','b_address_1','b_address_2','b_town',
	'b_postcode','b_county','b_country','noemail','norent','b_street_name','street_name','house_no','b_house_no'
	);
	
$post = cleanData($_POST,$acceptedFields);
//set error reporting
if($post['isAjax']) error_reporting(E_ERROR | E_PARSE);
//get the order array and get/set the order number
$order = isset($_SESSION['cert']) ? $_SESSION['cert'] : array();
if(empty($order)){
	if(isAjaxRequest()){
		echo 'alert=Empty order';
	} else {
		header('Location:index.php');
	}	
	exit;
}
//set page suffix
$pageSuffix = $order['page_suffix'];
$nextPage = "cert_terms.php";
$errorPage = "cert_details{$pageSuffix}.php";

//check the security tokens match
if(!empty($post)){
	if( empty($post['token']) || $post['token'] != $_SESSION['token'] ){		
		if(isAjaxRequest()){
			echo "alert=Invalid security token&token={$_SESSION['token']}";
		} else {
			header("Location: $errorPage");
		}
		exit();
	}
}

//generate new token
$token = setNewToken();
//set token and time for db
$order['orders']['token'] = $token;
$order['orders']['token_time'] = time();

$response = "";
$alertFlash = "";

//delivery method
if($post['delivery_method']){
	$order['orders']['delivery_method'] = $post['delivery_method'];
	$order[$order['cert_table']]['price_per_copy'] = getPricePerCopy($order['orders']['delivery_method']);
}

//order date
$order['orders']['order_date'] = $order['orders']['order_date'] ? $order['orders']['order_date'] : date("Y-m-d");

if(isset($order['order_number']) && $order['order_number']){
	//set master order number
	$order['orders']['order_number'] = $order['order_number'];
	//set order number for certificate_ordered table
	$order['certificate_ordered']['order_number'] = $order['orders']['order_number'];
} else {
	//set master order number
	$order['order_number'] = session_id();
	//cert table order number
	$order[$order['cert_table']]['order_number'] = $order['order_number'];
	//set GROI_known
	$order[$order['cert_table']]['GROI_known'] = 1;
}
if(!isset($order['recordType']) && isset($post['record_type'])) $order['recordType'] = $post['record_type'];
//set cert type  for certificate_ordered table
$order['certificate_ordered']['certificate_type'] = $tableCertTypes[$order['recordType']];
//number of certs required
if(!ctype_digit($post['no_of_certs']) || ($post['no_of_certs']*1) <1){	
	$alertFlash .= "<p>Certificate quantity must be a whole number greater than 0.</p>";
	$post['no_of_certs'] =1;
	$order[$order['cert_table']]['copies'] = $order[$order['cert_table']]['copies'] ? $order[$order['cert_table']]['copies'] : 1;
	$response .= "qty={$order[$order['cert_table']]['copies']}&";
} else {
	$order[$order['cert_table']]['copies'] = $post['no_of_certs'];
}
$order['no_of_certs'] = $order[$order['cert_table']]['copies'];
//scan and send
$order[$order['cert_table']]['scan_and_send'] = $post['scan_and_send'];
//discount code
$discountAmount = 0;
if($order['oc']){
	$discountCode = strtoupper($order['oc']); //from get_data.php
}
if(isset($post['discount_code'])){
	$discountCode = strtoupper($post['discount_code']); //from POST	
}
unset($order['invalid_offer']);
if($discountCode){
	$discountAmount = getDiscount($discountCode);
	if(!$discountAmount){
		//invalid code, no discount
		$order['invalid_offer'] = 1;
		$order['orders']['discount_code'] = '';
		$order['orders']['discount'] = 0;
		$response .= "inv_disc=1&";
	} else {
		//valid code
		$order['orders']['discount_code'] = $discountCode;
		$order['orders']['discount'] = $discountAmount;
		$response .= "disc_amount={$discountAmount}&";
	}
} else {
	$order['orders']['discount_code'] = '';
	$order['orders']['discount'] = 0;
}

//GRO info, inserting correct table name
$order[$order['cert_table']]['order_number'] = $order['orders']['order_number'];
$order[$order['cert_table']]['GRO_index_year'] = $post['GROI_year'];
$order[$order['cert_table']]['GRO_index_quarter'] = $post['GROI_quarter'];
$order[$order['cert_table']]['GRO_index_district'] = $post['GROI_district'];
$order[$order['cert_table']]['GRO_index_volume'] = $post['GROI_volume_number'];
$order[$order['cert_table']]['GRO_index_page'] = $post['GROI_page_number'];
$order[$order['cert_table']]['GRO_reg_no'] = $post['GROI_reg_number'];
$order[$order['cert_table']]['GRO_entry_no'] = $post['GROI_entry_number'];
$order[$order['cert_table']]['GRO_district_no'] = $post['GROI_district_number'];
$order['gro_reg_month'] = $post['GROI_reg_month'];
$order['gro_reg_year'] = $post['GROI_reg_year'];
if($order['gro_reg_year'] && $order['gro_reg_month']){
	$order[$order['cert_table']]['GRO_index_reg'] = $order['gro_reg_year'].'-'.$order['gro_reg_month'].'-01';
} else {
	$order[$order['cert_table']]['GRO_index_reg'] = "";
}
//birth cert info
if($order['recordType'] == 'b'){
	$order[$order['cert_table']]['birth_reg_year'] = $post['GROI_year'];
	$order[$order['cert_table']]['birth_surname'] = $post['birth_surname'];
	$order[$order['cert_table']]['forenames'] = $post['birth_forename'];
	$order[$order['cert_table']]['birth_place'] = $post['birth_place'];
}
//marriage cert info
if($order['recordType'] == 'm'){
	$order[$order['cert_table']]['registered_year'] = $post['GROI_year'];
	$order[$order['cert_table']]['mans_surname'] = $post['marriage_man_surname'];
	$order[$order['cert_table']]['mans_forenames'] = $post['marriage_man_forename'];
	$order[$order['cert_table']]['womans_surname'] = $post['marriage_woman_surname'];
	$order[$order['cert_table']]['womans_forenames'] = $post['marriage_woman_forename'];
	//one or other of names is required
	$manName = ($order[$order['cert_table']]['mans_surname'] && $order[$order['cert_table']]['mans_forenames']);
	$womanName = ($order[$order['cert_table']]['womans_surname'] && $order[$order['cert_table']]['womans_forenames']);
	$post['marriage_names'] = $manName || $womanName ? 1 : 0;
}
//death cert info
if($order['recordType'] == 'd'){
	//IL change - inline with lines 73 and 80
	//$order[$order['cert_table']]['registered_year'] = $post['death_year'];
	$order[$order['cert_table']]['registered_year'] = $post['GROI_year'];
	$order[$order['cert_table']]['surname_deceased'] = $post['death_surname'];
	$order[$order['cert_table']]['forenames_deceased'] = $post['death_forename'];
}

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
$order['orders']['noemail'] = $post['noemail'] ? 0 : 1;
$order['orders']['norent'] = $post['norent'] ? 0 : 1;


//validate fields
if(!empty($post)) $invalidFields = checkValidation($post);
$_SESSION['inv'] = $invalidFields;

//save all tables
if(empty($invalidFields) && !$alertFlash){
	//save order to db
	$saved = true;
	$i = 1;
	//save order to db
	$saved = saveOrder($order);
	$response .= $saved ? 'saved=1&' : 'saved=0&';
} else {
	$response .= 'saved=0&';
}

//update the session
$_SESSION['cert'] = $order;

if(!$saved) $alertFlash = 'There was a problem saving your order. Please try again.';

//if validation error, redirect back
if(!empty($invalidFields) || $alertFlash){
	$nextPage = $errorPage;
	$_SESSION['flash'] = $alertFlash;
}


//if ajax request, send response
if($post['isAjax'] || isAjaxRequest()){
    saveToken($token,$order['orders']['GRO_orders_id']);
	echo $response . "token={$token}&alert={$alertFlash}";
	exit;
} else { //not an ajax request
	if($post['btn'] == 'Apply') $nextPage = $errorPage; //appply discount btn used
	header('Location:'.$nextPage);
}
?>