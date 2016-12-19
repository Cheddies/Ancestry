<?php require_once('include/edgeward/process_bmd.inc.php'); 
$c = '073';
$shippingHelp = "";
foreach($shipData as $d){
	if($shippingHelp) $shippingHelp .= "<br/>";
	$shipNotes = $d['notes'] ? "- {$d['notes']}" : "";
	$shippingHelp .= "{$d['description']}: {$curSymbol}{$d['price']} {$shipNotes}";
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

<dt id="gro">
GRO reference information
</dt>
<dd>
<p><?php echo $groHelptext;?></p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>

<dt id="particulars">
Certificate Particulars
</dt>
<dd>
<p><?php echo $partsHelptext;?></p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>

<dt id="qty">
Number of Certificates
</dt>
<dd>
<p><?php echo $qtyHelptext;?></p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>

<dt id="shipping">
Delivery Service
</dt>
<dd>
<p><?php echo $shippingHelp;?></p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>

<dt id="scan">
Certificate - Digital Copy
</dt>
<dd>
<p><?php echo $scanHelptext;?></p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>

<dt id="discount">
Offer Code
</dt>
<dd>
<p><?php echo $discountHelptext;?></p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>

<dt id="delivery_address">
Delivery Address
</dt>
<dd>
<p><?php echo $delHelptext;?></p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>

<dt id="billing_address">
Billing Address
</dt>
<dd>
<p><?php echo $billHelptext;?></p>
<p><a href="<?php echo $referer;?>">Back to my shopping basket</a>.</p>
</dd>
</dl>


</div><!--end .fieldset_border-->
<div class="border_bottom"></div>

</div><!--end #content_wrapper-->
<div id="page_footer"></div>

<?php require_once('include/edgeward/footer.inc.php'); ?>