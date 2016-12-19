<?php
if(isset($_GET['cj_tracking']) && isset($_GET['ba_tracking']))
{
?>
	<img src="<?php echo $_GET['cj_tracking']?>" width="1" height="1" alt="Ancestry" />
	<img src="<?php echo $_GET['ba_tracking']?>" height="1" width="20" />
<?php 
} 
?>