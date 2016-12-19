<ul id="nav" class="grid_12 alpha omega">
	<li><a href="<?php echo WEBROOT;?>index" id="nav-home"<?php if($Page == 'index') echo ' class="current"';?>>Home</a></li>
	<li><a href="<?php echo WEBROOT;?>products/birth-marriage-death-certificates"<?php if($Page == 'birth-marriage-death-certificates') echo ' class="current"';?>>Certificates</a></li>
	<li><a href="<?php echo WEBROOT;?>products/software"<?php if($Page == 'software') echo ' class="current"';?>>Software</a></li>
	<li><a href="<?php echo WEBROOT;?>products/books"<?php if($Page == 'books') echo ' class="current"';?>>Books</a></li>
	<li><a href="<?php echo WEBROOT;?>products/gifts"<?php if($Page == 'gifts') echo ' class="current"';?>>Gifts</a></li>
	<li><a href="<?php echo WEBROOT;?>dna"<?php if($Page == 'dna') echo ' class="current"';?>>DNA</a></li>
	<li><a href="<?php echo WEBROOT;?>my-canvas"<?php if($Page == 'my-canvas') echo ' class="current"';?>>My Canvas</a></li>
	<li class="nav_last"><a href="<?php echo WEBROOT;?>customer_service"<?php if($Page == 'customer_service') echo ' class="current"';?>>Customer Service</a></li>
	<li id="my_basket"><?php echo $basketText;?></li>
</ul>