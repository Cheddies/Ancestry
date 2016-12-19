<?php
require_once('include/edgeward/global_functions.inc.php');
//clean GET
//$get = cleanData($_GET,array('token','isAjax','id'));

//IL change
if(isset($_GET['token']))
	$get['token']=form_clean($_GET['token'], 32);
if(isset($_GET['isAjax']))
	$get['isAjax']=form_clean($_GET['isAjax'], 10);
if(isset($_GET['id']))
	$get['id']=form_clean($_GET['id'], 20);
//end of IL change

//check the security tokens match
if(!empty($get)){
	if( empty($get['token']) || $get['token'] != $_SESSION['token'] ){
		header('Location:basket.php');
		exit();
	}
}
// Unset the token, so that it cannot be used again
unset($_SESSION['token']);

//remove the line item from session basket
unset($_SESSION['shop']['baskets'][$get['id']]);
//delete line item from db
openConn();
$sql = sprintf("DELETE FROM tbl_baskets WHERE sessionid = '%s' AND itemcode = '%s';",
			   mysql_real_escape_string(session_id()),mysql_real_escape_string($get['id']));
mysql_query($sql);
mysql_close();
//if not an ajax request, redirect back to previous
if(!isset($get['isAjax'])){
	$url = explode('?',$_SERVER['HTTP_REFERER']);
	$referer = $url[0];
	header('Location:'.$referer);
}
?>