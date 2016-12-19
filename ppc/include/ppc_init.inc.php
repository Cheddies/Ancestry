<?php
	define("WWW_ROOT", '/');
	$curPage = basename($_SERVER["REQUEST_URI"]);
	
	//expiry code for landing pages
	if(stripos($curPage, 'family-tree-maker-2011-') !== false){
		$endDate = strtotime('8 October 2010');
		$daysLeftText = daysLeft($endDate);
	}
	
	if(stripos($curPage, 'mac-edition') !== false){
		$endDate = strtotime('22 November 2010');
		$daysLeftText = daysLeft($endDate);
	}

    if(stripos($curPage, 'ftm2011-easter-2011') !== false){
		$endDate = strtotime('21 April 2011');
		$daysLeftText = daysLeft($endDate);
	}
	
	function daysLeft($endDate){
		$now = time();
		$dateDiff = ($endDate - $now);
		$daysLeft = ceil($dateDiff/(60*60*24)) +1;
		if($daysLeft <1) header('location: ' . WWW_ROOT . 'offer_closed');
		$daysLeftText = $daysLeft >1 ? "Hurry, only {$daysLeft} days left!" : "Hurry, only {$daysLeft} day left!";
		return $daysLeftText;
	}
?>