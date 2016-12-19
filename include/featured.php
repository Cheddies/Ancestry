<div id="featured_title">
Ancestry recommends...</div>
<?php

$products_per_line=3;
$count=0;
 
$feat=array();

$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

$clean = array();

if(isset($_GET['master'])&& is_numeric($_GET['master']))
{
	$clean['master']=form_clean($_GET['master'],4);
	
	if(strlen($clean['master'])==0)
		$clean['master']=0;
}
else
	$clean['master']=0;

$mysql=array();
$mysql['master']=mysql_real_escape_string($clean['master'],$link);

$query = "SELECT * FROM featured LEFT JOIN tbl_departments ON tbl_departments.deptcode=featured.dept LEFT JOIN tbl_products On tbl_products.number=featured.number WHERE dept={$mysql['master']} ORDER BY featnum ASC";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
while($line=mysql_fetch_array($result,MYSQL_ASSOC))
{
	$product_link="number=" . urlencode ( htmlentities("{$line['number']}")) ."&amp;qty=1";
	$product_image=GetImageName($line["inetthumb"],"Thumb","images/products/thumbnail/",$line["number"]);
		
			
	if(!isset($clean['page_dept']))
		$clean['dept']=GetDept($line['number']);
		
	if(!isset($clean['page_master']))
		$clean['master']=GetMaster($clean['dept']);
		
	if(isset($clean['master']))
		$product_link=$product_link . "&amp;master=" . $clean['master'];
	if(isset($clean['dept']))
		$product_link=$product_link . "&amp;dept=" . $clean['dept'];
	
	$pricetoshow = formatcurrency($line["price"]) ;
	// If the item is on sale display the "Was Now" Price.
	if ($line["price"] < $line["compareprice"]) {
		$pricetoshow = "<span class='was_price'>" . formatcurrency($line["compareprice"]) . "</span> <span class='now_price'>Now: " . formatcurrency($line["price"]) ."</span>";
	}
	$Euro=ConvertToEuro($line["price"]);
	$Dollar=ConvertToDollar($line["price"]);
	
	
	if(($count%3)==0)
	{
	?>
	<div style="clear:both">
	</div>
	<?php
	}
	$count++;
?>

	<div class="ProductListingDetail">

	<div class="ProductListingImage">
			<a href="productdetail.php?<?php echo $product_link?>"><img src="<?php Escaped_Echo( GetImageName($line["inetthumb"],"Thumb","images/products/",$line["number"]));?>"  alt="<?php Escaped_Echo( formatdescription($line["inetsdesc"])); ?>" /></a>
		</div>
		<div class="ProductListListingHeader">
			<a href="productdetail.php?<?php echo $product_link?>"><?php Escaped_Echo(formatdescription($line["inetsdesc"])); ?></a>
		</div>
		<div class="ProductListDesc">				
			<?php Escaped_Echo(formatDescription($line["inetshortd"])); ?>
		</div>
		<div class="ProductListPrice">
			<?php 
			if ($line["mastersub"] == 'M' && $line['price']==0)
			{	
				echo "£ Varies";
			}
			else 
			{ 
				echo $pricetoshow; 
			?>
			<span class="alt_price">
				<?php
				if( PRICES && ( !isset($_SESSION['prices']) || ( isset($_SESSION['prices']) && $_SESSION['prices']==true)  ) )
				{
					if(PRICES && isset($_SESSION['alt']))
					{
						switch($_SESSION['alt'])
						{
							case DOLLAR:
								Escaped_Echo ("(".$Dollar.")*");
							break;
							case EURO:
								Escaped_Echo ("(".$Euro.")*");
							break;
							case NONE:
							break;
						}
					}
				}
				?>
			</span>
			<?php
			}
			?>
		</div>
		<div class="ProductListAddToBasket">
			<span class="ProductListDetails">
				<a href="productdetail.php?<?php echo $product_link?>"><img src="images/details.gif" alt="Details" /></a> &nbsp;	
			</span>
			<?php	
			if(strtolower($line['presell'])=="per")
			{
			?>
				<a href="productdetail.php?<?php echo $product_link?>"><img src="images/personalize.gif" alt="Personalize <?php echo formatdescription($line["inetsdesc"]); ?>"  /></a>
			<?php
			}
			else
			{
				if ($line["mastersub"] == 'M')
				{
				?>
					<a href="productdetail.php?<?php echo $product_link?> " ><img src="images/addtobasket_small.gif" alt="Add <?php echo formatdescription($line["inetsdesc"]); ?> To Basket"  /></a>
				<?php
				}
				else
				{
				?>
					<a href="addtobasket.php?code=<?php echo urlencode ( htmlentities("{$line['number']}" )) ?>"><img src="images/addtobasket_small.gif" alt="Add <?php echo formatdescription($line["inetsdesc"]); ?> To Basket" /></a>
				<?php
				}
			}
			?>
		</div>
	</div><!-- end of product detail -->
<?php
	$count++;
}	
?>