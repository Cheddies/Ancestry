<?php include ('include/header.php');

?>

<h1>Site Map</h1>
<div id="site_map">
<ul>
	<li><a href="index.php">Home</a>
		<ul>
			<li><a href="products.php?master=1001">Mens Collection</a>
				
				<?php
				ListDepts(1001);
				?>
				
			</li>
			<li><a href="products.php?master=1002">Ladies Collection</a>
				
				<?php
				ListDepts(1002);
				?>
			
			</li>
			<li><a href="products.php?master=1003">Childrens Collection</a>
				
				<?php
				ListDepts(1003);
				?>	
				
			</li>
		</ul>
	</li>
</ul>
<ul>
	<li><a href="basket.php">Basket</a></li>
	
</ul>
<ul>
	<li><a href="privacy.php">Privacy Policy</a></li>
</ul>

<ul>
	<li><a href="refund.php">Refund Policy</a></li>
</ul>

<ul>
	<li><a href="tcs.php">Terms and Conditions</a></li>
</ul>

<ul>
	<li><a href="delivery.php">Delivery Information</a></li>
</ul>



</div>

<a href="index.php"><img id="back_button" src="images/back.gif" alt="Back" /></a>

<?php include ('include/footer.php');?>