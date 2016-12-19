<?php
require_once('include/edgeward/global_functions.inc.php');

//which layout set?
$layout = layoutSet(2); //use only layout 2
//page suffix (only put new layout onto GB forms)
$pageSuffix = isset($pageSuffix) ? $pageSuffix : '';
if(!$pageSuffix) $pageSuffix = $layout['suffix'];

//clean POST data
$post = cleanData($_POST,array('token'));
$startPage = "index{$pageSuffix}.php";

//check the security tokens match
if(!empty($post)){
	if( empty($post['token']) || $post['token'] != $_SESSION['token'] ){
		header("Location:cert_details{$pageSuffix}.php");
		exit();
	}
}

//gen new token
/*if(!isAjaxRequest())*/ $token = setNewToken();


//get the order array and get/set the order number
$order = isset($_SESSION['cert']) ? $_SESSION['cert'] : array();
if(empty($order)){
	header('Location:'.$startPage);
	exit;
}
//get any validation errors
$invalidFields = isset($_SESSION['inv']) ? $_SESSION['inv'] : array();

$_SESSION['inv'] = array();
//if no crucial data, redirect

if(!$order['recordType'] || !$order['formType']){
	header('Location:'.$startPage);
	exit;
}

//set certificate type (b,d,m)
$recordType = $order['recordType'];
//set the cert table name
$order['cert_table'] = strtolower($certTypes[$order['recordType']]).'_certificates';
//store the form type (search or shop)
$formType = $order['formType'];

//get the shipping options for the selected country
$c = $order['country'];
if($c){
	$shipData = getShippingInfo($c);
} else {
	header('Location:'.$startPage);
	exit;
}

//set the scan and send price for the country
//set static
switch($order['country']){
	case '073': $scanPrice = '2.50'; //uk
		break;
	default: $scanPrice = '8.00';
}

//if delivery method not yet set, check first option
if(!isset($order['delivery_method'])) $order['delivery_method'] = $shipData[0]['code'];

//update the session
$_SESSION['cert'] = $order;
//currency symbol
$curSymbol = $order['currency_symbol'];

//show extra birth fields if applicable
$birthExtra = $order['birth_extra'];
//page title
$origin = $order['formType'] == 'search' ? ' FS' : '';
$countrySuffix = $order['page_suffix'] ? ' '.strtoupper(str_replace('_','',$order['page_suffix'])) : '';
$currentPage = curPageName();
$page = $order['page_suffix'] ? str_replace($order['page_suffix'],'',$currentPage) : $currentPage ;
$pageName = '';
switch($page){
	case 'confirm_year.php':$pageName = 'Confirm Year'; 
	break;
	case 'cert_details.php':$pageName = 'Order Details'; 
	break;
	case 'delivery.php':$pageName = 'Delivery Address'; 
	break;
	case 'summary.php':$pageName = 'Order Summary'; 
	break;	
}
$omniture_pageTitle = "{$certTypes[$order['recordType']]} Certificate {$pageName}{$origin}{$countrySuffix}";
$pageTitle = "{$certTypes[$order['recordType']]} Certificate {$pageName}";
//make the particulars editable (if not on summary)
$disableParticulars = false;
//make the gro editable (if from shop)
$disableGro = false;
//show GRO help
$groHelp = true;
//enable delivery options
$disableDel = false;
//any flash alerts?
$alertFlash = $_SESSION['flash'];
unset($_SESSION['flash']);
//wrap alert flash with div for display
$alertFlash = $alertFlash ? "<div class=\"alert_flash\">$alertFlash</div>" : '';

//country data for jQuery to pick up for order totals etc
$extraPrice = array();
$country_data = "$curSymbol|";
$countryShip = "";
$y = 0;
foreach($shipData as $s){
	$shipData[$y]['price'] = number_format($s['price'],2);
	$shipData[$y]['extra_copy_price'] = number_format($s['extra_copy_price'],2);
	//set extra copy price for user notes
	if(stripos($s['description'],'Standard') !== false && !$extraPriceStd){
		$extraPriceStd = $order['currency_symbol'] . number_format($s['extra_copy_price'],2);
	} elseif(stripos($s['description'],'Express') !== false  && !$extraPriceExp){
		$extraPriceExp = $order['currency_symbol'] . number_format($s['extra_copy_price'],2);
	}
	$ecp = $shipData[$y]['extra_copy_price'];
	$countryShip .= $countryShip ? ",{$s['code']}-{$ecp}" : "{$s['code']}-{$ecp}";
	$extraPrice[$s['code']] = $ecp;
	$y++;
}
$country_data .= $countryShip;

//order total and discount total
$q = $order[$order['cert_table']]['copies']*1;
$scan = $order[$order['cert_table']]['scan_and_send']*1;
$del = $order['orders']['delivery_method'];
$certMulti = $extraPrice[$del];
$certCost = $q >1 ? ($q-1)*$certMulti : 0;
$shipCost = 0;
foreach($shipData as $s){
	if($s['code'] == $del){
		$shipCost = $s['price'];
		break;
	}
}
$discTotal = 0;
if($order['orders']['discount']) $discTotal = number_format((($shipCost + $certCost)/100) * $order['orders']['discount'],2);

$orderTotal = number_format( ($shipCost + $certCost) - $discTotal + $scan,2, '.', '');

//summary page only stuff
if($formset == 'summary' || $currentPage == "cert_terms.php"){
	//info for secure trading form
	$stSession = session_id();
	$stEmail = $order['orders']['email'];
	$stPhone = $order['orders']['phone'];
	$stName = $order['billing_address']['first_name'].' '.$order['billing_address']['surname'];
	$stAddress = $order['billing_address']['line_1'].','.$order['billing_address']['line_2'];
	$stTown = $order['billing_address']['city'];
	$stCounty = $order['billing_address']['county'];
	$stPostcode = $order['billing_address']['postcode'];
	$stCountry = $order['billing_address']['country'];
	$stAmount = $orderTotal*100;
	$stOrderref = strtoupper($certTypes[$order['recordType']])."_CERTIFICATE_".$order['orders']['GRO_orders_id'];
	$stToken = $token;
	$stCurrency = $order['currency_code'];
    saveToken($token,$order['orders']['GRO_orders_id']);
	//SUMMARY FORM STUFF
	//which form area are we editing?
	$editset = $_SESSION['editset'];
	//set the form action
	$formAction = $editset =='add' ? 'upd_address.php' : 'upd_cert.php';
	//make the particulars editable (if from shop)
	$disableParticulars = $editset == 'par' ? false: true ;
	//make the particulars editable (if from shop)
	$disableGro = $editset == 'gro'? false: true ;
	//make address fields editable
	$disableAddress = $editset == 'add' ? false: true ;
	//make address fields editable
	$disableDel = $editset == 'del' ? false: true ;
	//secure trading security hash
	$st_sitesecurity = securityHash(SECURE_TRADING_ACCOUNT,SECURE_TRADING_PASSWORD,$stCurrency,$stAmount);
	//save order to db
	//if(empty($invalidFields) && !$alertFlash) saveOrder($order);
}

//HELP TEXT
$partsHelptext = "The particulars of the person on the {$certTypes[$recordType]} Certificate are required for us to process your order.";
$groHelptext = "This information allows us to distinguish between different entries with the same name, and make sure that you receive the correct certificate. You can find these details by searching our birth, marriage and death indexes.<br><br>Our most common question relates to what period is covered by what quarter so please see below for an explanation.<br><br>Quarter 1 = Jan, Feb & March<br>Quarter 2 = Apr, May & Jun<br>Quarter 3 = Jul, Aug & Sept<br>Quarter 4 = Oct, Nov & Dec.";
$qtrHelptext= "Quarter 1 = Jan, Feb & March. Quarter 2 = Apr, May & Jun. Quarter 3 = Jul, Aug & Sept. Quarter 4 = Oct, Nov & Dec.";
$countryHelptext= "Please select which country you require the certificate to be delivered to by clicking the relevant flag.";
$delHelptext = "This is the address that you'd like the certificate delivered to.";
$billHelptext = "Complete this section if your billing address is different from your delivery address.";


// Country specific stuff (Delivery Service Notes etc)
$firstnameLabel = 'Forename(s)';
$lastnameLabel = 'Surname';
//$extraPriceStd = $curSymbol . $shipData[0]['extra_copy_price'];
//$extraPriceExp = $curSymbol . $shipData[1]['extra_copy_price'];
$serviceNotes = "<p class=\"note\">Additional copies will be charged at {$extraPriceStd} per copy for the standard service and {$extraPriceExp} per copy for the express service.</p>";
$qtyHelptext = "Enter how many copies of the certificate you would like to order. Additional copies will be charged at {$extraPriceStd} per copy for the standard service and {$extraPriceExp} per copy for the express service.";
$scanHelptext = "Receive the certificate information quickly with our Digital Copy service. Select this option to receive a digital copy of your certificate by email. Receive the Digital Copy email within 8-10 working days for Standard and within 4 working days for Express delivery. The original certificate will be sent via standard postal service within 24 hours of the digital version being sent. ";
$discountHelptext = "Enter a valid offer code to receive a promotional discount. Cost of digital copy service is not discounted.";
switch ($order['country']) {
    case '012': //australia
		$pageSuffix = "_aus";
        break;
    case '001': //US
		$pageSuffix = "_us";        
        $firstnameLabel = 'First Name(s)';
		$lastnameLabel = 'Last Name';
		break;
	case '034': //Canada
		$pageSuffix = "_ca";
       $firstnameLabel = 'First Name(s)';
		$lastnameLabel = 'Last Name';
		break;
    default: //UK
		$pageSuffix = '';
        $scanHelptext = "Receive the certificate information quickly with our Digital Copy service. Select this option to receive a digital copy of your certificate by email. Receive the Digital Copy email within 8-10 working days for Standard and within 4 working days for Express delivery. The original certificate will be sent via standard postal service within 24 hours of the digital version being sent.";
        break;
}

?>