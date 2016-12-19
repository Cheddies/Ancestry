<?php
include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");
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
<form action="marriage_certificate_proccess.php" method="post" >
<input type="hidden" value="<?php echo $token?>" name="token" id="token" /> 
<div id="f-wrap">
<div id="f-header">
<h3>Application for an England &amp; Wales Marriage Certificate</h3>
<img src="images/marriage-icon.png" alt="" class="marriage-icon" /></div>
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
    <p>Below is an England and Wales Marriage Certificate Application Form where full information is required.</p>
    <p>Required fields will be followed by the <em>*</em> character.</p>
    <p>Please complete the form and press the &quot;Continue&quot; button to add the application to your basket.</p>
    </div>
    <div class="container-1">
  <fieldset>
    	<legend>Particulars of the person whose certificate is required</legend>
        
      <div><label for="registered_year">Year Marriage was Registered<em>*</em> 
      <?php if(isset($errors['registered_year'])){?>
     	<br /> <span class="error"><?php echo $errors['registered_year']?></span>
        <?php } ?>   
      </label>
      <input type="text" size="4" maxlength="4" name="registered_year" id="registered_year" value="<?php if(isset($_SESSION['registered_year'])) echo $_SESSION['registered_year'] ?>"/></div>
        
        <div><label for="mans_surname">Man's Surname<em style="display:none">*</em>
        <?php if(isset($errors['mans_surname'])){?>
     	<br /> <span class="error"><?php echo $errors['mans_surname']?></span>
        <?php } ?>    </label>
      <input type="text" size="45" maxlength="45" name="mans_surname" id="mans_surname" value="<?php if(isset($_SESSION['mans_surname'])) echo $_SESSION['mans_surname'] ?>" /></div>
        
        <div><label for="mans_forenames">Man's Forename(s)<em style="display:none">*</em>
        <?php if(isset($errors['mans_forenames'])){?>
     	<br /> <span class="error"><?php echo $errors['mans_forenames']?></span>
        <?php } ?>    </label>
      <input type="text" size="45" maxlength="45" name="mans_forenames" id="mans_forenames" value="<?php if(isset($_SESSION['mans_forenames'])) echo $_SESSION['mans_forenames'] ?>" /></div>
        
        <div><label for="womans_surname">Woman's Surname at Marriage<em style="display:none">*</em> 
        <?php if(isset($errors['womans_surname'])){?>
     	<br /> <span class="error"><?php echo $errors['womans_surname']?></span>
        <?php } ?>   </label>
      <input type="text" size="45" maxlength="45" name="womans_surname" id="womans_surname" value="<?php if(isset($_SESSION['womans_surname'])) echo $_SESSION['womans_surname'] ?>" /></div>
        
        <div><label for="womans_forenames">Woman's Forename (s)<em style="display:none">*</em>
        <?php if(isset($errors['womans_forenames'])){?>
     	<br /> <span class="error"><?php echo $errors['womans_forenames']?></span>
        <?php } ?>   </label>
      <input type="text" size="45" maxlength="45" name="womans_forenames" id="womans_forenames" value="<?php if(isset($_SESSION['womans_forenames'])) echo $_SESSION['womans_forenames'] ?>" /></div>
        
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
	}//end of check for GROI known
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