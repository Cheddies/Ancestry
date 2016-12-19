<ul id="nav" class="grid_12 alpha omega">
	<li><a href="<?php echo WEBROOT;?>index" id="nav-home"<?php if($Page == 'index') echo ' class="current"';?>>Home</a></li>
	<!--<li><a href="<?php echo WEBROOT;?>products/sale"<?php if($Page == 'sale') echo ' class="current"';?>>Sale</a></li>-->
	<li><a href="<?php echo WEBROOT;?>products/birth-marriage-death-certificates/birth-certificate"<?php if($Page == 'birth-marriage-death-certificates/birth-certificate') echo ' class="current"';?>>Birth Certificate</a></li>
	<li><a href="<?php echo WEBROOT;?>products/birth-marriage-death-certificates/marriage-certificate"<?php if($Page == 'birth-marriage-death-certificates/marriage-certificate') echo ' class="current"';?>>Marriage Certificate</a></li>
	<li><a href="<?php echo WEBROOT;?>products/birth-marriage-death-certificates/death-certificate"<?php if($Page == 'birth-marriage-death-certificates/death-certificate') echo ' class="current"';?>>Death Certificate</a></li>
	<!--<li><a href="<?php echo WEBROOT;?>products/software"<?php if($Page == 'software') echo ' class="current"';?>>Family Tree Maker</a></li>-->
	<!--<li><a href="<?php echo WEBROOT;?>products/books"<?php if($Page == 'books') echo ' class="current"';?>>Books</a></li>-->
	<!--<li><a href="<?php echo WEBROOT;?>products/gifts"<?php if($Page == 'gifts') echo ' class="current"';?>>Gifts</a></li>-->
	<!--<li><a href="<?php echo WEBROOT;?>products/courses"<?php if($Page == 'courses') echo ' class="current"';?>>Courses</a></li>-->
	<!--<li><a href="<?php echo WEBROOT;?>my-canvas"<?php if($Page == 'my-canvas') echo ' class="current"';?>>My Canvas</a></li>-->
	<!--<li class="nav_last"><a href="<?php echo WEBROOT;?>customer_service"<?php if($Page == 'customer_service') echo ' class="current"';?>>Customer Service</a></li>-->
	<li id="my_basket"><?php echo $basketText;?></li>
</ul>