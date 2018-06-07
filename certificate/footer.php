<div id="newfoot">
	<div class="nf-secure">
    <img src="images/edgeward/securetrading-img.jpg" alt="Secure Trading" style="float: right; margin-left: 10px;" />
    <p>Shop with us with confidence and peace of mind</p>
        
    </div>
    <div class="nf-contact">
    	<p>If you have any questions then don't hesitate to call us on</p>
        <span class="nf-tel">0345 688 5114</span><br />
        <p>0900 - 1700 UK GMT</p>
    </div>
    <div class="nf-pay">
    	<p><span class="nf-paytitle">Many ways to pay</span></p>
        <p>We accept these forms of payment<br />
        <img src="images/edgeward/new_cards.jpg" alt="Methods of payment" style="padding-top:5px;" /></p>
    </div>
<br class="clear" />
</div>
<div id="shop_footer">
<p><a href="/aboutus" onclick="s_objectID='corp_foot'">About Us</a>&nbsp;&nbsp;<a href="customer_service.php?#contact" onclick="s_objectID='contact_foot'" >Contact Us</a></p>
			<p class="tnclinks">&copy;&nbsp;2017,&nbsp;Ancestry.com &nbsp;
				<a id="ctl08_ctl00_m_privacy" onclick="s_objectID='privacy_foot'" href="privacy_policy.php">Privacy Policy</a> | <a id="ctl08_ctl00_m_terms" onclick="s_objectID='terms_foot'" href="tcs.php">Terms and Conditions</a>
			</p>
			
				<div class="genlinkdiv">

				<img src="images/pixel.gif" style="height:1px;width:1px;border-width:0px;" alt="" />
				</div>
			
		</div><!-- end .links -->
	
	
</div>
<script type="text/javascript" src="script/global_footer.js"></script>



</div><!-- Container -->
<div id="tooltip_box">
	<div id="tt_top"></div>
	<div id="tt_container"></div>
	<div id="tt_btm"></div>
</div>



<?php
//if the test site address
//then don't include any tracking code
if( $_SERVER['HTTP_HOST']== 'www.ancestryshop.co.uk'):

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
	if($PAGE!="summary.php" && $PAGE!="order_summary.php"):?>
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
	<?php endif;?>
	
	<script type="text/javascript">
	
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-16478574-1']);
	_gaq.push(['_setAllowLinker', true]);
	_gaq.push(['_setAllowHash', false]);
	_gaq.push(['_trackPageview']);
	
	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	
	</script>
<?php
endif;//end of if to stop tracking code on test site

?>
<?php include_once('../include/tagman.inc.php');?>
</body>
</html>
<?php
if($_SERVER['HTTP_HOST']== 'www.ancestryshop.co.uk' && !in_array($pageSuffix, array('_us','_aus','_ca'))) require_once("ClickTale/ClickTaleBottom.php");
?>
