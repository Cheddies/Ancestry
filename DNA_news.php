<?php include ('include/header.php');?>
<?php 
$DB=new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$table="DNA_news";
$where=array("visible=1");
$fields=array('title','URL','visible');
$order_by="list_order";
$news=$DB->getData($table,$fields,$where,"","",$order_by);
$total_stories=sizeof($news);
$half=intval($total_stories/2);
//if the split is uneven
if($total_stories%2)
{
	//set half to one more
	//this will mean the left hand side has one extra in cases where the total is uneven
	$half=$half+1;
}
?>
<h2>Please click the links below to view the latest DNA news</h2>
<img src="images/DNA_news_header.jpg" alt="DNA News Links" style="float:left;"/>
<div id="DNA_news_box">
	<div id="DNA_news_box_inside">
	<ul id="DNA_news_left">
		<?php 
		for($i=0;$i<$half;$i++)
		{
			$news_link=$news[$i];
		?>
		<li><a href="<?php echo $news_link['URL']?>"><?php echo $news_link['title']?></a></li>
		<?php
		}
		?>
	</ul>
	<ul id="DNA_news_right">
		<?php 
		for($i=$half;$i<$total_stories;$i++)
		{
			$news_link=$news[$i];
		?>
		<li><a href="<?php echo $news_link['URL']?>"><?php echo $news_link['title']?></a></li>
		<?php
		}
		?>
	</ul>
	</div>
</div>
<br />

<?php include ('include/footer.php');?>