<?php
	ini_set('display_errors', 1);  
	error_reporting(E_ALL);

    function addWorkingDays($startdate, $numberOfDays) {
    	$dayx = strtotime($startdate);
  		
		echo "Start Date: " . date('jS F Y',$dayx);
  		
  		$i = 0;
  		
  		while($i < $numberOfDays) {
			// Add a day	
			$dayx = strtotime('+1 day', $dayx);
			//echo '<br />';
			//echo "Mid Date: " . date('jS F Y',$dayx);
			
			// Check the new day of the week
			$day_number = date('N',$dayx);
			
			// Is it a weekday, if so add one to the count
			if ($day_number < 6) {
				$i++;	
			}
   		}
  
  		return date('jS F Y',$dayx);
    }
    
    $mystartdate = $_GET['start'];
	$numberOfDays = $_GET['days'];
	//echo $mystartdate;
	echo '<br />';
	echo "End Date: " . addWorkingDays($mystartdate, $numberOfDays);
?>