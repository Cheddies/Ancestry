<?php
?>
<h4>General Options</h4>
<ul>
	<li><a href="stats.php">Order Stats</a></li>
	<li><a href="accounts.php">Accounts Reports</a></li>
</ul>
<h4>View Orders</h4>
<ul>
	<!--<li><a href="list_orders.php">All</a></li>-->
	<li><a href="list_orders.php?status=1">Awaiting proccessing by IL</a></li>
	<li><a href="list_orders.php?status=2">Pending with GRO</a></li>
	
	<!--<li><a href="list_orders.php?status=3">Completed (All)</a></li>
	<li><a href="list_orders.php?status=3&scan_and_send=1">Completed (Scan and Send)</a></li>
	-->
	<li><a href="list_orders.php?status=4">Cancelled</a></li>
	<li><a href="search.php">Search for orders</a></li>
</ul>
<h4>System</h4>
<ul>
	<li>Logged in as <strong><?php if(isset($_SESSION['user_name'])) echo $_SESSION['user_name'];?></strong></li>
	<li><a href="logout.php">Logout</a></li>
</ul>
<?php
?>