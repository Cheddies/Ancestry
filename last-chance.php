<?php 
include ('include/edgeward/global_functions.inc.php');
$qtyLeft = 'a few';
$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');
$query = "SELECT units  FROM tbl_products WHERE number = 'XMAS BUNDLE' LIMIT 1";
$result = sqlQuery($query);
$rows=count($result);
if(!$rows) return array();
// Closing connection
mysql_close($link);
if($result[0]['units'] >0){
	$qtyLeft = $result[0]['units'];
} 
elseif($result[0]['units'] <1){
	header ('location: ' . WEBROOT . 'offer_closed');
	exit;
}
include ('include/header_static.inc.php');
?>

	<div id="header">
		<h1 id="promo_logo">Ancestryshop</h1>
		<p id="strapline">Hurry! Limited stock, only <?php echo $qtyLeft;?> left</p>
		<!--<img src="images/promo/header-img.png" alt="Family Tree Maker Christmas Offer" id="header_img"/>-->
		<img src="images/promo/bow.png" id="bow_img"/>
		<a href="<?php echo WEBROOT;?>addtobasket.php?code=XMAS BUNDLE" class="big_buy" title="Buy Now">Buy Now</a>
	</div>
	<div id="main_content">
		
		<div class="txt_box_top"></div>
		<div class="txt_box">
			<p>Get in quick to get Family Tree Maker&trade; 2010 Platinum Edition for just <strong>&pound;33.71 (a saving of &pound;25).&#8224;</strong>
			</p>
			<p>And while stocks last you will also receive the Who Do You Think You Are? Encyclopedia Of Genealogy <strong>FREE - the normal RRP is &pound;25 - that's a total saving of &pound;50</strong>.
			</p>
			<p>Family Tree Maker&trade; is the UK's leading family tree software. It's simply the best way to unravel, discover and explore your family's history.
			</p>
			<p>And now with the Who Do You Think You Are? Encyclopedia Of Genealogy you also get the definitive, must-have guide to researching your family's roots and bringing your family history to life.
			</p>
			<p><strong>Place your order now!</strong>
			</p>
			<div class="buy_box_promo">
				<a href="<?php echo WEBROOT;?>addtobasket.php?code=XMAS BUNDLE" class="buy_now">Buy Now</a>
				<div class="prices">
					<p class="new_price">&pound;33.71</p>
					<p class="old_price"><del>&pound;58.71</del></p>
				</div>
			</div>
			
		</div><!--end .text_box-->
		<div class="txt_box_btm"><a href="#" class="to_top">Back to top</a></div>
		
		<h2 class="txt_box_title">What's new in Ancestry.co.uk Family Tree Maker&trade; 2010?</h2>
		<div class="txt_box">
			<p>Family Tree Maker&trade; has always made it easy to discover your story, preserve your legacy and share your unique heritage as you explore your family tree on your personal computer. And now the 2010 edition introduces even more rich storytelling and organisation tools that can add new life to your family history. 
			</p>	
			<ul>		
				<li>New tools and charts that let you tell a richer story </li>
				<li>Better ways to organise photos and other media </li>
				<li>Slideshows you can create with images in your tree </li>
				<li>Publish beautiful family books made from your tree to share with family & friends </li>
				<li>Standard source templates that help you reference the right information </li>
				<li>New Person View - A view of your relationship to everyone in your tree </li>
				<li>Extended family birthday calendars </li>
				<li>Scanner support - Add scanned images directly into your tree </li>
				<li>Family migration paths over time </li>
				<li>Easy family tree download from Ancestry.co.uk&trade;</li>
				<li>Better Performance - Experience faster load times and navigation</li>
			</ul>
			
			<a href="images/promo/ftm2010_2.jpg" class="fbox" title="Click for larger image"><img src="images/promo/ss_1.png"/></a>
			<a href="images/products/FTMPLATINUM2010_b.jpg" class="fbox"  title="Click for larger image"><img src="images/promo/ss_2.png" class="scr_shot"/></a>
			<a href="images/products/FTMPLATINUM2010_a.jpg" class="fbox"  title="Click for larger image"><img src="images/promo/ss_3.png" class="scr_shot"/></a>
			<a href="images/promo/ftm2010_1.jpg" class="fbox"  title="Click for larger image"><img src="images/promo/ss_4.png" class="scr_shot"/></a>
			
			<p><strong>Family Tree Maker&trade; 2010 PLATINUM also comes with 6 months of free* access to the UK's largest online collection of family history resources.</strong>
			</p>
			
		</div><!--end .text_box-->
		<div class="txt_box_btm"><a href="#" class="to_top">Back to top</a></div>
		
		<h2 class="txt_box_title">The must-have guide to researching your family's roots</h2>
		<div class="txt_box">
			<img src="images/promo/sm_book.png" class="img_left"/>
			
			<p>From the makers of the award-winning BBC series and Dr Nick Barratt, the UK's leading authority on family history, comes the definitive, must-have guide to researching your family's roots and bringing your family history to life. Containing all you need to know whether you're a new beginner or more experienced researcher. 
			</p>
			<p>Covering all access levels, from the new beginner to the more experienced researcher, the Encyclopedia of Genealogy is a comprehensive master class in solving the mysteries of your personal heritage.
			</p>
			<p><strong>It's a FREE gift for you from AncestryShop, the normal RRP for this book is &pound;25.</strong>
			</p>
			
		</div><!--end .text_box-->
		<div class="txt_box_btm"><a href="#" class="to_top">Back to top</a></div>
		
		<h2 class="txt_box_title">Contents of Family Tree Maker 2010 Platinum Edition</h2>
		<div class="txt_box">
			<img src="images/promo/ftm_box.png" class="img_left ftm_box"/>
			<ul class="list_right">
				<li>6-month Ancestry.co.uk&trade; Essentials Membership* (worth over &pound;65)</li>
				<li>Family Tree Maker program CD-ROM</li>
				<li>Family Tree Maker Getting Started Guide</li>
				<li>Family Tree Maker Training Tutorial</li>
				<li>Family Tree Maker Little Book of Answers</li>
				<li>20% discount on Ancestry&trade; DNA Testing+</li>
				<li>25% off Mycanvas&trade; Printed Books & Posters+</li>
				<li>Save up to &pound;44 off Arrowfile Genealogy Albums & Acid Free Archival Pages+</li>
				<li>3 FREE ISSUES of Your Family Tree Magazine when you subscribe for 1 year+</li>
			</ul>
			<div class="buy_box_promo">
				<a href="<?php echo WEBROOT;?>addtobasket.php?code=XMAS BUNDLE" class="buy_now">Buy Now</a>
				<div class="prices">
					<p class="new_price">&pound;33.71</p>
					<p class="old_price"><del>&pound;58.71</del></p>
				</div>
			</div>
			
		</div><!--end .text_box-->
		<div class="txt_box_btm"><a href="#" class="to_top">Back to top</a></div>
		
		<div id="terms">
			<p>+ Terms and Conditions Apply</p>
			<p>* Terms and Conditions of free subscription offer. 
			</p>
			<p>The Ancestry.co.uk&trade; Essentials Membership begins upon activation of the free subscription with a valid credit or debit card but no money will be taken during the free subscription period. To cancel your membership without incurring a charge to your credit or debit card, go to www.ancestry.co.uk, simply login and go to 'My Account', click on 'Cancel Subscription' at least 2 days before the free trial subscription is due to end, otherwise 100% of the monthly Essentials membership price applicable at that time will be automatically debited from your credit or debit card. Use of ancestry.co.uk is subject to our Terms and Conditions and Privacy Policy. 
			</p>
			<p>See <a href="http://www.ancestry.co.uk" target="_blank">www.ancestry.co.uk</a> for full details. 
			</p>
			<p>&#8224; Terms and Conditions 
			</p>
			<p>The Family Tree Maker&trade; 2010 discount offer is only valid from 00:01 (GMT) on 16th November 2009 until midnight (GMT) on 11th December 2009. Ancestry.com Operations Inc. reserves the right to change or cancel this offer without prior notice. The offer is only capable of acceptance through the acceptance procedures at Ancestry.co.uk and the <a href="http://www.ancestry.co.uk/legal/Terms.aspx?sssdmh=dm13.216088" target="_blank">Terms and Conditions</a> set out there.
			</p>
			
		</div>
		
	</div><!--end #main_content-->
	<div id="aside">
		<h2 class="aside_box_title">Product reviews</h2>
		<div class="aside_box">
			<div class="review_box first">
				<p>This is a great standalone product that'll definitely help you organise your family tree. It also comes with direct links to Ancestry.co.uk's excellent online archive, for which you get a six-month free trial.
				<br/><img src="images/promo/wu.png" class="reviewer_logo"/>
				</p>
			</div>
			<div class="review_box">
				<p>The software offers an excellent way to keep track of your research, and the Web site has millions of records that would take you many years (and considerable expense) to track down on your own.
				<br/><img src="images/promo/pcm.png" class="reviewer_logo"/>
				</p>
			</div>
			<div class="review_box last">
				<p>With its unbeatable layout and design, you will have a far-reaching family tree that will last forever. It's efficient, engaging and effective.
				<br/><img src="images/promo/2009.png" class="reviewer_logo"/>
				</p>	
			</div>
		</div><!--end .text_box-->
		<div class="aside_box_btm"></div>
	</div><!--end #aside-->
		
<?php include ('include/footer.php');?>