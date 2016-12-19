<?php require_once("include/login_check.php") ?>
<?php 

include_once("include/siteconstants.php");
include_once("../include/commonfunctions.php");

if( isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_time']) && ValidToken($_POST['token'],$_SESSION['token'],$_SESSION['token_time'],0))
{

	$messages=array();
	$errors=array();
	
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	$table="banners";
	$fields=array("enabled","weight");
	$count=0;
	
	$errors=array();
	$_SESSION['errors']="";
	
	if(isset($_POST['total_banners']))
	{
		$total=$_POST['total_banners'];
		
		for($banner=0;$banner<$total;$banner++)
		{
			$enabled="enabled_" . $banner;
			$weight="weight_" . $banner;
			$id="id_".$banner;
			
			if(isset($_POST[$enabled]))
				$enabled_value=1;
			else
				$enabled_value=0;
			$weight_value=$_POST[$weight];
			$id_value=$_POST[$id];
			
			if($enabled_value==1)
				$total_percentage=$total_percentage+$weight_value;
				
			$values[]=array($enabled_value,$weight_value);
			$where[]=array("banners_id={$id_value}");
			
			$count++;	 
		}
	}
	
		
	if($total_percentage>100)
	{
		$errors[] = "Update not performed. Banner weights totaled more than 100%";
	}
	elseif($total_percentage==0)
	{
		$errors[] ="Update not performed. No Banners were selected to be enabled";
	}
	elseif($total_percentage<100)
	{
		$errors[] ="Update not performed. Banner weights did not total 100%";
	}
	
	
	if(sizeof($errors)>0)
	{
		$_SESSION['errors']=serialize($errors);
		header('location:controls.php');
		exit();
	}
	
	for($i=0;$i<$count;$i++)
		$DB->updateData($table,$fields,$values[$i],$where[$i],false);

	$messages[]="Banners Succesfully Updated";
	$_SESSION['messages']=serialize($messages);
	
}
header('location:controls.php');
		

?>