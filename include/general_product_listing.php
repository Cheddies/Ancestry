<?php 

	if(isset($_GET['master']))
	{
		$clean['master']=form_clean($_GET['master'],4);
	}
	if(isset($_GET['dept']))
	{
		$clean['dept']=form_clean($_GET['dept'],4);
	}

	

	
?>
<?php
	//Generate a unique token
	//to be used to check the input is 
	//from this form on the proccessing page
	$token=UniqueToken();//will be passed in a hidden form element to proccessing page
	
	//Store the token in the session so it can
	//be checked on the proccessing page
	$_SESSION['token']=$token;
	
	//Also store the time 
	//which can be used to check
	//the lifetime of the token
	$_SESSION['token_time']=time();

	
	$pageinationcount = 1;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		
		
		$product_link="";
		if(isset($clean['master']) && isset($clean['dept']))
		{
			$product_link=$product_link . "&amp;master=" . $clean['master'];
			$product_link=$product_link . "&amp;dept=" . $clean['dept'];
		}
		else
		{
			$dept=getDept($line['number']);
			$master=getMaster($dept);
			$product_link=$product_link . "&amp;master=" . $master .  "&amp;dept=" . $dept ;
		}
		
		
		$pricetoshow = formatcurrency($line["price"]) ;
		// If the item is on sale display the "Was Now" Price.
		if ($line["price"] < $line["compareprice"]) {
				$pricetoshow = "<span class='was_price'>" . formatcurrency($line["compareprice"]) . "</span> <span class='now_price'>Now: " . formatcurrency($line["price"]) ."</span>";
		}
		$Euro=ConvertToEuro($line["price"]);
		$Dollar=ConvertToDollar($line["price"]);
		// If statement to display only the items on this page
		if (($pageinationcount >= $startindex) && ($pageinationcount <= $endindex)) {
			$AText= CheckStockDate($line['expectdate'],$line['units'],$line['mastersub']);
?>
		<?php
		/*if($count==0)
		{
		?>
		<div class="ProductListingLine"><!-- Output at start of loop -->
		<?php
		}*/
		?>
			<div class="ProductListingDetail">
				<div class="ProductListingImage">
					<a href="productdetail.php?<?php echo $product_link?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>"><img src="<?php Escaped_Echo( GetImageName($line["inetthumb"],"Thumb","images/products/",$line["number"]));?>"  alt="<?php Escaped_Echo( formatdescription($line["inetsdesc"])); ?>" /></a>
				</div>
				<div class="ProductListListingHeader">
					<a href="productdetail.php?<?php echo $product_link?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>"><?php Escaped_Echo(formatdescription($line["inetsdesc"])); ?></a>
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
						<a href="productdetail.php?<?php echo $product_link?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>"><img src="images/details.gif" alt="Details" /></a> &nbsp;	
					</span>
					<?php	
					if(strtolower($line['presell'])=="per")
					{
					?>
						<a href="productdetail.php?<?php echo $product_link?>&amp;qty=1&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )?> " ><img src="images/personalize.gif" alt="Personalize <?php echo formatdescription($line["inetsdesc"]); ?>"  /></a>
					<?php
					}
					else
					{
					if ($line["mastersub"] == 'M') {?>
						<a href="productdetail.php?<?php echo $product_link?>&amp;qty=1&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )?> " ><img src="images/addtobasket_small.gif" alt="Add <?php echo formatdescription($line["inetsdesc"]); ?> To Basket"  /></a>
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
		/*if($count==PRODUCTS_PER_ROW)
		{
		?>
		</div><!-- Output at end of loop -->
		<?php
		$count=0;
		}*/
		}
		$pageinationcount++;
		
?>
		

	<?php
		}
		mysql_free_result($result);
	?>

	<?php
	//check to see if ending div needs to be outputed
	if($count!=0)
	{
	?>
		</div>
	<?php
	}
	?>	
