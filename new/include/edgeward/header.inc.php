<?php 
//IL addition
require_once('include/commonfunctions.php');
require_once('include/siteconstants.php');
//end of IL addition
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pageTitle;?></title>
<link rel="stylesheet" type="text/css" href="style/edgeward/reset.css"/>
<link rel="stylesheet" type="text/css" href="style/edgeward/shop.css"/>
<?php if(!$no_js):?>
	<script type="text/javascript" src="script/edgeward/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="script/edgeward/jquery.validate.pack.js"></script>
    <script type="text/javascript" src="script/edgeward/jquery.tooltip.pack.js"></script>
    <script type="text/javascript" src="script/edgeward/shop.js"></script>
    <?php if($formtype == 'basket' || $formtype == 'summary'):?>
    	<script type="text/javascript" src="script/edgeward/basket.js"></script>
    <?php endif;?>
<?php endif;?>
	<!-- IL -->
	<link rel="stylesheet" type="text/css" href="style/global_footer.css" />
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<link href="style/ancestry_global_styles.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
    <div id="top_bar"><a href="http://www.ancestryshop.co.uk/customer_service.php" target="_blank" id="cust_service">Customer Service</a></div>
    <h1 class="header">Ancestry Shop</h1>