<?php
//IL addition
require_once('include/commonfunctions.php');
require_once('include/siteconstants.php');
//end of IL addition
$prefix = "tbl_";
$tableKeys = array('tbl_order_header'=>'ordernumber','tbl_baskets'=>'basketid');

function saveOrder($order){
	//saves an order, based on a hash array, to the database
	global $tableKeys, $prefix;
	openConn();
	
	foreach($order as $k=>$v){
		//get table name
		$tableName = $prefix.$k;
		//in case of baskets, get each array in turn
		if($tableName == 'tbl_baskets' && is_array($v)){
			foreach($v as $tk => $tv){
				saveToTable($tableName,$tv);
			}
		} elseif(is_array($v)){//if the variable is an array, treat it as a table
			saveToTable($tableName,$v);			
		}	
	}
	mysql_close();
	
}

function saveToTable($tableName,$tableArray){
	
	global $tableKeys;
	//get table info
	$query = sprintf("SHOW COLUMNS FROM %s;",
	mysql_real_escape_string($tableName));
	$result = mysql_query($query);
	
	if (!$result) {
		//echo 'Could not run query: ' . mysql_error();
		exit();
	}
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$tableInfo[$row['Field']] = $row;
		}
	}
	//loop thru the array, create sql string
	foreach($tableArray as $ok=>$ov){
		$isIntField = strpos($tableInfo[$ok]['Type'],'int')!==false;
		if($isIntField && !ereg('^[0-9]*$',$value)) continue;
		if($tableInfo[$ok]['Null'] == 'YES' && !strlen(trim($ov))){	
			$val = "null";
		} else {
			$val = $isIntField ? mysql_real_escape_string($ov) : "'".mysql_real_escape_string($ov)."'";
		}
		$values = $values ? $values.",".$val : $val ;
		$fields = $fields ? $fields.",".$ok : $ok ;
	}
	$query = sprintf("REPLACE INTO %s (%s) VALUES (%s);",
	mysql_real_escape_string($tableName),
	mysql_real_escape_string($fields),
	$values);				
	debug($query);
	//execute the update/insert
	$result=mysql_query($query);
	//if address data or cert data, get id from db and update session

	$lastId = mysql_insert_id();

	//update the order in session with id field from table							
	if($tableName == 'tbl_baskets'){			
		$_SESSION['shop']['baskets'][$tableArray['itemcode']][$tableKeys[$tableName]] = $lastId;
	} elseif($tableName == 'tbl_order_header'){
		$_SESSION['shop']['order_header']['niceordernum'] = $lastId;
	}

	
}

function saveToken($token,$orderId){
    openConn();
    $query = sprintf("UPDATE tbl_order_header SET token = '%s' WHERE ordernumber = %s LIMIT 1 ;",
    mysql_real_escape_string($token),
    mysql_real_escape_string($orderId));         
    //execute the update
    $result=mysql_query($query);
    return $result;
    mysql_close();    
}

function getCountries(){
	//gets array of countries to display for address input
	$sql = "SELECT code, country from tbl_countries WHERE visible = 1 ORDER BY country";
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
	$sql = sprintf("SELECT country from tbl_countries WHERE code ='%s' LIMIT 1",
	mysql_real_escape_string($countryCode));
	$rs = sqlQuery($sql);
	$res = '';
	if(!empty($rs)){
		$res = $rs[0]['country'];
	}
	mysql_close();
	return $res;
}

function getDiscount($discountCode,$itemCode){
	//checks discount code and gets discount percentage if its valid
	openConn();
	$today = date('Y-m-d');
	//check to see if the code is valid
	$query = sprintf("SELECT discount FROM discount_codes WHERE code = '%s' AND start_date <= '%s' AND end_date > '%s' LIMIT 1;",
			mysql_real_escape_string($discountCode),$today,$today);
	$result1 = sqlQuery($query);
	//if it is valid, is there a restriction on the code?
	if(!empty($result1)){ // code is valid
		$query = sprintf("SELECT * FROM discount_restriction WHERE discount_code = '%s';",
				mysql_real_escape_string($discountCode));
		$result2 = sqlQuery($query);
		if(empty($result2)){ //no restriction found
			return $result1[0]['discount'];
		} else { //there is a restriction, does it match the item code?
			foreach($result2 as $r){
				if($r['product_code'] == $itemCode){ //code matches
					return $result1[0]['discount'];
				}
			}
		}
	}
	return 0;	

	mysql_close();
}

function getProductInfo($productCode){
	openConn();
	//gets product information from supplied product code
	$query = sprintf("SELECT number AS itemcode, name, price, units FROM tbl_products WHERE number = '%s' LIMIT 1;",
				mysql_real_escape_string($productCode));	
	$result = sqlQuery($query);
	mysql_close();
	return 	$result;
}

//use this with caution, always use it with properly escaped sql string when using
//user input, returns an array of results
/*function sqlQuery($sqlString){

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
} */

function openConn(){
	//opens a db connection
	//IL edit 
	//change to use site constants
	//global $db_username, $db_password, $db_database, $db_hostname;
	
	//$conn = mysql_connect($db_hostname,$db_username,$db_password);

	$conn = mysql_connect(DB_HOST,DB_USER,DB_PASS);
	if (!$conn) {
		echo "Unable to connect to DB: " . mysql_error();
		exit();
	}
	if (!mysql_select_db(DB_NAME)) {
		echo "Unable to select $database: " . mysql_error();
		exit();
	}	
	
}



?>