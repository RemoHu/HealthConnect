<?php
	session_start();
	
	if(!empty($_SESSION['user']))
	{
		header("location:http://health-connect-dev.australiasoutheast.cloudapp.azure.com/wordpress/database.php");
 	}
	else
	{
		header("location:http://health-connect-dev.australiasoutheast.cloudapp.azure.com/wordpress/report_login.php");
	}
?>

<html>
<body>
Login Successful
</body>
</html>