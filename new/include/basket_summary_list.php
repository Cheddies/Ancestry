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
<table id="basket_summary" summary="List of items and quantitys in basket">
<th>Item</th><th>Qty</th>
<?php
while($line=mysql_fetch_array($result,MYSQL_ASSOC))
{
	//$subtotal=$line['price']*$line['quantity'];
	//$total=$total+$subtotal;
	$name=$line['name'];
	/*if(strlen(trim($name))>10)
	{
		$name=substr($name,0,10);
		$name=$name ."...";
	}*/
?>
	<tr> <td><?php Escaped_Echo($name);?> </td> <td> <?php Escaped_Echo($line['quantity']) ?></td> </tr> 
	
<?php
}
?>
</table>