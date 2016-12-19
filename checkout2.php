<?php include("include/siteconstants.php"); ?>
<?php include("include/commonfunctions.php"); ?>
<?php
session_set_cookie_params ( 0,"/." , "", true);


//need to check POST token when the page is accessed from checkout1b.
//and GET token when the page is accessed via the checkoutproccess2 page
if
( 
(isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
||
(isset($_GET['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_GET['token'],$_SESSION['token'],$_SESSION['token_time'],0))
)

{
//if shipping tpye has not been set
//need to  check if it is passed from a form
//or redirect to the checkout page that sets this

$clean=array();

if(isset($_POST['shiptype']))
	$clean['shiptype']=form_clean($_POST['shiptype'],3);

if( (isset($clean['shiptype'])) && (isset($_SESSION['cs_country'])) && (CheckShipType($clean['shiptype'],$_SESSION['cs_country'])==true) )
{
	$_SESSION['c_shiptype'] =  $clean['shiptype'];
}
else
{
	//Shiptype is not sent, either an error on checkout2 or page is being accessed directly
	if(isset($_SESSION['c_shiptype']) && (CheckShipType($_SESSION['c_shiptype'],$_SESSION['cs_country'])==true))
	{
		//if the shiptype is set and is correct with regards to the country
		//do nothing but else return to other checkout page
	}
	else
	{
		header('Location: checkout1a.php');
	}
	
}

	$baskettotal = sprintf('%.2f', getBasketTotal(session_id()));
	
	if ( (isset($_SESSION['c_shiptype'])) && ($_SESSION['c_shiptype']!="") &&
	     (isset($_SESSION['c_name']))     && ($_SESSION['c_name']!="") &&
	     (isset($_SESSION['c_sname']))    && ($_SESSION['c_sname']!="") &&
	     (isset($_SESSION['c_add1']))     && ($_SESSION['c_add1']!="") &&
	     (isset($_SESSION['c_city']))     && ($_SESSION['c_city']!="") &&
	     (isset($_SESSION['c_postcode'])) && ($_SESSION['c_postcode']!="") &&
	     (isset($_SESSION['c_email']))    && ($_SESSION['c_email']!="") &&
	     (isset($_SESSION['c_same']))     && ($_SESSION['c_same']!="") &&
	     (isset($_SESSION['cs_name']))    && ($_SESSION['cs_name']!="") &&
	     (isset($_SESSION['cs_sname']))   && ($_SESSION['cs_sname']!="") &&
	     (isset($_SESSION['cs_add1']))    && ($_SESSION['cs_add1']!="") &&
	     (isset($_SESSION['cs_city']))    && ($_SESSION['cs_city']!="") &&
	     (isset($_SESSION['cs_postcode'])) && ($_SESSION['cs_postcode']!="") &&
	     (isset($_SESSION['c_country']))   && ($_SESSION['c_country']!="") &&
	     (isset($_SESSION['cs_country']))   && ($_SESSION['cs_country']!="") &&
	     (isset($_SESSION['c_phone'])) && ($_SESSION['c_phone']!="")
	   )
	{
		if ( (validateemail($_SESSION['c_email']) == false) || (is_numeric($_SESSION['c_phone'])== FALSE) )
		{
		 header('Location: checkout.php');
		}
		$shippingcost = sprintf('%.2f', getShippingCost($_SESSION['c_shiptype']));

		$ordertotal = $baskettotal + $shippingcost;
		$DisplayOrderTotal=formatcurrency($ordertotal);
	}
	else
		header('Location:checkout.php');
	
		//Set the current year to work out credit card years						
		$CurrentYear=date("Y");
		
//Set mail/email prefs in SESSION

/*if(isset($_POST['dpmail']))
	$clean['dpmail']=form_clean($_POST['dpmail'],1);
	if(isset($_POST['dpemail']))
	$clean['dpemail']=form_clean($_POST['dpemail'],1);
if(isset($_POST['dpboth']))
	$clean['dpboth']=form_clean($_POST['dpboth'],1);
*/

if(isset($_POST['dprent']))
	$clean['dprent']=form_clean($_POST['dprent'],1);
	
if(isset($_POST['dpemail']))
	$clean['dpemail']=form_clean($_POST['dpemail'],1);
	
if(isset($_POST['dpmail']))
	$clean['dpmail']=form_clean($_POST['dpmail'],1);
	
if(!isset($_POST['tcs']))
	header('location:checkout1b.php?error=tcs');
else
	$_SESSION['tcs']=true;	

?>
<?php
//Insert order details to DB
include("include/final.php");
	
	function validate_cardnum($cardnum) {
		$checkdigit=substr($cardnum,-1);
		$remainingcardnum=substr($cardnum,0,strlen($cardnum)-1);

		$i=0;

	    	$checksum = 0;

		while($i < strlen($remainingcardnum)) {
			if($i%2==0)
				$remaing_array[$i]=substr($remainingcardnum,($i+1)*-1,1) * 2;
			else
				$remaing_array[$i]=substr($remainingcardnum,($i+1)*-1,1);
			if($remaing_array[$i]>=10)
				$checksum=$checksum+1;

			$checksum=$checksum+($remaing_array[$i]%10);
			$i++;
		}

		$calculatedcheckdigit=(10-($checksum%10))%10;
		if($calculatedcheckdigit==$checkdigit)
			return "ok";

		else
			return "not ok";
	}

		
	//check to make sure all the other info is set

	
	if(CheckoutSessionCheck()!=true)
	{
		header('Location: checkouterror.php');
	}

	
		//Set for OPT - OUT 
		
		/*if(isset($clean['dpmail']))
			$nomail = 1;
		else
			$nomail = 0;

		if(isset($clean['dprent']))
			$norent = 1;
		else
			$norent = 0;

		if(isset($clean['dpemail']))
			$noemail = 1;
		else
			$noemail = 0;*/
			
			
		//Set for OPT - IN
		/*if(isset($clean['dpmail']))
			$nomail = 0;
		else
			$nomail = 1;

		

		if(isset($clean['dpemail']))
			$noemail = 0;
		else
			$noemail = 1;
		*/
		//changed so 1 tick box sets both mail and email. Still opt-in
		
		/*if(isset($clean['dpboth']))
		{
			$nomail=0;
			$noemail=0;
		}
		else
		{
			$nomail=1;
			$noemail=1;
		}
			
		if(isset($clean['dprent']))
			$norent = 0;
		else
			$norent = 1;	*/
			
			
		//changed 14-08-08 
		//1 tick box for email - opt in 
		//1 box for rent - opt-in
		//no box for post opt-in - always opt-in to post
		if(isset($clean['dprent']))
			$norent=0;
		else
			$norent=1;
			
		if(isset($clean['dpemail']))
			$noemail=0;
		else
			$noemail=1;
		
		$nomail=0;
		
		
		//Generate a unique token
		//to be used to check the input is 
		//from this form on the proccessing page
		$token=UniqueToken();//will be passed in a hidden form element to proccessing page
		
		//Store the token in the session so it can
		//be checked on the proccessing page
		$_SESSION['token']=$token;
			
		//Also store the time 
		//which can be used to check
		//the lifetime of the token
		$_SESSION['token_time']=time();

		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		$mysql=array();
		$mysql['session_id']=mysql_real_escape_string(session_id(),$link);
		$orderdate = date("Y-m-d");
		$mysql['orderdate']=mysql_real_escape_string($orderdate,$link);
		$mysql['nomail']=mysql_real_escape_string($nomail,$link);
		$mysql['norent']=mysql_real_escape_string($norent,$link);
		$mysql['noemail']=mysql_real_escape_string($noemail,$link);
		$mysql['token']=mysql_real_escape_string($_SESSION['token'],$link);
		$mysql['token_time']=mysql_real_escape_string($_SESSION['token_time'],$link);
		if(!isset($_SESSION['discount_code']))
		{
			$mysql['source_key']="";
		}
		else
		{
			$mysql['source_key']=mysql_real_escape_string($_SESSION['discount_code']);
		}
		
		
		if(isset($_SESSION['niceodrnum']))
		{
			$mysql['niceodrnum']=mysql_real_escape_string($_SESSION['niceodrnum'],$link);
			$query = "UPDATE tbl_order_header
				  SET ordernumber = '" . $mysql['session_id'] . "',
				      order_date = '" . $mysql['orderdate'] . "',
				      firstname = '" . mysql_clean($_SESSION['c_name'], 15,$link) . "',
				      lastname = '" . mysql_clean($_SESSION['c_sname'], 20,$link) . "',
				      address1 = '" . mysql_clean($_SESSION['c_add1'], 40,$link) . "',
				      address2 = '" . mysql_clean($_SESSION['c_add2'], 40,$link) . "',
				      city = '" . mysql_clean($_SESSION['c_city'], 20,$link) . "',
				      state ='" . mysql_clean($_SESSION['c_state'], 2,$link) . "',
				      zipcode = '" . FormatPostCode(  mysql_clean($_SESSION['c_postcode'], 10,$link),$_SESSION['c_country']) . "',
				      phone = '" . mysql_clean($_SESSION['c_phone'], 14,$link) . "',
				      email = '" . mysql_clean($_SESSION['c_email'], 50,$link) . "',
				      sfirstname = '" . mysql_clean($_SESSION['cs_name'], 15,$link) . "',
				      slastname = '" . mysql_clean($_SESSION['cs_sname'], 20,$link) . "',
				      saddress1 = '" . mysql_clean($_SESSION['cs_add1'], 40,$link) . "',
				      saddress2 = '" . mysql_clean($_SESSION['cs_add2'], 40,$link) . "',
				      scity = '" . mysql_clean($_SESSION['cs_city'], 20,$link) . "',
				      sstate ='" . mysql_clean($_SESSION['cs_state'], 2,$link) . "',
				      szipcode = '" . FormatPostCode(  mysql_clean($_SESSION['cs_postcode'], 10,$link),$_SESSION['cs_country']) . "',
				      shipvia = '" . mysql_clean($_SESSION['c_shiptype'], 3,$link) . "',
				      country = '" . mysql_clean($_SESSION['c_country'],3,$link) ."',
				      scountry= '" . mysql_clean($_SESSION['cs_country'],3,$link) ."',
				      nomail = {$mysql['nomail']},
				      norent = {$mysql['norent']},
				      noemail = {$mysql['noemail']},
				      token = '{$mysql['token']}',
				      token_time = '{$mysql['token_time']}',
				      source_key = '{$mysql['source_key']}'
				      WHERE niceordernum={$mysql['niceodrnum']}
				      ;
				      ";
		}
		else
		{
			$query = "INSERT INTO tbl_order_header
				  SET ordernumber = '" . $mysql['session_id'] . "',
				      order_date = '" . $mysql['orderdate'] . "',
				      firstname = '" . mysql_clean($_SESSION['c_name'], 15,$link) . "',
				      lastname = '" . mysql_clean($_SESSION['c_sname'], 20,$link) . "',
				      address1 = '" . mysql_clean($_SESSION['c_add1'], 40,$link) . "',
				      address2 = '" . mysql_clean($_SESSION['c_add2'], 40,$link) . "',
				      city = '" . mysql_clean($_SESSION['c_city'], 20,$link) . "',
				      state ='" . mysql_clean($_SESSION['c_state'], 2,$link) . "',
				      zipcode = '" . FormatPostCode(  mysql_clean($_SESSION['c_postcode'], 10,$link),$_SESSION['c_country']) . "',
				      phone = '" . mysql_clean($_SESSION['c_phone'], 14,$link) . "',
				      email = '" . mysql_clean($_SESSION['c_email'], 50,$link) . "',
				      sfirstname = '" . mysql_clean($_SESSION['cs_name'], 15,$link) . "',
				      slastname = '" . mysql_clean($_SESSION['cs_sname'], 20,$link) . "',
				      saddress1 = '" . mysql_clean($_SESSION['cs_add1'], 40,$link) . "',
				      saddress2 = '" . mysql_clean($_SESSION['cs_add2'], 40,$link) . "',
				      scity = '" . mysql_clean($_SESSION['cs_city'], 20,$link) . "',
				      sstate ='" . mysql_clean($_SESSION['cs_state'], 2,$link) . "',
				      szipcode = '" . FormatPostCode(  mysql_clean($_SESSION['cs_postcode'], 10,$link),$_SESSION['cs_country']) . "',
				      shipvia = '" . mysql_clean($_SESSION['c_shiptype'], 3,$link) . "',
				      country = '" . mysql_clean($_SESSION['c_country'],3,$link) ."',
				      scountry= '" . mysql_clean($_SESSION['cs_country'],3,$link) ."',
				      nomail = {$mysql['nomail']},
				      norent = {$mysql['norent']},
				      noemail = {$mysql['noemail']},
				      token = '{$mysql['token']}',
				      token_time = '{$mysql['token_time']}',
				      source_key = '{$mysql['source_key']}';
				      ";
			}
		mysql_query($query) or die('Query failed: ' . mysql_error());

//Get order reference back
$clean['session'] = session_id();

$mysql['session']=mysql_real_escape_string($clean['session'],$link);

// Performing SQL query
$query = "SELECT niceordernum FROM tbl_order_header WHERE ordernumber = '{$mysql['session']}';";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());


$row = mysql_fetch_row($result);

$_SESSION['niceodrnum'] = $row[0];

mysql_free_result($result);
		
// Closing connection
mysql_close($link);
	

$tracking_urls= get_tracking_code(session_id());



?>


<?php $site_section="Checkout Step 4"?>
<?php 
	  include ('include/header.php');

?>

<?php include "include/checkout_header.php"?>
<h2>Order Summary</h2>

<?php if (isset($_GET['error'])) {?><div align="left" class="error"><?php Escaped_Echo( 'There has been an error processing your request, please check the flagged fields.  ');?></div><?php } ?>
	



<div class="checkout_box full">
<h3>Basket Summary</h3>
	<p>
	<?php include("include/basket_summary_list.php")?>
	</p>
	<p>
		<table id="order_summary" summary="Summary of charges on your order">
			<tr>
				<td>Basket Total:</td>
				<td><?php Escaped_Echo( "&pound;" . $baskettotal) ?></td>
			</tr>
			<tr>
				<td>Shipping:</div>
				<td><?php Escaped_Echo( "&pound;" . $shippingcost) ?></td>
			</tr>
			<tr>
				<td>Order Total:</td>
				<td><strong><?php Escaped_Echo( $DisplayOrderTotal) ?></strong></td>
			</tr>
		</table>
	</p>
</div>

<div class="checkout_box half">
<h3>Billing Address</h3>
	<p>
	First Name:<?php Escaped_Echo( $_SESSION['c_name']);?>
	<br />Surname:
		<?php
			Escaped_Echo( $_SESSION['c_sname']);
		?>
	<br />Address 1:
		<?php
			Escaped_Echo($_SESSION['c_add1']);
		?>
	<br />Address 2:
		<?php
			Escaped_Echo($_SESSION['c_add2']);
		?>
	<br />City:
		<?php
			Escaped_Echo($_SESSION['c_city']);
		?>
	<span style="display:none;"><br />State:
		<?php
			Escaped_echo ($_SESSION['c_state']);
		?></span>
	<br />Postcode/Zipcode:
		<?php
			Escaped_Echo( $_SESSION['c_postcode']);
		?>
	<br />Country:
		<?php
			Escaped_Echo($_SESSION['BillCountryName']);
		?>
	<br />Phone:
		<?php
			Escaped_Echo($_SESSION['c_phone']);
		?>
	<br />Email Address:
		<?php
			Escaped_Echo($_SESSION['c_email']);
		?>
	</p>
</div>

<div class="checkout_box half">
 <h3>Shipping Address</h3>
	<p>
	
 	First Name:
	<?php
		Escaped_Echo($_SESSION['cs_name']);
	?>
	
	<br />Surname:
	<?php
		Escaped_Echo($_SESSION['cs_sname']);
	?>
	 
							 
	<br />Address 1:
	<?php
		Escaped_Echo( $_SESSION['cs_add1']);
	?>
	 
							 
	<br />Address 2:
	<?php
		Escaped_Echo($_SESSION['cs_add2']);
	?>
	 
							 
	<br />City:
	<?php
		Escaped_Echo($_SESSION['cs_city']);
	?>
	 
	<span style="display:none;"><br />State:
	<?php
		echo $_SESSION['cs_state'];
	?></span>

	<br />Postcode/Zipcode:
	<?php
		Escaped_Echo($_SESSION['cs_postcode']);
	?>
	 
	<br />Country:
	<?php
		Escaped_Echo($_SESSION['ShipCountryName']);
	?>
	</p>
</div>
<div class="checkout_box full">
<h3>Please check all your details are correct before proceeding. </h3>
<p>
Click 'Back' to change your details. You can not change your details past this point. 
<br />
Click 'Continue' to be taken to our secure payment pages and complete your order.
</p>

</div>
<!-- Google code has to come before form with javascript call in -->
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	var pageTracker = _gat._getTracker("UA-810272-11");
	pageTracker._setDomainName("none");
	pageTracker._setAllowLinker(true);
	pageTracker._initData();
	pageTracker._trackPageview();
</script>
<form method="post" action="https://securetrading.net/authorize/form.cgi" onSubmit="javascript:pageTracker._linkByPost(this)">
<INPUT type="hidden" name="orderref" value="<?php echo $_SESSION['niceodrnum']?>" />
<INPUT type="hidden" name="orderinfo" value="AncestryShop.co.uk Online Order" />
<INPUT type="hidden" name="name" value="<?php echo $_SESSION['c_name'] . " " . $_SESSION['c_sname']?>" />
<INPUT type="hidden" name="company" value="" />
<INPUT type="hidden" name="address" value="<?php echo $_SESSION['c_add1'] . "," . $_SESSION['c_add2']?>" />
<INPUT type="hidden" name="town" value="<?php echo $_SESSION['c_city']?>" />
<INPUT type="hidden" name="county" value="<?php echo $_SESSION['c_state']?>" />
<INPUT type="hidden" name="country" value="<?php echo $_SESSION['BillCountryName']?>" />
<INPUT type="hidden" name="postcode" value="<?php echo $_SESSION['c_postcode']?>" />
<INPUT type="hidden" name="telephone" value="<?php echo $_SESSION['c_phone']?>" />
<INPUT type="hidden" name="fax" value="" />
<INPUT type="hidden" name="email" value="<?php echo $_SESSION['c_email']?>" />
<INPUT type="hidden" name="url" value="" />
<INPUT type="hidden" name="currency" value="gbp">
<INPUT type="hidden" name="requiredfields" value="name,email">

<!-- Live Account -->
<INPUT type="hidden" name="merchant" value="elmancestry11634" > 


<INPUT type="hidden" name="merchantemail" value="chris.gan@internetlogistics.com">

<INPUT type="hidden" name="customeremail" value="1">
<INPUT type="hidden" name="settlementday" value="1">

<input type="hidden" name="callbackurl" value="1" />


<input type="hidden" name="token" value="<?php echo $token?>" />
<input type="hidden" name="session_id" value="<?php echo session_id();?>" />

<input type="hidden" name="cj_tracking" value="<?php echo $tracking_urls['CJ_URL']?>" />
<input type="hidden" name="ba_tracking" value="<?php echo $tracking_urls['BA_URL']?>" />

<?php
$basetotal=($baskettotal+$shippingcost)*100;
?>
<INPUT type="hidden" name="amount" value="<?php echo $basetotal?>" />

<div id="checkout_nav">
<a href="checkout1b.php" ><img id="back_button" src="images/back.gif" alt="Back to the Last Step" title="Back to the Last Step" /></a>
<input id="continue_button" type="image" src="images/continue.gif" alt="Place Order"/>
</div>
 
</form>

<?php include ('include/footer.php');?>

<?php
}
else
{
	//problem with token
	header('location:checkout1b.php');
}
?>

