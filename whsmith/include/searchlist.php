<?php
	if(isset($_GET['prods'])&& is_numeric($_GET['prods']))
	{
		$clean['products_per_page']=form_clean($_GET['prods'],3);
		$_SESSION['products_per_page']=$clean['products_per_page'];
	}
	
	if(isset($_SESSION['products_per_page']))
		$productsperpage=$_SESSION['products_per_page'];

?>
<?php
	
	$SELECT = "SELECT inetfdesc,relateditems,units,tbl_products.expectdate,tbl_products.units, tbl_products.number, tbl_products.inetsdesc, tbl_products.price, tbl_products.compareprice, tbl_products.inetshortd, tbl_products.inetthumb, tbl_products.mastersub,presell FROM tbl_products INNER JOIN tbl_products_departments ON tbl_products.number=tbl_products_departments.number  WHERE (tbl_products.units > 0 OR lower(tbl_products.presell) = 'pre'   OR tbl_products.mastersub='M')  AND tbl_products.mastersub <> 'S' AND tbl_products.inetsell = 1 "  ;

	
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$count=0;
	$clean=array();
	$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");	
		
	//build search criteria
	if(isset($_GET['searchterm']))
	{
		$searchterm=form_clean($_GET['searchterm'],50, $badchars);
	}
	else
		$searchterm ="";
	$searchterms = explode ( " ", $searchterm ); 	
	$removedwords = array();
	$termquery = " AND ";
	$first=true;
	$word=0;
	$ANDOR="AND";
	$no_terms=0;
	
	
	foreach ($searchterms as $term)
	{
		$term = strtolower(trim($term));
		$term= mysql_real_escape_string($term,$link);
		if(($term!="and") && ($term!="or") && ($term!="the") && ($term!="a") && ($term!=" ") && ($term!="+") && ($term!="-") && ($term!="not"))
		{
			$no_terms++;
			if($first==true)
			{
				$termquery = $termquery  . " (  keywords LIKE '%$term%' OR inetsdesc LIKE '%$term%' OR tbl_products.number LIKE '%$term%' )";
				
				$first=false;
			}
			else
			{
				$termquery = $termquery  . $ANDOR . " (  keywords LIKE '%$term%' OR inetsdesc LIKE '%$term%' OR tbl_products.number LIKE '%$term%'  )";
		
			}
		}
		else
		{
			switch($term)
			{
				case "and":
					$ANDOR="AND";
				break;
				case "or":
					$ANDOR="OR";
				break;
				case "+":
					$ANDOR="AND";
				break;
				case "-":
					$ANDOR="AND NOT";
				break;
				case "not":
					$ANDOR="AND NOT";
				break;
				default:
					$removedwords[$word]=$term;
				$word=$word+1;
				break;
			}
			
		}
	}
	
	if($no_terms>0)
	{
	
	// Performing SQL query

	if(isset($_GET['sort']))
		{
			
			$clean['sort']=form_clean($_GET['sort'],1);
			switch ($clean['sort'])
			{ 
			case 1:
				
				
				$query = "SELECT tbl_products.presell,tbl_products.expectdate,tbl_products.units,tbl_products.number, tbl_products.inetsdesc, tbl_products.price, tbl_products.compareprice, tbl_products.inetshortd, tbl_products.inetthumb, tbl_products.mastersub,tbl_products.inetsell FROM tbl_products WHERE (tbl_products.units > 0 OR tbl_products.presell = 'pre' OR tbl_products.mastersub='M') AND tbl_products.inetsell != 0 AND tbl_products.mastersub <> 'S' " . $termquery ." ORDER BY tbl_products.price ASC;";
				
				break;
			case 2:
				
				
				$query = "SELECT tbl_products.presell,tbl_products.expectdate,tbl_products.units,tbl_products.number, tbl_products.inetsdesc, tbl_products.price, tbl_products.compareprice, tbl_products.inetshortd, tbl_products.inetthumb, tbl_products.mastersub,tbl_products.inetsell FROM tbl_products WHERE (tbl_products.units > 0 OR tbl_products.presell = 'pre' OR tbl_products.mastersub='M') AND tbl_products.inetsell != 0 AND tbl_products.mastersub <> 'S' " . $termquery . " ORDER BY tbl_products.price DESC;";
				
				break;
			default:
				$query = "SELECT tbl_products.presell,tbl_products.expectdate,tbl_products.units,tbl_products.number, tbl_products.inetsdesc, tbl_products.price, tbl_products.compareprice, tbl_products.inetshortd, tbl_products.inetthumb, tbl_products.mastersub,tbl_products.inetsell FROM tbl_products WHERE (tbl_products.units > 0 OR tbl_products.presell = 'pre' OR tbl_products.mastersub='M') AND tbl_products.inetsell != 0 AND tbl_products.mastersub <> 'S'" . $termquery . ";";
				break;
		
		}
	}
	else
	{
		
		$query = $SELECT . $termquery .  " ORDER BY tbl_products.number;";
	}
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$Items=mysql_num_rows($result);
	
	// Closing connection
	mysql_close($link);

	$pagesindept = totalpages(mysql_num_rows($result), $productsperpage);

	// Get the page number, if there is none it must be page 1
	if (isset($_GET['page']) && is_numeric($_GET['page'])) {
		$clean['pagenumber'] = form_clean($_GET['page'],2);
		$startindex = (($clean['pagenumber']-1) * $productsperpage) + 1;
	}

	else {
		$clean['pagenumber'] = 1;
		$startindex = 1;
	}

	$endindex   = $startindex + ($productsperpage-1);
	

?>

<?php $site_section="Search Results"?>

Your search for "<?php Escaped_Echo($searchterm)?>" returned the following results.
<br />
<?php if ($word>0)
{
?>
The following common words were removed from your search in order to return the most relevant results.
<br />
<?php
foreach ($removedwords as $words)
{
?>
<b><?php Escaped_Echo($words);?></b>
	 
<?php
}
}
?>
<?php
if($Items>0)
{
	include ('include/general_product_listing.php');
?>
	<div class="pagenumber">
	<?php
		$PAGE = $_SERVER['PHP_SELF'] ."?";
	
	/*if(isset($clean['master']))
		$PAGE = $PAGE . "&amp;master=" . $clean['master'];
	if(isset($clean['dept']))
		$PAGE = $PAGE . "&amp;dept=" . $clean['dept'];*/
	if(isset($search_token))
		$PAGE = $PAGE . "&amp;search_token=" . $search_token;
	if(isset($searchterm))
		$PAGE = $PAGE . "&amp;searchterm=" . $searchterm;
	if(isset($_GET['bulk']))
		$PAGE = $PAGE . "&amp;bulk=1" ;
	if(isset($_GET['factory']))
		$PAGE = $PAGE . "&amp;factory=1" ;	
	if(isset($_GET['site']))
		$PAGE = $PAGE . "&amp;site=1";
	?>
	Page
			<?php
			/*	
				if(isset($clean['sort'])) {
								
					for ($i=1; $i <= $pagesindept; $i++) {
						if ($i == $clean['pagenumber'])
							Escaped_Echo( $i . " ");
						else
						{
						?>
						<!--<a href="search.php?searchterm=<?php Escaped_Echo($searchterm  ."&amp;sort=" . $clean['sort'] ."&amp;page=" . $i ."&search_token=" . $search_token);?>"><?php  Escaped_Echo($i); ?></a>	-->
						
						<?php
						}	
						
					}
				}
				else
				{
					for ($i=1; $i <= $pagesindept; $i++) {
						if ($i == $clean['pagenumber'])
							echo $i . " ";
						else
						{
						?>
						<a href="search.php?searchterm=<?php Escaped_Echo ($searchterm . "&amp;page=" . $i ."&search_token=" . $search_token); ?>"><?php Escaped_Echo($i);?></a>
						<?php
						}
						
							
					}
				}*/
				for ($i=1; $i <= $pagesindept; $i++) {
						if ($i == $clean['pagenumber'])
							echo $i . " ";
						else
						{
						?>
						<a href="<?php echo $PAGE?>&amp;page=<?php echo $i?>"><?php echo $i ?></a>
						<?php
						}
				}
						
			?>
</div>
<?php

}
else
{
?>
	<div id="NoProducts">
	<br />
	Sorry, your search returned no results. Please try using fewer words in your search query, and removed words such as AND & OR.
	</div>
<?php
}
}
?>
