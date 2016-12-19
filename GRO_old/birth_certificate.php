<?php
include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");

//check to ensure everything is set that should be by this page

if(	!isset($_SESSION['cert_choice']) )
{
	header('location:index.php');
	exit();	
}


include ('include/header.php');


session_set_cookie_params ( 0,"/." , "", true);
	


$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

$clean=array();

if(isset($_SESSION['errors']))
{
	$errors=unserialize($_SESSION['errors']);
}
?>
<form action="birth_certificate_proccess.php" method="post" >
<input type="hidden" value="<?php echo $token?>" name="token" id="token" /> 
<div id="f-wrap">
<div id="f-header">
<h3>Application for an England &amp; Wales Birth Certificate</h3>
<img src="images/birth-icon.png" alt="" class="birth-icon" /></div>
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
    <p>Below is an England and Wales Birth Certificate Application Form where full information is required.</p>
    <p>Required fields will be followed by the <em>*</em> character.</p>
    <p>Please complete the form and press the &quot;Continue&quot; button to add the application to your basket.</p>
    <?php if(isset($_GET['error'])){?>
    <p class="error">There was an error processing your details. Please check the details below</p>
    <?php }?>
    </div>
    <div class="container-1">
  <fieldset>
    	<legend>Particulars of the person whose certificate is required</legend>
        
      <div><label for="birth_year">Year Birth was Registered<em>*</em> 
      <?php if(isset($errors['birth_year'])){?>
     	<br /> <span class="error"><?php echo $errors['birth_year']?></span>
      <?php } ?> 
      </label>
      <input type="text" name="birth_year" id="birth_year" size="4" maxlength="4" value="<?php if(isset($_SESSION['birth_year'])) echo $_SESSION['birth_year']?>" /></div>
      
       <div><label for="birth_surname">Surname at Birth<em>*</em>
       <?php if(isset($errors['birth_surname'])){?>
     	<br /> <span class="error"><?php echo $errors['birth_surname']?></span>
      <?php } ?>
       </label>
      <input type="text" name="birth_surname" id="birth_surname" size="45" maxlength="45" value="<?php if(isset($_SESSION['birth_surname'])) echo $_SESSION['birth_surname']?>" /></div>
        
      <div><label for="birth_forename">Forename(s)<em>*</em>
      <?php if(isset($errors['birth_forename'])){?>
     	<br /> <span class="error"><?php echo $errors['birth_forename']?></span>
      <?php } ?>
       </label>
      <input type="text" name="birth_forename" id="birth_forename" size="45" maxlength="45" value="<?php if(isset($_SESSION['birth_forename'])) echo $_SESSION['birth_forename']?>" /></div>
        
      
        
       <div><label for="birth_place">Place of Birth<em style="display:none">*</em> 
       <?php if(isset($errors['birth_place'])){?>
     	<br /> <span class="error"><?php echo $errors['birth_place']?></span>
        <?php } ?>
        </label>
      <input type="text" name="birth_place" id="birth_place" size="45" maxlength="45" value="<?php if(isset($_SESSION['birth_place'])) echo $_SESSION['birth_place'] ?>" /></div>
      
        
    </fieldset>
    </div>
     <?php if(isset($_SESSION['GROI_known']) && $_SESSION['GROI_known']==1) {?> 
    <div class="container-1">
      <fieldset>
      <legend>Reference Information from GRO Index</legend>
        <div>
          <label for="GROI_year">Year
           <?php if(isset($errors['GROI_year'])){?>
     	<br /> <span class="error"><?php echo $errors['GROI_year']?></span>
        <?php } ?>
           </label>
        <input type="text" name="GROI_year" id="GROI_year" maxlength="4" size="4" value="<?php if(isset($_SESSION['GROI_year'])) echo $_SESSION['GROI_year'] ?>" />
      </div>
        <div>
          <label for="GROI_quarter">Quarter<em>*</em> 
             <?php if(isset($errors['GROI_quarter'])){?>
     	<br /> <span class="error"><?php echo $errors['GROI_quarter']?></span>
        <?php } ?>
          </label>
        <select name="GROI_quarter" id="GROI_quarter">
          <option value="">Select</option>
          <option value="1" <?php if(isset($_SESSION['GROI_quarter']) && $_SESSION['GROI_quarter']==1) echo "selected" ?>>1</option>
          <option value="2" <?php if(isset($_SESSION['GROI_quarter']) && $_SESSION['GROI_quarter']==2) echo "selected" ?>>2</option>
          <option value="3" <?php if(isset($_SESSION['GROI_quarter']) && $_SESSION['GROI_quarter']==3) echo "selected" ?>>3</option>
          <option value="4" <?php if(isset($_SESSION['GROI_quarter']) && $_SESSION['GROI_quarter']==4) echo "selected" ?>>4</option>
        </select>
      </div>
        <div>
          <label for="GROI_district">District Name<em>*</em>
              <?php if(isset($errors['GROI_district'])){?>
     	<br /> <span class="error"><?php echo $errors['GROI_district']?></span>
        <?php } ?>
           </label>
        <input type="text" name="GROI_district" id="GROI_district" size="45" maxlength="45" value="<?php if(isset($_SESSION['GROI_district'])) echo $_SESSION['GROI_district'] ?>"/>
      </div>
        <div>
          <label for="GROI_volume_number">Volume Number<em>*</em>
               <?php if(isset($errors['GROI_volume_number'])){?>
     	<br /> <span class="error"><?php echo $errors['GROI_volume_number']?></span>
        <?php } ?>
           </label>
        <input type="text" name="GROI_volume_number" id="GROI_volume_number" size="5" maxlength="5" value="<?php if(isset($_SESSION['GROI_volume_number'])) echo $_SESSION['GROI_volume_number'] ?>" />
      </div>
        <div>
          <label for="GROI_page_number">Page Number<em>*</em>
                <?php if(isset($errors['GROI_page_number'])){?>
     	<br /> <span class="error"><?php echo $errors['GROI_page_number']?></span>
        <?php } ?>
           </label>
        <input type="text" name="GROI_page_number" id="GROI_page_number" size="4" maxlength="4" value="<?php if(isset($_SESSION['GROI_page_number'])) echo $_SESSION['GROI_page_number'] ?>"/>
      </div>
        <div>
          <label for="GROI_reg">Reg (month &amp; year of registration, mm/yyyy)<em style="display:none">*</em>
                 <?php if(isset($errors['GROI_reg_month'])){?>
     	<br /> <span class="error"><?php echo $errors['GROI_reg_month']?></span>
        <?php } ?>
               <?php if(isset($errors['GROI_reg_year'])){?>
     	<br /> <span class="error"><?php echo $errors['GROI_reg_year']?></span>
        <?php } ?>
          </label>
        <input type="text" name="GROI_reg_month" id="GROI_reg_month" maxlength="2" size="2" value="<?php if(isset($_SESSION['GROI_reg_month'])) echo $_SESSION['GROI_reg_month'] ?>" /><span style="float:left">/</span>
        <input type="text" name="GROI_reg_year" id="GROI_reg_year" maxlength="4" size="4" value="<?php if(isset($_SESSION['GROI_reg_year'])) echo $_SESSION['GROI_reg_year'] ?>" />
      </div>
        </fieldset>
    </div>
    <?php
	}
    ?>
    <div class="container-1">
      <fieldset>
      <legend>Number of Certificates</legend>
        <div>
        <label for="no_of_certs">Number of Certificates<em>*</em>
                <?php if(isset($errors['no_of_certs'])){?>
     	<br /> <span class="error"><?php echo $errors['no_of_certs']?></span>
        <?php } ?>
        </label>
        <input name="no_of_certs" type="text" id="no_of_certs" value="<?php if(isset($_SESSION['no_of_certs'])) echo $_SESSION['no_of_certs']; else echo "1"?>" maxlength="3" size="3"  />
        <p class="note">Additional copies will be charged at &pound;10.00 each.</p>
      </div>
      </fieldset>
    </div>
    <div class="container-2">
     
     <fieldset style="display:none;">
      <legend>Delivery Service</legend>
        <div>
        <label for="Standard">Standard £10.00 (7 working days)</label>
        <input name="delivery_method" type="radio" class="checkbox" id="Standard" value="radio" checked="checked" />
      </div>
        <div>
        <label for="Standard">Express £25.00 (2 working days)</label>
        <input type="radio" name="radio" id="Express" value="radio" class="checkbox" />
      </div>
      </fieldset>
    
     </div>
    <div class="buttons"> 
    <input type="image" src="images/continue_GRO.gif" alt="submit" />
     </div>
    <?php include ("footnote.php");?>
</div>
<!-- end f-content-->

</div>
<!-- end f-wrap-->
</form>
<?php
include ('include/footer.php');
?>