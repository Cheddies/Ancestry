<?php 
function randomcolor (){

//$color="#";
$color="";
for ($i=0;$i<3;$i++)
{

$number=rand(0,255);
$number2=dechex($number);
if (strlen($number2)==1)
	$number2="0" . $number2;
$color=$color . $number2;

}

return $color;

}
?>