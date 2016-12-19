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
	$mysql['c_country']=mysql_real_escape_string($_SESSION['c_country'],$link);
	$mysql['cs_country']=mysql_real_escape_string($_SESSION['cs_country'],$link);
	
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
	
	if ( $CountryTypeReturn == "USA" )
	{
		//country is USA
		$query = "SELECT code, description, price FROM tbl_shipping WHERE code='14' OR code='15';";
		
		$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
	}
	
	if ( $CountryTypeReturn== "UK" )
	{
		//country is in UK
		$Where="";
		
		switch(ShippingRate(session_id()))
		{
			case 1:
				$Where="WHERE code='7'";
			break;
			case 2:
				$Where="WHERE code='8'";
			break;
			case 3:
				$Where="WHERE code='9'";
			break;
			default:
				$Where="WHERE code='1' OR code='3'";
			break;		
				
		}
		
		//$query = "SELECT code, description, price FROM tbl_shipping WHERE code='1' OR code='2';";
		$query = "SELECT code, description, price FROM tbl_shipping $Where";
		$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
	
			
		
		
	}	
	if ( $CountryTypeReturn == "ROW" )
		{
			//country is in Rest of World
			$query = "SELECT code, description, price FROM tbl_shipping WHERE code='6' ;";
			$countryresult = mysql_query($query) or die('Query failed: ' . mysql_error());
			
			$num=mysql_num_rows($countryresult);
			
	}
	if ( $CountryTypeReturn == "ROE" )
		{
			//country is in Rest of Europe
			$query = "SELECT code, description, price FROM tbl_shipping WHERE code='6' OR code='7' OR code='8';";
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
	// Closing connection
	mysql_close($link);
	
	
?>
<?php $site_section="Checkout Step 2"?>
<?php  include ('include/header.php'); ?>


<?php include "include/checkout_header.php"?>
<h2>Confirm Your Details</h2>
<form action="checkout2.php" method="post">

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
<div id="checkout_nav">
	<a href="checkout.php"> <img id="back_button" src="images/back.gif" alt="Back" /></a>	
	<a href="checkout1b.php"><img id="continue_button" src="images/continue.gif" alt="Continue" /></a>
</div>
</form>


	


<?php include ('include/footer.php');?>



