<?php
//tracking stuff
setcookie('bmdtrlan', basename($_SERVER["REQUEST_URI"]), time() + (86400*7), '/'); //track this page in cookie
//page and discount stuff
$discCodes = array('BMD10'=>10);
$pageUrl = strtolower($_GET['url']);
$curSymbol = '&pound;';
$searchUrl = 'http://search.ancestry.co.uk/search/category.aspx?cat=34';
$attachUrl = 'http://landing.ancestry.co.uk/intl/blanch.aspx?landingpage=imageupload';
$orderNowLink = true;
$digiCopyPrice = '2.50';
$homeSite = 'Ancestry.co.uk';
$standardPrice = 22.99;
$expressPrice = 39.99;
$tcDates = array('15th June 2010','July 15th 2010');
//echo $pageUrl; exit;
switch($pageUrl){
	case 'bmdoffer10': 
		$discount = array('code'=>'BMD10','amount'=>10);
		break;
	case 'bmdoffer10-ma': 
		$discount = array('code'=>'BMD10A','amount'=>10);
		break;
	case 'bmdoffer15': 
		$discount = array('code'=>'BMD15','amount'=>15);
		break;
	case 'bmdoffer15-ma': 
		$discount = array('code'=>'BMD15A','amount'=>15);
		break;
    case 'aubmdoffer':
        $digiCopyPrice = '8.00';
        $searchUrl = 'http://search.ancestry.com.au/search/category.aspx?cat=34';
        $attachUrl = 'http://landing.ancestry.com.au/intl/blanch.aspx?landingpage=imageupload';
        $curSymbol = '$';
        $homeSite = 'Ancestry.com.au';
        $standardPrice = 43.00;
        $expressPrice = 84;
        $orderNowLink = true;
        $tcDates = array('18th March 2011','December 31st 2011');
		break;
    case 'aubmdoffer20':
        $discount = array('code'=>'BMD20AUS','amount'=>20);
        $orderNowLink = true;
        $digiCopyPrice = '8.00';
        $searchUrl = 'http://search.ancestry.com.au/search/group/uk_bmd';
        $attachUrl = 'http://landing.ancestry.com.au/intl/blanch.aspx?landingpage=imageupload';
        $curSymbol = '$';
        $homeSite = 'Ancestry.com.au';
        $standardPrice = 43.00;
        $expressPrice = 84;
        $tcDates = array('18th March 2011','December 31st 2011');
		break;
    case 'cabmdoffer':
        $digiCopyPrice = '8.00';
        $searchUrl = 'http://search.ancestry.ca/search/category.aspx?cat=34';
        $attachUrl = 'http://landing.ancestry.ca/intl/blanch.aspx?landingpage=imageupload';
        $curSymbol = '$';
        $homeSite = 'Ancestry.ca';
        $standardPrice = 37.00;
        $expressPrice = 74;
        $orderNowLink = true;
        $tcDates = array('18th March 2011','December 31st 2011');
		break;
    case 'cabmdoffer20':
        $discount = array('code'=>'BMD20CA','amount'=>20);
        $orderNowLink = true;
        $digiCopyPrice = '8.00';
        $searchUrl = 'http://search.ancestry.ca/search/category.aspx?cat=34';
        $attachUrl = 'http://landing.ancestry.ca/intl/blanch.aspx?landingpage=imageupload';
        $curSymbol = '$';
        $homeSite = 'Ancestry.ca';
        $standardPrice = 37.00;
        $expressPrice = 74;
        $tcDates = array('18th March 2011','December 31st 2011');
		break;
    case 'usbmdoffer':
        $digiCopyPrice = '8.00';
        $searchUrl = 'http://search.ancestry.com/search/category.aspx?cat=34';
        $attachUrl = 'http://landing.ancestry.com/intl/blanch.aspx?landingpage=imageupload';
        $curSymbol = '$';
        $homeSite = 'Ancestry.com';
        $standardPrice = 38.00;
        $expressPrice = 76;
        $orderNowLink = true;
        $tcDates = array('18th March 2011','December 31st 2011');
		break;
    case 'usbmdoffer20':
        $discount = array('code'=>'BMD20US','amount'=>20);
        $orderNowLink = true;
        $digiCopyPrice = '8.00';
        $searchUrl = 'http://search.ancestry.com/search/category.aspx?cat=34';
        $attachUrl = 'http://landing.ancestry.com/intl/blanch.aspx?landingpage=imageupload';
        $curSymbol = '$';
        $homeSite = 'Ancestry.com';
        $standardPrice = 38.00;
        $expressPrice = 76;
        $tcDates = array('18th March 2011','December 31st 2011');
		break;
	default:
		$discount = array('code'=>'','amount'=>0);
}

// no need to change anything below here
$standardPrice = number_format($standardPrice, 2);
$expressPrice = number_format($expressPrice, 2);
$discount['std'] = $discount['code'] ? number_format( $standardPrice - ($standardPrice / 100 * $discount['amount']),2) : $standardPrice;
$discount['exp'] = $discount['code'] ? number_format( $expressPrice - ($expressPrice / 100 * $discount['amount']),2) : $expressPrice;
if(stripos($pageUrl, 'aubmdoffer') !== false ) {
    require_once('include/bmd_aus_discount.inc.php');
} elseif(stripos($pageUrl, 'cabmdoffer') !== false ) {
    require_once('include/bmd_ca_discount.inc.php');
} elseif(stripos($pageUrl, 'usbmdoffer') !== false ) {
    require_once('include/bmd_us_discount.inc.php');
} else {
    require_once('include/bmd_2010_discount.inc.php');
}

?>