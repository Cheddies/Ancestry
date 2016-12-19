<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
require_once(ROOT . DS . 'include' . DS . 'siteconstants.php');
require_once(ROOT . DS . 'include' . DS . 'commonfunctions.php');

$url = $_GET['url'];
$badChars = array("\n","\r","#","\$","}","{","^","~","*","|","`",";","<",",","\\",">","(",")","!","[","]","'","\"");
$url = strtolower(form_clean($url,100,$badChars));

$newPage = false;
if($url == 'productdetail.php'){
	if(isset($_GET['number'])){
		$number = strtoupper(form_clean($_GET['number'],100,$badChars));
		switch($number){
			case 'GIFTPACK': $newPage = WEBROOT . 'products/gifts/ancestry-co-uk-membership-gift-pack';
			break;
			case 'FTMPLATINUM2010': $newPage = WEBROOT . 'products/software/family-tree-maker-2010-platinum-edition';
			break;
			case 'FTMDELUXE2010': $newPage = WEBROOT . 'products/software/family-tree-maker-2010-deluxe-edition';
			break;
			case 'WDYTYABOOK': $newPage = WEBROOT . 'products/books/who-do-you-think-you-are-encyclopedia-of-genealogy';
			break;
			case 'DNAPACK': $newPage = WEBROOT . 'products/gifts/dna-gift-pack';
			break;
			case 'ANCESTRYDNAGIFT': $newPage = WEBROOT . 'products/gifts/ancestry-co-uk-getting-started-pack';
			break;
		}		
	}	
}
if(stripos($url, '_cert.php') !==false){
	switch($url){
		case 'birth_cert.php': $newPage = WEBROOT . 'products/birth-marriage-death-certificates/birth-certificate';
		break;
		case 'marriage_cert.php': $newPage = WEBROOT . 'products/birth-marriage-death-certificates/marriage-certificate';
		break;
		case 'death_cert.php': $newPage = WEBROOT . 'products/birth-marriage-death-certificates/death-certificate';
		break;
		case 'bmd_cert.php': $newPage = WEBROOT . 'products/birth-marriage-death-certificates';
		break;
	}	
}

if($newPage) permRedir($newPage);

function permRedir($newPage){
	Header( "HTTP/1.1 301 Moved Permanently" );
	Header( "Location: " . $newPage ); 
	exit;
}

?>