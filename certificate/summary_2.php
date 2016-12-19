<?php 
$formset = "summary";
require_once('include/edgeward/process_bmd.inc.php');
//IL Addition
require_once('include/commonfunctions.php');
require_once('include/siteconstants.php');
//Override token and store agaisnt order in db
$token = updateSessionToken();
//debug($_SESSION['cert']);
?>
<?php require_once('include/edgeward/new_bmd/header.inc.php'); ?>
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
<!-- End of addition by IL -->
<div id="content_top"></div>
<div id="content_wrapper" class="layout2 new_bmd">

<ul id="progress_bar" class="stage3">
<li>Order Details</li>
<li>Delivery Address</li>
<li>Order Summary</li>
<li>Payment</li>
</ul>

<form method="post" class="shop_form" action="<?php echo $formAction;?>">
<input type="hidden" name="token" value="<?php echo $token;?>"/>
<input type="hidden" name="noemail" class="add_field" value="<?php echo $order['orders']['noemail'];?>"/>
<input type="hidden" name="norent" class="add_field" value="<?php echo $order['orders']['norent'];?>"/>
<input type="hidden" id="country_data" name="country_data" value="<?php echo $country_data;?>"/>

<h2 class="section_header section_acc">Certificate Details</h2>
<div class="fieldset_section"> 
<div class="col_a">
	
	<fieldset class="info">
		<h2>Particulars of the person whose <?php echo $certTypes[$recordType];?> Certificate is required</h2>
		<a class="help help_legend" id="help_particulars" title="Particulars Help" href="cert_help.php#particulars">?</a>
		<?php require_once('include/edgeward/fields_parts.inc.php'); ?>	
		
		<div class="edit_btn_wrapper">
		<?php if($editset == 'par'){ //only visible in shop form
			echo "<input type=\"submit\" value=\"Save\" class=\"noval edit_btn\" name=\"btn\"/>";   	
	    } elseif(!$editset) {
			echo "<a href=\"edit_summary.php?edit=par\" class=\"edit\" id=\"edit_par\">Edit</a>";
	    }?>		
		</div>		
		
	</fieldset>
		
		
	
	<fieldset class="info">
		
		<h2 id="acc_ref_info">Reference Information from GRO Index</h2>
		<a class="help help_legend" id="help_gro_ref" title="GRO reference information Help" href="cert_help.php#gro">?</a>
		<?php require_once('include/edgeward/fields_gro.inc.php'); ?>	
		
		<div class="edit_btn_wrapper">
		<?php if($editset == 'gro'){ //only visible in shop form
			echo "<input type=\"submit\" value=\"Save\" class=\"noval edit_btn\" name=\"btn\"/>";   	
	    } elseif(!$editset) {
			echo "<a href=\"edit_summary.php?edit=gro\" class=\"edit\" id=\"edit_gro\">Edit</a>";
	    }?>	
		</div>		
	</fieldset>
	
	
	
	
</div><!--end .col_a-->

<div class="col_b">
	<div class="del_option_top"></div>
	<div class="del_option"> 
	
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
</div><!--end .fieldset_section-->

<div id="del_add_fields" class="add_fields">
	<h2 class="section_header section_acc">Delivery Address<a class="help help_legend" id="help_del_add" title="Delivery Address Help" href="cert_help.php#del_address">?</a></h2>
	
	<div class="fieldset_section"> 
	<?php require_once('include/edgeward/new_bmd/fields_address.inc.php'); ?>
	</div><!--end .fieldset_border-->	
	
</div>


<div id="bil_add_fields" class="add_fields">
	<h2 class="section_header">Billing Address<a class="help help_legend" id="help_bill_add" title="Billing Address Help" href="cert_help.php#billing_address">?</a></h2>
	
	<div class="fieldset_section"> 
	<?php require_once('include/edgeward/new_bmd/fields_b_address.inc.php'); ?>
	</div><!--end .fieldset_border-->
</div>


</form>


<form id="tc_form" method="post" action="https://securetrading.net/authorize/form.cgi" onsubmit="javascript:pageTracker._linkByPost(this)">

<h3 class="info">Terms and Conditions</h3>
<div class="info_box"> 
	<div class="input checktc">
		<label for="terms">Before placing an order you should read and understand our <a href="https://ancestryshop.co.uk/certificate/tcs.php" title="Terms and Conditions" target="_blank">Terms and Conditions</a>. If we accept your order then a binding agreement will come into existence on our <a href="https://ancestryshop.co.uk/certificate/tcs.php" title="Terms and Conditions" target="_blank">Terms and Conditions</a>.<br/>Before placing an order you agree that you will use the records you order for the purposes of your personal, family or household affairs (including recreational purposes) only and you appoint Ancestry.com UK (Commerce) Limited as your agent to obtain those records on your behalf. Where you appoint us to act as agent on your behalf, we agree to process and deliver your order using the information that you provide. Please be sure that the information supplied is both accurate and complete. We cannot accept any responsibility for false, inaccurate or incomplete information supplied. We can give no warranty or guarantee in respect of the goods ordered.</label>    
	</div>    

	

	<input type="hidden" name="st_sitesecurity" value="<?php echo $st_sitesecurity;?>" />
	<input name="gro" id="gro" value="true" type="hidden">
	<input name="token" id="token" value="<?php echo $token;?>" type="hidden">
	<input name="orderref" value="<?php echo $stOrderref;?>" type="hidden">
	<input name="orderinfo" value="AncestryShop.co.uk Online Order" type="hidden">
	<input name="name" value="<?php echo $stName;?>" type="hidden">
	<input name="company" value="" type="hidden">
	<input name="address" value="<?php echo $stAddress;?>" type="hidden">
	<input name="town" value="<?php echo $stTown;?>" type="hidden">
	<input name="county" value="<?php echo $stCounty;?>" type="hidden">
	<input name="country" value="<?php echo getCountryName($stCountry);?>" type="hidden">
	<input name="postcode" value="<?php echo $stPostcode;?>" type="hidden">
	<input name="telephone" value="<?php echo $stPhone;?>" type="hidden">
	<input name="fax" value="" type="hidden">
	<input name="email" value="<?php echo $stEmail;?>" type="hidden">
	<input name="url" value="" type="hidden">
	<input name="currency" value="gbp" type="hidden">
	<input name="requiredfields" value="name,email" type="hidden">
	<input name="merchant" value="<?php echo SECURE_TRADING_ACCOUNT?>" type="hidden">
	<input name="merchantemail" value="chris.gan@internetlogistics.com" type="hidden">
	<input name="customeremail" value="1" type="hidden">
	<input name="settlementday" value="1" type="hidden">
	<input name="callbackurl" value="1" type="hidden">
	<input name="session_id" value="<?php echo $stSession;?>" type="hidden">
	<input name="cj_tracking" value="https://www.ancestryshop.co.uk/images/blank.gif" type="hidden">
	<input name="ba_tracking" value="https://www.ancestryshop.co.uk/images/blank.gif" type="hidden">
	<input name="amount" value="<?php echo $stAmount;?>" type="hidden">
	<input type="hidden" name="formref" value="3" />
    <?php //the following are fields for Tagman?>
    <input name="tm_itemrefs" value="<?php echo strtoupper($certTypes[$recordType]) . 'CERT';?>" type="hidden">
    <input name="tm_itemprices" value="<?php echo number_format(($stAmount /100) / $order['no_of_certs'], 2);?>" type="hidden">
    <input name="tm_total" value="<?php echo $stAmount /100;?>" type="hidden">
     <?php 
	if(SECURE_TRADING_ACCOUNT=='testelmbank9808')
		echo "<strong>TEST SECURE TRADING ACCOUNT IS BEING USED</strong>";
	?>


<div class="button">
<input type="submit" value="Continue" id="form_submit" class="noval img_btn <?php if($editset) echo 'btn_disabled';?>" <?php if($editset) echo 'disabled=\"disabled\"';?>/>
</div>
</form>
</div><!--end .info_box-->
<div class="info_box_btm"></div>



</div><!--end #content_wrapper-->
<div id="page_footer"></div>
<?php require_once('include/edgeward/help_text.inc.php'); ?>

<?php require_once('include/footer.php'); ?>