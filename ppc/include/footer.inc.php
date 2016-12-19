<div id="footer" class="grid_12">
	<ul id="footer_links" class="grid_7 alpha omega">
		<li class="first"><a href="<?php echo WWW_ROOT;?>aboutus" title="About Us">About Us</a></li>
		<li><a href="<?php echo WWW_ROOT;?>customer_service#contact" title="Contact Us">Contact Us</a></li>
		<li><a href="http://www.ancestry.co.uk/home/partner/uk/uk_affiliate_home.aspx" title="Affiliate Programme">Affiliate Programme</a></li>
		<li><a href="http://www.ancestry.co.uk/advertising/uk/default.aspx" title="Advertising">Advertising</a></li>
		<li><a href="<?php echo WWW_ROOT;?>privacy_policy" title="Privicy Statement">Privacy Statement</a></li>
		<li class="last"><a href="<?php echo WWW_ROOT;?>tcs" title="Terms and Conditions">Terms and Conditions</a></li>
	</ul>
	<div id="other_sites" class="grid_3 alpha omega">
		<p>Visit other Ancestry sites:</p>
		<p><a id="other_sites_link" href="other_sites"><?php echo $homeSite ? $homeSite : 'Ancestry.co.uk';?></a></p>
		<?php include_once('../include/other_sites.inc.php');?>
	</div>
	<div id="cards" class="grid_2 alpha omega">
		<p>We accept these cards:<br/><img src="<?php echo WWW_ROOT;?>images/layout/cards.png" alt="VISA, Mastercard, Delta, Maestro, Solo, VISA Electron" /></p>
	</div>
</div><!--end #footer-->