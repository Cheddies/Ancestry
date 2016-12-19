<div id="right_hand_bar">
<?php
	
	/*if(isset($Page) && $Page=='surname_book_detail')
	{
	?>
	<div id="software_requirements">
	<h4>Instructions to order your book</h4>
	<p style="font-size:1.2em; font-weight:bold;">
	Go to  www.amazon.co.uk or click on the link at the bottom of the page<br /><br />
	Choose Book category<br /><br />
	In the search box enter  	&lt;insert your surname&gt; Name in History .  For example Robinson Name in History<br /><br />
	If your name is not there try again later.  New Surnames are being added every day<br /><br />
	Select the UK version, published  Oct 2008<br /><br />
	</p>
	</div>
	<?php
	}*/
	
	?>
	
		<?php
/*
		if(isset($clean['dept']))
		{
			$file="right_bar/dept_right_hand_" . $clean['dept'] .".html";
			if(is_file($file))
				include($file);
		}*/
		
		//now pulls extra product data from the DB
		
		if(isset($_GET['number']))
		{
			$clean['number']=form_clean($_GET['number'],20);
			$mysql['number']=mysql_real_escape_string($clean['number']);
			$query="SELECT html FROM tbl_extra_product_info WHERE number='{$mysql['number']}'";
			$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
			mysql_select_db(DB_NAME) or die('Could not select database');
			
			$result = mysql_query($query);
			if($line=mysql_fetch_array($result))
			{
				echo $line['html'];
			}
			
		}
		
		if(isset($clean['number']) && $clean['number']!='FTMUPGRADE2010')
		{
	?>
		<a href="http://landing.ancestry.co.uk/offers/uk/dna.aspx">
		<img src="images/banners/dna_246x192.gif" alt="let your DNA introduce you to your genetic cousins. Ancestry.co.uk DNA order your kit today" />
	</a>
	<?php 	
	}
	?>
	
</div>