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
	
	$base_join="LEFT JOIN GRO_tbl_shipping on delivery_method=GRO_tbl_shipping.code LEFT JOIN GRO_orders_extra ON GRO_orders.GRO_orders_id=GRO_orders_extra.GRO_orders_id LEFT JOIN GRO_addresses ON billing_address=GRO_addresses_id";
	
	$join[0] =$base_join . " LEFT JOIN GRO_certificate_ordered O ON GRO_orders.order_number=O.order_number LEFT JOIN GRO_birth_certificates B ON (B.GRO_birth_certificates_id=O.certificate_id) ";
	$join[1] =$base_join . " LEFT JOIN GRO_certificate_ordered O ON GRO_orders.order_number=O.order_number LEFT JOIN GRO_marriage_certificates M ON (M.GRO_marriage_certificates_id=O.certificate_id)";
	$join[2] =$base_join . " LEFT JOIN GRO_certificate_ordered O ON GRO_orders.order_number=O.order_number LEFT JOIN GRO_death_certificates D ON (D.GRO_death_certificates_id=O.certificate_id)";
	
	$base_where=array("authorised=1");
	$base_where_certificate=array();
	
	//$join="LEFT JOIN GRO_tbl_shipping ON code=delivery_method";
	
	if(isset($clean['status']))
		$base_where = array_merge($base_where,array("order_status={$clean['status']}"));
	if(isset($clean['date']))
		$base_where = array_merge($base_where,array("order_date={$clean['date']}"));
	if(isset($clean['order_id']))
		$base_where = array_merge($base_where,array("GRO_orders.GRO_orders_id={$clean['order_id']}"));		
	if(isset($clean['email']))
		$base_where = array_merge($base_where,array("email={$clean['email']}"));	
	if(isset($clean['stref']))
		$base_where = array_merge($base_where,array("st_ref={$clean['stref']}"));			
	if(isset($clean['GRO_ref']))
		$base_where = array_merge($base_where,array("GRO_ref={$clean['GRO_ref']}"));	
	if(isset($clean['firstname']))
		$base_where = array_merge($base_where,array("first_name LIKE {$clean['firstname']}%"));
	if(isset($clean['surname']))
		$base_where = array_merge($base_where,array("surname LIKE {$clean['surname']}%"));
		
			
	if(isset($clean['GROI_year']))
	{
		$base_where_certificate = array_merge($base_where,array("GRO_index_year ={$clean['GROI_year']}"));		
		$where[0] = array_merge($base_where_certificate, array("O.certificate_type=1")); 
		$where[1] = array_merge($base_where_certificate, array("O.certificate_type=2")); 
		$where[2] = array_merge($base_where_certificate, array("O.certificate_type=3")); 
	}	
	else
	{		
		$where[0] = array_merge($base_where, array("O.certificate_type=1")); 
		$where[1] = array_merge($base_where, array("O.certificate_type=2")); 
		$where[2] = array_merge($base_where, array("O.certificate_type=3")); 
	}
	
		
		
	$order_by="order_date";
	
	$count=0;
	$orders=array();
	$i=0;
	foreach($join as $join_con)
	{
		$result=$DB->getData("GRO_orders",$fields_for_count,$where[$i],"",$join_con,$order_by,false);
		if($result)
		 $count=$count+$result[0]['count(*)'];
		$i++;
		
	}
	
	if($count)
	{
		$total_orders=$count;
		$Pages=$total_orders/$orders_per_page;
		echo "<b>Total orders:</b>" . $total_orders;
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
	
	$orders=array();
	$i=0;
	
	//need to write function that will perform all 3 querys 
	//then put data together in order
	//and then limit the return
	
	//$result = $DB->getDataWithLmitsMulti("GRO_orders",$fields,$Limit1,$Limit2,$where,"",$join,$order_by,true);
	
	foreach($join as $join_con)
	{
		//echo "<br/>";
		
		//just get the order numbers 
		$result=$DB->getDataWithLimits("GRO_orders",array("GRO_orders.GRO_orders_id"),$Limit1,$Limit2,$where[$i],"",$join_con,$order_by,false);
		
		//echo "<br />";
		if($result)
			$order_numbers=array_merge($orders,$result);
		//echo "<br />";
		
		$i++;
	}
	
	function order_compare($a,$b)
	{
		if($a['GRO_orders_id']>$b['GRO_orders_id'])
			return true;
	}
	
	usort  ( $order_numbers  , "order_compare" );
	//$orders=0
	
	//order_numbers now contains all orders we need in order 
	
	$in="";
	$first=true;
	
	//echo "Full Array";
	//echo "<pre><b>";
	
	//echo print_r($order_numbers);
	
	//echo "</b></pre>";
	
	foreach($order_numbers as $order_number)
	{
		//echo "Order Number Array";
		//echo "<pre><b>";
	
		//echo print_r($order_number);
		
		//echo "</b></pre>";
		
		if($first)
		{
			$in = $in . $order_number['GRO_orders_id'] ;
			$first=false;
		}
		else
			$in = $in .",".$order_number['GRO_orders_id'];
	}
	
	$where_con = array_merge($base_where,array("GRO_orders.GRO_orders_id IN {$in}"));
	$join_con = $base_join;
	
	$result=$DB->getDataWithLimits("GRO_orders",$fields,$Limit1,$Limit2,$where_con,"",$join_con,$order_by,false);
	
	$orders=$result;
	
	if($orders)
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