<?php

function process_form($form_fields,$data,$clean)
{
	$errors=array();
	
	foreach($form_fields as $field)
	{
		if(isset($data[$field['name']]))
		{
			if(isset($field['remove']))
				$clean[$field['name']]=form_clean($data[$field['name']],$field['length'],$field['remove']);
			else
				$clean[$field['name']]=form_clean($data[$field['name']],$field['length']);
				
			$_SESSION[$field['name']]=$clean[$field['name']];
			if(strlen($clean[$field['name']])==0 )
			{
				if($field['required']==true)
					$errors[$field['name']]=$field['display_name']. " is a required field";
			}
			elseif($field['reg_ex']!='' && !eregi($field['reg_ex'],$clean[$field['name']]))
			{
				$errors[$field['name']]="Invalid Value for " . $field['display_name'];
			}	
		}
		else
		{
			unset($_SESSION[$field['name']]);
			if($field['required']==true)
			{
				if(isset($field['error']))
					$errors[$field['name']]=$field['error'];
				else
					$errors[$field['name']]=$field['display_name']. " is a required field";
			}
		}
	}
	
	return $errors;

}


function get_tracking_code($session_id)
{

	/*
	<img
src="https://tbs.tradedoubler.com/report?organization=1260081&ev
ent=111331&orderNumber=____1_____&orderValue=____2____&currency=
GBP&reportInfo=f2%3D____3____" width="1" height="1"
alt="Ancestry" />

o Family Tree Maker Platinum Edition � FTMPlatinum
o Family Tree Maker Deluxe Edition � FTMDeluxe
o Subscription Gift Pack � GiftPack

CJ 

<img src="https://www.emjcd.com/u?CID=<ENTERPRISEID>&OID=<OID>&TYPE=<ACTIONID>&ITEM1=
<ITEMID>&AMT1=<AMT>&QTY1=<QTY>&CURRENCY=<CURRENCY>&METHOD=IMG" height="1"
width="20">


	*/
	//$BA_url="https://tbs.tradedoubler.com/report?organization=1260081&amp;event=111331&amp;orderNumber=";
	//$CJ_url="https://www.emjcd.com/u?CID=1500857&amp;OID=<OID>&TYPE=<ACTIONID>
	
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	$mysql=array();
	
	$mysql['session_id']=mysql_real_escape_string($session_id);
	
	$select="SELECT niceordernum FROM tbl_order_header WHERE ordernumber='{$mysql['session_id']}'";
	$result = mysql_query($select) or die('Query failed: ' . mysql_error());
	
	if($line=mysql_fetch_array($result))
		$order_number=$line['niceordernum'];
		
	//$url = $url . $order_number . "&amp;orderValue=" . getBasketTotal($session_id) . "GBP&reportInfo=";
	
	$select = "SELECT * FROM tbl_baskets WHERE sessionid='{$mysql['session_id']}'";
	$result = mysql_query($select) or die('Query failed: ' . mysql_error());
	$first=true;
	
	$BA_URL_Products="";
	$CJ_URL_Products="";
	
	$CJ_Item_No=1;
	
	
	while($line = mysql_fetch_array($result))
	{
		$recognised=false;
		
		switch($line['itemcode'])
		{			
			case 'FTMPLATINUM':
				$code='FTMPlatinum';
				$recognised=true;
			break;
			case 'FTMDELUXE':
				$code='FTMDeluxe';
				$recognised=true;
			break;
			case 'GIFTPACK':
				$code='GiftPack';
				$recognised=true;
			break;
		}
		
		if($recognised==true)
		{
			//BA code
			/*for($i=0;$i<$line['quantity'];$i++)
			{
				if($first)
				{
					$BA_URL_Products=$BA_URL_Products ."f2%3D" . $code;
					$first=false;
				}
				else
					$BA_URL_Products=$BA_URL_Products . "|f2%3D" . $code;
			}*/
			
			//BA code change
			if($first)
			{
				$BA_URL_Products=$BA_URL_Products ."f2%3D" . $code ."%26f4%3D" . $line['quantity'] ;
				$first=false;
			}
			else
				$BA_URL_Products=$BA_URL_Products . "|f2%3D" . $code ."%26f4%3D" .$line['quantity'];
					
			//CJ code
			
			//addition of VAT free price
			$price_less_VAT=round($line['price']/1.175,2);
			
			//$CJ_URL_Products=$CJ_URL_Products."&amp;ITEM{$CJ_Item_No}={$code}&amp;AMT{$CJ_Item_No}={$line['price']}&amp;QTY{$CJ_Item_No}={$line['quantity']}";
			
			$CJ_URL_Products=$CJ_URL_Products."&amp;ITEM{$CJ_Item_No}={$code}&amp;AMT{$CJ_Item_No}={$price_less_VAT}&amp;QTY{$CJ_Item_No}={$line['quantity']}";
			
			$CJ_Item_No++;
		}
		
	}
	
	$BA_url="https://tbs.tradedoubler.com/report?organization=1260081&amp;event=111331&amp;orderNumber={$order_number}&amp;orderValue=" . round(getBasketTotal($session_id),2) . "GBP&reportInfo={$BA_URL_Products}";
	
	$CJ_url="https://www.emjcd.com/u?CID=1500857&amp;OID={$order_number}&TYPE=319670{$CJ_URL_Products}&amp;CURRENCY=GBP&amp;METHOD=IMG";
	
	
	return array("BA_URL" => $BA_url, "CJ_URL" => $CJ_url);
}

function getAltImages($product_code,$image_dir,$sub_dir="")
{
	$image_dir=$image_dir . "/" . $product_code ."/".  $sub_dir;
	$count=0;

	if(is_dir($image_dir))
	{

		//search image directory for alternative images
		$all_images=scandir($image_dir);
		foreach($all_images as $filename)
		{

			if($filename==".." || $filename==".")
				unset($all_images[$count]);
			$count++;
		}
		return $all_images;
	}

	else
		return NULL;
}


//SEO Functions
function get_page_meta_tags($page,$keywords,$description,$title,$variable="")
{
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql=array();
	$mysql['page']=mysql_real_escape_string($page,$link);
	$mysql['variable']=mysql_real_escape_string($variable,$link);

	if(strlen($variable)>0)
		$query="SELECT keywords,description,title FROM meta_tags WHERE page='{$mysql['page']}' AND variable='{$mysql['variable']}'";
	else
		$query="SELECT keywords,description,title FROM meta_tags WHERE page='{$mysql['page']}'";

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	if($line=mysql_fetch_array($result,MYSQL_ASSOC))
	{
		$keywords=$line['keywords'];
		$description=$line['description'];
		$title=$line['title'];
		return true;
	}
	else
		return false;
}

function getProductName($number)
{
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql=array();
	$mysql['number']=mysql_real_escape_string($number,$link);
	$query="SELECT inetsdesc FROM tbl_products WHERE number='{$mysql['number']}'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	if($line=mysql_fetch_array($result,MYSQL_ASSOC))
		$name=$line['inetsdesc'];
	else
		$name="";
	return $name;
}


function Restricted_shipping($basketid,$country)
{

	//Check if a product is restricted sale in a country

	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql=array();

	$mysql['basketid']=mysql_real_escape_string($basketid,$link);
	//$mysql['country']=mysql_real_escape_string($country,$link);

	//$query="SELECT country FROM tbl_baskets LEFT JOIN ship_restrict ON trim(itemcode)=trim(product) WHERE sessionid='{$mysql['basketid']}'";
	//echo $query;
	$query="SELECT itemcode FROM tbl_baskets WHERE sessionid='{$mysql['basketid']}'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	while($line=mysql_fetch_array($result,MYSQL_ASSOC))
	{
		$mysql['itemcode']=mysql_real_escape_string($line['itemcode']);

		$ship_query="SELECT country FROM ship_restrict WHERE product='{$mysql['itemcode']}'";
		//echo $ship_query;
		$ship_result=mysql_query($ship_query) or die('Query failed: ' . mysql_error());
		if(mysql_num_rows($ship_result)>0)
		{
			$ship_line=mysql_fetch_array($ship_result,MYSQL_ASSOC);
			//echo $ship_line['country'] . "=" .$country;
			if($ship_line['country']!=$country)
				return false;
		}

	}
		return true;
}

function SpecialShipping($basketid,$country)
{
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql=array();

	$mysql['basketid']=mysql_real_escape_string($basketid,$link);

	$query="SELECT shipping FROM ship_restrict LEFT JOIN tbl_baskets ON itemcode=product WHERE sessionid='{$mysql['basketid']}'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	if(mysql_num_rows($result)>0)
	{
		$line=mysql_fetch_array($result,MYSQL_ASSOC);
		return $line['shipping'];
	}
}

function getDepartmentTree($department)
{
	$department_text="";
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');



	while(strlen(trim($department))>0)
	{
		//Clean vars for use with mysql
		$mysql=array( );
		$mysql['department']=mysql_real_escape_string($department,$link);

		$query="SELECT name,deptcode,under FROM tbl_departments WHERE deptcode='{$mysql['department']}'";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$line=mysql_fetch_array($result);

		$department=$line['under'];
		if($department_text=="")
			$department_text= $line['name'] ." > ";
		else
			$department_text= $line['name'] . " > " .$department_text;
	}
	return $department_text;
}


function UniqueToken()
{
	return $token=md5(uniqid(rand(),TRUE));
}

function ValidToken($token1,$token2,$tokentime,$LifeTime=5)
{
	//clean the input as it will allways be POST/GET values
	//passed in

	$clean=array();
	$clean['token1']=form_clean($token1,32);
	$clean['token2']=form_clean($token2,32);
	$clean['tokentime']=form_clean($tokentime,20);
	$clean['LifeTime']=form_clean($LifeTime,2);

	//get the difference between now and the time the token
	//was generated (in secs)
	$time_between = time() - $clean['tokentime'];

	//convert to minutes
	$time_between = $time_between/60;
	//echo $token1 . "<br />" . $token2;
	if($clean['LifeTime']!=0)
	{

		//Time between tokens is important

		if( ($clean['token1']==$clean['token2']) && ($time_between<$clean['LifeTime']) )
			return true;
		else
			return false;
	}
	else
	{

		//Ignore the time
		if( $clean['token1']==$clean['token2'])
			return true;
		else
			return false;
	}
}

function Escaped_Echo($output)
{
	//Convert any html encoding in the string the the relevant characters
	$output=html_entity_decode($output,ENT_QUOTES,'UTF-8');
	//convert all of string to html representation
	$output=htmlentities($output,ENT_QUOTES,'UTF-8');
	//convert all newlines to <br />s
	$output=nl2br($output);
	echo $output;
}

//Classes
class featprod {
	private $number;
	private $name;
	private $inetshortd;
	private $price;
	private $inetthumb;
	private $dept;
	private $master;

	function __construct($number,$link,$description="")
	{

		$this->number=$number;
		// Performing SQL query

		//clean vars for use with Mysql
		$mysql = array( );
		$mysql['number'] = mysql_real_escape_string($number,$link);

		$query = "SELECT deptcode,tbl_products.inetthumb,tbl_products.number, tbl_products.inetsdesc,tbl_products.inetshortd, tbl_products.price, tbl_products.compareprice, tbl_products.inetfdesc, tbl_products.inetimage, tbl_products.relateditems, tbl_products.mastersub ,tbl_products.units FROM tbl_products,tbl_products_departments WHERE tbl_products.number = '{$mysql['number']}' AND tbl_products.number=tbl_products_departments.number";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		$line=mysql_fetch_array($result);

		$this->name=formatdescription($line['inetsdesc']);
		if($description=="")
			$this->inetshortd=formatdescription($line['inetshortd']);
		else
			$this->inetshortd=formatdescription($description);

		if ($line["price"] < $line["compareprice"]) {
			$this->price= "<span class='pricesale'>Was: " . formatcurrency($line["compareprice"]) . "</span> <span class=\"price\">Now: " . formatcurrency($line["price"]) . "</span>";
		}
		else
		{
			$this->price="<span class=\"price\">" . formatcurrency($line['price']) ;
		}
		$euro=ConvertToEuro($line['price']);

		$this->price=$this->price ." (". $euro . ") -approx*" . "</span>";

		$this->inetthumb=GetImageName($line["inetthumb"],"Thumb","images/products/",$line["number"]);
		$this->dept=$line['deptcode'];
		$this->master=GetMaster($this->dept);

	}

	public function display()
	{
		if(strlen(trim($this->number))>0)
		{
			$encoded = urlencode ( htmlentities("{$this->number}",ENT_QUOTES,'UTF-8'));
			echo "<div class=\"featimg\"><a href=\"productdetail.php?master={$this->master}&amp;dept={$this->dept}&amp;number={$encoded}\">";
			echo "<img src=\"{$this->inetthumb}\" alt=\"{$this->name}\" />";
			echo "</a></div>";
			echo "<div class=\"featdesc\">";

				echo "<a href=\"productdetail.php?master={$this->master}&amp;dept={$this->dept}&amp;number={$encoded}\">{$this->name}</a>";
				echo "<br />";

				echo $this->inetshortd;
				echo "<br />";

				//echo $this->price;

				echo "<a href=\"productdetail.php?master={$this->master}&amp;dept={$this->dept}&amp;number={$encoded}\"><img src=\"images/details.gif\" alt=\"Details\" /></a>";

				echo "<br />";
			echo "</div>";
		}
	}

	public function getName()
	{
		return $this->name;
	}

	public function getDesc()
	{
		return $this->inetshortd;
	}

	public function getNumber()
	{
		return $this->number;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function getDept()
	{
		return $this->dept;
	}

	public function getMaster()
	{
		return $this->master;
	}

	public function getThumb()
	{
		return $this->inetthumb;
	}
}

//Old GetMaster function.

/*function GetMaster($deptcode)
{
	//find and return the top level dept
	//that the passed dept is under

	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	//Clean vars for use with mysql
	$mysql=array( );
	$mysql['deptcode'] = mysql_real_escape_string($deptcode,$link);

	$query="SELECT under,name,deptcode from  tbl_departments WHERE deptcode={$mysql['deptcode']}";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line=mysql_fetch_array($result);
	$masterdeptcode=trim($line['deptcode']);

	$under=trim($line['under']);

	if($under==0)
		return $deptcode;
	else
		return $under;

}
*/

function GetMaster($deptcode)
{

	//Edited 07-09-06
	//Now gets top level department for all level of departments.

	//find and return the top level dept
	//that the passed dept is under

	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	$under=1;

	while($under!=0)
	{
		$mysql=array( );
		$mysql['deptcode'] = mysql_real_escape_string($deptcode,$link);

		$query="SELECT under,name,deptcode from  tbl_departments WHERE deptcode={$mysql['deptcode']}";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$line=mysql_fetch_array($result);
		$return_deptcode=trim($line['deptcode']);

		$under=trim($line['under']);
		$deptcode=$under;
	}

	return $return_deptcode;
}

function GetDept($productcode)
{
	$deptcode=0;
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');
	$mysql=array();
	$mysql['productcode']=mysql_real_escape_string($productcode,$link);

	$query="SELECT deptcode from tbl_products_departments WHERE number='{$mysql['productcode']}'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	if(mysql_num_rows($result))
	{
		$line=mysql_fetch_array($result);
		$deptcode=$line['deptcode'];

	}
	return $deptcode;
}


//Functions
function CheckShippingRate($productupc)
{
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	//Clean vars for use with mysql
	$mysql=array( );
	$mysql['productupc'] = mysql_real_escape_string($productupc,$link);

	$query="SELECT rate FROM product_shipping WHERE number='{$mysql['productupc']}'";

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line=mysql_fetch_array($result);
	$price=$line['rate'];
	if(is_numeric($price))
		return "<span class=\"shippinginfo\">�" . $price ." UK shipping available on this item. <a href=\"shippingterms.php\">Terms and conditions apply.</a></span> ";
	else
		return "";
}


function ShippingRate($basketid)
{


	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql=array( );
	$mysql['basketid']=mysql_real_escape_string($basketid,$link);

	//check basket for items with different shipping rate
	//total it and then return total
	$query ="SELECT Count(*) NumProds,SUM(rate*quantity) Total FROM tbl_baskets RIGHT JOIN product_shipping ON itemcode=number WHERE sessionid='{$mysql['basketid']}'";

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line=mysql_fetch_array($result);
	$query_all="SELECT * FROM tbl_baskets WHERE sessionid='$basketid'";
	$result_all = mysql_query($query_all) or die('Query failed: ' . mysql_error());
	$total_all = mysql_num_rows($result_all);
	$NumProducts=$line['NumProds'];

	if($total_all<=$NumProducts)
	{

		$Total=$line['Total'];
	}
	else
	{
		$Total=0;
	}

	return $Total;
	//return 5;
}




//check a product exists
function CheckProduct($productupc)
{
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql=array( );
	$mysql['productupc']=mysql_real_escape_string($productupc,$link);

	$query="SELECT number from tbl_products WHERE number='{$mysql['productupc']}'";

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	if(mysql_num_rows($result)==0)
	{
		return false;
	}
	else
	{
		return true;
	}
}



//Check Expected date

function CheckExpectDate($ExpectedOn,$PrintDate)
{
	//Check the expect date is in the future
	//if so return true
	//else return false
	//return a nice formated expect date
	//in uk format in the PrintDate var


	//$ExpectedOn=$line['expectdate'];
	$Year=substr($ExpectedOn,0,4);
	$Month=substr($ExpectedOn,4,2);
	$Day=substr($ExpectedOn,6,2);

	$ExpectedDate="{$Month}/{$Day}/{$Year}";
	$TodayDate=date("m/d/Y");

	$ExpectedOn=strtotime($ExpectedDate);
	$Today=strtotime($TodayDate);

	$Diff=$ExpectedOn-$Today;
	if($Diff<=0)
	{
		return false;
		$PrintDate="";
	}
	else
	{
		$PrintDate="{$Day}/{$Month}/{$Year}";
		return true;

	}
}


//Update Stock
function StockCheckBasket($SessionId,$DB_Name,$DB_User,$DB_Password,$DB_Host)
{
	//check basket checking that all the required items have stock
	//or they are pre-order
	//return true if all in stock or pre-order
	//else return false
	$link = mysql_connect($DB_Host,$DB_User,$DB_Password) or die('Could not connect: ' . mysql_error());
	mysql_select_db($DB_Name) or die('Could not select database');

	$mysql=array( );
	$mysql['SessionId']=mysql_real_escape_string($SessionId,$link);

	$query= "SELECT itemcode,number,units,quantity,presell,sessionid,(units-quantity) as Diff FROM tbl_products,tbl_baskets WHERE   sessionid='{$mysql['SessionId']}' AND itemcode=number  AND units<quantity AND presell!='pre'";
	$result=  mysql_query($query) or die('Query failed: ' . mysql_error());

	if(mysql_num_rows($result)>0)
	{

		mysql_close($link);
		mysql_free_result($result);
		return false;
	}
	else
	{

		mysql_close($link);
		mysql_free_result($result);
		return true;
	}


}
function CheckStock($UnitsInStock,$UnitsWanted,$PreOrder,$ExpectedOn,$Message)
{
	//Called on the basket list page
	//needs to return the max number of units allowed
	//and a message to notify the user

	//3 conditions

	//enough stock - return the units wanted and nothing else(blank message)
	$PrintDate="";

	if(CheckExpectDate($ExpectedOn,&$PrintDate))
		$DateMessage="Currently not available. More supplies arriving soon. Expected $PrintDate";
	else
		$DateMessage="Currently not available. More supplies arriving soon. ";


	if($UnitsInStock>=$UnitsWanted)
		return $UnitsWanted;
	else
	{
		if( ($PreOrder=="pre")||($PreOrder=="PRE") )
		{
			if($UnitsInStock>0)
				$Message="$UnitsInStock in stock. $DateMessage. Order now, will be shipped when all stock is available";
			else
				$Message="Currently not available. More supplies arriving soon. $DateMessage. Order now, item will be shipped when all stock available. ";

			return $UnitsWanted;
		}
		else
		{
			if($UnitsInStock>0)
				$Message="$UnitsInStock in stock. Please reduce the quantity or remove from basket";
			else
				$Message="Currently not available. More supplies arriving soon. Please remove item from basket";
			return $UnitsInStock;
		}
	}

	//not enough stock - item on pre order - return the number of units wanted
	//return a message to say there are x in stock but you can
	//pre order the x amount which will be back in stock on expected date

	//not enough stock - item not allowed for pre-order
	//return the max units allowed
	//message to explain this is max in stock


}

function UpdateStock($SessionID,$DB_Name,$DB_User,$DB_Password,$DB_Host)
{
	$link = mysql_connect($DB_Host,$DB_User,$DB_Password) or die('Could not connect: ' . mysql_error());
	mysql_select_db($DB_Name) or die('Could not select database');

	$mysql=array( );
	$mysql['SessionID']=mysql_real_escape_string($SessionID,$link);

	//select productcode,amount from the basket table where the id is the same as basket id
	$query = "SELECT sessionid,quantity,itemcode FROM tbl_baskets WHERE sessionid='{$mysql['SessionID']}'";

	$basketresult = mysql_query($query) or die('Query failed: ' . mysql_error());

	//loop through the result of this
	while($line=mysql_fetch_array($basketresult,MYSQL_ASSOC))
	{
		$Reduction=$line['quantity'];
		$Product=$line['itemcode'];

		$mysql['Reduction']=mysql_real_escape_string($Reduction,$link);
		$mysql['Product']=mysql_real_escape_string($Product,$link);

		//update stock in product table
		$update = "UPDATE tbl_products SET units=(units-{$mysql['Reduction']}) WHERE number='{$mysql['Product']}'";
		//echo $update;

		$updateresult = mysql_query($update) or die('Query failed: ' . mysql_error());
	}

	mysql_close($link);
	mysql_free_result($basketresult);

}



//Check for stock dates
function CheckStockDate($ExpectedOn,$Units,$MasterSub="S")
{
	$AText="";
	if($MasterSub!="M")
	{
		if($Units<1)
		{
			//$ExpectedOn=$line['expectdate'];
			$Year=substr($ExpectedOn,0,4);
			$Month=substr($ExpectedOn,4,2);
			$Day=substr($ExpectedOn,6,2);

			$ExpectedDate="{$Month}/{$Day}/{$Year}";
			$TodayDate=date("m/d/Y");

			$ExpectedOn=strtotime($ExpectedDate);
			$Today=strtotime($TodayDate);

			$Diff=$ExpectedOn-$Today;

			$PrintExpectDate="{$Day}/{$Month}/{$Year}";

			//echo $ExpectedDate . "<BR>";
			//echo $TodayDate . "<BR>";

			if($Diff<=0)
			{
				//$AText="<img src=\"images/outof_stock.gif\" alt=\"Out Of Stock\" /><br /> <b>Available soon</b> - Order now to reserve item <br />" . NOCHARGE  ;
				$AText="<b>Currently not available.</b> More supplies arriving soon.";
			}
			else
			{
				//$AText="<img src=\"images/outof_stock.gif\" alt=\"Out Of Stock\" /><br /> <b>Available soon - Expected: $PrintExpectDate</b>
				$AText="<b>Currently not available.</b> More supplies arriving soon. Expected: $PrintExpectDate
				<br />
				Order now to reserve item
				<br />
				". NOCHARGE
				;
			}
		}
		else
		{
			$AText="<img src=\"images/in_stock.gif\" alt=\"In Stock\" /> - Available now";
		}
	}
	else
	{

		$AText="<b>See size selection for availability</b>";
	}
		return $AText;

}
//auction ones

function AuctionEnded($EndTime)
{
	$CurrentTime=time();
	$Diff=$EndTime-$CurrentTime;
	if($Diff>0)
		return false;
	else
		return true;
}

function AuctionFormatDate($Date)
{
	$DateArray=explode(" ",$Date);
	$Day=$DateArray[0];
	$Time=$DateArray[1];

	$DayArray=explode("-",$Day);
	$Day=$DayArray[2] . "/" . $DayArray[1] . "/" . $DayArray[0];
	$NewDate= $Day ." at " . $Time ;
	return $NewDate;
}
function ConfirmPassword($Password,$UserID)
{

	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	//select auction info
	$mysql=array( );
	$mysql['UserID']=mysql_real_escape_string($UserID,$link);

	$query = "SELECT * FROM auctionaccount WHERE IdNo='{$mysql['UserID']}'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	if(mysql_num_rows($result)==1)
	{

		$line=mysql_fetch_row($result);

		if(MD5($Password)==$line[4])
			return true;
	}
	return false;
}



function GetImageName($FullPath,$Size="Thumb",$ImagePath="images/products/",$ProductCode="",$mastersub="",$appendix="")
{
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql=array( );
	$mysql['ProductCode']=mysql_real_escape_string($ProductCode,$link);


	//echo $FullPath;
	//check that there is actually an image name set
	if($mastersub=="S")
	{

		//get the master code for the image
		$query="SELECT masterupc FROM tbl_products WHERE number='{$mysql['ProductCode']}'";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$line=mysql_fetch_row($result);
		$ProductCode=$line[0];

	}
	if($FullPath!="")
	{
		//echo "full path IS set";

		//split the image path up into an array
		//explode the bits seperated by : first then the slashes
		//return the last part of the array

		$imagenamearray=explode(":",$FullPath);
		$imagepart=sizeof($imagenamearray)-1;
		$image=$imagenamearray[$imagepart];

		$imagenamearray=explode("\\",$image);
		$imagepart=sizeof($imagenamearray)-1;
		$image=$imagenamearray[$imagepart];

		//check image exists and set path
		$ImagePath=$ImagePath . $image;
		//echo $ImagePath;
	}
	else
	{
	//echo "full path ISNT set";
		//Image name not set at all

		if(isset($ProductCode))
		{
			if($Size=="Large")
			{
				$ImagePath="images/products/" . $ProductCode .".jpg";
			}
			if($Size=="Thumb")
			{
				$ImagePath="images/products/" .$ProductCode ."_TN.jpg";
			}
			if($Size=="Small")
			{
				$ImagePath="images/products/" . $ProductCode . "_SM.jpg";
			}
		}
	}

	if(!(file_exists($ImagePath)))
	{
		//echo "entered bit if file dosn't exist";
			//set the image path to a blank one

			if($Size=="Large")
			{
				$ImagePath="images/products/noimage.gif";
			}
			if($Size=="Small")
			{
				$ImagePath="images/products/noimage_SM.gif";
			}
			if($Size=="Thumb")
			{
				$ImagePath="images/products/noimage_TN.gif";
			}
	}

	return $ImagePath;

}

function ConvertToEuro($Pounds)
{

	$Euros=$Pounds*1.5;
	$Euros=formatcurrency($Euros,"&euro;");

	return $Euros;
}

function ConvertToDollar($Pounds)
{

	$Dollars=$Pounds*1.75;
	$Dollars=formatcurrency($Dollars,"$");
	return $Dollars;
}

function CountryType($Country)
{

//Takes a country code in as input and returns the type of country
//i.e Europe,UK,Rest of World

	if ( ($Country=="001") || ($Country=="034"))
	{
		//classing Canada as USA as well
		//Country is in USA
		return ("USA");
	}
	if (($Country=="073"))
	{
		//Country is in UK
		return ("UK");
	}

	if(	  ($Country=="147") || ($Country=="130") ||
          ($Country=="110") || ($Country=="245") ||
          ($Country=="093") || ($Country=="091") ||
          ($Country=="070") || ($Country=="065") ||
          ($Country=="019") || ($Country=="013") ||
          ($Country=="168") || ($Country=="059") ||
          ($Country=="085") || ($Country=="118") ||
          ($Country=="173") || ($Country=="048") ||
          ($Country=="146") || ($Country=="103") ||
          ($Country=="050") ||
          ($Country=="109") || ($Country=="309") ||
          ($Country=="064")
          )
       {
       	 //Country is in EU
       //	 echo "EU MAIN";
         return ("EMAIN");

       }


	if (($Country=="312") || ($Country=="278") ||
	    ($Country=="247") || ($Country=="202") ||
	    ($Country=="191") || ($Country=="183") ||
	    ($Country=="178") || ($Country=="176") ||
	    ($Country=="164") || ($Country=="162") ||
	    ($Country=="160") || ($Country=="151") ||
	    ($Country=="148") || ($Country=="140") ||
	    ($Country=="139") || ($Country=="138") ||
	    ($Country=="136") || ($Country=="133") ||
	    ($Country=="126") || ($Country=="122") ||
	    ($Country=="115") || ($Country=="101") ||
	    ($Country=="096") || ($Country=="095") ||
	    ($Country=="094") || ($Country=="092") ||
	    ($Country=="088") || ($Country=="087") ||
	    ($Country=="086") || ($Country=="084") ||
	    ($Country=="083") || ($Country=="075") ||
	    ($Country=="062") || ($Country=="061") ||
	    ($Country=="056") || ($Country=="040") ||
	    ($Country=="036") || ($Country=="028") ||
	    ($Country=="027") || ($Country=="022") ||
	    ($Country=="020") || ($Country=="018") ||
	    ($Country=="016") || ($Country=="015") ||
	    ($Country=="012") || ($Country=="009") ||
	    ($Country=="026") ||
	    ($Country=="167") || ($Country=="184") ||
	    ($Country=="174") ||
            ($Country=="150") || ($Country=="137") ||
            ($Country=="108") || ($Country=="072") ||
            ($Country=="029")
	   )
	{
	   //country is Rest of World
	   // echo "ROW";
	   return ("ROW");


	}

 	if(($Country=="245") || ($Country=="244") ||
 	   ($Country=="243") || ($Country=="173") ||
 	   ($Country=="146") || ($Country=="118") ||
 	   ($Country=="085") ||
 	   ($Country=="064") || ($Country=="050") ||
 	   ($Country=="049") ||
 	   ($Country=="044") || ($Country=="005") ||
 	   ($Country=="123")
 	  )
 	{
          //Country is in Rest of Europe
         // echo "ROE";
 	  return ("ROE");


 	}

 	if($Country=="074")
 	{
	 	return ("GREECE");

 	}

       //OTHER shipping countries have been put into rest of world
       /*if(($Country=="184") || ($Country=="174") ||
          ($Country=="150") || ($Country=="137") ||
          ($Country=="108") || ($Country=="072") ||
          ($Country=="029") || ($Country=="029")
         )
       {
       	 //Country is classed as Other(may be anywhere but have a standard shipping price)

         return ("OTHER");
       }
       */
   //if the country passed is not caught by any of the IF statments then
   //return ERROR
   return ("ERROR");

}

function CheckShipType($ShipType,$Country)
{

//Takes in the Shipping type as a code (e.g 1)
//and the Country as a code (e.g. 001)
//It then checks that the shipping type
//is correct for that country(returns true
//else it returns false

	if ( ($Country=="001") || ($Country=="034") )
	{
		//Country is in USA + Canada
		//New Shiping types 14+15
       /*  if( ($ShipType=="USE") || ($ShipType=="USS") )
		return true;
	 else
       	 	return false;
       	 	*/
       //express taken off
     if($ShipType=="9")
		return true;
	 else
       	 	return false;


	}
	if (($Country=="073"))
	{
		//UK
         if ( ($ShipType=="1") || ($ShipType=="3") || ($ShipType=="7")  || ($ShipType=="8")  || ($ShipType=="9") )
		return true;
	 else
       	 	return false;
	}

	 if(	  ($Country=="147") || ($Country=="130") ||
          ($Country=="110") || ($Country=="245") ||
          ($Country=="093") || ($Country=="091") ||
          ($Country=="070") || ($Country=="065") ||
          ($Country=="019") || ($Country=="013") ||
          ($Country=="168") || ($Country=="059") ||
          ($Country=="085") || ($Country=="118") ||
          ($Country=="173") || ($Country=="048") ||
          ($Country=="146") || ($Country=="103") ||
          ($Country=="050") ||
          ($Country=="109") || ($Country=="309") ||
          ($Country=="064")
          )
       {
       	 //Country is is in Europe Mainland
         if( ($ShipType=="4") || ($ShipType=="5"))
		return true;
	 else
       	 	return false;

       }


	if (($Country=="312") || ($Country=="278") ||
	    ($Country=="247") || ($Country=="202") ||
	    ($Country=="191") || ($Country=="183") ||
	    ($Country=="178") || ($Country=="176") ||
	    ($Country=="164") || ($Country=="162") ||
	    ($Country=="160") || ($Country=="151") ||
	    ($Country=="148") || ($Country=="140") ||
	    ($Country=="139") || ($Country=="138") ||
	    ($Country=="136") || ($Country=="133") ||
	    ($Country=="126") || ($Country=="122") ||
	    ($Country=="115") || ($Country=="101") ||
	    ($Country=="096") || ($Country=="095") ||
	    ($Country=="094") || ($Country=="092") ||
	    ($Country=="088") || ($Country=="087") ||
	    ($Country=="086") || ($Country=="084") ||
	    ($Country=="083") || ($Country=="075") ||
	    ($Country=="062") || ($Country=="061") ||
	    ($Country=="056") || ($Country=="040") ||
	    ($Country=="036") || ($Country=="028") ||
	    ($Country=="027") || ($Country=="022") ||
	    ($Country=="020") || ($Country=="018") ||
	    ($Country=="016") || ($Country=="015") ||
	    ($Country=="012") || ($Country=="009")  ||
	    ($Country=="026") ||
	    ($Country=="167") ||
	    ($Country=="184")|| ($Country=="174") ||
            ($Country=="150") || ($Country=="137") ||
            ($Country=="108") || ($Country=="072") ||
            ($Country=="029")

	   )
	{
	   //country is is Rest of World
         if( ($ShipType=="6") )
		return true;
	 else
       	 	return false;
	}

 		if(($Country=="245") || ($Country=="244") ||
 	   ($Country=="243") || ($Country=="173") ||
 	   ($Country=="146") || ($Country=="118") ||
 	   ($Country=="085") ||
 	   ($Country=="064") || ($Country=="050") ||
 	   ($Country=="049") ||
 	   ($Country=="044") || ($Country=="005") ||
 	   ($Country=="123")
 	  )
 	{
          //Country is in Rest of Europe
         if( ($ShipType=="4") || ($ShipType=="5"))
		return true;
	 else
       	 	return false;

 	}

 	if($Country=="074")
 	{
	 	if($ShipType=="11")
	 		return true;
	 	else
	 		return false;
 	}

       //OTHER SHIPPING countries have been moved to Rest of WOrld
       /*if(($Country=="184") || ($Country=="174") ||
          ($Country=="150") || ($Country=="137") ||
          ($Country=="108") || ($Country=="072") ||
          ($Country=="029") || ($Country=="029")
         )
       {
       	 //Country is classed as Other(may be anywhere but have a standard shipping price)
       	 if($ShipType=="13")
       	 	return true;
       	 else
       	 	return false;


       }
 	*/
   return false;
}

	function formatcurrency($value,$PreString="&pound;") {
		$currency = sprintf('%.2f', $value);
		$currency = $PreString . $currency;
		return($currency);
	}


function displayrelateditems($relateditems) {
		$token = strtok($relateditems, ",");
		$display = "";
		$html=array();

		while ($token !== false) {
			// Run Query
			$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
			mysql_select_db(DB_NAME) or die('Could not select database');

			$mysql=array();
			$mysql['token']=mysql_real_escape_string($token,$link);

			// Performing SQL query
			$query = "SELECT tbl_products.number, tbl_products.price, tbl_products.inetsdesc FROM tbl_products WHERE number = {$mysql['token']} AND inetsell = true AND units > 0;";
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());

			// Closing connection
			mysql_close($link);

			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$html['number']=htmlentities($line['number'],ENT_QUOTES,'UTF-8');
				$html['inetsdesc']=htmlentities($line['inetsdesc'],ENT_QUOTES,'UTF-8');
				$price= formatcurrency($line["price"],ENT_QUOTES,'UTF-8');
				$html['price']=htmlentities($price,ENT_QUOTES,'UTF-8');

				$display = $display . "<p><a href='productdetail.php?number={$html['number']}'>{$html['inetsdesc']}</a> " . $html['price'] . "<br />";
			}

			$token = strtok(",");
			mysql_free_result($result);
		}

		if (strlen($display) > 0) {
			$display = "<p>You may also like:</p>" . $display;
		}

		return $display;
	}


function countitems($deptcode) {
		// Run Query
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		$mysql=array( );
		$mysql['deptcode']=mysql_real_escape_string($deptcode,$link);

		// Performing SQL query
		$query = "SELECT tbl_products.number FROM tbl_products INNER JOIN tbl_products_departments ON tbl_products.number=tbl_products_departments.number WHERE deptcode = {$mysql['deptcode']} AND tbl_products.inetsell = TRUE AND tbl_products.mastersub <> 'S';";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		// Closing connection
		mysql_close($link);

		$numberofitems = mysql_num_rows($result);

		mysql_free_result($result);

		return $numberofitems;
	}

	function displaysizes($masterupc,$Label="",$SorryString="Sorry there are no sizes currently in stock",$Alt="") {
		// Run Query
		//Label is used to pass a String (Html formating should include \'s to allow "
		//String is displayed after the drop down box
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		$mysql=array( );
		$mysql['masterupc']=mysql_real_escape_string($masterupc,$link);

		// Performing SQL query
		$query = "SELECT  DISTINCT(tbl_products.number), tbl_products.size,price FROM tbl_products LEFT JOIN tbl_products_departments ON tbl_products.number=tbl_products_departments.number WHERE  ( (tbl_products.units > 0) OR (tbl_products.presell='pre')) AND tbl_products.inetsell = TRUE AND tbl_products.mastersub = 'S' AND tbl_products.masterupc = '{$mysql['masterupc']}' ORDER BY tbl_products_departments.disp_seq ASC;";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		// Closing connection
		mysql_close($link);

		$numberofsizes = mysql_num_rows($result);
		$display = "";
		$count=0;
		if ($numberofsizes > 0) {

			$display = $Label .  $display . "<select id=\"size_select\"  name=\"upc\">";
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$count++;
				$pricetoshow=formatcurrency($line["price"]);
				switch ($Alt)
				{
					case "Euro":
					$Euro=ConvertToEuro($line["price"]);
					$pricetoshow = $pricetoshow . ("(".$Euro.")*");
					break;
					case "Dollar":
					$Dollar=ConvertToDollar($line["price"]);
					$pricetoshow = $pricetoshow . ("(".$Dollar.")*");
					break;
					default:

					break;
				}
				$html=array();
				$html['number']=html_entity_decode($line['number'],ENT_QUOTES,'UTF-8');
				$html['number']=htmlentities($html['number'],ENT_QUOTES,'UTF-8');

				$html['size']=html_entity_decode($line['size'],ENT_QUOTES,'UTF-8');
				$html['size']=htmlentities($html['size'],ENT_QUOTES,'UTF-8');

				$html['pricetoshow']=html_entity_decode($pricetoshow,ENT_QUOTES,'UTF-8');
				$html['pricetoshow']=htmlentities($html['pricetoshow'],ENT_QUOTES,'UTF-8');

				if($line['size']==DEFAULTSIZE)
				{
					$display = $display . "<option selected=\"true\" value=\"" . $html['number'] . "\">". $html['size'] . " - " . $html['pricetoshow'] . "</option>";
					$selected=true;
				}
				else
				{

					$display = $display . "<option  value=\"" . $html['number'] . "\">". $html['size'] . " - " . $html['pricetoshow'] . "</option>";
				}
			}
			$display = $display . "</select>" ;
			$display = $display . "<input id=\"detail_add_to_basket\" type=\"image\" src=\"images/addtobasket.gif\" alt=\"Add To Basket\" />";
		}

		else {
			$html['SorryString']=htmlentities($SorryString,ENT_QUOTES,'UTF-8');
			echo  $html['SorryString'];
		}

		mysql_free_result($result);

		return $display;
	}

	function totalpages($numberofproducts, $prodsperpage) {
		$pages = floor(($numberofproducts-1)/$prodsperpage)+1;
		return $pages;
	}

	function clean($input, $maxlength) {
		$input = substr($input, 0, $maxlength);
		$input = EscapeShellCmd($input);
		return ($input);
	}

	function form_clean($input,$maxlength,&$remove=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","-","[","]","/",".","'","\""))
	{

		foreach ($remove as $char)
		{
			$input=str_replace($char,"",$input);
		}

		$input=trim($input);

		$input=substr($input,0,$maxlength);
		return $input;
	}

	function mysql_clean($input, $maxlength,$link) {
		$input = substr($input, 0, $maxlength);
		$input = mysql_real_escape_string($input,$link);
		return ($input);
	}

	function sendpass($email, $password, $sitename) {
		$mailbody = "You have received this email because you have requested your password be sent to you.

		Username: $email
		Password: $password

		Regards,

		$sitename";

		// Send the email to the customer so they can verify their account
		mail($email, "Password Request", $mailbody);
	}

	// Function checks the email is in the correct format

	function validateemail($emailaddress) {
	//This function is also duplicated in the checkoutproccess1.php page
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $emailaddress)) {
			return true;
		}
		else {
			return false;
		}
	}


	// Function checks if the email is already in the database

	function checkduplication($emailaddress) {
		// Search to see if the product is already in the basket
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		$mysql=array();
		$mysql['emailaddress']=mysql_real_escape_string($emailaddress,$link);


		// Performing SQL query
		$query = "SELECT email FROM tbl_login WHERE email= '{$mysql['emailaddress']}';";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		$duplicate = false;

		if (mysql_num_rows($result) > 0) {
			$duplicate = true;
		}

		// Closing connection
		mysql_close($link);
		mysql_free_result($result);

		return $duplicate;
	}

	// Function checks if the email is already in the database

	function getDepartmentName($deptcode) {
		// Search to see if the product is already in the basket
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		$mysql=array( );
		$mysql['deptcode']=mysql_real_escape_string($deptcode,$link);

		// Performing SQL query
		$query = "SELECT name FROM tbl_departments WHERE deptcode= '{$mysql['deptcode']}';";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		$duplicate = false;

		if (mysql_num_rows($result) > 0) {
			$duplicate = true;
		}

		$deptname = "Search Results";

		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$deptname = $line['name'];
		}

		// Closing connection
		mysql_close($link);
		mysql_free_result($result);

		return htmlentities($deptname);
	}

	$session = session_id();


	function getBasketTotal($session) {
		// Connecting, selecting database
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		$mysql=array( );
		$mysql['session']=mysql_real_escape_string($session,$link);

		// Performing SQL query
		$query = "SELECT sum( (price * ( (100 - discount) / 100 ) ) * quantity) AS baskettotal FROM tbl_baskets WHERE sessionid = '{$mysql['session']}'";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());



		$line = mysql_fetch_row($result);
		$baskettotal = $line[0];

		// Closing connection
		//causes errors on some pages
		//mysql_close($link);

		return $baskettotal;
	}

	function getShippingCost($shippingid) {
		// Connecting, selecting database
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		$mysql=array( );
		$mysql['shippingid']=mysql_real_escape_string($shippingid,$link);

		// Performing SQL query
		$query = "SELECT price FROM tbl_shipping WHERE code = '{$mysql['shippingid']}'";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		// Closing connection
		mysql_close($link);

		$line = mysql_fetch_row($result);
		$price = $line[0];

		return $price;
	}




function SendAnEmail($body,$To,$From,$Subject,$Html=0)
{
	if($Html==1)
	{
		//setup for html email
		//$headers  = 'MIME-Version: 1.0' . "\r\n";
		//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers["MIME-Version"]="1.0";
		$headers["Content-type"]="text/html; charset=iso-8859-1";

	}

	$headers["From"]    = $From;
	$headers["Subject"] = $Subject;

	$params["host"] = "212.38.95.172";
	$params["port"] = "25";
	$params["auth"] = true;
	$params["username"] = "websmtp";
	$params["password"] = "zinger";

	// Create the mail object using the Mail::factory method
	$mail_object =& Mail::factory("smtp", $params);

	$mail_object->send($To, $headers, $body);
}



//Format Description
//Used to put <br/>, <p> etc into a description
//when pulled from mySQL.

function formatDescription($OriginalText){

//get rid of any old <p> or <br>

$OriginalText = str_replace("<BR>","",$OriginalText);
$OriginalText = str_replace("<P>","",$OriginalText);
$OriginalText = str_replace("</P>","",$OriginalText);


	//Replace any characters that should be represented using HTML tags
//eg quotes, &, � etc
//ENT_QUOTES will replace both double and single quotes



$NewText=htmlspecialchars ( $OriginalText , ENT_QUOTES);

//convert all nl(new lines) to <br/> (html line breaks)
$NewText=nl2br($NewText);

return $NewText;



}


function FormatPostCode($PostCode,$CountryCode)
{
//Take in a Postcode as string
//and the CountryCode as a number

//format a UK Postcode with space in middle
//else just return as normal
//could make it format all zipcodes/postcodes if needed


	//IF UK

	if($CountryCode=="073")
	{

		//strip all spaces
		$PostCode=ltrim($PostCode);
		$PostCode=rtrim($PostCode);

		$PostCode=str_replace(" ","",$PostCode);

		$Length=strlen($PostCode);

		$Start = substr ( $PostCode, 0 , $Length-3 );
		$End = substr ( $PostCode, $Length-3  , $Length );


		//put a space in 3 from end
		$PostCode=$Start . " " . $End;

		//make all letters caps
		$PostCode = strtoupper($PostCode);
	}

	return $PostCode;

}


function CheckoutSessionCheck()
{
	//simple function to check if all the required session variables for an
	//order are set and not blank.
	//also check basket is not empty

	if (
		(isset($_SESSION['c_country']))    && ($_SESSION['c_country']!="") &&
		(isset($_SESSION['cs_country']))   && ($_SESSION['cs_country']!="") &&
		(isset($_SESSION['c_name']))     && ($_SESSION['c_name']!="") &&
		(isset($_SESSION['c_sname']))    && ($_SESSION['c_sname']!="") &&
		(isset($_SESSION['c_add1']))     && ($_SESSION['c_add1']!="") &&
		(isset($_SESSION['c_city']))     && ($_SESSION['c_city']!="") &&
		(isset($_SESSION['c_postcode'])) && ($_SESSION['c_postcode']!="") &&
		(isset($_SESSION['c_email']))    && ($_SESSION['c_email']!="") &&
		(isset($_SESSION['c_same']))     && ($_SESSION['c_same']!="") &&
		(isset($_SESSION['cs_name']))    && ($_SESSION['cs_name']!="") &&
		(isset($_SESSION['cs_sname']))   && ($_SESSION['cs_sname']!="") &&
		(isset($_SESSION['cs_add1']))    && ($_SESSION['cs_add1']!="") &&
		(isset($_SESSION['cs_city']))    && ($_SESSION['cs_city']!="") &&
		(isset($_SESSION['cs_postcode'])) && ($_SESSION['cs_postcode']!="") &&
		(isset($_SESSION['c_phone'])) && ($_SESSION['c_phone']!="")
	)
	{
		if(getBasketTotal(session_id())==0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
}


function getrelateditems($productcode,$price,$master,$dept,$relateditems="",$MaxReturn=4,$MaxDescLength=24)
{
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql=array( );
	$mysql['productcode']=mysql_real_escape_string($productcode,$link);
	$mysql['master']=mysql_real_escape_string($master,$link);
	$mysql['dept']=mysql_real_escape_string($dept,$link);
	$mysql['MaxReturn']=mysql_real_escape_string($MaxReturn,$link);

	$numberofprod=0;
	$count=0;
	if(strlen($relateditems)>0)
	{
		$token = strtok($relateditems, ",");
		while ( ($token !== false) && ($count<$MaxReturn) )  {
			$mysql['token']=mysql_real_escape_string(trim($token),$link);

			$query= "SELECT tbl_products.number,
							under,inetsdesc,
							inetthumb,price,
							tbl_products.name,
							inetshortd,
							tbl_products_departments.deptcode,
							tbl_products.mastersub
							FROM
							tbl_products
							LEFT JOIN tbl_products_departments on tbl_products.number=tbl_products_departments.number
							LEFT JOIN tbl_departments on tbl_departments.deptcode=tbl_products_departments.deptcode
							WHERE (units>0  OR presell='pre' OR mastersub='M' ) AND mastersub!='S'
							AND inetsell=true
							AND tbl_products.number='{$mysql['token']}'
							ORDER BY units DESC
							LIMIT 1";
		//echo $query;
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		if($line=mysql_fetch_array($result,MYSQL_BOTH))
		{
			if(strlen($line['name'])>$MaxDescLength)
			{
				$prodname=substr($line['inetsdesc'],0,$MaxDescLength) . ".." ;
			}
			else
				if(strlen($line['name'])<10)
					$prodname=$line['inetsdesc'] . "&nbsp;&nbsp;&nbsp;";
				else
					$prodname=$line['inetsdesc'];
			?>

			<?php $master=GetMaster($line['under']); ?>

			<div class="relateditem">

			<?php
			if($line['mastersub']=="M")
			{
			?>
			<a href="productdetail.php?master=<?php Escaped_Echo($master)?>&amp;dept=<?php Escaped_Echo($line['deptcode']);?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>">
			<img class="relateditem_addtobasket" src="images/addtobasket_small.gif" alt="Add <?php echo formatdescription($line["inetsdesc"]); ?> To Basket" border="0" />
			</a>
			<?php
			}
			else
			{
			?>
				<a href="addtobasket.php?code=<?php echo urlencode ( htmlentities("{$line['number']}" )) ?>"><img class="relateditem_addtobasket" src="images/addtobasket_small.gif" alt="Add <?php echo formatdescription($line["inetsdesc"]); ?> To Basket" border="0" /></a>
			<?php
			}
			?>

			<a href="productdetail.php?master=<?php Escaped_Echo($master)?>&amp;dept=<?php Escaped_Echo($line['deptcode']);?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>">
				<img src="<?php Escaped_Echo(GetImageName($line["inetthumb"],"Thumb","images/products/",$line["number"]));?>"  alt="<?php echo formatdescription($line["inetsdesc"]); ?>" />
			</a>
			<a href="productdetail.php?master=<?php Escaped_Echo($master)?>&amp;dept=<?php Escaped_Echo($line['deptcode']);?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>">
				<?php echo $prodname;?>
			</a>

			</div>
		<?php
		}

		$numberofprod=$numberofprod+mysql_num_rows($result);
		$count++;
		$token = strtok(",");
		}
	}

	if($numberofprod==0)
	{
		//select 3 products to display as related items


		$LowerPrice= round( ($price-($price/2.5)),2);
		$HigherPrice= round( ($price+($price/1.2)),2);

		$mysql['LowerPrice']=mysql_real_escape_string($LowerPrice,$link);
		$mysql['HigherPrice']=mysql_real_escape_string($HigherPrice,$link);

		// Performing SQL query
		$query = "SELECT mastersub,under,inetsdesc,inetthumb,tbl_products.number,price,tbl_products.name,inetshortd,tbl_products_departments.deptcode FROM tbl_products LEFT JOIN tbl_products_departments on tbl_products.number=tbl_products_departments.number LEFT JOIN tbl_departments on tbl_departments.deptcode=tbl_products_departments.deptcode WHERE price >={$mysql['LowerPrice']} AND  price<={$mysql['HigherPrice']} AND tbl_products_departments.deptcode='{$mysql['dept']}' AND tbl_products.number!='{$mysql['productcode']}' AND (units>0  OR presell='pre' OR mastersub='M') AND inetsell=true AND mastersub!='S' Order By units DESC LIMIT {$mysql['MaxReturn']} ";
		//$query = "SELECT tbl_products.number,name,price,inetshortd,deptcode FROM tbl_products LEFT JOIN tbl_products_departments on tbl_products.number=tbl_products_departments.number WHERE  deptcode=$dept LIMIT 3;  ";
		//echo "First Query";
		//echo $query. "\n";


		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$numberofprod=mysql_num_rows($result);

		//echo "Number of prod" . $numberofprod . "\n";
		if(mysql_num_rows($result)==0)
		{
		//echo "Second Query";
			//do another query
			$query = "SELECT mastersub,under,inetsdesc,inetthumb,tbl_products.number,price,tbl_products.name,inetshortd,tbl_products_departments.deptcode FROM tbl_products LEFT JOIN tbl_products_departments on tbl_products.number=tbl_products_departments.number LEFT JOIN tbl_departments on tbl_departments.deptcode=tbl_products_departments.deptcode WHERE price >={$mysql['LowerPrice']} AND  price<={$mysql['HigherPrice']} AND under='{$mysql['master']}' AND tbl_products.number!='{$mysql['productcode']}' AND (units>0  OR presell='pre' OR mastersub='M') AND inetsell=true AND mastersub!='S' Order By units DESC LIMIT {$mysql['MaxReturn']} ";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		//echo $query;
		if(mysql_num_rows($result)==0)
		{
			//still no items
			//last chance
			//ignore all price limits
			$query = "SELECT mastersub,under,inetsdesc,inetthumb,tbl_products.number,price,tbl_products.name,inetshortd,tbl_products_departments.deptcode FROM tbl_products LEFT JOIN tbl_products_departments on tbl_products.number=tbl_products_departments.number LEFT JOIN tbl_departments on tbl_departments.deptcode=tbl_products_departments.deptcode WHERE under='{$mysql['master']}' AND tbl_products.number!='{$mysql['productcode']}' AND (units>0  OR presell='pre' OR mastersub='M') AND inetsell=true AND mastersub!='S' Order By units DESC LIMIT {$mysql['MaxReturn']} ";
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		}
		}
	}
	while($line=mysql_fetch_array($result,MYSQL_BOTH))
	{
		if(strlen($line['name'])>$MaxDescLength)
		{
			$prodname=substr($line['inetsdesc'],0,$MaxDescLength) . ".." ;
		}
		else
			if(strlen($line['name'])<10)
				$prodname=$line['inetsdesc'] . "&nbsp;&nbsp;&nbsp;";
			else
				$prodname=$line['inetsdesc'];
	?>
		<?php $master=GetMaster($line['under']); ?>
		<div class="relateditem">
		<?php
		if($line['mastersub']=="M")
		{
		?>
		<a href="productdetail.php?master=<?php Escaped_Echo($master)?>&amp;dept=<?php Escaped_Echo($line['deptcode']);?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>">
		<img class="relateditem_addtobasket" src="images/addtobasket_small.gif" alt="Add <?php echo formatdescription($line["inetsdesc"]); ?> To Basket" border="0" />
		</a>
		<?php
		}
		else
		{
		?>
		<a href="addtobasket.php?code=<?php echo urlencode ( htmlentities("{$line['number']}" )) ?>"><img class="relateditem_addtobasket" src="images/addtobasket_small.gif" alt="Add <?php echo formatdescription($line["inetsdesc"]); ?> To Basket" border="0" /></a>

		<?php
		}
		?>
		<a href="productdetail.php?master=<?php Escaped_Echo($master)?>&amp;dept=<?php Escaped_Echo($line['deptcode']);?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>">
			<img src="<?php Escaped_Echo(GetImageName($line["inetthumb"],"Thumb","images/products/",$line["number"]));?>"  alt="<?php echo formatdescription($line["inetsdesc"]); ?>" />
		</a>
		<a href="productdetail.php?master=<?php Escaped_Echo($master)?>&amp;dept=<?php Escaped_Echo($line['deptcode']);?>&amp;number=<?php echo urlencode ( htmlentities("{$line['number']}") )  ;  ?>">
			<?php Escaped_Echo($prodname);?>
		</a>

		</div>

	<?php
	}


	// Closing connection
	//mysql_close($link);

}


function CleanQuotes($text)
{

	$text=str_replace("'","\'",$text);
	$text=str_replace('"','\"',$text);
	return $text;
}

function SubDepts($dept)
{
	if(is_numeric($dept))
	{
		$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		$mysql=array();
		$mysql['dept']=mysql_real_escape_string($dept,$link);

		$query="SELECT deptcode FROM tbl_departments WHERE under={$dept} AND display=1";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$number_of_depts=mysql_num_rows($result);

		return $number_of_depts;

	}
}

/**************** Guinness Specific Functions *******************/

function Get_Age($dob_day,$dob_month,$dob_year)
{
	//Calculate the age from the date of birth
	//Return age in years
	$today_year=date("Y");
	$today_month=date("m");
	$today_day=date("d");

	if($dob_year<=1970)
	{
		$diff_year=$today_year-$dob_year;
		return $diff_year;
	}

	$diff=mktime(0,0,0,$today_month-$dob_month,$today_day-$dob_day,$today_year-$dob_year);
	$diff_year=date("y",$diff);
	return $diff_year;

}

function Allowed_Access($country,$age)
{
	//Lookup if the user is allowed to access the site
	//Looks up table to see if the users age or country
	//restricts access to the website

	//Country not selected or 'other' selected
	//Or Age is less than 18 (User must allways be over 18 to access site
	if($country==0 && $age<18)
		return false;

	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql=array();
	$mysql['country']=mysql_real_escape_string($country,$link);
	$mysql['age']=mysql_real_escape_string($age,$link);

	$query="SELECT allowed FROM access_lookup WHERE country={$mysql['country']} AND min_age<={$mysql['age']}";

	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line=mysql_fetch_array($result);

	//need to do lookup on table for country + age
	if($line['allowed']==1)
		return true;

	else
		return false;
}


function ListDepts($master,$subdeptsperrow=3)
{
	$clean['master']=form_clean($master,4);
	// Retrieve the Departments
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');


	$mysql=array();
	$mysql['master']=mysql_real_escape_string($clean['master'],$link);

	$query = "SELECT tbl_departments.name FROM tbl_departments WHERE tbl_departments.deptcode = " . $mysql['master'] . ";";

	$sub_dept_result = mysql_query($query) or die('Query failed: ' . mysql_error());



	while ($line = mysql_fetch_array($sub_dept_result, MYSQL_ASSOC)) {
		$masterdepname = $line["name"];
	}

	mysql_free_result($sub_dept_result);

	// Retrieve the Sub-Departments


	$query = "SELECT tbl_departments.name, tbl_departments.deptcode FROM tbl_departments WHERE tbl_departments.under = " . $mysql['master'] . " AND tbl_departments.display = 1 ORDER BY tbl_departments.disp_seq;";
	$sub_dept_result = mysql_query($query) or die('Query failed: ' . mysql_error());


	// Closing connection
	mysql_close($link);

	if( isset($masterdepname) )
	{

?>




<ul>
<?php
	$counter = 0;
	while ($line = mysql_fetch_array($sub_dept_result, MYSQL_ASSOC))
	{
		$deptitems = countitems($line["deptcode"]);
		/*if ($deptitems > 0) {*/
		if (1)
		{
			if ($counter == 0)
			{

			}
?>

			<li>
				<?php if ($deptitems > 0)
				{
					//The second level cat has products in
					//make the cat title a link
				?>
					<a href="products.php?master=<?php Escaped_Echo(  $clean['master']);?>&amp;dept=<?php Escaped_Echo(  $line["deptcode"]);?>"><?php Escaped_Echo(  $line['name'] )?></a>
				<?php
				}
				else
				{
					//The second level is empty
					//so just display the name

					//Changed for Guinness - needs to list depts if clicked on

				?>
				<a  href="products.php?master=<?php Escaped_Echo(  $clean['master']);?>&amp;dept=<?php Escaped_Echo(  $line["deptcode"]);?>" ><?php Escaped_Echo(  $line['name'] )?></a>


				<?php
				}
		$counter = $counter + 1;
		}

		//no products in here
		//check for lower catergory

		//Get deptarments from third level
		$subdept=$line['deptcode'];
		$subname=$line['name'];
		$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');

		$mysql['subdept']=mysql_real_escape_string($subdept,$link);

		$query = "SELECT tbl_departments.name, tbl_departments.deptcode FROM tbl_departments WHERE tbl_departments.under = " . $mysql['subdept'] . " AND tbl_departments.display = 1 ORDER BY tbl_departments.disp_seq;";

		$sub_dept_result_3=mysql_query($query) or die('Query failed: ' . mysql_error());

		if(mysql_num_rows($sub_dept_result_3)>0)
		{
		//if there were departments under the second level

		?>
			<ul>
			<?php
			 while($subdept3line=mysql_fetch_array($sub_dept_result_3, MYSQL_ASSOC))
	        {
		        //create a list with all the third level departments
			?>
				<li><a href="products.php?master=<?php Escaped_Echo(  $clean['master']);?>&amp;dept=<?php Escaped_Echo(  $subdept3line["deptcode"]);?>"><?php Escaped_Echo(  $subdept3line['name'])?></a></li>
			<?php
			}
			?>
			</ul>
		</li>
		 <?php
		 }
		 else
		 {
		?>
			</li>
		<?php
		 }
		 if ($counter == ($subdeptsperrow))
		 {
			//echo "</ul>";
			$counter = 0;
		 }

	}
	?>
	</ul>

<?php
	mysql_free_result($sub_dept_result);
}
}

function Valid_ST_Ref($ST_Ref)
{
	$Valid=true;
	$Split=explode("-",$ST_Ref);
	if(sizeof($Split)==3)
	{
		foreach ($Split as $Number)
		{
			if(!is_numeric($Number))
			{
				$Valid=false;
			}
		}
	}
	else
	{
		$Valid=false;
	}
	return $Valid;
}
?>