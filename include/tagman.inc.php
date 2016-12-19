<?php
// Tagman implamentation, adds JS code to the bottom of the page depending on page type

$currentPage = str_replace('.php', '',str_replace('/anc_dev', '', $_SERVER['REQUEST_URI']));
$httpsPage = $_SERVER['HTTPS'];
$tagJs = '';
//$confirmPage = $currentPage == '/order_summary';
$confirmPage = '';
if($confirmPage){
	//product names, discriptions etc
	$tagManBasket = array('prods' => array(), 'prices' => array(), 'id' => 0);
	foreach($order['baskets'] as $item){
		$tagManBasket['prods'][] = $item['itemcode'];
		$tagManBasket['prices'][] = $item['price'];
		$tagManBasket['id'] = $item['basketid'];
	}
	if(!empty($tagManBasket['prods'])){
		$tagManBasket['prods'] = implode('|', $tagManBasket['prods']);
		$tagManBasket['prices'] = implode('|', $tagManBasket['prices']);
	}
}

if($currentPage == '/' || $currentPage == '/index'){
	//debug('HOME');
	$tagJs = '<script type="text/javascript">
	// your parameters
	var tmParam = {};
	tmParam["tmpageref"] = "1";
	// our parameters
	var tmOPV = 1800;
	// set only fire container at the start of a visit (value = visit length in minutes, 0 = all the time)
	var tmPageId = 14;
	// base container id
	var tmAddJs = 1;
	var tmBaseUrl = "http://pfa.levexis.com/ancestry/tagman.cgi";
	// base url â€“ only specified for full TagMan Clients
	</script>
	<script type="text/javascript" src="http://res.levexis.com/clientfiles/tmap/ancestry.js"></script>
	<script type="text/javascript" src="http://res.levexis.com/js/tman.js"></script>
	<noscript><iframe src="http://res.levexis.com/clientfiles/default/14/ancestry.htm"
	style="border:0px none ; width: 0px; height:
	0px;"></iframe></noscript>';
} elseif($confirmPage){
	//debug('CONFIRM');
	$tagJs = '<script type="text/javascript">
	// your parameters
	var tmParam = {};
	tmParam["product_price"] = "' . $tagManBasket['prices'] . '";
    tmParam["product_name"] = "' . $tagManBasket['prods'] . '";
    tmParam["levrev"] = "' . number_format($orderTotal,2) . '";
    //tmParam["levresdes"] = "[Enter Conversion description]";
    tmParam["levordref"] = "' . $tagManBasket['id'] . '";
	tmParam["tmpageref"] = "1";
	// our parameters
	var tmOPV = 0;
	// set only fire container at the start of a visit (value = visit length in minutes, 0 = all the time)
	var tmPageId = 15;
	// base container id
	var tmAddJs = 1;
	var tmBaseUrl = "https://pfa.levexis.com/ancestry/tagman.cgi";
	// base url â€“ only specified for full TagMan Clients
	</script>
	<script type="text/javascript" src="https://sec.levexis.com/clientfiles/tmap/ancestry.js"></script>
	<script type="text/javascript" src="https://sec.levexis.com/js/tman.js"></script>
	<noscript><iframe src="https://sec.levexis.com/clientfiles/default/15/ancestry.htm"
	style="border:0px none ; width: 0px; height:
	0px;"></iframe></noscript>';
} elseif($httpsPage){
	//debug('HTTPS');
	$tagJs = '<script type="text/javascript">
	// your parameters
	var tmParam = {};
	tmParam["tmpageref"] = "1";
	// our parameters
	var tmOPV = 0;
	// set only fire container at the start of a visit (value = visit length in minutes, 0 = all the time)
	var tmPageId = 12;
	// base container id
	var tmAddJs = 1;
	var tmBaseUrl = "https://pfa.levexis.com/ancestry/tagman.cgi";
	// base url â€“ only specified for full TagMan Clients
	</script>
	<script type="text/javascript"
	src="https://sec.levexis.com/clientfiles/tmap/ancestry.js"></script>
	<script type="text/javascript" src="https://sec.levexis.com/js/tman.js"></script>
	<noscript><iframe src="https://sec.levexis.com/clientfiles/default/12/ancestry.htm"
	style="border:0px none ; width: 0px; height:
	0px;"></iframe></noscript>';
} else {
	//debug('GENERIC');
	$tagJs = '<script type="text/javascript">
	// your parameters
	var tmParam = {};
	tmParam["tmpageref"] = "1";
	// our parameters
	var tmOPV = 0;
	// set only fire container at the start of a visit (value = visit length in minutes, 0 = all the time)
	var tmPageId = 13;
	// base container id
	var tmAddJs = 1;
	var tmBaseUrl = "http://pfa.levexis.com/ancestry/tagman.cgi";
	// base url â€“ only specified for full TagMan Clients
	</script>
	<script type="text/javascript" src="http://res.levexis.com/clientfiles/tmap/ancestry.js"></script>
	<script type="text/javascript"
	src="http://res.levexis.com/js/tman.js"></script>
	<noscript><iframe src="http://res.levexis.com/clientfiles/default/13/ancestry.htm"
	style="border:0px none ; width: 0px; height:
	0px;"></iframe></noscript>';
}

echo "<!-- TAGMAN IMPLAMENTATION -->\n{$tagJs}\n<!-- END TAGMAN -->\n";