<?php require_once('include/edgeward/database.inc.php'); 
$c = '073';
openConn();
$sql = sprintf("SELECT s.code AS code, s.price AS price, s.description AS description,s.notes AS notes FROM tbl_shipping AS s, tbl_country_shipping AS c WHERE s.code = c.shipping AND c.country = '%s';",
			   mysql_real_escape_string($c));
$shipData = sqlQuery($sql);
mysql_close();
$shippingHelp = "";
foreach($shipData as $d){
	if($shippingHelp) $shippingHelp .= "<br/>";
	$shipNotes = $d['notes'] ? "- {$d['notes']}" : "";
	$shippingHelp .= "{$d['description']}:{$curSymbol}{$d['price']}{$shipNotes}";
}
$url = explode('?',$_SERVER['HTTP_REFERER']);
$referer = $url[0];
//dont include JS
$no_js = 1;
?>
<?php require_once('include/edgeward/header.inc.php'); ?>

<div id="content_top"></div>
<div id="content_wrapper">
<div class="border_top"></div>
<div class="fieldset_border">
<dl class="help_def">
<dt id="offer_code">
Special Offer Code
</dt>
<dd>
<p>Enter a promotional code to recieve promotional discount. Codes may apply to individual products or to the whole shopping basket.</p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>

<dt id="shipping">
Delivery Service
</dt>
<dd>
<p><?php echo $shippingHelp;?></p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>

<dt id="delivery_address">
Delivery Address
</dt>
<dd>
<p>This is the address that you'd like the certificate delivered to.<br/>All fields marked with <strong>*</strong> are required.</p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>

<dt id="billing_address">
Billing Address
</dt>
<dd>
<p>Complete the billing address section if your billing and delivery addresses are different.<br/>All fields marked with <strong>*</strong> are required.</p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>
</dl>


</div><!--end .fieldset_border-->
<div class="border_bottom"></div>

</div><!--end #content_wrapper-->
<div id="page_footer"></div>

<?php require_once('include/footer.php'); ?>