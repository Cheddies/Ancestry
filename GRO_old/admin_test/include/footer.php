<?php
//store the current page as last_page in session
//done in footer in case the current page uses this variable
if(isset($_SERVER['PHP_SELF']))
{
	$Page=basename($_SERVER['PHP_SELF']);
	if(isset($_SERVER['QUERY_STRING']))
	{
		$Query=$_SERVER['QUERY_STRING'];
		$_SESSION['last_page']=$Page . "?" . $Query;
	}
	else
	{
			$_SESSION['last_page']=$Page;
	}
}
	
?>
<div id="shop_footer">
</div>
</body>
</html>