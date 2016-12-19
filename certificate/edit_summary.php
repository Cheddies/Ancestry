<?php
require_once("include/edgeward/global_functions.inc.php");
//clean GET
$get = cleanData($_GET,array('edit'));
if($get['edit'] == 'par' || $get['edit'] == 'gro' || $get['edit'] == 'add' || $get['edit'] == 'del'){
	$_SESSION['editset'] = $get['edit'];
} else {
	//if nothing to edit 
	unset($_SESSION['editset']);	
}
$pageSuffix = $_SESSION['cert']['page_suffix'];
//return to summary page
header("Location: summary{$pageSuffix}.php");

?>