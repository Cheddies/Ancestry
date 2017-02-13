<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");


//email customer to say order complete 

SendCompletedEmail('513625');//UK local  (189829) 73 (189828)
SendCompletedEmail('513626');//US local (157083) 1  (157082)
//SendCompletedEmail('38002');//AUS local (156543) 12 (156542)
//SendCompletedEmail('37977');//Can local (156493) 34 (156492)

?>
