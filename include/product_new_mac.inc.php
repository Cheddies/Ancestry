<?php include_once('include/page_pre_process.inc.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="description" content="<?php echo $pageData['meta']['description'];?>" />
	<meta name="keywords" content="<?php echo $pageData['meta']['keywords'];?>" />
	<?php //don't cache https pages
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'):?>
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="no-cache" />	
	<?php endif;?>
	<title><?php echo $pageData['meta']['title'];?></title>
	<link href="<?php echo WEBROOT;?>css/960.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo WEBROOT;?>css/default.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo WEBROOT;?>css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo WEBROOT;?>css/video.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo WEBROOT;?>script/edgeward/jquery.fancybox-1.2.1.pack.js"></script>
	<script type="text/javascript" src="<?php echo WEBROOT;?>script/edgeward/anc_shop.js"></script>
	<script type="text/javascript" src="<?php echo WEBROOT;?>script/omniture003.js"></script>
	<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>
 	<link rel="stylesheet" type="text/css" href="<?php echo WEBROOT;?>landing/files/ancGlobal.css">

<style type="text/css">
                body { font: 12px/18px Tahoma, Geneva, sans-serif; color: #333333;  }
                p { padding-top: 5px; margin-bottom: 10px; }
	            .panA-r0 { width: 972px; height: auto; postiton: relative; margin: 10px auto; }
	            .panA-r0 .top { width: 972px; height: 0px; }
	            .panA-r0 .bottom { width: 972px; height: 0px; }
	            .panA-r0 .content { width: 972px; }
	            .panA-r0 .content .lefPan { width: 972px; height: auto; }
	            .panA-r0 .content .ritPan { display: none; }
	            /* TODO: Is this the correct background image */
	            body { background: #E2DECD url( http://c.mfcreative.com/offer/offer-page/2009/bodyGrdnt.gif ) repeat-x left top; }
            </style>

<link href="<?php echo WEBROOT;?>landing/files/landing.css" rel="stylesheet">
<script type="text/ecmascript">
// LET CMS DETERMINE DOMAIN ENVIRONMENT TO USE
var storeOrigin = "http://store.ancestry.com";
var ancOrigin = "http://www.ancestry.com";
// FLASH EMBED SCRIPT
var AC_FL_RunContent = 0;
</script>
<script src="<?php echo WEBROOT;?>landing/files/AC_RunActiveContent.js" language="javascript"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo WEBROOT;?>landing/files/BrightcoveExperiences.js"></script>
<script type="text/ecmascript" src="<?php echo WEBROOT;?>landing/files/jquery.min.js"></script>
<script type="text/ecmascript" src="<?php echo WEBROOT;?>landing/files/product_dev_06.js"></script>
<script type="text/ecmascript">
$(function() {
	
	// TABS
	$(".tabContent:not(.activeTab)").css("display","none");
	$(".tab").unbind("click").click(function() {
		var index = $(this).index();
		$(".tab").removeClass("activeTab");
		$(this).addClass("activeTab");
		$(".tabContent").slideUp(300);
		$(".tabContent:eq("+index+")").slideDown(300);
	});
	
	// INITIALIZE MODALS
	if(!Modal){Modal = function(){};}
	thumbnailTriggers = Modal({selector:".modal",targets:[
								"<?php echo WEBROOT;?>landing/files/map.jpg",
								"<?php echo WEBROOT;?>landing/files/organize.jpg",
								"<?php echo WEBROOT;?>landing/files/share.jpg"
								]});
	magnifierTriggers = Modal({selector:".zoom",targets:[
								"<?php echo WEBROOT;?>landing/files/map.jpg",
								"<?php echo WEBROOT;?>landing/files/organize.jpg",
								"<?php echo WEBROOT;?>landing/files/share.jpg"
								]});
	videoTriggers = Modal({type:"html",selector:".ftmVideoThumb",targets:".ftmVideo"});
});
</script>
</head>

        
        
        <body>
  <div id="container" class="container_12">          
<?php include('include/page_pre_process.inc.php');?>
	<?php include('include/page_header.inc.php');?>
	<?php include('include/nav.inc.php');?>

            <div class="ContentBG"></div>




	<div id="content_wrapper" class="grid_12 alpha omega">


            <div class="panA-r0" style="width:920px;">
                <div class="top" style="width:920px;"></div>
                <div class="content clearfix" style="width:920px;">
                    <div class="lefPan" style="width:920px;">
                        <div style="null">
                        	<!--[if IE 7 ]><div id="mainContent" class="ie ie7 singleProductContainer" style="width:920px;"><![endif]-->
<!--[if IE 8 ]><div id="mainContent" class="ie ie8 singleProductContainer"  style="width:920px;"><![endif]-->
<!--[if gte IE 9 ]><div id="mainContent" class="ie ie9 singleProductContainer"  style="width:920px;"><![endif]-->
<!--[if !(IE)]><!--> <div id="mainContent" class="singleProductContainer"  style="width:920px;"> <!--<![endif]-->
    <div id="productBanner" class="clearfix">
        <div style="float:left; width: 332px;">

    	<img id="productImage" src="<?php echo $lgImg;?>" alt="<?php echo $product['name'];?>" border="0" style="max-width:332px;">

        </div>

        <div class="productInfo" style="width:540px;">
            <div class="productName"></div>
            <div class="productByline"><h2 style="color:#556B11;"><?php echo $product['name'];?></h2></div>
            <div style="font-size:16px; font-weight:bold; color:#695e49;">Update your family tree from anywhere with NEW TreeSync&trade;!</div>
            <div class="productDescription">
                <p>Imagine one version of your family tree that you can update from anywhere and always know you're accessing or sharing the very latest. New Family Tree Maker for Mac 2 with TreeSync&trade; allows you to easily update your tree online from your desktop, your laptop, even your iPhone or iPad - and then simply click to sync so your tree is always up to date, no matter where you or your loved ones access it next.</p>
                
            </div>
            <div class="pricing">
            <?php if($product['compareprice']):?>
                <div class="regularPrice">
                    <span style="font-family:Georgia, 'Times New Roman', Times, serif; color:#bbb0a3; font-size: 16px;">RRP <del>&pound;<?php echo number_format($product['compareprice'],2);?></del></span>
                </div>
                <?php endif;?>
                <div class="discountedPrice">
                    <span style="color:#e6821f; font-family:Tahoma; font-size:18px; font-weight:bold;"><?php if($product['compareprice']):?>NOW <?php endif;?>&pound;<?php echo number_format($product['price'],2);?></span>
             	</div>
        	</div>
            <a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" class="buyNow ancBtn orange lrg"><span></span><em>Order Now</em><span></span></a>
            <br>
        </div><!-- END OF PRODUCT INFO -->
        <img id="btmRtLeaves" src="<?php echo WEBROOT;?>images/products/<?php echo $product['number'];?>_side.jpg" alt="" border="0">
    </div><!-- END OF PRODUCT BANNER -->
    <div id="tabContainer">
    	<div id="tabNavBar">
        	<div class="tab activeTab">What's new</div>
        	<div class="tab">Top 10 Reasons</div>
            <div class="tab">System Requirements</div>
            <div class="tab">Contents At A Glance</div>
        </div>
        <div id="tabContentContainer">
        	<div class="tabContent activeTab whatsNew">
				<h3>What else is new in Family Tree Maker&reg; for Mac 2 with TreeSync&trade;</h3>
				<ul>
					<li><strong>New Mac-Only Features</strong> — Capture photos using your iSight or built-in camera and import them directly into Family Tree Maker. And if you’re using OS X Lion, you can take advantage of the new full-screen capability—with one click fill your entire desktop with the Family Tree Maker workspace.</li>
					<li><strong>Easy-to-understand Combined Family View</strong> — The family group view has a new “blended families” option that lets you display all of a couple’s children in one location. An icon next to a child’s name lets you see at a glance whether he or she is the child of the father, the mother, or both parents.</li>
					<li><strong>More Ancestry Integration</strong> — Ancestry.com has millions of members all over the world. And now you can find out which members are searching for your ancestors by viewing Member Connect activity in the expanded Web Dashboard. You’ll also see links to message boards and notification of your new Ancestry messages.</li>
				</ul>
				<ul>
					<li><strong>Enhanced Performance</strong> — Now you can choose the type of Internet connection you’re using, which allows Family Tree Maker to tailor how it downloads information from the Web. Uploading and downloading speeds have been improved, and there is an increased ability to upload large files to Ancestry.co.uk.</li>
					<li><strong>New Image Collection</strong> — Create beautiful family trees and reports with a variety of new backgrounds and images.
				</ul>
				<div style="clear:both;"></div>
			</div>
        	<div class="tabContent reasonsTab" style="display: none; ">
            	<h3>10 more reasons to get the new Family Tree Maker for Mac 2 with TreeSync:</h3>
            	<table id="reasonsTable" style="border:0px none;">
                	<tbody>
                    	<tr>
                        	<td>
                                <img class="modal" src="../../landing/files/FTM-map-thmb.jpg">
                                <div class="zoom"></div>
                            	<div>
                                	<div><strong>1. Access interactive street and satellite maps</strong></div>
                                	View important locations from your ancestors' lives, or create a map showing their journeys across the globe.
                                </div>
                            </td>
                        	<td>
                            	<img class="modal" src="../../landing/files/FTM-organize-thmb.jpg">
                                <div class="zoom"></div>
                                <div>
                                	<div><strong>2. Easily organise media</strong></div>
                                	Add photos, documents, audio and video in one place. Link your files to several people in your tree and include them in charts and reports.
                                    </div>
                            </td>
                        	<td>
                            	<div>
                                	<img class="modal" src="../../landing/files/FTM-share-thmb.jpg">
                                    <div class="zoom"></div>
                                	<div><strong>3. Share your work with others</strong></div>
Use templates to create attractive family trees, or design your own. Then improve your charts with personal backgrounds and family photos.
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="border:0px none;">
                	<tbody><tr>
                    	<td>
                        	<ol style="counter-reset:4;" start="4" class="customOL">
                            	<li><strong>Discover new family members</strong> - <span>Get Ancestry.co.uk Hints within Family Tree Maker, to point you towards new discoveries.</span></li>
                            	<li><strong>Simplify source creation</strong> - <span>Use templates to source everything from online databases to vital records.</span></li>
                            	<li><strong>Use standard location names</strong> - <span>Use the place authority database to enter place names consistently and in a standard format.</span></li>
                        	</ol>
                        </td>
                    	<td>
                        	<ol style="counter-reset:8;" start="8" class="customOL">
                            	<li><strong>Explore data like never before</strong> - <span>Use new and improved reports to gather information and export it in a variety of ways. Then save your settings, and apply them time after time.</span></li>
                            	<li><strong>Navigate your tree with ease</strong> - <span>View several generations at once, find any ancestor with the click of the mouse, and easily add or edit life events.</span></li>
                            	<li><strong>Import data from other genealogy programs</strong> - <span>Open files created in Legacy&trade; Family Tree, The Master Genealogist&trade; and FamilySearch&trade; Personal Ancestral Files.</span></li>
                        	</ol>
                        </td>
                    </tr>
                </tbody></table>
            </div>
            <div class="tabContent reqTab" style="display: none; ">
				<h3>Recommended System Requirements:</h3>
				<ul>
					<li>Mac OS X 10.5 or later, including OS X Lion</li>
					<li>Intel-based Mac</li>
					<li>DVD Drive</li>
					<li>All online features require Internet access</li>
				</ul>
            </div>
            <div class="tabContent contTab" style="display: none; ">
            	<h3 style="margin-bottom: 15px;"><?php echo $product['name'];?> - Contents at a glance</h3>
				<ul>
					<li>6 Months Premium Membership to Ancestry.co.uk™* - Worth over £77&dagger;&dagger;</li>
					<li>Printed Companion Guide to Family Tree Maker® for Mac 2 - Learn Family Tree Maker's most popular features with quick lessons that make research fast and fun.</li> 
					<li>A fantastic selection of genealogy offers from our trusted partners.</li>
				</ul>
				<p>* Subscription Activation - Terms and Conditions apply</p>
				<p>The Ancestry.co.uk&trade; 'Premium' Membership begins upon activation of the 6-month free subscription with a valid credit or debit card, but no money will be taken during the free subscription period. To ensure uninterrupted service, your membership will be automatically renewed at the end of the free subscription period for a further 6 months of paid subscription. To cancel your membership without incurring a charge to your credit or debit card, go to www.ancestry.co.uk, simply login and go to 'My Account', click on 'Cancel Subscription' at least 2 days before the free subscription is due to end, otherwise the full price of a 6-month 'Premium' membership will be automatically debited from your credit or debit card. Assuming that you do not cancel, at the end of the 6 months paid subscription period your membership will roll over into an annual membership that will automatically renew at the end of each year, unless you cancel before each renewal. Use of Ancestry.co.uk is subject to our Terms and Conditions and Privacy Policy. See www.ancestry.co.uk for full details</p>
				<p>&dagger;&dagger; Based on the price of a monthly Premium package subscription at Ancestry.co.uk&trade; as of 6th September 2011.</p>
            </div>

            
        </div>
    </div>
</div></div>
                    </div>
                </div>
		<?php include('include/page_footer.inc.php');?>