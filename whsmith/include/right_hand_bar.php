<div id="right_hand_bar">

		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" type="application/x-shockwave-flash" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" title="" width="210" height="195" >
			<param name="movie" value="flash/dig_300x250uk.swf?clickTag=http://landing.ancestry.co.uk/offers/uk/dna.aspx&amp;target='_self'">
			<param name="quality" value="high">
			<param name="wmode" value="opaque">
			<param name="allowScriptAccess" value="always">
			<param name="base" value="http://www.ancestry.co.uk/">
			<embed allowscriptaccess="always" base="http://www.ancestry.co.uk/" src="flash/dig_300x250uk.swf?clickTag=http://landing.ancestry.co.uk/offers/uk/dna.aspx&amp;target=%27_self%27" wmode="opaque" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="210" height="195">
		</object>
		<a href="productdetail.php?master=3&dept=4&number=GIFTPACK"><img src="images/banners/internal_1.gif" alt="Share the addiction. Get your loved ones the Ancestry Subscription Gift Pack Only £35.99. Order Now" /></a>
	<?php

		if(isset($clean['dept']))
		{
			$file="right_bar/dept_right_hand_" . $clean['dept'] .".html";
			if(is_file($file))
				include($file);
		}

	?>
</div>