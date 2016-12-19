<?php

class PDF extends FPDF {

	function setPayRef($pay_ref) {
		$this->pay_ref = $pay_ref;
	}

	function setInvoice($invoice) {
		$this->invoice_number = $invoice;
	}

	function setOrderDate($order_date) {
		$this->order_date = $order_date;
	}

	function setBillingName($name) {
		$this->Billing_name = ucwords($name);
	}

	function setBillingAddress1($address1) {
		$this->Billing_address1 = ucwords($address1);
	}

	function setBillingAddress2($address2) {
		$this->Billing_address2 = ucwords($address2);
	}

	function setBillingTown($town) {
		$this->Billing_town = ucwords($town);
	}

	function setBillingCounty($county) {
		$this->Billing_county = ucwords($county);
	}

	function setBillingPostcode($postcode) {
		$this->Billing_postcode = strtoupper($postcode);
	}

	function setDeliveryName($name) {
		$this->Delivery_name = ucwords($name);
	}

	function setDeliveryAddress1($address1) {
		$this->Delivery_address1 = ucwords($address1);
	}

	function setDeliveryAddress2($address2) {
		$this->Delivery_address2 = ucwords($address2);
	}

	function setDeliveryTown($town) {
		$this->Delivery_town = ucwords($town);
	}

	function setDeliveryCounty($county) {
		$this->Delivery_county = ucwords($county);
	}

	function setDeliveryPostcode($postcode) {
		$this->Delivery_postcode = strtoupper($postcode);
	}
	
	function setShipping($shipping) {
		$this->shipping = $shipping;
	}

	function setGoodsVat($amount) {
		$this->goodsVAT = $amount;
	}

	//Page header
	function Header() {
		$this->SetLineWidth(0.3);

		//Logo
		$this->Image('images/' . LOGO, 10, 8, 150);

		//Arial 15
		$this->SetFont('Arial', '', 10);

		//Title
		$this->Cell(150, 65, COMPANY_ADDRESS, 0, 0, 'L');

		//Line break
		$this->Ln(20);
		$this->Line(10, 48, 200, 48);

		// Invoice Box
		$this->SetFont('Times','',10);
		$this->SETXY(130,52);
		$this->Cell(60,20,'',1);
		$this->Text(132, 59, 'Invoice Number:' );
		$this->Text(160, 59, $this->invoice_number);
		$this->Text(132, 67, 'Order Date:');
		$this->Text(160, 67, $this->order_date);

		// Address Box
		$startinvtext = 59;
		$invinc       = 5;

		$this->SETXY(20,52);
		$this->Cell(75, 38,'',1);
		
		$this->Text(22, $startinvtext, $this->Delivery_name);
		$this->Text(22, $startinvtext + $invinc, $this->Delivery_address1);
		$this->Text(22, $startinvtext + $invinc*2, $this->Delivery_address2);
		$this->Text(22, $startinvtext + $invinc*3, $this->Delivery_town);
		$this->Text(22, $startinvtext + $invinc*4, $this->Delivery_county);
		$this->Text(22, $startinvtext + $invinc*5, $this->Delivery_postcode);

		$this->SETXY(15, 95);
	}

	//Page footer
	function Footer() {
		$this->SetDrawColor(0);

		//Position at 1.5 cm from bottom
		$this->SetY(-35);
		//Arial italic 8
		$this->SetFont('Arial','I',8);

		$this->Cell(0,5,'Payment Details:',0,1,'L');
		$this->Cell(0,5,'Paid in full by credit card, thank you.',0,1,'L');
		$this->Cell(0,5,'Our pay ref: ' . $this->pay_ref, 0, 1, 'L');

		// Draw the line
		$this->Line(10, 280, 200, 280);
		// Company Numbers
		$this->Cell(0,10,'Company Number: 3439236    VAT Number: 701 0952 77',0,1,'C');
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	function LoadData($file) {
	    //Read file lines
	    $lines=file($file);
	    $data=array();
	    foreach($lines as $line)
		$data[]=explode(';',chop($line));
	    return $data;
	}

	//Colored table
	function FancyTable($header, $data, $xval) {

		//Colors, line width and bold font for the header.
		$this->SetFillColor(6,67,205);
		$this->SetTextColor(255);
		$this->SetDrawColor(6,67,205);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');

		//Header
		$w=array(20,70,25,25,15);
		$a=array('C','L','C','C', 'C');

		$this->SetWidths($w);
		$this->SetAligns($a);

		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$this->Ln();

		//Color and font restoration for the rows
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');

		//Data
		$fill=0;

		$grand_total = 0;

		foreach($data as $row) {
			$this->setX($xval);

			$unitprice  = "£" . number_format($row[3], 2, ".", "");
			$totalprice = "£" . number_format($row[4], 2, ".", "");

			$grand_total = $grand_total + $row[4];

			$this->Row(array($row[0],$row[1],$row[2],$unitprice,$totalprice,$row[5]));

			$fill=!$fill;
		}

		if ($this->shipping > 0) {
			$shippingexVat = $this->shipping / 1.175;
			$shippingVAT = $this->shipping - $shippingexVat;
		}

		else {
			$shippingexVat = 0;
			$shippingVAT = 0;
		}

		$vatTotal = $shippingVAT + $this->goodsVAT;
		$invoice_total = $grand_total + $this->shipping;


		$this->setX($xval);
		$this->Cell(array_sum($w),0,'','T');
		$this->setX($xval);
		$this->Row(array('', '', '', 'Shipping:', "£" . number_format($this->shipping, 2, ".", ""), '17.5'));
		$this->setX($xval);
		$this->Row(array('', '', '', 'Total:', "£" . number_format($invoice_total, 2, ".", "")));

		// VAT Box



		$this->setXY($xval + 75, $this->GetY()+5);
		$this->Cell(60,25,'',1);

		$vatboxY = $this->GetY();
		$vatboxX = $this->GetX();

		$this->Text($vatboxX-58, $vatboxY+5, 'VAT Detail: ');
		$this->Text($vatboxX-58, $vatboxY+10, 'Total Ex VAT: ');
		$this->Text($vatboxX-28, $vatboxY+10, "£" . number_format($invoice_total - $vatTotal, 2, ".", ""));
		$this->Text($vatboxX-58, $vatboxY+15, 'Total VAT: ');
		$this->Text($vatboxX-28, $vatboxY+15, "£" . number_format($vatTotal, 2, ".", ""));
		$this->Text($vatboxX-58, $vatboxY+20, 'Total Inc VAT: ');
		$this->Text($vatboxX-28, $vatboxY+20, "£" . number_format($invoice_total, 2, ".", ""));

	}


	function SetWidths($w) {
		//Set the array of column widths
		$this->widths=$w;
	}

	function SetAligns($a) {
		//Set the array of column alignments
		$this->aligns=$a;
	}

	function Row($data) {
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;

		//Issue a page break first if needed
		$this->CheckPageBreak($h);

		//Draw the cells of the row
		for($i=0;$i<count($data);$i++) {
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';

			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();

			//Draw the border
			$this->Rect($x,$y,$w,$h);

			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);

			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h) {
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt) {
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb) {
			$c=$s[$i];
			if($c=="\n") {
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];

			if($l>$wmax) {
				if($sep==-1) {
					if($i==$j)
						$i++;
				}
		    	else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
}
?>