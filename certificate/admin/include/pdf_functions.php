<?php

function NewPage($pdf,$PageNum,$Today,$Order,$billing_address,$delivery_address)
{
	$pdf->AddPage();
	//$pdf->Image("logo.png", 10,10,40);
	//$pdf->Image("logo2.png", 75,2,60);
	//$pdf->Image("images/ancestry_logo_invoice.jpg", 5,2,100);
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(128,128,128);
	
	//$pdf->write("10","Ancestry Shop, PO Box 150, Sandbach, Cheshire, CW11 3WB");
	//$pdf->SetX("10");
	//$pdf->write("17","Phone: 0845 688 5114	Email: service@ancestryshop.co.uk");
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(128,128,128);

	
	$pdf->SetXY(145,4);
	//$pdf->SetX(145);
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
	$pdf->SetFillColor(128,128,128);

	//grey 128,128,128
	$pdf->ln(10);
	$pdf->Cell(27,5,"DELIVER TO",1,0,"L",1);
	$pdf->SetX(115);
	/*$pdf->Cell(27,5,"INVOICE TO",1,0,"L",1);*/
		
	$pdf->SetFont('Arial','',10);
		
			
	$pdf->SetFillColor(255,255,255);
		
	$pdf->SetTextColor(0,0,0);
	$pdf->ln(5);
	$pdf->Cell(85,40,"",1,"","",1);
	/*$pdf->SetX(115);
	$pdf->Cell(85,40,"",1,"","",1);*/
		
	
	$pdf->ln(2);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['title'] . " ");
	$pdf->write('2',$delivery_address['first_name'] . " ");
	$pdf->write('2',$delivery_address['surname'] . " ");
	
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['title'] . " " );
	$pdf->write('2',$billing_address['first_name'] . " " );
	$pdf->write('2',$billing_address['surname'] . " " );*/
			
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['line_1']);
	
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['line_1']);*/
		
		
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['line_2']);
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['line_2']);*/
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['city']);
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['city']);*/
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['county']);
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['county']);*/
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['country']);
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['country']);*/
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['postcode']);
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['postcode']);*/
	
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
	$pdf->SetFont('Arial',"",10);
	
	/*$pdf->SetTextColor(255,255,255);*/
	$pdf->SetFillColor(128,128,128);
	
	$pdf->SetTextColor(255,255,255);
	$pdf->Cell(15,5,'Line No',1,"","",1);
	$pdf->Cell(25,5,'Item Code',1,"","",1);
	$pdf->Cell(57,5,'Description',1,"","",1);
	$pdf->Cell(10,5,'Q\'ty',1,"","C",1);
	$pdf->Cell(20,5,'Unit Price',1,"","C",1);
	$pdf->Cell(20,5,'Total Price',1,"","C",1);
	$pdf->Cell(23,5,"VAT(@".VAT_PERCENT. "%)",1,"","C",1);
	$pdf->Cell(20,5,'Total Price',1,"","C",1);
	
	//Line No	Item Code	Description			Q'ty	Unit price	Total price 	VAT (@17.5%)	Total price

	
	/*$pdf->Cell(25,5,'Amount',1,"","",1);
	$pdf->Cell(22,5,'VAT',1,"","",1);
	*/
	/*$pdf->Cell(10,5,'Qty',1,"","",1);
	$pdf->Cell(30,5,'Item Code',1,"","",1);
	$pdf->Cell(81,5,'Description',1,"","",1);
	$pdf->Cell(22,5,'Unit Price',1,"","",1);
	$pdf->Cell(25,5,'Amount',1,"","",1);
	$pdf->Cell(22,5,'VAT',1,"","",1);*/
	

	$pdf->SetTextColor(0,0,0);
	
	$pdf->SetFillColor(255,255,255);
	
	$pdf->SetFont('Arial','',10);
}	



function EndPage($pdf,$CustomerDetails,$GiftMessage="")
{
	//nothing to do for footer
	
	//potential can add gift message or other footer elements here
	
	/*$pdf->SETXY(10,265);
	
	$pdf->Line(10,264,200,264);
	
	$pdf->SetFillColor(225);
	if($GiftMessage!="")
		$pdf->CellFitScale(190,10,$GiftMessage,1,"","",1);
	$pdf->SetFillColor(255);
	
	
	
	//$pdf->SetFont('Arial','I',7);
	/*$pdf->SetFont('Arial','',7);
	$pdf->Image("images/ancestry_logo_invoice_footer.jpg",10,265,25);
	
	$pdf->SETXY(35,265);
	
	$pdf->write("5","is a trading name of THE GENERATIONS NETWORK (COMMERCE) LIMITED");
	//$pdf->write("5","Ancestry Shop is a trading name of THE GENERATIONS NETWORK (COMMERCE) LIMITED");
	
	$pdf->SETXY(10,270);
	$pdf->write("5","Registered Office: Amberley Place, 107-111 Peascod Street, Windsor, Berkshire No. 6576240     Registered in England and Wales    VAT No.: 927 3808 03");*/
}


//Austrailia change
//removed phone

function NewPageAus($pdf,$PageNum,$Today,$Order,$billing_address,$delivery_address)
{
	$pdf->AddPage();
	//$pdf->Image("logo.png", 10,10,40);
	//$pdf->Image("logo2.png", 75,2,60);
	$pdf->Image("images/ancestry_logo_invoice.jpg", 5,2,100);
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(128,128,128);
	
	$pdf->write("10","Ancestry Shop, PO Box 150, Sandbach, Cheshire, CW11 3WB");
	$pdf->SetX("10");
	$pdf->write("17","Email: service@ancestryshop.co.uk");
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(128,128,128);

	
	
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
	$pdf->SetFillColor(128,128,128);

	$pdf->Cell(27,5,"DELIVER TO",1,0,"L",1);
	$pdf->SetX(115);
	/*$pdf->Cell(27,5,"INVOICE TO",1,0,"L",1);*/
		
	$pdf->SetFont('Arial','',10);
		
			
	$pdf->SetFillColor(255,255,255);
		
	$pdf->SetTextColor(0,0,0);
	$pdf->ln(5);
	$pdf->Cell(85,40,"",1,"","",1);
	/*$pdf->SetX(115);
	$pdf->Cell(85,40,"",1,"","",1);*/
		
	
	$pdf->ln(2);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['title'] . " ");
	$pdf->write('2',$delivery_address['first_name'] . " ");
	$pdf->write('2',$delivery_address['surname'] . " ");
	
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['title'] . " " );
	$pdf->write('2',$billing_address['first_name'] . " " );
	$pdf->write('2',$billing_address['surname'] . " " );*/
			
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['line_1']);
	
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['line_1']);*/
		
		
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['line_2']);
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['line_2']);*/
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['city']);
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['city']);*/
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['county']);
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['county']);*/
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['country']);
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['country']);*/
	
	$pdf->ln(5);
	$pdf->SetX(15);
	$pdf->write('2',$delivery_address['postcode']);
	/*$pdf->SetX(120);
	$pdf->write('2',$billing_address['postcode']);*/
	
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
	$pdf->SetFont('Arial',"",10);
	
	/*$pdf->SetTextColor(255,255,255);*/
	$pdf->SetFillColor(128,128,128);
	
	
	$pdf->Cell(15,5,'Line No',1,"","",1);
	$pdf->Cell(25,5,'Item Code',1,"","",1);
	$pdf->Cell(57,5,'Description',1,"","",1);
	$pdf->Cell(10,5,'Q\'ty',1,"","C",1);
	$pdf->Cell(20,5,'Unit Price',1,"","C",1);
	$pdf->Cell(20,5,'Total Price',1,"","C",1);
	$pdf->Cell(23,5,"VAT (@".VAT_PERCENT. "%)",1,"","C",1);
	$pdf->Cell(20,5,'Total Price',1,"","C",1);
	
	//Line No	Item Code	Description			Q'ty	Unit price	Total price 	VAT (@17.5%)	Total price

	
	/*$pdf->Cell(25,5,'Amount',1,"","",1);
	$pdf->Cell(22,5,'VAT',1,"","",1);
	*/
	/*$pdf->Cell(10,5,'Qty',1,"","",1);
	$pdf->Cell(30,5,'Item Code',1,"","",1);
	$pdf->Cell(81,5,'Description',1,"","",1);
	$pdf->Cell(22,5,'Unit Price',1,"","",1);
	$pdf->Cell(25,5,'Amount',1,"","",1);
	$pdf->Cell(22,5,'VAT',1,"","",1);*/
	

	$pdf->SetTextColor(0,0,0);
	
	$pdf->SetFillColor(255,255,255);
	
	$pdf->SetFont('Arial','',10);
}	

?>
