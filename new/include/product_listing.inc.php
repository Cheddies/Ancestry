<?php include ('include/header.php');?>
	<?php include_once('include/page_header.inc.php');?>
	<?php include_once('include/nav.inc.php');?>
	<div id="content_wrapper" class="grid_12 alpha omega">
		<div id="product_listing" class="grid_12">
			<h2 class="box_top_12 box_top"><?php echo $listTitle;?></h2>
			<ul id="product_list">
				<?php if(!empty($products)):
					foreach($products as $product):?>				
					<li class="prod_item grid_12 alpha omega">
						<div class="prodlist_img_wrapper">
							<a href="<?php echo WEBROOT . "products/{$product['deptslug']}/{$product['slug']}";?>" title="<?php echo $product['inetsdesc']?>"><img src="<?php echo WEBROOT?>images/products/<?php echo $product['number'];?>_MED.jpg" alt="<?php echo $product['inetsdesc']?>" class="prod_img_s" /></a>
						</div>
						<h2><a href="<?php echo WEBROOT . "products/{$product['deptslug']}/{$product['slug']}";?>" title="<?php echo $product['inetsdesc']?>"><?php echo $product['inetsdesc'];?></a></h2>
						<p class="prod_desc"><?php echo $product['inetshortd'];?> <a href="<?php echo WEBROOT . "products/{$product['deptslug']}/{$product['slug']}";?>">Read more.</a></p>
						<?php if($product['buybutton'] !== '0'):?>	
							
							<p class="prod_price">
								<?php if($product['compareprice']):?>
								<del class="old_price">&pound;<?php echo number_format($product['compareprice'],2);?></del>
								<?php endif;?>
								<?php echo $from . '&pound;' . number_format($product['price'],2);?>
							</p>	
							<?php if($product['buybutton'] === '1'):?>									
								<a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" class="buy_now">Buy Now</a>
							<?php else:?>
								<a href="<?php echo WEBROOT . $product['buybutton'];?>" class="buy_now">Buy Now</a>
							<?php endif;?>
						<?php endif;?>
					</li>	
				<?php 
					endforeach;
				else:?>
				<li class="prod_item grid_12 alpha omega">
					<?php if($dept == 'search'):?>
						<p>Sorry, your search term produced no results.</p>
					<?php else:?>
						<p>There are no products to display.</p>
					<?php endif;?>
					<p>Our main product categories are: <a href="<?php echo WEBROOT?>products/birth-marriage-death-certificates">Certificates</a>, <a href="<?php echo WEBROOT?>products/software">Software</a>, <a href="<?php echo WEBROOT?>products/books">Books</a>, <a href="<?php echo WEBROOT?>products/gifts">Gifts</a>, <a href="dna">DNA</a> and <a href="my-canvas">My Canvas</a>.</p>
					<p>You can use our seach facility (above the menu bar) to help locate what you're looking for or <a href="<?php echo WEBROOT?>index" title="Return to home page">return to the home page.</a></p>
				</li>
				<?php endif;?>		
			</ul>
		</div>
		<?php //print_r($products);?>
		<?php include_once('include/page_footer.inc.php');?>
	</div><!--end #content_wrapper-->
<?php include ('include/footer.php');?>