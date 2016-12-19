<?php
echo formInput('text','b_title','b_title','Title',$order['address']['title'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>$titleReq,'error'=>validationError('b_title')));

echo formInput('text','b_forename','b_forename',$firstnameLabel,$order['billing_address']['first_name'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_forename')));

echo formInput('text','b_surname','b_surname',$lastnameLabel,$order['billing_address']['surname'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_surname')));

if($order['country'] == '073'){ //if country is uk, add some fields for postcode lookup
	echo formInput('text','b_house_no','b_house_no','House Number/Name',$order['add_b_house_no'],array('attr'=>array('class'=>'add_field','maxlength'=>20),'div'=>array('id'=>'b_hno'),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_house_no')));
	echo formInput('text','b_street_name','b_street_name','Street/Road Name',$order['add_b_street_name'],array('attr'=>array('class'=>'add_field','maxlength'=>40),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_street_name')));
} else {
	echo formInput('text','b_address_1','b_address_1','Address 1',$order['billing_address']['line_1'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_address_1')));
}

echo formInput('text','b_address_2','b_address_2','Address 2',$order['billing_address']['line_2'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'error'=>validationError('b_address_2')));

echo formInput('text','b_town','b_town',$cityLabel,$order['billing_address']['city'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_town')));

echo formInput('text','b_county','b_county',$countyLabel,$order['billing_address']['county'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>$countyReq,'error'=>validationError('b_county')));

echo '<div class="input text" id="b_pcode">';
echo formInput('text','b_postcode','b_postcode',$postcodeLabel,$order['billing_address']['postcode'],array('attr'=>array('class'=>'add_field','maxlength'=>10),'div'=>false,'disabled'=>$disableAddress,'required'=>$postCodeReq,'error'=>validationError('b_postcode')));
echo '</div>';

echo formInput('select','b_country','b_country','Country',$order['billing_address']['country'],array('attr'=>array('class'=>'add_field'),'options'=>$countries,'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_country')));

?>