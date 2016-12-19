<?php

	session_destroy();
	
	

?>
<html>
	<head>
		<link rel="stylesheet" href="style/style.css" type="text/css" />
	</head>
	<body topmargin="0">
		<br />
		<form action="checkpass.php" method="post">
			<div align="center">
				
				<br />
				<p>You are now logged out</p>
				<a href="index.php">Click here to login again</a>
			</div>
		</form>
	</body>
</html>