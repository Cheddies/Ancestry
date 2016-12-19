<?php
require_once('include/edgeward/global_functions.inc.php');
//clean POST data
$post = cleanData($_POST,array('token','delivery_method'));
//check the security tokens match
if(!empty($post)){
	if( empty($post['token']) || $post['token'] != $_SESSION['token'] ){
		header('Location:index.php');
		exit();
	}
}
// Unset the token, so that it cannot be used again
unset($_SESSION['token']);
//gen new token
$token = md5(uniqid(rand(), true));
$_SESSION['token'] = $token;
//create array with session values
$order = isset($_SESSION['cert']) ? $_SESSION['cert'] : array();
if(empty($order)) header('Location:index.php');
//delivery method
$order['orders']['delivery_method'] = $post['delivery_method'];
$order[$order['cert_table']]['price_per_copy'] = getPricePerCopy($order['orders']['delivery_method']);
//save the session
$_SESSION['cert'] = $order;
//save order to db
if(empty($invalidFields) && !$alertFlash) saveOrder($order);
//go to summary page
header('Location:summary.php');

?>