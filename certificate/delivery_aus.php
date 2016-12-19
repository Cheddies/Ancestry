<?php 
$formset = "address";
require_once('include/edgeward/process_bmd.inc.php');
$pageSuffix = "_aus";
//enable the address fields
$disableAddress = false;
?>
<?php require_once('include/edgeward/new_bmd/header.inc.php'); ?>
<div id="content_top"></div>
<div id="content_wrapper" class="layout2 new_bmd">

<ul id="progress_bar" class="stage2">
<li>Order Details</li>
<li>Delivery Address</li>
<li>Order Summary</li>
<li>Payment</li>
</ul>

<?php if($alertFlash) echo $alertFlash;?>

<form action="upd_address.php" method="post" class="shop_form">
<input type="hidden" name="token" value="<?php echo $token;?>"/>
<input type="hidden" id="country_data" name="country_data" value="<?php echo $country_data;?>"/>


<div id="del_add_fields" class="add_fields">
	<h2>Delivery Address</h2>
	<a class="help help_legend" id="help_del_add" title="Delivery Address Help" href="cert_help.php#del_address">?</a>
		<?php require_once('include/edgeward/new_bmd/fields_address.inc.php'); ?>

</div>


<div id="bil_add_fields" class="add_fields">
	<h2>Billing Address</h2>
	<a class="help help_legend" id="help_bill_add" title="Billing Address Help" href="cert_help.php#billing_address">?</a>
		<?php require_once('include/edgeward/new_bmd/fields_b_address.inc.php'); ?>

</div>

<div class="button">
<input type="submit" value="Continue" class="noval img_btn" name="btn"/>
</div>

<div class="fieldset_border"> 

<div class="input checktc">
<label for="tc1">Ancestry may contact you by email with updates, special offers and other information about Ancestry related products and services. By providing us with your email address and clicking 'continue' below, you consent to being contacted by email. If you do not want to receive marketing information from Ancestry by email, please un-tick this box.</label>    
<input name="noemail" type="checkbox" id="noemail" checked="checked" class="noval" value="1"/>
</div>
<div class="input checktc">
<label for="tc2">Carefully selected partners and/or suppliers of Ancestry may contact you by email about family history and related products and services, special offers and promotions.<br/>
If you consent to receiving these emails, please tick the box.<br />
For full details of our privacy practices please see our <a href="https://www.ancestryshop.co.uk/privacy_policy.php" title="Privicy Statement" target="_blank">PRIVACY STATEMENT</a></label> 
<input name="norent" type="checkbox" id="norent" class="noval" value="1"/>
</div>


</div><!--end .fieldset_border-->



</form>

</div><!--end #content_wrapper-->
<div id="page_footer"></div>
<?php require_once('include/edgeward/help_text.inc.php'); ?>

<?php require_once('include/footer_aus.php'); ?>
