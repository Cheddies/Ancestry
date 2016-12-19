<?php include("include/siteconstants.php"); ?>
<?php include("include/commonfunctions.php"); ?>
<?php
session_set_cookie_params ( 0,"/." , "", true);

//if shipping tpye has not been set
//need to  check if it is passed from a form
//or redirect to the checkout page that sets this

$clean=array();

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
			header('Location: enter_details.php');
			exit();
		}
	
	}
	else
	{
		header('Location:enter_details.php');
		exit();
	}
		
//Set mail/email prefs in SESSION

if(isset($_SESSION['dprent']))
	$clean['dprent']=form_clean($_POST['dprent'],1);
if(isset($_SESSION['dpemail']))
	$clean['dpemail']=form_clean($_POST['dpemail'],1);
if(isset($_SESSION['dpmail']))
	$clean['dpmail']=form_clean($_POST['dpmail'],1);
	
$shippingcost = sprintf('%.2f', getShippingCost($_SESSION['c_shiptype']));
$ordertotal = $baskettotal + $shippingcost;
$DisplayOrderTotal=formatcurrency($ordertotal);

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
		
//Generate a unique token to be used to check the input is from this form on the proccessing page
$token=UniqueToken();//will be passed in a hidden form element to proccessing page
//Store the token in the session so it can be checked on the proccessing page
$_SESSION['token']=$token;
//Also store the time which can be used to check the lifetime of the token
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
	$query = "UPDATE tbl_order_header_promo
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
		      WHERE niceordernum={$mysql['niceodrnum']};";
}
else
{
	$query = "INSERT INTO tbl_order_header_promo
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
			  source_key = '{$mysql['source_key']}';";
}

mysql_query($query) or die('Query failed: ' . mysql_error());

//Get order reference back
$clean['session'] = session_id();
$mysql['session']=mysql_real_escape_string($clean['session'],$link);

// Performing SQL query
$query = "SELECT niceordernum FROM tbl_order_header_promo WHERE ordernumber = '{$mysql['session']}';";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
$row = mysql_fetch_row($result);
$_SESSION['niceodrnum'] = $row[0];
mysql_free_result($result);
		
// Closing connection
mysql_close($link);
	
//Track URL code removed from promo pages

//$tracking_urls= get_tracking_code(session_id());

$site_section="Checkout Step 4";
include ('include/header.php');
include "include/checkout_header.php";
?>

<h2>Order Summary</h2>
<div class="checkout_box full">
<h3>Basket Summary</h3>
	<p>
	<table id="basket_summary" summary="List of items and quantitys in basket">
	<tbody>
		<tr><th>Item</th><th>Qty</th></tr>
		<tr><td>Family Tree Maker Upgrade</td> <td> 1</td> </tr> 
	</tbody>
	</table>	
	</p>
	<p>
		<table id="order_summary" summary="Summary of charges on your order">
			<tr>
				<td>Basket Total:</td>
				<td><?php Escaped_Echo( "&pound; 0.00" ) ?></td>
			</tr>
			<tr>
				<td>Shipping:</div>
				<td><?php Escaped_Echo( "&pound;" . $shippingcost) ?></td>
			</tr>
			<tr>
				<td>Order Total:</td>
				<td><strong><?php Escaped_Echo( "&pound;" . $shippingcost) ?></strong></td>
			</tr>
		</table>
	</p>
</div>
<div class="checkout_box half">
<h3>Billing Address</h3>
	<p>
		First Name:<?php Escaped_Echo( $_SESSION['c_name']);?><br />
		Surname:<?php Escaped_Echo( $_SESSION['c_sname']);?><br />
		Address 1:<?php Escaped_Echo($_SESSION['c_add1']);?><br />
		Address 2:<?php Escaped_Echo($_SESSION['c_add2']);?><br />
		City:<?php Escaped_Echo($_SESSION['c_city']);?><br />
		<span style="display:none;">State:<?php Escaped_echo ($_SESSION['c_state']);?><br /></span>
		Postcode/Zipcode:<?php Escaped_Echo( $_SESSION['c_postcode']);?><br />
		Country:<?php Escaped_Echo($_SESSION['BillCountryName']);?><br />
		Phone:<?php Escaped_Echo($_SESSION['c_phone']);?><br />
		Email Address:<?php Escaped_Echo($_SESSION['c_email']);?>
	</p>
</div>
<div class="checkout_box half">
<h3>Shipping Address</h3>
	<p>
	 	First Name:<?php Escaped_Echo($_SESSION['cs_name']);?><br />
	 	Surname:<?php Escaped_Echo($_SESSION['cs_sname']);?><br />
	 	Address 1:<?php Escaped_Echo( $_SESSION['cs_add1']);?><br />
	 	Address 2:<?php Escaped_Echo($_SESSION['cs_add2']);?><br />
	 	City:<?php Escaped_Echo($_SESSION['cs_city']);?><br />
	 	<span style="display:none;"><br />State:<?php echo $_SESSION['cs_state'];?><br /></span>
		Postcode/Zipcode:<?php Escaped_Echo($_SESSION['cs_postcode']);?><br />
		Country:<?php Escaped_Echo($_SESSION['ShipCountryName']);?>
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

<form method="post" action="https://securetrading.net/authorize/form.cgi">
<input type="hidden" name="orderref" value="<?php echo $_SESSION['niceodrnum']?>" />
<input type="hidden" name="orderinfo" value="AncestryShop.co.uk Online Order" />
<input type="hidden" name="name" value="<?php echo $_SESSION['c_name'] . " " . $_SESSION['c_sname']?>" />
<input type="hidden" name="company" value="" />
<input type="hidden" name="address" value="<?php echo $_SESSION['c_add1'] . "," . $_SESSION['c_add2']?>" />
<input type="hidden" name="town" value="<?php echo $_SESSION['c_city']?>" />
<input type="hidden" name="county" value="<?php echo $_SESSION['c_state']?>" />
<input type="hidden" name="country" value="<?php echo $_SESSION['BillCountryName']?>" />
<input type="hidden" name="postcode" value="<?php echo $_SESSION['c_postcode']?>" />
<input type="hidden" name="telephone" value="<?php echo $_SESSION['c_phone']?>" />
<input type="hidden" name="fax" value="" />
<input type="hidden" name="email" value="<?php echo $_SESSION['c_email']?>" />
<input type="hidden" name="url" value="" />
<input type="hidden" name="currency" value="gbp" />
<input type="hidden" name="requiredfields" value="name,email" />

<!-- Live Account -->
<input type="hidden" name="merchant" value="elmancestry11634" /> 
<!-- Test Account -->
<!--<input type="hidden" name="merchant" value="testelmbank9808" />-->
<input type="hidden" name="merchantemail" value="chris.gan@internetlogistics.com" />
<input type="hidden" name="customeremail" value="1" />
<input type="hidden" name="settlementday" value="1" />
<input type="hidden" name="callbackurl" value="1" />
<input type="hidden" name="token" value="<?php echo $token?>" />
<input type="hidden" name="session_id" value="<?php echo session_id();?>" />

<!-- added so callback from secure trading knows what to update -->
<input type="hidden" name="promo_type" value="<?php echo $_SESSION['promo_type']?>" />
<input type="hidden" name="promo_code" value="<?php echo $_SESSION['promo_code']?>" />

<?php $basetotal=($baskettotal+$shippingcost)*100;?>

<input type="hidden" name="amount" value="<?php echo $basetotal?>" />

<input type="hidden" name="cj_tracking" value="https://www.ancestryshop.co.uk/images/blank.gif" />
<input type="hidden" name="ba_tracking" value="https://www.ancestryshop.co.uk/images/blank.gif" />

<div id="checkout_nav">
<a href="enter_details.php" ><img id="back_button" src="images/back.gif" alt="Back to the Last Step" title="Back to the Last Step" /></a>
<input id="continue_button" type="image" src="images/continue.gif" alt="Place Order"/>
</div>
 
</form>

<?php include ('include/footer.php');?>