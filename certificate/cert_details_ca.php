<?php
//IL addition
//check to see if the page is accessed via HTTPS
include_once('include/check_https.inc.php');
$formset = "details";
$pageSuffix = "_ca";
require_once('include/edgeward/process_bmd.inc.php');
?>
<?php require_once('include/edgeward/new_bmd/header.inc.php'); ?>
    <div id="content_top"></div>
    <div id="content_wrapper" class="cert_details layout2 new_bmd">
    
        <ul id="progress_bar">
        <li>Order Details</li>
        <li>Delivery Address</li>
        <li>Order Summary</li>
        <li>Payment</li>
        </ul>
		
		<?php echo $alertFlash;?>
		
		<form action="upd_cert.php" method="post" class="shop_form">
	        <input type="hidden" name="token" value="<?php echo $token;?>"/>
	        <input type="hidden" id="country_data" name="country_data" value="<?php echo $country_data;?>"/>
        
			<div class="col_a">

				<fieldset class="info">
					<h2>This is our Canada store<br><img src="images/CAN.jpg"><br>If you are not in Canada, please select your country by clicking on the correct flag below</h2>
					<a class="help help_legend" id="help_country_ref" title="Country Help" href="cert_help.php#country">?</a>
	<br>

	<a href="https://www.ancestryshop.co.uk/certificate/index_aus.php" target="_blank"><img src="images/AUS-med.jpg" alt="Australia" border="0"/></a>
	
	<a href="https://www.ancestryshop.co.uk/certificate/index.php" target="_blank"><img src="images/UK-med.jpg" alt="United Kingdom" border="0"/></a>

	<a href="https://www.ancestryshop.co.uk/certificate/index_us.php" target="_blank"><img src="images/USA-med.jpg" alt="United States of America" border="0"/></a>
				
				</fieldset>			
							
				<fieldset class="info">
					<h2>Particulars of the person whose <?php echo $certTypes[$recordType];?> Certificate is required</h2>
					<a class="help help_legend" id="help_particulars" title="Particulars Help" href="cert_help.php#particulars">?</a>
					<?php require_once('include/edgeward/fields_parts.inc.php'); ?>					
					
				</fieldset>
					
					
				
				<fieldset class="info">
					
					<h2 id="acc_ref_info">Reference Information from GRO Index</h2>
					<a class="help help_legend" id="help_gro_ref" title="GRO reference information Help" href="cert_help.php#gro">?</a>
					<?php require_once('include/edgeward/fields_gro.inc.php'); ?>				
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
									
					<?php require_once('include/edgeward/new_bmd/delivery_options.inc.php'); ?>     
					
					
					<!--<input type="image" src="images/continue_GRO.gif" alt="submit" />-->
					<div class="button">
						<input type="submit" value="Continue" class="noval img_btn"/>
					</div>
				</div><!--end .del_option-->
				<div class="del_option_bottom"></div>
				<?php echo getCertInfo(strtolower($certTypes[$recordType]));?>
        	</div><!--end .col_b-->        
        
        </form><!--end of form--> 
    	<p class="small">95%* of our customers confirm that they are satisfied with our service and would recommend it to others and we hope you will also. So that you can make an informed decision when ordering, please be aware that there are other certificate ordering services available and that costs do vary. (* Customer research Oct 2009)</p>
    
    </div><!--end #content_wrapper-->
    <div id="page_footer"></div>
    <?php require_once('include/edgeward/help_text.inc.php'); ?>


<?php require_once('include/footer' . $pageSuffix . '.php'); ?>