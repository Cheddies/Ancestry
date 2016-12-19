<?php

setReporting();
removeMagicQuotes();
unregisterGlobals();
initPage();

/** Check if environment is development and display errors **/

function setReporting() {
if (DEVELOPMENT_ENVIRONMENT == true) {
	error_reporting(E_ALL & ~E_NOTICE);
	ini_set('display_errors','On');
} else {
	error_reporting(E_ALL & ~E_NOTICE);
	ini_set('display_errors','Off');
	ini_set('log_errors', 'On');
	ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
}
}

/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function removeMagicQuotes() {
if ( get_magic_quotes_gpc() ) {
	$_GET    = stripSlashesDeep($_GET   );
	$_POST   = stripSlashesDeep($_POST  );
	$_COOKIE = stripSlashesDeep($_COOKIE);
}
}

/** Check register globals and remove them **/

function unregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Main Call Function **/

function initPage() {
	global $url;

	$urlArray = array();
	
	$urlArray = explode("/",$url);
	$controller = isset($urlArray[0]) ? strtolower($urlArray[0]) : null;
	$controller = str_replace('.php', '', $controller);
	array_shift($urlArray);
	$dept = isset($urlArray[0]) ? strtolower($urlArray[0]) : null;
	$dept = str_replace('.php', '', $dept);
	array_shift($urlArray);
	$prod = isset($urlArray[0]) ? strtolower($urlArray[0]) : null;
	
	//echo "$controller | $dept | $prod";

	if(strtolower($controller) == 'products'){
		//if requesting products page
		if(!$dept){
			//no department, 404
			error404();
		}
		//set department name
		$deptName = strtotitle($dept);
		//set "From" £x.xx text for certain products
		$from = $dept == 'birth-marriage-death-certificates' ? 'From ' : '';
		//set page variable for navigation
		$Page = $dept;
		if(!$prod){
			if($dept == 'search'){
				//product search
				$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");
				$searchTerm = form_clean($_GET['searchterm'],50, $badchars);
				//redirect some searches to specific pages eg dna, my canvas
				$redir = searchRedirect($searchTerm);
				//if no redirect show search results, otherwise redirect to specified page
				if(!$redir){
					$products = loadProducts($dept, $searchTerm, true);
					$prodCount = count($products);
					$prodCount = $prodCount ==1 ? $prodCount . ' product' : $prodCount . ' products';
					$listTitle = "Search results for search term \"$searchTerm\" ($prodCount)";
				} else {
					header ('location: ' . WEBROOT . $redir);
					exit;
				}
			} else {				
				//if no product given, show all products in dept
				$products = loadProducts($dept);
				$prodCount = count($products);
				$prodCount = $prodCount >1 ? $prodCount . ' products' : $prodCount . ' product';
				//breadcrumb
				$listTitle = '<a href="' . WEBROOT . 'index" title="Ancestry Shop Home Page">Ancestry Shop</a> > ';
				$listTitle .= "<a class=\"last_crumb\" href=\"" . WEBROOT . "products/{$dept}\" title=\"$deptName\">{$deptName}</a> ($prodCount)";
			}
			if(empty($products) && $dept != 'search'){
				//nothing to display
				error404();
			} else {
				include_once(ROOT . DS . 'include' . DS . 'product_listing.inc.php');
			}
		} else {
			//details page template to use
			$pageTemplate = 'product_detail'; //default
			if($prod == 'surname-book-your-name-in-history'){
				$pageTemplate = 'surname_book';
			} elseif($dept == 'birth-marriage-death-certificates'){
				$pageTemplate = 'product_cert_detail';
			}
			
			//product slug given, must be a product detail page
			$products = loadProducts($dept,$prod);
			if(empty($products)){
				//404 error
				error404();
			} else {
				$product = $products[0];
				//get meta tags for product
				$pageData['meta'] = getMetaTags($product['number']);
				$pageData['meta']['title'] = $pageData['meta']['title'] . ' | Ancestry Shop';
				//form the breadcrumb
				$prodDetailLink = WEBROOT . "products/{$dept}/{$product['slug']}";
				$fullUrl = 'http://www.ancestryshop.co.uk' . $prodDetailLink;
				$listTitle = '<a href="' . WEBROOT . 'index" title="Ancestry Shop Home Page">Ancestry Shop</a> > ';
				$listTitle .= "<a href=\"" . WEBROOT . "products/{$dept}\" title=\"$deptName\">{$deptName}</a> > ";				
				$listTitle .= "<a class=\"last_crumb\" href=\"" . $prodDetailLink . "\" title=\"{$product['name']}\">{$product['name']}</a>";
				//get image thumbnails and first image to display
				$images = getProductImages($product['number']);
				if(empty($images)){
					$medImg = prodImage($product['number'] . '.jpg','med');
					$lgImg = prodImage($product['number'] . '.jpg','large');
				} else {
					if(isset($_GET['img'])){
						$img = clean($_GET['img'],100);
						$medImg = prodImage($img,'med');
						$lgImg = prodImage($img,'large');
					} else {
						$medImg = prodImage($images[0]['filename'],'med');
						$lgImg = prodImage($images[0]['filename'],'large');
					}
				}
				//get extra product info
				$extraInfo = getExtraInfo($product['number']);
				include_once(ROOT . DS . 'include' . DS . $pageTemplate . '.inc.php');
			}
		}
	} else {
		//no controller
		header ('location: ' . WEBROOT . 'index.php');
		exit;
	}
}

/** Get products **/

function loadProducts($deptSlug, $prodSlug=null, $search=false){
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	$select = "SELECT DISTINCT
		depts.name,
		depts.slug AS deptslug,
		prods.number,
		prods.name,
		prods.price,
		prods.compareprice,
		prods.inetsdesc,
		prods.inetshortd,
		prods.buybutton,
		prods.slug";
	$from = " FROM
		tbl_departments AS depts
		Right Join tbl_products_departments ON tbl_products_departments.deptcode = depts.deptcode
		Right Join tbl_products AS prods ON tbl_products_departments.number = prods.number";
	$where = " 	WHERE prods.inetsell =  '1'";
	if($search){
		$where = " WHERE prods.inetsell =  '1' " . termQuery($prodSlug);
	} else {
		if(!$prodSlug){
			$where = " 	WHERE prods.inetsell =  '1' AND depts.slug = '{$deptSlug}' ORDER BY tbl_products_departments.disp_seq ASC, prods.name ASC";
		} else {
			$select .= ', prods.inetfdesc';
			$where = " WHERE (prods.slug =  '{$prodSlug}') AND prods.inetsell =  '1' LIMIT 1"; 
		}
	}
	$query = $select . $from . $where;
	//echo $query . '<br/><br/>';
	$result = sqlQuery($query);
	$rows=count($result);
	if(!$rows) return array();
	// Closing connection
	mysql_close($link);
	return $result;
}

function getMetaTags($prodId){
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	$query = "SELECT meta_tags.title, meta_tags.description, meta_tags.keywords FROM meta_tags WHERE variable = '{$prodId}' LIMIT 1";
	//echo $query;
	//$query = "SELECT * FROM tbl_departments WHERE slug = '$masterSlug' LIMIT 1";
	$result = sqlQuery($query);
	$rows=count($result);
	if(!$rows) return array();
	// Closing connection
	mysql_close($link);
	return $result[0];
}

/**
 * returns array - database record of extra product information
 * @param object $prodId
 * @return array
 */
function getExtraInfo($prodId){
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	$query = "SELECT * FROM tbl_extra_product_info WHERE tbl_extra_product_info.number =  '{$prodId}' LIMIT 1";
	$result = sqlQuery($query);
	$rows=count($result);
	if(!$rows) return array();
	// Closing connection
	mysql_close($link);
	return $result[0];
}

/**
 * Return an array of images (thumbnails) for the given product ref
 * @param string $prodId
 * @return array
 */
function getProductImages($prodId){
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	$query = "SELECT * FROM tbl_product_images WHERE prod_ref = '{$prodId}' ORDER BY tbl_product_images.order ASC";
	$result = sqlQuery($query);
	$rows=count($result);
	if(!$rows) return array();
	// Closing connection
	mysql_close($link);
	return $result;
}

/**
 * Returns a filename based on a thumbnail filename and return type
 * @param string $thumbnail
 * @param string $imageType
 * @return 
 */
function prodImage($img,$imageType='',$fullPath = true){
	$baseImage = str_replace('.jpg', '', $img);
	$pathPrefix = $fullPath ? WEBROOT . 'images/products/' : '' ;
	switch($imageType){
		case 'med': return "{$pathPrefix}{$baseImage}_MED.jpg";
		case 'thumb': return "{$pathPrefix}{$baseImage}_TN.jpg";
		case 'large': return "{$pathPrefix}{$baseImage}.jpg";
		case 'small': return "{$pathPrefix}{$baseImage}_S.jpg";
		default: return  $pathPrefix . $baseImage . '.jpg';
	}
}

function termQuery($term){
	//build search criteria
	$searchterms = explode ( " ", $term ); 	
	$removedwords = array();
	$termquery = " AND ";
	$first=true;
	$word=0;
	$ANDOR="AND";
	$no_terms=0;	
	
	foreach ($searchterms as $term){
		$term = strtolower(trim($term));
		if(($term!="and") && ($term!="or") && ($term!="the") && ($term!="a") && ($term!=" ") && ($term!="+") && ($term!="-") && ($term!="not"))
		{
			$no_terms++;
			if($first==true){
				$termquery .= " (  keywords LIKE '%$term%' OR inetsdesc LIKE '%$term%' OR prods.number LIKE '%$term%' )";
				
				$first=false;
			} else {
				$termquery .= $ANDOR . " (  keywords LIKE '%$term%' OR inetsdesc LIKE '%$term%' OR prods.number LIKE '%$term%'  )";		
			}
		} else {
			switch($term){
				case "and":
					$ANDOR="AND";
				break;
				case "or":
					$ANDOR="OR";
				break;
				case "+":
					$ANDOR="AND";
				break;
				case "-":
					$ANDOR="AND NOT";
				break;
				case "not":
					$ANDOR="AND NOT";
				break;
				default:
					$removedwords[$word]=$term;
				$word=$word+1;
				break;
			}
			
		}
	}
	return $termquery;
}

/**
 * Returns a url to redirect to if search term matches specific keywords
 * @param string $searchTerm
 * @return string
 */
function searchRedirect($searchTerm){
	//redirect some searches to specific pages eg dna, certificates
	$term = strtolower(trim($searchTerm));
	$terms = explode(' ', $term);
	$searchRedir = array(
		'dna'=>array('dna','d.n.a','genes','chromosome','haplo','mitochondrial','test'),
		'my-canvas'=>array('my','canvas','poster','collage','photo','publish')
		);
	foreach($terms as $t){
		foreach($searchRedir as $url => $kw){
			if(in_array($t,$kw)){
				return $url;
			}
		}
	}
	return false;
}

function error404(){
	header("HTTP/1.0 404 Not Found");
	header ('location: ' . WEBROOT . 'error404');
	exit;
}


