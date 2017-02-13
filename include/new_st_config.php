<?php 
//used across the 4 summary pages

//could be moved to the main siteconstants check
//to change between dependant on site
/*
if($_SERVER['REMOTE_ADDR']=='10.0.0.101' || $_SERVER['REMOTE_ADDR']=='82.109.39.210' || $_SERVER['REMOTE_ADDR']=='87.127.20.185')
{
	define('NEW_SECURE_TRADING_ACCOUNT','elmancestry27200');
}
else
{
	define('NEW_SECURE_TRADING_ACCOUNT','test_elmancestry32377');

}*/

if($_SERVER['HTTP_HOST']=="ancestry.ubuntudev.elmbanklogistics.local")
{
	define('NEW_SECURE_TRADING_ACCOUNT','test_elmancestry32377');
}
elseif($_SERVER['HTTP_HOST']=="ancestry.internetlogistics.com")
{
	define('NEW_SECURE_TRADING_ACCOUNT','elmancestry27200');
}
else
{
	//define('NEW_SECURE_TRADING_ACCOUNT','elmancestry27200');
	define('NEW_SECURE_TRADING_ACCOUNT','test_elmancestry32377');
}

define('NEW_SECURE_TRADING_SECURITY_PASSWORD_OUTGOING','d6UHecaprehabrEbA4h958Fu7ruca8re');

$iso_countries = array('005' => 'AD', '191' => 'AE', '002' => 'AF', '008' => 'AG', '007' => 'AI', '003' => 'AL', '225' => 'AM', '131' => 'AN', '006' => 'AO', '249' => 'AQ', '009' => 'AR', '248' => 'AS', '013' => 'AT', '012' => 'AU', '010' => 'AW', '226' => 'AZ', '239' => 'BA', '018' => 'BB', '017' => 'BD', '019' => 'BE', '030' => 'BF', '029' => 'BG', '016' => 'BH', '032' => 'BI', '021' => 'BJ', '022' => 'BM', '028' => 'BN', '024' => 'BO', '026' => 'BR', '015' => 'BS', '023' => 'BT', '250' => 'BV', '025' => 'BW', '227' => 'BY', '020' => 'BZ', '034' => 'CA', '254' => 'CC', '300' => 'CD', '037' => 'CF', '043' => 'CG', '174' => 'CH', '046' => 'CI', '256' => 'CK', '039' => 'CL', '033' => 'CM', '040' => 'CN', '041' => 'CO', '045' => 'CR', '047' => 'CU', '035' => 'CV', '253' => 'CX', '048' => 'CY', '245' => 'CZ', '070' => 'DE', '051' => 'DJ', '050' => 'DK', '052' => 'DM', '053' => 'DO', '004' => 'DZ', '055' => 'EC', '059' => 'EE', '056' => 'EG', '279' => 'EH', '257' => 'ER', '168' => 'ES', '060' => 'ET', '064' => 'FI', '063' => 'FJ', '061' => 'FK', '304' => 'FM', '062' => 'FO', '065' => 'FR', '258' => 'FX', '068' => 'GA', '073' => 'GB', '076' => 'GD', '229' => 'GE', '066' => 'GF', '071' => 'GH', '072' => 'GI', '075' => 'GL', '069' => 'GM', '079' => 'GN', '077' => 'GP', '058' => 'GQ', '074' => 'GR', '310' => 'GS', '078' => 'GT', '260' => 'GU', '080' => 'GW', '081' => 'GY', '084' => 'HK', '261' => 'HM', '083' => 'HN', '240' => 'HR', '082' => 'HT', '085' => 'HU', '088' => 'ID', '091' => 'IE', '092' => 'IL', '087' => 'IN', '251' => 'IO', '090' => 'IQ', '089' => 'IR', '086' => 'IS', '093' => 'IT', '094' => 'JM', '096' => 'JO', '095' => 'JP', '098' => 'KE', '231' => 'KG', '252' => 'KH', '099' => 'KI', '042' => 'KM', '153' => 'KN', '301' => 'KP', '202' => 'KR', '101' => 'KW', '036' => 'KY', '230' => 'KZ', '302' => 'LA', '104' => 'LB', '155' => 'LC', '108' => 'LI', '169' => 'LK', '106' => 'LR', '105' => 'LS', '109' => 'LT', '110' => 'LU', '103' => 'LV', '107' => 'LY', '126' => 'MA', '123' => 'MC', '265' => 'MD', '112' => 'MG', '262' => 'MH', '303' => 'MK', '117' => 'ML', '266' => 'MM', '124' => 'MN', '111' => 'MO', '306' => 'MP', '119' => 'MQ', '120' => 'MR', '125' => 'MS', '118' => 'MT', '121' => 'MU', '116' => 'MV', '114' => 'MW', '122' => 'MX', '115' => 'MY', '127' => 'MZ', '267' => 'NA', '132' => 'NC', '135' => 'NE', '305' => 'NF', '136' => 'NG', '134' => 'NI', '130' => 'NL', '137' => 'NO', '129' => 'NP', '128' => 'NR', '268' => 'NU', '133' => 'NZ', '138' => 'OM', '140' => 'PA', '143' => 'PE', '067' => 'PF', '141' => 'PG', '144' => 'PH', '139' => 'PK', '146' => 'PL', '156' => 'PM', '145' => 'PN', '272' => 'PR', '307' => 'PS', '147' => 'PT', '271' => 'PW', '142' => 'PY', '148' => 'QA', '149' => 'RE', '150' => 'RO', '151' => 'RU', '152' => 'RW', '160' => 'SA', '165' => 'SB', '162' => 'SC', '170' => 'SD', '173' => 'SE', '164' => 'SG', '154' => 'SH', '243' => 'SI', '311' => 'SJ', '309' => 'SK', '163' => 'SL', '158' => 'SM', '161' => 'SN', '166' => 'SO', '171' => 'SR', '159' => 'ST', '057' => 'SV', '175' => 'SY', '172' => 'SZ', '185' => 'TC', '038' => 'TD', '259' => 'TF', '179' => 'TG', '178' => 'TH', '275' => 'TJ', '276' => 'TK', '236' => 'TM', '183' => 'TN', '180' => 'TO', '054' => 'TP', '184' => 'TR', '181' => 'TT', '186' => 'TV', '312' => 'TW', '177' => 'TZ', '237' => 'UA', '190' => 'UG', '313' => 'UM', '001' => 'US', '192' => 'UY', '238' => 'UZ', '194' => 'VA', '308' => 'VC', '195' => 'VE', '027' => 'VG', '278' => 'VI', '196' => 'VN', '193' => 'VU', '187' => 'WF', '188' => 'WS', '197' => 'YE', '263' => 'YT', '198' => 'YU', '167' => 'ZA', '200' => 'ZM', '201' => 'ZW');
$mainamount=$orderTotal;
$orderreference=$stOrderref;
$password=NEW_SECURE_TRADING_SECURITY_PASSWORD_OUTGOING;
$sitereference=NEW_SECURE_TRADING_ACCOUNT;

$securityhash_string=
$sitereference.
$currencyiso3a.
$mainamount.
$orderreference.
$password;

$sitesecurity=hash('sha256',$securityhash_string);

?>
