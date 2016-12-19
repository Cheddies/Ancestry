<?php include("include/siteconstants.php"); ?>
<?php include("include/commonfunctions.php"); ?>
<?php
$badchars=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/",".","'","\"");
$badchars_amount=array("\n","\r","#","\$","}","{","^","~","?","*","|","`","&",";","<",",","\\",">","(",")","!","[","]","/","'","\"");
$mysql=array();
$clean=array();

//set some edgeward variables
$pageTitle="Ancestry Shopping Basket | Order Complete";
$no_js=false;
$formtype="summary";

define('HASHPASSWORD','d6UHecaprehabrEbA4h958Fu7ruca8re');

//Order complete page
//redirected to upon success from ST

//sending back the following
/*
ba_tracking,cj_tracking,gro,mainamount,
orderreference,tm_prodprices,tm_prodrefs,
tm_total

+
responsesitesecurity

calulated from the fields above + password 

/*
ba_tracking
cj_tracking
customercountryiso2a
customercounty
customertown
gro
mainamount
orderreference
tm_prodprices
tm_prodrefs
tm_total
*/
$TEST=false; 

if(
	(
	isset($_GET['ba_tracking'])&&
	isset($_GET['cj_tracking'])&&
	isset($_GET['customercountryiso2a']) &&
	isset($_GET['customercounty']) &&
	isset($_GET['customertown']) &&
	isset($_GET['gro']) &&
	isset($_GET['mainamount'])&&
	isset($_GET['orderreference'])&&
	isset($_GET['tm_prodprices'])&&
	isset($_GET['tm_prodrefs'])&&
	isset($_GET['tm_total'])&&
	isset($_GET['responsesitesecurity'])
	) || $TEST
)
{

	
	$string_to_hash=
		$_GET['ba_tracking'].
		$_GET['cj_tracking'].
		$_GET['customercountryiso2a'].
		$_GET['customercounty'].
		$_GET['customertown'].
		$_GET['gro'].
		$_GET['mainamount'].
		$_GET['orderreference'].
		$_GET['tm_prodprices'].
		$_GET['tm_prodrefs'].
		$_GET['tm_total'].
		HASHPASSWORD;

	$hash=hash('sha256',$string_to_hash);
	
	//check the hash is correct
	//continue if they match
	//else will drop through the default response
	if($_GET['responsesitesecurity']==$hash || $TEST)
	{
		// Connecting, selecting database
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
		
		
		$clean['niceodrnum']=form_clean($_GET['orderreference'],50);
		/*$clean['stauthcode']=form_clean($_GET['authcode'],45);
		$clean['streference']=form_clean($_GET['transactionreference'],45,$badchars);
		$clean['ordernumber']=form_clean($_GET['session_id'],32);*///these are not ssent 
		$clean['amount']=form_clean($_GET['mainamount'],10,$badchars_amount);//amount now comes through as original value,so don't cut out the .
		
		//would either look these up or get them passed back from ST
		//used by the GA tracking
		
		//for the GA tracking
		$clean['city']=form_clean($_GET['customertown'],20);
	    $clean['state']=form_clean($_GET['customercounty'],2);//the state is the field that is sent to ST
	    $clean['country']=form_clean($_GET['customercountryiso2a'],2);//2 digit country, will probably need conversion
	    
	    //this could be set dependant on the site address
	    
	   
	    
	    
		//display succses page
		require_once('include/edgeward/header.inc.php');
	?>
	<?php 
	/*tracking */
	?>

	 <div id="content_top"></div>
    	<div id="content_wrapper">
    		<img id="prog_bar" src="images/prog_bar_1.png" alt="Order Payment" style="padding-bottom:10px;" />
			<h2 class="section_header section_acc">Order Complete</h2>
			<div class="fieldset_section"> 
				<p><strong>Your order has been received successfully</strong></p>
				<p>
					For your reference your order number is:<b> <?php echo $clean['niceodrnum']?></b>
					<br />
					An email has been sent to confirm your order.
				</p>
			</div><!--end .fieldset_section-->
		</div><!--end #content_wrapper-->
	
	<div id="page_footer"></div>
	
	<?php 
	//tracking code
	
	if ($_SERVER['HTTP_HOST']=="ancestryshop.co.uk" || $_SERVER['HTTP_HOST']=="www.ancestryshop.co.uk")
	{
		
	    //live
	    $clean['ga_code']="UA-810272-11";
		
		include ("include/omniture_order_complete.php");//only a live account
		include ("include/order_complete_tracking_pixels.php"); //only a live account
		include ("include/GA_order_complete.php"); //uses the GA code above to send e-commerce transaction
		include ("include/tagman_order_complete.php"); //only a live account

	}
	else
	{
		/* //test
	    $clean['ga_code']="UA-31714579-1";
	    
		include ("include/omniture_order_complete.php"); //only a live account
		include ("include/order_complete_tracking_pixels.php"); //only a live account
		include ("include/GA_order_complete.php"); //uses the GA code above to send e-commerce transaction
		include ("include/tagman_order_complete.php"); //only a live account*/
		
	}
	?>
	
	<?php
		require_once('include/footer_basket.php');
				
		//require_once('include/footer.php');
		
	}?>
	
	</div><!--end #container-->
	<div id="tooltip_box">
	<div id="tt_top"></div>
	<div id="tt_container"></div>
	<div id="tt_btm"></div>
	</body>
</html>
</div>
	<?php
exit();//exit rather than falling through to default response
}
//default response on the page will be to send bad request
//this will only be hit if the update does not go through
header('Content-Type: text/html');
header("Response",true,'400') ;
exit();	


?>