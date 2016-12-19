<?php
$scanSendOptions = array("No"=>"0","Yes, email my certificate ({$curSymbol}{$scanPrice})"=>$scanPrice);
?>
<fieldset class="no_box">
<legend><strong>Certificate - Digital Copy</strong></legend>
<?php echo formInput('select','scan_and_send','scan_and_send','Digital Copy service',$order[$order['cert_table']]['scan_and_send'],array('attr'=>array('class'=>'del_field noval'),'help'=>'cert_help.php#scan','options'=>$scanSendOptions,'disabled'=>$disableDel));?>
</fieldset>   