<?php
//include("Mail.php");

//require_once ("Zend/Mail.php");

	
/*ini_set("SMTP", "192.168.222.165");
ini_set("sendmail_from", "service@internetlogistics.com");
*/


function formatcurrencytext($value) {
	$currency = sprintf('%.2f', $value);
	$currency = "£".$currency;
	return($currency);
}

function formatcurrencyhtml($value) {
	$currency = sprintf('%.2f', $value);
	$currency = "&pound;".$currency;
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
	$query = "SELECT tbl_order_header.niceordernum, tbl_order_header.order_date, tbl_order_header.firstname, tbl_order_header.lastname, tbl_order_header.address1, tbl_order_header.address2, tbl_order_header.city, tbl_order_header.zipcode, tbl_order_header.email, tbl_order_header.sfirstname, tbl_order_header.slastname, tbl_order_header.saddress1, tbl_order_header.saddress2, tbl_order_header.scity, tbl_order_header.szipcode, tbl_shipping.description , tbl_shipping.price, tbl_order_header.ordernumber,tbl_order_header.state,tbl_order_header.sstate,total_paid
			  FROM tbl_order_header
		  	  INNER JOIN tbl_shipping
		      ON tbl_order_header.shipvia=tbl_shipping.code
		      WHERE tbl_order_header.niceordernum = {$mysql['niceordernumber']};";

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

	while ($line = mysql_fetch_array($basketresult, MYSQL_ASSOC)) {
		$basketbody_text = $basketbody_text . pad($line["itemcode"], 20) . "\t" . pad($line["name"], 45) . "\t" . pad($line["quantity"], 4) . "\t" . pad(formatcurrencytext($line["price"]), 6) . "\t" . pad($line["discount"] . "%", 8)  ."\t" . pad(formatcurrencytext($line["subtotal"]), 8) . "\n";
		$basketbody_html = $basketbody_html . "<tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\"><td width=\"50%\">" . $line["itemcode"] . "</td>" . "<td align=\"right\" bgcolor=\"#F1EDC5\">"  . $line["name"] . "</td>" . "<td align=\"right\" bgcolor=\"#F1EDC5\">" . $line["quantity"] .  "</td>" . "<td align=\"right\" bgcolor=\"#F1EDC5\">" . formatcurrencyhtml($line["price"]) ."</td><td align=\"right\" bgcolor=\"#F1EDC5\">" .  $line["discount"] ."%" . "</td><td align=\"right\" bgcolor=\"#F1EDC5\">" . formatcurrencyhtml($line["subtotal"]). "</td></tr>";

	}
	
	$basketbody_text = $basketbody_text . "\n Order Total:" . formatcurrencytext($order_data['total_paid']) . "\n";
	$basketbody_html = $basketbody_html . "<tr align=\"right\" valign=\"top\" bgcolor=\"#F1EDC5\" > <td colspan=\"6\"> <strong>Order Total:</strong> " . formatcurrencyhtml($order_data['total_paid']) . "</td></tr>";
	
	
	
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
          <p>Please note that this email is only a confirmation of receipt of your order and your offer to purchase these items is not legally accepted until we dispatch your products. Please check the details below are correct. If there are any problems feel free to phone our customer service helpline on 0345 688 5114 or email us at service@ancestryshop.co.uk.</p></td>
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
			<p></p>
            <p>This is an automated email please do not respond.</p>
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
          It is important to us that you are satisfied with your Ancestry Shop experience. If you are not completely satisfied with our goods you have the right to cancel your order up to 21 days after receipt of your product and receive a full order refund or exchange. We will make all refunds to the card used for payment as soon as possible and within 14 days of the date of cancellation. The refund will be the cost of the returned goods together with the original delivery fee. Please note that this does not apply to items personalized or made to your specification (including Birth, Marriage, Death certificates), audio or video recordings or computer software (such as CD-ROMs and DVDs) which you have unsealed, newspapers, periodicals or magazines, or perishable goods. <br />
          Please note that you will be responsible for the costs of returning the items to us unless we delivered the item to you in error or if the item is damaged or defective.<br />If you would like to return an item please contact us on 0345 688 5114, email <a href=\"mailto:service@ancestryshop.co.uk\" style=\"color:#afbc22\">service@ancestryshop.co.uk</a> or fill in and return to us this <a href=\"http://www.ancestryshop.co.uk/include/Return_Form.pdf\" target=\"_blank\">form</a>. We will provide you with a return authorisation which will speed up your return and refund process.  You should then send us the goods in their original condition and packaging to the address in the 'Contact Us' at section 2 above marked 'Returns'. We reserve the right to refuse returns or to charge you our fees and expenses if the product is received otherwise than in accordance with this requirement.</p>
		  <p>As a consumer, you have legal rights in relation to goods that are faulty or not as described.  We are under a legal duty to supply goods that are in conformity with these terms and conditions. This 'Returns and refunds' policy does not take away any such rights.<br/><strong>Recommended System Requirements - Windows</strong><ul><li>Windows&reg XP, Vista&reg (32-bit or 64-bit), Windows&reg 7 (32-bit or 64-bit) or Windows&reg 8 (32-bit or 64-bit)</li><li>Hard disk space: 675 MB for installation</li><li>Memory: 1GB RAM</li><li>Display 1024 x 768 resolution</li><li>DVD Drive</li><li>All online features require Internet access</li></ul><strong>Recommended System Requirements - MAC</strong><ul><li>Mac OS X 10.6 or later, including OS X 10.9 Mavericks</li><li>Intel-based Mac</li><li>DVD Drive</li><li>All online features require Internet access</li></ul></p>
          <p>Use of Ancestryshop.co.uk is subject to our <a href=\"https://www.ancestryshop.co.uk/tcs.php\" style=\"color:#444443;\">Terms and Conditions</a> and <a href=\"https://www.ancestryshop.co.uk/privacy_policy.php\" style=\"color:#444443;\">Privacy Statement</a>.<br />Ancestryshop.co.uk is operated by Ancestry.com UK (Commerce) Limited a company incorporated in England and whose principal office is at 3rd Floor, Waterfront Building, Hammersmith, Embankment , Chancellors Road, London, W6 9RU. Questions? Comments? Please don not reply to this email as we cannot respond to messages sent to this address. Contact us by calling us on 0345 688 5114.<br />&copy; 2016 Ancestry.com<br />
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

Please note that this email is only a confirmation of receipt of your order and your offer to purchase these items is not legally accepted until we dispatch your products. Please check the details below are correct. If there are any problems feel free to phone our customer service helpline on 0345 688 5114 or email us at service@ancestryshop.co.uk



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

This is an automated email please do not respond.

Kind regards,

The Ancestry Shop Customer Team

Your right to cancel: 
It is important to us that you are satisfied with your Ancestry Shop experience. If you are not completely satisfied with our goods you have the right to cancel your order up to 21 days after receipt of your product and receive a full order refund or exchange. We will make all refunds to the card used for payment as soon as possible and within 14 days of the date of cancellation. The refund will be the cost of the returned goods together with the original delivery fee. Please note that this does not apply to items personalized or made to your specification (including Birth, Marriage, Death certificates), audio or video recordings or computer software (such as CD-ROMs and DVDs) which you have unsealed, newspapers, periodicals or magazines, or perishable goods.

Please note that you will be responsible for the costs of returning the items to us unless we delivered the item to you in error or if the item is damaged or defective.

If you would like to return an item please contact us on 0345 688 5114, email service@ancestryshop.co.uk or fill in and return to us this form [http://www.ancestryshop.co.uk/include/Return_Form.pdf] . We will provide you with a return authorisation which will speed up your return and refund process. You should then send us the goods in their original condition and packaging to the address in the 'Contact Us' at section 2 above marked 'Returns'. We reserve the right to refuse returns or to charge you our fees and expenses if the product is received otherwise than in accordance with this requirement.

As a consumer, you have legal rights in relation to goods that are faulty or not as described. We are under a legal duty to supply goods that are in conformity with these terms and conditions. This 'Returns and refunds' policy does not take away any such rights.

Recommended System Requirements for Windows
Windows XP, Vista (32 bit or 64 bit), Windows 7 (32 bit or 64 bit) or Windows 8 (32 bit or 64 bit)
Hard disk space 675 MB for installation
Memory 1GB RAM
Display 1024 x 768 resolution
DVD Drive
All online features require Internet access

Recommended System Requirements for MAC
Mac OS X 10.6 or later, including OS X 10.9 Mavericks
Intel-based Mac
DVD Drive
All online features require Internet access

Use of Ancestryshop.co.uk is subject to our Terms and Conditions and Privacy Statement.

Ancestryshop.co.uk is operated by Ancestry.com UK (Commerce) Limited a company incorporated in England and whose principal office is at 3rd Floor, Waterfront Building, Hammersmith, Embankment , Chancellors Road, London, W6 9RU. Questions? Comments? Please don not reply to this email as we cannot respond to messages sent to this address. Contact us by calling us on 0345 688 5114.

2016 Ancestry.com";





    
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



//old header code
 		
/*
	$recipients = $order_data['email'];
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$headers .= 'From: service@ancestryshop.co.uk' . "\r\n" .
'Reply-To: service@ancestryshop.co.uk' . "\r\n" .
'X-Mailer: PHP/' . phpversion();
		
mail ( $recipients , "The Ancestry Shop Order Confirmation"  , $body ,$headers);
*/
}


sendEmailConfirmation_html($clean['niceodrnum']);


?>