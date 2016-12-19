<?php
include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");

include ('include/header.php');

session_set_cookie_params ( 0,"/." , "", true);

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

$clean=array();

$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);


$table="BMD_contact_countries";
$where=array("visible=1");
$fields=array('code','country','visible');
$order_by="country";
$countries=$DB->getData($table,$fields,$where,"","",$order_by);


if(isset($_SESSION['errors']))
{
	$errors=unserialize($_SESSION['errors']);
}
?>
<form action="p_BMD_contact.php" method="post" >
<input type="hidden" value="<?php echo $token?>" name="token" id="token" /> 
<div id="f-wrap">
<div id="f-header">
<h3>Enter your contact details</h3>
<img src="images/tree-icon.jpg" alt="" class="tree-icon" /></div>
<!-- end f-header-->


<div id="f-content">

<div class="container-1">    
  <fieldset>
   	<legend>Data Protection Notice for Ancestry Shop</legend>
	<div>
	<p>
	The Generations Network (Commerce) Limited (registration number Z1407756) and The Generations Network, Inc are Data Controllers for the purposes of the Data Protection Act 1998.  We may use your personal information for marketing purposes and to facilitate payment and fulfillment for membership and purchases at the Ancestry Shop.  We may share your personal information with carefully selected third parties for the purposes of processing and fulfilling your order.
	</p>
	<br />
	<p>
	You consent to our transferring your information to countries or jurisdictions which do not provide the same level of data protection as the UK, if necessary for the above purposes. 
	</p>
	<br />
	<p>
	For further details of our information gathering and dissemination practices please see our <a href="privacy_policy.php">PRIVACY STATEMENT</a>.
	</p>
	</div> 
</fieldset>
</div>  


<div class="container-1">
	<fieldset>
	  	<legend>Please provide the following information</legend>
	  	 <div>
	  	 	<label for="contact_title">Title<em>*</em> 
	  	 		<?php if(isset($errors['contact_title'])){?>
	     		<br /> <span class="error"><?php echo $errors['contact_title']?></span>
	     		<?php } ?> 
	     	</label>
	   		<input type="text" name="contact_title" id="contact_title" size="45" maxlength="45" value="<?php if(isset($_SESSION['contact_title'])) echo $_SESSION['contact_title']?>" />
	    </div>
	     <div>
	  	 	<label for="contact_firstname">Firstname<em>*</em> 
	  	 		<?php if(isset($errors['contact_firstname'])){?>
	     		<br /> <span class="error"><?php echo $errors['contact_firstname']?></span>
	     		<?php } ?> 
	     	</label>
	   		<input type="text" name="contact_firstname" id="contact_firstname" size="45" maxlength="45" value="<?php if(isset($_SESSION['contact_firstname'])) echo $_SESSION['contact_firstname']?>" />
	    </div>
	    <div>
	  	 	<label for="contact_surname">Surname<em>*</em> 
	  	 		<?php if(isset($errors['contact_surname'])){?>
	     		<br /> <span class="error"><?php echo $errors['contact_surname']?></span>
	     		<?php } ?> 
	     	</label>
	   		<input type="text" name="contact_surname" id="contact_surname" size="45" maxlength="45" value="<?php if(isset($_SESSION['contact_surname'])) echo $_SESSION['contact_surname']?>" />
	    </div>
	    <div>
	  	 	<label for="contact_email">Email<em>*</em> 
	  	 		<?php if(isset($errors['contact_email'])){?>
	     		<br /> <span class="error"><?php echo $errors['contact_email']?></span>
	     		<?php } ?> 
	     	</label>
	   		<input type="text" name="contact_email" id="contact_email" size="50" maxlength="50" value="<?php if(isset($_SESSION['contact_email'])) echo $_SESSION['contact_email']?>" />
	    </div>
	    <div>
	  	 	<label for="contact_country">Country<em>*</em> 
	  	 		<?php if(isset($errors['contact_country'])){?>
	     		<br /> <span class="error"><?php echo $errors['contact_country']?></span>
	     		<?php } ?> 
	     	</label>
	     	<select name="contact_country" id="contact_country">
	     		<option value="">Select Your Country</option>
	     	<?php foreach($countries as $country)
	     	{
		    ?>
		    	<option value="<?php echo $country['code']?>" <?php if(isset($_SESSION['contact_country']) && $_SESSION['contact_country']==$country['code']) echo "selected=\"selected\""?>><?php echo $country['country']?></option>
		    <?php	
	     	}
	     	?>
	     	</select>
	   	</div>
	</fieldset>
	
</div>
<div class="buttons"> 
   	<input type="image" src="images/continue_GRO.gif" alt="submit" />
</div>
</div>
<!-- end f-content-->

</div>
<!-- end f-wrap-->
</form>
<?php include ('include/footer.php');?>