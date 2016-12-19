<?php
$shippingHelp = "";
if(!empty($shipData)){?>
	<fieldset class="no_box">  
    <legend><strong>Delivery method</strong></legend>
    <?php
	$i = 0;
	foreach($shipData as $d){
		$fieldOptions = array('attr'=>array('class'=>'checkbox noval del_field','rev'=>$d['price']),'disabled'=>$disableDel);		
		$fieldOptions['checked'] = $order['orders']['delivery_method'] ? ($order['orders']['delivery_method'] == $d['code']) : ($i < 1);
		if($i < 1){
			//set one help button
			$fieldOptions['help'] = 'cert_help.php#shipping|Delivery Service';	
		}		
		$label = "{$d['description']} - <em>{$curSymbol}{$d['price']}</em>";
		echo formInput('radio','delivery_method','del_'.$i,$label,$d['code'],$fieldOptions);
		if($shippingHelp) $shippingHelp .= "<br/>";
		$shipNotes = $d['notes'] ? "- {$d['notes']}" : "";
		$shippingHelp .= "<strong>{$d['description']}:</strong> {$curSymbol}{$d['price']}{$shipNotes}";
		$i++;
	}
	?>
	
	<?php require_once('include/edgeward/new_bmd/scan_send.inc.php'); ?>
    
	<?php	
	
	$hidden = $order['orders']['discount'] ? '' : 'hidden';
	echo "<p id=\"order_discount_total\" class=\"{$hidden}\"><strong>Order Discount: -{$curSymbol}<span id=\"disc_total\">{$discTotal} ({$order['orders']['discount']}%)</span></strong></p>";
	if($formset == "summary" || $currentPage == "cert_details_3.php"){		
		echo "<p id=\"del_total_wrapper\" class=\"clear\"><strong>Order Total: $curSymbol<span id=\"del_total\">$orderTotal</span></strong></p>";
	} else {
		echo "<p id=\"del_total_wrapper\" class=\"clear\"></p>";
	}
	echo "</fieldset>";

}

if($formset == "summary"){
	echo "<div class=\"edit_btn_wrapper\">\n";
	if($editset == 'del'){
		echo "<input type=\"submit\" value=\"Save\" class=\"noval edit_btn\" name=\"btn\"/>\n";   	
	} elseif(!$editset) {
		echo "<a href=\"edit_summary.php?edit=del\" class=\"edit\" id=\"edit_del\">Edit</a>\n";
	}
	echo "</div>\n";
}
?>