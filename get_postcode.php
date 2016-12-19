<?php

#########################
#Simply Postcode settings
#########################

$simplyserver = "http://www.simplylookupadmin.co.uk/xmlservice/searchforthoroughfareaddress.aspx";

$datakey = "W_30EC00C557C1473D9121BBD7441872";
if(!isset($_POST['isAjax']) || $_POST['isAjax'] != '1') exit;
$getpostcode = $_POST['postcode'];
$getpostcode = str_replace ( " ", "", $getpostcode );
$XMLService = $simplyserver . "?datakey=" . $datakey . "&postcode=" . $getpostcode;

###############
#XML Functions
###############

##Create start element handler function##
function startXML($parser, $name, $attrib) {
	global $currentElement;
	$currentElement = $name;
}

##Create the data handler function##
function XMLelements($parser, $data) {
	global $currentElement, $Line1, $Line2, $Line3, $Town, $County, $Postcode, $Country, $License;

	switch($currentElement){
		case 'CREDITS_DISPLAY_TEXT';
			$License = $License . $data;
			break;
		case 'LINE1';
			$Line1 = $Line1 . $data;
			break;
		case 'LINE2';
			$Line2 = $Line2 . $data;
			break;
		case 'LINE3';
			$Line3 = $Line3 . $data;
			break;
		case 'TOWN';
			$Town = $Town . $data;
			break;
		case 'COUNTY';
			$County = $County . $data;
			break;
		case 'POSTCODE';
			$Postcode = $Postcode . $data;
			break;
		case 'COUNTRY';
			$Country = $Country . $data;
			break;			
		default:
     		break;
	}
}

##Create end element handler function##
function endXML($parser, $name) {
}

################
#Open XML Parser
################

##Create XML Parser##
$xml_parser = xml_parser_create();

##Set the element handlers##
xml_set_element_handler($xml_parser, "startXML", "endXML");

##Set the character data handler##
xml_set_character_data_handler($xml_parser, "XMLelements");


########################
#Read File data returned
########################

##Open the XML Document##
$XMLData = fopen("$XMLService","r") or die("0");

##Parse the data 4096 bytes at a time##
while ($data = fread($XMLData, 4096))
xml_parse($xml_parser, $data, feof($XMLData));

##Close the XML file and free the parser##
fclose($XMLData);
xml_parser_free($xml_parser);

/*echo "<br>License     : <b>" . $License . "</b><br>";
echo "Line1       : <b>" . $Line1 . "</b><br>";
echo "Line2       : <b>" . $Line2 . "</b><br>";
echo "Line3       : <b>" . $Line3 . "</b><br>";
echo "Town        : <b>" . $Town . "</b><br>";
echo "County      : <b>" . $County . "</b><br>";
echo "Postcode    : <b>" . $Postcode . "</b><br>";
echo "Country     : <b>" . $Country . "</b><br>";*/

if(!trim($Town) && trim($County)) $Town = $County; //for london addresses where "london" appears in county field
echo "address_1={$Line1}&address_2={$Line2}&town={$Town}&county={$County}";

//see http://www.simply-postcode-lookup.com/Software/Webhttpservice.htm for more fields

?>
