<?php
//check to see if the page is accessed via HTTPS
if  ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') || ($_SERVER['HTTP_HOST']=="10.0.0.3" ) || ($_SERVER['HTTP_HOST']=="212.38.95.165") || ($_SERVER['HTTP_HOST']=="ancestry.ubuntudev.elmbanklogistics.local" ) || ($_SERVER['HTTP_HOST']=="78.31.106.192" )) 
{
require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");

include ('include/header.php');

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

?>
Username and Password Invalid.
<br />
<a href="index.php">Try Again</a>
<?php
include ('include/footer.php');
}
else
{
	$page = $_SERVER['PHP_SELF'];
	$page = str_replace(" ","%20",$page);
	$page =  "https://" . $_SERVER['HTTP_HOST'] . $page ;	
	$page = "Location:" . $page;
	header($page);
}
?>