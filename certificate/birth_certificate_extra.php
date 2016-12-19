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
<form action="birth_certificate_extra_proccess.php" method="post" >
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
    <p>Additional information is required.</p>
    <p>Required fields will be followed by the <em>*</em> character.</p>
    <p>Please complete the form and press the &quot;Continue&quot; button to add the application to your basket.</p>
    <?php if(isset($_GET['error'])){?>
    <p class="error">There was an error processing your details. Please check the details below</p>
    <?php }?>
    </div>
    <div class="container-1">
  <fieldset>
    	<legend>Particulars of the person whose certificate is required</legend>
        
        <div><label for="dob">Date of birth (dd/mm/yyyy)<em>*</em> 
        <?php if(isset($errors['dob_day'])){?>
     	<br /> <span class="error"><?php echo $errors['dob_day']?></span>
        <?php } ?>
        <?php if(isset($errors['dob_month'])){?>
     	<br /> <span class="error"><?php echo $errors['dob_month']?></span>
        <?php } ?>
        <?php if(isset($errors['dob_year'])){?>
     	<br /> <span class="error"><?php echo $errors['dob_year']?></span>
        <?php } ?>
        </label>
      	<input size="2" maxlength="2" type="text" name="dob_day" id="dob_day" value="<?php if(isset($_SESSION['dob_day'])) echo $_SESSION['dob_day']?>" /><span style="float:left">/</span>
      	<input size="2" maxlength="2" type="text" name="dob_month" id="dob_month" value="<?php if(isset($_SESSION['dob_month'])) echo $_SESSION['dob_month']?>" /><span style="float:left">/</span>
      	<input size="4" maxlength="4" type="text" name="dob_year" id="dob_year" value="<?php if(isset($_SESSION['dob_year'])) echo $_SESSION['dob_year']?>" />
      	</div>
      	
      	<div><label for="birth_place">Place of Birth<em>*</em> 
       <?php if(isset($errors['birth_place'])){?>
     	<br /> <span class="error"><?php echo $errors['birth_place']?></span>
        <?php } ?>
        </label>
      <input type="text" name="birth_place" id="birth_place" size="45" maxlength="45" value="<?php if(isset($_SESSION['birth_place'])) echo $_SESSION['birth_place'] ?>" /></div>
 
        <div><label for="fathers_surname">Father's Surname<em>*</em>
         <?php if(isset($errors['fathers_surname'])){?>
     	<br /> <span class="error"><?php echo $errors['fathers_surname']?></span>
        <?php } ?>
        </label>
      <input type="text" name="fathers_surname" id="fathers_surname" size="45" maxlength="45" value="<?php if(isset($_SESSION['fathers_surname'])) echo $_SESSION['fathers_surname'] ?>" /></div>
        
        <div><label for="fathers_forename">Father's Forename(s)<em>*</em> 
         <?php if(isset($errors['fathers_forename'])){?>
     	<br /> <span class="error"><?php echo $errors['fathers_forename']?></span>
        <?php } ?>
        </label>
      <input type="text" name="fathers_forename" id="fathers_forename" size="45" maxlength="45" value="<?php if(isset($_SESSION['fathers_forename'])) echo $_SESSION['fathers_forename'] ?>" /></div>
        
        <div><label for="mothers_maiden_surname">Mother's Maiden Surname<em>*</em>
        <?php if(isset($errors['mothers_maiden_surname'])){?>
     	<br /> <span class="error"><?php echo $errors['mothers_maiden_surname']?></span>
        <?php } ?>
         </label>
      <input type="text" name="mothers_maiden_surname" id="mothers_maiden_surname" size="45" maxlength="45" value="<?php if(isset($_SESSION['mothers_maiden_surname'])) echo $_SESSION['mothers_maiden_surname'] ?>" /></div>
        
        <div>
          <label for="mothers_surname">Mother's Surname at Time of the Birth
           <?php if(isset($errors['mothers_surname'])){?>
     	<br /> <span class="error"><?php echo $errors['mothers_surname']?></span>
        <?php } ?>
           </label>
      <input type="text" name="mothers_surname" id="mothers_surname" size="45" maxlength="45" value="<?php if(isset($_SESSION['mothers_surname'])) echo $_SESSION['mothers_surname'] ?>" /></div>
        
         <div><label for="mothers_forename">Mother's Forename(s)<em>*</em>
            <?php if(isset($errors['mothers_forename'])){?>
     	<br /> <span class="error"><?php echo $errors['mothers_forename']?></span>
        <?php } ?> </label>
      <input type="text" name="mothers_forename" id="mothers_forename" size="45" maxlength="45" value="<?php if(isset($_SESSION['mothers_forename'])) echo $_SESSION['mothers_forename'] ?>" /></div>
        
      
        
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