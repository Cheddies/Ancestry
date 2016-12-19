<?php include "logincheck.php"?>
<?php


require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once('fpdf/fpdf_fit.php');
require_once('include/pdf_functions.php');

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();


$clean=array();
$error=false;
$query_string="error=1";
$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$errors=array();
$_SESSION['errors']="";
	

$form_fields=array( array ("name" => "order_id","display_name" => "Order ID","length"=>"6","reg_ex"=>"","required"=>true));
	
$errors = process_form($form_fields,$_GET,&$clean);
	
if(sizeof($errors)>0)
{
	$_SESSION['errors']=serialize($errors);
	header('location:index.php');
	exit();
}
else
{
	$fields=array("GRO_orders_id","order_number","billing_address","shipping_address","order_date","GRO_tbl_shipping.description","GRO_tbl_shipping.price","email","phone","order_status","st_ref","st_autho");
	$where=array("GRO_orders_id={$clean['order_id']}");
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
		$labels=array("Year Birth Was Registerd","Surname at Birth","Forenames","DOB","Place of Birth","Father's Surname","Father's Forename","Mother's Maiden Surname","Mother's Surname at Time of Birth","Mother's Forename(s)","Copies");
		$GRO_details=$DB->getData("GRO_birth_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
		$cert_details['DOB']=$cert_details[3]=$DB->format_date($cert_details[3]);

	break;
	CASE DEATH:
		$where=array("GRO_death_certificates_id={$cert_ordered['certificate_id']}");
		$clean['cert_ordered']="Death Certificate";
		$required_fields=array("registered_year","surname_deceased","forenames_deceased","death_date","relationship_to_deceased","death_age","fathers_surname","fathers_forenames","mothers_surname","mothers_forenames","copies");
		$cert_details=$DB->getData("GRO_death_certificates",$required_fields,$where);
		$labels=array("Year Death was Registered","Surname of Deceased","Forename(s) of Deceased","Date of Death","Relationship to the Deceased","Age at Death in Years","Father's Surname","Father's Forename(s)","Mother's Surname","Mother's Forename(s)","Copies");
		$GRO_details=$DB->getData("GRO_death_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
		$cert_details['death_date']=$cert_details[3]=$DB->format_date($cert_details[3]);

	break;
	CASE MARRIAGE:
		$where=array("GRO_marriage_certificates_id={$cert_ordered['certificate_id']}");
		$clean['cert_ordered']="Marriage Certificate";
		$required_fields=array("registered_year","mans_surname","mans_forenames","womans_surname","womans_forenames","copies");
		$labels=array("Year Marriage was Registered","Man's Surname","Man's Forenames","Woman's Surname","Woman's Forenames","Copies");
		$cert_details=$DB->getData("GRO_marriage_certificates",$required_fields,$where);
		$GRO_details=$DB->getData("GRO_marriage_certificates",$GRO_fields,$where);
		$cert_details=$cert_details[0];
	break;
	}
	$GRO_details=$GRO_details[0];
	$GRO_details['GRO_index_reg']=$GRO_details[5]=substr($DB->format_date($GRO_details[5]),3);
	
	$order_number = $clean['order_id'];

	define("COMPANY_ADDRESS", "Ancestry Shop, PO Box 150, Sandbach, Cheshire, CW11 3WB	                 Phone: 0870 850 8752");
	define("LOGO", "ancestry_logo_invoice.jpg");

$pdf=new FPDF_CellFit("P","mm","A4");
$pdf->SetAuthor("Acestry Shop");
$pdf->SetSubject("Ancestry Shop Invoice");




	//call new page to produce the header
	$PrintToday= date("d-m-Y");
	$Order=$order_data['GRO_orders_id'];
	$PageNum=1;
	
	NewPage($pdf,$PageNum,$PrintToday,$Order,$billing_address,$delivery_address);	

	
	$LineCount=0;
	$OrderTotal=0;
	$VATTotal=0;
	
	$pdf->ln(5);
	
	$pdf->CellFitScale(10,5,"1",1,"","",1);
	$pdf->CellFitScale(30,5,"CERTIFICATE",1,"","",1);
	$pdf->CellFitScale(81,5,$clean['cert_ordered'],1,"","",1);
	
	$VAT=$order_data['price']-($order_data['price']/1.175);
	
	$VATTotal=$VATTotal+$VAT;
	
	$pdf->CellFitScale(22,5,sprintf('%.2f', $order_data['price']),1,"","R",1);
	$pdf->CellFitScale(25,5,sprintf('%.2f', $order_data['price']),1,"","R",1);
	$pdf->CellFitScale(22,5,sprintf('%.2f', $VAT),1,"","R",1);
	
	
	$OrderTotal=$OrderTotal + $order_data['price'];
	
	if($cert_details['copies']>1)
	{
		$pdf->ln(5);
		
		$extra_copies=$cert_details['copies'] - 1;
		$extra_cost=CERT_COST*$extra_copies;
		
		$OrderTotal=$OrderTotal + $extra_cost;
		
		$pdf->CellFitScale(10,5,$extra_copies,1,"","",1);
		$pdf->CellFitScale(30,5,"COPIES",1,"","",1);
		$pdf->CellFitScale(81,5,"Extra Copies",1,"","",1);
		
		$VAT=$extra_cost-($extra_cost/1.175);
		$VATTotal=$VATTotal+$VAT;
		
		$pdf->CellFitScale(22,5,sprintf('%.2f', CERT_COST),1,"","R",1);
		$pdf->CellFitScale(25,5,sprintf('%.2f', $extra_cost),1,"","R",1);
		$pdf->CellFitScale(22,5,sprintf('%.2f', $VAT),1,"","R",1);
	
	}
	
	
	/*while($Items_line=mysql_fetch_array($ItemsResult))
	{
		
		$LineCount++;
		
		$pdf->ln(5);
		$qty=$Items_line["quantity"];
		$price=$Items_line["price"];
		$amount=round(($qty*$price),2);
		$ProductCode=$Items_line["itemcode"];
		$OrderTotal=$amount+$OrderTotal;
		$VAT=GetVAT($ProductCode,$amount);
		
		$VATTotal=$VATTotal+$VAT;
		
		$pdf->CellFitScale(10,5,$qty,1,"","",1);
		$pdf->CellFitScale(30,5,$ProductCode,1,"","",1);
		$pdf->CellFitScale(81,5,$Items_line["name"],1,"","",1);
		
		$pdf->CellFitScale(22,5,sprintf('%.2f', $price),1,"","R",1);
		$pdf->CellFitScale(25,5,sprintf('%.2f', $amount),1,"","R",1);
		$pdf->CellFitScale(22,5,sprintf('%.2f', $VAT),1,"","R",1);
		
		
		
			
		if ($LineCount==17)
		{
			EndPage($pdf,$CustomerDetails);
			$PageNum++;
			NewPage($pdf,$PageNum,$PrintToday,$Order,$CustomerDetails);	
			$LineCount=0;
		}
		
		
	}*/
	//need to find postage

	$FullTotal=$OrderTotal+$Postage;
	//Print out a total for the order
	$pdf->SetFont('Arial','B',10);
	$pdf->ln(5);
	$pdf->CellFitScale(10,5,"",1,"","",1);
	$pdf->CellFitScale(30,5," ",1,"","",1);
	$pdf->CellFitScale(128,5,"VAT Total £",1,"","R",1);

	$pdf->CellFitScale(22,5,sprintf('%.2f', $VATTotal),1,"","R",1);

	
	$pdf->ln(5);
	$pdf->ln(5);
	//$pdf->CellFitScale(10,5,"1",1,"","",1);
	//$pdf->CellFitScale(30,5," ",1,"","",1);
	//$pdf->CellFitScale(103,5,"GOODS INVOICE TOTAL (NET) £",1,"","R",1);
	//$pdf->CellFitScale(25,5,sprintf('%.2f', $OrderTotal),1,"","R",1);	
	$pdf->ln(5);
	//$pdf->CellFitScale(10,5,"1",1,"","",1);
	//$pdf->CellFitScale(30,5," ",1,"","",1);
	
	//$pdf->CellFitScale(103,5,"CARRIAGE & HANDLING (NET) ($ShippingDesc) £",1,"","R",1);
	//$pdf->CellFitScale(25,5,sprintf('%.2f', $Postage),1,"","R",1);	
	
	
	if(substr($order_data['st_autho'],0,2)=='SV')
		$PaymentMethod ="UKASH Voucher" ;
	else
		$PaymentMethod ="Credit/Debit Card" ;		
		
	
	$pdf->ln(5);
	$pdf->CellFitScale(10,5,"",1,"","",1);
	$pdf->CellFitScale(30,5," ",1,"","",1);
	$pdf->CellFitScale(103,5,"Invoice Total £",1,"","R",1);
	$pdf->CellFitScale(25,5,sprintf('%.2f', $FullTotal),1,"","R",1);	
	$pdf->ln(5);
	$pdf->CellFitScale(143,5,"Payment Method:" ,1,"","R",1);	
	$pdf->CellFitScale(25,5,$PaymentMethod,1,"","R",1);	 
	//
	$pdf->SetFont('Arial','',10);
	
	EndPage($pdf,$CustomerDetails,$GiftMessage);
	
	
	
	
	$pdf->Output();
}

?>