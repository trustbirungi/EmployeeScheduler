<?php
/*********************************************************
	File: es_logout.php
	Project: Employee Scheduler
	
	Comments:
		Log out a user and redirect to home page.
		
	
**********************************************************/

require "es_functions.php";

$_SESSION = array();
@session_destroy();
header("Location: index.php");
print "Logged out";
?>