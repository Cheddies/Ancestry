<div id="products">

<?php
	$clean=array();
	
	if(isset($_GET['master']))
		$clean['master']=form_clean($_GET['master'],4);
	if(isset($_GET['dept']))
		$clean['dept']=form_clean($_GET['dept'],4);
	if(isset($_GET['per_name']))
		$clean['per_name']=form_clean($_GET['per_name'],10);
	if(isset($_GET['alt_image']) && strlen($_GET['alt_image'])>0 )
		$clean['alt_image']=form_clean($_GET['alt_image'],1);
		
	if(isset($_GET['number'])) 
	{
	//"-", "/" removed from list of characters to remove as products have this in code
	
	$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");	
		
	$clean['productupc']=form_clean($_GET['number'],20,$badchars);	
		
	if(isset($clean['alt_image']))
 	{
		$clean['imagename']=$clean['productupc'] . "_" .  $clean['alt_image'];
 	}
 	else
 		$clean['imagename']=$clean['productupc'];
	
	
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
	
	//Set PAGE variable to be used in links that refresh the page
	 $PAGE=$_SERVER['PHP_SELF'];
	 $QUERY= "number=" . $clean['productupc'];
	 if(isset($clean['dept']))
	 	 $QUERY = $QUERY . "&amp;dept=" . $clean['dept'];
	 if(isset($clean['master']))
	 	 $QUERY = $QUERY . "&amp;master=" . $clean['master'];  
	 $PAGE=$PAGE . "?" . $QUERY;
	
	
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$mysql=array( );
	$mysql['productupc']=mysql_real_escape_string($clean['productupc'],$link);
	
	
	// Performing SQL query
	if( (isset($clean['master'])) && (isset($clean['dept'])) )
	{ 
		$query = "SELECT tbl_products.expectdate,tbl_products.number, tbl_products.inetsdesc,tbl_products.inetshortd, tbl_products.price, tbl_products.compareprice, tbl_products.inetfdesc, tbl_products.inetimage, tbl_products.relateditems, tbl_products.mastersub ,tbl_products.units,presell FROM tbl_products WHERE number = '{$mysql['productupc']}'";
	}
	else
	{
		$query= "SELECT 	tbl_products.compareprice, 
							tbl_products.inetfdesc, 
							tbl_products.inetimage,
							tbl_products.relateditems, 
							tbl_products.mastersub ,
							tbl_products.units,
							tbl_products.inetshortd,
							tbl_products.expectdate, 
							tbl_products.number,
							under,inetsdesc,
							inetthumb,price,
							tbl_products.name,
							inetshortd,
							tbl_products_departments.deptcode,
							presell
							FROM
							tbl_products
							LEFT JOIN tbl_products_departments on tbl_products.number=tbl_products_departments.number
							LEFT JOIN tbl_departments on tbl_departments.deptcode=tbl_products_departments.deptcode
							WHERE (units>0  OR presell='pre' OR mastersub='M')
							AND inetsell=true
							AND tbl_products.number='{$mysql['productupc']}'
							ORDER BY units DESC
							LIMIT 1"; 
	}
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$pricetoshow =  formatcurrency($line["price"])  ;
		if ($line["price"] < $line["compareprice"]) {
			$pricetoshow = "<span class='was_price'>Was: " . formatcurrency($line["compareprice"]) . "</span> <span class='now_price'>Now: " . formatcurrency($line["price"]) . "</span>";
		}
		$Euro=ConvertToEuro($line["price"]);
		$Dollar=ConvertToDollar($line["price"]);
		//Check to see if this product is in stock and if not when it will be avaiable
		//returns a message regarding this to be stored in AText
		$AText= CheckStockDate($line['expectdate'],$line['units'],$line['mastersub']);
		$DeliveryText="";
		
		if($line['units']>0)
			$DeliveryText=DELIVERYTIME;
			
		$DeliveryText=CheckShippingRate($clean['productupc']);
		
	?>

<div class="productdetail">
	
	<div class="productdetailimg">
	<?php
	 if(isset($_GET['zoom']))
	 {
		 $clean['zoom']=form_clean($_GET['zoom'],1);
		 if(is_numeric($clean['zoom']))
		 {
			 if($clean['zoom']==0)
			 	$_SESSION['zoom']=false;
			 else
			 	$_SESSION['zoom']=true;
	 	 }
	 }
	
		/*if(isset($_SERVER['QUERY_STRING']))
		{	
			$query=$_SERVER['QUERY_STRING'];
			$query=str_replace("&zoom=0","",$query);
			$query=str_replace("&zoom=1","",$query);
			$query=str_replace("&prices=0","",$query);
			$query=str_replace("&prices=1","",$query);
			
			$query=str_replace("&alt_image=1","",$query);
			$query=str_replace("&alt_image=2","",$query);
			$query=str_replace("&alt_image=3","",$query);
			$query=str_replace("&alt_image=4","",$query);
			$query=str_replace("&alt_image=5","",$query);
			
			$PAGE=$PAGE . "?" . $query;
			//$PAGE = htmlentities($PAGE);
		}*/
	
	 $zoomy="images/products/zoom/" . basename($clean['imagename'])  ."/";
	
	 if(is_dir($zoomy) && ((!isset($_SESSION['zoom'])) || ($_SESSION['zoom']==true)))
	 {
		 //zoom image is there so try to display
	
		
	?><!--No Image? Page Loading Slowly?  <a href="<?php Escaped_Echo( $PAGE . "&amp;zoom=0" )?>"><br />Turn Product Zoom Off</a>-->
		<br />
		<object type="application/x-shockwave-flash" data="../flash/zoomifyViewer.swf" width="245" height="315">
			<param name="wmode" value="transparent"><!--This overcomes the fact that flash content is not affected by the z-index in css. -->	
			<PARAM NAME="FlashVars" VALUE="zoomifyImagePath=<?php  Escaped_Echo( $zoomy);?>&amp;zoomifyNavWindow=0">
		    <PARAM NAME="MENU" VALUE="FALSE">
			<PARAM NAME="SRC" VALUE="../flash/zoomifyViewer.swf">	
			<img src="<?php Escaped_Echo( GetImageName($line["inetimage"],"Large","images/products/",$clean['imagename']));?>"  alt="<?php Escaped_Echo ($line["inetsdesc"]);?>" />
		</object>
	<?php
	
	 }
	 else
	 {
		 //no zoomy image so display normal
	?>
		<?php if(is_dir($zoomy))
		{
		?>
		<a href="<?php Escaped_Echo ($PAGE . "&zoom=1" )?>"><br />Turn Product Zoom On</a><br />
		<?php
		}
		?>
		<img src="<?php Escaped_Echo (GetImageName($line["inetimage"],"Large","images/products/",$clean['imagename']));?>"  alt="<?php Escaped_Echo( $line["inetsdesc"]);?>" />
	<?php
	 }
	?>
		<div id="alt_images">
		<?php
			$alt_images=array();
			$alt_images=getAltImages($line['number'],"images/products/alt");
			$number_of_images=sizeof($alt_images);	
			$count=0;
		?>
		<ul>
		<?php
		if($number_of_images>0)
		{	
			echo "Extra Images - Click To View";
			foreach($alt_images as $image)
			{
				$full_image_path="images/products/alt/" . $line['number'] ."/" .$image; 
				$image_name=substr($image,0,strlen($image)-3);
				$image_split = explode("_",$image_name);
				$appendix = $image_split[sizeof($image_split)-2];
				if($appendix==$clean['productupc'])
					$appendix="";
				
				
		?>
			<li><a href="<?php echo $PAGE?>&amp;alt_image=<?php echo $appendix?>"><img src="<?php echo $full_image_path ?>" alt="" /></a></li>
		<?php
				$count++;
			}
		}
		?>
		</ul>
		</div>
	</div>

<?php /*Escaped_Echo ($line['number'])*/ ?><?php if(strlen(trim($DeliveryText))!=0) Escaped_Echo( "<b> ".  $DeliveryText . "</b>") ?>

<div id="productdetail_header">
	<h1><?php Escaped_Echo($line["inetsdesc"]) ;?></h1> 
</div>
<div id="product_desc">
	<?php echo($line["inetfdesc"]) ;?>
</div>

				

<?php
	if ($line["mastersub"] == "M") {
?>
		<?php
		if(strtolower($line['presell'])=="per")
		{
		?>
			<form action="perproccess.php" method="post">
			<input type="hidden" name="token" value="<?php echo $token?>" />
			<input type="hidden" name="dept" value="<?php Escaped_Echo( $clean['dept'])?>" />
			<input type="hidden" name="master" value="<?php Escaped_Echo( $clean['master'])?>" />
			<input type="hidden" name="masterupc" value="<?php Escaped_Echo( $clean['productupc'])?>" /> 
		<?php
		}
		else
		{
			
		?>
			<form action="variationaddtobasket.php" method="post">
		<?php
		}
		?>
		
		<!--<label for="qty">Quantity</label>-->
		<input class="shadow"  type="hidden" name="qty" id="qty" value="1" size="4" maxlength="4" />
		<?php 
			
		if(strtolower($line['presell'])=="per")
		{
		?>
		<label for="per_name">Name (max 10 chars)</label>
		<br />
		 <input class="shadow" type="textbox" name="per_name" value="<?php if(isset($clean['per_name'])) Escaped_Echo( $clean['per_name'])?>" maxlength="10" size="15" />
		<?php
		if(isset($_GET['error']))
		{
		?>
			<span class="error">Please enter a name</span>
		<?php
		}
		?>
	
		<?php
		}
		
		if(PRICES && isset($_SESSION['alt']))
		{
			switch($_SESSION['alt'])
			{
				case DOLLAR:
				echo(displaysizes($line["number"],"<label for=\"size\">Choose a Size</label><br />","Currently not available. More supplies arriving soon. ","Dollar"));
				break;
				case EURO:
				echo(displaysizes($line["number"],"<label for=\"size\">Choose a Size</label><br />","Currently not available. More supplies arriving soon. ","Euro"));
				break;
				case NONE:
				echo(displaysizes($line["number"],"<label for=\"size\">Choose a Size</label><br />","Currently not available. More supplies arriving soon."));
				break;
				
			}
			
		}
		else
		{
			echo(displaysizes($line["number"],"<label for=\"size\">Size</label><br />","Currently not available. More supplies arriving soon. "));
				
		}
		?>
		<input type="hidden" name="token" value="<?php echo $token?>" />
		</form>
<?php
	}
	else {
	?>
		<div id="product_detail_price">
			<span class="price"><?php Echo( $pricetoshow);?>
		</div>
<?php
		if(PRICES && isset($_SESSION['alt']))
		{
			switch($_SESSION['alt'])
			{
				case DOLLAR:
				Escaped_Echo ("(".$Dollar.") - approx *");
				break;
				case EURO:
				Escaped_Echo ("(".$Euro.") - approx *");
				break;
				case NONE:
				
				break;
				
			}
			
		}
?>
		</span>
		<form action="variationaddtobasket.php" method="post">
		<input type="hidden" name="upc" value="<?php Escaped_Echo($line['number'])?>" />
		<!--<label for="qty">Quantity</label>   -->
		<input class="shadow"  type="hidden" name="qty" id="qty" value="1" size="4" maxlength="4" /> 
	
		<?php 
		
		if(strtolower($line['presell'])=="per")
		{
		?>
		<label for="per_name">Name (max 10 chars)</label>
		<br />
		<input class="shadow"  type="textbox" name="per_name" value="" maxlength="10" size="15" />
		<br />
		<?php
		}
		//below removed and add to basket link added instead
		//this is so the new edgeward add to basket page is used
		?>

		<input type="hidden" name="token" value="<?php echo $token?>" />
		<!-- <input style="margin-left:178px;"type="image" src="images/addtobasket.gif" alt="Add To Basket" />-->
	
		
	<?php	
		echo "<a  style=\"margin-left:178px;\" href='addtobasket.php?code=" . $line['number']. "' class=\"AddToBasket\"><img src=\"images/addtobasket.gif\" alt=\"Add To Basket\" border=\"0\" /> </a>";
	}
?>	
</form>
<!--<div class="AvailInfo">
		<h3>Availability</h3>
			<?php Echo($AText);?>
</div> -->
		<?php
		
	/*	if(PRICES)
		{
			echo "<i>*Please Note - Prices shown in US Dollars ($) and Euros (&euro;) are approximate vaules and are for a guide only.All transactions will take place in UK pounds (&pound;) and will be converted at the relevant exchange rate at the time of purchase.";
		}*/
		
?>

	<?php 
		/*if(isset($clean['dept']) && isset($clean['master']))
		{
	?>
	
		<a href="products.php?master=<?php echo $clean['master']?>&dept=<?php echo $clean['dept']?>"><img style="float:right;margin-top:10px; margin-right:25px;" src="images/back.gif" alt="Back" /></a>
	<?php
		}*/
	?>

	<div id="extra_info">
		<?php
		
		if ($line["mastersub"] == "M") {
		?>
			
			<a href="sizechart.php">Size Chart</a>
			<br />
		<?php
		}
		?>		
	</div>
	<div id="christmas_delivery">
	<br />
		
	</div>
	
	
</div>


<?php
	
include('include/right_hand_bar.php');

if(RELATED_ITEMS)
{
?>
	<div class="relateditems">
	<h3 id="RIHeader">Have you considered these?</h3>
		<?php 
			if( (isset($clean['master'])) && (isset($clean['dept'])) )
				getrelateditems($line['number'],$line['price'],$clean['master'],$clean['dept'],$line['relateditems'],3,100);
			else
				getrelateditems($line['number'],$line['price'],$line['under'],$line['deptcode'],$line['relateditems'],3,100);	
			?>
	</div>
<?php
}
?>

<?php
	}
	//Closing connection
	mysql_free_result($result);
	//mysql_close($link);

	}//end of if number is set
	else
	{
		//redirect to home
		header('location:index.php');
		exit();
	}
?>

</div>