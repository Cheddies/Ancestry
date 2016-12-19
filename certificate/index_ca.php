<?php
require_once('include/edgeward/global_functions.inc.php');

include_once('include/check_https.inc.php');

?>
<?php
//get new session id
if(isset($_SESSION['cert'])){
	session_regenerate_id(true);
}
//dont include JS
$no_js = 1;
$omniture_pageTitle = "Ancestry Shop Certificates";
?>
<?php require_once('include/edgeward/header.inc.php'); ?>

<div id="content_top"></div>
<div id="content_wrapper">
<div class="border_top"></div>
<div class="fieldset_border">

<p><strong>Choose the Certificate you require</strong></p><br/>

<p><a href="get_data.php?formType=shop&recordType=b&country=5543">England and Wales Birth Certificate</a></p>
<p><a href="get_data.php?formType=shop&recordType=m&country=5543">England and Wales Marriage Certificate</a></p>
<p><a href="get_data.php?formType=shop&recordType=d&country=5543">England and Wales Death Certificate</a></p>
<br/>
<p>Please be advised that we can only supply Births, Deaths and Marriage certificates from England and Wales. Certificates can be obtained from 1837 to 18 months prior to the present date.</p>
</div><!--end .fieldset_border-->
<div class="border_bottom"></div>

</div><!--end #content_wrapper-->
<div id="page_footer"></div>

<?php require_once('include/footer.php'); ?>

