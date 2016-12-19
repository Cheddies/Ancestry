<?php 
	  require_once('include/siteconstants.php');
	  require_once('include/commonfunctions.php');
	  
	  $clean=array();
	  if(isset($_GET['dept'])&& is_numeric($_GET['dept']) && $_GET['dept']>0 )
	  {
		  $clean['dept']=form_clean($_GET['dept'],4);
	  }
	  if(isset($_GET['master'])&& is_numeric($_GET['master']) && $_GET['master']>0 )
	  {
		  $clean['master']=form_clean($_GET['master'],4);
	  }
	  
	  		if( isset($clean['dept'])&&  isset($clean['master']) )
			{
				include ('include/header.php');
				include('include/productlist.php'); 
				
			}
			else
			{
				if(isset($clean['master']))
				{
					include ('include/header.php');
					/*include('include/featured.php');*/
				}
				else
				{
					//neither DEPT or MASTER is set so redirect to home
					header('location:index.php');
					exit();
				}
			}
			?>
<?php include ('include/footer.php');?>		


