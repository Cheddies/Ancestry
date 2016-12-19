<?php
	require_once("include/siteconstants.php");
	require_once("include/commonfunctions.php");

	//Generate a unique token
	//to be used to check the input is 
	//from this form on the proccessing page
	if(!isset($search_token))
	{
		if(isset($_GET['search_token']))
		{
			$search_token=form_clean($_GET['search_token'],32);
		}
		else
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

	if(isset($_SERVER['PHP_SELF']))
	{
		$Page=basename($_SERVER['PHP_SELF']);
		$Page=substr($Page,0,strlen($Page)-4);
	}
	
	if($Page=='search')
	{
		header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: no-cache");
	}
	
	//build breadcrumb

	$bread_crumbs=unserialize($_SESSION['bread_crumbs']);
	
		switch($Page)
		{
			case 'index':
				$bread_crumbs=array(0=>array('text'=>"Choose Certificate"));
			break;
			
			case 'birth_certificate':
			
				$bread_crumbs=array(0=>array('link'=>"index.php",'text'=>"Choose Certificate"),
									1=>array('link'=>"birth_certificate.php",'text'=>"Birth Certificate")
									);
				break;
		
			case 'death_certificate':
			
				$bread_crumbs=array(0=>array('link'=>"index.php",'text'=>"Choose Certificate"),
									1=>array('link'=>"death_certificate.php",'text'=>"Death Certificate")
									);
			
			break;
			
			case 'marriage_certificate':
			
				$bread_crumbs=array(0=>array('link'=>"index.php",'text'=>"Choose Certificate"),
									1=>array('link'=>"marriage_certificate.php",'text'=>"Marriage Certificate")
									);
			break;
			
			case 'address_details':
					
					$bread_crumbs[3]['text']='Address Details';	
					$bread_crumbs[3]['link']='address_details.php';	
					
					unset($bread_crumbs[4]);
					unset($bread_crumbs[5]);
			break;
			
			case 'choose_delivery':
					$bread_crumbs[4]['text']='Choose Delivery';	
					$bread_crumbs[4]['link']='choose_delivery.php';	
					
					unset($bread_crumbs[5]);
					
			break;
			
			case 'order_summary':
					$bread_crumbs[5]['text']='Order Summary';	
					$bread_crumbs[5]['link']='order_summary.php';	
			break;
			
		}
		
		
		$_SESSION['bread_crumbs']=serialize($bread_crumbs);
	
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
	<link rel="stylesheet" type="text/css" href="style/global_footer.css" />
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<link href="style/ancestry_global_styles.css" rel="stylesheet" type="text/css" />
	<link href="style/main.css" rel="stylesheet" type="text/css" media="screen" />
	<!--[if IE]>
	<style type="text/css">
	#f-content legend {margin-left: -7px;}
	#f-content fieldset .checkbox {width:13px;height:13px;}
	</style>
	<![endif]-->

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
<?php

$mysql['session_id']=mysql_real_escape_string(session_id(),$link);

/*$items_query="SELECT COUNT(*) as items_in_basket FROM tbl_baskets WHERE sessionid='{$mysql['session_id']}'";
$items_result=mysql_query($items_query);
if($line=mysql_fetch_array($items_result,MYSQL_ASSOC))
	$clean['items_in_basket']=$line['items_in_basket'];
else
	$clean['items_in_basket']=0;*/
	
?>

<body>
<div id="container">

<div id="main_top_bar">
	<span id="ancestry_link"><a href="http://www.ancestry.co.uk">&lt; BACK TO ANCESTRY.CO.UK</a></span> <a href="customer_service.php">Customer Service</a> <a href="faqs.php">FAQ's</a>
</div>
<div id="logo_section">
	<h1><img src="images/ancestryshop.jpg" alt="the ancestry shop" /></h1>
	
</div>