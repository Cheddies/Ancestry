<?php

function NewPage($pdf,$PageNum,$Today,$Order,$billing_address,$delivery_address)
{
	$pdf->AddPage();
	//$pdf->Image("logo.png", 10,10,40);
	//$pdf->Image("logo2.png", 75,2,60);
	$pdf->Image("images/ancestry_logo_invoice.jpg", 5,2,100);
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(175,188,34);
	
	$pdf->write("10","Ancestry Shop, PO Box 150, Sandbach, Cheshire, CW11 3WB");
	$pdf->SetX("10");
	$pdf->write("17","Phone: 0870 850 8752	Email: service@ancestryshop.co.uk");
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(175,188,34);

	
	
	$pdf->SetX(145);
	$pdf->Cell(16,5,"Page No.",1,"","",1);
	$pdf->Cell(20,5,"Date",1,"","",1);
	$pdf->Cell(25,5,"Invoice No.",1,"","",1);
	$pdf->ln(5);
	$pdf->SetX(145);
		
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(255,255,255);
		
	$pdf->Cell(16,5,$PageNum,1,"","",1);
	$pdf->Cell(20,5,$Today,1,"","",1);
	$pdf->Cell(25,5,$Order,1,"","",1);
		
	$pdf->SetFont('Arial','B',12);
	$pdf->ln(15);
		
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(175,188,34);

	$pdf->Cell(27,5,"DELIVER TO",1,0,"L",1);
	//$pdf->SetX(115);
	//$pdf->Cell(27,5,"INVOICE TO",1,0,"L",1);
		
	$pdf->SetFont('Arial','',10);
		
			
	$pdf->SetFillColor(255,255,255);
		
	$pdf->SetTextColor(0,0,0);
	$pdf->ln(5);
	$pdf->Cell(85,40,"",1,"","",1);
	//$pdf->SetX(115);
	//$pdf->Cell(85,40,"",1,"","",1);
		
	
	$pdf->ln(2);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['title'] . " ");
	$pdf->write('2',$delivery_address['first_name'] . " ");
	$pdf->write('2',$delivery_address['surname'] . " ");
	
	//$pdf->SetX(120);
	//$pdf->write('2',$billing_address['title'] . " " );
	//$pdf->write('2',$billing_address['first_name'] . " " );
	//$pdf->write('2',$billing_address['surname'] . " " );
			
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['line_1']);
	
	//$pdf->SetX(120);
	//$pdf->write('2',$billing_address['line_1']);
		
		
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['line_2']);
	//$pdf->SetX(120);
	//$pdf->write('2',$billing_address['line_2']);
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['city']);
	//$pdf->SetX(120);
	//$pdf->write('2',$billing_address['city']);
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['county']);
	//$pdf->SetX(120);
	//$pdf->write('2',$billing_address['county']);
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['country']);
	//$pdf->SetX(120);
	//$pdf->write('2',$billing_address['country']);
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['postcode']);
	//$pdf->SetX(120);
	//$pdf->write('2',$delivery_address['postcode']);
	
	/*$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['phone']);
	$pdf->SetX(110);
	$pdf->write('2',$billing_address['phone']);
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['email']);
	$pdf->SetX(110);
	$pdf->write('2',$billing_address['email']);*/
	
	$pdf->ln(20);
	
	//create headers for item lines
	$pdf->SetFont('Arial','B',10);
	
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(175,188,34);
	$pdf->Cell(10,5,'Qty',1,"","",1);
	$pdf->Cell(30,5,'Item Code',1,"","",1);
	$pdf->Cell(81,5,'Description',1,"","",1);
	$pdf->Cell(22,5,'Unit Price',1,"","",1);
	$pdf->Cell(25,5,'Amount',1,"","",1);
	$pdf->Cell(22,5,'VAT',1,"","",1);
	

	$pdf->SetTextColor(0,0,0);
	
	$pdf->SetFillColor(255,255,255);
	
	$pdf->SetFont('Arial','',10);
}	



function EndPage($pdf,$CustomerDetails,$GiftMessage="")
{
	$pdf->SETXY(10,270);
	
	$pdf->Line(10,270,200,270);
	
	$pdf->SetFillColor(225);
	if($GiftMessage!="")
		$pdf->CellFitScale(190,10,$GiftMessage,1,"","",1);
	$pdf->SetFillColor(255);
	
	$pdf->SETXY(70,270);
	
	$pdf->SetFont('Arial','I',7);
	$pdf->write("5","Company Number: 3439236 ");
	$pdf->write("5"," VAT Number: 701 0952 77");
}

?>