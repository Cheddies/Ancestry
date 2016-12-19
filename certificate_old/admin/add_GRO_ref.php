<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");


session_set_cookie_params ( 0,"/." , "", true);
if( ( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
||(isset($_GET['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_GET['token'],$_SESSION['token'],$_SESSION['token_time'],0))
)
{

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();
//set different characters to remove from email field
//as . and - are allowed
$email_remove=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");#
	
$form_fields=array(	array ("name" => "order_id","display_name" => "order number","length"=>"10","reg_ex"=>"","required"=>true)
					);

$errors = process_form($form_fields,$_GET,&$clean);

if(sizeof($errors)>0)
{
	$_SESSION['errors']=serialize($errors);
	header('location:index.php');
	exit();
}
else
{
	if(isset($_SESSION['errors']))
	{
		$errors=unserialize($_SESSION['errors']);
	}
	include('include/header.php');
?>
<div id="admin_page">
<div id="f-wrap">
<div id="f-header">
<h3>
Mark As Sent To GRO - Order Number <?php echo $clean['order_id']?>
</h3>
<img src="images/tree-icon.jpg" alt="" class="tree-icon">
</div>
</div>

<div id="order_details">


<div id="f-content">
	<form action="process_add_GRO_ref.php" method="post">
		<input type="hidden" name="token" id="token" value="<?php echo $token?>" />
		<input type="hidden" name="order_id" id="order_id" value="<?php echo $clean['order_id']?>" />
		<label for="GRO_ref">GRO Reference</label> <input type="text" id="GRO_ref" name="GRO_ref" value="" /> 
		<input type="submit" value="Update" />
		<?php if(isset($errors['GRO_ref']))
		{
		?>
		<br />
		<span class="error"><?php echo $errors['GRO_ref']?></span>
		<?php
		}
		?>
	</form>
</div>
</div>
<?php
include('include/footer.php');
}

}
else
{
	header('location:index.php');
	exit();
}

?>