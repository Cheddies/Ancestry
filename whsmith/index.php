<?php
//check to see if the page is accessed via HTTPS
if  ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') || ($_SERVER['HTTP_HOST']=="10.0.0.3" ) || ($_SERVER['HTTP_HOST']=="212.38.95.165") || ($_SERVER['HTTP_HOST']=="10.0.0.136" ) || ($_SERVER['HTTP_HOST']=="78.31.106.192" )) 
{

include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");
include ('include/header.php');

$_SESSION['promo_type']='WHSMITH';

session_set_cookie_params ( 0,"/." , "", true);
	
$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

$clean=array();
?>
<h2 style="font-size:1.4em; font-weight:900">WH Smith Ancestry Gift Pack Registration</h2>
<div class="checkout_box full">
<div style="height:22px; background-color: #eae4a9;"></div>
<p>
<img src="images/gift_pack.jpg" alt="Gift Pack" />
</p>
<p style="font-size:1.2em">
Please enter your unique activation code.  Please note that this code can only be used once.  
</p>
<form action="promo_code_lookup.php" method="post">
	<p>
		<?php 
		if(isset($_GET['error']))
		{
		?>
		
			<span class="error">
		<?php
			$clean['error']=form_clean($_GET['error'],1);
			switch($clean['error']){
				case 1:
					echo "Please enter your activation code";
				break;
				case 2:
					echo "Activation code has already been used";
				break;
				case 3:
					echo "Invalid activation code";
				break;
				case 4:
					echo "Code has expired";
				break;
			}
			
			
		?>
			</span>
			<br />
		<?php
		}
		?>
		<label for="promo_code">Unique Activation Code:</label>
		<!--<input maxlength="40" size="40" type="text" id="promo_code" name="promo_code" value="<?php if(isset($_SESSION['promo_code'])) echo $_SESSION['promo_code']?>"/>-->
		<input maxlength="3" size="3" type="text" id="promo_code_pt1" name="promo_code_pt1" value="<?php if(isset($_SESSION['promo_code_pt1'])) echo $_SESSION['promo_code_pt1']?>"/>
		-
		<input maxlength="3" size="3" type="text" id="promo_code_pt2" name="promo_code_pt2" value="<?php if(isset($_SESSION['promo_code_pt2'])) echo $_SESSION['promo_code_pt2']?>"/>
		-
		<input maxlength="3" size="3" type="text" id="promo_code_pt3" name="promo_code_pt3" value="<?php if(isset($_SESSION['promo_code_pt3'])) echo $_SESSION['promo_code_pt3']?>"/>
		
		
		<input type="hidden" name="token" value="<?php echo $token?>" />
		<input type="submit" value="Validate Code" />
	</p>
</form>
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