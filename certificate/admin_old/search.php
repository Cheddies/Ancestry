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
<form action="process_search.php" method="post">
<input type="hidden" value="<?php echo $token?>" name="token" id="token" /> 
<div id="f-content">
	<div class="container-2">
		<fieldset>
			<legend>Search Options</legend>
				<div>
          			<label for="order_number">Order Number:</label>
          			<input type="text" name="order_number" id="order_number" value="" />
          		</div>
          		<div>
          			<label for="firstname">Firstname</label>
          			<input type="text" name="firstname" id="firstname" value="" />
          		</div>
          		<div>
          			<label for="surname">Surname</label>
          			<input type="text" name="surname" id="surname" value="" />
          		</div>
          		<div>
          			<label for="order_number">GRO Reference:</label>
          			<input type="text" name="GRO_ref" id="GRO_ref" value="" />
          		</div>
          		<div>
          			<label for="GROI_year">GRO Index Registered Year</label>
          			<input type="text" name="GROI_year" id="GRO_year" value="" size="4" maxlength="4" />
          		</div>
          		<div>
          			<label for="email">Email:</label>
          			<input type="text" name="email" id="email" value="" />
          		</div>
          		<div style="display:none;">
          			<label for="order_date">Order Date</label>
          			<input type="text" name="order_date_day" id="order_date" value="" size="2" maxlength="2" /> <span style="float:left">/</span> 
          			<input type="text" name="order_date_month" id="order_date" value="" size="2" maxlength="2" /> <span style="float:left">/</span>  
          			<input type="text" name="order_date_year" id="order_date" value="" size="2" maxlength="2" /> 
          		</div>
          		<div>
          			<label for="stref">Secure Trading Reference</label>
          			<input type="text" name="stref" id="stref" value="" />
          		</div>
          		<div>
          			<label for="Status">Status</label>
          			<select name="status" id="status">
          				<option value="0">Any</option>
          				<option value="1"><?php echo descriptive_status("1")?></option>
          				<option value="2"><?php echo descriptive_status("2")?></option>
          				<option value="3"><?php echo descriptive_status("3")?></option>
          				<option value="4"><?php echo descriptive_status("4")?></option>
          			</select>
          		</div>
          		<div>
          			<label for="scan_and_send">Scan and Send</label>
          			<select name="scan_and_send" id="scan_and_send">
          				<option value="0">Both</option>
          				<option value="1">Only Scan And Send</option>
          				<option value="2">Non - Scan and Send</option>
          			</select>
          		</div>
          	
	  	</fieldset>	
	</div>
	<div class="buttons"> 
    <input type="image" src="images/submit.gif" alt="submit" />
     </div>
</div>

</form>
<p>
</p>
</div>

<?php
include ('include/footer.php');
?>