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
        
        <div class="fieldset_border"> 
        <?php require_once('include/edgeward/cart.inc.php'); ?>
        </div><!--end .fieldset_border-->
	  
    </div><!--end #content_wrapper-->
    <div id="page_footer"></div>
    <div class="helptext" id="helptext_del_1"><p class="top">Family Tree Maker software is only available for shipping to the United Kingdom</p><br><?php echo $shippingHelp;?></div>
    <div class="helptext" id="helptext_offer_code"><p class="top">Enter a special offer code to receive promotional discount.</p></div>
    

<?php require_once('include/footer_basket.php');
require_once('include/footer.php'); ?>

<?php /*
Omniture tracking
*/ ?>    
    
    <script type='text/javascript' src='http://c.mfcreative.com/js/omniture004.js?v=5'></script>
<script type='text/javascript'>
s.products= 'birth-marriage-death-certificates;birth-certificate'; 'birth-marriage-death-certificates;death-certificate'; 'birth-marriage-death-certificates;marriage-certificate'; 'software;family-tree-maker-2012-platinum-edition'; 'software;family-tree-maker-2012-deluxe-edition'; 'software;family-tree-maker-2012-world-edition'; 'software;family-tree-maker-mac'; 'books;dya-bookazine'; 'gifts;dya-bookazine';
//Product Category;Product Name (all lower case)
	s.events='scAdd';
	s.pageName=document.title;
	var s_code=s.t();if(s_code)document.write(s_code);
</script>