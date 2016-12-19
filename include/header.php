<?php include_once('include/page_pre_process.inc.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="ROBOTS" content="all" />
	<meta name="description" content="<?php echo $pageData['meta']['description'];?>" />
	<meta name="keywords" content="<?php echo $pageData['meta']['keywords'];?>" />
	<?php //don't cache https pages
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'):?>
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="no-cache" />	
	<?php endif;?>
	<title><?php echo $pageData['meta']['title'];?></title>
	<link href="<?php echo WEBROOT;?>css/960.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo WEBROOT;?>css/default.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo WEBROOT;?>css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo WEBROOT;?>script/edgeward/jquery.fancybox-1.2.1.pack.js"></script>
	<script type="text/javascript" src="<?php echo WEBROOT;?>script/edgeward/anc_shop.js"></script>
	<script type="text/javascript" src="<?php echo WEBROOT;?>script/omniture003.js"></script>
	<script type="text/javascript" src="<?php echo WEBROOT;?>script/cookie.js"></script>
</head>


<body>
	<div id="container" class="container_12">

