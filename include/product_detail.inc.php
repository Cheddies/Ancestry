<?php include('include/header.php');?>
	<?php include('include/page_header.inc.php');?>
	<?php include('include/nav.inc.php');?>
	<div id="content_wrapper" class="grid_12 alpha omega">
		<div id="product_details" class="grid_12">
			<h2 class="box_top box_top_12"><?php echo $listTitle;?></h2>
			<div id="prod_details_wrapper">
				<div id="aside" class="grid_4 alpha omega">
					<div id="product_aside">
						<?php include('include/product_detail_images.inc.php');?>
						<p id="print_send">
							<a href="#" id="print_page" class="print_page" title="Print this page">Print this page</a><a title="Email this page to a friend" href="mailto:?subject=<?php echo str_replace(array('&amp;','&'),'%26',$product['name']);?>&amp;body=I found this product on Ancestryshop.co.uk and thought you might like it: http://ancestryshop.co.uk<?php echo $prodDetailLink;?>" id="email_page">Email to a friend</a>
						</p>	
						
						<?php include('include/social_bookmarks.inc.php');?>
						
						<?php if(!empty($extraInfo)):?>
						<h2 id="prod_extra_title"><?php echo $extraInfo['title'] ? $extraInfo['title'] : 'Other Information';?></h2>
						<div id="prod_extra_wrapper">
							<?php echo $extraInfo['html'];?>
						</div><!--end #prod_extra_wrapper-->
						<?php endif;?>
					</div>
				</div>
				<div id="prod_content" class="grid_6 alpha omega">
					<h1 id="product_title"><?php echo $product['name'];?></h1>
					<?php echo $product['inetfdesc'];?>
					<?php include('include/xmas_del.inc.php');?>
				</div>
				
				<div id="aside_b">
					<div class="buy_box">
						<?php if($product['compareprice']):?>
						<p class="prod_old_price">RRP: <del>&pound;<?php echo number_format($product['compareprice'],2);?></del></p>
						<?php endif;?>
						<p class="buy_price">Price: &pound;<?php echo number_format($product['price'],2);?></p>
						<p class="qty"><label for="prod_qty_1">Qty: </label>
							<select class="prod_qty" name="prod_qty_1">
								<option value="1" selected="selected">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
							</select>
						</p>
						<a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" class="buy_now">Buy Now</a>
					</div>
					
					<?php if($extraInfo['html2']):?>
					<div id="reviews">
						<?php echo $extraInfo['html2'];?>
					</div>
					<?php endif;?>
					
					<div class="buy_box lower">
						<?php if($product['compareprice']):?>
						<p class="prod_old_price">RRP: <del>&pound;<?php echo number_format($product['compareprice'],2);?></del></p>
						<?php endif;?>
						<p class="buy_price">Price: &pound;<?php echo number_format($product['price'],2);?></p>
						<p class="qty"><label for="prod_qty_2">Qty: </label>
							<select class="prod_qty" name="prod_qty_2">
								<option value="1" selected="selected">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
							</select>
						</p>
						<a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" class="buy_now">Buy Now</a>
					</div>
				</div><!--end #aside_b-->
				
			</div><!--end #prod_box_wrapper-->
		</div>
		<?php include('include/page_footer.inc.php');?>
	</div><!--end #content_wrapper-->
<?php include ('include/footer.php');?>