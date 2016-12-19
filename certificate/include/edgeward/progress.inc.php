<?php
//function to get current page name
/*function curPageName() {
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

//Array of pages to be displayed in the navigation
$pages = array("Home" => "index.php", 
							"Portfolio" => "portfolio.php",
							"About" => "about.php",
							"Contact" => "contact.php");

//Loop thru and create list of pages
$curPage = curPageName();
foreach ($pages as $k => $v) { 
	echo "<li><a href=\"$v\"  id=\"nav-$k\"";
	if ($v == $curPage) {
		echo " class=\"current\"";
	}		
echo ">" .$k ."</a></li>\n";
}*/
?>

<ul id="progress_bar">
<li><a href="../../inc/from_search_1.php">Confirm Details</a></li>
<li><a href="../../inc/from_search_1.php">Billing & Delivery</a></li>
<li><a href="../../inc/from_search_1.php">Summary and T&amp;C</a></li>
</ul>