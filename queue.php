<?
require_once('./include/siteconstants.php');

function getQueued() {
	$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Could not select database');

	$mysql=array();
	$mysql['dept']=mysql_real_escape_string($dept,$link);

	$query="SELECT niceordernum FROM tbl_order_header WHERE authorised = 1 AND batchdate = 0;";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$count=0;

	while($line=mysql_fetch_array($result)) {
		$count++;
	}

	return $count;
}

$queued    = getQueued();

echo $queued

?>