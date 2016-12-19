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
	            
	            /*Addition for Download / Ship It Buttons*/	    
			    a.ancBtn.lrg.downloadBtn > em { padding: 0 8px 0 30px; position:relative;}
			    a.ancBtn.lrg.shipBtn > em { padding: 0 8px 0 30px; position:relative;}
			   	#shipBtn em:before {background: url(../../images/buttonIcons.png) repeat 0 0 transparent;content: " ";left: 0;padding: 11px;position: absolute;top: 10px;}
				#downloadBtn em:before {background: url(../../images/buttonIcons.png) repeat 22px 0 transparent;content: " ";left: 0px;padding: 11px 11px;position: absolute;top: 10px;}  
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
            <!--<div style="font-size:16px; font-weight:bold; color:#695e49;">Update your family tree from anywhere with NEW TreeSync&trade;!</div>-->
            <div class="productDescription">
                <p>Packed with dozens of exciting new features and enhancements to simplify your tasks, the new Family Tree Maker Mac 3 makes building and sharing your family tree easier than ever. Enjoy a new family view that gives a broader view of your tree, explore enhanced charts and reports, and save time with new organisational tools such as family child sort. And the more robust TreeSync&trade; lets you sync more of your tree information, so you always know you’re accessing and sharing the very latest.</p>
                
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

        	<?php
        	//download links addition ....
        	/*if($product['number']=='FTMFORMAC3V')
        	{*/
	        ?>
	        <!--<a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" id="shipBtn" class="shipBtn ancBtn orange lrg"><span></span><em>Ship It</em><span></span></a>-->
			<!--<a href="https://shop.avanquest.com/store2/ancestry_transition.php?i=9969571&preflanguage=1&rs2=AQ_UK_DTC_ANCESTRYUK&rs4=DTC_ANCESTRYUK" id="downloadBtn" class="downloadBtn ancBtn orange lrg"><span></span><em>Download</em><span></span></a>-->
	        <?php	
        	/*}
        	else{*/
	        ?>      	
            <a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" class="buyNow ancBtn orange lrg"><span></span><em>Order Now</em><span></span></a>
            <?php
        	/*}*/
           	?>
            <br>
        </div><!-- END OF PRODUCT INFO -->
        <!--<img id="btmRtLeaves" src="<?php echo WEBROOT;?>images/products/<?php echo $product['number'];?>_side.jpg" alt="" border="0">-->
    </div><!-- END OF PRODUCT BANNER -->
    <div id="tabContainer">
    	<div id="tabNavBar">
        	<div class="tab activeTab">What's new</div>
        	<div class="tab">Top 10 Reasons</div>
            <div class="tab">System Requirements</div>
            <div class="tab">Contents At A Glance</div>
            <div class="tab">Membership Details</div>
        </div>
        <div id="tabContentContainer">
        	<div class="tabContent activeTab whatsNew">
				<h3>What's new in Family Tree Maker&reg; for Mac 3</h3>
				<ul>
					<li><strong>New Family view</strong> — enjoy a broader view of your family tree, which makes navigating your tree and seeing extended family members easier.</li>
					<li><strong>New tree branch export</strong> — a new, simplified export option makes it much easier to export a single branch of your tree to Family Tree Maker or a GEDCOM file.</li>
					<li><strong>More organisational tools</strong> — it’s easy to stay organised with our new global and family child sort feature. Save time with the option to sort children automatically by birth order and view people by location, grouping them by country, state, county, and city.</li>
					<li><strong>Mac and PC compatible files</strong> - now you can open your Family Tree Maker tree on a Mac or PC without needing to convert it.</li>
					<li><strong>New and improved charts and reports</strong> - more options and views let you display an individual’s ancestors, spouses, and children together. Also, the Index of Individuals Report has been expanded with options for anniversary, birthday, contact lists, and more.</li>
					<li><strong>More editing options</strong> - save time with the ability to copy and paste facts including linked source citations, media items, and notes.</li>
				</ul>
				<ul>
					<li><strong>Improved TreeSync</strong> — lets you easily synchronise your tree in Family Tree Maker with an online Ancestry.com tree.</li>
						<ul>
                      	<li>Now you can synchronise even more of your family tree information such as photos, documents, source template data, and customised notes.</li>
						<li>Access and update your family tree from anywhere — your computer, Ancestry.com, even your iPhone, iPad, or Android device.</li>
						<li>Easily share your tree with your family and work on it together. Family and friends can view the online version of your tree without software or a subscription.</li>
						<li>Collaborate with the largest, most active family history community in the world on Ancestry.com. Keep your online tree private or make it public so that others researching your family can find you. You may connect with others who have insight on your ancestors, discover rare family photos, or even find relatives you didn’t know you had.</li>
						</ul>
					<li><strong>Merge duplicate facts</strong> — This time saver helps you merge duplicate facts with just a quick click, without losing any information.</li>
				</ul>
				<div style="clear:both;"></div>
			</div>
        	<div class="tabContent reasonsTab" style="display: none; ">
            	<h3>10 more reasons to get the new Family Tree Maker for Mac 3:</h3>
            	<table id="reasonsTable" style="border:0px none;">
                	<tbody>
                    	<tr>
                        	<td>
                                <img class="modal" src="../../landing/files/FTM-map-thmb.jpg">
                                <div class="zoom"></div>
                            	<div>
                                	<div><strong>1. Access interactive street and satellite maps</strong></div>
                                	View important locations from your ancestors' lives, or create a map showing their journeys across the globe. Or just round town.
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
                            	<li><strong>Create books</strong> - <span>Publish beautiful keepsakes and books to share with friends and family. You can even customise fact sentences so your genealogy reports read just the way you’d like them to.</span></li>
                            	<li><strong>Simplify source creation</strong> - <span>Use templates to source everything from online databases to vital records.</span></li>
                            	<li><strong>Use standard location names and description fast fields</strong> - <span>Use the locations database to enter place names consistently and in a standard format. And when entering facts, new “fast fields” makes adding descriptions quick and consistent.</span></li>
                        	</ol>
                        </td>
                    	<td>
                        	<ol style="counter-reset:8;" start="8" class="customOL">
                            	<li><strong>Explore data like never before</strong> - <span>Use new and improved reports to gather information and export it in a variety of ways. Then save your settings, and apply them time after time.</span></li>
                            	<li><strong>Navigate your tree with ease</strong> - <span>View several generations at once, find any ancestor with the click of the mouse, and easily add or edit life events.</span></li>
                            	<li><strong>Import data from other genealogy programs</strong> - <span>Open files created in Family Tree Maker for Windows, or import GEDCOM files created in Reunion&reg; and other programs. </span></li>
                        	</ol>
                        </td>
                    </tr>
                </tbody></table>
            </div>
            <div class="tabContent reqTab" style="display: none; ">
				<h3>Recommended System Requirements:</h3>
				<ul>
					<li>Mac OS X 10.6 or later, including OS X 10.9 Mavericks</li>
					<li>Intel-based Mac</li>
					<li>DVD Drive</li>
					<li>All online features require Internet access</li>
				</ul>
            </div>
			
            <div class="tabContent contTab" style="display: none; ">
            	<h3 style="margin-bottom: 15px;"><?php echo $product['name'];?> - Contents at a glance</h3>
				<?php echo $product['inetfdesc'];?>
            </div>

             <div class="tabContent membershipTab" style="display: none; ">
            	<p>The included Ancestry.co.uk&trade; membership begins upon activation with a valid credit or debit card. Unless you cancel before the end of your included membership period, a paid membership will commence at the end of the included membership period and the relevant price will be debited from your credit or debit card. Your membership will then automatically renew at the end of each membership period at the same price unless you cancel at least two days before your renewal date. To cancel visit the My Account section of Ancestry.co.uk or call 0800 404 9723.</p>
            </div>
            
                       
        </div>
    </div>
</div></div>
                    </div>
                </div>
		<?php include('include/page_footer.inc.php');?>