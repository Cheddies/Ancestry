<?php
if(
isset($clean['niceodrnum']) &&
isset($clean['amount']) &&
isset($clean['city']) &&
isset($clean['state']) &&
isset($clean['country']) && 
isset($clean['ga_code'])
) 
{
?>
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	var pageTracker = _gat._getTracker("<?php echo $clean['ga_code']?>");
	pageTracker._setDomainName("none");
	pageTracker._setAllowLinker(true);
	pageTracker._initData();
	pageTracker._trackPageview();
	
	pageTracker._addTrans(
	    "<?php echo $clean['niceodrnum']?>",                                     // Order ID
	    "",                            // Affiliation
	    <?php echo $clean['amount']?>,                                    // Total
	    "",                                     // Tax
	    "",                                        // Shipping
	    "<?php echo $clean['city']?>",                                 // City
	    "<?php echo $clean['state']?>",                               // State
	    "<?php echo $clean['country']?>"                                       // Country
	);
	
	
	pageTracker._trackTrans();
	
	</script>
<?php
} 
?>