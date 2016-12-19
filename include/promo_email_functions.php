<?php
/***** Moved from sendemail.php *******/
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


function  promo_email($niceordernumber,$promo_type){
	// Collect the Order Header Details

	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$mysql=array( );
	$mysql['niceordernumber']=mysql_real_escape_string($niceordernumber,$link);		
	// Performing SQL query
	$query = "SELECT tbl_order_header_promo.niceordernum, tbl_order_header_promo.order_date, tbl_order_header_promo.firstname, tbl_order_header_promo.lastname, tbl_order_header_promo.address1, tbl_order_header_promo.address2, tbl_order_header_promo.city, tbl_order_header_promo.zipcode, tbl_order_header_promo.email, tbl_order_header_promo.sfirstname, tbl_order_header_promo.slastname, tbl_order_header_promo.saddress1, tbl_order_header_promo.saddress2, tbl_order_header_promo.scity, tbl_order_header_promo.szipcode, tbl_shipping.description , tbl_shipping.price, tbl_order_header_promo.ordernumber,tbl_order_header_promo.state,tbl_order_header_promo.sstate
			  FROM tbl_order_header_promo
		  	  INNER JOIN tbl_shipping
		      ON tbl_order_header_promo.shipvia=tbl_shipping.code
		      WHERE tbl_order_header_promo.niceordernum = {$mysql['niceordernumber']};";

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	$order_data = mysql_fetch_array($result);
	
	$countryquery = "SELECT tbl_countries.country,tbl_order_header_promo.niceordernum FROM tbl_countries,tbl_order_header_promo WHERE tbl_order_header_promo.niceordernum = {$mysql['niceordernumber']} AND tbl_order_header_promo.country = tbl_countries.code"; 
	$countryresult = mysql_query($countryquery) or die('Query failed: ' . mysql_error());
	$country=mysql_fetch_row($countryresult);
	$country=$country[0];
	
	$countryquery = "SELECT tbl_countries.country FROM tbl_countries,tbl_order_header_promo WHERE tbl_order_header_promo.niceordernum = {$mysql['niceordernumber']} AND tbl_order_header_promo.scountry = tbl_countries.code"; 
	$countryresult = mysql_query($countryquery) or die('Query failed: ' . mysql_error());
	$scountry=mysql_fetch_row($countryresult);
	$scountry=$scountry[0];
	
	// Closing connection
	mysql_close($link);
	
	// Collect the Basket Details
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	$mysql['sessionid']=mysql_real_escape_string($order_data['ordernumber'],$link);
	// Performing SQL query
	$basketquery = "SELECT itemcode, name, quantity, price, discount, (price * ( (100 - discount) / 100 ) ) * quantity AS subtotal
					FROM tbl_baskets
		  			WHERE sessionid = '". $mysql['sessionid'] ."';";

	$basketresult = mysql_query($basketquery) or die('Query failed: ' . mysql_error());

	// Closing connection
	mysql_close($link);

	$basketbody_text = "\n";
	$basketbody_html = "";

	switch($promo_type)
	{
		case "FREEUPGRADE":
			$basketbody_text = $basketbody_text . pad("UPGRADE", 20) . "\t" . pad("Family Tree Maker Upgrade", 45) . "\t" . pad("1", 4) . "\t" . pad("0.00", 6) . "\t" . pad("0" . "%", 8)  ."\t" . pad("0.00", 8) . "\n";
			$basketbody_html = $basketbody_html . "<tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\"><td width=\"50%\">" . "UPGRADE" . "</td>" . "<td align=\"right\" bgcolor=\"#F1EDC5\">"  . "Family Tree Maker Upgrade" . "</td>" . "<td align=\"right\" bgcolor=\"#F1EDC5\">" . "1" .  "</td>" . "<td align=\"right\" bgcolor=\"#F1EDC5\">" . "0.00"  ."</td><td align=\"right\" bgcolor=\"#F1EDC5\">" .  "0"  ."%" . "</td><td align=\"right\" bgcolor=\"#F1EDC5\">" . "0.00". "</td></tr>";
		break;
	}
	
	$mailbody_html="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
					<html xmlns=\"http://www.w3.org/1999/xhtml\">
					<head>
						<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<title>Untitled Document</title>
					</head>";
	
	$mailbody_html=$mailbody_html . 
	"<body>
	<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px\">
		<tr>
			<td bgcolor=\"#000000\">&nbsp;</td>
		</tr>
		<tr>
			<td><img src=\"http://www.ancestryshop.co.uk/images/shop_email_head4.gif\" width=\"600\" height=\"61\" /></td>
		</tr>
		<tr>
			<td bgcolor=\"afbc22\"><table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"20\" style=\"color:#FFFFFF\">
			<tr>
				<td width=\"52\"><strong>Sent:<br />Subject:</strong></td>
        		<td width=\"468\">". date ("d F Y H:i") ."<br />The Ancestry Shop Order Confirmation</td>
			</tr>
		</table>
		</td>
	  	</tr>
	  	<tr>
	 	<td><table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"20\">
     <tr>
	<td><p><strong>Dear ". $order_data['firstname'] .",</strong></p>
          <p>Thank you for your order.</p>
          <p>Please note that this email is only a confirmation of receipt of your order and your offer to purchase these items is not legally accepted until we either dispatch, or send you an email notifying you of the dispatch of your products. Please check the details below are correct. If there are any problems feel free to phone our customer service helpline on 0845 688 5114 or email us at service@ancestryshop.co.uk.</p></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align=\"center\" valign=\"top\">
      <table width=\"560\" border=\"0\" cellpadding=\"10\" cellspacing=\"3\">
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td colspan=\"2\"><strong>Order Number:</strong>". $order_data['niceordernum']. "</td>
          </tr>
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td width=\"50%\" bgcolor=\"#F1EDC5\"><p><strong>Billing Address:</strong></p>
              <p>" . $order_data['firstname']. " " . $order_data['lastname'] .  "<br />".
                $order_data['address1']."<br />".
                $order_data['address2']."<br />".
                $order_data['city']."<br />".
                $order_data['zipcode']."<br />
              </p></td>
            <td width=\"50%\"><p><strong>Shipping Address:</strong></p>
             <p>" . $order_data['sfirstname']. " " . $order_data['slastname'] .  "<br />".
                $order_data['saddress1']."<br />".
                $order_data['saddress2']."<br />".
                $order_data['scity']."<br />".
                $order_data['szipcode']."<br />
            </p></td>
          </tr>
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td colspan=\"2\"><strong>Shipping Method:</strong>". $order_data['description'] . "- &pound;".  $order_data['price'] ."</td>
          </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align=\"center\" valign=\"top\">
      <table width=\"560\" border=\"0\" cellspacing=\"3\" cellpadding=\"10\">
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td colspan=\"6\"><strong>Order details:</strong></td>
          </tr>
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td bgcolor=\"#F1EDC5\"><strong>Item Code</strong></td>
            <td width=\"50%\" bgcolor=\"#F1EDC5\"><strong>Name</strong></td>
            <td bgcolor=\"#F1EDC5\"><strong>Quan</strong></td>
            <td bgcolor=\"#F1EDC5\"><strong>Price</strong></td>
            <td bgcolor=\"#F1EDC5\"><strong>Discount</strong></td>
            <td bgcolor=\"#F1EDC5\"><strong>Subtotal</strong></td>
          </tr>
        ". $basketbody_html ."
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width=\"600\" border=\"0\" cellpadding=\"20\">
          <tr align=\"left\" valign=\"top\">
            <td><p>We recommend that you print off and retain the copy of this email for your records together with a copy of our Ancestry Shop <a href=\"http://www.ancestryshop.co.uk/tcs.php\" style=\"color:#afbc22\">Terms &amp; Conditions</a>.</p>
              <p>This is an automated email please do not respond. Details of your right to cancel (returns and refunds) can be found in sections 8 &amp; 9 of <a href=\"http://www.ancestryshop.co.uk/tcs.php\" style=\"color:#afbc22\">Terms &amp; Conditions</a>.</p>
              <p>Kind regards,</p>
              <p>The Ancestry Shop Customer Team<br />
            </p></td>
          </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><table width=\"600\" border=\"0\" cellpadding=\"20\" style=\"font-size:9px\">
      <tr>
        <td align=\"left\" valign=\"top\"><p><strong>Your right to cancel:</strong> <br />
          We at The Ancestry Shop  want you to be delighted every time you shop with us. Occasionally though, we know you may want to return items. To read more about our Returns Policy please <a href=\"http://www.ancestryshop.co.uk/customer_service.php#returns\" style=\"color:#afbc22\">click here</a>. <br />
          It is important to us that you are satisfied with your Ancestry Shop experience. If you are not completely satisfied with our goods when you receive them you have the right to cancel your order up to 21 days after receipt of your product and receive a full order refund or exchange. We will make all refunds to the card used for payment as soon as possible which we would expect to be within 30 days of the date of cancellation.  The refund will be the cost of the returned goods together with the original delivery fee if the goods are being returned because they can be shown to have been faulty or damaged when they were received by you.  Please note that this does not apply to items personalised or made to your specification; audio or video recordings or computer software (such as CD-ROMs and DVDs) which you have opened, unwrapped or unsealed; newspapers, periodicals or magazines; or perishable goods. Please see our <a href=\"http://www.ancestryshop.co.uk/tcs.php\" style=\"color:#afbc22\">Returns & Refunds Policy</a> for more information. </p>
          <p>If you would like to return an item please contact us on 0845 688 5114 or email <a href=\"mailto:service@ancestryshop.co.uk\" style=\"color:#afbc22\">service@ancestryshop.co.uk</a> where we will be able to provide you with a return authorisation which will speed up your return and refund process.  It is your right to cancel your order within 21 days of receipt of the goods by notifying us of your intentions.  Please notify us by email at service@ancestryshop.co.uk or call us on 0845 688 5114. This \"Returns and refunds\" policy does not take away any rights you may have under consumer law.</p>
          <p>Use of ancestryshop.co.uk is subject to our <a href=\"http://www.ancestry.co.uk/legal/Terms.aspx\" style=\"color:#afbc22\">Terms and Conditions</a>  and privacy statement.<br />
          </p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>";

$mailbody_text="
======================================================

". date ("d F Y H:i") ."

Subject: The Ancestry Shop Order Confirmation

======================================================

Dear {$order_data['firstname']},

Thank you for your order. 

Please note that this email is only a confirmation of receipt of your order and your offer to purchase these items is not legally accepted until we either dispatch, or send you an email notifying you of the dispatch of your products. This email is confirmation you have placed the order. Please check the details below are correct. If there are any problems feel free to phone our customer service helpline on 0845 688 5114 or email us at service@ancestryshop.co.uk.



Order Number: {$order_data['niceordernum']}

Billing Address:
{$order_data['firstname']} {$order_data['lastname']} 
{$order_data['address1']}
{$order_data['address2']}
{$order_data['city']}
{$order_data['zipcode']}


Shipping Address:
{$order_data['sfirstname']} {$order_data['slastname']} 
{$order_data['saddress1']}
{$order_data['saddress2']}
{$order_data['scity']}
{$order_data['szipcode']}

Shipping Method:
{$order_data['description']} £{$order_data['price']} 

--------------------------------------------------------------------------------

Order Details:

".
 pad("Item Code", 20) . "\t" . pad("Name", 45) . "\t" . pad("Quan", 4) . "\t" . pad("Price", 6) . "\t" . pad("Discount", 8) . "\t" . pad("SubTotal", 8)
."
{$basketbody_text}                                                                                            

--------------------------------------------------------------------------------                                                                             

We recommend that you print off and retain the copy of this email for your records together with a copy of our Ancestry Shop Terms & Conditions.

This is an automated email please do not respond. Details of your right to cancel (returns and refunds) can be found in sections 8 & 9 of Terms & Conditions.

Kind regards,

The Ancestry Shop Customer Team

Your right to cancel: 
We at The Ancestry Shop  want you to be delighted every time you shop with us. Occasionally though, we know you may want to return items. To read more about our Returns Policy please click here [http://www.ancestryshop.co.uk/tcs.php] . 
It is important to us that you are satisfied with your Ancestry Shop experience. If you are not completely satisfied with our goods when you receive them you have the right to cancel your order up to 21 days after receipt of your product and receive a full order refund or exchange. We will make all refunds to the card used for payment as soon as possible which we would expect to be within 30 days of the date of cancellation.  The refund will be the cost of the returned goods together with the original delivery fee if the goods are being returned because they can be shown to have been faulty or damaged when they were received by you.  Please note that this does not apply to items personalised or made to your specification; audio or video recordings or computer software (such as CD-ROMs and DVDs) which you have opened, unwrapped or unsealed; newspapers, periodicals or magazines; or perishable goods.  Please see our Returns & Refunds Policy for more information [link].

If you would like to return an item please contact us on 0845 688 5114 or email service@ancestryshop.co.uk where we will be able to provide you with a return authorisation which will speed up your return and refund process.  It is your right to cancel your order within 21 days of receipt of the goods by notifying us of your intentions.  Please notify us by email at service@ancestryshop.co.uk or call us on 0845 688 5114. This \"Returns and refunds\" policy does not take away any rights you may have under consumer law.

Use of ancestryshop.co.uk is subject to our Terms and Conditions ( http://www.ancestry.co.uk/legal/Terms.aspx ) and privacy statement.";





    
$notice_text = "This is a multi-part message in MIME format.";
$plain_text = $mailbody_text;
$html_text =  $mailbody_html;

$semi_rand = md5(time());
$mime_boundary = "==MULTIPART_BOUNDARY_$semi_rand";
$mime_boundary_header = chr(34) . $mime_boundary . chr(34);

$to = $order_data['email'];
$from="service@ancestryshop.co.uk";
$subject = "The Ancestry Shop Order Confirmation";

$body = "$notice_text

--$mime_boundary
Content-Type: text/plain; charset=us-ascii
Content-Transfer-Encoding: 7bit

$plain_text

--$mime_boundary
Content-Type: text/html; charset=us-ascii
Content-Transfer-Encoding: 7bit

$html_text

--$mime_boundary--";

mail($to, $subject, $body,"From: " . $from . "\n" ."MIME-Version: 1.0\n" ."Content-Type: multipart/alternative;\n" ."     boundary=" . $mime_boundary_header);
}



/***** Promo functions for update.php **/

/*function Ancestry_Send_Email($mailbody_html,$mailbody_text,$to)
{
	$notice_text = "This is a multi-part message in MIME format.";
	$plain_text = $mailbody_text;
	$html_text =  $mailbody_html;
	
	$semi_rand = md5(time());
	$mime_boundary = "==MULTIPART_BOUNDARY_$semi_rand";
	$mime_boundary_header = chr(34) . $mime_boundary . chr(34);
	
	$from="service@ancestryshop.co.uk";
	$subject = "The Ancestry Shop Order Confirmation";
	
$body = "$notice_text
	
--$mime_boundary
Content-Type: text/plain; charset=us-ascii
Content-Transfer-Encoding: 7bit

$plain_text

--$mime_boundary
Content-Type: text/html; charset=us-ascii
Content-Transfer-Encoding: 7bit

$html_text

--$mime_boundary--";
	
	mail($to, $subject, $body,"From: " . $from . "\n" ."MIME-Version: 1.0\n" ."Content-Type: multipart/alternative;\n" ."     boundary=" . $mime_boundary_header);
		
} 

function promo_email($niceordernumber,$promo_type)
{
	// Collect the Order Header Details

	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	
	$mysql=array( );
	$mysql['niceordernumber']=mysql_real_escape_string($niceordernumber,$link);		
	// Performing SQL query
	$query = "SELECT tbl_order_header_promo.niceordernum, tbl_order_header_promo.order_date, tbl_order_header_promo.firstname, tbl_order_header_promo.lastname, tbl_order_header_promo.address1, tbl_order_header_promo.address2, tbl_order_header_promo.city, tbl_order_header_promo.zipcode, tbl_order_header_promo.email, tbl_order_header_promo.sfirstname, tbl_order_header_promo.slastname, tbl_order_header_promo.saddress1, tbl_order_header_promo.saddress2, tbl_order_header_promo.scity, tbl_order_header_promo.szipcode, tbl_shipping.description , tbl_shipping.price, tbl_order_header_promo.ordernumber,tbl_order_header_promo.state,tbl_order_header_promo.sstate
			  FROM tbl_order_header_promo
		  	  INNER JOIN tbl_shipping
		      ON tbl_order_header_promo.shipvia=tbl_shipping.code
		      WHERE tbl_order_header_promo.niceordernum = {$mysql['niceordernumber']};";

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	$order_data = mysql_fetch_array($result);
	
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
	
	
	$basketbody_text = "\n";
	$basketbody_html = "";

	switch($promo_type)
	{
		case "FREEUPGRADE":
			$basketbody_text = $basketbody_text . pad("UPGRADE", 20) . "\t" . pad("Family Tree Maker Upgrade", 45) . "\t" . pad("1", 4) . "\t" . pad("0.00", 6) . "\t" . pad("0" . "%", 8)  ."\t" . pad("0.00", 8) . "\n";
			$basketbody_html = $basketbody_html . "<tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\"><td width=\"50%\">" . "UPGRADE" . "</td>" . "<td align=\"right\" bgcolor=\"#F1EDC5\">"  . "Family Tree Maker Upgrade" . "</td>" . "<td align=\"right\" bgcolor=\"#F1EDC5\">" . "1" .  "</td>" . "<td align=\"right\" bgcolor=\"#F1EDC5\">" . "0.00"  ."</td><td align=\"right\" bgcolor=\"#F1EDC5\">" .  "0"  ."%" . "</td><td align=\"right\" bgcolor=\"#F1EDC5\">" . "0.00". "</td></tr>";
		break;
	}
	
	
	$mailbody_html="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
					<html xmlns=\"http://www.w3.org/1999/xhtml\">
					<head>
						<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<title>Untitled Document</title>
					</head>";
	
	$mailbody_html=$mailbody_html . 
	"<body>
	<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px\">
		<tr>
			<td bgcolor=\"#000000\">&nbsp;</td>
		</tr>
		<tr>
			<td><img src=\"http://www.ancestryshop.co.uk/images/shop_email_head4.gif\" width=\"600\" height=\"61\" /></td>
		</tr>
		<tr>
			<td bgcolor=\"afbc22\"><table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"20\" style=\"color:#FFFFFF\">
			<tr>
				<td width=\"52\"><strong>Sent:<br />Subject:</strong></td>
        		<td width=\"468\">". date ("d F Y H:i") ."<br />The Ancestry Shop Order Confirmation</td>
			</tr>
		</table>
		</td>
	  	</tr>
	  	<tr>
	 	<td><table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"20\">
     <tr>
	<td><p><strong>Dear ". $order_data['firstname'] .",</strong></p>
          <p>Thank you for your order.</p>
          <p>Please note that this email is only a confirmation of receipt of your order and your offer to purchase these items is not legally accepted until we either dispatch, or send you an email notifying you of the dispatch of your products. Please check the details below are correct. If there are any problems feel free to phone our customer service helpline on 0845 688 5114 or email us at service@ancestryshop.co.uk.</p></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align=\"center\" valign=\"top\">
      <table width=\"560\" border=\"0\" cellpadding=\"10\" cellspacing=\"3\">
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td colspan=\"2\"><strong>Order Number:</strong>". $order_data['niceordernum']. "</td>
          </tr>
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td width=\"50%\" bgcolor=\"#F1EDC5\"><p><strong>Billing Address:</strong></p>
              <p>" . $order_data['firstname']. " " . $order_data['lastname'] .  "<br />".
                $order_data['address1']."<br />".
                $order_data['address2']."<br />".
                $order_data['city']."<br />".
                $order_data['zipcode']."<br />
              </p></td>
            <td width=\"50%\"><p><strong>Shipping Address:</strong></p>
             <p>" . $order_data['sfirstname']. " " . $order_data['slastname'] .  "<br />".
                $order_data['saddress1']."<br />".
                $order_data['saddress2']."<br />".
                $order_data['scity']."<br />".
                $order_data['szipcode']."<br />
            </p></td>
          </tr>
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td colspan=\"2\"><strong>Shipping Method:</strong>". $order_data['description'] . "- &pound;".  $order_data['price'] ."</td>
          </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align=\"center\" valign=\"top\">
      <table width=\"560\" border=\"0\" cellspacing=\"3\" cellpadding=\"10\">
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td colspan=\"6\"><strong>Order details:</strong></td>
          </tr>
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td bgcolor=\"#F1EDC5\"><strong>Item Code</strong></td>
            <td width=\"50%\" bgcolor=\"#F1EDC5\"><strong>Name</strong></td>
            <td bgcolor=\"#F1EDC5\"><strong>Quan</strong></td>
            <td bgcolor=\"#F1EDC5\"><strong>Price</strong></td>
            <td bgcolor=\"#F1EDC5\"><strong>Discount</strong></td>
            <td bgcolor=\"#F1EDC5\"><strong>Subtotal</strong></td>
          </tr>
        ". $basketbody_html ."
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width=\"600\" border=\"0\" cellpadding=\"20\">
          <tr align=\"left\" valign=\"top\">
            <td><p>We recommend that you print off and retain the copy of this email for your records together with a copy of our Ancestry Shop <a href=\"http://www.ancestryshop.co.uk/tcs.php\" style=\"color:#afbc22\">Terms &amp; Conditions</a>.</p>
              <p>This is an automated email please do not respond. Details of your right to cancel (returns and refunds) can be found in sections 8 &amp; 9 of <a href=\"http://www.ancestryshop.co.uk/tcs.php\" style=\"color:#afbc22\">Terms &amp; Conditions</a>.</p>
              <p>Kind regards,</p>
              <p>The Ancestry Shop Customer Team<br />
            </p></td>
          </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><table width=\"600\" border=\"0\" cellpadding=\"20\" style=\"font-size:9px\">
      <tr>
        <td align=\"left\" valign=\"top\"><p><strong>Your right to cancel:</strong> <br />
          We at The Ancestry Shop  want you to be delighted every time you shop with us. Occasionally though, we know you may want to return items. To read more about our Returns Policy please <a href=\"http://www.ancestryshop.co.uk/customer_service.php#returns\" style=\"color:#afbc22\">click here</a>. <br />
          It is important to us that you are satisfied with your Ancestry Shop experience. If you are not completely satisfied with our goods when you receive them you have the right to cancel your order up to 21 days after receipt of your product and receive a full order refund or exchange. We will make all refunds to the card used for payment as soon as possible which we would expect to be within 30 days of the date of cancellation.  The refund will be the cost of the returned goods together with the original delivery fee if the goods are being returned because they can be shown to have been faulty or damaged when they were received by you.  Please note that this does not apply to items personalised or made to your specification; audio or video recordings or computer software (such as CD-ROMs and DVDs) which you have opened, unwrapped or unsealed; newspapers, periodicals or magazines; or perishable goods. Please see our <a href=\"http://www.ancestryshop.co.uk/tcs.php\" style=\"color:#afbc22\">Returns & Refunds Policy</a> for more information. </p>
          <p>If you would like to return an item please contact us on 0845 688 5114 or email <a href=\"mailto:service@ancestryshop.co.uk\" style=\"color:#afbc22\">service@ancestryshop.co.uk</a> where we will be able to provide you with a return authorisation which will speed up your return and refund process.  It is your right to cancel your order within 21 days of receipt of the goods by notifying us of your intentions.  Please notify us by email at service@ancestryshop.co.uk or call us on 0845 688 5114. This \"Returns and refunds\" policy does not take away any rights you may have under consumer law.</p>
          <p>Use of ancestryshop.co.uk is subject to our <a href=\"http://www.ancestry.co.uk/legal/Terms.aspx\" style=\"color:#afbc22\">Terms and Conditions</a>  and privacy statement.<br />
          </p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>";

$mailbody_text="
======================================================

". date ("d F Y H:i") ."

Subject: The Ancestry Shop Order Confirmation

======================================================

Dear {$order_data['firstname']},

Thank you for your order. 

Please note that this email is only a confirmation of receipt of your order and your offer to purchase these items is not legally accepted until we either dispatch, or send you an email notifying you of the dispatch of your products. This email is confirmation you have placed the order. Please check the details below are correct. If there are any problems feel free to phone our customer service helpline on 0845 688 5114 or email us at service@ancestryshop.co.uk.



Order Number: {$order_data['niceordernum']}

Billing Address:
{$order_data['firstname']} {$order_data['lastname']} 
{$order_data['address1']}
{$order_data['address2']}
{$order_data['city']}
{$order_data['zipcode']}


Shipping Address:
{$order_data['sfirstname']} {$order_data['slastname']} 
{$order_data['saddress1']}
{$order_data['saddress2']}
{$order_data['scity']}
{$order_data['szipcode']}

Shipping Method:
{$order_data['description']} £{$order_data['price']} 

--------------------------------------------------------------------------------

Order Details:

".
 pad("Item Code", 20) . "\t" . pad("Name", 45) . "\t" . pad("Quan", 4) . "\t" . pad("Price", 6) . "\t" . pad("Discount", 8) . "\t" . pad("SubTotal", 8)
."
{$basketbody_text}                                                                                            

--------------------------------------------------------------------------------                                                                             

We recommend that you print off and retain the copy of this email for your records together with a copy of our Ancestry Shop Terms & Conditions.

This is an automated email please do not respond. Details of your right to cancel (returns and refunds) can be found in sections 8 & 9 of Terms & Conditions.

Kind regards,

The Ancestry Shop Customer Team

Your right to cancel: 
We at The Ancestry Shop  want you to be delighted every time you shop with us. Occasionally though, we know you may want to return items. To read more about our Returns Policy please click here [http://www.ancestryshop.co.uk/tcs.php] . 
It is important to us that you are satisfied with your Ancestry Shop experience. If you are not completely satisfied with our goods when you receive them you have the right to cancel your order up to 21 days after receipt of your product and receive a full order refund or exchange. We will make all refunds to the card used for payment as soon as possible which we would expect to be within 30 days of the date of cancellation.  The refund will be the cost of the returned goods together with the original delivery fee if the goods are being returned because they can be shown to have been faulty or damaged when they were received by you.  Please note that this does not apply to items personalised or made to your specification; audio or video recordings or computer software (such as CD-ROMs and DVDs) which you have opened, unwrapped or unsealed; newspapers, periodicals or magazines; or perishable goods.  Please see our Returns & Refunds Policy for more information [link].

If you would like to return an item please contact us on 0845 688 5114 or email service@ancestryshop.co.uk where we will be able to provide you with a return authorisation which will speed up your return and refund process.  It is your right to cancel your order within 21 days of receipt of the goods by notifying us of your intentions.  Please notify us by email at service@ancestryshop.co.uk or call us on 0845 688 5114. This \"Returns and refunds\" policy does not take away any rights you may have under consumer law.

Use of ancestryshop.co.uk is subject to our Terms and Conditions ( http://www.ancestry.co.uk/legal/Terms.aspx ) and privacy statement.";

$to=$order_data['email'];

Ancestry_Send_Email($mailbody_html,$mailbody_text,$to);

}

/***************************************/


?>