<?php 
	session_start();
	unset($_SESSION['user']); 
	session_destroy();
	
	header("location:http://health-connect-dev.australiasoutheast.cloudapp.azure.com/wordpress/report_login.php");
?>