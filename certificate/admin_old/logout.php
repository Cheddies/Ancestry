<?php 

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");

session_destroy();
session_regenerate_id();

header('location:index.php');
exit();

?>