<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");
include ('include/header.php');

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);

?>
<div id="admin_page">

<div id="f-wrap">
<div id="f-header">
<h3>Order Search</h3>
<img src="images/tree-icon.jpg" alt="" class="tree-icon">
</div>
<!-- end f-header-->
<form action="proccess_cert_choice.php" method="post"/><form action="process_search.php" method="post">
<input type="hidden" value="<?php echo $token?>" name="token" id="token" /> 
<div id="f-content">
	<div class="container-2">
		<fieldset>
			<legend>Certificate Types</legend>
				<div>
          			<label for="order_number">Order Number:</label>
          			<input type="text" name="order_number" id="order_number" value="" />
          		</div>
          		<div>
          			<label for="email">Email:</label>
          			<input type="text" name="email" id="email" value="" />
          		</div>
          		<div>
          			<label for="order_date">Order Date</label>
          			<input type="text" name="order_date" id="order_date" value="" />
          		</div>
          		<div>
          			<label for="st_ref">Secure Trading Reference</label>
          			<input type="text" name="st_ref" id="st_ref" value="" />
          		</div>
          		<div>
          			<label for="Status">Status</label>
          			<select name="status" id="status">
          				<option value="0">Any</option>
          				<option value="1"><?php echo descriptive_status("1")?></option>
          				<option value="2"><?php echo descriptive_status("2")?></option>
          				<option value="3"><?php echo descriptive_status("3")?></option>
          			</select>
          		</div>
	  	</fieldset>	
	</div>
</div>

</form>
<p>
</p>
</div>

<?php
include ('include/footer.php');
?>