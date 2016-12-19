<?php
//graph function

function BarGraph($Values,$Xaxis,$Yaxis,$Height,$Width,$Spacing,$FileName,$FontSize=3,$Colors=0,$Multicolor=false,$Color="60/70/220")
{	
	
	$NumValues=sizeof($Values);
	
	//work out how much space is required for the labels
	$XLabelExtra=(strlen($Yaxis)*$FontSize)*2.5;
	$YLabelExtra=(4*$FontSize);
	$ExtraWidth=($Spacing*$NumValues)+$XLabelExtra;
	
	
	$MaxValue=0;
	$HeightUnits=0;
	$WidthSpacing=$Width/$NumValues;
	
	//Find the largest value passed
	//use this to determine the size of a unit
	foreach($Values as $Value){
		if($Value>$MaxValue)
			$MaxValue=$Value;
	}
	if($MaxValue>0)
	{
		$HeightUnits=$Height/$MaxValue;
	
		//create image
		$Graph=ImageCreate($Width+$ExtraWidth,$Height+2+$YLabelExtra);
		//fill in background
		$BGcolor=ImageColorAllocate($Graph,255,255,255);
		$Linecolor=ImageColorAllocate($Graph,0,0,0);
		
		if($Multicolor==true)
		{
			//loop through the passed array of colors
			//colors as passed in hex form of FFFFFF
			//loops through, splits up the hex 
			//converts to decimal values
			//then uses this to allocate the colors for the bars
			$ColorCount=0;
			foreach($Colors as $Color)
			{
				
				$colorarray=str_split($Color, 2);
				if (isset($colorarray[0])==false)
					$colorarray[0]=0;
				if (isset($colorarray[1])==false)
					$colorarray[1]=0;
				if (isset($colorarray[2])==false)
					$colorarray[2]=0;
				
				$colorarray[0]=hexdec("0x{$colorarray[0]}");
				$colorarray[1]=hexdec("0x{$colorarray[1]}");
				$colorarray[2]=hexdec("0x{$colorarray[2]}");
				
				
				$Barcolorarray[$ColorCount]=ImageColorAllocate($Graph,$colorarray[0],$colorarray[1],$colorarray[2]);
				$ColorCount++;
			}
		}
		else
		{
			$ColorArray=explode("/",$Color);
			
			$Barcolor=ImageColorAllocate($Graph,$ColorArray[0],$ColorArray[1],$ColorArray[2]);
			$Barcolor2=ImageColorAllocate($Graph,$ColorArray[0]+30,$ColorArray[1]+30,$ColorArray[2]+30);
		}
		

		imagefill($Graph , 0,0 , $BGcolor);
		   
		//draw x,y axis
	
		//x
		ImageLine($Graph,0+$XLabelExtra,0,0+$XLabelExtra,$Height,$Linecolor);
		//label
		imagestring ( $Graph,$FontSize, ($Width+$ExtraWidth)/2,$Height, $Xaxis, $Linecolor );
		
		//y
		ImageLine($Graph,0+$XLabelExtra,$Height,$Width+$ExtraWidth,$Height,$Linecolor);
		//label 
		imagestring ( $Graph, $FontSize, 0,$Height/2, $Yaxis, $Linecolor );

		//draw bars
		$StartWidth=$Spacing+$XLabelExtra;
		$ColorCount=0;
		foreach ($Values as $Value){
		
			$BarHeight=$HeightUnits*$Value;
			//echo ($BarHeight);
			//echo ($StartWidth);
			if($Multicolor==true)
			{
				imagefilledrectangle ($Graph,$StartWidth,$Height-1,$StartWidth+$WidthSpacing,$Height-$BarHeight, $Barcolorarray[$ColorCount]);
				
			}
			else
			{
				if($ColorCount%2==0)
					imagefilledrectangle ($Graph,$StartWidth,$Height-1,$StartWidth+$WidthSpacing,$Height-$BarHeight, $Barcolor);
				else
					imagefilledrectangle ($Graph,$StartWidth,$Height-1,$StartWidth+$WidthSpacing,$Height-$BarHeight, $Barcolor2);
				
			}
			//display number at top of bar
			/*if($Value>0)
			//imagestring ( $Graph, $FontSize, $StartWidth+($Spacing/2), $Height-$BarHeight, $Value, $Linecolor);
			//imagestring ( $Graph, $FontSize, $StartWidth+($Spacing/2)-5, ($Height-$BarHeight)-12, $Value, $Linecolor);
			*/
			$StartWidth=$StartWidth+$WidthSpacing+$Spacing;
			$ColorCount++;
		
		}
		
		//loop again to label the bars
		//This can be done during the main loop, however the bars then can be overlaid on the text
		
		$StartWidth=$Spacing+$XLabelExtra;
		$ColorCount=0;
		foreach ($Values as $Value){
		
			$BarHeight=$HeightUnits*$Value;
			//echo ($BarHeight);
			//echo ($StartWidth);
			
			//display number at top of bar
			if ($Value>0)
			{
				//imagestring ( $Graph, $FontSize, $StartWidth+($Spacing/2), $Height-$BarHeight, $Value, $Linecolor);
				if($Value!=$MaxValue) 
					imagestring ( $Graph, $FontSize, $StartWidth+($Spacing/2)-5, ($Height-$BarHeight)-12, $Value, $Linecolor);
				else
					imagestring ( $Graph, $FontSize, $StartWidth+($Spacing/2)-5, ($Height-$BarHeight), $Value, $Linecolor);
			}
			
			$StartWidth=$StartWidth+$WidthSpacing+$Spacing;
			$ColorCount++;
				
		}
		
		
		//save the image as required
		ImageGIF($Graph,$FileName);
	
		//clear the memory used to create image	
		imagedestroy($Graph);
	}//end of producing graph
	
	else
	{
		//no graph to draw
		//create image
		$Graph=ImageCreate($Width+$ExtraWidth,$Height+2+$YLabelExtra);
		//fill in background
		$BGcolor=ImageColorAllocate($Graph,0xFF,0xFF,0xFF);
		$Linecolor=ImageColorAllocate($Graph,0x00,0x00,0x00);
		$Barcolor=ImageColorAllocate($Graph,255,50,30);
		imagefill($Graph , 0,0 , $BGcolor);	
		//draw some text on image
		//imagepstext ($Graph,"No Values to Display on Graph", 1, 12, $LineColor, $BGColor, 0, 0);
		imagestring ( $Graph, $FontSize, $Width/2, $Height/2, "No Values to Display on Graph", $Linecolor );
		//save the image as required
		ImageGIF($Graph,$FileName);
			
		//clear the memory used to create image	
		imagedestroy($Graph);
	}
}

// ----------------------
// -----------------------
/// PIE CHART 
// ----------------------
// -----------------------


function PieChart($Values,$Xaxis,$Yaxis,$Height,$Width,$Spacing,$FileName,$FontSize=3,$Colors,$SpacingText="                          ",$Labels=0)
{	
	
	$NumValues=sizeof($Values);
	
	//work out how much space is required for the labels
	$XLabelExtra=$FontSize*15;
	$YLabelExtra=$FontSize*15;
	$ExtraWidth=$XLabelExtra;
	
	/*$XLabelExtra=1;
	$YLabelExtra=1;
	$ExtraWidth=1;
	*/
	$MaxValue=0;
	$HeightUnits=0;
	$WidthSpacing=$Width/$NumValues;
	
	$TotalValues=0;
	
	//Total all the values
	foreach($Values as $Value){
		$TotalValues=$TotalValues+$Value;
	}
	
	
	if($TotalValues>0)
	{
		
		
		//create image
		$Graph=ImageCreate($Width+$ExtraWidth,$Height+2+$YLabelExtra);
		imageantialias($Graph,TRUE);
		//fill in background
		$BGcolor=ImageColorAllocate($Graph,255,255,255);
		$Linecolor=ImageColorAllocate($Graph,0,0,0);
		
		//loop through the passed array of colors
		//colors as passed in hex form of FFFFFF
		//loops through, splits up the hex 
		//converts to decimal values
		//then uses this to allocate the colors for the bars
		$ColorCount=0;
		foreach($Colors as $Color)
		{
			
			$colorarray=str_split($Color, 2);
			if (isset($colorarray[0])==false)
				$colorarray[0]=0;
			if (isset($colorarray[1])==false)
				$colorarray[1]=0;
			if (isset($colorarray[2])==false)
				$colorarray[2]=0;
				
				$colorarray[0]=hexdec("0x{$colorarray[0]}");
				$colorarray[1]=hexdec("0x{$colorarray[1]}");
				$colorarray[2]=hexdec("0x{$colorarray[2]}");
				
				
				$Barcolorarray[$ColorCount]=ImageColorAllocate($Graph,$colorarray[0],$colorarray[1],$colorarray[2]);
				$ColorCount++;
			
		}
		
		//echo $ColorCount;

		imagefill($Graph , 0,0 , $BGcolor);
		   
		//draw x,y axis
	
		//x
		/*ImageLine($Graph,0+$XLabelExtra,0,0+$XLabelExtra,$Height,$Linecolor);
		//label
		imagestring ( $Graph,$FontSize, ($Width+$ExtraWidth)/2,$Height, $Xaxis, $Linecolor );
		
		//y
		ImageLine($Graph,0+$XLabelExtra,$Height,$Width+$ExtraWidth,$Height,$Linecolor);
		//label 
		imagestring ( $Graph, $FontSize, 0,$Height/2, $Yaxis, $Linecolor );
		*/
		
		//draw chart
		
		$StartWidth=$Spacing+$XLabelExtra;
		$ColorCount=0;
		$Start=0;
		$TotalPercent=0;
		/*$HOffset=-20;
		$WOffset=-10;
		foreach ($Values as $Value){
					
					
			$Percentage=$Value/$TotalValues;
			floor($Percentage);
			$Slice=(360*$Percentage);
				
			$End=$Slice+$Start;
					
			imagefilledarc ($Graph, (($Width+$ExtraWidth)/2)+$WOffset, (($Height+$YLabelExtra)/2)-$HOffset, $Width+$WOffset,  $Height-$HOffset, $Start+10, $End+10, $Barcolorarray[$ColorCount],IMG_ARC_PIE|IMG_ARC_EDGED);
					
			
			$Start=$Start+$Slice;
			$ColorCount++;
				
		}*/
		$StartWidth=$Spacing+$XLabelExtra;
		$ColorCount=0;
		$Start=0;
		$TotalPercent=0;
		$Offset=-10;
		foreach ($Values as $Value){
			
			
			$Percentage=$Value/$TotalValues;
			//floor($Percentage);
			$Slice=(360*$Percentage);
		
			$End=$Slice+$Start;
			
			imagefilledarc ($Graph, ($Width+$ExtraWidth)/2, ($Height+$YLabelExtra)/2, $Width,  $Height, $Start, $End, $Barcolorarray[$ColorCount],IMG_ARC_PIE|IMG_ARC_EDGED|IMG_ARC_ROUNDED);
			
			//display number at top of bar
			/*if($Value>0)
				imagestring ( $Graph, $FontSize, $StartWidth+($Spacing/2), $Height-$BarHeight, $Value, $Linecolor);
			*/
			$Start=$Start+$Slice;
			$ColorCount++;
		
		}
		$StartWidth=$Spacing+$XLabelExtra;
		$ColorCount=0;
		$Start=0;
		$TotalPercent=0;
		foreach ($Values as $Value){
					
			
			$Percentage=$Value/$TotalValues;
				
			$Slice=(360*$Percentage);
			$End=$Slice+$Start;
			$Middle=($End-$Start)/2;
			//echo $End."-".$Start ."/2=" . $Middle;
			//echo "<P>";
			//echo $Middle ."+". $Start ."=";
			$Middle=$Middle+$Start;
			//echo $Middle+$Start;
			//echo "<P>";
			imagefilledarc ($Graph, ($Width+$ExtraWidth)/2, ($Height+$YLabelExtra)/2, $Width,  $Height, $Start, $End, $Linecolor,IMG_ARC_NOFILL|IMG_ARC_EDGED);
					
			//value + percentage (labels?)
			if($Value>0)
			{			
				//imagettftext ( resource image, float size, float angle, int x, int y, int color, string fontfile, string text )
				
				if ( ($End-$Start)>20)
				{
					if( ($Middle<270)&& ($Middle>90) )
					{
						imagettftext ( $Graph, $FontSize, 720-$Middle, ($Width+$ExtraWidth)/2, ($Height+$YLabelExtra)/2, $Linecolor, "arial.ttf", $SpacingText  .  $Value . "- (" . (floor($Percentage*100)) ."%)");
						
					}
					else
					{
						imagettftext ( $Graph, $FontSize, 360-$Middle, ($Width+$ExtraWidth)/2, ($Height+$YLabelExtra)/2, $Linecolor, "arial.ttf", $SpacingText .  $Value . "- (" . (floor($Percentage*100)) ."%)");
						//imagestring ( $Graph, $FontSize, $Start, $Height-$End, $Value, $Linecolor);
					}
				}
				else
				{
					if( ($Middle<270)&& ($Middle>90) )
					{
						imagettftext ( $Graph, $FontSize, 720-$Middle, ($Width+$ExtraWidth)/2, ($Height+$YLabelExtra)/2, $Linecolor, "arial.ttf", $SpacingText. $SpacingText. $Value . "- (" . (floor($Percentage*100)) ."%)" );
						
					}
					else
					{
						imagettftext ( $Graph, $FontSize, 360-$Middle, ($Width+$ExtraWidth)/2, ($Height+$YLabelExtra)/2, $Linecolor, "arial.ttf", $SpacingText. $SpacingText. $Value . "- (" . (floor($Percentage*100)) ."%)");
						//imagestring ( $Graph, $FontSize, $Start, $Height-$End, $Value, $Linecolor);
					}
				}
			}
			$Start=$Start+$Slice;
			$ColorCount++;
				
		}
		
		
		//save the image as required
		
		ImageGIF($Graph,$FileName);
	
		//clear the memory used to create image	
		imagedestroy($Graph);
	}//end of producing graph
	
	else
	{
		//no graph to draw
		//create image
		$Graph=ImageCreate($Width+$ExtraWidth,$Height+2+$YLabelExtra);
		//fill in background
		$BGcolor=ImageColorAllocate($Graph,0xFF,0xFF,0xFF);
		$Linecolor=ImageColorAllocate($Graph,0x00,0x00,0x00);
		$Barcolor=ImageColorAllocate($Graph,255,50,30);
		imagefill($Graph , 0,0 , $BGcolor);	
		//draw some text on image
		//imagepstext ($Graph,"No Values to Display on Graph", 1, 12, $LineColor, $BGColor, 0, 0);
		imagestring ( $Graph, $FontSize, $Width/2, $Height/2, "No Values to Display on Graph", $Linecolor );
		//save the image as required
		ImageGIF($Graph,$FileName);
			
		//clear the memory used to create image	
		imagedestroy($Graph);
	}
}

?>

