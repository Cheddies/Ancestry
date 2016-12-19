<?php
$shippingHelp = "";
if(!empty($shipData)){
	$i = 0;
	foreach($shipData as $d){
		$fieldOptions = array('attr'=>array('class'=>'basket_update noval','rev'=>$d['price']),'disabled'=>$disableCart,'div'=>0);		
		$fieldOptions['checked'] = ($order['delivery_method'] == $d['code']);
		if($i < 1){
			//set one help button
			$fieldOptions['help'] = 'shop_help.php#shipping|Delivery Service';	
		}		
		$label = $d['description']." &pound;".$d['price'];
		echo formInput('radio','delivery_method','del_'.$d['code'],$label,$d['code'],$fieldOptions);
		if($shippingHelp) $shippingHelp .= "<br/>";
		$shipNotes = $d['notes'] ? "- {$d['notes']}" : "";
		$shippingHelp .= "<strong>{$d['description']}:</strong> {$curSymbol}{$d['price']}{$shipNotes}";
		$i++;
	}
}
?>