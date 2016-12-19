<?php

require_once('../include/siteconstants.php');
require_once('../include/commonfunctions.php');

//check to see if the page is accessed via HTTPS
if  ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') || ($_SERVER['HTTP_HOST']=="10.0.0.3" ) || ($_SERVER['HTTP_HOST']=="ancestry.internetlogistics.com" )) 
{
	
//Generate a unique token
//to be used to check the input is 
//from this form on the proccessing page
$token=UniqueToken();//will be passed in a hidden form element to proccessing page

//Store the token in the session so it can
//be checked on the proccessing page
$_SESSION['token']=$token;
	
//Also store the time 
//which can be used to check
//the lifetime of the token
$_SESSION['token_time']=time();
?>

<html>
	<head>
		<link rel="stylesheet" href="style/style.css" type="text/css" />
	</head>
	<body topmargin="0">
		<br />
		<form action="checkpass.php" method="post">
		<input type="hidden" name="token" value="<?php echo $token?>" />
			<div align="center">
				
				<br />
				USERNAME<br />
				<input type="text" name="username" autocomplete="off"><br />
				<br />
				PASSWORD<br />
				<input type="password" name="password" autocomplete="off"><br />
				<br />
				<input type="submit" value="Log In">
			</div>
		</form>
	</body>
</html>
<?php
}
else
{
	$page = $_SERVER['PHP_SELF'];
	$page = str_replace(" ","%20",$page);
	
	$page =  "https://" . $_SERVER['HTTP_HOST'] . $page ;	

	
	$page = "Location:" . $page;
	
	//echo "\n" . $page;
	//echo $page2;
	header($page);
	
}

?>