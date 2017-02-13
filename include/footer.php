
	</div><!--end #container-->
	<div id="tooltip_box">
	<div id="tt_top"></div>
	<div id="tt_container"></div>
	<div id="tt_btm"></div>
</div>
<?php
//if the test site address
//then don't include any tracking code
if( $_SERVER['HTTP_HOST']== 'www.ancestryshop.co.uk' || $_SERVER['HTTP_HOST']== 'ancestryshop.co.uk'):
	//check for $pageTitle var
	//if not set, then use the one from IL header $title
	if(!isset($pageTitle))
	{
//		$pageTitle=$title;
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
<?php include_once('include/tagman.inc.php');?>
</body>
</html>
