<?php	

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
// change WEBROOT to '/' on live
//define('WEBROOT', '/');
//define('ROOT', dirname(dirname(__FILE__)));
define('DEVELOPMENT_ENVIRONMENT', true);
require_once(ROOT . DS . 'include' . DS . 'siteconstants.php');
require_once(ROOT . DS . 'include' . DS . 'commonfunctions.php');
//get the url passed to this script
$url = $_GET['url'];
$badChars = array("\n","\r","#","\$","}","{","^","~","*","|","`",";","<",",","\\",">","(",")","!","[","]","'","\"");
$url = form_clean($url,100,$badChars);
//init pageData array
$pageData = array();
require_once (ROOT . DS . 'include' . DS . 'product_init.inc.php');