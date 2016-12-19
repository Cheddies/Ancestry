<?php 
//IL addition
//check to see if the page is accessed via HTTPS
include('include/check_https.inc.php');
//set formtype
$formtype = 'delivery';
require_once('include/edgeward/process_cart.inc.php');
//IL addition
require_once('include/commonfunctions.php');
require_once('include/siteconstants.php');
//end of IL addition
//enable the address fields
$disableAddress = false;

$pageTitle = 'Ancestry Shopping Basket | Delivery Address';
?>
<?php require_once('include/edgeward/header.inc.php'); ?>
    <div id="content_top"></div>
    <div id="content_wrapper">
    
        <ul id="progress_bar" class="stage2">
        <li>Order Details</li>
        <li>Delivery Address</li>
        <li>Order Summary</li>
        <li>Payment</li>
        </ul>
        
        
        <form action="upd_address.php" method="post" class="shop_form">
        <input type="hidden" name="formset" value="address"/>
        <input type="hidden" name="token" value="<?php echo $token; ?>" />
       
       
        <div id="del_add_fields" class="add_fields">
	<h2>Delivery Address</h2>
        <a class="help help_legend" id="help_del_add" title="Delivery Address Help" href="shop_help.php#delivery_address">?</a>
             <?php require_once('include/edgeward/fields_address.inc.php'); ?>
            </div><!--end .fieldset_bg-->
        
       <div id="bil_add_fields" class="add_fields">
	<h2>Billing Address</h2>
        <a class="help help_legend" id="help_bill_add" title="Billing Address Help" href="shop_help.php#billing_address">?</a>
       
            <?php require_once('include/edgeward/fields_b_address.inc.php'); ?>
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
    <div class="helptext" id="helptext_del_add"><p class="top">This is the address that you'd like the certificate delivered to.<br/>All fields marked with <strong>*</strong> are required.</p></div>
    <div class="helptext" id="helptext_bill_add"><p class="top">Complete the billing address section if your billing and delivery addresses are different.<br/>All fields marked with <strong>*</strong> are required.</p></div>
<?php require_once('include/footer_basket.php');
require_once('include/footer.php'); ?>
