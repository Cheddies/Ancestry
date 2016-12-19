<?php
require_once("include/edgeward/global_functions.inc.php");
//clean GET
//$get = cleanData($_GET,array('edit'));
//IL change
if(isset($_GET['edit']))
	$get['edit']=form_clean($_GET['edit'], 10);
else
	$get['edit']='';
//End of IL change

if($get['edit'] == 'cart' || $get['edit'] == 'add'){
	$_SESSION['editset'] = $get['edit'];
} else {
	//if nothing to edit 
	unset($_SESSION['editset']);	
}
//return to summary page
header('Location:order_summary.php');

?>