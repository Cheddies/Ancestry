<?php
//check to see if the page is accessed via HTTPS
if  ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') || ($_SERVER['HTTP_HOST']=="10.0.0.3" ) || ($_SERVER['HTTP_HOST']=="212.38.95.165") || ($_SERVER['HTTP_HOST']=="10.0.0.136" ) || ($_SERVER['HTTP_HOST']=="78.31.106.192" )) 
{

include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");
include ('include/header.php');

$_SESSION['promo_type']='FREEUPGRADE';

session_set_cookie_params ( 0,"/." , "", true);
	
$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

$clean=array();
?>
<h2 style="font-size:1.4em; font-weight:900">Exclusive Family Tree Maker&trade; Free* Upgrade Offer</h2>
<div class="checkout_box full">
<div style="height:22px; background-color: #eae4a9;"></div>
<img src="images/UK_CD.jpg" alt="UK CD" style="margin-right:30px; margin-left:10px; margin-top:5px;" />

<img src="images/aus_CD.jpg" alt="Australian CD" style="margin-top:5px;"/>

<p style="font-size:1.5em">
The Free Upgrade Offer Has Now Ended.
</p>
</div>
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