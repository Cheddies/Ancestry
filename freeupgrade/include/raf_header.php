<?php
	require_once("include/siteconstants.php");
	require_once("include/commonfunctions.php");
	//Generate a unique token
	//to be used to check the input is 
	//from this form on the proccessing page
	if(!isset($search_token))
	{
		$search_token=UniqueToken();//will be passed in a hidden form element to proccessing page
	}
	//Store the token in the session so it can
	//be checked on the proccessing page
	$_SESSION['search_token']=$search_token;
	
	//Also store the time 
	//which can be used to check
	//the lifetime of the token
	$_SESSION['search_token_time']=time();
	
	$clean=array();
	$mysql=array();
	
	if(isset($_GET['dept']))
	{
		
		
		$clean['dept']=form_clean($_GET['dept'],4);	
		$_SESSION['dept']=$clean['dept'];
		
	}
	
	
	//Setup which alternative currency to show
	$_SESSION['alt']=EURO;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Chris Gan" />
	<meta name="ROBOTS" content="all" />
	
	<?php
		$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
		
	
	
		if(isset($_SERVER['PHP_SELF']))
		{
			$Page=basename($_SERVER['PHP_SELF']);
			$Page=substr($Page,0,strlen($Page)-4);
		}
		
		$keywords="";
		$description="";
		$variable="";
		
		$title=PAGE_HEADER_TEXT;//Set in SiteConstants.php
		
		if(isset($Page))
		{	
			if(isset($_GET['dept']))
				$variable=form_clean($_GET['dept'],4);
			else
			{
				if(isset($_GET['master']))
					$variable=form_clean($_GET['master'],4);
			}
			if(get_page_meta_tags($Page,&$keywords,&$description,&$title,$variable))
				$title=PAGE_HEADER_TEXT . ">".  $title;
		}
			
			if(isset($_GET['dept']))
			{
				$clean['department']=form_clean($_GET['dept'],4);
				$title=$title.getDepartmentTree($clean['department']);
				
			}
			
			if(isset($_GET['number']))
			{
				$clean['number']=form_clean($_GET['number'],20);
				$title=$title . " " . getProductName($clean['number']);
			
			}		 	
	 ?>
	<meta name="description" content="<?php echo $description ?>" />
	<meta name="keywords" content="<?php echo $keywords;?>" />
	<title><?php escaped_echo (str_replace(">" , "|" , $title)) ;?></title>
	
	<link href="style/new_style.css" rel="stylesheet" type="text/css" />
	<link href="style/dropdownmenu.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="include/pulloutmenus.js"></script>

	<?php
	if(isset($_GET['master'])&& is_numeric($_GET['master']))
	{	
		
		$clean['master']=form_clean($_GET['master'],4);
		$_SESSION['master']=$clean['master'];
	}	
	if(isset($clean['dept'])&& strlen($clean['dept']>0))
	{
		$file="style/" . basename($clean['dept']) . "_style.css";
		if(file_exists($file))
		{
	?>	
		<link href="style/<?php Escaped_Echo( $clean['dept'])?>_style.css" rel="stylesheet" type="text/css" />
	<?php
		}
		else
		{
		?>
			<link href="style/default_dept_style.css" rel="stylesheet" type="text/css" />
		<?php
		}
	}
	else
	{
	?>
	
	<?php
	}	 
	?>
	
	<?php
		if(isset($Page))
		{
			$Css ="style/" . basename($Page) ."_style.css";
			if(file_exists($Css))
			{
			?>
				<link href="<?php echo $Css ?>" rel="stylesheet" type="text/css" />
			<?php
			}
			else
			{
			?>
				<link href="style/default_style.css" rel="stylesheet" type="text/css" />
			<?php
			}
		}
	?>
	
	<?php
	if   (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')
	{
		//don't cache https pages
	?>
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="no-cache" />	
	<?php
	}
	?>	
</head>
<body>

<div id="container">

	<div id="leftside">
		<a href="index.php"><img src="images/raf_logo.gif" alt="The RAF Collection" /></a>
		<?php 
			include ("include\maindepts.php");
		?>
		
		
		<div id="side_basket">
			<?php include ("include/basketplugin_new.php");?>
		</div>
	</div>
	
	<div id="top_bar">
		<ul>
			<li><a href="index.php">Home</a></li>
			
			<li><a href="basket.php">Shopping Basket</a></li>
			<li><a href="checkout.php">Checkout</a></li>
			<li><a href="faqs.php">FAQ</a></li>
		</ul>	
		<form action="search.php" method="get"><label for="searchterm">Product Search</label><input type="hidden" name="search_token" value="<?php Escaped_echo ($search_token)?>" /><input style="margin-bottom:20px;" type="text" size="15" maxlength="50" id="searchterm" name="searchterm" alt="Search" /><input id="go" type="image" src="images/go.gif" alt="Go"/></form>	
	</div>
	
	<div id="inner_container">
	<?php
		
		
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
			<?php escaped_echo (PAGE_HEADER_TEXT_EXTRA . " " .$title);?>
		 </div>
		 
	<?php
		if
		(
			isset($Page) && 
			( $Page=="index" || 
			  $Page=="products" || 
			  $Page=="checkout" ||
			  $Page=="checkout1a" ||
			  $Page=="checkout1b" ||
			  $Page=="checkout2" ||
			  $Page=="basket" 
		     )
		)
		{
			include ("include/header_section.php");
		}
	?>
	<div id="main">