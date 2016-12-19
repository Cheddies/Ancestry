<?php

require_once('include/commonfunctions.php');
require_once('include/siteconstants.php');

$prefix = "GRO_";
$tableKeys = array('orders'=>'GRO_orders_id','marriage_certificates'=>'GRO_marriage_certificates_id','birth_certificates'=>'GRO_birth_certificates_id','death_certificates'=>'GRO_death_certificates_id','address'=>'GRO_addresses_id','billing_address'=>'GRO_addresses_id','certificate_ordered'=>'order_number');

function saveOrder($order){
	//saves an order, based on a hash array, to the database
	$res = false;	
	global $prefix,$order;
	$saveOrder = array('address','billing_address','orders',$order['cert_table'],'certificate_ordered');
	openConn();
	//save table in a particular order
	foreach($saveOrder as $s){
		if(is_array($order[$s])){
			$res = saveToTable($s,$order[$s]);
			if(!$res) return false;	
		}
	}
	return $res; //return the order array
}

function saveToTable($tableId,$tableArray){
	
	global $tableKeys,$prefix,$order;
	//get table name
	$tableName = ($tableId == 'address' || $tableId == 'billing_address') ? $prefix.'addresses' : $prefix.$tableId;
	//get table info
	$infoQuery = sprintf("SHOW COLUMNS FROM %s;",
	mysql_real_escape_string($tableName));
	$result = mysql_query($infoQuery);
	
	if (!$result) {
		return false;
	}
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$tableInfo[$row['Field']] = $row;
		}
	}
	//loop thru the array, create sql string
	foreach($tableArray as $ok=>$ov){
		$isIntField = strpos($tableInfo[$ok]['Type'],'int')!==false;
		if($isIntField && !preg_match('^[0-9]*$^',$ov)) continue;
		if($tableInfo[$ok]['Null'] == 'YES' && !strlen(trim($ov))){	
			$val = 'null';
		} else {
			$val = $isIntField ? mysql_real_escape_string($ov) : "'".mysql_real_escape_string($ov)."'";
		}
		$values = $values ? $values.",".$val : $val ;
		$fields = $fields ? $fields.",".$ok : $ok ;
	}
	//update the table
	$query = sprintf("REPLACE INTO %s (%s) VALUES (%s);",
	mysql_real_escape_string($tableName),
	mysql_real_escape_string($fields),
	$values);			
	//execute the update/insert
	$result = mysql_query($query);
	if(!$result) return false;
	//if address data or cert data, get id from db and update session
	$lastId = mysql_insert_id();
	//update the order array with id fields from table
	if($lastId){
		if($tableId == 'address'){
			$order[$tableId][$tableKeys[$tableId]] = $lastId;	
			$order['orders']['shipping_address'] = $lastId;
		} elseif($tableId == 'billing_address') {
			$order[$tableId][$tableKeys[$tableId]] = $lastId;
			$order['orders']['billing_address'] = $lastId;
		} elseif(strpos($tableId,'certificates')!==false) { //if certificates table
			$order[$tableId][$tableKeys[$tableId]] = $lastId;
			$order['certificate_ordered']['certificate_id'] = $lastId;
		} elseif($tableId == 'orders') {
			$order['orders'][$tableKeys['orders']] = $lastId;
		}
	}
	return $order;	
}

function saveToken($token,$ordersId){
    openConn();
    $query = sprintf("UPDATE GRO_orders SET token = '%s' WHERE GRO_orders_id = %s LIMIT 1 ;",
    mysql_real_escape_string($token),
    mysql_real_escape_string($ordersId));         
    //execute the update
    $result=mysql_query($query);
    return $result;
    mysql_close();    
}

function getCountries(){
	//gets array of countries to display for address input
	$sql = "SELECT code, country from GRO_tbl_countries WHERE visible = 1 ORDER BY country";
	openConn();
	$rs = sqlQuery($sql);
	$res = array();
	if(!empty($rs)){
		//if more than one record, add first row
		if(count($rs) >1) $res['Select...'] = '';
		foreach($rs as $rec){
			$res[$rec['country']] = $rec['code'];
		}
	}
	mysql_close();
	return $res;
}

function getCountryName($countryCode){
	//gets array of countries to display for address input
	openConn();
	$sql = sprintf("SELECT country from GRO_tbl_countries WHERE code ='%s' LIMIT 1",
	mysql_real_escape_string($countryCode));
	$rs = sqlQuery($sql);
	$res = '';
	if(!empty($rs)){
		$res = $rs[0]['country'];
	}
	mysql_close();
	return $res;
}
function getPricePerCopy($shipMethod){
	//gets price per copy based on selected shiping method
	openConn();
	$sql = sprintf("SELECT extra_copy_price FROM GRO_tbl_shipping WHERE code = '%s' LIMIT 1",
	mysql_real_escape_string($shipMethod));
	$rs = sqlQuery($sql);
	$res = 0;
	if(!empty($rs)){
		$res = $rs[0]['extra_copy_price'];
	}
	mysql_close();
	return $res;
	
}

/**
 * checks discount code and returns discount percentage if code is valid
 * @param object $discountCode
 * @return int
 */
function getDiscount($discountCode){
	//checks discount code and gets discount percentage if its valid
	openConn();
	$today = date('Y-m-d');
	//check to see if the code is valid
	$query = sprintf("SELECT discount FROM GRO_discount_codes WHERE code = '%s' AND start_date <= '%s' AND end_date > '%s' LIMIT 1;",
			mysql_real_escape_string(strtoupper($discountCode)),$today,$today);
	$result1 = sqlQuery($query);
	//if it is valid, is there a restriction on the code?
	if(!empty($result1)){ // code is valid
			return $result1[0]['discount'];
	}
	mysql_close();
	return 0;	
}

//use this with caution, always use it with properly escaped sql string when using
//user input, returns an array of results
function sqlQuery($sqlString){

	$data = array();
	$i = 0;
	$result = mysql_query($sqlString);
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$data[$i] = $row;
			$i++;
		}
	}

	return $data;
} 

function openConn(){
	//opens a db connection
	//global $db_username, $db_password, $db_database, $db_hostname;
	
	//IL change to use constants
	$conn = mysql_connect(DB_HOST,DB_USER,DB_PASS);

	if (!$conn) {
		exit();
	}
	  
	if (!mysql_select_db(DB_NAME)) {
		exit();
	}	
	
}
?>