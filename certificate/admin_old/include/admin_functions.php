<?php 

//email functions
function email_from_template($html_template_file,$text_template_file=null,$data,$variable_encloser="%")
{
	$emails=array();
	
	$keys=array_keys($data);
	
	$html=file_get_contents  ($html_template_file);
	if(isset($text_template_file))
		$txt=file_get_contents  ($text_template_file);
	
	foreach($keys as $key)
	{
		$search = $variable_encloser. $key .  $variable_encloser;
		$html= str_replace  ( $search ,  $data[$key]  , $html  );
		if(isset($txt))
			$txt= str_replace  ( $search ,  $data[$key]  , $txt  );
	}
	
	$emails['html']=$html;
	if(isset($txt))
		$emails['text']=$txt;
	
	return $emails;
}


function send_email($to,$from,$subject,$mailbody_text,$mailbody_html){

$notice_text = "This is a multi-part message in MIME format.";
$plain_text = $mailbody_text;
$html_text =  $mailbody_html;

$semi_rand = md5(time());
$mime_boundary = "==MULTIPART_BOUNDARY_$semi_rand";
$mime_boundary_header = chr(34) . $mime_boundary . chr(34);



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

/* Test code*/
/*Multipart currently disabled due to problems with hotmail addresses*/
//$headers="From: " . $from . "\n" ."MIME-Version: 1.0\n" ."Content-Type: multipart/alternative;\n" ."     boundary=" . $mime_boundary_header;
//$headers="From: " . $from . "\n" ."MIME-Version: 1.0\n" ."Content-Type: multipart/alternative;" ." boundary=" . $mime_boundary_header ."";

//will only send the html version of the email with this code below
//html
$headers="From: " . $from . "\n";
$headers=$headers . "Content-Type: text/html; charset=us-ascii\n";
$headers=$headers . "Content-Transfer-Encoding: 7bit\n";
$body= $html_text;

//text

/*
$headers="From: " . $from . "\n";
$headers=$headers . "Content-Type: text/plain; charset=us-ascii\n";
$headers=$headers . "Content-Transfer-Encoding: 7bit\n";
$body= $plain_text;*/
/*
echo "Email message<br />";
echo $headers;
echo $body;
echo "End of email message<br />";
*/


mail($to, $subject, $body,$headers);

}


function SendCompletedEmail($order_number)
{
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	
	//get data for order 
	$fields=array("GRO_orders.GRO_orders_id","order_number","billing_address","shipping_address","order_date","GRO_tbl_shipping.description","GRO_tbl_shipping.price","email","phone","order_status","st_ref","GRO_ref","GRO_date","completed_date","discount","discount_code");
	$where=array("GRO_orders.GRO_orders_id={$order_number}");
	$join ="LEFT JOIN GRO_tbl_shipping on delivery_method=GRO_tbl_shipping.code LEFT JOIN GRO_orders_extra ON GRO_orders.GRO_orders_id=GRO_orders_extra.GRO_orders_id ";
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
	
	
	
	//check to see if it is a UK order continue if so
	if($billing_address['country']=='United Kingdom')
	{	
		$data['BASE_URL']="http://" .$_SERVER['HTTP_HOST'];
		$data['IMG_DIR']=$data['BASE_URL'] . '/certificate/images/order_complete_email';
		//proccess into data array for email function
		
		$data['order_number']=$order_data['GRO_orders_id'];
		
		switch($cert_ordered['certificate_type'])
		{
			CASE BIRTH:
				$data['order_number'] = "BIRTH_CERTIFICATE_" . $data['order_number'];
			break;
		
			CASE DEATH:
				$data['order_number'] = "DEATH_CERTIFICATE_" . $data['order_number'];
			break;
				
			CASE MARRIAGE:
				$data['order_number'] = "MARRIAGE_CERTIFICATE_" . $data['order_number'];
			break;
		}
		
		
		
		$data['first_name']=$billing_address['first_name'];
		$data['order_date']=date("jS F",strtotime($order_data['order_date']));
		$data['shipping_name']=$delivery_address['first_name'] . " " . $delivery_address['surname'];
		$data['shipping_line1']=$delivery_address['line_1'];
		$data['shipping_line2']=$delivery_address['line_2'];
		$data['shipping_city']=$delivery_address['city'];
		$data['shipping_county']=$delivery_address['county'];
		$data['shipping_postcode']=$delivery_address['postcode'];
		$data['shipping_country']=$delivery_address['country'];
			
		//call email function
		$html_template_file="email_template/order_complete.html";
				
		$emails= email_from_template($html_template_file,null,$data,$variable_encloser="%");
			
		//setup to/from/subject etc
			
		$to=$order_data['email'];
		$subject="Your Ancestry Shop Certificate order has been processed";
	
		//echo $emails['html'];
			
		send_email($to,SEND_EMAIL_FROM,$subject,"",$emails['html']);
		
		return true;
	}	
}

function descriptive_status($status_code)
{
	switch($status_code)
	{
		case 1:
			return "Awaiting Proccessing";
		break;
		case 2:
			return "Pending With GRO";
		break;
		case 3:
			return "Order Complete";
		break;
		case 4:
			return "Cancelled";
		break;
		
	}
}

function descriptive_event($event)
{
	switch($event)
	{
		case 1:
			return "Order Status Reset";
		case 2:
			return "Sent to GRO";
		break;
		case 3:
			return "Order Completed";
		break;
		case 4:
			return "Order Cancelled";
		break;
		case 5:
			return "Note Added";
		break;
		case 6:
			return "Note Deleted";
		break;
	}
}

?>
