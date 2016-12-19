<?php include "logincheck.php"?>
<?php require_once("../include/siteconstants.php"); ?>
<?php require_once("../include/commonfunctions.php"); ?>
<?php
	//$session = session_id();
$clean=array();
$mysql=array();

if(isset($_GET['ordernumber']))
{
    $clean['PassedOrderNum']=form_clean($_GET['ordernumber'],32);
    $PrevID="";
    $BasketTotal=0;
        
	// Connecting, selecting database
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql['PassedOrderNum']=mysql_real_escape_string($clean['PassedOrderNum'],$link);
	// Performing SQL query
	
	$query = "SELECT sessionid,itemcode,name,price,quantity FROM tbl_baskets WHERE sessionid='{$mysql['PassedOrderNum']}';";
	
	//query to get details from order_header
	$queryordertable = "SELECT state,sstate,description,shipvia,price,niceordernum,ordernumber, order_date, firstname, lastname,address1,address2,city,zipcode,phone,sfirstname,slastname,saddress1,saddress2,scity,szipcode,total_paid FROM tbl_order_header,tbl_shipping WHERE ordernumber='{$mysql['PassedOrderNum']}' AND shipvia=code;";
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	$ordertableresult = mysql_query($queryordertable) or die('Query failed: ' . mysql_error());
	
	
	//get the results from the query on the order table into an array
	//will allways be an array of 1
	
	
	$ordertablerow=mysql_fetch_array($ordertableresult);
	
	//query to get shipping method
	//$queryshiptable = "SELECT description FROM tbl_shipping WHERE code = shippingmethod";
	//$shiptableresult = mysql_query($queryshiptable) or die('Query failed: ' . mysql_error());
	//$shiptablerow=mysql_fetch_array($shiptableresult);
	
	
	//Get the names of the coutries
	$countryquery = "SELECT tbl_countries.country,tbl_order_header.niceordernum FROM tbl_countries,tbl_order_header WHERE ordernumber='{$mysql['PassedOrderNum']}' AND tbl_order_header.country = tbl_countries.code"; 
		
	$countryresult = mysql_query($countryquery) or die('Query failed: ' . mysql_error());
	
	$country=mysql_fetch_row($countryresult);
	$country=$country[0];
	
	$countryquery = "SELECT tbl_countries.country FROM tbl_countries,tbl_order_header WHERE ordernumber='{$mysql['PassedOrderNum']}' AND tbl_order_header.scountry = tbl_countries.code"; 
		
	$countryresult = mysql_query($countryquery) or die('Query failed: ' . mysql_error());
	
	$scountry=mysql_fetch_row($countryresult);
	$scountry=$scountry[0];
	 
	// Closing connection
	mysql_close($link);

	
?>

<html>
	<head>
		<link rel="stylesheet" href="style/adminstyle.css" type="text/css" />
	</head>
	<body topmargin="0">
		<form action="checkpass.php" method="post">
			<div align="center">
				
				<?php include("menu.php"); ?>
				<h3>Order Details</h3>
				<?php
					while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
						if($line["sessionid"]==$PrevID)
						{
						//this if could be used if multiple orders where to be output
					
						}
						else
						{
				 ?>
						<a href="visitordetails.php?visitor=<?php Escaped_echo ( $line["sessionid"]) ?>">View vist details</a>
			 		
					        <table align="center" border=1>
						<th width="50%"><b>Order Number</b></th><th  width="50%"><b>Order Date</b></th>
						<tr>
							<td colspan="1" align="left"><b>Long:</b><?php Escaped_echo ( $line["sessionid"]); ?><br /><b>Nice:</b><?php  Escaped_echo (" {$ordertablerow["niceordernum"]}"); ?> </td><td colspan="1" align="center"><?php  Escaped_echo ($ordertablerow["order_date"]); ?></td>
													
						</tr>
						<tr>
							<td  align="center"><b>Billing Address</b></td><td align="center"><b>Shiping Address</b></td>
						</tr>
						<tr>
							<td align="center"><?php  Escaped_echo ( $ordertablerow["firstname"]);?><?php  Escaped_echo ($ordertablerow["lastname"]);?>
							     <br /><?php  Escaped_echo ( $ordertablerow["address1"]); ?>
							     <br /><?php  Escaped_echo ( $ordertablerow["address2"]); ?>
							     <br /><?php  Escaped_echo ( $ordertablerow["city"]); ?>
							     <br /><?php  Escaped_echo ( $ordertablerow["state"]); ?>
							     <br /><?php  Escaped_echo ( $ordertablerow["zipcode"]); ?>
							     <br /><?php  Escaped_echo ( $country);?>
							     <br /><?php  Escaped_echo ( $ordertablerow['phone']);?>
							</td>
							<td align="center"><?php  Escaped_echo ( $ordertablerow["sfirstname"]);?><?php  Escaped_echo ( $ordertablerow["slastname"]);?>
							     <br /><?php  Escaped_echo ( $ordertablerow["saddress1"]); ?>
							     <br /><?php  Escaped_echo ( $ordertablerow["saddress2"]); ?>
							     <br /><?php Escaped_echo ( $ordertablerow["scity"]); ?>
							     <br /><?php  Escaped_echo ( $ordertablerow["sstate"]); ?>
							     <br /><?php  Escaped_echo ($ordertablerow["szipcode"]); ?>
							     <br /><?php  Escaped_echo ( $scountry);?>
							</td>
						</tr>
							
						<tr>
							<td colspan="2" align="center"><b>Items</b></td>
						</tr>
						
						<tr>
							<td align="center" colspan="2">
								<table align="center" border="1" width="100%">
								<th>Code</th><th>Description</th><th>Qty</th><th>Price</th><th>Total</th>
						

					    <?php
					       	}
					        //Add total of this item onto baskettotal
					        $ThisItem=$line["price"]*$line["quantity"];
					        $BasketTotal=$BasketTotal+$ThisItem;
					    ?>
						<tr>
							<td><?php  Escaped_echo ( $line["itemcode"]); ?></td>
							<td><?php  Escaped_echo ( $line["name"]); ?></td> 
							<td align="center"><?php  Escaped_echo ( $line["quantity"]); ?></td>
							<td align="center"><?php  Escaped_echo (formatcurrency($line["price"])); ?></td>
							<td align="center"><?php  Escaped_echo (formatcurrency($ThisItem)); ?></td>
						</tr>
							
							
							
						
						
					<?php
						$PrevID=$line["sessionid"];
					}
					?>
					
					<tr>
						<td colspan="4" align="right">Order Total: </td>
						
						<td align="center"><?php printf("£%.2f",$BasketTotal)?></td>
						<?php $BasketTotal=$BasketTotal+$ordertablerow["price"];?>
					</tr>
					<tr>
						<td colspan="5" align="center"><b>Shipping Method:<?php Escaped_echo(" {$ordertablerow["shipvia"]} -  {$ordertablerow["description"]}"); ?></b></td>
					</tr>
					<tr>
					<td colspan="4" align="right">Shipping Cost </td>
					
					<td align="center"><?php echo $ordertablerow["price"]?></td>
					</tr>
					<tr>
						<td colspan="4" align="right">Order Total(+Postage): </td>
						
						<td align="center"><?php printf("£%.2f",$BasketTotal)?></td>
					</tr>
					<tr>
						<td colspan="4" align="right">Total Paid</td>
						<td align="center"><?php echo formatcurrency($ordertablerow['total_paid']) ?></td>
					</tr>
							       </table>
					      </td>
					      </tr>	
									
					
					      </table>
				</div>
		</form>
	</body>
</html>
<?php
}
?>