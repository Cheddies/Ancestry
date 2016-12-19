<?php 
//check to see if the page is accessed via HTTPS
include_once('include/check_https.inc.php');
require_once('include/edgeward/process_bmd.inc.php');
//IL Addition
require_once('include/commonfunctions.php');
require_once('include/siteconstants.php');
//Override token and store agaisnt order in db
$token = updateSessionToken();
?>
<?php require_once('include/edgeward/new_bmd/header.inc.php'); ?>
<!-- Google code has to come before form with javascript call in -->
<?php include('include/edgeward/google_analytics.inc.php');?>
<!-- End of addition by IL -->
<div id="content_top"></div>
<div id="content_wrapper" class="layout2 new_bmd">

<ul id="progress_bar" class="one_page">
<li>Order &amp; Address Details</li>
<li>Payment</li>
</ul>

<?php echo $alertFlash;?>

<form method="post" id="cert_details_form" class="shop_form" action="update_order.php">
<input type="hidden" name="token" value="<?php echo $token;?>"/>
<input type="hidden" id="country_data" name="country_data" value="<?php echo $country_data;?>"/>
<input type="hidden" id="record_type" name="record_type" value="<?php echo $recordType;?>"/>

<h2 class="section_header section_acc">Order Details</h2>
<div class="fieldset_section"> 
<div class="col_a">
	
	<fieldset class="info">
		<h2>1. Particulars of the person whose <?php echo $certTypes[$recordType];?> Certificate is required</h2>
		<a class="help help_legend" id="help_particulars" title="Particulars Help" href="cert_help.php#particulars">?</a>
		<?php require_once('include/edgeward/fields_parts.inc.php'); ?>	
		
	
		
	</fieldset>
		
		
	
	<fieldset class="info">
		
		<h2 id="acc_ref_info">2. Reference Information from GRO Index</h2>
		<a class="help help_legend" id="help_gro_ref" title="GRO reference information Help" href="cert_help.php#gro">?</a>
		<?php require_once('include/edgeward/fields_gro.inc.php'); ?>	
		
	
	</fieldset>
	
	
	
	
</div><!--end .col_a-->

<div class="col_b">
	<div class="del_option_top"></div>
	<div class="del_option"> 
		<h2>3. Order Options</h2>
		<fieldset class="no_box">
			<?php echo formInput('text','no_of_certs','no_of_certs','Number of certificates',$order['no_of_certs'],array('attr'=>array('maxlangth'=>3,'size'=>3,'class'=>'del_field numField'),'disabled'=>$disableDel,'required'=>!$disableDel,'help'=>'cert_help.php#qty'));?>
			<?php echo $serviceNotes;?>
		</fieldset>   
		
		<?php require_once('include/edgeward/new_bmd/disc_code.inc.php'); ?> 
		
		<?php require_once('include/edgeward/new_bmd/scan_send.inc.php'); ?> 
		
		<?php require_once('include/edgeward/new_bmd/delivery_options.inc.php'); ?>     
		
		
	</div><!--end .del_option-->
	<div class="del_option_bottom"></div>
	
	<?php echo getCertInfo(strtolower($certTypes[$recordType]));?>
	
</div><!--end .col_b--> 
</div><!--end #summary_cert_detail-->

<h2 class="section_header section_acc">Address Details</h2>
<div class="fieldset_section"> 
	<div id="del_add_fields" class="add_fields">
		<h2>4. Delivery Address</h2>
		<a class="help help_legend" id="help_del_add" title="Delivery Address Help" href="cert_help.php#del_address">?</a>
		<?php require_once('include/edgeward/new_bmd/fields_address.inc.php'); ?>
			
		
	</div>
	
	
	<div id="bil_add_fields" class="add_fields">
		<h2>5. Billing Address</h2>
		<a class="help help_legend" id="help_bill_add" title="Billing Address Help" href="cert_help.php#billing_address">?</a>
		
		<?php require_once('include/edgeward/new_bmd/fields_b_address.inc.php'); ?>
	
	</div>
</div><!--end fieldset_section-->

<div class="button">
<input type="submit" value="Continue" id="d3_form_submit" class="noval img_btn <?php if($editset) echo 'btn_disabled';?>" <?php if($editset) echo 'disabled=\"disabled\"';?>/>
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

</form><!-- end of form-->

<p class="small">95%* of our customers confirm that they are satisfied with our service and would recommend it to others and we hope you will also. So that you can make an informed decision when ordering, please be aware that there are other certificate ordering services available and that costs do vary. (* Customer research Oct 2009)</p>


</div><!--end #content_wrapper-->
<div id="page_footer"></div>
<?php require_once('include/edgeward/help_text.inc.php'); ?>

<?php require_once('include/footer.php');?>