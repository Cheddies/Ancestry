<?php

require_once('include/siteconstants.php');

$total=0;
$subtotal=0;
$session = session_id();

$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');
	
$mysql=array();
$mysql['session']=mysql_real_escape_string($session,$link);

$query="SELECT itemcode,name,quantity,price, (price * ( (100 - discount) / 100 ) ) * quantity AS subtotal,price * ( (100 - discount) / 100 ) AS dis_price from tbl_baskets WHERE sessionid ='{$mysql['session']}' AND itemcode!='DONATION'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

$numberofproducts = mysql_num_rows($result);
?>
<div id="side_basket_header">Shopping Basket</div>
<ul>
<?php
while($line=mysql_fetch_array($result,MYSQL_ASSOC))
{
	//$subtotal=$line['price']*$line['quantity'];
	//$total=$total+$subtotal;
	$name=$line['name'];
	if(strlen(trim($name))>10)
	{
		$name=substr($name,0,10);
		$name=$name ."...";
	}
?>
	<li><?php Escaped_Echo( $name);?> <?php  Escaped_Echo($line['quantity'] ." X £". $line['dis_price'] ." - £" .$line['subtotal']);?> </li> 
	
<?php
}
echo "<li style=\"text-align:right; font-weight:bold; font-size:12px;\">Total: " . formatcurrency(getBasketTotal($session))."</li>";
?>

<li><a style="border-right:1px dashed #B1B0B2; padding-right:10px;" href="basket.php">View Basket</a>  <a href="checkout.php">Checkout</a></li>
</ul>