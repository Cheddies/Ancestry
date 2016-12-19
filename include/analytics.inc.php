<?php
if( $_SERVER['HTTP_HOST']== 'www.ancestryshop.co.uk' || $_SERVER['HTTP_HOST']== 'ancestryshop.co.uk'):?>

	<?php //omnature tracking code;?>
	<script type='text/javascript'><!--
	var s_account = "tgnshopsuki";
	//--></script>
	<script type='text/javascript' src='http://c.mfcreative.com/js/omniture004.js?v=5'></script>
	<script type='text/javascript'>
		s.pageName=document.title;
		var s_code=s.t();if(s_code)document.write(s_code);
	</script>
	
	<?php //google analytics tracking code;?>
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