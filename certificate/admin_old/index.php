<?php
//check to see if the page is accessed via HTTPS
if  ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') 
	|| ($_SERVER['HTTP_HOST']=="10.0.0.3" ) 
	|| ($_SERVER['HTTP_HOST']=="212.38.95.165") 
	|| ($_SERVER['HTTP_HOST']=="ancestry.ubuntudev.elmbanklogistics.local" ) 
	|| ($_SERVER['HTTP_HOST']=="78.31.106.192" )
	|| ($_SERVER['HTTP_HOST']=="ancestry.internetlogistics.com")
)
{
require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");

if(isset($_SESSION['adminlogin']) && $_SESSION['adminlogin']==true)
{
//redirect to home page of admin
header('location:admin.php');
exit();
}


include ('include/header.php');

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

?>
<form action="check_pass.php" method="post">
<input type="hidden" name="token" id="token" value="<?php echo $token?>" />
USERNAME<br /><input type="text" name="username" autocomplete="off"><br />
<br />
PASSWORD<br />
<input type="password" name="password" autocomplete="off"><br />
<br />
<input type="submit" value="Log In">
</form>
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