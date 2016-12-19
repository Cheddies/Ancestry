<?php
require_once('include/edgeward/global_functions.inc.php');

//get new session id
if(isset($_SESSION['cert'])){
	session_regenerate_id(true);
	//$_SESSION['cert'] = array();
}


$acceptedFields = array('recordType',
						'yearReg',
						'monthReg',
						'qReg',
						'district',
						'volume',
						'page',
						'surname',
						'given',
						'by',
						'bd',
						'bm',
						'bp',
						'fatherS',
						'fatherG',
						'motherS',
						'motherG',
						'age',
						'formType',
						'country',
						'oc', //offer code
						'layout' //layout set
						);


//clean GET
$get = cleanData($_GET,$acceptedFields);
//create order array
$order = array();

//search or shop?
$order['formType'] = $get['formType'] == "shop" ? "shop" : "search";
$order['orders']['tracking'] = $order['formType']; //save the origin of the order to db for tracking purposes
if(isset($_COOKIE['bmdtrlan']) && $_COOKIE['bmdtrlan']){
	//save the landing page for tracking if applicable
	$order['orders']['tracking'] .= '|' . $_COOKIE['bmdtrlan'];
}

//validate fields
$invFields = checkValidation($get);
//which country?
switch ($get['country']) {
	case '5544':
    case '012': //australia
        $order['country'] = '012';
		$order['page_title'] = "Aus ";
		$nextPage = 'confirm_year.php';
		$order['currency_symbol'] = "AU$";
		$order['currency_code'] = "AUD";
		$layout = layoutSet(1);//layout is always 1
		$order['page_suffix'] = "_aus";
        break;
    case '0': //US
        $order['country'] = '001';
		$order['page_title'] = "US ";
		$nextPage = 'confirm_year.php';
		$order['currency_symbol'] = "US$";
		$order['currency_code'] = "USD";
		$layout = layoutSet(1);//layout is always 1
		$order['page_suffix'] = "_us";
        break;
	case '5543': //Canada
        $order['country'] = '034';
		$order['page_title'] = "Canada ";
		$nextPage = 'confirm_year.php';
		$order['currency_symbol'] = "CA$";
		$order['currency_code'] = "CAD";
		$layout = layoutSet(1);//layout is always 1
		$order['page_suffix'] = "_ca";
        break;
    default: //UK
        $order['country'] = '073';
		$order['page_title'] = "";
		$nextPage = 'confirm_year.php';
		$order['currency_symbol'] = "&pound;";
		$order['currency_code'] = "GBP";		
		$layout = layoutSet($get['layout']);//which layout set?
		$order['page_suffix'] = $layout['suffix'];
        break;
}
//set the billing country
$order['address']['country'] = $order['country'];
//discount code?
if($get['oc']){
	if(getDiscount($get['oc'])){
		$order['oc'] = $get['oc'];
	}
} 
//record type
$recType = strtolower($get['recordType']);
if($recType != "b" && $recType != "m" && $recType != "d"){
	header("Location:index{$order['page_suffix']}.php");
	exit();
} else {
	$order['recordType'] = $recType;
}

//set the cert table name
$order['cert_table'] = strtolower($certTypes[$order['recordType']]).'_certificates';
//get search data
if($order['formType'] == 'search'){
	//map GET data to data model
	if(!array_key_exists('yearReg', $invFields) && $get['yearReg']){
		$order[$order['cert_table']]['GRO_index_year'] =  $get['yearReg'];
		$order['GRO_year'] = $get['yearReg'];
		$nextPage = "cert_details{$order['page_suffix']}.php";
	} 
	if(!array_key_exists('qReg', $invFields) && $get['qReg']) $order[$order['cert_table']]['GRO_index_quarter'] = $get['qReg'];
	if(!array_key_exists('district', $invFields) && $get['district']) $order[$order['cert_table']]['GRO_index_district'] = $get['district'];
	if(!array_key_exists('volume', $invFields) && $get['volume']) $order[$order['cert_table']]['GRO_index_volume'] = $get['volume'];
	if(!array_key_exists('page', $invFields) && $get['page']) $order[$order['cert_table']]['GRO_index_page'] = $get['page'];
	if(!array_key_exists('monthReg', $invFields) && $get['monthReg']) $order['gro_reg_month'] = $get['monthReg'];//
	if(!array_key_exists('yearReg', $invFields) && $get['yearReg']){
		$order['gro_reg_year'] = $get['yearReg'];
		$order[$order['cert_table']]['GRO_index_year'] = $get['yearReg'];
	} 
	
	if($order['gro_reg_year'] && $order['gro_reg_month']) $order[$order['cert_table']]['GRO_index_reg'] = $order['gro_reg_year'].'-'.$order['gro_reg_month'].'-01';

	if($order['recordType'] == 'b'){
		if(!array_key_exists('surname', $invFields) && $get['surname']) $order[$order['cert_table']]['birth_surname']=$get['surname'];
		if(!array_key_exists('given', $invFields) && $get['given']) $order[$order['cert_table']]['forenames']=$get['given'];
	}
	if($order['recordType'] == 'd'){
		if(!array_key_exists('surname', $invFields) && $get['surname']) $order[$order['cert_table']]['surname_deceased']=$get['surname'];
		if(!array_key_exists('given', $invFields) && $get['given']) $order[$order['cert_table']]['forenames_deceased']=$get['given'];	
	}
	//criteria for displaying GRO form fields
	$order['viewFields'] = viewGroFields($order['recordType'],$order['GRO_year']);
}
//set qty
$order[$order['cert_table']]['copies'] = 1;
$order['no_of_certs'] = 1;
//set master order number
$order['order_number'] = session_id();
//cert table order number
$order[$order['cert_table']]['order_number'] = $order['order_number'];
//set GROI_known
$order[$order['cert_table']]['GROI_known'] = 1;
//save the session
$_SESSION['cert'] = $order;
//go to basket
header('Location:'.$nextPage);

?>