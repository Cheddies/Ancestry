<?php include("include/siteconstants.php"); ?>
<?php include("include/commonfunctions.php"); ?>
<?php
	session_set_cookie_params ( 0,"/." , "", true);
	
	if ( (isset($_SESSION['c_country']))    && ($_SESSION['c_country']!="") &&
	     (isset($_SESSION['cs_country']))   && ($_SESSION['cs_country']!="") &&
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
	     (isset($_SESSION['c_phone'])) && ($_SESSION['c_phone']!="")
	     
	   )
	{
		//all variables that are allways needed are set
		
		//check email is valid
		if ( (validateemail($_SESSION['c_email']) == false) || (is_numeric($_SESSION['c_phone'])== FALSE) )
		{
			header('Location: checkout.php');
			exit();
		}
		
		//check to make sure state is set if needed
		//if country is equal to US or Canada then state is required
		
		//billing country
		if ( CountryType($_SESSION['c_country'])=="USA")
		{
		
			if ( (isset($_SESSION['c_state'])) && ($_SESSION['c_state']!=""))
			{
				//state is set so OK
			}
			else
			{
				//state is not set so go back
				header('Location: checkout.php');
				exit();
			}
		}
		else
		{
		//set the State to blank as it is not needed
			$_SESSION['c_state']="";
		}
		//shipping country
		if ( CountryType($_SESSION['cs_country'])=="USA") 
		{
				
			if ( (isset($_SESSION['cs_state'])) && ($_SESSION['cs_state']!=""))
			{
				//state is set so OK
			}
			else
			{
				//state is not set so go back
				header('Location: checkout.php');
				exit();
			}
		}
		else
		{
			//set the State to blank as it is not needed
			$_SESSION['cs_state']="";
		}
		 

	}
	else
	{
		header('Location: checkout.php');
		exit();
	}	
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$mysql=array();
	$mysql['c_country']=mysql_real_escape_string($_SESSION['c_country']);
	$mysql['cs_country']=mysql_real_escape_string($_SESSION['cs_country']);
	
	//get name of bill country
	$query = "SELECT country from tbl_countries where code= '{$mysql['c_country']}'";
	$result= mysql_query($query) or die ('Query failed'. mysql_error());
	$BillCountry=mysql_fetch_row($result);
	$_SESSION['BillCountryName']=$BillCountry[0];
	unset($BillCountry);
	
	//get name of shipping countries
	$query = "SELECT country from tbl_countries where code= '{$mysql['cs_country']}'";
	$result= mysql_query($query) or die ('Query failed'. mysql_error());
	$ShipCountry=mysql_fetch_row($result);
	$_SESSION['ShipCountryName']=$ShipCountry[0];
	unset($ShipCountry);
	
	// Performing SQL query to get shipping details
	//only allow ones related to shipping country
	
	
	
	//If you change these checks, then you should 
	//change the CheckShipType function in common functions
	//to perform checks that reflect the changes made
	$CountryTypeReturn=CountryType($_SESSION['cs_country']);
	
	if ( $CountryTypeReturn == "ERROR" )
	{
	  	header('Location: checkout.php');
	  	exit();
	}
	
	$Special_shipping=SpecialShipping(session_id(),$_SESSION['cs_country']);
	
	if($Special_shipping!=false)
	{
		$query="SELECT code,description, price FROM tbl_shipping WHERE code='{$Special_shipping}'";
		$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
	}
	else
	{
		if ( $CountryTypeReturn == "USA" )
		{
			//country is USA
			$query = "SELECT code, description, price FROM tbl_shipping WHERE code='10' OR code='9';";
			
			$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
		}
		
		if ( $CountryTypeReturn== "UK" )
		{
		//country is in UK
			$Where="";
			
			switch(ShippingRate(session_id()))
			{
				case 1:
					$Where="WHERE code='7' OR code='3'";
				break;
				case 2:
					$Where="WHERE code='8' OR code='3'";
				break;
				case 3:
					$Where="WHERE code='9' OR code='3'";
				break;
				default:
					$Where="WHERE code='1' OR code='3'";
				break;		
					
			}
			
			//$query = "SELECT code, description, price FROM tbl_shipping WHERE code='1' OR code='2';";
			$query = "SELECT code, description, price FROM tbl_shipping $Where ORDER by price";
			$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
		
				
			
			
		}	
		if ( $CountryTypeReturn == "ROW" )
			{
				//country is in Rest of World
				$query = "SELECT code, description, price FROM tbl_shipping WHERE code='6';";
				$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
		}
		if ( $CountryTypeReturn == "ROE" )
			{
				//country is in Rest of Europe
				$query = "SELECT code, description, price FROM tbl_shipping WHERE code='4' OR code='5';";
				$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
		}
		if ( $CountryTypeReturn == "EMAIN" )
			{
				//country is in Europe Mainland
				$query = "SELECT code, description, price FROM tbl_shipping WHERE code='4' OR code='5';";
				$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
		}
		if ( $CountryTypeReturn == "OTHER" )
			{
				//country is other type
				$query = "SELECT code, description, price FROM tbl_shipping WHERE code='13';";
				$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
		}
		if($CountryTypeReturn =="GREECE")
		{
			$query = "SELECT code, description, price FROM tbl_shipping WHERE code='11';";
			$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
		}
	}
	// Closing connection
	mysql_close($link);
	
	
?>

<?php $site_section="Checkout Step 3"?>

<?php include ('include/header.php');?>

<?php include "include/checkout_header.php"?>
<h2>Shipping Method</h2>

<?php
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

<form action="checkout2.php" method="post">
<input type="hidden" name="token" value="<?php echo $token?>" />

<div class="checkout_box full">
<h3>Select Shipping Type</h3>
<p>
	<select name="shiptype">
					<?php
						while ($line = mysql_fetch_array($countryresult, MYSQL_ASSOC)) {
						
						if($line['price']!="0")
						
						{
						$Price=formatcurrency($line['price']);
						$Euro=ConvertToEuro($line['price']);
						$Dollar=ConvertToDollar($line['price']);
						
						}
						else
						{	
							$Price="&pound;Free";
							$Euro="&euro;Free";
							$Dollar="${$Free}";
							
						}
						if(PRICES && isset($_SESSION['alt']))
						{
						switch($_SESSION['alt'])
						{
						case DOLLAR:
						?>
						<option value="<?php Escaped_Echo( $line['code']);?>"><?php Escaped_Echo($line['description'] ." " . $Price. " (" .$Dollar.")* " ) ; ?></option>
						<?php
						break;
						case EURO:
						?>
						<option value="<?php Escaped_Echo($line['code']);?>"><?php Escaped_Echo($line['description'] ." " . $Price. " (" .$Euro.")* ") ; ?></option>
						<?php
						break;
						case NONE:
						?>
						<option value="<?php Escaped_Echo($line['code']);?>"><?php Escaped_Echo($line['description'] ." " . $Price); ?></option>
						<?php
						break;
				
						}
					
						}//end if prices
						else
						{
					?>
						<option value="<?php Escaped_Echo( $line['code']);?>"><?php Escaped_Echo( $line['description'] . " " . $Price. " " ); ?></option>
					<?php
					
						}
						
						}

						mysql_free_result($countryresult);
					?>
				</select>
	</p>
 </div>

 

<div class="checkout_box full">
<h3>Email / Mail Prefrences</h3>
<p>
<label for="dpemail">Ancestry* may contact you by email with updates, special offers and other information about Ancestry related products and services.  By providing us with your email address and clicking "continue" below, you consent to being contacted by email.  If you do not want to receive marketing information from Ancestry* by email, please un-tick the box:</label><input class="shadow" type="checkbox" name="dpemail" id="dpemail" value="on" checked />
<br /><br />
<label for="dprent">Carefully selected partners and/or suppliers of Ancestry* may contact you by email about family history and related products and services, special offers and promotions.  If you consent to receiving these emails, please tick the box</label><input class="shadow" type="checkbox" name="dprent" value="on" />
<br /><br />
*Ancestry means The Generations Network, Inc, The Generations Network Ltd and The Generations Network (Commerce) Ltd
</p>
</div>
 	
<div class="checkout_box full">
	<h3>Accept Terms and Conditions</h3>
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
</div>

<a href="checkout1a.php" ><img id="back_button" src="images/back.gif" alt="Back to the Last Step" title="Back to the Last Step" /></a>
<input  type="image" id="continue_button" src="images/continue.gif" alt="Continue to the Next Step" title="Continue to the Next Step" />


<br />
 

</form>




<?php include ('include/footer.php');?>



