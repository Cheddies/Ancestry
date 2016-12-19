<?php
//clicktale include must be at top of page
/*if($_SERVER['HTTP_HOST']== 'www.ancestryshop.co.uk')*/ require_once("ClickTale/ClickTaleTop.php");
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
<?php 
//if($layout['set'] >1){
if(1 == 1){
	require_once('include/edgeward/new_bmd/header.inc.php'); 
} else {
	require_once('include/edgeward/header.inc.php'); 
}

?>

<div id="content_top"></div>
<div id="content_wrapper" class="layout<?php echo $layout['set'];?>">
<?php 
//Edgeward change, keep to new layout on index page to avoid probs with non UK forms
//if($layout['set'] == 1):
if(1 != 1):
?>
	<div class="border_top"></div>
	<div class="fieldset_border">
	
	<p><strong>Choose the Certificate you require</strong></p><br/>
	<ul id="cert_choice">
		<li><a href="get_data.php?formType=shop&recordType=b">England and Wales Birth Certificate</a></li>
		<li><a href="get_data.php?formType=shop&recordType=m">England and Wales Marriage Certificate</a></li>
		<li><a href="get_data.php?formType=shop&recordType=d">England and Wales Death Certificate</a></li>
	</ul>
	<?php /** testing only
	<p><a href="get_data.php?formType=shop&recordType=b&layout=1">England and Wales Birth Certificate</a></p>
	<p><a href="get_data.php?formType=shop&recordType=m&layout=1">England and Wales Marriage Certificate</a></p>
	<p><a href="get_data.php?formType=shop&recordType=d&layout=1">England and Wales Death Certificate</a></p>
	
	<br/>
	<p><a href="get_data.php?formType=shop&recordType=b&layout=2">England and Wales Birth Certificate (layout 2)</a></p>
	<p><a href="get_data.php?formType=shop&recordType=m&layout=2">England and Wales Marriage Certificate (layout 2)</a></p>
	<p><a href="get_data.php?formType=shop&recordType=d&layout=2">England and Wales Death Certificate (layout 2)</a></p>
	<br/>
	<p><a href="get_data.php?formType=shop&recordType=b&layout=3">England and Wales Birth Certificate (layout 3)</a></p>
	<p><a href="get_data.php?formType=shop&recordType=m&layout=3">England and Wales Marriage Certificate (layout 3)</a></p>
	<p><a href="get_data.php?formType=shop&recordType=d&layout=3">England and Wales Death Certificate (layout 3)</a></p>	
	*****/
	?>
	<br/>
	<p>Please be advised that we can only supply Births, Deaths and Marriage certificates from England and Wales. Certificates can be obtained from 1837 to 18 months prior to the present date.</p>
	</div><!--end .fieldset_border-->
	<div class="border_bottom"></div>
<?php 
//elseif($layout['set'] >= 2):
elseif(1 == 1):
?>

	<label for="test">Test field</label><input id="test" type="text"></input>
	<h2>Choose the Certificate you require</h2>
	<ul id="cert_choice">
		<li><a href="get_data.php?formType=shop&recordType=b">England and Wales Birth Certificate</a></li>
		<li><a href="get_data.php?formType=shop&recordType=m">England and Wales Marriage Certificate</a></li>
		<li><a href="get_data.php?formType=shop&recordType=d">England and Wales Death Certificate</a></li>
	</ul>
	<?php /** testing only
	<p><a href="get_data.php?formType=shop&recordType=b&layout=1">England and Wales Birth Certificate</a></p>
	<p><a href="get_data.php?formType=shop&recordType=m&layout=1">England and Wales Marriage Certificate</a></p>
	<p><a href="get_data.php?formType=shop&recordType=d&layout=1">England and Wales Death Certificate</a></p>
	
	<br/>
	<p><a href="get_data.php?formType=shop&recordType=b&layout=2">England and Wales Birth Certificate (layout 2)</a></p>
	<p><a href="get_data.php?formType=shop&recordType=m&layout=2">England and Wales Marriage Certificate (layout 2)</a></p>
	<p><a href="get_data.php?formType=shop&recordType=d&layout=2">England and Wales Death Certificate (layout 2)</a></p>
	<br/>
	<p><a href="get_data.php?formType=shop&recordType=b&layout=3">England and Wales Birth Certificate (layout 3)</a></p>
	<p><a href="get_data.php?formType=shop&recordType=m&layout=3">England and Wales Marriage Certificate (layout 3)</a></p>
	<p><a href="get_data.php?formType=shop&recordType=d&layout=3">England and Wales Death Certificate (layout 3)</a></p>
	*****/
	?>
	<h3 class="info">Certificate Availability</h3>
	<div class="info_box"> 
	<p>Please be advised that we can only supply Births, Deaths and Marriage certificates from England and Wales. Certificates can be obtained from 1837 to 18 months prior to the present date.</p>
	</div><!--end .info_box-->
	<div class="info_box_btm"></div>
	

<?php endif;?>
</div><!--end #content_wrapper-->

<div id="page_footer"></div>

<?php require_once('include/footer.php'); 
/*if($_SERVER['HTTP_HOST']== 'www.ancestryshop.co.uk')*/ require_once("ClickTale/ClickTaleBottom.php");
?>
