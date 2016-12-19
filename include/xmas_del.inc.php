<?php
$today = date('Ymd') *1;
if($today < 20101217):
$deliveryText = '16th December using standard delivery &amp; 22nd December using Express.';
if(stripos(basename($_SERVER["REQUEST_URI"]), '-certificate') !== false) $deliveryText = '1st December using standard delivery &amp; 14th December using Express.';
?>
<div id="xmas_del">
	<h2>Ordering for Christmas?</h2>
	<p><strong>To ensure delivery by December 24th please order by <?php echo $deliveryText;?></strong></p>
</div>
<?php endif;?>