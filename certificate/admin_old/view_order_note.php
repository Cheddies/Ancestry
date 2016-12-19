<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");
include_once("fckeditor/fckeditor.php") ;

//check for user level
if(!isset($_SESSION['user_level']) || $_SESSION['user_level']!=ADMIN_USER)
{
	header('location:index.php');
	exit();
}

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
	
	
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	
	$fields=array("GRO_orders_notes_id","GRO_orders_id","note","updated");
	$where=array("GRO_orders_id={$clean['order_id']}");
	$order="updated DESC";
	$notes=$DB->getData("GRO_orders_notes",$fields,$where,"","",$order);
	
?>
<div id="admin_page">
<div id="f-wrap">
<div id="f-header">
<h3>
View Order Notes - Order Number <?php echo $clean['order_id']?>
</h3>
<img src="images/tree-icon.jpg" alt="" class="tree-icon">
</div>
</div>

<div id="order_details">
<div id="f-content">
<div id="update_bar">
<a href="view_order.php?token=<?php echo $token?>&amp;order_id=<?php echo $clean['order_id']?>" />Back To Order</a>
</div>

<?php

if($notes)
{
?>
<table id="order_notes">
<th style="width:85%">Note</th><th>Added</th><th>Delete</th>
<?php 
$count=0;
foreach($notes as $note)
{
	$count++;
	?>
	<tr style="vertical-align:top" class="<?php if($count%2==0) echo "odd"; else echo "even";?>">
		<td><?php echo $note['note']?></td><td><?php echo date("d/m/Y H:i:s",strtotime($note['updated']))?></td><td><a href="delete_order_note.php?note_id=<?php echo $note['GRO_orders_notes_id']?>&amp;token=<?php echo $token?>&amp;order_id=<?php echo $clean['order_id']?> "/>Delete</a></td>
	</tr>
	<?php
}
?>
</table>
<?php
}
else
{
	echo "Order currently has no notes";
}
?>
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