<?php
include_once("include/siteconstants.php");
include_once("include/commonfunctions.php");

session_set_cookie_params ( 0,"/." , "", true);

//check the form was submitted and data proccesssed
if(isset($_SESSION['BMD_contact_complete']))
{
	//clear the form details in case they try to resubmit
	unset($_SESSION['contact_title']);
	unset($_SESSION['contact_firstname']);
	unset($_SESSION['contact_surname']);
	unset($_SESSION['contact_email']);
	unset($_SESSION['contact_country']);
}
else
{
	//not completed
	//return to the inital form
	header('location:BMD_contact.php');
	exit();
}

include ('include/header.php');	
?>
<div id="f-wrap">
<div id="f-header">
<h3>Thank You</h3>
<img src="images/tree-icon.jpg" alt="" class="tree-icon" /></div>
<!-- end f-header-->

<div id="f-content">
<div class="breadcrumb">
	<ul>
	</ul>
</div>
<div class="intro">
	<p>Your details have been stored and we will contact you once the new international service is available.</p>
</div>
</div>
<!-- end f-content-->

</div>
<!-- end f-wrap-->
<?php include ('include/footer.php');?>