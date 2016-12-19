<?php include('include/ppc_init.inc.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
$tab = isset($_GET['tab']) ? $_GET['tab'] : 1;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Family Tree Maker&reg; 2011 | Family History Software | Ancestry Shop</title>
<meta name="description" content="Family Tree Maker&reg; software. Save 30% on the No.1 selling family history software" />
<meta name="keywords" content="Family Tree Maker, Ancestry shop, family history software, family history, family research" />
<link rel="stylesheet" href="<?php echo WWW_ROOT;?>css/landing.css"/>
<link href="<?php echo WWW_ROOT;?>css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo WWW_ROOT;?>script/edgeward/jquery.fancybox-1.2.1.pack.js"></script>
<script type="text/javascript" src="<?php echo WWW_ROOT;?>script/edgeward/ppc.js"></script>
<script type="text/javascript">
	$(function(){
		//Lightbox
		$('.prod_img').fancybox({'titleShow':false});
	});
</script>

</head>

<body>
	<div id="container">
		<h1 id="logo">Family Tree Maker 2011 - Ancestry Shop</h1>
		<div id="content_wrapper" class="ftm_mac ftm">
				
			<div id="intro_content">
				
				<p class="breadcrumb"><a href="http://AncestryShop.co.uk">AncestryShop</a> > Family Tree Maker</p>
				<h2 class="main_title">Get 25% off Family Tree Maker<span class="super">&reg;</span> for Mac</h2>
					
				<p>
					Family Tree Maker&reg; for Mac, makes organizing, researching and sharing your family history easier than ever, whether you're just getting started or already an expert.
				</p>
				<ul>
					<li>Research and preserve your family story on your computer</li>
					<li>Create charts and reports to share with family and friends</li>
					<li>Add photos, documents, audio and video into your tree</li>
				</ul>
				<p><strong><a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMMAC&oc=FTMMAC" title="Buy Family Tree Maker for Mac now">Don't miss out on this great offer, order today!</a></strong>
				</p>
				<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMMAC&oc=FTMMAC" class="big_buy_now btn"  title="Buy Family Tree Maker for Mac now">Buy now</a>
				<p class="lead_txt expires"><i>This offer ends on 22nd November 2010.</i></p>
				<?php if($daysLeftText) echo '<p class="lead_txt days_left"><i>' . $daysLeftText . '</i></p>';?>
				<p id="ships_on">Ships from: 18th November 2010</p>
				
			
						
			</div>
			<!--<a name="tabs"/>-->
			<ul id="nav">
				<li class="<?php if($tab == 1) echo current ;?>">
					<a href="<?php echo $pageUrl;?>?tab=1" id="tc1">Overview</a>
				</li>
				<li class="<?php if($tab == 2) echo current ;?>">
					<a href="<?php echo $pageUrl;?>?tab=2" id="tc2">In the box</a>
				</li>
				<li class="<?php if($tab == 3) echo current ;?>">
					<a href="<?php echo $pageUrl;?>?tab=3" id="tc3">Software</a>
				</li>
				<li class="<?php if($tab == 4) echo current ;?>">
					<a href="<?php echo $pageUrl;?>?tab=4" id="tc4">Screenshots</a>
				</li>
			</ul>
			<div id="main_content">
				<div class="tab_top"></div>
				
				<!-- tab 1-->
				<div id="tab_1" class="tab <?php if($tab != 1) echo inactive_tab ;?>">
					<div class="col_a firstcol">
					 
						<p class="lead_txt">Family Tree Maker&reg; for Mac comes with 6 months of free* access to the UK's largest online collection of family history resources.</p>
						<p><strong>Family Tree Maker&reg; for Mac provides:</strong></p>
						<ul class="main_features">
							<li>Easy integration with Ancestry.co.uk - Download records, images & family trees.</li>
							<li>Family books, charts and reports - Publish beautiful keepsakes.</li>
							<li>Slideshows - Create stunning presentations from photos in your tree.</li>
							<li>Family migration charts - View timelines and interactive maps.</li>
						</ul>
						
						<p class="cta"><strong><a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMMAC&oc=FTMMAC" title="Buy Family Tree Maker for Mac now">Don't miss out on this great offer, order now!</a></strong></p>
						<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMMAC&oc=FTMMAC" title="Buy Family Tree Maker for Mac now" class="buy_now btn"></a>
					</div>
					
					<div class="col_b">
						<img src="<?php echo WWW_ROOT;?>images/promo/ftm2011_mac/ss5_s.jpg" alt="Family Tree Maker for Mac" id="tab1_screen"/>
					</div>
					 
					
					
				</div><!-- end #tab_1-->
				
				<!-- tab 2-->
				<div id="tab_2" class="tab <?php if($tab != 2) echo inactive_tab ;?>">
					<div class="col_a firstcol">
						<p><strong>Contents at a glance:</strong></p>
						<ul>
						<li>Family Tree Maker&reg; for Mac CD-ROM</li>
						<li>Family Tree Maker&reg; for Mac The Companion Guide book</li>
						<li>6-month Ancestry.co.uk™ Premium Membership* (worth over £77)</li>
						</ul>
						<div class="cta">
							<p><strong><a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMMAC&oc=FTMMAC" title="Buy Family Tree Maker for Mac now">Don't miss out on this great offer, order now!</a></strong></p>
							<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMMAC&oc=FTMMAC" title="Buy Family Tree Maker for Mac now" class="buy_now btn"></a>
						</div>
					</div>
					<div class="col_b">
						<p><strong>Additional Benefits:</strong></p>
						<ul>
						<li>30% Discount on Genealogy folder+</li>
						<li>25% off MyCanvas printed books and folders+</li>
						<li>3 FREE ISSUES of your Family Tree Magazine when you subscribe for 1 year+</li>
						</ul>
					</div>
				</div><!-- end #tab_2-->
				
				
				
				<!-- tab 3-->
				<div id="tab_3" class="tab <?php if($tab != 3) echo inactive_tab ;?>">
					<div class="col_a firstcol">
						<p><strong>Minimum system requirements:</strong></p>
						<ul>
							<li>Mac OSX 10.5 or later</li>
							<li>Intel-based Mac</li>
							<li>All online features require internet access</li>
						</ul>
						
					</div>
					<div class="cta clear">
						<p><strong><a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMMAC&oc=FTMMAC" title="Buy Family Tree Maker for Mac now">Don't miss out on this great offer, order now!</a></strong></p>
						<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMMAC&oc=FTMMAC" title="Buy Family Tree Maker for Mac now" class="buy_now btn"></a>
					</div>
				</div><!-- end #tab_3-->
				
				<!-- tab 4-->
				<div id="tab_4" class="tab <?php if($tab != 4) echo inactive_tab ;?>">
					<p class="lead_txt">Family Tree Maker&reg; for Mac is more than just a family tree program – it provides a whole suite of powerful tools...</p>
			
						<p  class="cta"><strong><a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMMAC&oc=FTMMAC" title="Buy Family Tree Maker for Mac now">Don't miss out on this great offer, order now!</a></strong></p>
						<a href="<?php echo WWW_ROOT;?>addtobasket.php?code=FTMMAC&oc=FTMMAC" title="Buy Family Tree Maker for Mac now" class="buy_now btn"></a>
		
					
					<a id="ss_main" class="prod_img clear" href="<?php echo WWW_ROOT;?>images/products/FTMMAC_a.jpg" title="" rel="screenshots">
						<img src="<?php echo WWW_ROOT;?>images/promo/ftm2011_mac/ss1_s.jpg" alt="Family Tree Maker Platinum" />
					</a>
					
					<a id="ss_1" href="<?php echo WWW_ROOT;?>images/products/FTMMAC_b.jpg" class="prod_img" title="" rel="screenshots">
						<img src="<?php echo WWW_ROOT;?>images/promo/ftm2011_mac/ss2_s.jpg" alt="Family Tree Maker Platinum" />
					</a>
					<a id="ss_2" href="<?php echo WWW_ROOT;?>images/products/FTMMAC_c.jpg" class="prod_img" title="" rel="screenshots">
						<img src="<?php echo WWW_ROOT;?>images/promo/ftm2011_mac/ss3_s.jpg" alt="Family Tree Maker Platinum" />
					</a>
					<a id="ss_3" href="<?php echo WWW_ROOT;?>images/products/FTMMAC_d.jpg" class="prod_img" title="" rel="screenshots">
						<img src="<?php echo WWW_ROOT;?>images/promo/ftm2011_mac/ss4_s.jpg" alt="Family Tree Maker Platinum" />
					</a>
				</div><!-- end #tab_4-->
				<p class="terms">
				<strong>Terms and Conditions</strong><br/>
				+ Terms and Conditions Apply<br/>
				* Subscription Activation - Terms and Conditions apply.<br/>
				The Ancestry.co.uk&trade; Premium Membership begins upon activation of the free subscription with a valid credit or debit card but no money will be taken during the free subscription period. To cancel your membership without incurring a charge to your credit or debit card, go to www.ancestry.co.uk, simply login and go to 'My Account', click on 'Cancel Subscription' at least 2 days before the free trial subscription is due to end, otherwise 100% of the monthly Premium membership price will be automatically debited from your credit or debit card. Your membership will continue on a monthly basis for 6 months after the free trial subscription ends, and then will roll over into an annual membership, unless you cancel your subscription. Use of ancestry.co.uk is subject to our Terms and Conditions and Privacy Policy. See www.ancestry.co.uk for full details.
				<br/>
				
				</p>
				
			</div><!--end #main_content-->
			<?php //include('include/footer.inc.php');?>
		</div><!--end #content_wrapper-->
		
	</div><!--end #container-->
	<?php include('../include/analytics.inc.php');?>
</body>
</html>
