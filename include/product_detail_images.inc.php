<div id="med_img_wrapper">
	<a id="med_prod_image" href="<?php echo $lgImg;?>" class="med_image"><img src="<?php echo $medImg;?>" alt="<?php echo $product['inetsdesc'];?>" /></a>	
</div>
<p>Click for larger image</p>
<?php if(count($images) >1):?>
	<ul id="product_thumbs">
		<?php foreach($images as $image):?>
		<li><a class="prod_thumb" href="<?php echo "{$prodDetailLink}?img=" . prodImage($image['filename'],'small',false);?>"><img src="<?php echo prodImage($image['filename'],'small');?>" alt="<?php echo $product['inetsdesc'];?>" /></a></li>
		<?php endforeach;?>
	</ul>
<?php endif;?>