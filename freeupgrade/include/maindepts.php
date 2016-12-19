<?php

$link = mysql_connect(DB_HOST,DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_NAME) or die('Could not select database');
		
		
//Get list of top level categories
	
$query = "SELECT tbl_departments.name, tbl_departments.deptcode FROM tbl_departments WHERE tbl_departments.under = 0 AND tbl_departments.display = 1 ORDER BY tbl_departments.disp_seq;";
	
$sub_dept_result = mysql_query($query) or die('Query failed: ' . mysql_error());
	

// Closing connection
mysql_close($link);
	
if( mysql_num_rows($sub_dept_result)>0 )
{
?>
<ul id="nav">
<?php
	$counter = 0;
	while ($line = mysql_fetch_array($sub_dept_result, MYSQL_ASSOC))
	{
		$clean['master']=$line['deptcode'];
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
						<span class="menu_text"><a href="products.php?master=<?php Escaped_Echo(  $clean['master']);?>&amp;dept=<?php Escaped_Echo(  $line["deptcode"]);?>"><?php Escaped_Echo(  $line['name'] )?></a></span>
				<?php
				}
				else
				{
					//The second level is empty
					//so just display the name
					
					//Changed for Guinness - needs to list depts if clicked on
					
				?>
				<!--<<a  href="products.php?master=<?php Escaped_Echo(  $clean['master']);?>&amp;dept=<?php Escaped_Echo(  $line["deptcode"]);?>" ><?php Escaped_Echo(  $line['name'] )?></a>
				-->
				<span class="menu_level_one">
				
					<span class="menu_text"><?php Escaped_Echo(  $line['name'])?></span>
				
				</span>
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
				<!-- test --> <li><a href="products.php?master=<?php Escaped_Echo(  $clean['master']);?>&amp;dept=<?php Escaped_Echo(  $subdept3line["deptcode"]);?>"><span class="menu_text_lower"><?php Escaped_Echo(  $subdept3line['name'])?></span></a></li>
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
		<span class="menu_level_one">
			<span class="menu_text">Certificates</span>
		</span>
		<ul>
			<li>Birth Certificates</li>
			<li>Marriage Certificates<li>
			<li>Death Certificates</li>
		</ul>	
		
		<span class="menu_level_one">
		<span class="menu_text">Books</span>
		</span>
		<ul>
			<li>Ancestry Press</li>
		</ul>
		
		<span class="menu_level_one">
		<span class="menu_text">Gifts</span>
		</span>
		<ul>
			<li><a href="">Gift Pack</a></li>
			<li>Ancestry Press</li>	
			<li>DNA</li>	
				
		</ul>
		<span class="menu_level_one">
		<span class="menu_text">DNA</span>
		</span>
		<ul>
			<li>Y Chromosome test 33 marker</li>
			<li>Y Chromosome test 46 marker<li>
			<li>Mitochondrial test</li>
		</ul>
		
	</ul>
<?php
	mysql_free_result($sub_dept_result);
}//End of IF department is found