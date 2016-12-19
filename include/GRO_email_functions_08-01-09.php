<?php
	/* GRO constants */
	define ('BIRTH','1');
	define ('MARRIAGE','2');
	define ('DEATH','3');

require_once ("classes/mysql_class.php");

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


function  GRO_email($ordernumber,$niceordernumber){
	
	//now uses the ordernumber (i.e. session id rather than niceordernumber)
	
	// Collect the Order Header Details

	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	
	
	
	$fields=array("GRO_orders_id","order_number","billing_address","shipping_address","order_date","GRO_tbl_shipping.description","GRO_tbl_shipping.price","email","phone","order_status","total_paid");
	$where=array("order_number={$ordernumber}");
	$join ="LEFT JOIN GRO_tbl_shipping on delivery_method=GRO_tbl_shipping.code";
	$order=$DB->getData("GRO_orders",$fields,$where,"",$join,"");
	$order_data=$order[0];
	
	$fields=array("first_name","surname","line_1","line_2","city","county","postcode","GRO_tbl_countries.country","title");
	$where=array("GRO_addresses_id={$order_data['billing_address']}");
	$join="LEFT JOIN GRO_tbl_countries on code=GRO_addresses.country";
	$address=$DB->getData("GRO_addresses",$fields,$where,"",$join,"");
	$billing_address=$address[0];
	
	$where=array("GRO_addresses_id={$order_data['shipping_address']}");
	$address=$DB->getData("GRO_addresses",$fields,$where,"",$join,"");
	$delivery_address=$address[0];
	
	$fields=array("certificate_id","certificate_type");
	$where=array("order_number={$order_data['order_number']}");
	$cert=$DB->getData("GRO_certificate_ordered",$fields,$where,"","","");
	
	$cert_ordered=$cert[0];
	
	$GRO_fields=array("GRO_index_year","GRO_index_quarter","GRO_index_district","GRO_index_volume","GRO_index_page","GRO_index_reg","GROI_known");
	$GRO_labels=array("Year","Quarter","District Name","Volume Number","Page Number","Reg");
	
	switch($cert_ordered['certificate_type'])
	{
		CASE BIRTH:
		$where=array("GRO_birth_certificates_id={$cert_ordered['certificate_id']}");
		$clean['cert_ordered']="Birth Certificate";
		$required_fields=array("birth_reg_year","birth_surname","forenames","DOB","birth_place","fathers_surname","fathers_forenames","mothers_maiden_surname","mothers_surname_birth","mothers_forenames","copies");
		$cert_details=$DB->getData("GRO_birth_certificates",$required_fields,$where);
		$labels=array("Year Birth Was Registerd","Surname at Birth","Forenames","DOB","Place of Birth","Father's Surname","Father's Forename","Mother's Maiden Surname","Mother's Surname at Time of Birth","Mother's Forename(s)");
		$GRO_details=$DB->getData("GRO_birth_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
		if($cert_details['birth_reg_year']<'1984')
		{
			$labels=array("Year Birth Was Registerd","Surname at Birth","Forenames","Place of Birth");
			
			//unset the unused variables
			//index number is used to access later on, so unset this as well as named index.
			unset($cert_details['DOB']);
			unset($cert_details[3]);
			
			unset($cert_details['fathers_surname']);
			unset($cert_details[5]);
			
			unset($cert_details['fathers_forenames']);
			unset($cert_details[6]);
			
			unset($cert_details['mothers_maiden_surname']);
			unset($cert_details[7]);
			
			unset($cert_details['mothers_surname_birth']);
			unset($cert_details[8]);
			
			unset($cert_details['mothers_forenames']);
			unset($cert_details[9]);
			
			//renumber the array so all numbers are sequential
			$cert_details=array_merge($cert_details);			
			
		}
		else
		{
			$labels=array("Year Birth Was Registerd","Surname at Birth","Forenames","DOB","Place of Birth","Father's Surname","Father's Forename","Mother's Maiden Surname","Mother's Surname at Time of Birth","Mother's Forename(s)");
			$cert_details['DOB']=$cert_details[3]=$DB->format_date($cert_details[3]);
		}

	break;
	CASE DEATH:
		$where=array("GRO_death_certificates_id={$cert_ordered['certificate_id']}");
		$clean['cert_ordered']="Death Certificate";
		$required_fields=array("registered_year","surname_deceased","forenames_deceased","death_date","relationship_to_deceased","death_age","fathers_surname","fathers_forenames","mothers_surname","mothers_forenames","copies");
		$cert_details=$DB->getData("GRO_death_certificates",$required_fields,$where);
		$labels=array("Year Death was Registered","Surname of Deceased","Forename(s) of Deceased","Date of Death","Relationship to the Deceased","Age at Death in Years","Father's Surname","Father's Forename(s)","Mother's Surname","Mother's Forename(s)");
		$GRO_details=$DB->getData("GRO_death_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
		$cert_details['death_date']=$cert_details[3]=$DB->format_date($cert_details[3]);

	break;
	CASE MARRIAGE:
		$where=array("GRO_marriage_certificates_id={$cert_ordered['certificate_id']}");
		$clean['cert_ordered']="Marriage Certificate";
		$required_fields=array("registered_year","mans_surname","mans_forenames","womans_surname","womans_forenames","copies");
		$labels=array("Year Marriage was Registered","Man's Surname","Man's Forenames","Woman's Surname","Woman's Forenames");
		$cert_details=$DB->getData("GRO_marriage_certificates",$required_fields,$where);
		$GRO_details=$DB->getData("GRO_marriage_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
	break;
	}
	$GRO_details=$GRO_details[0];
	$GRO_details['GRO_index_reg']=$GRO_details[5]=substr($DB->format_date($GRO_details[5]),3);
	
	$gro_order_number=$niceordernumber;
	
	//billing_address now holds all billing details
	//delivery_address now holds all delivery details
	//cert_details contains all info on the certifcate, labels contains the required label for each field
	//GRO_details contains all the generic GRO details
	
//need to populate cert_text/cert_html with the certificate details

	$cert_text="Certificate Ordered\t{$clean['cert_ordered']}";
	$cert_html="<tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\"><td  bgcolor=\"#F1EDC5\"><strong>Certificate Ordered</strong></td><td  bgcolor=\"#F1EDC5\">{$clean['cert_ordered']}</td></tr>";
	
	$cert_text=$cert_text . "\nCopies\t{$cert_details['copies']}";
	$cert_html=$cert_html . "<tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\"><td  bgcolor=\"#F1EDC5\"><strong>Copies</strong></td><td  bgcolor=\"#F1EDC5\">{$cert_details['copies']}</td></tr>";
		
	$count=0;
	foreach($labels as $label)
	{
		
	$cert_text=$cert_text."\n{$label}\t{$cert_details[$count]}";
	$cert_html=$cert_html."<tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\"><td><strong>{$label}</strong></td><td>{$cert_details[$count]}</td></tr>";

	
	$count++;
	}
	
	if($GRO_details['GROI_known']==1){
		$cert_text=$cert_text."GRO Index Details";
		$cert_html=$cert_html ."<tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\"><td colspan=\"2\"><strong>GRO Index Details</strong></td></tr>";
		
		$count=0;
		foreach($GRO_labels as $label)
		{
		$cert_text=$cert_text."\n{$label}\t{$GRO_details[$count]}";
		$cert_html=$cert_html."<tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\"><td><strong>{$label}</strong></td><td>{$GRO_details[$count]}</td></tr>";
		$count++;
		}
	}
	
	$cert_text=$cert_text."\nTotal Cost\t{$order_data['total_paid']}";
	$cert_html=$cert_html."<tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\"><td  bgcolor=\"#F1EDC5\"><strong>Total Cost</strong></td><td  bgcolor=\"#F1EDC5\">{$order_data['total_paid']}</td></tr>";
	
	
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
	<td><p><strong>Dear ". $billing_address['first_name'] .",</strong></p>
          <p>Thank you for your order.</p>
          <p>Please note that this email is confirmation of receipt of your order .  Unfortunately due to the personalisation of this service we can not cancel or amend an order once it has been placed.  If you have any queries please feel free to phone our customer service helpline on 0870 850 8752 or email us at service@ancestryshop.co.uk.</p></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align=\"center\" valign=\"top\">
      <table width=\"560\" border=\"0\" cellpadding=\"10\" cellspacing=\"3\">
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td colspan=\"2\"><strong>Order Number:</strong>". $gro_order_number. "</td>
          </tr>
          <tr align=\"left\" valign=\"top\" bgcolor=\"#F1EDC5\">
            <td width=\"50%\" bgcolor=\"#F1EDC5\"><p><strong>Billing Address:</strong></p>
              <p>" . $billing_address['title'] . " " . $billing_address['first_name']. " " . $billing_address['surname'] .  "<br />".
                $billing_address['line_1']."<br />".
                $billing_address['line_2']."<br />".
                $billing_address['city']."<br />".
                $billing_address['postcode']."<br />".
                $billing_address['country'] ."<br />
                
              </p></td>
            <td width=\"50%\"><p><strong>Shipping Address:</strong></p>
              <p>" . $delivery_address['title'] . " " . $delivery_address['first_name']. " " . $delivery_address['surname'] .  "<br />".
                $delivery_address['line_1']."<br />".
                $delivery_address['line_2']."<br />".
                $delivery_address['city']."<br />".
                $delivery_address['postcode']."<br />".
                $delivery_address['country'] ."<br />
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
            <td colspan=\"2\"><strong>Order details:</strong></td>
          </tr>
          
        ". $cert_html ."
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
          <p>If you would like to return an item please contact us on 0870 850 8752 or email <a href=\"mailto:service@ancestryshop.co.uk\" style=\"color:#afbc22\">service@ancestryshop.co.uk</a> where we will be able to provide you with a return authorisation which will speed up your return and refund process.  It is your right to cancel your order within 21 days of receipt of the goods by notifying us of your intentions.  Please notify us by email at service@ancestryshop.co.uk or call us on 0870 850 8752. This \"Returns and refunds\" policy does not take away any rights you may have under consumer law.</p>
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

Dear {$billing_address['first_name']},

Thank you for your order. 

Please note that this email is confirmation of receipt of your order .  Unfortunately due to the personalisation of this service we can not cancel or amend an order once it has been placed.  If you have any queries please feel free to phone our customer service helpline on 0870 850 8752 or email us at service@ancestryshop.co.uk.



Order Number: {$gro_order_number}

Billing Address:
{$billing_address['title']} {$billing_address['first_name']} {$billing_address['surname']} 
{$billing_address['line_1']}
{$billing_address['line_2']}
{$billing_address['city']}
{$billing_address['county']}
{$billing_address['postcode']}
{$billing_address['country']}



Shipping Address:
{$delivery_address['title']} {$delivery_address['first_name']} {$delivery_data['surname']} 
{$delivery_address['line_1']}
{$delivery_address['line_2']}
{$delivery_address['city']}
{$delivery_address['county']}
{$delivery_address['postcode']}
{$delivery_address['country']}

Shipping Method:
{$order_data['description']} £{$order_data['price']} 

--------------------------------------------------------------------------------

Order Details:

{$cert_text}                                                                                            

--------------------------------------------------------------------------------                                                                             

We recommend that you print off and retain the copy of this email for your records together with a copy of our Ancestry Shop Terms & Conditions.

This is an automated email please do not respond. Details of your right to cancel (returns and refunds) can be found in sections 8 & 9 of Terms & Conditions.

Kind regards,

The Ancestry Shop Customer Team

Your right to cancel: 
We at The Ancestry Shop  want you to be delighted every time you shop with us. Occasionally though, we know you may want to return items. To read more about our Returns Policy please click here [http://www.ancestryshop.co.uk/tcs.php] . 
It is important to us that you are satisfied with your Ancestry Shop experience. If you are not completely satisfied with our goods when you receive them you have the right to cancel your order up to 21 days after receipt of your product and receive a full order refund or exchange. We will make all refunds to the card used for payment as soon as possible which we would expect to be within 30 days of the date of cancellation.  The refund will be the cost of the returned goods together with the original delivery fee if the goods are being returned because they can be shown to have been faulty or damaged when they were received by you.  Please note that this does not apply to items personalised or made to your specification; audio or video recordings or computer software (such as CD-ROMs and DVDs) which you have opened, unwrapped or unsealed; newspapers, periodicals or magazines; or perishable goods.  Please see our Returns & Refunds Policy for more information [link].

If you would like to return an item please contact us on 0870 850 8752 or email service@ancestryshop.co.uk where we will be able to provide you with a return authorisation which will speed up your return and refund process.  It is your right to cancel your order within 21 days of receipt of the goods by notifying us of your intentions.  Please notify us by email at service@ancestryshop.co.uk or call us on 0870 850 8752. This \"Returns and refunds\" policy does not take away any rights you may have under consumer law.

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

?>