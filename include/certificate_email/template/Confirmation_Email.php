<?php
$billingAddress = "{$this->billingAddress['title']} {$this->billingAddress['first_name']}  {$this->billingAddress['surname']}<br />";
$billingAddress .= $this->billingAddress['line_1'] ? "{$this->billingAddress['line_1']}<br />" : '';
$billingAddress .= $this->billingAddress['line_2'] ? "{$this->billingAddress['line_2']}<br />" : '';
$billingAddress .= $this->billingAddress['city'] ? "{$this->billingAddress['city']}<br />" : '';
$billingAddress .= $this->billingAddress['county'] ? "{$this->billingAddress['county']}<br />" : '';
$billingAddress .= "{$this->billingAddress['postcode']}<br />";
$billingAddress .= "{$this->billingAddress['country']}<br />";

$deliveryAddress = "{$this->deliveryAddress['title']} {$this->deliveryAddress['first_name']}  {$this->deliveryAddress['surname']}<br />";
$deliveryAddress .= "{$this->deliveryAddress['line_1']}<br />";
$deliveryAddress .= $this->deliveryAddress['line_2'] ? "{$this->deliveryAddress['line_2']}<br />" : '';
$deliveryAddress .= $this->deliveryAddress['city'] ? "{$this->deliveryAddress['city']}<br />" : '';
$deliveryAddress .= $this->deliveryAddress['county'] ? "{$this->deliveryAddress['county']}<br />" : '';
$deliveryAddress .= "{$this->deliveryAddress['postcode']}<br />";
$deliveryAddress .= "{$this->deliveryAddress['country']}<br />";

$country = $this->regionInfo[$this->deliveryAddress['country']];

$imgDir = IMAGEROOT . '/email_template/';

$orderInfo = $this->htmlOrderInfo();

//estimate delivery date
$delDays = preg_replace('/[^0-9]/', '', $this->orderData['description']) *1;
date_default_timezone_set('Europe\London');
$startdate = $this->orderDetails['order_date'];
$holidays=array();
$estDelDate = $this->addBusinessDays($startdate,$delDays,$holidays,'jS F Y');

$htmlEmail = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ancestry.co.uk Certificate Confirmation Email</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<!--Fireworks CS3 Dreamweaver CS3 target.  Created Thu May 26 14:29:35 GMT+0100 (GMT Daylight Time) 2011-->
</head>
<body bgcolor="#ffffff">
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333333;">
<!-- fwtable fwsrc="' . $imgDir . 'Untitled" fwpage="Page 1" fwbase="Confirmation_Email.jpg" fwstyle="Dreamweaver" fwdocid = "405912819" fwnested="0" -->
  <tr>
   <td><img src="' . $imgDir . 'spacer.gif" width="14" height="1" border="0" alt="" /></td>
   <td><img src="' . $imgDir . 'spacer.gif" width="358" height="1" border="0" alt="" /></td>
   <td><img src="' . $imgDir . 'spacer.gif" width="266" height="1" border="0" alt="" /></td>
   <td><img src="' . $imgDir . 'spacer.gif" width="12" height="1" border="0" alt="" /></td>
   <td><img src="' . $imgDir . 'spacer.gif" width="1" height="1" border="0" alt="" /></td>
  </tr>

  <tr>
   <td colspan="4"><img name="Confirmation_Email_r1_c1" src="' . $imgDir . 'Confirmation_Email_r1_c1.jpg" width="650" height="77" border="0" id="Confirmation_Email_r1_c1" alt="" /></td>
   <td><img src="' . $imgDir . 'spacer.gif" width="1" height="77" border="0" alt="" /></td>
  </tr>
  <tr>
   <td rowspan="8" valign="top"><img name="Confirmation_Email_r2_c1" src="' . $imgDir . 'Confirmation_Email_r2_c1.jpg" width="14" height="1823" border="0" id="Confirmation_Email_r2_c1" alt="" /></td>
   <td bgcolor="#f7f7f7" style="padding-top:10px; padding-left:20px; padding-right:10px;"><p><strong>Dear ' . $this->billingAddress['first_name'] . ',</strong></p>
     <p>Thank you for ordering a birth, marriage or death certificate from Ancestry Shop. We hope it provides some fascinating information about your \'ancestors\' lives.</p>
    <p>As you delve deeper into your family history, you’ll probably want to order many more certificates. For example, have you tried finding this same relative’s other records, to fill in the rest of their life story?</p></td>
   <td valign="top"><img name="Confirmation_Email_r2_c3" src="' . $imgDir . 'Confirmation_Email_r2_c3.jpg" width="266" height="274" border="0" id="Confirmation_Email_r2_c3" alt="" /></td>
   <td rowspan="8" valign="top"><img name="Confirmation_Email_r2_c4" src="' . $imgDir . 'Confirmation_Email_r2_c4.jpg" width="12" height="1823" border="0" id="Confirmation_Email_r2_c4" alt="" /></td>
   <td><img src="' . $imgDir . 'spacer.gif" width="1" height="274" border="0" alt="" /></td>
  </tr>
  <tr>
   <td colspan="2"><div style="border-top:#b7bb00 1px solid; border-bottom:#e98300 1px solid;"><table width="90%" border="0" align="center" cellpadding="2" cellspacing="6">
     <tr>
       <td colspan="2"><strong>Here are the details of your order for your records:</strong></td>
       </tr>
     <tr>
       <td width="58%"><strong>Order Number: </strong></td>
       <td width="42%">' . $this->niceOrderRef . '</td>
     </tr>
     <tr>
       <td><strong>Shipping method:</strong></td>
       <td>' . $this->orderDetails['description'] . '</td>
     </tr>
     <tr>
       <td><strong>Digital Copy ordered:</strong></td>
       <td>' . $this->orderDetails['scan_and_send'] . '</td>
     </tr>
     <tr>
       <td colspan="2" bgcolor="#e98300"> <table width="100%" border="0" cellspacing="4" cellpadding="4">
         <tr>
           <td width="58%"><font color="#ffffff"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Estimated delivery date:</strong></font></td>
           <td width="42%"><font color="#ffffff"><strong>' . $estDelDate . '</strong></font></td>
         </tr>
       </table></td>
       </tr>
     <tr>
       <td><strong>Total cost:</strong></td>
       <td><font color="#853824"><strong>' . $this->currency($this->orderDetails['price']) . '</strong></font></td>
     </tr>
   </table></div></td>
   <td><img src="' . $imgDir . 'spacer.gif" width="1" height="169" border="0" alt="" /></td>
  </tr>
  <tr>
   <td colspan="2" valign="middle"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="4">
     <tr>
       <td width="57%" valign="top" style="line-height:160%;"><strong>Billing address:</strong><br />
         ' . $billingAddress . '
       <td width="43%" valign="top" style="line-height:160%;"><strong>Shipping address:</strong><br />
         ' . $deliveryAddress . '
     </tr>
   </table></td>
   <td rowspan="2"><img src="' . $imgDir . 'spacer.gif" width="1" height="232" border="0" alt="" /></td>
  </tr>
  <tr>
    <td colspan="2" valign="bottom"><img src="' . $imgDir . 'dotted.jpg" alt="" width="624" height="7" /></td>
  </tr>
  <tr>
   <td colspan="2" valign="middle" bgcolor="#fbecd6">
   <table width="90%" border="0" align="center" cellpadding="0" cellspacing="4">
        <tr>
        <td width="57%" valign="top" style="line-height:160%;"><strong>Type of certificate:</strong><br /></td>
        <td width="43%" valign="top" style="line-height:160%;"><span style="color:#e98300;"><strong>' . $this->certificate . '</strong></span><br /></td>
        </tr>
        <tr>
        <td valign="top" style="line-height:160%;"><strong>Copies:</strong> </td>
        <td valign="top" style="line-height:160%;">' . $this->orderDetails['copies'] . '</td>
        </tr>
    ' . $orderInfo . '
   </table></td>
   <td><img src="' . $imgDir . 'spacer.gif" width="1" height="296" border="0" alt="" /></td>
  </tr>
  <tr>
   <td colspan="2" valign="top"><div style="border-top:#e98300 1px solid; padding:20px;"><p>We are not unfortunately able to offer refunds for certificates as each one is ordered to your specification.  This does not affect your statutory rights and our duty to supply goods that are in conformity with your order.</p>
     <p>We recommend that you print and keep a copy of this email together with the <a href="http://ancestryshop.co.uk/tcs" style="text-decoration:none;"><span style="color:#527200;">Ancestry Shop Terms and Conditions.</span></a> You’ll find details of your right to cancel (returns and refunds) in sections eight and nine of the Terms and Conditions.</p>
     <p>This is an automated email, so please don’t reply directly. If you have any queries or need help with your research, please call our Member Services advisors on 0845 688 5114 or email us at <a href="mailto:service@ancestryshop.co.uk" style="text-decoration:none;"><span style="color:#527200;">service@ancestryshop.co.uk.</span></a></p>
    <p>Enjoy uncovering your family history, and don’t forget to use your special code to save 10% on future certificate orders.</p>
    <p><span style="color:#527200;"><strong>The Ancestry Shop Team</strong></span></p>
   </div><img name="Confirmation_Email_r7_c2" src="' . $imgDir . 'Confirmation_Email_r7_c2.jpg" width="624" height="69" border="0" id="Confirmation_Email_r7_c2" alt="" /></td>
   <td><img src="' . $imgDir . 'spacer.gif" width="1" height="320" border="0" alt="" /></td>
  </tr>

  <tr>
   <td colspan="2" valign="top"><div style="padding-left:20px; padding-right:20px; padding-bottom:20px;"><p>Use of Ancestryshop.co.uk is subject to our <a href="http://ancestryshop.co.uk/tcs" style="color:#333333;">Terms and Conditions</a> and <a href="http://ancestryshop.co.uk/privacy_policy" style="color:#333333;">Privacy Statement.</a></p>
     <p>Ancestryshop.co.uk is operated by Ancestry.com UK (Commerce) Limited a company incorporated in England and whose principal office is at 3rd Floor, Waterfront Building, Hammersmith, Embankment , Chancellors Road, London, W6 9RU. Questions? Comments? Please don not reply to this email as we cannot respond to messages sent to this address. Contact us by calling us on 0345 688 5114.</p>
    <p>&copy; 2014 Ancestry.com</p></div></td>
   <td><img src="' . $imgDir . 'spacer.gif" width="1" height="463" border="0" alt="" /></td>
  </tr>
</table>
</body>
</html>
';


$textEmail = explode("\r\n", strip_tags($htmlEmail));
foreach($textEmail as $k => $v){
    if(!strlen(trim($v))){
        unset($textEmail[$k]);
    } else {
        $textEmail[$k] = trim($v);
    }    
}
$textEmail = array_filter($textEmail);
$textEmail = implode("\r\n\r\n", $textEmail);
