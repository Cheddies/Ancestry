<?php
include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");
//check to ensure everything is set that should be by this page

if(	!isset($_SESSION['cert_choice']) ||
	!isset($_SESSION['cert_id']) ||
	!isset($_SESSION['billing_address_id']) ||
	!isset($_SESSION['delivery_address_id']) ||
	!isset($_SESSION['GRO_orders_id'])
)
{
	header('location:index.php');
	exit();	
}



include ('include/header.php');
require_once ("classes/mysql_class.php");

session_set_cookie_params ( 0,"/." , "", true);


$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

//store this in the order header table for later
$DB->updateData("GRO_orders",array("token","token_time"),array($_SESSION['token'],$_SESSION['token_time']),array("GRO_orders_id={$_SESSION['GRO_orders_id']}"));

//update the certficate_ordered table 
//this stores which certficate has been ordered and is used for the update proccess
//and for viewing the orders
$DB->storeData("GRO_certificate_ordered",array("order_number","certificate_id","certificate_type"),array(session_id(),$_SESSION['cert_id'],$_SESSION['cert_choice']));

$clean=array();
$clean['session_id']=session_id();

$address_fields=array("title","first_name","surname","line_1","line_2","city","county","postcode","country");

$billing_address=$DB->getData("GRO_addresses",$address_fields,array("GRO_addresses_id={$_SESSION['billing_address_id']}"));
$delivery_address=$DB->getData("GRO_addresses",$address_fields,array("GRO_addresses_id={$_SESSION['delivery_address_id']}"));

$billing_address=$billing_address[0];
$delivery_address=$delivery_address[0];

$required_fields=array();



$GRO_fields=array("GRO_index_year","GRO_index_quarter","GRO_index_district","GRO_index_volume","GRO_index_page","GRO_index_reg");
$GRO_labels=array("Year","Quarter","District Name","Volume Number","Page Number","Reg");



switch($_SESSION['cert_choice'])
{
	CASE BIRTH:
		$where=array("GRO_birth_certificates_id={$_SESSION['cert_id']}");
		$clean['cert_ordered']="Birth Certificate";
		$required_fields=array("birth_reg_year","birth_surname","forenames","DOB","birth_place","fathers_surname","fathers_forenames","mothers_maiden_surname","mothers_surname_birth","mothers_forenames","copies");
		$cert_details=$DB->getData("GRO_birth_certificates",$required_fields,$where);

		$GRO_details=$DB->getData("GRO_birth_certificates",$GRO_fields,$where);
		
		$cert_details=$cert_details[0];
		if($cert_details['birth_reg_year']<'1984')
		{
			$labels=array("Year Birth Was Registerd","Surname at Birth","Forenames","Place of Birth");
			
			//unset the unused variables
			//index number is used to access later on, so unset this as well as named index.
			unset($cert_details['DOB']);
			unset($cert_details[3]);
			
			unset($cert_details['fathers_surname']);
			unset($cert_details[5]);
			
			unset($cert_details['fathers_forenames']);
			unset($cert_details[6]);
			
			unset($cert_details['mothers_maiden_surname']);
			unset($cert_details[7]);
			
			unset($cert_details['mothers_surname_birth']);
			unset($cert_details[8]);
			
			unset($cert_details['mothers_forenames']);
			unset($cert_details[9]);
			
			//renumber the array so all numbers are sequential
			$cert_details=array_merge($cert_details);			
			
		}
		else
		{
			$labels=array("Year Birth Was Registerd","Surname at Birth","Forenames","DOB","Place of Birth","Father's Surname","Father's Forename","Mother's Maiden Surname","Mother's Surname at Time of Birth","Mother's Forename(s)");
			$cert_details['DOB']=$cert_details[3]=$DB->format_date($cert_details[3]);
		}
		
		$GRO_nice_order_number="BIRTH_CERTIFICATE_" . $_SESSION['GRO_orders_id'];
	break;
	CASE DEATH:
		$where=array("GRO_death_certificates_id={$_SESSION['cert_id']}");
		$clean['cert_ordered']="Death Certificate";
		$required_fields=array("registered_year","surname_deceased","forenames_deceased","death_date","relationship_to_deceased","death_age","fathers_surname","fathers_forenames","mothers_surname","mothers_forenames","copies");
		$cert_details=$DB->getData("GRO_death_certificates",$required_fields,$where);
		$labels=array("Year Death was Registered","Surname of Deceased","Forename(s) of Deceased","Date of Death","Relationship to the Deceased","Age at Death in Years","Father's Surname","Father's Forename(s)","Mother's Surname","Mother's Forename(s)");
		$GRO_details=$DB->getData("GRO_death_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
		$cert_details['death_date']=$cert_details[3]=$DB->format_date($cert_details[3]);
		$GRO_nice_order_number="DEATH_CERTIFICATE_" . $_SESSION['GRO_orders_id'];
	break;
	CASE MARRIAGE:
		$where=array("GRO_marriage_certificates_id={$_SESSION['cert_id']}");
		$clean['cert_ordered']="Marriage Certificate";
		$required_fields=array("registered_year","mans_surname","mans_forenames","womans_surname","womans_forenames","copies");
		$labels=array("Year Marriage was Registered","Man's Surname","Man's Forenames","Woman's Surname","Woman's Forenames");
		$cert_details=$DB->getData("GRO_marriage_certificates",$required_fields,$where);
		$GRO_details=$DB->getData("GRO_marriage_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
		$GRO_nice_order_number="MARRIAGE_CERTIFICATE_" . $_SESSION['GRO_orders_id'];
	break;
}


$GRO_details=$GRO_details[0];
$GRO_details['GRO_index_reg']=$GRO_details[5]=substr($DB->format_date($GRO_details[5]),3);

//work out the total charges
$delivery=$DB->getData("GRO_tbl_shipping",array("price"),array("code={$_SESSION['delivery_method']}"));
$delivery_cost=$delivery[0]['price'];

if($cert_details['copies']>1)
{
	//additional charge for extra copies
	$extra_copies = $cert_details['copies'] - 1;
	$copies_cost=$extra_copies*CERT_COST;
	$order_total=$delivery_cost+$copies_cost;
}
else
{
	$order_total=$delivery_cost;
}

?>
<!-- Google code has to come before form with javascript call in -->
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	var pageTracker = _gat._getTracker("UA-810272-11");
	pageTracker._setDomainName("none");
	pageTracker._setAllowLinker(true);
	pageTracker._initData();
	pageTracker._trackPageview();
</script>

<form method="post" action="https://securetrading.net/authorize/form.cgi" onSubmit="javascript:pageTracker._linkByPost(this)">
<input type="hidden" name="gro" id="gro" value="true" />
<input type="hidden" name="token" id="token" value="<?php echo $token?>">
<input name="orderref" value="<?php echo $GRO_nice_order_number?>" type="hidden">
<input name="orderinfo" value="AncestryShop.co.uk Online Order" type="hidden">
<input name="name" value="<?php escaped_echo($_SESSION['forenames'] ." " . $_SESSION['surname'])?>" type="hidden">
<input name="company" value="" type="hidden">
<input name="address" value="<?php escaped_echo($_SESSION['address_line_1'] . "," . $_SESSION['address_line_2'])?>" type="hidden">
<input name="town" value="<?php escaped_echo($_SESSION['town'])?>" type="hidden">
<input name="county" value="<?php escaped_echo($_SESSION['county'])?>" type="hidden">
<input name="country" value="<?php escaped_echo($_SESSION['country_name'])?>" type="hidden">
<input name="postcode" value="<?php escaped_echo($_SESSION['postcode'])?>" type="hidden">
<input name="telephone" value="<?php escaped_echo($_SESSION['phone'])?>" type="hidden">
<input name="fax" value="" type="hidden">
<input name="email" value="<?php escaped_echo($_SESSION['email'])?>" type="hidden">
<input name="url" value="" type="hidden">
<input name="currency" value="gbp" type="hidden">
<input name="requiredfields" value="name,email" type="hidden">
<!-- Test Account -->
<input name="merchant" value="testelmbank9808" type="hidden">
<input name="merchantemail" value="chris.gan@internetlogistics.com" type="hidden">
<input name="customeremail" value="1" type="hidden">
<input name="settlementday" value="1" type="hidden">
<input name="callbackurl" value="1" type="hidden">
<input name="session_id" value="<?php escaped_echo(session_id())?>" type="hidden">
<input type="hidden" name="cj_tracking" value="https://www.ancestryshop.co.uk/images/blank.gif" />
<input type="hidden" name="ba_tracking" value="https://www.ancestryshop.co.uk/images/blank.gif" />
<input name="amount" value="<?php echo $order_total*100?>" type="hidden">

<div id="f-wrap">
<div id="f-header">
<h3>Order Summary</h3>
<img src="images/tree-icon.jpg" alt="" class="tree-icon" />
</div>
<!-- end f-header-->


<div id="f-content">
<div class="breadcrumb">
  <ul>
    <?php
   if(isset($_SESSION['bread_crumbs']))
   {
	   $bread_crumbs=unserialize($_SESSION['bread_crumbs']);
		foreach ($bread_crumbs as $crumb)
		{
		
			if(isset($crumb['link']))
			{
			?>	
	      	<li><a href="<?php echo $crumb['link']?>"><?php echo $crumb['text']?></a>&gt;</li>
	       	<?php
			}
			else
			{
			?>
			<li><?php echo $crumb['text']?>&gt;</li>
			<?php
			}
		}	
   }
   ?>
  </ul>
  
	</div>
  <div class="intro">
    <p>Please check all your details below are correct</p>
    <p>Click 'Submit' to continue to the payment page</p>
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
  <?php if(isset($_SESSION['GROI_known']) && $_SESSION['GROI_known']==1){?>
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
<?php }?>
  
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
	<label for="country">Country</label><?php echo $_SESSION['country_name']?>
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
	<label for="country">Country</label><?php echo $_SESSION['delivery_country_name']?>
	</div>
  </fieldset>
  </div>
    
  <div class="container-1">
  <fieldset>
    <legend>Order Charges</legend>
    <div>
	<label for="">Delivery Charge </label><?php echo formatcurrency($delivery_cost)?>
	</div>
 
  
  <?php if(isset($copies_cost)){?>

    <div>
	<label for=""><?php  echo $extra_copies?> Extra <?php if($extra_copies==1) echo "Copy"; else echo "Copies"?> </label><?php echo formatcurrency($copies_cost)?>
	</div>

  <?php }?>
   
    <div>
	<label for="">Total </label><?php echo formatcurrency($order_total)?>
	</div>
  </fieldset>
  </div>
  
  
    <div class="buttons">
		<input type="image" src="images/submit.gif" alt="submit" />
    </div>
    </form>
</div>
<!-- end f-content-->

</div>
<!-- end f-wrap-->

<?php
include ('include/footer.php');
?>