<?php
//check to see if the page is accessed via HTTPS
if  ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') || ($_SERVER['HTTP_HOST']=="10.0.0.3" ) || ($_SERVER['HTTP_HOST']=="212.38.95.165") || ($_SERVER['HTTP_HOST']=="10.0.0.136" ) || ($_SERVER['HTTP_HOST']=="78.31.106.192" )) 
{

include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");
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
<div id="f-wrap">
<div id="f-header">
<h3>Certificate choice</h3>
<img src="images/tree-icon.jpg" alt="" class="tree-icon" />
</div>
<!-- end f-header-->

<form action="proccess_cert_choice.php" method="post"/>
<input type="hidden" value="<?php echo $token?>" name="token" id="token" /> 
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
    <p>Please select a certificate type and provide any additional information requested.  Following this, you will be taken to the appropriate application form to complete.</p>
    <?php
    if(isset($_GET['error']))
    {
    ?>
	    <p>
	    <span class="error">There was an error proccessing your details. Please check below for details.</span>
	    </p>
    <?php
	}
    ?>
    </div>
    
    <div class="container-2">
      <fieldset>
      <legend>Certificate Types</legend>
      <p class="intro">For events registered in England and Wales</p>
      <?php if(isset($errors['cert_choice'])){?>
     	<br /> <span class="error"><?php echo $errors['cert_choice']?></span>
        <?php } ?>   
         <div>
          <label for="cert_choice">1.  Birth Certificate</label>
          <input name="cert_choice" type="radio"  id="cert_choice" value="1" class="checkbox" <?php if(isset($_SESSION['cert_choice']) && $_SESSION['cert_choice']==BIRTH) echo "checked"?> />
        </div>
      <div>
          <label for="l">2.  Marriage Certificate <em></em> </label>
          <input name="cert_choice" type="radio"  id="cert_choice" value="2" class="checkbox" <?php if(isset($_SESSION['cert_choice']) && $_SESSION['cert_choice']==MARRIAGE) echo "checked"?> />
      </div>
      <div>
          <label for="l">3.  Death Certificate<em></em> </label>
          <input name="cert_choice" type="radio"  id="cert_choice" value="3" class="checkbox" <?php if(isset($_SESSION['cert_choice']) && $_SESSION['cert_choice']==DEATH) echo "checked"?> />
          <div class="inside">
         
            <label for="death_age">Age at death in years</label>
            <input type="text" size="3" maxlength="3" name="death_age" id="death_age" value="<?php if(isset($_SESSION['death_age'])) echo $_SESSION['death_age']?>" />
         	
           <?php if(isset($_GET['death_age'])){?>
          <p class="error">Please enter a valid age </p>
          <?php
  	      } 
          ?>
            </div>
            <p class="note">
            Age at death must be given for applications where event was registered in last 50 years.
            </p>
        </div>
      <div>
       </div>
      </fieldset>
    </div>
    <div class="container-2 ">
      <fieldset>
        <div> <?php if(isset($errors['GROI_known'])){?>
     	<br /> <span class="error"><?php echo $errors['GROI_known']?></span>
        <?php } ?>   
        <p class="question">Is the General Register Office Index known? (<a target="_new" href="explanation.php">Explanation</a>) </p>
       
        <div class="control-set">
          <input name="GROI_known" type="radio"  id="GROI_known" value="1" class="checkbox" <?php if(isset($_SESSION['GROI_known']) && $_SESSION['GROI_known']==1) echo "checked"?>  />
          <label for="">Yes</label>
          <input name="GROI_known" type="radio"  id="GROI_known" value="0" class="checkbox" <?php if(isset($_SESSION['GROI_known']) && $_SESSION['GROI_known']==0) echo "checked"?>  />
          <label for="">No</label>
        </div>
        </div>
        <div class="container-1">
        <?php if(isset($errors['GROI_reg_month'])){?>
     	<br /> <span class="error"><?php echo $errors['GROI_reg_month']?></span>
        <?php } ?>  
        <?php if(isset($errors['GROI_reg_year'])){?>
     	<br /> <span class="error"><?php echo $errors['GROI_reg_year']?></span>
        <?php } ?>    
        	<div>
          		<label for="GROI_reg_year">Date of Entry (mm/yyyy)<em></em> </label>
          		
          		<input type="text" size="2" maxlength="2" name="GROI_reg_month" id="GROI_reg_month" value="<?php if(isset($_SESSION['GROI_reg_month'])) echo $_SESSION['GROI_reg_month']?>" /><span style="float:left">/</span><input type="text" size="4" maxlength="4" name="GROI_reg_year" id="GROI_reg_year" value="<?php if(isset($_SESSION['GROI_reg_year'])) echo $_SESSION['GROI_reg_year']?>" /> 
        	</div>
		<p class="note">
						Applications for events registered within the last 18 months cannot be made via this site.</p>
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
<?php
include ('include/footer.php');

}
else
{
	$page = $_SERVER['PHP_SELF'];
	$page = str_replace(" ","%20",$page);
	$page =  "https://" . $_SERVER['HTTP_HOST'] . $page ;	
	$page = "Location:" . $page;
	header($page);
}
?>