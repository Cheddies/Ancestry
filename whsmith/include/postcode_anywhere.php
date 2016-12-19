<?php

/*
  Note: This script has been developed for .php version 4.2.3.
  .php version 4.3 has more options to parse xml properly with slightly modified functions.

  This script will let you lookup an address from a postcode.
  
  !!NOTE: Since this example uses the XML service, fields will only be available in the results
  if a value is present i.e. they are not null. If this causes problems the Recordset service
  can be used instead - just modify the PrepareData function below to strip out the different
  characters in the response.
*/

//enter you account code and license key
$ACCOUNTCODE = "THEGE11116";
$LICENSEKEY = "KU96-DY76-ME93-JU46";

function ByPostcode($SearchPostcode){

   global $ACCOUNTCODE,$LICENSEKEY;
   
   /* Build up the URL to send the request to. */
   $sURL = "http://services.postcodeanywhere.co.uk/xml.aspx?";
   $sURL .= "account_code=" . urlencode($ACCOUNTCODE);
   $sURL .= "&license_code=" . urlencode($LICENSEKEY);
   $sURL .= "&action=lookup";
   $sURL .= "&type=by_postcode";
   $sURL .= "&postcode=" . urlencode($SearchPostcode);
   
   PrepareData($sURL);

}

function FetchAddress($AddressID){
   
   global $ACCOUNTCODE, $LICENSEKEY;
   
   /* Build up the URL to request the data from. */
   $sURL = "http://services.postcodeanywhere.co.uk/xml.aspx?";
   $sURL .= "account_code=" . urlencode($ACCOUNTCODE);
   $sURL .= "&license_code=" . urlencode($LICENSEKEY);
   $sURL .= "&action=fetch";
   $sURL .= "&style=simple";
   $sURL .= "&id=" . $AddressID;

   PrepareData($sURL);

}

function PrepareData($URL){
   
   global $Data;
   
   /* Open the URL into a file */
   $ContentsFetch=file("$URL");

   foreach ($ContentsFetch as $line_num => $line) {
      if (strpos($line,"<Item ")!=false) { $Contents[]= $line;}
   }

   for ($i=0;$i<count($Contents);$i++) {

      /* Strip out "<Item " and " />" from the XML */
      $Contents[$i]=substr($Contents[$i], 6+strpos($Contents[$i],"<Item "));
      $Contents[$i]=substr($Contents[$i], 0, strlen($Contents[$i])-4);
      $breakapart=explode("\"",$Contents[$i]);

      /* Extract field names and values */
      for ($x=0;$x<count($breakapart);$x++){
         if ($x % 2 == 0){
            $k=trim(str_replace("=", "", $breakapart[$x]));
            if ($k!='') { $Data[$i][$k]=$breakapart[$x+1]; }
         }

      }

   }

}

?>