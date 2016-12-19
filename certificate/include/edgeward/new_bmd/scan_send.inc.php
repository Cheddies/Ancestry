<?php
$scanSendOptions = array("No"=>"0","Yes, email my certificate ({$curSymbol}{$scanPrice})"=>$scanPrice);
?>
<fieldset class="no_box">
<legend><strong>Would you also like to receive a Digital Copy?</strong></legend>
<?php echo formInput('checkbox','scan_and_send','scan_and_send',"Yes, email me a copy within 8-10 working days (Standard) or 4 working days (Express) for an additional <em>{$curSymbol}{$scanPrice}</em>",$scanPrice,array('attr'=>array('class'=>'del_field noval'),'help'=>'cert_help.php#scan','checked'=>$order[$order['cert_table']]['scan_and_send'],'options'=>$scanSendOptions,'disabled'=>$disableDel));?>
</fieldset>   