<?php
/*********************************************************
	File: es_config.php
	Project: Employee Scheduler
	
	Comments:
		Contains common control variables for the site.
		
	
**********************************************************/

//--prevent direct access of this file
if (strstr($_SERVER["PHP_SELF"],"config.php")) {
	print "Why do you want to do that?";
	exit;
}

// ---- Define global variables
//- Database Connection variables 
$DBHOST = "localhost";			//- MySQL Database Host
$DBNAME = "scheduler";		//- MySQL Database Name
$DBUSER = "username";			//- MySQL DB Username
$DBPASS = "password";			//- MySQL DB Userpassword

//-- LDAP Authentication variables
$LDAP_HOST = "ldaps://ldap.yourdomain.com";	//-- LDAP Host URL
$LDAP_PORT = 636;							//-- LDAP Port
$LDAP_SEARCHBASE = "ou=people,o=yourdomain.com";	//-- LDAP search base
$LDAP_CONTEXT = "ou=people,o=yourdomain.com";		//-- LDAP Context
$LDAP_USER_ID_PROP = "uid";					//-- LDAP User identifying field
$LDAP_ATTRS_ARRAY = array("cn","mail");		//-- LDAP Attributes to return after the ldap search

?>
