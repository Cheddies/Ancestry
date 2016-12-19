<?php
include("Mail.php");

require_once ("Zend/Mail.php");

	
ini_set("SMTP", "192.168.222.165");
ini_set("sendmail_from", "service@rafcollection.com");
	
function formatcurrencytext($value) {
	$currency = sprintf('%.2f', $value);
	$currency = "£".$currency;
	return($currency);
}

function pad($text, $length) {
	if (strlen($text) > $length) {
		$text = substr($text, 0, $length);
	}

	else {
		$text = str_pad($text, $length);
	}
	return $text;
}


function sendEmailConfirmation_html($niceordernumber){
		// Collect the Order Header Details

	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$mysql=array( );
	$mysql['niceordernumber']=mysql_real_escape_string($niceordernumber,$link);		
	// Performing SQL query
	$query = "SELECT tbl_order_header.niceordernum, tbl_order_header.order_date, tbl_order_header.firstname, tbl_order_header.lastname, tbl_order_header.address1, tbl_order_header.address2, tbl_order_header.city, tbl_order_header.zipcode, tbl_order_header.email, tbl_order_header.sfirstname, tbl_order_header.slastname, tbl_order_header.saddress1, tbl_order_header.saddress2, tbl_order_header.scity, tbl_order_header.szipcode, tbl_shipping.description , tbl_shipping.price, tbl_order_header.ordernumber,tbl_order_header.state,tbl_order_header.sstate
		  FROM tbl_order_header
		  INNER JOIN tbl_shipping
		  ON tbl_order_header.shipvia=tbl_shipping.code
		  WHERE tbl_order_header.niceordernum = {$mysql['niceordernumber']};";

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	$row = mysql_fetch_row($result);
	
	
	$countryquery = "SELECT tbl_countries.country,tbl_order_header.niceordernum FROM tbl_countries,tbl_order_header WHERE tbl_order_header.niceordernum = {$mysql['niceordernumber']} AND tbl_order_header.country = tbl_countries.code"; 
	
	$countryresult = mysql_query($countryquery) or die('Query failed: ' . mysql_error());
	
	$country=mysql_fetch_row($countryresult);
	$country=$country[0];

	$countryquery = "SELECT tbl_countries.country FROM tbl_countries,tbl_order_header WHERE tbl_order_header.niceordernum = {$mysql['niceordernumber']} AND tbl_order_header.scountry = tbl_countries.code"; 
	
	$countryresult = mysql_query($countryquery) or die('Query failed: ' . mysql_error());
	
	$scountry=mysql_fetch_row($countryresult);
	$scountry=$scountry[0];
	
	// Closing connection
	mysql_close($link);
	

	// Collect the Basket Details

	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql['sessionid']=mysql_real_escape_string($row[17],$link);
	// Performing SQL query
	$basketquery = "SELECT itemcode, name, quantity, price
		  FROM tbl_baskets
		  WHERE sessionid = '". $mysql['sessionid'] ."';";

	$basketresult = mysql_query($basketquery) or die('Query failed: ' . mysql_error());

	// Closing connection
	mysql_close($link);

	$basketbody_text = "";
	$basketbody_html = "";

	while ($line = mysql_fetch_array($basketresult, MYSQL_ASSOC)) {
		$basketbody_text = $basketbody . pad($line["itemcode"], 20) . "\t" . pad($line["name"], 45) . "\t" . pad($line["quantity"], 4) . "\t" . pad(formatcurrencytext($line["price"]), 6) . "\n";
		$basketbody_html = $basketbody_html . "<tr><td>" . $line["itemcode"] . "</td>" . "<td>"  . $line["name"] . "</td>" . "<td>" . $line["quantity"] .  "</td>" . "<td>" . formatcurrencytext($line["price"]) . "</td></tr>";
	}
	
	$mailbody_html="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
  	\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
	<html lang=\"en\" xmlns=\"http://www.w3.org/1999/xhtml\">
	<head><meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\" />";
	
	$html_style="<style>";
	$html_style=$html_style . ".bold{font-weight:bold;}";
	$html_style=$html_style . "table{ margin:0; padding:0; border:0; text-align:left; width:100%; }";
	$html_style=$html_style ."</style>";
	
	$mailbody_html=$mailbody_html . $html_style;
	
	
	$mailbody_text="";
	
//Create the Text version of the email	
$mailbody ="Dear " . $row[2] . ",\n";

$mailbody = $mailbody . "\nThank you for placing your order with www.rafcollection.com \n";

$mailbody = $mailbody . "\nYour order will be delivered in the specified time frame, to the shipping address provided as shown below \n";

$mailbody = $mailbody . 
"\nOrder Number: " . $row[0] . "

\nBilling Address:

" . $row[2] . " " . $row[3] . ", " . $row[4] . ", " . $row[5] . ", " . $row[6] . " ," . $row[18] . ", " . $row[7] . ", " . $country ."

\nShipping Address:

" . $row[9] . " " . $row[10] . ", " . $row[11] . ", " . $row[12] . ", " . $row[13] . ", " . $row[19] . ", " . $row[14] . ", " . $scountry ."

\nShipping Method:

" . $row[15] . " - £" . $row[16] . "

\nOrder Details:\n

" . pad("Item Code", 20) . "\t" . pad("Name", 45) . "\t" . pad("Quantity", 4) . "\t" . pad("Price", 6) . "
";

$mailbody = $mailbody . $basketbody_text;

$mailbody = $mailbody . "\nIf you have any questions about your order please call +44 (0)1270 758 845 or email service@rafcollection.com \n";

$mailbody = $mailbody .

"
Kind Regards,

The RAF Collection";
	
	$mailbody_text = $mailbody;
	
//create the html version of the email
	$mailbody_html=$mailbody_html . "<p>" .  "Dear " . $row[2] ."</p>";
	$mailbody_html=$mailbody_html . "<p>" . "Thank you for placing your order with <a href=\"http://www.rafcollection.com\">www.rafcollection.com</a>" ."</p>";
	$mailbody_html=$mailbody_html . "<p>" . "Your order will be delivered in the specified time frame, to the shipping address provided as shown below" ."</p>";
	$mailbody_html=$mailbody_html . "<p>" . "<span class=\"bold\">Order Number:</span>" . $row[0] ."</p>";
	
	$mailbody_html=$mailbody_html . "<p>" . "<span class=\"bold\">Billing Address:</span>" . "<br />";
	$mailbody_html=$mailbody_html . $row[2] . " " . $row[3] . "<br />";
	$mailbody_html=$mailbody_html  . $row[4] . ", " . $row[5] . ", " . $row[6] . ", " . $row[18] . ", " . $row[7] . ", " . $country ;
	$mailbody_html=$mailbody_html ."</p>";
	
	$mailbody_html=$mailbody_html . "<p>" . "<span class=\"bold\">Shipping Address:</span>" . "<br />";
	$mailbody_html=$mailbody_html . $row[9] . " " . $row[10]  . "<br />";;
	$mailbody_html=$mailbody_html . $row[11] . ", " . $row[12] . ", " . $row[13] . ", " . $row[19] . ", " . $row[14] . ", " . $scountry;
	$mailbody_html=$mailbody_html ."</p>";
	
	$mailbody_html=$mailbody_html . "<p>" . "<span class=\"bold\">Shpping Method:</span>" . "<br />";
	$mailbody_html=$mailbody_html .  $row[15] . " - £" . $row[16];
	$mailbody_html=$mailbody_html ."</p>";
	
	$mailbody_html=$mailbody_html . "<p>" . "<span class=\"bold\">Order Details:</span>" . "<br />";
	$mailbody_html=$mailbody_html . "<table>";
	$mailbody_html=$mailbody_html . "<tr><th>Item Code</th> <th>Name</th>  <th>Qty</th> <th>Price</th> </tr>";
	$mailbody_html=$mailbody_html . $basketbody_html;
	$mailbody_html=$mailbody_html . "</table>";
	$mailbody_html=$mailbody_html ."</p>";
	
	$mailbody_html=$mailbody_html ."<p>";
	$mailbody_html=$mailbody_html ."If you have any questions about your order please call +44 (0)1270 758 845 or email <a href=\"mailto:service@rafcollection.com\">service@rafcollection.com</a>.";
	$mailbody_html=$mailbody_html ."</p>";
	
	$mailbody_html=$mailbody_html ."<p>";
	$mailbody_html=$mailbody_html ."Kind Regards,";
	$mailbody_html=$mailbody_html ."<br /><br />The RAF Collection";
	$mailbody_html=$mailbody_html ."</p>";
	
	$mailbody_html=$mailbody_html ."</body></html>";
	$mail = new Zend_Mail();

	
	$recipients = $row[8];
	/*$body = $mailbody;*/
	
	$mail = new Zend_Mail();
	
	/*$mail->setBodyText($body);*/
	
	$mail->setBodyText($mailbody_text);
	$mail->setBodyHtml($mailbody_html);

	$mail->setFrom("service@rafcollection.com", "service@rafcollection.com");

	$mail->addTo($recipients,$recipients);
	
	$mail->setSubject('Order Confirmation');
	
	$mail->send();

}

$clean=array();
$clean['niceodrnum']=114;
sendEmailConfirmation_html($clean['niceodrnum']);


?>