<?php 
//IL addition
//check to see if the page is accessed via HTTPS
if  ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') 
	   || ($_SERVER['HTTP_HOST']=="10.0.0.3" ) 
	   || ($_SERVER['HTTP_HOST']=="212.38.95.165") 
	   || ($_SERVER['HTTP_HOST']=="10.0.0.136" ) 
	   || ($_SERVER['HTTP_HOST']=="78.31.106.192") 
	   || ($_SERVER['HTTP_HOST']=="ancestry.internetlogistics.com")
	   ) 
{
?>
<?php 
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
        
      <div class="border_top"></div>
        <div class="fieldset_border"> 
        <p><strong>Data Protection Notice for Ancestry Shop</strong><br/>
        The Generations Network (Commerce) Limited (registration number Z1407756) and The Generations Network, Inc are Data Controllers for the purposes of the Data Protection Act 1998. We may use your personal information for marketing purposes and to facilitate payment and fulfillment for membership and purchases at the Ancestry Shop. We may share your personal information with carefully selected third parties for the purposes of processing and fulfilling your order.<br/>
        You consent to our transferring your information to countries or jurisdictions which do not provide the same level of data protection as the UK, if necessary for the above purposes.<br/>
        For further details of our information gathering and dissemination practices please see our <a href="https://www.ancestryshop.co.uk/privacy_policy.php" title="Privicy Statement">PRIVACY STATEMENT</a>.</p>
        </div><!--end .fieldset_border-->
      <div class="border_bottom"></div>
        
        <form action="upd_address.php" method="post" class="shop_form">
        <input type="hidden" name="formset" value="address"/>
        <input type="hidden" name="token" value="<?php echo $token; ?>" />
        <div class="fieldset">
        <a class="help help_legend" id="help_del_add" title="Delivery Address Help" href="shop_help.php#delivery_address">?</a>
        <fieldset class="box">
            <span class="legend legend_top">Delivery Address</span>
            <div class="fieldset_bg">
            <?php require_once('include/edgeward/fields_address.inc.php'); ?>
            </div><!--end .fieldset_bg-->
        </fieldset>
        </div>
        <div class="fieldset accordion">
        <a class="help help_legend" id="help_bill_add" title="Billing Address Help" href="shop_help.php#billing_address">?</a>
        <fieldset class="box">
        
            <span class="legend legend_mid" id="acc_billing_add">Billing Address (if different from above)</span>
            <div class="fieldset_bg">
            <?php require_once('include/edgeward/fields_b_address.inc.php'); ?>
            </div><!--end .fieldset_bg-->
        </fieldset>
        </div>
        
        <div class="border_top"></div>
        <div class="fieldset_border"> 
        
        <div class="input checktc">
        <label for="tc1">Ancestry may contact you by email with updates, special offers and other information about Ancestry related products and services. By providing us with your email address and clicking 'continue' below, you consent to being contacted by email. If you do not want to receive marketing information from Ancestry by email, please un-tick this box.</label>    
        <input name="noemail" type="checkbox" id="noemail" checked="checked" class="noval" value="1"/>
        </div>
        <div class="input checktc">
        <label for="tc2">Carefully selected partners and/or suppliers of Ancestry* may contact you by email about family history and related products and services, special offers and promotions.<br/>If you consent to receiving these emails, please tick the box.</label>    
        <input name="norent" type="checkbox" id="norent" class="noval" value="1"/>
        </div>
        
        <div class="button">
        <input type="submit" value="Continue" class="noval img_btn" name="btn"/>
        </div>
        </div><!--end .fieldset_border-->
        <div class="border_bottom"></div>
        </form>
    </div><!--end #content_wrapper-->
    <div id="page_footer"></div>
    <div class="helptext" id="helptext_del_add"><p class="top">This is the address that you'd like the certificate delivered to.<br/>All fields marked with <strong>*</strong> are required.</p></div>
    <div class="helptext" id="helptext_bill_add"><p class="top">Complete the billing address section if your billing and delivery addresses are different.<br/>All fields marked with <strong>*</strong> are required.</p></div>
<?php require_once('include/footer.php'); ?>
<?php 
//IL Addition
}
else
{
	$page = $_SERVER['PHP_SELF'];
	$page = str_replace(" ","%20",$page);
	
	$page =  "https://" . $_SERVER['HTTP_HOST'] . $page ;	

	
	$page = "Location:" . $page;
	
	//echo "\n" . $page;
	//echo $page2;
	header($page);
	
}
?>
