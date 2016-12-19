<?php 
//set formtype
$formtype = 'shipping';
require_once('include/edgeward/process_bmd.inc.php');
//dont include JS
//$no_js = 1;
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
        
        <form action="upd_shipping.php" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>" />
        <input type="hidden" id="country_data" name="country_data" value="<?php echo $country_data;?>"/>
        <?php require_once('include/edgeward/delivery_options.inc.php'); ?>   
        
        <div class="button">
        <input type="submit" value="Continue" class="noval img_btn" name="btn"/>
        </div>
        </div><!--end .fieldset_border-->
        <div class="border_bottom"></div>
        </form>
    </div><!--end #content_wrapper-->
    <div id="page_footer"></div>
    <?php require_once('include/edgeward/help_text.inc.php'); ?>


<?php require_once('include/footer.php'); ?>

