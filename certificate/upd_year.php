<?php
require_once('include/edgeward/global_functions.inc.php');

//clean POST data
$acceptedFields = array('token','GROI_year');
	
$post = cleanData($_POST,$acceptedFields);

//check the security tokens match
if(!empty($post)){
	if( empty($post['token']) || $post['token'] != $_SESSION['token'] ){
		header('Location:cert_details.php');
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
//get the order array and get/set the order number
$order = isset($_SESSION['cert']) ? $_SESSION['cert'] : array();
if(empty($order)) header('Location:index.php');

//get the year
$order['GRO_year'] = $post['GROI_year'];
//criteria for displaying GRO form fields
$order['viewFields'] = viewGroFields($order['recordType'],$order['GRO_year']);

$nextPage = "cert_details{$order['page_suffix']}.php";

//validate fields
if(!empty($post)) $invalidFields = checkValidation($post);
$_SESSION['inv'] = $invalidFields;
//if fields are invalid, next page will be previous
if(!empty($invalidFields) || $alertFlash){
	$nextPage = "confirm_year.php";
	$_SESSION['flash'] = $alertFlash;
}
//update the session
$_SESSION['cert'] = $order;

header('Location:'.$nextPage);

?>