<?php
if(isset($clean['master']))
	{
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
	
	if( mysql_num_rows($sub_dept_result)>0 )
	{
 
if(isset($clean['dept']))
{
	$clean['header_image']="images/" . basename($clean['master'])."_sub_header.gif";
}
else
{
	$clean['header_image']="images/" . basename($clean['master'])."_header.jpg";
}

$clean['footer_image']="images/" . basename($clean['master'])."_footer.jpg";
$id=$clean['master'] . "_subdepts";
?>
<?php
if(isset($masterdepname))
{
?>
<img src="<?php Escaped_Echo( $clean['header_image'])?>" alt="<?php Escaped_Echo(  $masterdepname)?>"/>
<?php
}
?>


<ul id="nav">
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
			//echo "<ul id=\"subdepts\">";
			}
?>	
			<!--<li><a href="products.php?master=<?php Escaped_Echo(  $clean['master']);?>&amp;dept=<?php Escaped_Echo(  $line["deptcode"]);?>"><?echo formatDescription($line["name"]);?></a></li> -->
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
				
					<!--<span ><?php Escaped_Echo(  $line['name'])?></span>-->
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
		</li><!--1-->
		 <?php	
		 }
		 else
		 {
		?>
			</li><!--2-->
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
	<img src="<?php Escaped_Echo( $clean['footer_image'])?>" alt="Footer Image"/>
<?php
	mysql_free_result($sub_dept_result);
}//End of IF department is found
}//End of IF master code is set

?>


<?php
if(isset($_SERVER['PHP_SELF']))
{
	$clean['page']=basename($_SERVER['PHP_SELF']);
	
	switch($clean['page'])
	{
		case "productdetail.php":
		{
			//include something below category listing in product detail pages.
		?>
		
		<?php
		}
	}
}
?>