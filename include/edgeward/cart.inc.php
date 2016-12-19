<?php echo $alertFlash; ?>
<?php if(empty($lineItems)):?>
<p>There are currently no items in your shopping basket. <a href="<?php echo WEBROOT;?>" title="Continue shopping">Continue shopping</a>.</p>
<?php else:?>
<?php 
if($formtype == 'summary'){
	$postAction = $editset == 'cart' || $countryCount >1 ? 'upd_cart.php' : 'edit_summary.php';
} else { 
	$postAction = 'upd_cart.php';
}?>
<form method="post" action="<?php echo $postAction;?>" id="basket_form">
	<input type="hidden" name="token" value="<?php echo $token; ?>" />
	<input type="hidden" name="formset" value="basket"/>
    <input type="hidden" name="formtype" value="<?php echo $formtype;?>"/>
    <div id="basket_wrapper">
    	<img src="../../images/edgeward/ajax-loader-1.gif" class="ajax_loader"/>
        <table class="shopping_basket">
        <thead>
        <tr>
	        <th id="th_thumb">Item</th>
	        <th style="width:50%" class="item_name">Description</th>
	        <th>Price</th>
	        <?php if($discTotal >0):?><th>Discount</th><?php endif;?>
	        <th>Quantity</th>
	        <th>Subtotal</th>
	        <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php 
		$x = 0;
		foreach($order['baskets'] as $item):
			//get the line total
            $lineTotal = $item['quantity']*$item['price'];
			$lineTotal -= $lineTotal*($item['discount']/100);
			$lineTotal = number_format($lineTotal,2);
        ?>
            <tr class="line_item">
            <td class="item_thumb"><?php echo basketThumbnail($item['itemcode'],75);?></td>
			
            <td class="item_name"><?php echo basketItemName($item['itemcode'],$item['name']);?></td>
            
            <td class="number"><?php echo $curSymbol;?><span class="line_price"><?php echo number_format($item['price'],2);?></span></td>
            
            <?php if($discTotal >0):?><td><span class="line_disc"><?php echo $item['discount'];?></span>%</td><?php endif;?>
            
            <td>
            <?php echo formInput('text','lnqty_'.$item['itemcode'],'lnqty_'.$item['itemcode'],null,$item['quantity'],array('attr'=>array('class'=>'line_qty noval basket_update','size'=>4,'maxlength'=>4),'disabled'=>$disableCart,'div'=>0));?>
            </td>
            
            <td class="number"><?php echo $curSymbol;?><span class="line_total"><?php echo $lineTotal;?></span></td>
            
            <td>
            <?php if(!$disableCart):?>
            <a href="rem_item.php?id=<?php echo $item['itemcode'];?>&amp;token=<?php echo $token; ?>" class="rem_item">Remove</a>
            <?php endif;?>
            </td>
            </tr>
            <input type="hidden" class="line_item"name="lnitem_<?php echo $x;?>" value="<?php echo $item['itemcode'].'|'.$item['quantity'].'|'.$item['price'].'|'.$item['name'].'|'.$item['discount'];?>"/>
            <?php 
			$x++;
        endforeach;?>
        <tr class="total"><td colspan="<?php echo $discTotal >0?5:4;?>">Basket Total</td>
		<td class="number"><span class="basket_total"><?php echo $curSymbol; echo number_format($itemTotal,2);?></span></td>
		<td>&nbsp;</td></tr>
        </tbody>
        </table>
        
        <div class="input text inline clear">
			<?php echo formInput('text','offer_code','offer_code','Special Offer Code',$order['offer_code'],array('attr'=>array('class'=>'noval floatleft'),'help'=>'shop_help.php#offer_code','disabled'=>$disableCart,'div'=>0));?>
            <?php if(!$disableCart):?>
                <input type="submit" value="Apply" id="apply_discount" name="btn" class="sec_small_btn"/>
            <?php endif;?>
            <?php if($invalidOffer):?>
            <label for="offer_code" class="error">Invalid or expired offer code.</label>
            <?php endif;?>
        </div>
        
        <?php if($showDelOptions):?>
            <fieldset class="clear"><legend><strong>Select Delivery Service</strong></legend>
            <div class="input inline_radio">
                <?php require_once('delivery_options.inc.php'); ?>    
            </div>
            </fieldset>
            
            <p id="order_total">Order Total: <span class="order_total">&pound;<?php echo number_format($orderTotal,2);?></span></p>
    	<?php endif;?>
    
        
	
        <div class="input submit clear">
            <?php if($formtype != 'summary'):?>
                <input type="submit" value="Update basket" id="update_basket" name="btn"/>
            <?php elseif($formtype == 'summary' && $editset == 'cart'):?>
            	<input type="submit" value="Save" id="save_basket" name="btn" class="edit_btn"/>
            <?php endif;?>
            <?php if(!$editset && $formtype == 'summary') {
				echo "<a href=\"edit_summary.php?edit=cart\" id=\"edit_cart\">Edit</a>";
			}?>
        </div>
		<?php if($formtype != 'summary'):?>
            <div class="button">
            	<a href="<?php echo WEBROOT;?>" title="Return to shopping" id="continue_shopping">Return to shopping</a>
            <input type="submit" title="Continue to delivery" value="Continue" name="btn"  id="submit_cart" class="noval img_btn" <?php if($editset) echo 'disabled="disabled"';?>/>
            </div>
		<?php endif;?>
    </div><!--end #basket_wrapper-->
</form>
<?php endif;//items in basket?>

