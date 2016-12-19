<?php
$priceTitle = $discount['code'] ? 'Regular price' : 'Price';
$stdPrice = $discount['code'] ? "<del>{$curSymbol}{$standardPrice}</del>" : "{$curSymbol}{$standardPrice}";
$expPrice = $discount['code'] ? "<del>{$curSymbol}{$expressPrice}</del>" : "{$curSymbol}{$expressPrice}";
?>
<h3>Price options</h3>
<table>
	<tr>
		<th>Type</th>
		<th><?php echo $priceTitle;?></th>
		<?php if($discount['code']):?><th>Discounted price</th><?php endif;?>
	</tr>
	<tr>
		<td>Standard</td>
		<td class="std"><?php echo $stdPrice;?></td>
		<?php if($discount['code']):?><td class="disc"><?php echo $curSymbol.$discount['std'];?> -<?php echo $discount['amount'];?>% discount</td><?php endif;?>
	</tr>
	<tr>
		<td>Express</td>
		<td class="std"><?php echo $expPrice;?></td>
		<?php if($discount['code']):?><td class="disc"><?php echo $curSymbol.$discount['exp'];?> -<?php echo $discount['amount'];?>% discount</td><?php endif;?>
	</tr>
	<tr>
		<td>Digital copy</td>
		<td class="std"><?php echo $curSymbol.$digiCopyPrice;?></td>
	</tr>
</table>