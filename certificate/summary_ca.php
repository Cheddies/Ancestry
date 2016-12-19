<?php 
$formset = "summary";
$pageSuffix = "_ca";
require_once('include/edgeward/process_bmd.inc.php');
//IL Addition
require_once('include/commonfunctions.php');
require_once('include/siteconstants.php');
//Override token and store agaisnt order in db
$token = updateSessionToken();
//debug($_SESSION['cert']);

//this needs to come before the include for ST where the Security hash is done
//this needs to change for the different GEOs
$currencyiso3a='CAD';

//defines constants for ST and works out the security hash
//should allways come after the settings of the currencyiso3a field as it is used in the hash
require_once("../include/new_st_config.php");
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

<?php include ("include/new_st_form_bmd.php")?>

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