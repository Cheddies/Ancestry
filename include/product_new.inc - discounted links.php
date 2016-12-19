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
            <div class="productByline"><h2 style="color:#83381f;"><?php echo $product['name'];?></h2></div>
            <!--<div style="font-size:16px; font-weight:bold; color:#695e49;">Everything you've been waiting for is almost here.</div>-->
            <div class="productDescription">
                <p>The new Family Tree Maker is here and now is your chance to experience its powerful, easy-to-use tools and new, time saving features.</p>
                <p>New Family Tree Maker&reg; 2014 with TreeSync&trade; allows you to easily update your tree online from your desktop or your laptop then simply click to sync so your tree is always up-to-date, no matter where you or your family access it next.</p>
                <!--<p><strong>Terms and conditions apply</strong></p>
                <ul>
                <li>&bull; 25% off RRP of your selected product. 25% discount applies to purchases of new Family Tree maker Deluxe, Platinum and World versions only.</li>
                <li>&bull; Delivery within UK only.</li>
                <li>&bull; Offer expires 23 December 2013 5pm GMT.</li>
                <li>&bull; Order by 2pm GMT on 18th December 2013 for delivery on or before 24 December 2013 via Standard Delivery.</li>
                </ul>-->
                <!--<p><strong>Pre order your copy today!</strong></p>-->
                <!--<p><a href="<?php echo WEBROOT;?>tcs">Terms and Conditions apply</a></p>-->
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
        	if($product['number']=='FTMUPGRADE2014')
        	{
	        ?>
	        <a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" id="shipBtn" class="shipBtn ancBtn orange lrg"><span></span><em>Ship It</em><span></span></a>
			<a href="https://shop.avanquest.com/store2/ancestry_transition.php?i=9974553&preflanguage=1&rs2=AQ_UK_DTC_ANCESTRYUK&rs4=DTC_ANCESTRYUK" id="downloadBtn" class="downloadBtn ancBtn orange lrg"><span></span><em>Download</em><span></span></a>
	        <?php	
        	}
        	elseif($product['number']=='FTMDELUXE2014')
        	{
	        ?>
	        <a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" id="shipBtn" class="shipBtn ancBtn orange lrg"><span></span><em>Ship It</em><span></span></a>
	        <a href="https://shop.avanquest.com/store2/ancestry_transition.php?i=9974556&preflanguage=1&rs2=AQ_UK_DTC_ANCESTRYUK&rs4=DTC_ANCESTRYUK&cc=FTM25" id="downloadBtn" class="downloadBtn ancBtn orange lrg"><span></span><em>Download</em><span></span></a>
	        <?php	
        	}
        	elseif($product['number']=='FTMPLATINUM2014')
        	{
	        ?>
	        <a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" id="shipBtn" class="shipBtn ancBtn orange lrg"><span></span><em>Ship It</em><span></span></a>
	        <a href="https://shop.avanquest.com/store2/ancestry_transition.php?i=9974554&preflanguage=1&rs2=AQ_UK_DTC_ANCESTRYUK&rs4=DTC_ANCESTRYUK&cc=FTM25" id="downloadBtn" class="downloadBtn ancBtn orange lrg"><span></span><em>Download</em><span></span></a>
	        <?php	
        	}
        	elseif($product['number']=='FTMWW2014')
        	{
	        ?>
	        <a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" id="shipBtn" class="shipBtn ancBtn orange lrg"><span></span><em>Ship It</em><span></span></a>
	        <a href="https://shop.avanquest.com/store2/ancestry_transition.php?i=9974557&preflanguage=1&rs2=AQ_UK_DTC_ANCESTRYUK&rs4=DTC_ANCESTRYUK&cc=FTM25" id="downloadBtn" class="downloadBtn ancBtn orange lrg"><span></span><em>Download</em><span></span></a>
	        <?php	
        	}
        	else{
	        ?>
	        <a href="<?php echo WEBROOT;?>addtobasket.php?code=<?php echo $product['number'];?>" class="buyNow ancBtn orange lrg"><span></span><em>Order Now</em><span></span></a>
	        <?php
        	}
           	?>
            <br>
        </div><!-- END OF PRODUCT INFO -->
        <!--<img id="btmRtLeaves" src="<?php echo WEBROOT;?>images/products/<?php echo $product['number'];?>_side.jpg" alt="" border="0">-->
    </div><!-- END OF PRODUCT BANNER -->
    <div id="tabContainer">
    	<div id="tabNavBar">
        	<div class="tab activeTab">What's new</div>
        	<div class="tab">Top 10 Reasons</div>
            <!--<div class="tab">Tutorials</div>-->
            <div class="tab">System Requirements</div>
            <div class="tab">Contents At A Glance</div>
            <div class="tab">Membership Details</div>
        </div>
        <div id="tabContentContainer">
        	<div class="tabContent activeTab whatsNew">
            	<h3>What's new in Family Tree Maker&reg;?</h3>
              	<ul>
              
              		<li><strong>New Family View</strong> - see your family tree in a new way. This additional view makes navigating easier, especially when you want to see extended family members.</li>
              		<li><strong>Improved TreeSync</strong> - lets you easily synchronize your tree in Family Tree Maker with an online Ancestry.co.uk tree.</li>
              		    <ul>
                      	<li>A more robust TreeSync&trade; lets you sync even more of your family tree info.
                      	<li>Easily share your tree with your family and work on it together. Family and friends can view the online version of your tree without software or a subscription.
                      	<li>Collaborate with the world's largest online family history resource. Keep your online tree private or make it public so that others researching your family can find you. You may connect with others who have insight on your ancestors, discover rare family photos, or even find relatives you didn't know you had.
                      	</ul>
              		<li><strong>More organizational tools</strong> - stay organized with new tools that let you sort children automatically by birth order and view people by location, grouping them by country, state, county, and city.</li>
              		<li><strong>New and improved charts and reports</strong> - more options and views let you display an individual's ancestors, spouses, and children together. Also, the Index of Individuals Report has been expanded with options for anniversary, birthday, and contact lists, and more.</li>
                	<li><strong>New tree branch export</strong> - a new export option makes it much simpler to export a single branch of your tree.</li>
                	<li><strong>More editing options</strong> - save time with the ability to copy and paste facts including related source citations, media items, and notes.</li>
				</ul>
				<ul>
                <img id="productImage" src="../../landing/files/ftm_2014_whats_new.jpg" alt="Family Tree Maker" border="0">
                </ul> 
		<!--<div class="ftmVideoThumb" title="Family Tree Maker 2012 - Video(1:27)" style="clear:none; width:380px; height:230px; margin-top:2em;"><img src="../../landing/files/ftm_video_thumb.jpg" /></div>
                <div class="ftmVideo">
                	<object id="myExperience1258993738001" class="BrightcoveExperience">
			  <param name="bgcolor" value="#FFFFFF" />
			  <param name="width" value="720" />
			  <param name="height" value="404" />
			  <param name="playerID" value="891668712001" />
			  <param name="playerKey" value="AQ~~,AAAAo9jbQUk~,UYqC5EgW06DyerSStWJowt9QMelw92WY" />
			  <param name="isVid" value="true" />
			  <param name="isUI" value="true" />
			  <param name="dynamicStreaming" value="true" />
			  <param name="@videoPlayer" value="1258993738001" />
			</object>
		</div>-->
                <div style="clear:both;"></div>
            </div>
        	<div class="tabContent reasonsTab" style="display: none; ">
            	<h3>10 more reasons to get the new Family Tree Maker:</h3>
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
                            	<li><strong>Create Smart Stories</strong> - <span>Transform facts from your tree into stories that update automatically as you change your tree.</span></li>
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
        	<!--<div class="tabContent tutorialsTab clearfix" style="display: none; ">
                <div align="center">
                  <!--url's used in the movie-->
                  <!--text used in the movie-->
                  <!-- saved from url=(0013)about:internet -->
                  <!--<script language="javascript">
                    if (AC_FL_RunContent == 0) {
                        alert("This page requires AC_RunActiveContent.js.");
                    } else {
                        AC_FL_RunContent(
                            'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
                            'width', '800',
                            'height', '600',
                            'src', 'http://c.mfcreative.com/offer/shop/tutorials/tgn_tutorial',
                            'quality', 'high',
                            'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
                            'align', 'middle',
                            'play', 'true',
                            'loop', 'true',
                            'scale', 'showall',
                            'wmode', 'window',
                            'devicefont', 'false',
                            'id', 'tgn_tutorial',
                            'bgcolor', '#000000',
                            'name', 'http://c.mfcreative.com/offer/shop/tutorials/tgn_tutorial',
                            'menu', 'true',
                            'allowFullScreen', 'false',
                            'allowScriptAccess','sameDomain',
                            'movie', 'http://c.mfcreative.com/offer/shop/tutorials/tgn_tutorial',
                            'salign', ''
                            ); //end AC code
                    }
                </script><noscript>
                       </noscript>
                </div>
            </div>-->
        	<div class="tabContent reqTab" style="display: none; ">
            	<h3>Recommended System Requirements:</h3>
                <ul>
                	<li>Windows&reg; Vista&reg; (32-bit or 64-bit), Windows&reg; 7 (32-bit or 64-bit) or Windows&reg; 8 (32-bit or 64-bit)</li>
                	<li>Hard disk space: 675 MB for installation</li>
                	<li>Memory: 1 GB RAM</li>
                	<li>Display 1024 x 768 resolution</li>
                	<li>DVD Drive</li>
                	<li>Internet connection required</li>
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