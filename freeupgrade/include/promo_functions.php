<?php

function PromoShipping($country)
{
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect:' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$clean=array();
	$clean['country']=$country;
	
	$mysql=array();
	$mysql['country']=mysql_real_escape_string($clean['country']);
	
	
	$query="SELECT shipping FROM promo_country_shipping WHERE country='{$mysql['country']}'";
	$result=mysql_query($query);
	
	if($line= mysql_fetch_array($result,MYSQL_ASSOC))
	{
		return $line['shipping'];
	}
	else
		return false;
	
}

function CountryName($country)
{
	
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect:' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
		
	$clean=array();
	$clean['country']=$country;
		
	$mysql=array();
	$mysql['country']=mysql_real_escape_string($clean['country']);
		
	//get name of country
	$query = "SELECT country from tbl_countries where code= '{$mysql['country']}'";
	$result= mysql_query($query) or die ('Query failed'. mysql_error());
	
	if($line= mysql_fetch_array($result,MYSQL_ASSOC))
	{
		return $line['country'];
	}
	else
		return false;
}

?>