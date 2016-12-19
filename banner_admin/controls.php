<?php 
//banner controls

include_once("include/siteconstants.php");
include_once("../include/commonfunctions.php");

include("include/admin_header.php");

//get a list of banners
$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);

$table="banners";
$fields=array("banners_id","name","link","file","file_type","width","height","weight","enabled","weight");
$banner_data=$DB->getData($table,$fields);

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();


if(isset($_SESSION['errors']))
{
	$errors=unserialize($_SESSION['errors']);
	unset($_SESSION['errors']);
}

if(isset($_SESSION['messages']))
{
	$messages=unserialize($_SESSION['messages']);
	unset($_SESSION['messages']);
}

$display_message="";

if(isset($errors) && is_array($errors))
{
	foreach($errors as $error)
	{
		$display_message=$display_message . "<span id=\"error\">{$error}</span><br />";
	}
}

if(isset($messages) && is_array($messages))
{
	foreach($messages as $message)
	{
		$display_message=$display_message . "<span id=\"message\">{$message}</span><br />";
	}
}

?>
<h2>Current Banner Status</h2>
<p>
The below table lists the status of each banner. Click <strong>'View'</strong> next to a banner to show it in a new window.
</p>
<p>
Use the form controls to change the status of the current banners. Click <strong>'Update'</strong> to confirm the changes.
</p>
<p>
At least one banner must be enabled, and the  percentage weighting must total 100 for the update to be succesful.
</p>
<p>
Only enabled banners count towards the total percentage.
</p>
<?php if(strlen($display_message)>0)
{
?>
	<div id="display_message">
		<?php echo $display_message?>
	</div>
<?php 
}
?>
<form action="banner_change.php" method="post">
<input type="hidden" name="token" value="<?php echo $token ?>" /> 
<table id="banner_table">
<tr>
	<th id="name">Name</th><th id="weighting">Weighting %</th><th id="enabled">Enabled</th><th id="view">View</th>
</tr>
<?php
$count=0;
foreach ($banner_data as $banner)
{
	if( ($count%2)>0)
		$class="odd";
	else
		$class="even";
	
?>
	<tr class="<?php echo $class?>" >
		
		<td><input name="id_<?php echo $count?>" type="hidden" value="<?php echo $banner['banners_id']?>" />
		<?php echo $banner['name']?></td>
		<td><input name="weight_<?php echo $count?>" type="text" size="3" maxlength="3" value="<?php echo $banner['weight']?>" /></td>
		<td><input name="enabled_<?php echo $count?>" type="checkbox" <?php if($banner['enabled']) echo "checked=\"checked\"" ?> /></td>
		<td><a target="_blank" href="view_banner.php?banner_id=<?php echo $banner['banners_id']?>">View</a></td>
	</tr>
<?php
$count++;
}
?>
</table>
<input type="hidden" name="total_banners" value="<?php echo $count?>" /> 
<input type="submit" value="Update" />
</form>
<?php 
	include("include/admin_footer.php");
?>