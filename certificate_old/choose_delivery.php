<?php
include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");
//check to ensure everything is set that should be by this page

if(	!isset($_SESSION['cert_choice']) ||
	!isset($_SESSION['cert_id']) ||
	!isset($_SESSION['billing_address_id']) ||
	!isset($_SESSION['delivery_address_id'])
)
{
	header('location:index.php');
	exit();	
}


include ('include/header.php');
require_once ("classes/mysql_class.php");


session_set_cookie_params ( 0,"/." , "", true);
	

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

$clean=array();

$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$shipping_methods=$DB->getData("GRO_tbl_shipping",array("code","description","price"),array("country={$_SESSION['delivery_country']}"),"","LEFT JOIN GRO_country_shipping ON shipping=code");

if(isset($_SESSION['errors']))
{
	$errors=unserialize($_SESSION['errors']);
}

?>
<form action="proccess_delivery.php" method="post">
<input type="hidden" name="token" id="token" value="<?php echo $token?>">
<div id="f-wrap">
<div id="f-header">
<h3>Delivery Details</h3>
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
    <p>Please choose your delivery method and select your contact preferences from below.</p>
    <p>After completing, please press the 'Continue' button to continue.</p>
    </div>
    <div class="container-1">
  <fieldset>
    <legend>Choose Delivery Method</legend>
     <?php if(isset($errors['delivery_method'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_method']?></span>
      <?php } ?> 
    <?php
    foreach($shipping_methods as $method)
    {
	?>
	<div>
		<label for="delivery_method"><?php if($method['price']==0) echo "Free"; else echo formatcurrency($method['price']);?>  <br /> <?php escaped_echo ($method['description']) ?></label>
		<input name="delivery_method" type="radio" class="checkbox" id="delivery_method" value="<?php echo $method['code']?>" <?php if(isset($_SESSION['delivery_method']) && $_SESSION['delivery_method']==$method['code']) echo "checked";?> />
    </div>
	<?php
    }
    ?>
    <br />
    Please note orders received after 4pm on Dec 22nd will not be processed until Dec 29th.
    <!--Orders placed after 1pm will start to be processed on following working day-->
    <br/>
    Costs includes cost of certificate, handling charge, P&P and VAT
  </fieldset>
    </div>
  <div class="container-1"> 
  <fieldset>
    <legend>Contact Preferences</legend>
    <div>
		<label for="dpemail" style="width:600px">Ancestry* may contact you by email with updates, special offers and other information about Ancestry related products and services. By providing us with your email address and clicking "continue" below, you consent to being contacted by email. If you do not want to receive marketing information from Ancestry* by email, please un-tick the box:</label>
		<input name="dpemail" type="checkbox" class="checkbox" id="dpemail" checked="checked" />
    </div>
    
    <div>
		<label for="dprent" style="width:600px">Carefully selected partners and/or suppliers of Ancestry* may contact you by email about family history and related products and services, special offers and promotions. If you consent to receiving these emails, please tick the box</label>
		<input name="dprent" type="checkbox" class="checkbox" <?php if(isset($_SESSION['dprent'])) echo "checked" ?>  id="dprent" />
    </div>
    
    
	*Ancestry means The Generations Network, Inc, The Generations Network Ltd and The Generations Network (Commerce) Ltd 
    
  </fieldset>
  <fieldset>
    <legend>Accept Terms and Conditions</legend>
    <div>
  	 <label for="tcs" style="width:600px"> Before placing an order you should read and understand our <a href="tcs.php" target="_new">Terms and Conditions</a>.  If we accept your order then a binding agreement will come into existence on our Terms and Conditions.  Only tick the box if you wish to be bound by our Terms and Conditions
  	 <?php if(isset($errors['tcs'])){?>
     	<br /> <span class="error"><?php echo $errors['tcs']?></span>
      <?php } ?> 
  	 </label>
	<input name="tcs" type="checkbox" class="checkbox" id="tcs" <?php if(isset($_SESSION['tcs'])) echo "checked" ?> />
    </div>
  <div>
  	 <label for="tcs" style="width:600px">
    Before placing an order you agree that you will use the records you order for the purposes of your personal, family or household affairs (including recreational purposes) only and you appoint The Generations Network (Commerce) Limited as your agent to obtain those records on your behalf.  Where you appoint us to act as agent on your behalf, we agree to process and deliver your order using the information that you provide.  Please be sure that the information supplied is both accurate and complete. We cannot accept any responsibility for false, inaccurate or incomplete information supplied.   We can give no warranty or guarantee in respect of the goods ordered.<?php if(isset($errors['tcs_2'])){?>
     	<br /> <span class="error"><?php echo $errors['tcs_2']?></span>
    <?php } ?> 
     </label>
		<input name="tcs_2" type="checkbox" class="checkbox" id="tcs_2" <?php if(isset($_SESSION['tcs_2'])) echo "checked" ?> />
    </div>  	
   </fieldset>
  </div>
    
    <div class="buttons">
		<input type="image" src="images/continue_GRO.gif" alt="submit" />
    </div>
    </form>
</div>
<!-- end f-content-->

</div>
<!-- end f-wrap-->

<?php
include ('include/footer.php');
?>