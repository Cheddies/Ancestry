<div id="shop_footer">
	<div id="g_footer" class="g_container">
		<div id="sites" class="acom">
		
			<h5>Visit Other Ancestry Sites</h5>
			<ul>
				<li><a href="#nogo" id="droplink"><em>Ancestry.co.uk</em></a>

					<!--[if lte IE 6]><table><tr><td><![endif]-->
					<div id="droplist">
						<dl id="sites-int">
							<dt>Ancestry International</dt>
							<dd class="site-us">
								
									<a href="http://www.ancestry.com/HomeRedirect.aspx?ref=5538"><em>United States</em></a>
								
							</dd>
							<dd class="site-uk">

								
										<a href="http://www.ancestry.co.uk" onclick="s_objectID='UK_foot'" ><em>United Kingdom</em></a>
								
							</dd>
							<dd class="site-ca">
								
										<a href="http://www.ancestry.ca" onclick="s_objectID='CA_foot'" ><em>Canada</em></a>
								
							</dd>
							<dd class="site-au">
								
										<a href="http://www.Ancestry.com.au" onclick="s_objectID='AU_foot'" ><em>Australia</em></a>

								
							</dd>
							<dd class="site-de">
								
										<a href="http://www.Ancestry.de" onclick="s_objectID='DE_foot'" ><em>Deutschland</em></a>
								
							</dd>
							<dd class="site-it">
								
										<a href="http://www.ancestry.it" onclick="s_objectID='IT_foot'" ><em>Italia</em></a>
								
							</dd>
							<dd class="site-fr">

								
										<a href="http://www.ancestry.fr" onclick="s_objectID='FR_foot'" ><em>France</em></a>
								
							</dd>
							<dd class="site-se">
								
										<a href="http://www.ancestry.se" onclick="s_objectID='SE_foot'" ><em>Sverige</em></a>
								
							</dd>
						</dl>
						<dl id="sites-tgn">
							<dt>Other Ancestry.com Sites</dt>

							<dd class="site-acom">
								
									<a href="http://www.ancestry.com/HomeRedirect.aspx?ref=5538"><em>Ancestry.com</em></a>
								
							</dd>
							<dd class="site-mfam"><a href="http://www.myfamily.com/"><em>Myfamily.com</em></a></dd>
							<dd class="site-gcom"><a href="http://www.genealogy.com/"><em>Genealogy.com</em></a></dd>                                      
							<dd class="site-rweb"><a href="http://www.rootsweb.com/"><em>Rootsweb.com</em></a></dd>                                      
							<dd class="site-tgn"><a href="http://www.tgn.com/"><em>TGN.com</em></a></dd>

							<dd class="site-ftm"><a href="http://www.familytreemaker.com/"><em>FamilyTreeMaker.com</em></a></dd>
							<dd class="site-amag"><a href="http://www.ancestrymagazine.com/"><em>Ancestrymagazine.com</em></a></dd>
						</dl>
					</div>
					<!--[if lte IE 6]></td></tr></table><![endif]-->
				</li>
			</ul>
		
			<div class="logo">

				
			</div><!-- end .logo -->
		</div><!-- #sites -->
		

		<div class="links">
			
			<dl>
				<dd class="firstlink">
					
					<a href="aboutus.php" onclick="s_objectID='corp_foot'">About Us</a>
					
				</dd>

				<dd>
					
					<a href="http://www.ancestry.co.uk/home/partner/uk/uk_affiliate_home.aspx" onclick="s_objectID='affiliate_foot'" >Affiliate Programme</a>
					
				</dd>
				
				<dd>
					
					<a href="customer_service.php?#contact" onclick="s_objectID='contact_foot'" >Contact Us</a>
					
				</dd>
			</dl>
			<p>

				&copy;&nbsp;2008,&nbsp;Ancestry.com &nbsp;
				<a id="ctl08_ctl00_m_privacy" onclick="s_objectID='privacy_foot'" href="privacy_policy.php">PRIVACY STATEMENT</a> | <a id="ctl08_ctl00_m_terms" onclick="s_objectID='terms_foot'" href="tcs.php">Terms and Conditions</a>
			</p>
			
				<div class="genlinkdiv">

				<img src="images/pixel.gif" style="height:1px;width:1px;border-width:0px;" alt="" />
				</div>
			
		</div><!-- end .links -->
	</div><!-- end #g_footer -->
	<div id="footer_logos">
		<a target="_blank" href="http://www.securetrading.com"><img src="images/ST_Merchant_logo.jpg" alt="Safe Payments By Secure Trading" /></a>
	</div>
	
</div>
<script type="text/javascript" src="script/global_footer.js"></script>
</div><!-- Container -->

<?php 
//if the test site address
//then don't include any tracking code
if( $_SERVER['HTTP_HOST']== 'www.ancestryshop.co.uk'):
	//check for $pageTitle var
	//if not set, then use the one from IL header $title
	if(!isset($pageTitle))
	{
		$pageTitle=$title;
	}
	
	if  ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'))
	{
		//changed so js is stored on our server
		
	?>
	<script type='text/javascript'><!--
	var s_account = "tgnshopsuki";
	//--></script>
	<script type='text/javascript' src='https://a248.e.akamai.net/7/248/7051/v001/origin.c.mfcreative.com/js/omniture004.js?v=5'></script>
	<script type='text/javascript'>
		s.pageName=document.title;
		var s_code=s.t();if(s_code)document.write(s_code);
	</script>
	<?php
	}
	else
	{
	?>
	<script type='text/javascript'><!--
	var s_account = "tgnshopsuki";
	//--></script>
	<script type='text/javascript' src='http://c.mfcreative.com/js/omniture004.js?v=5'></script>
	<script type='text/javascript'>
		s.pageName=document.title;
		var s_code=s.t();if(s_code)document.write(s_code);
	</script>
	<?php
	}
	?>
	
	<?php
	
	//Google analytics code added earlier in page on checkout2.php
	//due to the javascript call needed for links between domains
	
	$PAGE = basename($_SERVER['PHP_SELF']);
	if($PAGE!="checkout2.php" && $PAGE!="order_summary.php")
	{
	?>
		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		var pageTracker = _gat._getTracker("UA-810272-11");
		pageTracker._setDomainName("none");
		pageTracker._setAllowLinker(true);
		pageTracker._initData();
		pageTracker._trackPageview();
		</script>
	<?php 
	}
endif;//end of if to stop tracking code on test site

?>
</body>
</html>