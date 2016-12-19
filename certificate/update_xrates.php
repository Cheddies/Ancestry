<?php
include('include/siteconstants.php');
include('include/edgeward/global_functions.inc.php');

$currencies = array('USD','GBP','NZD','AUD','CAD');
$acceptedFields = array_merge(array('pword','base'),$currencies);
$post = cleanData($_GET,$acceptedFields);

$password = 'D5YFn4gwWMU6';

// if password not set or wrong, exit
if(!isset($post['pword']) || $post['pword'] !== $password || !isset($post['base']) || !isset($post['GBP'])){
	exit;
}

// if base is not one of our currencies, exit
if(!in_array($post['base'],$currencies)){
	exit;
}
$base = $post['base']; //base currency, normally USD
$rates = array();
if($base != 'GBP'){
	//get the x rate of the base currency
	if(is_numeric($post['GBP'])){
		$rates[$post['base']] = round(1/$post['GBP'],6);
	} else {
		exit;
	}
}
// create array of rates based on GBP base
foreach($currencies as $c){
	if(is_numeric($post[$c]) && $c != 'GBP' && $c != $post['base']){
		$rates[$c] = round($post[$c] * $rates[$post['base']],6);
	}
}

//save rates to db
openConn();
$today = date('Y-m-d H:i:s');

$query = sprintf("INSERT INTO ancestry_dev.gro_tbl_currency_rates (id ,code ,rate ,updated) VALUES ");
$ins = '';
foreach($rates as $k => $v){
	if($ins) $ins .= ',';
	$ins .= sprintf("(NULL , '%s', '%s', '%s')",mysql_real_escape_string($k),mysql_real_escape_string($v),$today);
}
$query .= $ins . ';';	
$result = mysql_query($query);
//echo $query; //debug only
mysql_close();

echo $result;
?>