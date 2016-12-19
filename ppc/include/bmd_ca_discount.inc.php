<?php include('include/ppc_init.inc.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
$tab = isset($_GET['tab']) ? $_GET['tab'] : 1;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Birth, Marriage & Death Certificates – Hold your family history in your hands</title>
<meta name="description" content="Family Tree Maker&trade; software. Save 30% on the No.1 selling family history software" />
<meta name="keywords" content="Family Tree Maker, Ancestry shop, family history software, family history, family research" />
<link rel="stylesheet" href="<?php echo WWW_ROOT;?>css/landing.css"/>
<link href="<?php echo WWW_ROOT;?>css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo WWW_ROOT;?>script/edgeward/jquery.fancybox-1.2.1.pack.js"></script>
<script type="text/javascript" src="<?php echo WWW_ROOT;?>script/edgeward/ppc.js"></script>


</head>

<body>
	<div id="container">
		<h1 id="logo">Birth, Death and Marriage Certificates - Ancestry Shop</h1>
		<div id="content_wrapper" class="<?php echo $discount['code'] ? '' : 'std_page';?>">
				
			<div id="intro_content">
				<?php if($discount['code']):?><h3 class="order_today" style="font-size:32px;">Save <span><?php echo $discount['amount'];?>%</span><span class="ast">*</span></h3><?php endif;?>
				<!--<p class="breadcrumb"><a href="http://AncestryShop.co.uk">AncestryShop</a> > Birth Marriage Death Certificates</p>	-->				
				<h2 class="main_title">Hold your family history in your hands</h2>
				
				<p>Order an Official England & Wales birth, marriage and death certificates through the Ancestryshop – the quick, simple, affordable way to discover even more about your family history. Certificates can be obtained from 1837 to 18 months prior to the present date.</p>
				
				<p>As well as having a treasured piece of family history, you’ll also be able to use vital information to help you make links with earlier generations.
				</p> 
				<?php if($discount['code']):?><p>Simply enter promo code <strong class="emph"><?php echo $discount['code'];?></strong> at the checkout!</p><?php endif;?>
				<div class="pricing">
					<a href="<?php echo $searchUrl;?>" title="Search certificates now" class="search_now btn">Search now</a>
					<p class="pricing">From <?php echo $curSymbol.$standardPrice;?></p>
				</div>
							
			</div>
			<!--<a name="tabs"/>-->
			<ul id="nav">
				<li class="<?php if($tab == 1) echo current ;?>">
					<a href="<?php echo $pageUrl;?>?tab=1" id="tc1">See what you can discover</a>
				</li>
				<li class="<?php if($tab == 2) echo current ;?>">
					<a href="<?php echo $pageUrl;?>?tab=2" id="tc2">Search, then order</a>
				</li>
				<li class="<?php if($tab == 3) echo current ;?>">
					<a href="<?php echo $pageUrl;?>?tab=3" id="tc3">Order straight from your family tree</a>
				</li>
				<li class="<?php if($tab == 4) echo current ;?>">
					<a href="<?php echo $pageUrl;?>?tab=4" id="tc4">FAQs</a>
				</li>
			</ul>
			<div id="main_content">
				<div class="tab_top"></div>
				
				<!-- tab 1-->
				<div id="tab_1" class="tab <?php if($tab != 1) echo inactive_tab ;?>">					
						 
					<p>With an England & Wales birth, marriage and death certificates you can discover a whole host of fascinating information that you won’t be able to find anywhere else. Unmarried mothers, unknown fathers, unnatural deaths… There’s so much waiting for you.</p>
					
					<div class="cert_box">
						<a title="Birth certificates include the  name, date and place of birth, father’s and mother’s names and occupations" href="<?php echo WWW_ROOT;?>images/products/BIRTHCERT.jpg" rel="tab1" class="prod_img"><img src="<?php echo WWW_ROOT;?>images/promo/cert_birth.jpg" alt="Birth Certificate" class="cert_img" /></a>
						<p><strong class="title"><a href="http://www.ancestry.co.uk/search/rectype/vital/freebmd/bmd.aspx">Birth certificates</a></strong> will give you information about:</p>
						<ul>
							<li>Child's name and place of birth</li>
							<li>Date of birth and registration</li>
							<li>Father's name, occupation and, after 1969, place of birth</li>
							<li>Mother's name, maiden name and, after 1984, occupation</li>
						</ul>
						<p><strong>Remember</strong> – a lack of father's name or an absence of a mother's married name could mean a child born out of wedlock.</p>
					</div>
					
					<div class="cert_box">
						<a title="Marriage certificates include  date and place of marriage, information about bride and groom, names of witnesses" href="<?php echo WWW_ROOT;?>images/products/MARRIAGECERT.jpg" rel="tab1" class="prod_img"><img src="<?php echo WWW_ROOT;?>images/promo/cert_marriage.jpg" alt="Marriage Certificate" class="cert_img" /></a>
						<p><strong class="title"><a href="http://www.ancestry.co.uk/search/rectype/vital/freebmd/bmd.aspx">Marriage certificates</a></strong> contain a wealth of information about the bride and groom – like name, age, marital status, occupation and where they were living at the time of marriage. They also include: </p>
						<ul>
							<li>Date and place of marriage</li>
							<li>Name and occupation of each party's father</li>
							<li>Names of witnesses</li>
							<li>The name of the person who solemnised the marriage.</li>

						</ul>
					</div>
					
					<div class="cert_box">
						<a title="Death certificates include  name, date and place of death, cause of death, age and much more" href="<?php echo WWW_ROOT;?>images/products/DEATHCERT.jpg" rel="tab1" class="prod_img"><img src="<?php echo WWW_ROOT;?>images/promo/cert_death.jpg" alt="Death Certificate" class="cert_img" /></a>
						<p><strong class="title"><a href="http://www.ancestry.co.uk/search/rectype/vital/freebmd/bmd.aspx">Death certificates</a></strong> will tell you: </p>
						<ul>
							<li>Name, date and place of death</li>
							<li>Date and place of birth (before 1969, only the age was recorded)</li>
							<li>Occupation and usual address</li>
							<li>Name of the person who gave information for the death registration.</li> 
						</ul>
						<p>They'll also give you the cause of death – this could include detailed information if an inquest has occurred.</p>
					</div>
					<div class="info_btm">
						<?php if($orderNowLink):?>
                        <!--<p class="ital"><a href="<?php echo WWW_ROOT;?>products/birth-marriage-death-certificates" id="index_info">If you have the Index information to hand, you can order a certificate straight away.</a></p>-->
                        
						<p class="pricing">From <?php echo $curSymbol.$standardPrice;?></p>
						<a href="<?php echo $searchUrl;?>" title="Search certificates now" class="search_now btn">Search now</a>
						<?php endif;?>
					</div>
					<?php if($discount['code']) include('include/bmd_2010_terms_ca.inc.php');?>
					
				</div><!-- end #tab_1-->
				
				<!-- tab 2-->
				<div id="tab_2" class="tab <?php if($tab != 2) echo inactive_tab ;?>">
					<p>You can order a certificate asssss soon as you've found who you're looking for at Ancestry.ca:</p>
					
					<div class="cert_box">
						<div class="col_a">
							<ol>
								<li><p>At the top of the page, click on 'Search' and then choose 'Birth, Marriage & Death, including Parish'.</p></li>
								<li><p>Enter as much information as you can.</p> </li>
								<li><p>When your search results appear, click on 'View Record'. You'll be able to see all the Index information available.</p></li>
								<li><p>Once you've found the record of the person you want to discover more about, just click on the 'Order' button on the left under 'Page Tools'.</p></li>
								<li><p>We'll automatically fill in all the required information from the BMD Index for the record you've found. We'll even double-check everything's correct before we request the certificate.</p> </li>
								<li><p>Then just decide what delivery service you'd like.</p></li>
							</ol>
							<p class="note">Once you've ordered your certificate, you'll receive your paper copy within 28 working days – 16 days if you choose Express Delivery. Please note: Orders placed after 1300 GMT will start to be processed on following working day.</p>
							<p class="digi_copy"><strong>For an extra <?php echo $curSymbol.$digiCopyPrice;?></strong>, you can also order an additional digital copy, which we'll send you ahead of your paper certificate. You can easily upload this and <a href="<?php echo $attachUrl;?>">attach it to your Ancestry family tree</a>, or any other tree you might have – without having to scan in your paper copy. For further details about digital certificates see our FAQs.</p>
						</div>
						<div class="col_b">
							<ul class="gallery">
								<li>
									<a href="<?php echo WWW_ROOT;?>images/promo/bmd2011/bmd-ca-search.jpg" class="prod_img" rel="tab2" title="You can order a certificate as soon as you've found who you're looking for at Ancestry.ca"><img src="<?php echo WWW_ROOT;?>images/promo/bmd2011/bmd-ca-search-thumb.jpg"/></a>
								</li>
								<li>
									<a href="<?php echo WWW_ROOT;?>images/promo/bmd2011/bmd-ca-record.jpg" class="prod_img" rel="tab2" title="Once you've found the record of the person you want to discover more about, just click on the 'Order' button on the left under 'Page Tools'"><img src="<?php echo WWW_ROOT;?>images/promo/bmd2011/bmd-ca-record-thumb.jpg"/></a>
								</li>
								<li>
									<a href="<?php echo WWW_ROOT;?>images/promo/bmd2011/bmd-ca-checkout.jpg" class="prod_img" rel="tab2" title="We'll automatically fill in all the required information from the BMD Index for the record you've found"><img src="<?php echo WWW_ROOT;?>images/promo/bmd2011/bmd-ca-checkout-thumb.jpg"/></a>
								</li>
							</ul>
						</div>
					</div>
					<div class="cert_box price_options">
						<?php include('include/bmd_2010_price_opts.inc.php');?>
						<p class="price_btns">
							<a href="<?php echo $searchUrl;?>" title="Start searching now" class="start_search btn">Start searching now</a>
						</p>
					</div><!--end .price_options-->
					<?php if($discount['code']) include('include/bmd_2010_terms_ca.inc.php');?>
				</div><!-- end #tab_2-->		
				
				<!-- tab 3-->
				<div id="tab_3" class="tab <?php if($tab != 3) echo inactive_tab ;?>">
					<p>If you've found your ancestors through our England and Wales BMD Index records and have attached the record to your Ancestry family tree, you can order a certificate straight away – it's that quick and easy.</p>
					
					<div class="cert_box">
						<div class="col_a">
							<ol>
								<li><p>Click on your chosen family member in your tree.</p></li>
								<li><p>Click on 'View profile', followed by the 'Facts and Sources' tab.</p></li>
								<li><p>To order a certificate, click on your chosen record, followed by the 'Order' button on the left under 'Page Tools'.</p></li>
								<li><p>We'll automatically fill in all the required information from the BMD Index. </p></li>
								<li><p>Then just decide what service you'd like.</p></li>
							</ol>
							<p class="note">Once you've ordered your certificate, you'll receive your paper copy within 28 working days – 16 days if you choose Express Delivery. Please note: Orders placed after 1300 GMT will start to be processed on following working day.</p>
							
							<div class="pricing">
								<a href="<?php echo $searchUrl;?>" title="Search certificates now" class="search_now btn">Search now</a>
								<p class="pricing">From <?php echo $curSymbol.$standardPrice;?></p>
							</div>
							
							<p class="digi_copy"><strong>For an extra <?php echo $curSymbol.$digiCopyPrice;?></strong>, you can also order an additional digital copy, which we'll send you ahead of your paper certificate. You can easily upload this and <a href="<?php echo $attachUrl;?>">attach it to your Ancestry family tree</a>, or any other tree you might have – without having to scan in your paper copy.</p>
						</div>
						<div class="col_b">
							<ul class="gallery">
								<li>
									<a href="<?php echo WWW_ROOT;?>images/promo/bmd2010/bmd4_l.jpg" class="prod_img" rel="tab3" title="If you've found your ancestors through our BMD Index records and have attached the record to your Ancestry family tree, you can order a certificate straight away"><img src="<?php echo WWW_ROOT;?>images/promo/bmd2010/bmd4.jpg"/></a>
								</li>
								<li>
									<a href="<?php echo WWW_ROOT;?>images/promo/bmd2010/bmd5_l.jpg" class="prod_img" rel="tab3" title="Once you've found the chosen family member in your tree. Select 'View profile', followed by the 'Facts and Sources' tab"><img src="<?php echo WWW_ROOT;?>images/promo/bmd2010/bmd5.jpg"/></a>
								</li>
								<li>
									<a href="<?php echo WWW_ROOT;?>images/promo/bmd2011/bmd-ca-record.jpg" class="prod_img" rel="tab3" title="To order a certificate, click on your chosen record, followed by the 'Order' button on the left under 'Page Tools'"><img src="<?php echo WWW_ROOT;?>images/promo/bmd2011/bmd-ca-record-thumb.jpg"/></a>
								</li>
							</ul>
						</div>
					</div>
					
					<div class="cert_box price_options">
						<?php include('include/bmd_2010_price_opts.inc.php');?>
						<p class="price_btns">
							<a href="<?php echo $searchUrl;?>" title="Search certificates now" class="search_now btn">Search now</a>
							<!--<a href="http://trees.ancestry.co.uk/Default.aspx?req=tree" title="Order from your family tree now" class="order_from btn">Order from your family tree now</a>-->
						</p>
					</div><!--end .price_options-->
					<?php if($discount['code']) include('include/bmd_2010_terms_ca.inc.php');?>
				</div><!-- end #tab_3-->
				
				<!-- tab 4-->	
				<div id="tab_4" class="tab <?php if($tab != 4) echo inactive_tab ;?>">
					<?php include('include/faq.inc.php');?>
					<?php if($discount['code']) include('include/bmd_2010_terms_ca.inc.php');?>
				</div><!-- end #tab_4-->
								
				
			</div><!--end #main_content-->
			<?php include('include/footer.inc.php');?>
		</div><!--end #content_wrapper-->
		
	</div><!--end #container-->
	<?php include('../include/analytics.inc.php');?>
</body>
</html>
