<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");


//email customer to say order complete 

SendConfirmationEmail('38272');//US local (157083) 1  (157082)
SendConfirmationEmail('38002');//AUS local (156543) 12 (156542)
SendConfirmationEmail('37977');//Can local (156493) 34 (156492)

?>