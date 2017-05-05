<pre>
<?php
//var_dump($order);
?>
</pre>
<form id="tc_form" action="https://payments.securetrading.net/process/payments/choice" onsubmit="javascript:pageTracker._linkByPost(this)">
<h3 class="info">Terms and Conditions</h3>
<div class="info_box"> 
	<div class="input checktc">
		<label for="terms">Before placing an order you should read and understand our <a href="https://ancestryshop.co.uk/tcs.php" title="Terms and Conditions" target="_blank">Terms and Conditions</a>. If we accept your order then a binding agreement will come into existence on our <a href="https://ancestryshop.co.uk/tcs.php" title="Terms and Conditions" target="_blank">Terms and Conditions</a>.<br/>Before placing an order you agree that you will use the records you order for the purposes of your personal, family or household affairs (including recreational purposes) only and you appoint Ancestry.com UK (Commerce) Limited as your agent to obtain those records on your behalf. Where you appoint us to act as agent on your behalf, we agree to process and deliver your order using the information that you provide. Please be sure that the information supplied is both accurate and complete. We cannot accept any responsibility for false, inaccurate or incomplete information supplied. We can give no warranty or guarantee in respect of the goods ordered.</label>    
	</div>  
<?php
	if($sitereference=='test_elmancestry32377')
		echo "<strong>TEST SECURE TRADING ACCOUNT IS BEING USED</strong>"; 
	?>
	<?php //new ST?>
	<input type="hidden" name="sitereference" value="<?php echo $sitereference?>"> <?php /* should be dynamic */?>
	<?php if(isset($locale)){?>
	<input type="hidden" name="locale" value="<?php echo $locale?>" >
	<?php }?>
<input type="hidden" name="currencyiso3a" value="<?php echo $currencyiso3a ?>">
<input type="hidden" name="mainamount" value="<?php echo $orderTotal?>"> <?php /* dosn't need to be in pence for new ST */?>
<input type="hidden" name="version" value="1">
<input type="hidden" name="billingpremise" value="<?php echo $order['billing_address']['line_1']?>" >
<input type="hidden" name="billingstreet" value="<?php echo $order['billing_address']['line_2']?>" >
<input type="hidden" name="billingtown" value="<?php echo $stTown ?>" >
<input type="hidden" name="billingcounty" value="<?php echo $stCounty?>" >
<input type="hidden" name="billingpostcode" value="<?php echo $stPostcode?>" >

<input type="hidden" name="billingprefixname" value="" >
<input type="hidden" name="billingfirstname" value="<?php echo $order['billing_address']['first_name']?>" >
<input type="hidden" name="billinglastname" value="<?php echo  $order['billing_address']['surname']?>" >
<input type="hidden" name="billingcountryiso2a" value="<?php echo $iso_countries[(string)$order['billing_address']['country']]?>" > <?php /* needs to be iso2a so will need conversion */?>
<input type="hidden" name="billingemail" value="<?php echo $stEmail ?>" >
<input type="hidden" name="billingtelephone" value="<?php echo $stPhone ?>" >
<input type="hidden" name="billingtelephonetype" value="H" ><?php /*required if sending telephone , will just have to set to H for home */?>
<?php /*shipping details*/?>
<input type="hidden" name="customerpremise" value="<?php echo $order['address']['line_1']?>" >
<input type="hidden" name="customerstreet" value="<?php echo $order['address']['line_2']?>" >
<input type="hidden" name="customertown" value="<?php echo $order['address']['city']?>" >
<input type="hidden" name="customercounty" value="<?php echo $order['address']['county']?>" > <?php /*we don't store county*/?>
<input type="hidden" name="customerpostcode" value="<?php echo $order['address']['postcode']?>" >


<input type="hidden" name="customercountryiso2a" value="<?php echo $iso_countries[(string)$order['address']['country']]?>" > <?php /* needs to be iso2a so will need conversion */?>
<input type="hidden" name="customeremail" value="<?php echo $stEmail ?>" >
<input type="hidden" name="customertelephone" value="<?php echo $stPhone ?>" >
<input type="hidden" name="customertelephonetype" value="H" ><?php /*required if sending telephone , will just have to set to H for home */?>
<input name="orderreference" value="<?php echo $stOrderref;?>" type="hidden">
<input type="hidden" name="childcss" value="child" />
<input type="hidden" name="sitesecurity" value="<?php echo $sitesecurity?>" />

<input name="session_id" value="<?php echo $stSession;?>" type="hidden">
<?php 
/*
<!-- Custom Fields -->

<!-- to get these returned in POST need to contact ST Support -->
<!-- may not need them, but will need some custom things returning -->

Session id most important

Seems to be just a case of adding these into the desination setup in notifications
*/
?>
<input name="gro" id="gro" value="true" type="hidden">
<input name="cj_tracking" value="https://www.ancestryshop.co.uk/images/blank.gif" type="hidden">
<input name="ba_tracking" value="https://www.ancestryshop.co.uk/images/blank.gif" type="hidden">

<?php /*** Tagman Fields ***/?>
<?php 
/*Tagman fields are all set for the certificate pages currently
fields renamed below to match with main checkout
*/
?>
<input name="tm_prodrefs" value="<?php echo strtoupper($certTypes[$recordType]) . 'CERT';?>" type="hidden">
<input name="tm_prodprices" value="<?php echo number_format(($stAmount /100) / $order['no_of_certs'], 2);?>" type="hidden">
<input name="tm_total" value="<?php echo $stAmount /100;?>" type="hidden">
<?php //end of new ST?>