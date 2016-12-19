<?php 
define("WWW_ROOT", '');

$endDate = strtotime('5 April 2010');
$now = time();
$dateDiff = $endDate - $now;
$daysLeft = ceil($dateDiff/(60*60*24));
if($daysLeft <1) header('location: ' . WWW_ROOT . 'offer_closed');
$headerTitle = $daysLeft >1 ? "Hurry, only {$daysLeft} days left!" : "Hurry, only {$daysLeft} day left!";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
$tab = isset($_GET['tab']) ? $_GET['tab'] : 1;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Family Tree Maker&trade; 2010 - Family History Software - Ancestry Shop</title>
<meta name="description" content="Family Tree Maker&trade; software. Save 30% on the No.1 selling family history software" />
<meta name="keywords" content="Family Tree Maker, Ancestry shop, family history software, family history, family research" />

<link rel="stylesheet" href="<?php echo WWW_ROOT;?>css/ppc.css"/>
<link href="<?php echo WWW_ROOT;?>css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo WWW_ROOT;?>script/edgeward/jquery.fancybox-1.2.1.pack.js"></script>

<script type="text/javascript">
	$(function(){
		var container = $('#container');
		container.data('height',container.height());
		var tabControls = $('#tab_controls li a');
		tabControls.click(function(){
			var tabNo = $(this).attr('id');
			tabNo = tabNo.replace('tc','');
			tabControls.removeClass('current');
			$(this).addClass('current');
			$('.tab').removeClass('active_tab');
			$('#tab_' + tabNo).addClass('active_tab');
			if(container.height() > container.data('height')){
				container.data('height',container.height());
				container.height(container.data('height'));
			}
			return false;
		});
		
		//Lightbox
		$('.prod_img').fancybox(); 
	});
</script>

</head>

<body class="easter easter_last">
	<div id="container">
		<div id="content_wrapper">
			<div id="intro_content">
				<div id="main_content">
					<h1 id="logo">Family Tree Maker 2010 - Ancestry Shop</h1>				
					
					<h2 class="main_title"><?php echo $headerTitle;?></h2>
					<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" class="big_buy" title="Buy Family Tree Maker 2010 PLATINUM now">Buy Now</a>
					<!--<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now" class="buy_now" id="header_buy"></a>-->
					<img src="<?php echo WWW_ROOT;?>images/layout/prod_box_e.png" alt="Family Tree Maker 2010" id="pack_shot"/>
					<img src="<?php echo WWW_ROOT;?>images/layout/easter_photos.png" alt="Family Tree Maker 2010" id="header_photos"/>
				
				</div><!--end #main_content-->
			</div>
			<a name="tabs"/>
			<ul id="tab_controls">
				<li><a href="ftm2010?tab=1" id="tc1" class="<?php if($tab == 1) echo current ;?>">New Features</a></li>
				<li><a href="ftm2010?tab=2" id="tc2" class="<?php if($tab == 2) echo current ;?>">Why Upgrade?</a></li>
				<li><a href="ftm2010?tab=3" id="tc3" class="<?php if($tab == 3) echo current ;?>">Reviews</a></li>
				<!--<li><a href="ftm2010?tab=4" id="tc4" class="<?php if($tab == 4) echo current ;?>">Software</a></li>-->
				<li><a href="ftm2010?tab=5" id="tc5" class="<?php if($tab == 5) echo current ;?>">Take A Look</a></li>
			</ul>
			<div id="tab_1" class="tab <?php if($tab == 1) echo active_tab ;?>">
				<div class="col2 firstcol">
					<p>The beauty of the new Family Tree Maker&reg; 2010 Platinum is simplicity. You can download your existing tree(s), photos, stories and certificates from Ancestry.co.uk directly into Family Tree Maker&reg;.</p>
					<p>And you’ll get lots of new information about your family - such as the average life span of people in your past and the earliest and most recent birthdates available.</p>
					<p><strong>Includes 6-month Ancestry.co.uk&trade; Essentials Membership* (worth over &pound;65).</strong></p>
					<p><strong>You'll also get new charts, reports and timelines plus: </strong></p>
					<ul>
						<li>Create impressive slideshows from photos in your tree</li>
						<li>Standard source templates giving you the right information every time</li>
						<li>View interactive maps highlighting important events in your ancestor's lives</li>
						<li>Publish beautiful keepsakes and books</li>
					</ul>
				</div>
				
				<div class="col2">
					<ul class="img_list featured_imgs">
						<li>
							<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_1.jpg" class="prod_img" title="Family Tree Maker 2010 comes with 6 months of free access* to the UK's largest online collection of family history resources" rel="overview">
								<img src="<?php echo WWW_ROOT;?>images/products/ppc/hp1_s.jpg" alt="Family Tree Maker 2010"/>
							</a>
						</li>
						<li>
							<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_3.jpg" class="prod_img" title="Organise your family story privately and conveniently on your PC" rel="overview">
								<img src="<?php echo WWW_ROOT;?>images/products/ppc/hp3_s.jpg" alt="Family Tree Maker 2010"/>
							</a>
						</li>
						<li>
							<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_7.jpg" class="prod_img" title="View timelines and interactive maps highlighting events and places in your ancestor's lives" rel="overview">
								<img src="<?php echo WWW_ROOT;?>images/products/ppc/hp7_s.jpg" alt="Family Tree Maker 2010"/>
							</a>
						</li>
						<li>
							<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_6.jpg" class="prod_img" title="Family books made from your tree to share with family &amp; friends" rel="overview">
								<img src="<?php echo WWW_ROOT;?>images/products/ppc/hp6_s.jpg" alt="Family Tree Maker 2010"/>
							</a>
						</li>
						<li>
							<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_9.jpg" class="prod_img" title="Create charts and reports in a variety of formats to share with friends and family" rel="screenshots">
								<img src="<?php echo WWW_ROOT;?>images/products/ppc/hp9_s.jpg" alt="Family Tree Maker 2010"/>
							</a>
						</li>
						<li>
							<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_10.jpg" class="prod_img" title="Discover new information anywhere on the web and add it to your tree instantly" rel="screenshots">
								<img src="<?php echo WWW_ROOT;?>images/products/ppc/hp10_s.jpg" alt="Family Tree Maker 2010"/>
							</a>
						</li>
					</ul>
				</div>
				<div style="clear:left;">
					<p><strong><a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now">Buy before 5th April to save 40%** off the normal RRP &pound;59.99</a> on the UK’s favourite Family Tree Maker&reg; 2010 Platinum. </strong></p>
					<div class="pricing">
						<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now" class="buy_now"></a>					
						<p class="price">&pound;35.99<br/>
						<del>&pound;59.99</del></p>
					</div>
					
				</div>					
					

				
			</div><!-- end #tab_1-->
			
			<div id="tab_2" class="tab <?php if($tab == 2) echo active_tab ;?>">
				<p>Even if you’ve already got an earlier version of Family Tree Maker, you’ll find this latest addition has lots of new features.</p>
				<img src="<?php echo WWW_ROOT;?>images/layout/prod_compare.jpg" alt="Family Tree Maker Product Comparison" id="prod_compare" />
				<p><strong><a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now">Buy before 5th April to save 40%** off the normal RRP &pound;59.99</a> on the UK’s favourite Family Tree Maker&reg; 2010 Platinum. </strong></p>
				<div class="pricing">
					<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now" class="buy_now"></a>					
					<p class="price">&pound;35.99<br/>
					<del>&pound;59.99</del></p>
				</div>
			</div><!-- end #tab_2-->		
			
			<div id="tab_3" class="tab <?php if($tab == 3) echo active_tab ;?>">
				<div class="review first">
					<p>"This is a great standalone product that'll definitely help you organise your family tree. It also comes with direct links to Ancestry.co.uk's excellent online archive, for which you get a six-month free trial."</p>
					<img src="<?php echo WWW_ROOT;?>images/promo/wu.png" alt="Family Tree Maker&trade; 2010 PLATINUM review Web User" />
				</div>
				<div class="review">
					<p>"The combination of this streamlined look and smoother integration with the Ancestry web service makes Family Tree Maker a highly effective product."</p>
					<img src="<?php echo WWW_ROOT;?>images/promo/itr.png" alt="Family Tree Maker&trade; 2010 PLATINUM review IT Reviews" />
				</div>
				<div class="review">
					<p>"With such a vast collection of advanced features, all intended to make the process of building your family tree that little bit easier, Family Tree Maker 2010 rightly occupies our number one spot in this category."</p>
					<img src="<?php echo WWW_ROOT;?>images/promo/no1choice.png" alt="Family Tree Maker&trade; 2010 PLATINUM review Number One Reviews" />
				</div>
				<div class="review">
					<p>"The software offers an excellent way to keep track of your research, and the Web site has millions of records that would take you many years (and considerable expense) to track down on your own."</p>
					<img src="<?php echo WWW_ROOT;?>images/promo/pcm.png" alt="Family Tree Maker&trade; 2010 PLATINUM review PC Magazine" />
				</div>
				<div class="review last">
					<p>"With its unbeatable layout and design, you will have a far-reaching family tree that will last forever. It's efficient, engaging and effective."</p>
					<img src="<?php echo WWW_ROOT;?>images/promo/2009.png" alt="Family Tree Maker&trade; 2010 PLATINUM review 2009 Top 10 Silver Award" />
				</div>
				
				<p><strong><a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now">Buy before 5th April to save 40%** off the normal RRP &pound;59.99</a> on the UK’s favourite Family Tree Maker&reg; 2010 Platinum. </strong></p>
				<div class="pricing">
					<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now" class="buy_now"></a>					
					<p class="price">&pound;35.99<br/>
					<del>&pound;59.99</del></p>
				</div>	
			</div><!-- end #tab_3-->
			
			<div id="tab_4" class="tab <?php if($tab == 4) echo active_tab ;?>">
				<div class="col2 firstcol">
					<p><strong>Minimum system requirements:</strong></p>
					<ul>
						<li>Windows&reg; XP SP2/Vista&trade; or Windows 7</li>
						<li>500 MHz Intel Pentium&reg; II (or equivalent)</li>
						<li>460 MB HDD for installation / 256 MB of RAM</li>
						<li>2X CD-ROM (required for installation)</li>
						<li>800x600 resolution display</li>
					</ul>
					
				</div>
				<div class="col2">
					<p><strong>Recommended system requirements:</strong></p>
					<ul>
						<li>Windows&reg; XP SP2/Vista&trade; or Windows 7</li>
						<li>1 GHz Intel Pentium&reg; III (or equivalent)</li>
						<li>460 MB HDD or installation / 512 MB of RAM</li>
						<li>32X CD/CD-R</li>
						<li>1024x768 resolution display</li>
						<li>All online features require internet access</li>
					</ul>
				</div>
				<div class="cta">
					<p><strong><a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now">Don't miss out on this great offer, order now!</a></strong></p>
					<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now" class="buy_now"></a>	
				</div>
					
						
			</div><!-- end #tab_4-->
			
			<div id="tab_5" class="tab <?php if($tab == 5) echo active_tab ;?>">
				<ul class="img_list">
					<li>
						<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_1.jpg" class="prod_img" title="Family Tree Maker 2010 comes with 6 months of free access* to the UK's largest online collection of family history resources" rel="screenshots">
							<img src="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_1_s.jpg" alt="Family Tree Maker 2010"/>
						</a>
					</li>
					<li>
						<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_2.jpg" class="prod_img" title="Save up to &pound;44 on Arrowfile Genealogy Storage Products – Organise and safeguard your family history for future generations" rel="screenshots">
							<img src="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_2_s.jpg" alt="Family Tree Maker 2010"/>
						</a>
					</li>
					<li>
						<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_3.jpg" class="prod_img" title="Organise your family story privately and conveniently on your PC" rel="screenshots">
							<img src="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_3_s.jpg" alt="Family Tree Maker 2010"/>
						</a>
					</li>
					<li>
						<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_4.jpg" class="prod_img" title="Incorporate photos, documents, audio, video and other files into your tree" rel="screenshots">
							<img src="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_4_s.jpg" alt="Family Tree Maker 2010"/>
						</a>
					</li>
					<li>
						<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_5.jpg" class="prod_img" title="Save 25% on MyCanvas&trade;.  Create beautiful pages to showcase your documents, photos and charts" rel="screenshots">
							<img src="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_5_s.jpg" alt="Family Tree Maker 2010"/>
						</a>
					</li>
					<li>
						<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_6.jpg" class="prod_img" title="Family books made from your tree to share with family &amp; friends" rel="screenshots">
							<img src="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_6_s.jpg" alt="Family Tree Maker 2010"/>
						</a>
					</li>
					<li>
						<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_7.jpg" class="prod_img" title="View timelines and interactive maps highlighting events and places in your ancestor's lives" rel="screenshots">
							<img src="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_7_s.jpg" alt="Family Tree Maker 2010"/>
						</a>
					</li>
					<li>
						<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_8.jpg" class="prod_img" title="Family Tree Maker&trade; 2010 gives your more ways to enrich your family history with exciting new story telling and organisation tools" rel="screenshots">
							<img src="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_8_s.jpg" alt="Family Tree Maker 2010"/>
						</a>
					</li>
					<li>
						<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_9.jpg" class="prod_img" title="Create charts and reports in a variety of formats to share with friends and family" rel="screenshots">
							<img src="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_9_s.jpg" alt="Family Tree Maker 2010"/>
						</a>
					</li>
					<li>
						<a href="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_10.jpg" class="prod_img" title="Discover new information anywhere on the web and add it to your tree instantly" rel="screenshots">
							<img src="<?php echo WWW_ROOT;?>images/products/ppc/ftmplat_10_s.jpg" alt="Family Tree Maker 2010"/>
						</a>
					</li>
				</ul>
				<p><strong><a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now">Buy before 5th April to save 40%** off the normal RRP &pound;59.99</a> on the UK’s favourite Family Tree Maker&reg; 2010 Platinum. </strong></p>
				<div class="pricing">
					<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMPLAT2010" title="Buy Family Tree Maker 2010 PLATINUM now" class="buy_now"></a>					
					<p class="price">&pound;35.99<br/>
					<del>&pound;59.99</del></p>
				</div>
			</div><!-- end #tab_5-->	
			<div id="tab_bottom"></div>
		</div><!--end #content_wrapper-->
		<div id="footer">
			<h3 class="terms">Terms and Conditions</h3>
<p class="terms">

* The 6-month free subscription to Ancestry.co.uk™ Essentials Membership begins upon activation of the free subscription with a valid credit or debit card, but no money will be taken during the free subscription period. To cancel your membership without incurring a charge to your credit or debit card, go to <a href="http://www.ancestry.co.uk">www.ancestry.co.uk</a>, simply login and go to 'My Account', click on 'Cancel Subscription' at least 2 days before the free trial subscription is due to end, otherwise 100% of the monthly Essentials membership price will be automatically debited from your credit or debit card. Use of ancestry.co.uk is subject to our Terms and Conditions and Privacy Policy. See <a href="http://www.ancestry.co.uk">www.ancestry.co.uk</a> for full details.
<br />
** The 40% discount offer for Family Tree Maker® 2010 Platinum is only valid from 00:01 (GMT) on 10th March 2010 until midnight (GMT) on 5th April 2010.  Offer is open to UK residents only and cannot be used in conjunction with any other promotional offer.  Ancestry.com Operations Inc. reserves the right to change or cancel this offer without prior notice.
</p>
			
		</div>
		<div id="easter_eggs"></div>
	</div><!--end #container-->
	<?php include('../include/analytics.inc.php');?>
</body>
</html>
