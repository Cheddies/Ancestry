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

//this needs to come before the include for ST where the Security hash is done
//this needs to change for the different GEOs
$currencyiso3a='GBP';

//defines constants for ST and works out the security hash
//should allways come after the settings of the currencyiso3a field as it is used in the hash
require_once("include/new_st_config.php");

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
        
<h2 class="section_header section_acc">Order Details</h2>
<div class="fieldset_section"> 
        <?php require_once('include/edgeward/cart.inc.php'); ?>
        </div><!--end .fieldset_border-->
        
        
      <form method="post" class="shop_form" action="<?php echo $editset ? "upd_address.php" : "edit_summary.php";?>">
        <input type="hidden" name="noemail" value="<?php echo $order['order_header']['noemail'];?>"/>
        <input type="hidden" name="norent" value="<?php echo $order['order_header']['norent'];?>"/>
        <input type="hidden" name="token" value="<?php echo $token; ?>" />
        <input type="hidden" name="formtype" value="<?php echo $formtype;?>"/>
        
        
  <div id="del_add_fields" class="add_fields">
	<h2 class="section_header section_acc">Delivery Address<a class="help help_legend" id="help_del_add" title="Delivery Address Help" href="shop_help.php#del_address">?</a></h2>
	
	<div class="fieldset_section"> 

            <?php require_once('include/edgeward/fields_address.inc.php'); ?>
        
            <?php if($editset == 'add'){
                echo "<input type=\"submit\" value=\"Save\" class=\"noval edit_btn\" name=\"btn\"/>"; 
                echo "<input type=\"hidden\" name=\"formset\" value=\"address\"/>";
            } elseif(!$editset) {
                echo "<a href=\"edit_summary.php?edit=add\" class=\"edit_btn\" id=\"edit_add\">Edit</a>";
            }?>
            </div><!--end .fieldset_bg-->
        </div>
        
        
<div id="bil_add_fields" class="add_fields">
	<h2 class="section_header">Billing Address<a class="help help_legend" id="help_bill_add" title="Billing Address Help" href="shop_help.php#billing_address">?</a></h2>
	
	<div class="fieldset_section"> 

            <?php require_once('include/edgeward/fields_b_address.inc.php'); ?>
            </div><!--end .fieldset_bg-->
        </div>
        
        </form>
        
       
       <form id="tc_form" action="https://payments.securetrading.net/process/payments/choice" onSubmit="javascript:pageTracker._linkByPost(this)">     
            
<h3 class="info">Terms and Conditions</h3>
<div class="info_box"> 
	<div class="input checktc">
		<label for="terms">Before placing an order you should read and understand our <a href="https://ancestryshop.co.uk/tcs" title="Terms and Conditions" target="_blank">Terms and Conditions</a>. If we accept your order then a binding agreement will come into existence on our <a href="https://ancestryshop.co.uk/tcs" title="Terms and Conditions" target="_blank">Terms and Conditions</a>.<br/>Before placing an order you agree that you will use the records you order for the purposes of your personal, family or household affairs (including recreational purposes) only and you appoint Ancestry.com UK (Commerce) Limited as your agent to obtain those records on your behalf. Where you appoint us to act as agent on your behalf, we agree to process and deliver your order using the information that you provide. Please be sure that the information supplied is both accurate and complete. We cannot accept any responsibility for false, inaccurate or incomplete information supplied. We can give no warranty or guarantee in respect of the goods ordered.</label>    
	</div>    

         <?php 
	if(NEW_SECURE_TRADING_ACCOUNT=='test_elmancestry32377')
		echo "<strong>TEST SECURE TRADING ACCOUNT IS BEING USED</strong>";
	?>
        <div class="button">
        <input type="submit" value="Continue" class="noval img_btn <?php if($editset) echo 'btn_disabled';?>" <?php if($editset) echo 'disabled=\"disabled\"';?>/>
        </div>
        </div><!--end .fieldset_border-->
       
    </div><!--end #content_wrapper-->
    <div id="page_footer"></div>
    <div class="helptext" id="helptext_del_1"><p class="top"><?php echo $shippingHelp;?></div>
    <div class="helptext" id="helptext_del_add"><p class="top">This is the address that you'd like the certificate delivered to.<br/>All fields marked with <strong>*</strong> are required.</p></div>
    <div class="helptext" id="helptext_bill_add"><p class="top">Complete the billing address section if your billing and delivery addresses are different.<br/>All fields marked with <strong>*</strong> are required.</p></div>
    <div class="helptext" id="helptext_offer_code"><p class="top">Enter a special offer code to receive promotional discount.</p></div>

<input type="hidden" name="sitereference" value="<?php echo NEW_SECURE_TRADING_ACCOUNT?>">
<input type="hidden" name="currencyiso3a" value="<?php echo $currencyiso3a ?>">
<input type="hidden" name="mainamount" value="<?php echo $orderTotal?>"> <?php /* dosn't need to be in pence for new ST */?>
<input type="hidden" name="version" value="1">
<input type="hidden" name="billingpremise" value="<?php echo $order['order_header']['address1']?>" >
<input type="hidden" name="billingstreet" value="<?php echo $order['order_header']['address2']?>" >
<input type="hidden" name="billingtown" value="<?php echo $stTown ?>" >
<input type="hidden" name="billingcounty" value="<?php echo $stCounty?>" >
<input type="hidden" name="billingpostcode" value="<?php echo $stPostcode?>" >

<input type="hidden" name="billingprefixname" value="" >
<input type="hidden" name="billingfirstname" value="<?php echo $order['order_header']['firstname']?>" >
<input type="hidden" name="billinglastname" value="<?php echo  $order['order_header']['lastname']?>" >
<input type="hidden" name="billingcountryiso2a" value="<?php echo $iso_countries[(string)$order['order_header']['country']]?>" > <?php /* needs to be iso2a so will need conversion */?>
<input type="hidden" name="billingemail" value="<?php echo $stEmail ?>" >
<input type="hidden" name="billingtelephone" value="<?php echo $stPhone ?>" >
<input type="hidden" name="billingtelephonetype" value="H" ><?php /*required if sending telephone , will just have to set to H for home */?>
<?php /*shipping details*/?>
<input type="hidden" name="customerpremise" value="<?php echo $order['order_header']['saddress1']?>" >
<input type="hidden" name="customerstreet" value="<?php echo $order['order_header']['saddress2']?>" >
<input type="hidden" name="customertown" value="<?php echo $order['order_header']['scity']?>" >
<input type="hidden" name="customercounty" value="" > <?php /*we don't store county*/?>
<input type="hidden" name="customerpostcode" value="<?php echo $order['order_header']['szipcode']?>" >


<input type="hidden" name="customercountryiso2a" value="<?php echo $iso_countries[(string)$order['order_header']['scountry']]?>" > <?php /* needs to be iso2a so will need conversion */?>
<input type="hidden" name="customeremail" value="<?php echo $stEmail ?>" >
<input type="hidden" name="customertelephone" value="<?php echo $stPhone ?>" >
<input type="hidden" name="customertelephonetype" value="H" ><?php /*required if sending telephone , will just have to set to H for home */?>
<input name="orderreference" value="<?php echo $stOrderref;?>" type="hidden">
<input type="hidden" name="childcss" value="child" />
<input type="hidden" name="sitesecurity" value="<?php echo $sitesecurity?>" />

<?php 
/*
<!-- Custom Fields -->

<!-- to get these returned in POST need to contact ST Support -->
<!-- may not need them, but will need some custom things returning -->

Session id most important

Seems to be just a case of adding these into the desination setup in notifications
*/
?>
<input name="session_id" value="<?php echo $stSession;?>" type="hidden">

<input type="hidden" name="cj_tracking" value="<?php echo $tracking_urls['CJ_URL']?>" />
<input type="hidden" name="ba_tracking" value="<?php echo $tracking_urls['BA_URL']?>" />

<?php /*** Tagman Fields ***/?>
<?php 
/*Tagman fields don't appear to be set for shop checkout
but the total is still being sent on the current checkout
works for the certificate checkout as far as I can se
*/
?>
<?php if (isset($tagman)){?>
<input name="tm_prodrefs" value="<?php echo implode('|',$tagman['itemrefs']);?>" type="hidden">
<input name="tm_prodprices" value="<?php echo implode('|',$tagman['itemprices']);?>" type="hidden">
<?php }
else
{
?>
<input name="tm_prodrefs" value="" type="hidden">
<input name="tm_prodprices" value="" type="hidden">
<?php
}
?>
<input name="tm_total" value="<?php echo number_format($orderTotal,2);?>" type="hidden">

</form>
    
    <?php require_once('include/footer_basket.php');
require_once('include/footer.php'); ?>