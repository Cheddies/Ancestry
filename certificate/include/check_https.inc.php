<?php
//IL addition
//check to see if the page is accessed via HTTPS
$devServer = ($_SERVER['HTTP_HOST']=="localhost" || $_SERVER['HTTP_HOST']=="clients.edgeward.co.uk" || $_SERVER['HTTP_HOST']=="ancestry.internetlogistics.com"   || $_SERVER['HTTP_HOST']=="10.0.0.136"  );
if(!isset($devServer) || $devServer == false){
	if  ( !(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') 
		   || ($_SERVER['HTTP_HOST']=="10.0.0.3" ) 
		   || ($_SERVER['HTTP_HOST']=="212.38.95.165") 
		   || ($_SERVER['HTTP_HOST']=="10.0.0.136" ) 
		   || ($_SERVER['HTTP_HOST']=="78.31.106.192") 
		   || ($_SERVER['HTTP_HOST']=="ancestry.internetlogistics.com")
		   ) 
	{
		$page = $_SERVER['PHP_SELF'];
		$page = str_replace(" ","%20",$page);	
		$page =  "https://" . $_SERVER['HTTP_HOST'] . $page ;		
		$page = "Location:" . $page;
		header($page);
		exit;
	}
}

?>