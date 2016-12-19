<?php
//summary form, removes several elements from page that arent required
//also saves session cart to db at this point (saved by process_cart.php)
$formtype = 'summary';
require_once('include/edgeward/process_cart.inc.php');

//IL Addition
require_once('include/commonfunctions.php');
require_once('include/siteconstants.php');
//see form fields below for output
$tracking_urls= get_tracking_code(session_id());
//override token and store agaisnt order
$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

$mysql=array();
$mysql['token']=mysql_real_escape_string($_SESSION['token']);
$mysql['token_time']=mysql_real_escape_string($_SESSION['token_time']);
$mysql['session_id']=mysql_real_escape_string(session_id());

$query="UPDATE tbl_order_header SET token='{$mysql['token']}',token_time='{$mysql['token_time']}' WHERE ordernumber='{$mysql['session_id']}'";
$result=mysql_query($query);

$pageTitle = 'Ancestry Shopping Basket | Order Summary';
?>
<?php require_once('include/edgeward/header.inc.php'); ?>

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
    <div id="content_wrapper">
    
        <ul id="progress_bar" class="stage3">
        <li>Order Details</li>
        <li>Delivery Address</li>
        <li>Order Summary</li>
        <li>Payment</li>
        </ul>
        
      <div class="border_top"></div>
        <div class="fieldset_border"> 
        <?php require_once('include/edgeward/cart.inc.php'); ?>
        </div><!--end .fieldset_border-->
      <div class="border_bottom"></div>
        
        
      <form method="post" class="shop_form" action="<?php echo $editset ? "upd_address.php" : "edit_summary.php";?>">
        <input type="hidden" name="noemail" value="<?php echo $order['order_header']['noemail'];?>"/>
        <input type="hidden" name="norent" value="<?php echo $order['order_header']['norent'];?>"/>
        <input type="hidden" name="token" value="<?php echo $token; ?>" />
        <input type="hidden" name="formtype" value="<?php echo $formtype;?>"/>
        <div class="fieldset accordion">
        <a class="help help_legend" id="help_del_add" title="Delivery Address Help" href="shop_help.php#delivery_address">?</a>
        <fieldset class="box">
            <span class="legend legend_top<?php if(!$disableAddress) echo ' acc_open';?>">Delivery Address</span>
            <div class="fieldset_bg">
            <?php require_once('include/edgeward/fields_address.inc.php'); ?>
        
            <?php if($editset == 'add'){
                echo "<input type=\"submit\" value=\"Save\" class=\"noval edit_btn\" name=\"btn\"/>"; 
                echo "<input type=\"hidden\" name=\"formset\" value=\"address\"/>";
            } elseif(!$editset) {
                echo "<a href=\"edit_summary.php?edit=add\" class=\"edit_btn\" id=\"edit_add\">Edit</a>";
            }?>
            </div><!--end .fieldset_bg-->
        </fieldset>
        </div>
        <div class="fieldset accordion">
        <a class="help help_legend" id="help_bill_add" title="Billing Address Help" href="shop_help.php#billing_address">?</a>
        <fieldset class="box">
        
            <span class="legend legend_mid" id="acc_billing_add">Billing Address</span>
            <div class="fieldset_bg">
            <?php require_once('include/edgeward/fields_b_address.inc.php'); ?>
            </div><!--end .fieldset_bg-->
        </fieldset>
        </div>
        
        </form>
        
        <form method="post"  id="tc_form" action="https://securetrading.net/authorize/form.cgi" onsubmit="javascript:pageTracker._linkByPost(this)">
        <div class="border_top"></div>
        <div class="fieldset_border"> 
        
        <fieldset class="box">  
            <legend><strong>Terms and Conditions</strong></legend>
            <div class="input checktc">
            <label for="terms">Before placing an order you should read and understand our <a href="https://ancestryshop.co.uk/certificate/tcs.php" title="Terms and Conditions" target="_blank">Terms and Conditions</a>. If we accept your order then a binding agreement will come into existence on our <a href="https://ancestryshop.co.uk/certificate/tcs.php" title="Terms and Conditions" target="_blank">Terms and Conditions</a>. <!--Only tick the box if you wish to be bound by our <a href="https://ancestryshop.co.uk/certificate/tcs.php" title="Terms and Conditions" target="_blank">Terms and Conditions</a>.--></label>    
            <!--<input name="terms" type="checkbox" id="terms" />-->
            </div>
            
        
        </fieldset>
        <!--secure trading stuff-->
        <input type="hidden" name="token" value="<?php echo $token; ?>" />
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
        <input type="hidden" name="cj_tracking" value="<?php echo $tracking_urls['CJ_URL']?>" />
		<input type="hidden" name="ba_tracking" value="<?php echo $tracking_urls['BA_URL']?>" />
	    <input name="amount" value="<?php echo $stAmount;?>" type="hidden">
         <?php 
	if(SECURE_TRADING_ACCOUNT=='testelmbank9808')
		echo "<strong>TEST SECURE TRADING ACCOUNT IS BEING USED</strong>";
	?>
        <div class="button">
        <input type="submit" value="Continue" class="noval img_btn <?php if($editset) echo 'btn_disabled';?>" <?php if($editset) echo 'disabled=\"disabled\"';?>/>
        </div>
        </div><!--end .fieldset_border-->
        <div class="border_bottom"></div>
        </form>
    </div><!--end #content_wrapper-->
    <div id="page_footer"></div>
    <div class="helptext" id="helptext_del_1"><p class="top"><?php echo $shippingHelp;?></div>
    <div class="helptext" id="helptext_del_add"><p class="top">This is the address that you'd like the certificate delivered to.<br/>All fields marked with <strong>*</strong> are required.</p></div>
    <div class="helptext" id="helptext_bill_add"><p class="top">Complete the billing address section if your billing and delivery addresses are different.<br/>All fields marked with <strong>*</strong> are required.</p></div>
    <div class="helptext" id="helptext_offer_code"><p class="top">Enter a special offer code to receive promotional discount.</p></div>
<?php require_once('include/footer.php'); ?>
