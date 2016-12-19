<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();


$clean=array();
$error=false;
$query_string="error=1";
$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$errors=array();
$_SESSION['errors']="";
	

$form_fields=array( array ("name" => "order_id","display_name" => "Order ID","length"=>"6","reg_ex"=>"","required"=>true));
	
$errors = process_form($form_fields,$_GET,&$clean);
	
if(sizeof($errors)>0)
{
	$_SESSION['errors']=serialize($errors);
	header('location:index.php');
	exit();
}
else
{
	include ('include/header.php');

	$fields=array("GRO_orders.GRO_orders_id","order_number","billing_address","shipping_address","order_date","GRO_tbl_shipping.description","GRO_tbl_shipping.price","email","phone","order_status","st_ref","GRO_ref","GRO_date","completed_date","discount","discount_code");
	$where=array("GRO_orders.GRO_orders_id={$clean['order_id']}");
	$join ="LEFT JOIN GRO_tbl_shipping on delivery_method=GRO_tbl_shipping.code LEFT JOIN GRO_orders_extra ON GRO_orders.GRO_orders_id=GRO_orders_extra.GRO_orders_id ";
	$order=$DB->getData("GRO_orders",$fields,$where,"",$join,"");
	$order_data=$order[0];
	
	$fields=array("first_name","surname","line_1","line_2","city","county","postcode","GRO_tbl_countries.country","title");
	$where=array("GRO_addresses_id={$order_data['billing_address']}");
	$join="LEFT JOIN GRO_tbl_countries on code=GRO_addresses.country";
	$address=$DB->getData("GRO_addresses",$fields,$where,"",$join,"");
	$billing_address=$address[0];
	
	$where=array("GRO_addresses_id={$order_data['shipping_address']}");
	$address=$DB->getData("GRO_addresses",$fields,$where,"",$join,"");
	$delivery_address=$address[0];
	
	$fields=array("certificate_id","certificate_type");
	$where=array("order_number={$order_data['order_number']}");
	$cert=$DB->getData("GRO_certificate_ordered",$fields,$where,"","","");
	
	$cert_ordered=$cert[0];
	
	/*
	new fields and labels
	"GRO_district_no","GRO_reg_no","GRO_entry_no",
	"District Number","Reg Number","Entry Number",
	*/
	
	$GRO_fields=array("GRO_index_year","GRO_index_quarter","GRO_index_district","GRO_district_no","GRO_reg_no","GRO_entry_no","GRO_index_volume","GRO_index_page","GRO_index_reg","GROI_known");
	$GRO_labels=array("Year","Quarter","District Name","District Number","Reg Number","Entry Number","Volume Number","Page Number","Reg");
	
	switch($cert_ordered['certificate_type'])
	{
		CASE BIRTH:
		$where=array("GRO_birth_certificates_id={$cert_ordered['certificate_id']}");
		$clean['cert_ordered']="Birth Certificate";
		$required_fields=array("birth_reg_year","birth_surname","forenames","DOB","birth_place","fathers_surname","fathers_forenames","mothers_maiden_surname","mothers_surname_birth","mothers_forenames","copies","scan_and_send");
		$cert_details=$DB->getData("GRO_birth_certificates",$required_fields,$where);
		$labels=array("Year Birth Was Registerd","Surname at Birth","Forenames","DOB","Place of Birth","Father's Surname","Father's Forename","Mother's Maiden Surname","Mother's Surname at Time of Birth","Mother's Forename(s)","Copies");
		$GRO_details=$DB->getData("GRO_birth_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
		$cert_details['DOB']=$cert_details[3]=$DB->format_date($cert_details[3]);

	break;
	CASE DEATH:
		$where=array("GRO_death_certificates_id={$cert_ordered['certificate_id']}");
		$clean['cert_ordered']="Death Certificate";
		$required_fields=array("registered_year","surname_deceased","forenames_deceased","death_date","relationship_to_deceased","death_age","fathers_surname","fathers_forenames","mothers_surname","mothers_forenames","copies","scan_and_send");
		$cert_details=$DB->getData("GRO_death_certificates",$required_fields,$where);
		$labels=array("Year Death was Registered","Surname of Deceased","Forename(s) of Deceased","Date of Death","Relationship to the Deceased","Age at Death in Years","Father's Surname","Father's Forename(s)","Mother's Surname","Mother's Forename(s)","Copies");
		$GRO_details=$DB->getData("GRO_death_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
		$cert_details['death_date']=$cert_details[3]=$DB->format_date($cert_details[3]);

	break;
	CASE MARRIAGE:
		$where=array("GRO_marriage_certificates_id={$cert_ordered['certificate_id']}");
		$clean['cert_ordered']="Marriage Certificate";
		$required_fields=array("registered_year","mans_surname","mans_forenames","womans_surname","womans_forenames","copies","scan_and_send");
		$labels=array("Year Marriage was Registered","Man's Surname","Man's Forenames","Woman's Surname","Woman's Forenames","Copies");
		$cert_details=$DB->getData("GRO_marriage_certificates",$required_fields,$where);
		$GRO_details=$DB->getData("GRO_marriage_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
	break;
	}
	$GRO_details=$GRO_details[0];
$GRO_details['GRO_index_reg']=$GRO_details[8]=substr($DB->format_date($GRO_details[8]),3);
?>
<div id="admin_page">

<div id="f-wrap">
<div id="f-header">
<h3><?php
	echo $clean['order_id'] . " - " ;
	echo $DB->format_date($order_data['order_date']) . " - " ;
	echo descriptive_status($order_data['order_status']);
	
?>
</h3>
<img src="images/tree-icon.jpg" alt="" class="tree-icon">
</div>
</div>

<div id="order_details">


<div id="f-content">

<?php 
if(isset($_SESSION['user_level']) && $_SESSION['user_level']==ADMIN_USER)
{
?>
<div id="update_bar">
<?php switch($order_data['order_status']){
	case 1:
	?>
		<!--<a href="update_order_status.php?token=<?php echo $token?>&amp;order_id=<?php echo $clean['order_id']?>&amp;status=2">Mark as Sent to GRO</a> -->
		<a href="add_GRO_ref.php?token=<?php echo $token?>&amp;order_id=<?php echo $clean['order_id']?>">Mark As Sent To GRO</a>
		<a style="float:right" href="update_order_status.php?token=<?php echo $token?>&amp;order_id=<?php echo $clean['order_id']?>&amp;status=4">Cancel Order</a>
	<?php
	break;
	case 2:
	?>
	<a href="update_order_status.php?token=<?php echo $token?>&amp;order_id=<?php echo $clean['order_id']?>&amp;status=3">Mark as Complete</a>
	<?php
	break;
	case 3:
	?>
	<a href="invoice.php?order_id=<?php echo $clean['order_id']?>">View Invoice</a>
	<a style="float:right" href="update_order_status.php?token=<?php echo $token?>&amp;order_id=<?php echo $clean['order_id']?>&amp;status=1">Reset Status</a>
	<?php
	break;
}
?>
<a href="view_order_note.php?token=<?php echo $token?>&amp;order_id=<?php echo $clean['order_id']?>" />View Notes</a>
<a href="add_order_note.php?token=<?php echo $token?>&amp;order_id=<?php echo $clean['order_id']?>" />Add Note</a>
</div>
<?php
}//end of check for user level
?>
<div class="container-1">


  <fieldset>
	  <legend>Order Details</legend>
	  
	  <?php 
		  if($cert_details['scan_and_send']>0)
		  {
			?>
			<div class="important">
			This is a Scan and Send Order 
			</div>
			<?php
		  }
		  if($delivery_address['country']=='Australia')
		  {
			?>
			<div class="important">
			This is an Australian Order
			</div>
			<?php	  
		  }
		  elseif($delivery_address['country']=='United States')
		  {
			?>
			<div class="important">
			This is an United States Order
			</div>
			<?php	  
		  }
		  elseif($delivery_address['country']=='Canada')
		  {
			?>
			<div class="important">
			This is an Canadian Order
			</div>
			<?php	  
		  }
	  ?>	    
	  <div>
	  <label for="Order Date">Order Date:</label><?php echo $DB->format_date($order_data['order_date'])?>
	  </div>
	  <div>
	  <label for="email">Email:</label><?php echo $order_data['email']?>
	  </div>
	    <div>
	  <label for="telephone">Phone:</label><?php echo $order_data['phone']?>
	   </div>
	    <div>
	  <label for="telephone">Delivery Method</label> <?php echo $order_data['description']?>
	   </div>
	   <div>
	  <label for="st_ref">Secure Trading Reference</label> <?php echo $order_data['st_ref']?>
	   </div>
	    <div>
	  <label for="GRO_ref">GRO Reference</label> <?php echo $order_data['GRO_ref']?>
	   </div>
	    <div>
	  <label for="GRO_date">Sent to GRO</label> <?php echo  $DB->format_date($order_data['GRO_date'])?>
	   </div>
	    <div>
	  <label for="completed_date">Completed Date</label> <?php echo  $DB->format_date($order_data['completed_date'])?>
	   </div>
	  </fieldset>
	  
	   <?php if($order_data['discount']>0) {?>
	   <fieldset>
		   <legend>Discount Details</legend>
		   <div>
		   <label for="discount">Discount</label> <?php echo $order_data['discount']?>%
		   </div>
		   <div>
		   <label for="discount">Discount Code</label> <?php echo $order_data['discount_code']?>
		   </div>
	   </fieldset>
	   <?php
	   } ?>
	   
  </fieldset>
 </div>

<div class="container-1">
  <fieldset>
    <legend><?php echo $clean['cert_ordered'];?></legend>
	<?php
	$count=0;
	foreach($labels as $label)
	{
		
	?>
	<div>
	<label for="<?php echo $label?>"><?php echo $label?></label><?php echo $cert_details[$count]?>
	</div>
	<?php
	$count++;
	}
	?>
  </fieldset>
  </div>
  <?php
  if($GRO_details['GROI_known']==1)
  {
  ?>
  <div class="container-1">
  <fieldset>
    <legend>Reference Information from GRO Index</legend>
	<?php
	$count=0;
	foreach($GRO_labels as $label)
	{
		
	?>
	<div>
	<label for="<?php echo $label?>"><?php echo $label?></label><?php echo $GRO_details[$count]?>
	</div>
	<?php
	$count++;
	}
	?>
  </fieldset>
  </div>
  <?php
}
  else
  {
	?>
	<div class="container-1">
  <fieldset>
    <legend>GRO Index Reference not entered</legend>
     </fieldset>
  </div>
	<?php
  }
  ?>
  
  <div class="container-1">
  <fieldset>
    <legend>Billing Address</legend>
   
    <div>
    <label for="title">Title</label><?php echo $billing_address['title']?>
    </div>
   	<div>
	<label for="first_name">First Name</label><?php echo $billing_address['first_name']?>
	</div>
	<div>
	<label for="surname">Surname Name</label><?php echo $billing_address['surname']?>
	</div>
	<div>
	<label for="address_line_1">Address Line 1</label><?php echo $billing_address['line_1']?>
	</div>
	<div>
	<label for="address_line_2">Address Line 2</label><?php echo $billing_address['line_2']?>
	</div>
	<div>
	<label for="Town">Town</label><?php echo $billing_address['city']?>
	</div>
	<div>
	<label for="County">County</label><?php echo $billing_address['county']?>
	</div>
	<div>
	<label for="postcode">Postcode</label><?php echo $billing_address['postcode']?>
	</div>
	<div>
	<label for="country">Country</label><?php echo $billing_address['country']?>
	</div>
</fieldset>
  </div>
    <div class="container-1">
  <fieldset>
    <legend>Delivery Address</legend>
	<div>
    <label for="title">Title</label><?php echo $delivery_address['title']?>
    </div>
    <div>
	<label for="first_name">First Name</label><?php echo $delivery_address['first_name']?>
	</div>
	<div>
	<label for="surname">Surname Name</label><?php echo $delivery_address['surname']?>
	</div>
	<div>
	<label for="address_line_1">Address Line 1</label><?php echo $delivery_address['line_1']?>
	</div>
	<div>
	<label for="address_line_2">Address Line 2</label><?php echo $delivery_address['line_2']?>
	</div>
	<div>
	<label for="Town">Town</label><?php echo $delivery_address['city']?>
	</div>
	<div>
	<label for="County">County</label><?php echo $delivery_address['county']?>
	</div>
	<div>
	<label for="postcode">Postcode</label><?php echo $delivery_address['postcode']?>
	</div>
	<div>
	<label for="country">Country</label><?php echo $delivery_address['country']?>
	</div>
  </fieldset>
  </div>
  
 
</div>
</div>
</div>
<?php
}
include ('include/footer.php');


?>