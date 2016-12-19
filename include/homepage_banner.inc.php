<?php
//change these entries
$promos = array(
	/*2 => array('title' => 'Family Tree Maker 2012','image' => 'ftm_platinum_2012.jpg',
		'link' => WEBROOT . 'products/software/family-tree-maker-2012-platinum-edition','shortdesc'=>'Family Tree Maker 2012'),*/
		
	/*1 => array('title' => 'Family Tree Maker','image' => 'FTM2014.jpg',
		'link' => WEBROOT . 'products/software','shortdesc'=>'Family Tree Maker'),*/		
		
	/*4 => array('title' => 'Hall of Names','image' => 'hall-of-names.gif',
		'link' => WEBROOT . 'products/gifts/hall-of-names','shortdesc'=>'Hall of Names'),*/
	
	/*4 => array('title' => 'Discover Your Ancestors Bookazine','image' => 'DYA_800x184.jpg',
		'link' => WEBROOT . 'products/books/dya-bookazine','shortdesc'=>'Discover Your Ancestors Bookazine'),*/
	
	2 => array('title' => 'Hall Of Names','image' => 'HALLOFNAMES_NEW.jpg',
		'link' =>  'http://www.ancestryshop.co.uk/products/gifts/hall-of-names','shortdesc'=>'Hall Of Names'),
			
	/*1 => array('title' => 'Living Relative Search','image' => 'lrs-shop-banner.swf',
		'link' => 'https://www.livingrelativesearch.co.uk/','shortdesc'=>'Living Relative Search'),*/
		
	1 => array('title' => 'Order Birth, Marriage and Death Certificates Online','image' => 'bmd.swf',
		'link' => WEBROOT . 'certificates','shortdesc'=>'Birth, Marriage &amp; Death Certificates')
	);
//$selected = rand(1, 4);
$selected = 1;
if(isset($_GET['pr'])){
	$prNum = clean($_GET['pr'],1);
	$prNum = $prNum*1;
	if($prNum >=1 && $prNum <=2 ){
		$selected = $prNum;
	}
}
$promoLinks = '';
?>
<div id="top_adspace" class="grid_9 alpha omega">
	<?php
	$i = 1;
	while($i <= 2):
		if($i != $selected){
			$style = ' style="display:none"';
		} else {
			$style = '';
		}
		?>
		<div id="home_promo_<?php echo $i;?>" class="home_promo"<?php echo $style;?> rev="<?php echo $promos[$i]['link'];?>">
			<?php if(stripos($promos[$i]['image'], '.swf') !==false):?>
			<object width="700" height="184" class="promo_movie" id="promo_movie" data="images/banners/homepage/<?php echo $promos[$i]['image'];?>">
			<param name="movie" value="<?php echo $promos[$i]['image'];?>"/>
			<param name="wmode" value="opaque"/>
			<embed src="images/banners/homepage/<?php echo $promos[$i]['image'];?>" width="700" height="184" wmode="opaque" >
			</embed>
			</object>
			<?php else:?>
			<a href="<?php echo $promos[$i]['link'];?>"><img src="images/banners/homepage/<?php echo $promos[$i]['image'];?>" alt="<?php echo $promos[$i]['title'];?>" /></a>
			<?php endif;?>
		</div>
		<?php
		$class = $selected == $i ? 'class="current"' : '';
		$linkLoc = WEBROOT . "index?pr=$i";
		$promoLinks .= "<li id=\"promo_link_$i\"><a href=\"$linkLoc\" $class>{$promos[$i]['shortdesc']}</a></li>";
		$i++;
	endwhile;?>
	
	<ul id="promo_links">
		<?php echo $promoLinks;?>
	</ul>
</div>