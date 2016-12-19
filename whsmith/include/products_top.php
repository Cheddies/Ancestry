<?php
$clean=array();
if(isset($_GET['dept'])&& is_numeric($_GET['dept']) )
{
$clean['dept']=form_clean($_GET['dept'],4);
		
		$dept_image="images/" . basename($clean['dept']) ."_top.gif";
		if(file_exists($dept_image))
		{
	?>
		<img src="<?php Escaped_echo($dept_image)?>" alt="" />
	<?php
		}
}
else
{
	if(isset($_GET['master']) && is_numeric($_GET['master']) )
	{
		$clean['master']=form_clean($_GET['master'],4);
		
		$dept_image="images/" . basename($clean['master']) ."_top.gif";
		if(file_exists($dept_image))
		{
	?>
		<img src="<?php Escaped_echo($dept_image)?>" alt="" />
	<?php
		}
	}
}
?>

<div id="dept_title">
	<?php 
	$dept_title=PAGE_HEADER_TEXT;//Set in SiteConstants.php
	if(isset($_GET['dept']))
	{
		$clean['department']=form_clean($_GET['dept'],4);
		$dept_title=$dept_title.getDepartmentTree($clean['department']);
		Escaped_echo( $dept_title);
	}
	
	if(isset($_GET['number']))
	{
		$clean['number']=form_clean($_GET['number'],20);
		Escaped_echo( "Item #:" . $clean['number']);
	}
	?>
	
	
	
</div>
