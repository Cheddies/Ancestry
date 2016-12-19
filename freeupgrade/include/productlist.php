<div id="products">
<?php
	
	if( 
		(isset($_GET['master']))
		&&
		(is_numeric($_GET['master']))
		&& 
		(isset($_GET['dept']))
		&&
		(is_numeric($_GET['dept']))
	  )
	{
		$clean=array();	
		$clean['master']=form_clean($_GET['master'],4);
		$clean['dept']=form_clean($_GET['dept'],4);
		
	$count=0;
	// If the page is a sub department fetch the reults from the database
	if( isset($clean['dept']) && (is_numeric($clean['dept'])) ) {
								
		// Connecting, selecting database
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
		
		//Clean vars for use with mysql	
		$mysql=array( );	
		$mysql['department'] = mysql_real_escape_string($clean['dept'],$link);
		
		// Performing SQL query

		// Obey Stock
		//$query = "SELECT tbl_products.number, tbl_products.inetsdesc, tbl_products.price, tbl_products.compareprice, tbl_products.inetshortd, tbl_products.inetthumb, tbl_products.mastersub FROM tbl_products INNER JOIN tbl_products_departments ON tbl_products.number=tbl_products_departments.number WHERE (tbl_products.units > 0 OR tbl_products.mastersub = 'M') AND deptcode=" . $department . " AND tbl_products.mastersub <> 'S' AND tbl_products.inetsell = 1 ORDER BY tbl_products_departments.disp_seq;";

		//Do not obey Stock
		$SELECT = "SELECT tbl_products.expectdate,tbl_products.units, tbl_products.number, tbl_products.inetsdesc, tbl_products.price, tbl_products.compareprice, tbl_products.inetshortd, tbl_products.inetthumb, tbl_products.mastersub,presell";
		
		if(isset($_GET['sort']) &&  isset($_GET['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_GET['token'],$_SESSION['token'],$_SESSION['token_time'],0))
		{
			$clean['sort']=form_clean($_GET['sort'],1);
			switch($clean['sort']){
			case 1:
				$query = $SELECT . " FROM tbl_products INNER JOIN tbl_products_departments ON tbl_products.number=tbl_products_departments.number WHERE (tbl_products.units > 0 OR lower(tbl_products.presell) = 'pre'  OR lower(tbl_products.presell = 'per') OR tbl_products.mastersub='M') AND deptcode=" . $mysql['department'] . " AND tbl_products.mastersub <> 'S' AND tbl_products.inetsell = 1 ORDER BY tbl_products.price ASC;";
				break;
			case 2:
				$query = $SELECT . " FROM tbl_products INNER JOIN tbl_products_departments ON tbl_products.number=tbl_products_departments.number WHERE (tbl_products.units > 0 OR lower(tbl_products.presell) = 'pre'  OR lower(tbl_products.presell = 'per') OR tbl_products.mastersub='M') AND deptcode=" . $mysql['department'] . " AND tbl_products.mastersub <> 'S' AND tbl_products.inetsell = 1 ORDER BY tbl_products.price DESC;";
				break;
			default:
				$query = $SELECT . "  FROM tbl_products INNER JOIN tbl_products_departments ON tbl_products.number=tbl_products_departments.number WHERE (tbl_products.units > 0 OR lower(tbl_products.presell) = 'pre'  OR lower(tbl_products.presell = 'per') OR tbl_products.mastersub='M') AND deptcode=" . $mysql['department'] . " AND tbl_products.mastersub <> 'S' AND tbl_products.inetsell = 1 ORDER BY tbl_products_departments.disp_seq;";
				break;
			}
		}
		else
		{
			$query = $SELECT . "  FROM tbl_products INNER JOIN tbl_products_departments ON tbl_products.number=tbl_products_departments.number WHERE (tbl_products.units > 0 OR lower(tbl_products.presell) = 'pre'  OR lower(tbl_products.presell = 'per') OR tbl_products.mastersub='M') AND deptcode=" . $mysql['department'] . " AND tbl_products.mastersub <> 'S' AND tbl_products.inetsell = 1 ORDER BY tbl_products_departments.disp_seq;";
		}
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		// Closing connection
		mysql_close($link);
		$ItemsInDept=mysql_num_rows($result);
		
		$pagesindept = totalpages(mysql_num_rows($result), $productsperpage);
	}
	else
	{
		//Error with department 
		//So default to no items in the department
		$ItemsInDept=0;
	}

	// Get the page number, if there is none it must be page 1
	if ( isset($_GET['page']) && is_numeric($_GET['page']) ) {
		$clean['pagenumber']=form_clean($_GET['page'],4);
		$startindex = (($clean['pagenumber']-1) * $productsperpage) + 1;
	}
	else {
		$clean['pagenumber'] = 1;
		$startindex= 1;
	}

	$endindex  = $startindex + ($productsperpage-1);
?>

<?php
$file=basename($clean['dept']);
$file="include/depttext/" . $file .".html";
if(is_file($file))
	include($file);
?>

<?php
//If there are products in this dept
if($ItemsInDept>0)
{
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
?>
<!-- Sorting bit -->
<!--
<div id="ProductListingSort">
	<form action="products.php" method="get">
	<input type="hidden" name="token" value="<?php echo $token?>" />
	<?php
	if(isset($clean['master']))
	{
	?>
		<input type="hidden" name="master" value="<?php Escaped_Echo( $clean['master']);?>" />
	<?php
	}
	?>
		<input type="hidden" name="dept" value="<?php Escaped_Echo( $clean['dept']);?>" />
		<label for="sort">Order by :</label><select id="sort" name="sort">
						<option <?php if ( (isset($clean['sort'])) && ($clean['sort']==1) ) echo "Selected"?> value="1">Lowest Price</option>
						<option <?php if ( (isset($clean['sort'])) && ($clean['sort']==2) ) echo "Selected"?> value="2">Highest Price</option>
					</select>
	
		<input type="submit" value="Go" />
	</form>
</div>

<br style="clear:both;"/>
-->
<?php
	$pageinationcount = 1;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		
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
					<a href="productdetail.php?master=<?php Escaped_Echo( $clean['master'])?>&amp;dept=<?php Escaped_Echo($clean['dept'])?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>"><img src="<?php Escaped_Echo( GetImageName($line["inetthumb"],"Thumb","images/products/",$line["number"]));?>"  alt="<?php Escaped_Echo( formatdescription($line["inetsdesc"])); ?>" /></a>
				</div>
				<div class="ProductListListingHeader">
					<a href="productdetail.php?master=<?php Escaped_Echo( $clean['master'])?>&amp;dept=<?php Escaped_Echo( $clean['dept'])?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>"><?php Escaped_Echo(formatdescription($line["inetsdesc"])); ?></a>
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
						<a href="productdetail.php?master=<?php Escaped_Echo( $clean['master'])?>&amp;dept=<?php Escaped_Echo ($clean['dept'])?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>"><img src="images/details.gif" alt="Details" /></a> &nbsp;	
					</span>
					<?php	
					if(strtolower($line['presell'])=="per")
					{
					?>
						<a href="productdetail.php?master=<?php echo $clean['master']?>&amp;dept=<?php echo $clean['dept']?>&amp;qty=1&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )?> " ><img src="images/personalize.gif" alt="Personalize <?php echo formatdescription($line["inetsdesc"]); ?>"  /></a>
					<?php
					}
					else
					{
					if ($line["mastersub"] == 'M') {?>
						<a href="productdetail.php?master=<?php echo $clean['master']?>&amp;dept=<?php echo $clean['dept']?>&amp;qty=1&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )?> " ><img src="images/addtobasket_small.gif" alt="Add <?php echo formatdescription($line["inetsdesc"]); ?> To Basket"  /></a>
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
	/*if($count!=0)
	{
	?>
		</div>
	<?php
	}*/
	?>	

<div style="clear:both;"></div>
<?php 
if($pagesindept>1)
{
?>
<div class="pagenumber">
	Page
			<?php
				// If its a sub department then display the page numbers

				if(isset($clean['dept'])) {


				if(isset($clean['master']))
				{
					$string="master=" . $clean['master'] . "&amp;dept=" . $clean['dept'];


				}
				else
				{
					$string="dept=" . $clean['dept'];
				}

				if(isset($clean['sort'])) {
					for ($i=1; $i <= $pagesindept; $i++) {
						if ($i == $clean['pagenumber'])
							Escaped_Echo( $i . " ");
						else
						{
						?>
						<a href="products.php?<?php Escaped_Echo($string ."&amp;sort=" . $clean['sort'] ."&amp;page=" . $i);?>"><?php  Escaped_Echo($i); ?></a>	
						<?php
						}	
					}
				}
				else
				{
					for ($i=1; $i <= $pagesindept; $i++) {
						if ($i == $clean['pagenumber'])
							Escaped_Echo( $i . " ");
						else
						{
						?>
						<a href="products.php?<?php Escaped_Echo ($string. "&amp;page=" . $i); ?>"><?php Escaped_Echo($i);?></a>
						<?php
						}
					}
				}

				}
			?>

</div>
<?php
}//end of if there are multiple pages
?>
<?php
}

//end of if there are products in dept
else
{
	//Check to see if there are sub departments below this dept
	if(SubDepts($clean['dept']))
	{
		
		include('include/alt_sub_depts.php');	
	}
	else
	{
?>
	<div id="NoProducts">
	<br />
	Sorry, there are currently no items in this department. 
	<br />
	Please select a different department to continue shopping.
	</div>
<?php
	}
}
}
?>

</div>