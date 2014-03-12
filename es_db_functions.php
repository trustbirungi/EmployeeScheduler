<?php
/*********************************************************
	File: es_db_functions.php
	Project: Employee Scheduler
	
	Comments:
		Contains common functions for the employee scheduler
		files
	
	
**********************************************************/

//--prevent direct access of this file
if (strstr($_SERVER["PHP_SELF"],"functions.php")) {
	print "Why do you want to do that?";
	exit;
}

//============================== show_error_page
//-- show an error message to the user and exit
//-- to stop the script at this point
function show_error_page($msg) {
	global $es_lang;
	
	print_header($es_lang["error_page_title"]);
	print "<div align=\"center\">\n";
	print "<br><br><font class=\"pagetitle\">".$es_lang["error_page_title"]."</font><br><img src=\"images/bar_1.gif\" width=\"50%\" height=\"2\"><br><br><br>\n";
	print "<font class=\"error\">$msg</font><br><br>";
	print "</div>\n";
	print_footer();
	exit;
}

//============================== dbconnect
// -- establish a connection to the mysql database
function dbconnect() {
	global $DBHOST, $DBNAME, $DBPASS, $DBUSER, $DBCONN, $DBSEL, $es_lang;

	if (isset($DBSEL)) return;
	
	//-- establish mysql connection
	$DBCONN = mysql_connect($DBHOST, $DBUSER, $DBPASS);
	if (!$DBCONN) show_error_page($es_lang["no_db_connect"]."<br>".mysql_error());
	//-- select database
	$DBSEL = mysql_select_db($DBNAME);
	if (!$DBSEL) show_error_page($es_lang["no_db_select"]."<br>".mysql_error());
}


// Return a string identifying the database version:
function sql_version() {
	$v = get_db_items("select version()");
	return "MySQL ".$v[0][0];
}


//============================== dbquery
//-- perform the given SQL query on the database
//-- print out any errors
function dbquery($sql) {
	global $TOTAL_QUERIES, $user, $es_lang;
	
	$TOTAL_QUERIES++;
	$res = mysql_query($sql);
	if (!$res) {
		//-- display an error message
		print "<font class=\"error\"><b>ERROR:".mysql_error()." <BR>SQL:</b>$sql</font><br><br>\n";
		//-- email a copy of the error message
		//$message = $es_lang["query_error"]."\n".mysql_error()."\nSQL: $sql\n\nScript:".$_SERVER["PHP_SELF"]."\nQuery String:".$_SERVER["QUERY_STRING"]."\nAction:".$_REQUEST["action"]."\n\nUser: ".$user["u_id"]."\n      ".$user["u_name"]."\n      ".$user["u_netid"]."\n\n";
		//mail("yalnifj@users.sourceforge.net", $es_lang["error_page_title"], $message);
	}
	return $res;
}

//============================== db_cleanup
//-- clean the slashes and convert special
//-- html characters to their entities for
//-- display and entry into form elements
function db_cleanup($item) {
	if (is_array($item)) {
		foreach($item as $key=>$value) {
			$item[$key]=htmlspecialchars(stripslashes($value));
		}
		return $item;
	}
	else {
		return htmlspecialchars(stripslashes($item));
	}
}

//============================== db_prep
//-- add slashes and convert special
//-- so that it can be added to db
function db_prep($item) {
	if (is_array($item)) {
		foreach($item as $key=>$value) {
			$item[$key]=db_prep($value);
		}
		return $item;
	}
	else {
		return addslashes($item);
		//-- use the following commented line to convert between character sets
		//return addslashes(iconv("iso-8859-1", "UTF-8", $item));
	}
}


/**
 * get an array of table rows
 *
 * runs a given sql statement and returns an array of the rows
 * @param string $sql the sql statement to run.  Must be a SELECT statement.
 * @return array $items
 */
function get_db_items($sql) {
	$items = array();
	$res = dbquery($sql);
	while($item = mysql_fetch_array($res)) {
		$item = db_cleanup($item);
		$items[] = $item;
	}
	return $items;
}

/**
 * get the settings from the database settings table
 *
 * This function will load the settings from the database into global variables.
 */
function get_settings() {
	global $DBVERSION,$SITE_URL,$SESSION_COOKIE_TIMEOUT,$CHARACTER_SET,$SITE_ADMIN_EMAIL;
	global $COMPANY_URL,$COMPANY_NAME,$START_HOUR,$END_HOUR,$DEFAULT_TIME_BLOCKS,$PRIORITY;
	global $ES_SHOW_STATS,$ES_FULL_MAIL_TO;
	
	$PRIORITY = array();
	
	$sql = "SHOW TABLES";
	$res = dbquery($sql);
	$has_settings = false;
	while($table = mysql_fetch_array($res)) {
		if ($table[0]=="es_settings") $has_settings = true;
	}
	
	if (!$has_settings) {
		$DBVERSION = "1.0";
		if ((preg_match("/es_upgrade\.php/", $_SERVER["PHP_SELF"])==0)
			&&(preg_match("/es_dbsetup\.php/", $_SERVER["PHP_SELF"])==0)) {
			header("Location: es_upgrade.php");
			exit;
		}
	}
	else {
		$sql = "SELECT * FROM es_settings";
		$res = dbquery($sql);
		$settings = mysql_fetch_array($res);
		
		$DBVERSION = $settings["se_version"];
		$SITE_URL = $settings["se_SITE_URL"];
		$SESSION_COOKIE_TIMEOUT = $settings["se_SESSION_COOKIE_TIMEOUT"];
		$CHARACTER_SET = $settings["se_CHARACTER_SET"];
		$SITE_ADMIN_EMAIL = $settings["se_SITE_ADMIN_EMAIL"];
		$COMPANY_URL = $settings["se_COMPANY_URL"];
		$COMPANY_NAME = $settings["se_COMPANY_NAME"];
		$START_HOUR = $settings["se_START_HOUR"];
		$END_HOUR = $settings["se_END_HOUR"];
		$DEFAULT_TIME_BLOCKS = $settings["se_DEFAULT_TIME_BLOCKS"];
		$ES_SHOW_STATS = $settings["se_ES_SHOW_STATS"];
		$ES_FULL_MAIL_TO = $settings["se_ES_FULL_MAIL_TO"];
		
		$PRIORITY[0] = $settings["se_PRIORITY_0"];
		$PRIORITY[1] = $settings["se_PRIORITY_1"];
		$PRIORITY[2] = $settings["se_PRIORITY_2"];
		$PRIORITY[3] = $settings["se_PRIORITY_3"];
		$PRIORITY[4] = $settings["se_PRIORITY_4"];
		$PRIORITY[5] = $settings["se_PRIORITY_5"];
		$PRIORITY[6] = $settings["se_PRIORITY_6"];
		$PRIORITY[7] = $settings["se_PRIORITY_7"];
		$PRIORITY[8] = $settings["se_PRIORITY_8"];
	}
}

?>