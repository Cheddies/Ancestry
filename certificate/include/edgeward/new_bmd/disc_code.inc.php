<?php if(!$order['invalid_offer']) $hidden = 'hidden'; ?>
<fieldset class="no_box">
<div class="input text">
	<?php echo formInput('text','discount_code','discount_code','Promotional Offer Code',$order['orders']['discount_code'],array('attr'=>array('class'=>'del_field noval short_field'),'help'=>'cert_help.php#discount','disabled'=>$disableDel,'div'=>0));?>
	<?php $class = $disableDel ? 'hidden' : '';?>
	<input type="submit" value="Apply" id="apply_discount" name="btn" class="noval sec_small_btn <?php echo $class;?>"/>
	<label id="offer_code_error" for="discount_code" class="error <?php echo $hidden;?>">Invalid or expired offer code.</label>
</div>


<input type="hidden" id="order_discount" value="<?php echo $order['orders']['discount'];?>">
</fieldset>   