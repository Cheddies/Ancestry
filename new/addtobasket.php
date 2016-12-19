<?php
require_once('include/edgeward/global_functions.inc.php');
//clean GET
$get = cleanData($_GET,array('code'));

//IL change
//"-" removed from list of characters to remove as products have this in code
$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");	
if(isset($_GET['code']))
	$itemCode =  form_clean($_GET['code'], 20, $badchars);
//end of IL change
//$itemCode = $get['code'];
if(!$itemCode){
	//echo 'NO CODE GIVEN';
	header('Location:'.$_SERVER['HTTP_REFERER']);
	exit();
}
//create array with session values
$order = isset($_SESSION['shop']) ? $_SESSION['shop'] : array();
if(isset($_GET['qty'])){
	$q = form_clean($_GET['qty'], 6, $badchars);
} else {
	$q = 1;
}
if(!empty($order['baskets'][$itemCode])){
	$q = $order['baskets'][$itemCode]['quantity'] +$q;
}
$prod = getProductInfo($itemCode);
if(empty($prod)){	
	//echo 'NO INFO FOUND';
	header('Location:'.$_SERVER['HTTP_REFERER']);
	exit();
} else {	
	if($prod[0]['units'] < $q){ //not enough stock
		$q = $prod[0]['units'];
		$pName = $prod[0]['name'];
		$_SESSION['flash'] = "<p>The available quantity of \"$pName\" currently in stock is $q.</p>";
	}
	if($q >0){
		$order['baskets'][$itemCode]['itemcode'] = $prod[0]['itemcode'];
		$order['baskets'][$itemCode]['price'] = $prod[0]['price'];
		$order['baskets'][$itemCode]['name'] = $prod[0]['name'];
		$order['baskets'][$itemCode]['quantity'] = $q;
		$order['baskets'][$itemCode]['sessionid'] = session_id();
		
		//check offer code, if given
		if($order['offer_code']){
			$order['baskets'][$itemCode]['discount'] = getDiscount($order['offer_code'],$order['baskets'][$itemCode]['itemcode']);
		} else {
			$order['baskets'][$itemCode]['discount'] = 0;
		}
	}
	//save the session
	$_SESSION['shop'] = $order;
	//save order to db
	saveOrder($order);
	//go to basket
	header('Location:basket.php');
}

?>