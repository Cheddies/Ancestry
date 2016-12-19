<?php
require_once('include/edgeward/process_bmd.inc.php');
require_once('include/edgeward/new_bmd/header.inc.php'); 

?>

<div id="content_top"></div>
<div id="content_wrapper">

<h2>Certificate Year</h2>
<form action="upd_year.php" method="post" class="shop_form">
    <input type="hidden" name="token" value="<?php echo $token;?>"/>
    <p>Please enter the year of the <?php echo strtolower($certTypes[$recordType]);?>, the year determines the information we require to fulfill your order.</p>
    <?php echo formInput('text','GROI_year','GROI_year','Year of '.strtolower($certTypes[$recordType]).' (yyyy)',$order['GRO_year'],array('attr'=>array('class'=>'gro_field','maxlength'=>4,'size'=>5),'required'=>true,'error'=>validationError('GROI_year')));?>
    <div class="edit_btn_wrapper">
        <input type="submit" value="Continue" class="noval img_btn"/>
    </div>
</form>
<h3 class="info">Certificate Availability</h3>
<div class="info_box"> 
<p>Please be advised that we can only supply Births, Deaths and Marriage certificates from England and Wales. Certificates can be obtained from 1837 to 18 months prior to the present date.</p>
</div><!--end .info_box-->
<div class="info_box_btm"></div>
</div><!--end #content_wrapper-->
<div id="page_footer"></div>

<?php require_once('include/footer.php'); ?>

