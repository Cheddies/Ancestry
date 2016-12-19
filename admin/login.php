<?php
	if (isset($_SESSION['adminlogin_shop'])) {
		if ($_SESSION['adminlogin_shop'] == true) {
			// Redirect to the password stage
			header('Location: admin.php');
			exit();
		}
		
		else {
			header('Location: error.php');
			exit();
		}
	}
	else {
		// Redirect to the password stage
		header('Location: error.php');
		exit();
	}
?>