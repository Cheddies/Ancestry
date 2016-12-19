<?php 
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include('../ppc/include/ppc_init.inc.php');
include('./siteconstants.php');
include('./btw_switch.php');

if ($_GET['s'] == "y") {
	$error = 0;
	if (!($_POST['first_name'])) {
	$error = 1;
	$err_first_name = 1; 
	$msg_first_name = "Please enter your first name";
	} 
	if (!($_POST['last_name'])) {
	$error = 1;
	$err_last_name = 1; 
	$msg_last_name = "Please enter your last name";
	} 
	if (!($_POST['tel'])) {
	$error = 1;
	$err_tel = 1; 
	$msg_tel = "Please enter your telephone number";
	} 
	if (!($_POST['email'])) {
	$error = 1;
	$err_email = 1; 
	$msg_email = "Please enter your email address";
	} 
	if (!($_POST['street'])) {
	$error = 1;
	$err_street = 1; 
	$msg_street = "Please enter your street address";
	} 
	if (!($_POST['town'])) {
	$error = 1;
	$err_town = 1; 
	$msg_town = "Please enter your town";
	} 
	if (!($_POST['city'])) {
	$error = 1;
	$err_city = 1; 
	$msg_city = "Please enter your city";
	} 
	if (!($_POST['postcode'])) {
	$error = 1;
	$err_postcode = 1; 
	$msg_postcode = "Please enter your post code";
	} 
		if (!($_POST['terms'])) {
	$error = 1;
	$err_terms = 1; 
	$msg_terms = "You must accept our terms to proceed";
	} 

if (!$error) {

$qquery="INSERT INTO bts_entrants (
		e_first_name,
		e_last_name,
		e_memno,
		e_tel,
		e_email,
		e_add_street,
		e_add_town,
		e_add_city,
		e_add_postcode,
		e_stage
) VALUES (
		'".mysql_real_escape_string($_POST['first_name'])."',
		'".mysql_real_escape_string($_POST['last_name'])."',
		'".mysql_real_escape_string($_POST['member'])."',
		'".mysql_real_escape_string($_POST['tel'])."',
		'".mysql_real_escape_string($_POST['email'])."',
		'".mysql_real_escape_string($_POST['street'])."',
		'".mysql_real_escape_string($_POST['town'])."',
		'".mysql_real_escape_string($_POST['city'])."',
		'".mysql_real_escape_string($_POST['postcode'])."',
		'".$camp_no."'
)";

//echo $qquery."<br>";
$qresult = mysql_query($qquery);
//echo $qquery;
$successmsg = "<div class=\"btssuccess\">Thank you for entering the Back to School prize draw.  <a href=\"http://www.ancestry.co.uk/back-to-school\">Click here</a> to return to the back to school timetable</div>";
}
}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$camp_name;?> Promotion</title>
<meta name="description" content="Family Tree Maker&trade; software. Save 30% on the No.1 selling family history software" />
<meta name="keywords" content="Family Tree Maker, Ancestry shop, family history software, family history, family research" />
<link rel="stylesheet" href="<?php echo WWW_ROOT;?>css/landing.css"/>
<link href="<?php echo WWW_ROOT;?>css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo WWW_ROOT;?>script/edgeward/jquery.fancybox-1.2.1.pack.js"></script>
<script type="text/javascript" src="<?php //echo WWW_ROOT;?>script/edgeward/ppc.js"></script>
<style>
input[type=text] {
width: 95%;
padding: 3px;
font-size: 14px;
}
#content_wrapper.std_page {
background-image: url('<?php echo WWW_ROOT;?>landing/<?=$camp_head;?>.jpg');
}
input.img_btn {
width: 89px;
height: 28px;
line-height: 28px;
padding-bottom: 2px;
background: url(../images/edgeward/continue_btn.png) no-repeat;
border: 0;
font-weight: bold;
color: white;
}
h1#logo {
background-image: url('<?php echo WWW_ROOT;?>landing/ances_logo.gif');
}
.btssuccess {
padding: 5px;
background-color: #ddffd2;
border: 1px solid #6cb854;
color: #6cb854;
font-size: 12px;
font-weight: bold;
}
.btserror {
font-size: 12px;
font-weight: bold;
line-height: 18px;
color: #cd0000;
}
</style>


</head>

<body>
	<div id="container">
		<h1 id="logo"><?=$camp_name;?> Promotion</h1>
		<div id="content_wrapper" class="std_page">
				
			<div id="intro_content">
							
				<h2 class="main_title">Back to school prize draw<br />
                <span style="font-size: 0.8em;">Prize draw <?=$camp_no;?></span></h2>
				
				<p><span style="font-size:18px; color: #695e49; font-weight:bold;"><?=$camp_title;?></span><br />
                 <span style="color: #695e49; font-weight:bold;"><?=$camp_subtitle;?></span>
                 <?=$camp_desc;?>
				<p><strong>Closing date <?=$camp_closing;?></strong>
							
			</div>
			<!--<a name="tabs"/>-->
			<div id="main_content">
				<div class="tab_top"></div>
				
				<!-- tab 1-->
				<div id="tab_1" class="tab">					
						 
					<div class="cert_box">
                    <?=$successmsg;?>
                    <form action="./backtoschool<?=$camp_no;?>?s=y" method="post">
                    	<div style="width: 45%; float:left;">
                        <p>First name  <strong>*</strong><br />
                        <input name="first_name" id="first_name" type="text" value="<?=$_POST['first_name'];?>" />
                        <? if ($err_first_name != 0) { echo "<br /><span class=\"btserror\">".$msg_first_name."</span>"; } ?>
                        </p>
                        <p>Membership No<br />
                        <input name="member" id="member" type="text" />
                        </p>
                        <p>Telephone <strong>*</strong><br />
                        <input name="tel" id="tel" type="text" value="<?=$_POST['tel'];?>" />
                        <? if ($err_tel != 0) { echo "<br /><span class=\"btserror\">".$msg_tel."</span>"; } ?>
                        </p>
                        <p>Town <strong>*</strong><br />
                        <input name="town" id="town" type="text" value="<?=$_POST['town'];?>" />
                        <? if ($err_town != 0) { echo "<br /><span class=\"btserror\">".$msg_town."</span>"; } ?>
                        </p>
                        <p>Postcode <strong>*</strong><br />
                        <input name="postcode" id="postcode" type="text" value="<?=$_POST['postcode'];?>" />
                        <? if ($err_postcode != 0) { echo "<br /><span class=\"btserror\">".$msg_postcode."</span>"; } ?>
                        </p>
                        
                        </div>
                        <div style="width: 45%; float: right;">
                        
                        <p>Surname <strong>*</strong><br />
                        <input name="last_name" id="last_name" type="text" value="<?=$_POST['last_name'];?>" />
                        <? if ($err_last_name != 0) { echo "<br /><span class=\"btserror\">".$msg_last_name."</span>"; } ?>
                        </p>
                        <p>Email <strong>*</strong><br />
                        <input name="email" id="email" type="text" value="<?=$_POST['email'];?>" />
                        <? if ($err_email != 0) { echo "<br /><span class=\"btserror\">".$msg_email."</span>"; } ?>
                        </p>
                        <p>Street Name <strong>*</strong><br />
                        <input name="street" id="street" type="text" value="<?=$_POST['street'];?>" />
                        <? if ($err_street != 0) { echo "<br /><span class=\"btserror\">".$msg_street."</span>"; } ?>
                        </p>
                        <p>City <strong>*</strong><br />
                        <input name="city" id="city" type="text" value="<?=$_POST['city'];?>" />
                        <? if ($err_city != 0) { echo "<br /><span class=\"btserror\">".$msg_city."</span>"; } ?>
                        </p>
                        <p><input id="terms" name="terms" type="checkbox" /> I accept the competition terms and conditions <strong>*</strong>
                        <? if ($err_terms != 0) { echo "<br /><span class=\"btserror\">".$msg_terms."</span>"; } ?>
                        </p>
                        </div>
                        <div style="clear:both;"></div>
                        <input type="submit" value="Enter" class="img_btn" />
                        <p><small><strong>* required</strong></small>
                        <? if ($err_terms != 0) { echo "<br /><span class=\"btserror\">".$msg_terms."</span>"; } ?>
                        </p>
  						</form>
                    
                    
                    </div>
                 <p class="terms"><strong>Terms and conditions</strong><br/>
<?=$camp_terms;?>
</p>   
                    
				</div><!-- end #tab_1-->
				
				<!-- tab 2-->
								
				
			</div><!--end #main_content-->
			<?php //include('../ppc/include/footer.inc.php');?>
		</div><!--end #content_wrapper-->
		
	</div><!--end #container-->
	<?php include('../include/analytics.inc.php');?>
</body>
</html>
