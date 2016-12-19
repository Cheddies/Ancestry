<?php
//change these entries
$promos = array(1 => array('title' => 'Who Do You Think You Are? Encyclopedia of Genealogy','image' => 'whodo.swf',
		'link' => WEBROOT . 'products/books/who-do-you-think-you-are-encyclopedia-of-genealogy','shortdesc'=>'Encyclopedia of Genealogy'),
	2 => array('title' => 'Ancestry.co.uk Getting Started Pack','image' => 'giftpack.swf',
		'link' => WEBROOT . 'products/gifts/ancestry-co-uk-getting-started-pack','shortdesc'=>'Getting Started Pack'),
	3 => array('title' => 'Ancestry.co.uk DNA Gift Pack','image' => 'dna_banner.swf',
		'link' => WEBROOT . 'products/gifts/dna-gift-pack','shortdesc'=>'DNA Gift Pack'),
	4 => array('title' => 'Order Birth, Marriage and Death Certificates Online','image' => 'bmd.swf',
		'link' => WEBROOT . 'certificates','shortdesc'=>'Birth, Marriage &amp; Death Certificates')
	);
$selected = rand(1, 4);
if(isset($_GET['pr'])){
	$prNum = clean($_GET['pr'],1);
	$prNum = $prNum*1;
	if($prNum >=1 && $prNum <=4 ){
		$selected = $prNum;
	}
}
$promoLinks = '';
?>
<div id="top_adspace" class="grid_9 alpha omega">
	<?php foreach($promos as $i => $promo):
	if($i != $selected){
		$style = ' style="display:none"';
	} else {
		$style = '';
	}
	?>
	<div id="home_promo_<?php echo $i;?>" class="home_promo"<?php echo $style;?>>
		<object width="700" height="184">
		<param name="movie" value="<?php echo $promo['image'];?>">
		<embed src="images/banners/homepage/<?php echo $promo['image'];?>" width="700" height="184">
		</embed>
		</object>
	</div>
	<!--<div id="home_promo_<?php echo $i;?>" class="home_promo"<?php echo $style;?>>
		<a href="<?php echo $promo['link'];?>"><img src="images/banners/homepage/<?php echo $promo['image'];?>" alt="<?php echo $promo['title'];?>" /></a>
	</div>-->
	<?php 
	$class = $selected == $i ? 'class="current"' : '';
	$linkLoc = WEBROOT . "index?pr=$i";
	$promoLinks .= "<li id=\"promo_link_$i\"><a href=\"$linkLoc\" $class>{$promo['shortdesc']}</a></li>";
	endforeach;?>
	
	<ul id="promo_links">
		<?php echo $promoLinks;?>
	</ul>
</div>