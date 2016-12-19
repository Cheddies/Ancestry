<?php
include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");
//check to ensure everything is set that should be by this page

if(	!isset($_SESSION['cert_choice']) ||
	!isset($_SESSION['cert_id'])
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

$countries=$DB->getData("GRO_tbl_countries",array("code","country"),array("visible=1"));
if(isset($_SESSION['errors']))
{
	$errors=unserialize($_SESSION['errors']);
}

?>
<form action="proccess_address_details.php" method="post">
<input type="hidden" name="token" id="token" value="<?php echo $token?>">
<div id="f-wrap">
<div id="f-header">
<h3>Address Details</h3>
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
    <p>Please enter your address details below.</p>
    <p>After completing, please press the 'Continue' button to continue.</p>
    </div>
    
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
</div>  
    <div class="container-1">    
  <fieldset>
    	<legend>Billing address details</legend>
        
        <div><label for="title">Title<em>*</em> 
        <?php if(isset($errors['title'])){?>
     	<br /> <span class="error"><?php echo $errors['title']?></span>
      	<?php } ?> 
        </label>
        <input type="text" name="title" id="title" value="<?php if(isset($_SESSION['title'])) echo $_SESSION['title']?>" maxlength="45" size="45" /></div>
        
        <div><label for="forenames">Forenames<em>*</em> 
        <?php if(isset($errors['forenames'])){?>
     	<br /> <span class="error"><?php echo $errors['forenames']?></span>
      	<?php } ?> 
        </label>
        <input type="text" name="forenames" id="forenames" value="<?php if(isset($_SESSION['forenames'])) echo $_SESSION['forenames']?>" maxlength="45" size="45" /></div>
        
        <div><label for="surname">Surname<em>*</em>
        <?php if(isset($errors['surname'])){?>
     	<br /> <span class="error"><?php echo $errors['surname']?></span>
     	<?php } ?>  </label>
        <input type="text" name="surname" id="surname" value="<?php if(isset($_SESSION['surname'])) echo $_SESSION['surname']?>" maxlength="45" size="45"/></div>
           
        <div><label for="address_line_1">Address Line 1<em>*</em>
        <?php if(isset($errors['address_line_1'])){?>
     	<br /> <span class="error"><?php echo $errors['address_line_1']?></span>
    	<?php } ?> 
        </label>
        <input type="text" name="address_line_1" id="address_line_1" value="<?php if(isset($_SESSION['address_line_1'])) echo $_SESSION['address_line_1']?>" maxlength="45" size="45" /></div>
        
        <div><label for="address_line_2">Address Line 2
        <?php if(isset($errors['address_line_2'])){?>
     	<br /> <span class="error"><?php echo $errors['address_line_2']?></span>
      	<?php } ?> 
        </label>
        <input type="text" name="address_line_2" id="address_line_2" value="<?php if(isset($_SESSION['address_line_2'])) echo $_SESSION['address_line_2']?>" maxlength="45" size="45" /></div>
        
        <div><label for="town">Town<em>*</em>
        <?php if(isset($errors['town'])){?>
     	<br /> <span class="error"><?php echo $errors['town']?></span>
      	<?php } ?> 
       </label>
        <input type="text" name="town" id="town" value="<?php if(isset($_SESSION['town'])) echo $_SESSION['town']?>" maxlength="45" size="45" /></div>
        
        <div><label for="county">County<em>*</em>
        <?php if(isset($errors['county'])){?>
     	<br /> <span class="error"><?php echo $errors['county']?></span>
     	<?php } ?> 
        </label>
        <input type="text" name="county" id="county" value="<?php if(isset($_SESSION['county'])) echo $_SESSION['county']?>" maxlength="45" size="45" /></div>
        
        <div><label for="country">Country<em>*</em>
        <?php if(isset($errors['country'])){?>
     	<br /> <span class="error"><?php echo $errors['country']?></span>
      	<?php } ?> 
        </label>
        <select id="country" name="country">
          <option selected="selected" value="">Select</option>
          <?php
          foreach ($countries as $country)
          {
	      ?>
	      	<option value="<?php echo $country['code']?>" <?php if(isset($_SESSION['country']) && $_SESSION['country']==$country['code']) echo "selected"?>><?php echo $country['country']?></option>
	      <?php
          }
          ?>
        </select></div>
        
        <div><label for="postcode">Postcode<em>*</em> 
        <?php if(isset($errors['postcode'])){?>
     	<br /> <span class="error"><?php echo $errors['postcode']?></span>
     	<?php } ?> 
        </label>
        <input type="text" name="postcode" id="postcode" value="<?php if(isset($_SESSION['postcode'])) echo $_SESSION['postcode']?>" maxlength="9" size="9" /></div>
        
         
        <div><label for="email">Email Address<em>*</em> 
        <?php if(isset($errors['email'])){?>
     	<br /> <span class="error"><?php echo $errors['email']?></span>
     	<?php } ?> 
        </label>
        <input type="text" name="email" id="email" value="<?php if(isset($_SESSION['email'])) echo $_SESSION['email']?>" maxlength="50" size="50" /></div>
        
        <div><label for="phone">Telephone Number (daytime)<em>*</em>
        <?php if(isset($errors['phone'])){?>
     	<br /> <span class="error"><?php echo $errors['phone']?></span>
     	<?php } ?> 
         </label>
        <input type="text" name="phone" id="phone" value="<?php if(isset($_SESSION['phone'])) echo $_SESSION['phone']?>" maxlength="14" size="14" /></div>
        
    </fieldset>
    </div>
    
      <div class="container-1">
  <fieldset>
    	<legend>Delivery address details</legend>
    	
         <div><label for="delivery_same">Delivery Address is same as Billing address
         <?php if(isset($errors['delivery_same'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_same']?></span>
     	 <?php } ?> 
         </label>
        <input type="checkbox" name="delivery_same" id="delivery_same" <?php if(isset($_SESSION['delivery_same'])) echo "checked" ?> /></div>
    	
        <div><label for="delivery_title">Title<em>*</em>
        <?php if(isset($errors['delivery_title'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_title']?></span>
      	<?php } ?> 
        </label>
        <input type="text" name="delivery_title" id="delivery_title"  value="<?php if(isset($_SESSION['delivery_title'])) echo $_SESSION['delivery_title']?>" maxlength="45" size="45" /></div>
        
        <div><label for="delivery_forenames">Forenames<em>*</em> 
        <?php if(isset($errors['delivery_forenames'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_forenames']?></span>
     	<?php } ?> 
        </label>
        <input type="text" name="delivery_forenames" id="delivery_forenames" value="<?php if(isset($_SESSION['delivery_forenames'])) echo $_SESSION['delivery_forenames']?>" maxlength="45" size="45" /></div>
        
        <div><label for="delivery_surname">Surname<em>*</em> 
        <?php if(isset($errors['delivery_surname'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_surname']?></span>
     	<?php } ?> 
        </label>
        <input type="text" name="delivery_surname" id="delivery_surname" value="<?php if(isset($_SESSION['delivery_surname'])) echo $_SESSION['delivery_surname']?>" maxlength="45" size="45" /></div>
        
        <div><label for="delivery_address_line_1">Address Line 1<em>*</em>
        <?php if(isset($errors['delivery_address_line_1'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_address_line_1']?></span>
      	<?php } ?> 
        </label>
        <input type="text" name="delivery_address_line_1" id="delivery_address_line_1" value="<?php if(isset($_SESSION['delivery_address_line_1'])) echo $_SESSION['delivery_address_line_1']?>" maxlength="45" size="45"  /></div>
        
        <div><label for="delivery_address_line_2">Address Line 2
        <?php if(isset($errors['delivery_address_line_2'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_address_line_2']?></span>
     	<?php } ?> 
        </label>
        <input type="text" name="delivery_address_line_2" id="delivery_address_line_2" value="<?php if(isset($_SESSION['delivery_address_line_2'])) echo $_SESSION['delivery_address_line_2']?>" maxlength="45" size="45" /></div>
        
        <div><label for="delivery_town">Town<em>*</em> 
        <?php if(isset($errors['delivery_town'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_town']?></span>
     	<?php } ?> 
        </label>
        <input type="text" name="delivery_town" id="delivery_town" value="<?php if(isset($_SESSION['delivery_town'])) echo $_SESSION['delivery_town']?>" maxlength="45" size="45" /></div>
        
        <div><label for="delivery_county">County<em>*</em>
        <?php if(isset($errors['delivery_county'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_county']?></span>
     	<?php } ?> 
         </label>
        <input type="text" name="delivery_county" id="delivery_county" value="<?php if(isset($_SESSION['delivery_county'])) echo $_SESSION['delivery_county']?>" maxlength="45" size="45" /></div>
        
        <div><label for="delivery_country">Country<em>*</em>
        <?php if(isset($errors['delivery_country'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_country']?></span>
      	<?php } ?>         
         </label>
        <select id="delivery_country" name="delivery_country">
          <option selected="selected" value="">Select</option>
          <?php
          foreach ($countries as $country)
          {
	      ?>
	      	<option value="<?php echo $country['code']?>" <?php if(isset($_SESSION['delivery_country']) && $_SESSION['country']=$country['code']) echo "selected"?>><?php echo $country['country']?></option>
	      <?php
          }
          ?>
        </select></div>
        
        <div><label for="delivery_postcode">Postcode<em>*</em> 
        <?php if(isset($errors['delivery_postcode'])){?>
     	<br /> <span class="error"><?php echo $errors['delivery_postcode']?></span>
     	<?php } ?> 
        </label>
         <input type="text" name="delivery_postcode" id="delivery_postcode" value="<?php if(isset($_SESSION['delivery_postcode'])) echo $_SESSION['delivery_postcode']?>" maxlength="9" size="9" /></div>        
    </fieldset>
    </div>
    
    <div class="buttons">
		<input type="image" src="images/continue_GRO.gif" alt="Continue" />
    </div>
    </form>
</div>
<!-- end f-content-->

</div>
<!-- end f-wrap-->

<?php
include ('include/footer.php');
?>