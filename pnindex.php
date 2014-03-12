<?php
/*=================================================
	Project: Employee Scheduler
	File: pnindex.php
	
===================================================*/

if (!eregi("modules.php", $PHP_SELF)) {
	die ("You can't access this file directly...");
} 

session_start();

global $config;
global $ES_BASEDIR;
global $ES_MODULENAME;
$ES_MODULENAME = $name;
$ES_BASEDIR = "modules/$ES_MODULENAME/";
if (!isset($config)) { include("config.php"); }
$config["module"] = "empscheduler";

$username = "";
$_SESSION['pgv_user'] = "";

if (pnUserLoggedIn()) 
{
	$username = pnUserGetVar('uname');

	list($userperms, $groupperms) = pnSecGetAuthInfo();
	if ((count($userperms) == 0) &&
            (count($groupperms) == 0)) 
	{
         print "no permissions<br>";
		// No permissions - is an error - how did they get here ?
         return;
	}
}

if (!empty($username)) { 
	$_SESSION['es_username'] = $username;
}
		
// go to scheduler
pnRedirect("$ES_BASEDIR/index.php");
exit;

?>
