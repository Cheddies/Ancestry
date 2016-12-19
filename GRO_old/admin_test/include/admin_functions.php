<?php 
function descriptive_status($status_code)
{
	switch($status_code)
	{
		case 1:
			return "Awaiting Proccessing";
		break;
		case 2:
			return "Pending With GRO";
		break;
		case 3:
			return "Order Complete";
		break;
		case 4:
			return "Cancelled";
		break;
		
	}
}

function descriptive_event($event)
{
	switch($event)
	{
		case 1:
			return "Order Status Reset";
		case 2:
			return "Sent to GRO";
		break;
		case 3:
			return "Order Completed";
		break;
		case 4:
			return "Order Cancelled";
		break;
	}
}

?>
