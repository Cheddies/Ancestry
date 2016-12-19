<?php
	require_once('include/siteconstants.php');
	require_once('include/commonfunctions.php');
	
	//check that the data is coming from the correct form
	
	if
	(
	( (isset($_GET['search_token']) && isset($_SESSION['search_token']) && isset($_SESSION['search_token_time']) && ValidToken($_GET['search_token'],$_SESSION['search_token'],$_SESSION['search_token_time'],0))	)
	||
	( isset($_POST['search_token']) && isset($_SESSION['search_token']) && isset($_SESSION['search_token_time']) && ValidToken($_POST['search_token'],$_SESSION['search_token'],$_SESSION['search_token_time'],0))
	)
	{
	  include ('include/header.php');
	
?>
<div id="products">		
	<?php include ('include/searchlist.php')?>
</div>	


<?php include ('include/footer.php');?>
<?php
	}
	else
	{
		
		//there is a problem with the token
		//the page has possibly been accessed via 
		//a different method than the form
		//so redirect to index
		header('location:index.php');
	}
?>