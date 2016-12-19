<?php include "logincheck.php"?>
<?php

require_once("include/siteconstants.php");
require_once("include/commonfunctions.php");
require_once("include/admin_functions.php");
include ('include/header.php');

$token=UniqueToken();
$_SESSION['token']=$token;
$_SESSION['token_time']=time();

$orders_per_page=ORDERS_PER_PAGE;


//set different characters to remove from email field
//as . and - are allowed
$email_remove=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");#
	
$form_fields=array( array ("name" => "status","display_name" => "status","length"=>"1","reg_ex"=>"[1-9]","required"=>false),
					array ("name" => "GRO_ref","display_name" => "GRO Reference","length"=>"45","reg_ex"=>"","required"=>false),
					array ("name" => "date","display_name" => "date","length"=>"1","reg_ex"=>"","required"=>false),
					array ("name" => "order_id","display_name" => "order number","length"=>"10","reg_ex"=>"","required"=>false),
					array ("name" => "email","display_name" => "email","length"=>"50","reg_ex"=>"","required"=>false,"remove"=>$email_remove),
					array ("name" => "stref","display_name" => "secure trading reference","length"=>"50","reg_ex"=>"","required"=>false,"remove"=>$email_remove),
					array ("name" => "page","display_name" => "page","length"=>"10","reg_ex"=>"","required"=>false),
					array ("name" => "firstname","display_name" => "Firstname","length"=>"50","reg_ex"=>"","required"=>false),	
					array ("name" => "surname","display_name" => "Surname","length"=>"50","reg_ex"=>"","required"=>false),
					array ("name" => "GROI_year","display_name" => "GRO Index Registered Year", "length"=>"4","reg_ex"=>YEAR_REG_EX,"required"=>false)			
					);

$errors = process_form($form_fields,$_GET,&$clean);

if(sizeof($errors)>0)
{
	$_SESSION['errors']=serialize($errors);
	header('location:index.php');
	exit();
}
else
{
	$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	$fields_for_count=array("count(*)");
	$fields=array("GRO_orders.GRO_orders_id","order_date","authorised","order_status","GRO_tbl_shipping.description","GRO_ref","GRO_date","completed_date","title","first_name","surname");
	$join ="LEFT JOIN GRO_tbl_shipping on delivery_method=GRO_tbl_shipping.code LEFT JOIN GRO_orders_extra ON GRO_orders.GRO_orders_id=GRO_orders_extra.GRO_orders_id LEFT JOIN GRO_addresses ON billing_address=GRO_addresses_id";
	$where=array("authorised=1");
	//$join="LEFT JOIN GRO_tbl_shipping ON code=delivery_method";
	
	if(isset($clean['status']))
		$where = array_merge($where,array("order_status={$clean['status']}"));
	if(isset($clean['date']))
		$where = array_merge($where,array("order_date={$clean['date']}"));
	if(isset($clean['order_id']))
		$where = array_merge($where,array("GRO_orders.GRO_orders_id={$clean['order_id']}"));		
	if(isset($clean['email']))
		$where = array_merge($where,array("email={$clean['email']}"));	
	if(isset($clean['stref']))
		$where = array_merge($where,array("st_ref={$clean['stref']}"));			
	if(isset($clean['GRO_ref']))
		$where = array_merge($where,array("GRO_ref={$clean['GRO_ref']}"));	
	if(isset($clean['firstname']))
		$where = array_merge($where,array("first_name LIKE {$clean['firstname']}%"));
	if(isset($clean['surname']))
		$where = array_merge($where,array("surname LIKE {$clean['surname']}%"));		
		
		
	$order_by="order_date";
	
	
	$count=$DB->getData("GRO_orders",$fields_for_count,$where,"",$join,$order_by,true);
	if($count)
	{
		$total_orders=$count[0]['count(*)'];
		$Pages=$total_orders/$orders_per_page;
	}
	else
	{
		$Pages=0;
	}
	
	
?>
<div id="admin_page">
	<?php
	
	if(isset($clean['page']))
	{
		$Limit1=$clean['page']*$orders_per_page;
		$Limit2=$orders_per_page;
	}	
	else
	{
		$Limit1=$orders_per_page;
		$Limit2="";
	}
	
	if($orders=$DB->getDataWithLimits("GRO_orders",$fields,$Limit1,$Limit2,$where,"",$join,$order_by,true))
	{
		//$Pages=sizeof($orders)/$orders_per_page;
		//echo $Pages;	
		
		$Page=$_SERVER['PHP_SELF'];
		$Query=$_SERVER['QUERY_STRING'];
			
		$Page_pos=strpos  ( $Query  , "page");
		if($Page_pos)
		{
			if(!$End_of_page_pos=strpos($Query,"&",$Page_pos))
				$End_of_page_pos=strlen($Query);
				
			$Query_without_page=substr($Query,0,$Page_pos-1) . substr($Query,$End_of_page_pos);
		}
		else
		{
			$Query_without_page=$Query;	
		}
		
		for($i=1;$i<$Pages;$i++)
		{
			$link=$Page . "?" .$Query_without_page . "&page=" . $i;
			
			if(isset($clean['page']))
			{
				if($i==$clean['page'])
					$page_numbers=$page_numbers ."<li class=\"selected_page\">";
				else
					$page_numbers=$page_numbers ."<li>";
			}
			elseif($i==1)
				$page_numbers=$page_numbers ."<li class=\"selected_page\">";	
			else
				$page_numbers=$page_numbers ."<li>";
				
			$page_numbers=$page_numbers. "<a href=\"$link\">$i</a></li>";
		}
		
		/*$order_chunks=array_chunk  ( $orders , $orders_per_page );
		
		if(isset($clean['page']))
			$orders=$order_chunks[$clean['page']-1];
		else
			$orders=$order_chunks[0];	*/
		
		
		?>
		<h4>Pages</h4>
		<ul id="page_numbers">
		<?php echo $page_numbers;?>
		</ul>
		<table cellpadding = "3">
		<th>Order number</th><th>GRO Ref</th><th>Name</th><th>Order Date</th><th>Sent To GRO</th><th>Completed</th><th>Order Status</th><th>Delivery</th><th>Click To View</th>
		<?php
		$count=0;
		foreach ($orders as $order)
		{
			$count++;
			
		?>
		<tr class="<?php if($count%2==0) echo "odd"; else echo "even";?>">
		<td><?php echo $order['GRO_orders_id']?></td>
		<td><?php echo $order['GRO_ref']?></td>
		<td><?php echo $order['title'] . " " . $order['first_name'] . " " . $order['surname']?></td>
		<td><?php echo $DB->format_date($order['order_date']) ?></td>
		<td><?php echo $DB->format_date($order['GRO_date']) ?></td>
		<td><?php echo $DB->format_date($order['completed_date']) ?></td>
		<td><?php echo descriptive_status($order['order_status'])?></td>
		
		<td><?php echo substr($order['description'],0, strpos ($order['description'],"(" )) ?></td>
		
		<td><a href="view_order.php?order_id=<?php echo $order['GRO_orders_id']?>">View Order</a></td>
		</tr>
		<?php
		}
	}
	else
	{
		echo "No Orders found";
	}	
	
}
?>
</div>

<?php
include ('include/footer.php');


?>