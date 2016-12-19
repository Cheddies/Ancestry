<?php
$titleReq = !$disableAddress;
$countyReq = !$disableAddress;
$telReq = $order['country'] == '001' ? false : !$disableAddress;
$postCodeReq = !$disableAddress;
switch ($order['country']) {
    case '012': //australia
		$cityLabel = "City";
		$countyLabel = "State";
		$postcodeLabel ="Postcode";
		$countries = array('Australia'=>'012');
        break;
	case '133': //NZ
		$cityLabel = "City";
		$countyLabel = "State";
		$postcodeLabel ="Postcode";
		$countries = array('New Zealand'=>'133');
		$countyReq = false;
        break;
    case '001': //US
		$cityLabel = "City";
		$countyLabel = "State";
		$postcodeLabel ="Zip Code";
		$titleReq = false;
		$countries = array('United States'=>'001');
		$states = array('Armed Forces Americas (except Canada)'=>'AA','Armed Forces Middle East'=>'AE','ALASKA'=>'AK','ALABAMA'=>'AL','Armed Forces Pacific'=>'AP','ARKANSAS'=>'AR','AMERICAN SAMOA'=>'AS','ARIZONA'=>'AZ','CALIFORNIA'=>'CA','COLORADO'=>'CO','CONNECTICUT'=>'CT','DISTRICT OF COLUMBIA'=>'DC','DELAWARE'=>'DE','FLORIDA'=>'FL','FEDERATED STATES OF MICRONESIA'=>'FM','GEORGIA'=>'GA','GUAM'=>'GU','HAWAII'=>'HI','IOWA'=>'IA','IDAHO'=>'ID','ILLINOIS'=>'IL','INDIANA'=>'IN','KANSAS'=>'KS','KENTUCKY'=>'KY','LOUISIANA'=>'LA','MASSACHUSETTS'=>'MA','MARYLAND'=>'MD','MAINE'=>'ME','MARSHALL ISLANDS'=>'MH','MICHIGAN'=>'MI','MINNESOTA'=>'MN','MISSOURI'=>'MO','NORTHERN MARIANA ISLANDS'=>'MP','MISSISSIPPI'=>'MS','MONTANA'=>'MT','NORTH CAROLINA'=>'NC','NORTH DAKOTA'=>'ND','NEBRASKA'=>'NE','NEW HAMPSHIRE'=>'NH','NEW JERSEY'=>'NJ','NEW MEXICO'=>'NM','NEVADA'=>'NV','NEW YORK'=>'NY','OHIO'=>'OH','OKLAHOMA'=>'OK','OREGON'=>'OR','PENNSYLVANIA'=>'PA','PUERTO RICO'=>'PR','PALAU'=>'PW','RHODE ISLAND'=>'RI','SOUTH CAROLINA'=>'SC','SOUTH DAKOTA'=>'SD','TENNESSEE'=>'TN','TEXAS'=>'TX','UTAH'=>'UT','VIRGINIA'=>'VA','VIRGIN ISLANDS'=>'VI','VERMONT'=>'VT','WASHINGTON'=>'WA','WISCONSIN'=>'WI','WEST VIRGINIA'=>'WV','WYOMING'=>'WY');
        break;
	case '034': //Canada
		$cityLabel = "City";
		$countyLabel = "Province";
		$postcodeLabel ="Postcode";
		$countries = array('Canada'=>'034');
        break;
    default: //UK
		$cityLabel = "Town";
		$countyLabel = "County";
		$postcodeLabel ="Postcode";
		$countries = array('United Kingdom'=>'073');
        break;
}

?>


<div class="add_col add_col_a">
<?php
echo formInput('text','title','title','Title',$order['address']['title'],array('attr'=>array('class'=>'add_field short_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>$titleReq,'error'=>validationError('title')));

echo formInput('text','forename','forename',$firstnameLabel,$order['address']['first_name'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('forename')));

echo formInput('text','surname','surname',$lastnameLabel,$order['address']['surname'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('surname')));

echo formInput('text','email','email','Email',$order['orders']['email'],array('attr'=>array('class'=>'add_field','maxlength'=>50),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('email')));

echo formInput('text','tel','tel','Contact Telephone',$order['orders']['phone'],array('attr'=>array('class'=>'add_field','maxlength'=>14),'disabled'=>$disableAddress,'required'=>$telReq,'error'=>validationError('tel')));

echo "<div class=\"edit_btn_wrapper\">";
if($editset == 'add' && $formset == "summary"){
	echo "<input type=\"submit\" value=\"Save\" class=\"noval edit_btn\" name=\"btn\"/>";   	
} elseif(!$editset  && $formset == "summary") {
	echo "<a href=\"edit_summary.php?edit=add\" class=\"edit\" id=\"edit_add\">Edit</a>";
}
echo "</div>";

?>
</div>
<div class="add_col add_col_b">
<?php

if($order['country'] == '073'){ //if country is uk, add some fields for postcode lookup
	echo formInput('text','house_no','house_no','House Number/Name',$order['add_house_no'],array('attr'=>array('class'=>'add_field','maxlength'=>20),'div'=>array('id'=>'hno'),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('house_no')));
	echo formInput('text','street_name','street_name','Street/Road Name',$order['add_street_name'],array('attr'=>array('class'=>'add_field','maxlength'=>40),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('street_name')));
} else {
	echo formInput('text','address_1','address_1','Address 1',$order['address']['line_1'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('address_1')));
}
echo formInput('text','address_2','address_2','Address 2',$order['address']['line_2'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'optional'=>1,'error'=>validationError('address_2')));

echo formInput('text','town','town',$cityLabel,$order['address']['city'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('town')));
if($order['country'] == '001')
{
		echo formInput('select','county','county',$countyLabel,$order['address']['county'],array('attr'=>array('class'=>'add_field'),'options'=>$states,'disabled'=>$disableAddress,'required'=>$countyReq,'error'=>validationError('county')));
}
else
{
	echo formInput('text','county','county',$countyLabel,$order['address']['county'],array('attr'=>array('class'=>'add_field','maxlength'=>45),'disabled'=>$disableAddress,'required'=>$countyReq,'error'=>validationError('county')));
}
if($order['country'] != '073'){
    echo formInput('select','country','country','Country',$order['address']['country'],array('attr'=>array('class'=>'add_field'),'options'=>$countries,'disabled'=>$disableAddress,'required'=>!$disableAddress,'error'=>validationError('country')));
}

echo formInput('text','postcode','postcode',$postcodeLabel,$order['address']['postcode'],array('attr'=>array('class'=>'add_field short_field','maxlength'=>10),'div'=>array('id'=>'pcode'),'disabled'=>$disableAddress,'required'=>$postCodeReq,'error'=>validationError('postcode')));


if($order['country'] == '073') echo "<input type=\"hidden\" id=\"country\" name=\"country\" value=\"{$order['country']}\"/>";

$addDifCheck = $order['billing_different'] ? true : false;
echo "<div class=\"input checkbox\">";
echo formInput('checkbox','billing_different','billing_different','','1',array('attr'=>array('class'=>'add_field noval'),'div'=>0,'disabled'=>$disableAddress,'checked'=>$addDifCheck));
echo "<label for=\"billing_different\">My billing address is different</label></div>";

?>
</div>