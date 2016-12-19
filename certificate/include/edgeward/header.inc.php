<?php 
//clicktale include must be at top of page
if($_SERVER['HTTP_HOST']== 'www.ancestryshop.co.uk' && !in_array($pageSuffix, array('_us','_aus','_ca'))) require_once("ClickTale/ClickTaleTop.php");
//IL addition
require_once('include/commonfunctions.php');
require_once('include/siteconstants.php');
//end of IL addition
//which layout set?
//$layout = layoutSet();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $omniture_pageTitle;?></title>
<?php if($layout['set'] >1):?>
<link rel="stylesheet" type="text/css" href="style/edgeward/new_bmd.css"/>
<?php else:?>
<link rel="stylesheet" type="text/css" href="style/edgeward/reset.css"/>
<link rel="stylesheet" type="text/css" href="style/edgeward/bmd.css"/>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link href="style/ancestry_global_styles.css" rel="stylesheet" type="text/css" />
<?php endif;?>
<link rel="stylesheet" type="text/css" href="style/global_footer.css" />
<?php if(!$no_js):?>
	<script type="text/javascript" src="script/edgeward/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="script/edgeward/jquery.validate.min.js"></script>
    <script type="text/javascript" src="script/edgeward/jquery.tooltip.pack.js"></script>
    <script type="text/javascript" src="script/edgeward/bmd.js"></script>
<?php endif;?>
</head>

<body>	
<div id="container">
<div id="top_bar"><a href="faqs.php" target="_blank" id="cust_service">FAQs</a></div>
<h1 class="header"><?php echo $pageTitle;?></h1>