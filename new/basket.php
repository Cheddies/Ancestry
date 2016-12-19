<?php
//set formtype
$formtype = 'basket';
require_once('include/edgeward/process_cart.inc.php');
$pageTitle = 'Ancestry Shopping Basket | Order Details';
?>
<?php require_once('include/edgeward/header.inc.php'); ?>
    <div id="content_top"></div>
    <div id="content_wrapper">
    
        <ul id="progress_bar">
        <li>Order Details</li>
        <li>Delivery Address</li>
        <li>Order Summary</li>
        <li>Payment</li>
        </ul>
        
        
      <div class="border_top"></div>
        <div class="fieldset_border"> 
        <p>If you need any help, hover your cursor over any of the <img src="images/edgeward/help_btn.png" width="14" height="14" alt="Help (?)" /> buttons and check the help panel that appears on the right. If you have entered any of the information required incorrectly you will see <img src="images/edgeward/val_no.png" width="13" height="14" alt="X" /> to the right of the question that needs your attention and an explanation of the the error(s) will appear. Once the correct information has been entered and you move through the form you will see <img src="images/edgeward/val_yes.png" width="15" height="14" alt="Tick" /> next to the question.</p>
        </div><!--end .fieldset_border-->
      <div class="border_bottom"></div>
        
      <div class="border_top"></div>
        <div class="fieldset_border"> 
        <?php require_once('include/edgeward/cart.inc.php'); ?>
        </div><!--end .fieldset_border-->
      <div class="border_bottom"></div>
    </div><!--end #content_wrapper-->
    <div id="page_footer"></div>
    <div class="helptext" id="helptext_del_1"><p class="top"><?php echo $shippingHelp;?></div>
    <div class="helptext" id="helptext_offer_code"><p class="top">Enter a special offer code to receive promotional discount.</p></div>
    

<?php require_once('include/footer.php'); ?>


