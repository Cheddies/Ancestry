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
					
			<script type='text/javascript' src='http://edgeward.co.uk/ancestry/SurnameBookWidget.js'></script>
			<script type='text/javascript'>
				TGN.OurHistory.SurnameBookWidget.initialize('en-GB', 'SurnameWidget');
			</script>
			<!-- -->
            <div id="surname_offer">
               <!-- <h2>SAVE &pound;3* off the Our Name in History book</h2>
                <p>To claim your Surnamebook discount enter in the shopping basket the promotion code: ANCESTRY</P>
<p>* Terms and Conditions<br/>

1. Offer is open to UK residents only.<br/>

2. Offer only valid at www.Amazon.co.uk.<br/>

3. The discount can be used up to and including 31st May 2011.</p>-->
            </div>
		</div>
		<?php include_once('include/page_footer.inc.php');?>		
	</div><!--end #content_wrapper-->