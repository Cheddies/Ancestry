<?php
$session = session_id();
	
// Connecting, selecting database
$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');

$mysql=array();
$mysql['session']=mysql_real_escape_string($session,$link);
	
// Performing SQL query
//$query = "SELECT tbl_baskets.itemcode, tbl_baskets.name, tbl_baskets.inetthumb, tbl_baskets.quantity, tbl_baskets.price, (tbl_baskets.quantity * tbl_baskets.price) AS linetotal FROM tbl_baskets WHERE tbl_baskets.sessionid = '$session';";
$query = "SELECT basketid,custom,expectdate,units,presell,tbl_baskets.itemcode, tbl_baskets.name, tbl_baskets.inetthumb, tbl_baskets.quantity, tbl_baskets.price, ( (tbl_baskets.price * ( (100 - discount) / 100 ) ) * tbl_baskets.quantity) AS linetotal,discount FROM tbl_baskets,tbl_products WHERE tbl_baskets.sessionid = '{$mysql['session']}' AND tbl_baskets.itemcode=tbl_products.number;";

$result = mysql_query($query) or die('Query failed: ' . mysql_error());
// Closing connection
mysql_close($link);

$productsinbasket = mysql_num_rows($result);
//get referer link
//use this to create the continue shopping link
	
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


if(isset($_SESSION['master'])&& isset($_SESSION['dept']))
	$return_link="products.php?master={$_SESSION['master']}&amp;dept={$_SESSION['dept']}";
else
	$return_link="index.php";


if(isset($_GET['error']))
{
?>
	<br />
	<span class="error">Sorry, but there is less stock available than required to complete your order. <br /> For details, please check the message by each item.</span>
	<br />
<?php
}

if($productsinbasket > 0) { 
?>
	<form action="updatebasket.php" method="post">
	<input type="hidden" name="token" value="<?php echo $token?>" />
	<table summary="Details of each item you have added to you shopping basket">
	<tr>
		<th>Item</th><th>Stock</th><th>Quantity</th><th>Price</th><th>Discount</th><th>Total</th><th>Remove</th>
	</tr>
	<?php
	$baskettotal = 0;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	?>
	<tr>
	<td >
		<?php  
		Escaped_Echo ("{$line["name"]}");
 		if (trim($line['custom'])!="")
		{
			echo( "<b>Personalized Info: </b>");
			Escaped_Echo ($line['custom']); 
		?>
			<a href="customedit.php?itemcode=<?php echo $line['itemcode']?>&amp;custom=<?php echo $line['custom']?>">Edit This Item</a>
		<?php
		}	 	 
	?>
	</td>
	<td>
		<?php	
		$Message="&nbsp;";
		$Allowed=CheckStock($line['units'],$line['quantity'],$line['presell'],$line['expectdate'],&$Message);
		if($line['units']>0)
		{
		?>
		In Stock
		<?php
		}
		else
		{
		?>
		Out Of Stock
		<?php
		}
		?>
		<span class="basket_error"><br /><?php  Escaped_Echo( $Message);?></span>
	</td>
	<td>
		<input type="hidden" name="<?php  Escaped_Echo( "CUST" . $line['custom']);?>" value="<?php  Escaped_Echo( $line['custom']);?>" />
		<input  type="hidden" maxlength="4" name="<?php  Escaped_Echo( "UPC" . $line["itemcode"]); ?>" size="2" value="<?php  Escaped_Echo( $line["quantity"]); ?>" />
		<input  type="text" maxlength="4" name="<?php  Escaped_Echo( "ID" . $line["basketid"]); ?>" size="2" value="<?php  Escaped_Echo( $line["quantity"]); ?>" />
	</td>			
	<td>
		<?php Escaped_Echo(formatcurrency($line["price"])); ?>
	</td>		
	<td>
		<?php Escaped_echo($line['discount']);?>%
	</td>			
	<td>
		<?php  Escaped_Echo( formatcurrency( ( ( (100 - $line['discount'])/100 ) * $line['price']) * $line['quantity']) ) ?>
	</td>	
	<td>
		<a href="deletefrombasket.php?id=<?php echo $line['basketid']?>&amp;token=<?php echo $token?>&amp;number=<?php echo $line['itemcode']?>">Remove</a>
	</td>
	</tr>	
	<?php
	$baskettotal = $baskettotal + $line["linetotal"];
	}
	?>
	<tr id="order_total">
		<td colspan="5" style="text-align:right">
			Order total:
		</td>
		<td colspan="2">
			<?php  Escaped_Echo( formatcurrency($baskettotal) )?>
		</td>
	</tr>

</table>



	<div id="discount_box">
		Special offer code <input type="text" size="15" maxlength="20" value="" name="discount_code" style="margin-bottom:5px" />	<input type="image" src="images/apply.gif"  alt="Apply" /> 
	</div>
	<div id="basket_links">		
		<a href="<?php echo $return_link?>"><img src="images/return_to_shopping.gif" alt="RETURN TO SHOPPING" /></a>
		<input type="image" src="images/update.gif"  alt="Update" /> 
		<a href="checkout.php"><img src="images/checkout.gif" alt="Proceed to Secure Checkout" /></a>	
	</div>
</form>
	
	<?php
		}
		else {
	?>
	<div class="basketboxline">
		<br />
		Your shopping basket is currently empty.
		<br />
		You can add items by clicking the "Buy Now" button next to the item you want.
		<br />		
	</div>
		<?php
	if(isset($_SESSION['master'])&&isset($_SESSION['dept']))
	{
	?>
	<div id="basketfooter">
		<a href="products.php?master=<?php  Escaped_Echo( $_SESSION['master']);?>&amp;dept=<?php Escaped_echo($_SESSION['dept'])?>"><a href="index.php"><img src="images/return_to_shopping.gif" alt="Continue Shopping" /></a>
	</div>
	<?php
	}
	else
	{
	?>
	<div id="basketfooter">
		<a href="index.php"><img src="images/return_to_shopping.gif" alt="Continue Shopping" /></a>
	</div>
	<?php
	}
	
	}
	?>