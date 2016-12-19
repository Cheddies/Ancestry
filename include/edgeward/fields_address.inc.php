<div class="add_col add_col_a">
<?php
//echo formInput('select','title','title','Title',$order['address']['title'],array('attr'=>array('class'=>'add_field'),'options'=>$titles,'disabled'=>$disableAddress,'required'=>!$disableAddress));
echo formInput('text','forename','forename','Forename(s)',$order['order_header']['sfirstname'],array('attr'=>array('class'=>'add_field','maxlength'=>15),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('forename')));

echo formInput('text','surname','surname','Surname',$order['order_header']['slastname'],array('attr'=>array('class'=>'add_field','maxlength'=>20),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('surname')));

echo formInput('text','email','email','Email',$order['order_header']['email'],array('attr'=>array('class'=>'add_field','maxlength'=>50),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('email')));

echo formInput('text','tel','tel','Contact Telephone',$order['order_header']['phone'],array('attr'=>array('class'=>'add_field','maxlength'=>14),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('tel')));
?>
</div>
<div class="add_col add_col_b">
<?
echo formInput('text','house_no','house_no','House Number/Name',$order['add_house_no'],array('attr'=>array('class'=>'add_field','maxlength'=>20),'div'=>array('id'=>'hno'),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('house_no')));

echo formInput('text','street_name','street_name','Street/Road Name',$order['add_street_name'],array('attr'=>array('class'=>'add_field','maxlength'=>40),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('street_name')));

echo formInput('text','address_2','address_2','Address 2',$order['order_header']['saddress2'],array('attr'=>array('class'=>'add_field noval','maxlength'=>40),'disabled'=>$disableAddress,'error'=>validationError('address_2')));

echo formInput('text','town','town','Town',$order['order_header']['scity'],array('attr'=>array('class'=>'add_field','maxlength'=>20),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('town')));

echo formInput('text','postcode','postcode','Postcode',$order['order_header']['szipcode'],array('attr'=>array('class'=>'add_field short_field','maxlength'=>10),'div'=>array('id'=>'pcode'),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('postcode')));

echo formInput('select','country','country','Country',$order['order_header']['scountry'],array('attr'=>array('class'=>'add_field'),'options'=>$countries,'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('country')));

$addDifCheck = $order['billing_different'] ? true : false;
echo "<div class=\"input checkbox\">";
echo formInput('checkbox','billing_different','billing_different','','1',array('attr'=>array('class'=>'add_field noval'),'div'=>0,'disabled'=>$disableAddress,'checked'=>$addDifCheck));
echo "<label for=\"billing_different\">My billing address is different</label></div>";
?>
</div>
