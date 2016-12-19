<?php
require_once('include/edgeward/global_functions.inc.php');
//clean POST data
//$post = cleanData($_POST,array('token','delivery_method'));
//IL change
if(isset($_POST['token']))
	$post['token']= form_clean($_POST['token'], 32);
if(isset($_POST['delivery_method']))
	$post['delivery_method']= form_clean($_POST['delivery_method'], 3);
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

//update shipvia
$order['delivery_method'] = $post['delivery_method'];
$order['order_header']['shipvia'] = $order['delivery_method'];

//save the session
$_SESSION['shop'] = $order;
//save order to db
saveOrder($order);
//go to summary page
header('Location:order_summary.php');

?>