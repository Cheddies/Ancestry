<?php include ('include/header.php');?>
	<?php include_once('include/page_header.inc.php');?>
	<?php include_once('include/nav.inc.php');?>
	<div id="content_wrapper" class="grid_12 alpha omega">
		<div id="top_content" class="grid_9">
			<?php include('include/homepage_banner.inc.php');?>
			<div id="introduction" class="grid_9 alpha omega">
				<h1>Welcome to the Ancestry Shop</h1>
				<p>At the Ancestry Shop you can buy specialist genealogy books, purchase Family Tree Maker&#0153;, order birth, marriage & death certificates and even order a DNA Ancestry test.</p>
			</div>
		</div>
		
		<div id="promo_a" class="grid_3">
			<h2 class="box_top">What's New</h2>
			<div class="grid_3 prod_box" id="promo_a_content">
				<a href="<?php echo WEBROOT;?>products/software/family-tree-maker-2010-platinum-edition"><img src="images/products/FTMPLATINUM2010_TN.jpg" alt="" class="prod_img_s" /></a>
				<div class="prod_desc_wrapper">
					<h3><a href="<?php echo WEBROOT;?>products/software/family-tree-maker-2010-platinum-edition">Family Tree Maker 2010 Platinum Edition</a></h3>
					<p class="prod_desc">Whether you're a seasoned pro or just starting on your family tree, Family Tree Maker 2010 can help you. <a href="<?php echo WEBROOT;?>products/software/family-tree-maker-2010-platinum-edition">Read more...</a></p>
				</div>
				<p class="prod_price">&pound;58.71</p>						
				<a href="<?php echo WEBROOT;?>addtobasket.php?code=FTMPLATINUM2010" class="buy_now">Buy Now</a>	
			</div>
		</div>
		
		<?php include_once('include/featured_static.php');?>
		<?php include_once('include/page_footer.inc.php');?>
	</div><!--end #content_wrapper-->

<?php include ('include/footer.php');?>