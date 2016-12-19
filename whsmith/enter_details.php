<?php include_once("include/siteconstants.php");?>
<?php include_once("include/commonfunctions.php");?>
<?php

//check to see if the page is accessed via HTTPS
if  ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') || ($_SERVER['HTTP_HOST']=="10.0.0.3" ) || ($_SERVER['HTTP_HOST']=="212.38.95.165") || ($_SERVER['HTTP_HOST']=="10.0.0.136" ) || ($_SERVER['HTTP_HOST']=="78.31.106.192" )) 
{
	session_set_cookie_params ( 0,"/." , "", true);
	
	if(!isset($_SESSION['promo_code']))
	{
		//promo code has not been entered 
		
		header('location:index.php');
		exit();
	}

	
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect:' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	// Performing SQL query
	
	
	
	$querystate = "SELECT name,code,visible FROM tbl_states WHERE visible='1' ORDER BY name ASC;";
	$resultstate = mysql_query($querystate) or die('Query failed:' . mysql_error());
	$resultstate2 = mysql_query($querystate) or die('Query failed:' . mysql_error());
	
?>
<?php $site_section="Checkout Step 1"?>
<?php
	// Set all the session variables if this is the first attempt at checkout

	if (isset($_SESSION['firstcheckout']) == false) {

		// Billing Address
		$_SESSION['c_name'] = "";
		$_SESSION['c_sname'] = "";
		$_SESSION['c_add1'] = "";
		$_SESSION['c_add2'] = "";
		$_SESSION['c_city'] = "";
		$_SESSION['c_postcode'] = "";
		$_SESSION['c_phone'] = "";
		$_SESSION['c_email'] = "";
		$_SESSION['c_country'] = "";
		$_SESSION['c_state'] = "";
		
		$_SESSION['c_same'] = "off";
		
		// Shipping Address
		$_SESSION['cs_name'] = "";
		$_SESSION['cs_sname'] = "";
		$_SESSION['cs_add1'] = "";
		$_SESSION['cs_add2'] = "";
		$_SESSION['cs_city'] = "";
		$_SESSION['cs_postcode'] = "";
		$_SESSION['cs_country'] = "";
		$_SESSION['cs_state'] = "";
		// If this is the first checkout set the flag
		$_SESSION['firstcheckout'] = 0;
	}
?>
<?php include ('include/header.php'); ?>


<?php include "include/checkout_header.php"?>
<h2>Enter Your Details</h2>

<?php

if (isset($_GET['error'])) 
{
?>
<span class="error">
<?php
	Escaped_Echo('There has been an error processing your request, please complete all fields marked with an asterisk.');
?>
</span>
<?php	

} 

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
?>	
<form action="proccess_details.php" method="post">

<div class="checkout_box full">
<h3>Data Protection Notice for Ancestry Shop</h3>
<p>
The Generations Network (Commerce) Limited (registration number Z1407756) and The Generations Network, Inc are Data Controllers for the purposes of the Data Protection Act 1998.  We may use your personal information for marketing purposes and to facilitate payment and fulfillment for membership and purchases at the Ancestry Shop.  We may share your personal information with carefully selected third parties for the purposes of processing and fulfilling your order.
</p>
<p>
You consent to our transferring your information to countries or jurisdictions which do not provide the same level of data protection as the UK, if necessary for the above purposes. 
</p>
<p>
For further details of our information gathering and dissemination practices please see our <a href="privacy_policy.php">PRIVACY STATEMENT</a>.
</p>
</div>
<div class="checkout_box full">
<h3>Your Address</h3>
<p>	
	<input type="hidden" name="token" value="<?php echo $token?>" />
	<label for="fname">*First Name:</label>
		<?php 
		if (isset($_GET['billfname']) == true)
		{ 
		?>
			<span class="error">
				<?php Escaped_Echo("First name is a Required Field");?>
			</span>
		<?php
		} 
		?>
		<br />
		<input type="text" size="15" maxlength="15" id="fname" name="fname" value="<?php Escaped_Echo ($_SESSION['c_name']) ?>" />
		<br />
		<label for="surname">*Surname:</label>
		<?php 
		if (isset($_GET['billsurname']) == true)
		{
		?>
			<span class="error">
				<?php Escaped_Echo("Surname is a Required Field");?>
			</span>
		<?php
		} 
		?>
		<br />
		<input type="text" size="15" maxlength="20" id="surname" name="surname" value="<?php Escaped_Echo ($_SESSION['c_sname']) ?>" />
		<br />
		<label for="add1">*Address 1:</label>
		<?php 
		if (isset($_GET['billadd1']) == true)
		{
		?>
			<span class="error">
				<?php Escaped_Echo ("Address 1 is a Required Field");?>
			</span>
		<?php
		}
		?>
		<br /> 
		<input type="text" size="40" maxlength="40" id="add1" name="add1" value="<?php Escaped_Echo ($_SESSION['c_add1']) ?>" />
		<br />
		<label for="add2">Address 2:</label><br /><input type="text" size="40" maxlength="40" id="add2" name="add2" value="<?php Escaped_Echo ($_SESSION['c_add2']) ?>" />
		<br />
		<label for="city">*City:</label>
		<?php 
		if (isset($_GET['billcity']) == true)
		{
		?>
			<span class="error">
				<?php Escaped_Echo ("City is a Required Field");?>
			</span>
		<?php
		} 
		?>
		<br />
		
		<input type="text" size="20" maxlength="20" id="city" name="city" value="<?php Escaped_Echo ($_SESSION['c_city']) ?>" />
			
		<label style="display:none" for="state">State(required for US + Canada):</label><?php if (isset($_GET['billstate']) == true) { echo "<span class=\"error\">Select a state</span>";} ?>
		
			
		<select style="display:none;" name="state" id="state">
		
		<?php
		if((isset($_SESSION['c_state'])) && ($_SESSION['c_state']!=""))
		{
		?>
		<option value="">Select a State</option>
		<?php
			while ($line = mysql_fetch_array($resultstate, MYSQL_ASSOC)) {
				if($line['code']==$_SESSION['c_state'])
				{
		?>
			<option selected value="<?php echo $line['code'];?>"> <?php echo $line['name']?> (<?php echo $line['code']?>)</option>
		<?php
				}
				else
				{
		?>	
				<option value="<?php echo $line['code'];?>"> <?php echo $line['name']?> (<?php echo $line['code']?>)</option>
		<?php
				}
				}
		}
		else
		{
		?>
		<option selected value="">Select a State</option>
		<?php
			while ($line = mysql_fetch_array($resultstate, MYSQL_ASSOC)) {
		?>	
				<option value="<?php echo $line['code'];?>"> <?php echo $line['name']?> (<?php echo $line['code']?>)</option>
		<?php
				}
		}	
		
				mysql_free_result($resultstate);
		?>
		</select>
		<br />
		<label for="postcode">*Postcode/Zipcode:</label>
		<br />	
		<input type="text" size="10" maxlength="10" id="postcode" name="postcode" value="<?php Escaped_Echo ($_SESSION['c_postcode']) ?>" />
		<?php if (isset($_GET['billpostcode']) == true) 
		{ 
		?>
			<span class="error">
				<?php Escaped_Echo("Required Field");?>
			</span>
		<?php
		} 
		?>
		<br />
		<label for="country">*Country:</label>
		<br />
		<input name="country" id="country" type="hidden" value="073"> United Kingdom
		
		<!--<select id="country" name="country">
		<?php
		/*
		if ( (isset($_SESSION['c_country'])) && ($_SESSION['c_country']!="") )
		{
		?>
			<option value="">Select a Country</option>
			<?php
			$query1 = "SELECT country,code,visible FROM tbl_countries_promo WHERE visible='1' ORDER BY country ASC;";
			$countryresult= mysql_query($query1) or die('Query failed:<br /> ' . mysql_error());
			while ($line = mysql_fetch_array($countryresult, MYSQL_ASSOC)) {
				if($line['code']==$_SESSION['c_country'])
				{
			?>
					<option selected value="<?php Escaped_Echo ( $line['code']);?>"> <?php Escaped_Echo($line['country'])?></option>
			<?php
				}
				else
				{
			?>
					<option value="<?php Escaped_Echo($line['code']);?>"> <?php Escaped_Echo($line['country'])?></option>
			<?php
				}
			}
			mysql_free_result($countryresult);
			?>
		<?php
		}
		else
		{
		?>
			<option selected value="">Select a Country</option>
			<?php
			$query1 = "SELECT country,code,visible FROM tbl_countries_promo WHERE visible='1' ORDER BY country ASC;";
			$countryresult= mysql_query($query1) or die('Query failed:<br /> ' . mysql_error());
			while ($line = mysql_fetch_array($countryresult, MYSQL_ASSOC)) {
			?>	
			<option value="<?php Escaped_Echo ($line['code']);?>"> <?php Escaped_Echo ($line['country'])?></option>
			<?php
			}
			mysql_free_result($countryresult);
			?>
		<?php
		}
		?>
		</select>
		
		<?php 
		if (isset($_GET['billcountry']) == true)
		{
		?>
			<span class="error">
				<?php Escaped_Echo("Select a Country");?>
			</span>
		<?php
		} */
		?>
		-->
		<br />
		<label for="phone">*Telephone: (Do not include spaces or other characters)</label>
		<?php 
		if (isset($_GET['billphone']) == true) 
		{
		?>
			<span class="error">
				<?php Escaped_Echo("Phone is a Required Field");?>
			</span>
		<?php
		} 
		?>
		<?php 
		if (isset($_GET['valphone']) == true) 
		{ 
		?>
		<span class="error">
			<?php Escaped_Echo ("Phone number must be only numbers with no spaces or other characters")?>;
		</span>
		<?php
		} 
		?>
		<br />
		<input type="text" size="14" maxlength="14" id="phone" name="phone" value="<?php Escaped_Echo($_SESSION['c_phone']) ?>" />
		<br />
		<label for="email">*Email:</label>
		<?php 
		if (isset($_GET['billemail']) == true) 
		{
		?>
			<span class="error">
				<?php Escaped_Echo( "Email address is a Required Field");?>
			</span>
		<?php
		} 
		?>
		<?php 
		if (isset($_GET['valemail']) == true) 
		{ 
		?>
			<span class="error">
				<?php Escaped_Echo ("Invalid Email Address");?>
			</span>
		<?php
		} 
		?>
		<br />
		<input size="40" type="text" id="email" maxlength="50" name="email" value="<?php Escaped_Echo ($_SESSION['c_email']) ?>" />
		<br />
		
		<input type="hidden" name="same" value="true"  />
	
</p>
		
</div>

	<div class="checkout_box full">
	<h3>Email / Mail Prefrences</h3>
	<p>
	<label for="dpemail">Ancestry* may contact you by email with updates, special offers and other information about Ancestry related products and services.  By providing us with your email address and clicking "continue" below, you consent to being contacted by email.  If you do not want to receive marketing information from Ancestry* by email, please un-tick the box:</label><input class="shadow" type="checkbox" name="dpemail" id="dpemail" value="on" checked />
	<br /><br />
	<label for="dprent">Carefully selected partners and/or suppliers of Ancestry* may contact you by email about family history and related products and services, special offers and promotions.  If you consent to receiving these emails, please tick the box</label><input class="shadow" type="checkbox" name="dprent" value="on" />
	<br /><br />
	<label for="dpwhsmith">WHSmith may contact you by email with updates, special offers and other information about WHSmith related products and services. Please tick the box if you would like to receive such offers</label><input class="shadow" type="checkbox" name="dpwhsmith" value="on" />
	<br /><br />
	*Ancestry means The Generations Network, Inc, The Generations Network Ltd and The Generations Network (Commerce) Ltd
	</p>
	</div>
	 	
	<div class="checkout_box full">
		<h3>Accept Terms and Conditions</h3>
		<p>
		Before placing an order you should read and understand our <a href="tcs.php" target="_new">Terms and Conditions</a>.  If we accept your order then a binding agreement will come into existence on our Terms and Conditions.  Only tick the box if you wish to be bound by our Terms and Conditions
		<br />
		<input type="checkbox" name="tcs" value="on" /><label for="tcs">I accept the Terms and Conditions</label>
		<?php
		if(isset($_GET['error']))
		{
			$clean['error']=form_clean($_GET['error'],10);
			if($clean['error']=='tcs')
			{
			?>
				<br />
				<span class="error">You must accept the Terms and Conditions in order to place your order</span>
			<?php
			}
		}		
		?>
		</p>
		<p>
			<strong></strong>
		</p>
	</div>
	
	<div id="checkout_nav">
			<input id="continue_button" class="inputbutton" type="image" src="images/continue.gif" alt="Continue to the Next Step" /> 
	</div>	
	</form>
	



<?php
// Closing connection
	mysql_close($link);

?>

	

<?php include ('include/footer.php');?>

<?php
}
else
{
	$page = $_SERVER['PHP_SELF'];
	$page = str_replace(" ","%20",$page);
	
	$page =  "https://" . $_SERVER['HTTP_HOST'] . $page ;	

	
	$page = "Location:" . $page;
	
	//echo "\n" . $page;
	//echo $page2;
	header($page);
	
}
?>