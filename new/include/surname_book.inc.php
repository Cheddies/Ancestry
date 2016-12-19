<?php include ('include/header.php');?>
	<?php include_once('include/page_header.inc.php');?>
	<?php include_once('include/nav.inc.php');?>
	<div id="content_wrapper" class="grid_12 alpha omega">
		<div id="page_content" class="grid_12">
			<!-- Div where the widget will go -->
			<div id="SurnameWidget"></div>
			<!-- -->
			<!-- Script include tags and initialize code -->
			<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/yahoo/yahoo-min.js"></script>
			<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/get/get-min.js"></script>
			<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
					
			<script type='text/javascript' src='http://c.mfcreative.com/ComBiz/OurHistory/SurnameBook/Scripts/SurnameBookWidget.js'></script>
			<script type='text/javascript'>
				TGN.OurHistory.SurnameBookWidget.initialize('en-GB', 'SurnameWidget');
			</script>
			<!-- -->
		</div>
		<?php include_once('include/page_footer.inc.php');?>		
	</div><!--end #content_wrapper-->