<?php 
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
<div id="terms_modal">
<h2 class="section_header section_acc">Order Details</h2>
<div class="fieldset_section"> 
<p>Please check your order details carefully. Due to the personalised nature of this service, we cannot offer refunds. If the information is not correct, <span id="back_action">click your browsers back button and correct the details</span> before proceeding.</p>
<ul id="order_summary_list">
	<?php 
	$summaryFields = array(
		'Birth'=>array(
			'copies'=>'No of copies',
			'birth_surname'=>'Surname at birth',
			'forenames'=>'Forename(s) at birth',
			'birth_reg_year'=>'Year registered',
			'DOB'=>'Date of birth',
			'birth_place'=>'Birth place',
			'fathers_surname'=>"Father's Surname",
			'fathers_forenames'=>"Father's forename(s)",
			'mothers_maiden_surname'=>"Mother's maiden surname",
			'mothers_surname_birth'=>"Mother's surname at birth",
			'mothers_forenames'=>"Mother's forename(s)",
			'GRO_index_year'=>'Index year',
			'GRO_index_quarter'=>'Quarter',
			'GRO_index_district'=>'District',
			'GRO_index_volume'=>'Volume',
			'GRO_index_page'=>'Page',
			'GRO_index_reg'=>'Index',
			'GRO_reg_no'=>'Reg no',
			'GRO_entry_no'=>'Entry no',
			'GRO_district_no'=>'District no',
		),
		'Marriage'=>array(
			'copies'=>'No of copies',			
			'mans_surname'=>"Man's surname",
			'mans_forenames'=>"Man's forename(s)",
			'womans_surname'=>"Woman's surname",
			'womans_forenames'=>"Woman's forename(s)",
			'registered_year'=>'Year registred',
			'GRO_index_year'=>'Index year',
			'GRO_index_quarter'=>'Quarter',
			'GRO_index_district'=>'District',
			'GRO_index_volume'=>'Volume',
			'GRO_index_page'=>'Page',
			'GRO_index_reg'=>'Index',			
			'GRO_reg_no'=>'Reg no',
			'GRO_entry_no'=>'Entry no',
			'GRO_district_no'=>'District no',
		),
		'Death'=>array(
			'copies'=>'No of copies',
			'surname_deceased'=>'Surname of deceased',
			'forenames_deceased'=>'Forename(s) of deceased',
			'death_date'=>'Date of death',
			'registered_year'=>'Year registered',
			'relationship_to_deceased'=>'Relationship to deceased',
			'death_place'=>'Place of death',
			'death_age'=>'Age at death',
			'fathers_surname'=>"Father's surname",
			'fathers_forenames'=>"Father's forename(s)",
			'mothers_surname'=>"Mother's surname",
			'mothers_forenames'=>"Mother's forename(s)",
			'GRO_index_year'=>'Index year',
			'GRO_index_quarter'=>'Quarter',
			'GRO_index_district'=>'District',
			'GRO_index_volume'=>'Volume',
			'GRO_index_page'=>'Page',
			'GRO_index_reg'=>'Index',
			'GRO_reg_no'=>'Reg no',
			'GRO_entry_no'=>'Entry no',
			'GRO_district_no'=>'District no',
		)
	);
	$list = '';
	foreach($summaryFields[$certTypes[$recordType]] as $k => $v){
		if($order[$order['cert_table']][$k]) $list .= "<li>{$v}: {$order[$order['cert_table']][$k]}</li>";
	}	
	?>
	<li><strong><?php echo $certTypes[$recordType];?> Certificate</strong></li>
	<?php echo $list;?>
</ul>

</div><!--end fieldset_section-->

<h3 class="info">Terms and Conditions</h3>
<div class="info_box"> 
	<!--<form id="tc_form" method="post" action="test_post.php">-->
	<form id="tc_form" method="post" action="https://securetrading.net/authorize/form.cgi" onsubmit="javascript:pageTracker._linkByPost(this)">
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
	<input type="hidden" name="formref" value="6" />
    <?php //the following are fields for Tagman?>
    <input name="tm_prodrefs" value="<?php echo strtoupper($certTypes[$recordType]) . 'CERT';?>" type="hidden">
    <input name="tm_prodprices" value="<?php echo number_format(($stAmount /100) / $order['no_of_certs'], 2);?>" type="hidden">
    <input name="tm_total" value="<?php echo $stAmount /100;?>" type="hidden">
     <?php 
	if(SECURE_TRADING_ACCOUNT=='testelmbank9808')
		echo "<strong>TEST SECURE TRADING ACCOUNT IS BEING USED</strong>";
	?>


	<div class="button">
		<input type="submit" value="Continue" id="terms_submit" class="noval img_btn <?php if($editset) echo 'btn_disabled';?>" <?php if($editset) echo 'disabled=\"disabled\"';?>/>
	</div>
	</form>
</div><!--end .info_box-->
<div class="info_box_btm"></div>
</div><!--end #terms_modal-->

</div><!--end #content_wrapper-->
<div id="page_footer"></div>
<?php require_once('include/edgeward/help_text.inc.php'); ?>

<?php require_once('include/footer.php'); ?>