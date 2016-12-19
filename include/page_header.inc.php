<div id="header" class="grid_12">
	<div id="logo_area" class="grid_7 alpha">
		<h1><a href="<?php echo WEBROOT;?>index" id="logo">Ancestry Shop</a></h1>
	</div>
	<div id="site_search" class="grid_5 omega">
		<form action="<?php echo WEBROOT;?>products/search" method="get" id="product_search">
			<div class="input text" id="search_input">
				<label for="search" id="search_label">Search the shop</label><input type="text" name="searchterm" id="search" maxlength="50"/>
			</div>
			<!--<input type="hidden" name="search_token" value="95c27eabc333a0394c9d0134e9b1e0dd" />-->
			<input type="image" id="submit_search" src="<?php echo WEBROOT;?>images/layout/btn-search.png"/>
		</form>
	</div>
</div><!--end #header-->