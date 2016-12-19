<div class="add_col add_col_a">
<?php
//echo formInput('select','title','title','Title',$order['address']['title'],array('attr'=>array('class'=>'add_field'),'options'=>$titles,'disabled'=>$disableAddress,'required'=>!$disableAddress));
echo formInput('text','b_forename','b_forename','Forename(s)',$order['order_header']['firstname'],array('attr'=>array('class'=>'add_field','maxlength'=>15),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_forename')));
echo formInput('text','b_surname','b_surname','Surname',$order['order_header']['lastname'],array('attr'=>array('class'=>'add_field','maxlength'=>20),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_surname')));
?>
</div>
<div class="add_col add_col_b">
<?
echo formInput('text','b_house_no','b_house_no','House Number/Name',$order['add_b_house_no'],array('attr'=>array('class'=>'add_field','maxlength'=>40),'div'=>array('id'=>'b_hno'),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_house_no')));

echo formInput('text','b_street_name','b_street_name','Street/Road Name',$order['add_b_street_name'],array('attr'=>array('class'=>'add_field','maxlength'=>40),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_street_name')));

//echo formInput('text','b_address_1','b_address_1','Address 1',$order['order_header']['address1'],array('attr'=>array('class'=>'add_field','maxlength'=>40),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_address_1')));
echo formInput('text','b_address_2','b_address_2','Address 2',$order['order_header']['address2'],array('attr'=>array('class'=>'add_field noval','maxlength'=>40),'disabled'=>$disableAddress,'error'=>validationError('b_address_2')));
echo formInput('text','b_town','b_town','Town',$order['order_header']['city'],array('attr'=>array('class'=>'add_field','maxlength'=>20),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_town')));
//echo formInput('text','county','county','County',$order['order_header']['sstate'],array('attr'=>array('class'=>'add_field'),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('county')));

echo formInput('text','b_postcode','b_postcode','Postcode',$order['order_header']['zipcode'],array('attr'=>array('class'=>'add_field short_field','maxlength'=>10),'div'=>array('id'=>'b_pcode'),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_postcode')));

echo formInput('select','b_country','b_country','Country',$order['order_header']['country'],array('attr'=>array('class'=>'add_field'),'options'=>$countries,'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('b_country')));
?>
</div>