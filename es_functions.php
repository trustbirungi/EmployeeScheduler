<?php
/*********************************************************
	File: es_functions.php
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

//-- import the post, get, and cookie variable into the scope on new versions of php
if ((!ini_get('register_globals'))||(ini_get('register_globals')=="Off")) {
	@import_request_variables("cgp");
}

//-- get the time in microsecs
function getmicrotime(){
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

//-- setup execution timer
$EXEC_START_TIME = getmicrotime();

//-- now require config variables to prevent request var override
require "es_config.php";
//-- require the datbase functions
require "es_db_functions.php";
dbconnect();
get_settings();

ini_set('arg_separator.output', '&amp;');
ini_set ('error_reporting', 0);

if (empty($ES_MEMORY_LIMIT)) $ES_MEMORY_LIMIT = "32M";
ini_set('memory_limit', $ES_MEMORY_LIMIT);

$VERSION = "2.1";
$TOTAL_QUERIES = 0;

//-- start the session
session_set_cookie_params($SESSION_COOKIE_TIMEOUT);
if (!empty($SESSION_SAVE_PATH)) session_save_path($SESSION_SAVE_PATH);
session_start();

//-- DETERMINE BROWSER LANGUAGE SETTINGS
require('languages/es_langcodes.php');
if (isset($HTTP_ACCEPT_LANGUAGE)) $accept_langs = $HTTP_ACCEPT_LANGUAGE;
else if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $accept_langs = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
if (isset($accept_langs)) {
	if (strstr($accept_langs, ",")) {
		$langs_array = preg_split("/(,\s*)|(;\s*)/", $accept_langs);
		for ($i=0; $i<count($langs_array); $i++) {
			if (!empty($langcode[$langs_array[$i]])) {
				$LANGUAGE = $langcode[$langs_array[$i]];
				break;
			}
		}
	}
	else {
		if (!empty($langcode[$accept_langs])) $LANGUAGE = $langcode[$accept_langs];
	}
}

if (isset($_SESSION['CLANGUAGE'])) $CLANGUAGE = $_SESSION['CLANGUAGE'];
else if (isset($HTTP_SESSION_VARS['CLANGUAGE'])) $CLANGUAGE = $HTTP_SESSION_VARS['CLANGUAGE'];
if (isset($CLANGUAGE)) {
	$LANGUAGE = $CLANGUAGE;
}
if ((isset($changelanguage))&&($changelanguage=="yes")) {
	if (!empty($NEWLANGUAGE)) $LANGUAGE=$NEWLANGUAGE;
}

$es_language["english"] 	= "languages/es_lang.en.php";
$es_language["german"]		= "languages/es_lang.de.php";
$es_lang=array();
$es_lang["english"] = "English";
$es_lang["german"] = "Deutsch";

require($es_language["english"]);							//-- load english as the default language
if (isset($es_language[$LANGUAGE])) require($es_language[$LANGUAGE]);				//-- load language file

//-- put the language in the session
$_SESSION['CLANGUAGE'] = $LANGUAGE;

$valid_sections = array(24, 48, 96);

if (empty($_SESSION["sections_in_day"])) $_SESSION["sections_in_day"] = $DEFAULT_TIME_BLOCKS;
if (!empty($_REQUEST["sections_in_day"])) $_SESSION["sections_in_day"] = $_REQUEST["sections_in_day"];
if (!in_array($_SESSION["sections_in_day"], $valid_sections)) $_SESSION["sections_in_day"] = 24;
$sections_in_day = $_SESSION["sections_in_day"];
$sections_in_hour = $sections_in_day / 24;

if (empty($_SESSION["last_hour"])) $_SESSION["last_hour"] = $END_HOUR;
if (isset($_REQUEST["last_hour"])) $_SESSION["last_hour"] = $_REQUEST["last_hour"];
if ($_SESSION["last_hour"]>$END_HOUR) $_SESSION["last_hour"] = $END_HOUR;
$last_hour = $_SESSION["last_hour"];

if (empty($_SESSION["first_hour"])) $_SESSION["first_hour"] = $START_HOUR;
if (isset($_REQUEST["first_hour"])) $_SESSION["first_hour"] = $_REQUEST["first_hour"];
if ($_SESSION["first_hour"]<$START_HOUR) $_SESSION["first_hour"] = $START_HOUR;
$first_hour = $_SESSION["first_hour"];

//-- day association
$DAYS = array();
$DAYS[0]["long"] = $es_lang["sunday"];
$DAYS[1]["long"] = $es_lang["monday"];
$DAYS[2]["long"] = $es_lang["tuesday"];
$DAYS[3]["long"] = $es_lang["wednesday"];
$DAYS[4]["long"] = $es_lang["thursday"];
$DAYS[5]["long"] = $es_lang["friday"];
$DAYS[6]["long"] = $es_lang["saturday"];
$DAYS[0]["short"] = $es_lang["sun"];
$DAYS[1]["short"] = $es_lang["mon"];
$DAYS[2]["short"] = $es_lang["tue"];
$DAYS[3]["short"] = $es_lang["wed"];
$DAYS[4]["short"] = $es_lang["thu"];
$DAYS[5]["short"] = $es_lang["fri"];
$DAYS[6]["short"] = $es_lang["sat"];

//-- repeat seconds
$REPEAT[604800] = $es_lang["weekly"];
$REPEAT[1209600] = "2 ".$es_lang["weeks"];
$REPEAT[1810044] = "3 ".$es_lang["weeks"];
$REPEAT[2419200] = "4 ".$es_lang["weeks"];
$REPEAT[3024000] = "5 ".$es_lang["weeks"];
$REPEAT[3628800] = "6 ".$es_lang["weeks"];
$REPEAT[4233600] = "7 ".$es_lang["weeks"];
$REPEAT[4838400] = "8 ".$es_lang["weeks"];
$REPEAT[5443200] = "9 ".$es_lang["weeks"];
$REPEAT[6048000] = "10 ".$es_lang["weeks"];

//-- weeks of seconds for rotation weekly schedules
$REPEAT[1] = 604800;		//-- 1 week of seconds
$REPEAT[2] = 1209600;
$REPEAT[3] = 1814400;
$REPEAT[4] = 2419200;
$REPEAT[5] = 3024000;
$REPEAT[6] = 3628800;
$REPEAT[7] = 4233600;
$REPEAT[8] = 4838400;
$REPEAT[9] = 5443200;
$REPEAT[10] = 6048000;		//-- 10 weeks of seconds

//-- array to hold a cache of the schedules that have been accessed
$schedule_cache = array();
$users_cache = array();
$position_cache = array();

//============================== get_supervisor_areas
//-- get the areas for the given user
//-- returns an array of area rows
function get_supervisor_areas($user) {
	$sql = "SELECT * FROM es_area, es_area_sups WHERE a_id=as_a_id AND as_u_id=".$user["user_id"]." ORDER BY a_name";
	$areas = get_db_items($sql);
	for($i=0; $i<count($areas); $i++) {
		$sql = "SELECT * FROM es_position WHERE p_a_id=".$areas[$i]["a_id"];
		$positions = get_db_items($sql);
		$areas[$i]["a_positions"] = $positions;
	}
	return $areas;
}

//============================== get_supervisor_employees
//-- get the employees for the given supervisor
//-- returns an array of user rows
function get_supervisor_employees($user) {
	global $users_cache;
	
	$sql = "SELECT * FROM es_user, es_user_sups WHERE us_emp_id=user_id AND us_sup_id=".$user["user_id"]." ORDER BY user_name";
	$users = get_db_items($sql);
	for($i=0; $i<count($users); $i++) {
		if (empty($users[$i]["user_name"])) $users[$i]["user_name"]="No Name";
		if (!empty($users[$i]["user_id"])) $users_cache[$users[$i]["user_id"]] = $users[$i];
	}
	usort($users, "user_cmp");
	return $users;
}

//============================== get_employee_supervisors
//-- get the supervisors for the given employee
//-- returns an array of user rows
function get_employee_supervisors($user) {
	global $users_cache;
	
	if (empty($user["user_id"])) return array();
	$sql = "SELECT * FROM es_user, es_user_sups WHERE us_sup_id=user_id AND us_emp_id=".$user["user_id"]." ORDER BY user_name";
	$users = get_db_items($sql);
	for($i=0; $i<count($users); $i++) {
		if (empty($users[$i]["user_name"])) $users[$i]["user_name"]="No Name";
		if (!empty($users[$i]["user_id"])) $users_cache[$users[$i]["user_id"]] = $users[$i];
	}
	usort($users, "user_cmp");
	return $users;
}

/**
 * get the supervisors
 * 
 * @return array of user rows
 */
function get_supervisors() {
	global $users_cache;
	
	$sql = "SELECT * FROM es_user WHERE user_type LIKE '%Supervisor%' OR user_type LIKE '%Admin%' ORDER BY user_name";
	$users = get_db_items($sql);
	for($i=0; $i<count($users); $i++) {
		if (empty($users[$i]["user_name"])) $users[$i]["user_name"]="No Name";
		if (!empty($users[$i]["user_id"])) $users_cache[$users[$i]["user_id"]] = $users[$i];
	}
	usort($users, "user_cmp");
	return $users;
}

/**
 * get the admins
 * 
 * @return array of user rows
 */
function get_admins() {
	global $users_cache;
	
	$sql = "SELECT * FROM es_user WHERE user_type LIKE '%Admin%' ORDER BY user_name";
	$users = get_db_items($sql);
	for($i=0; $i<count($users); $i++) {
		if (empty($users[$i]["user_name"])) $users[$i]["user_name"]="No Name";
		if (!empty($users[$i]["user_id"])) $users_cache[$users[$i]["user_id"]] = $users[$i];
	}
	usort($users, "user_cmp");
	return $users;
}

//============================== get_user_schedules
//-- get the schedules for the given user
//-- returns an array of schedule rows
function get_user_schedules($user) {
	global $schedule_cache, $user_cache;
	
	if (isset($user_cache[$user["user_id"]]["u_schedules"])) return $user_cache[$user["user_id"]]["u_schedules"];
	
	$sql = "SELECT * FROM es_schedule WHERE s_u_id=".$user["user_id"]." AND s_exptime>".time()." ORDER BY s_starttime";
	$schedules = get_db_items($sql);
	$ct = count($schedules);
	if ($ct<7) $ct = 7;
	$start = get_next_user_starttime($user);
	if ($start < time()) $start = mktime(1,0,0,date("m"), date("d"), date("Y"));
	for($i=0; $i<$ct; $i++) {
		if (!isset($schedules[$i])) {
			$schedules[$i] = array();
			$schedules[$i]["s_assignments"] = array();
			$schedules[$i]["s_hours"] = "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
			$schedules[$i]["s_comments"] = array();
			$schedules[$i]["s_repeat"] = 60*60*24*7;
			$schedules[$i]["s_starttime"] =  starttime_to_sunday($start + (60*60*24*$i));
			$schedules[$i]["s_exptime"] = exptime_to_saturday($start+(60*60*24*40));
			$schedules[$i]["s_group"] = $user["user_name"];
		}
		else {
			$sql = "SELECT * FROM es_schedule_comment WHERE sc_s_id=".$schedules[$i]["s_id"];
			$comments = get_db_items($sql);
			$schedules[$i]["s_comments"] = array();
			foreach($comments as $comment) {
				$schedules[$i]["s_comments"][$comment["sc_hour"]] = $comment;
			}
			$sql = "SELECT es_position_assignment.* FROM es_position_assignment, es_schedule WHERE pa_u_id=".$user["user_id"]." AND pa_s_id=s_id AND pa_us_id=".$schedules[$i]["s_id"];
			$assignments = get_db_items($sql);
			$schedules[$i]["s_assignments"] = array();
			foreach($assignments as $assignment) {
				$schedules[$i]["s_assignments"][$assignment["pa_hour"]] = $assignment;
			}
		}
		$schedules[$i]["s_exptime"] = exptime_to_saturday($schedules[$i]["s_exptime"]);
	}
	$schedules[0]["s_starttime"] = starttime_to_sunday($schedules[0]["s_starttime"]);
	if (!isset($user_cache[$user["user_id"]])) $user_cache[$user["user_id"]] = $user;
	$user_cache[$user["user_id"]]["u_schedules"] = $schedules;
	return $schedules;
}

//============================== get_past_user_schedules
//-- get the schedules for the given user
//-- returns an array of schedule rows
function get_past_user_schedules($user) {
	$sql = "SELECT * FROM es_schedule WHERE s_u_id=".$user["user_id"]." AND s_exptime<".time()." ORDER BY s_starttime";
	$schedules = get_db_items($sql);
	$ct = count($schedules);
	for($i=0; $i<$ct; $i++) {
		$sql = "SELECT * FROM es_schedule_comment WHERE sc_s_id=".$schedules[$i]["s_id"];
		$comments = get_db_items($sql);
		$schedules[$i]["s_comments"] = array();
		foreach($comments as $comment) {
			$schedules[$i]["s_comments"][$comment["sc_hour"]] = $comment;
		}
		$sql = "SELECT es_position_assignment.* FROM es_position_assignment, es_schedule WHERE pa_u_id=".$user["user_id"]." AND pa_s_id=s_id AND pa_us_id=".$schedules[$i]["s_id"]." AND s_exptime>".time()." AND s_starttime<".time();
		$assignments = get_db_items($sql);
		$schedules[$i]["s_assignments"] = array();
		foreach($assignments as $assignment) {
			$schedules[$i]["s_assignments"][$assignment["pa_hour"]] = $assignment;
		}
	}
	return $schedules;
}

//============================== get_timed_user_schedules
//-- get the schedules for the given user
//-- returns an array of schedule rows
function get_timed_user_schedules($user, $starttime, $exptime) {
	$sql = "SELECT * FROM es_schedule WHERE s_u_id=".$user["user_id"]." AND ((s_exptime<=$exptime AND s_exptime>$starttime) OR (s_starttime>=$starttime AND s_starttime<$exptime)) ORDER BY s_starttime";
	$schedules = get_db_items($sql);
	$ct = count($schedules);
	for($i=0; $i<$ct; $i++) {
		$sql = "SELECT * FROM es_schedule_comment WHERE sc_s_id=".$schedules[$i]["s_id"];
		$comments = get_db_items($sql);
		$schedules[$i]["s_comments"] = array();
		foreach($comments as $comment) {
			$schedules[$i]["s_comments"][$comment["sc_hour"]] = $comment;
		}
		$sql = "SELECT es_position_assignment.* FROM es_position_assignment, es_schedule WHERE pa_u_id=".$user["user_id"]." AND pa_s_id=s_id AND pa_us_id=".$schedules[$i]["s_id"]." AND s_exptime>".time()." AND s_starttime<".time();
		$assignments = get_db_items($sql);
		$schedules[$i]["s_assignments"] = array();
		foreach($assignments as $assignment) {
			$schedules[$i]["s_assignments"][$assignment["pa_hour"]] = $assignment;
		}
	}
	return $schedules;
}

/**
 * limit the schedules by start and end time
 *
 * this will loop through a list of schedules and only get the schedules that are within the
 * specified time range
 * @param array $schedules the schedules to check and limit
 * @param timestamp $starttime
 * @param timestamp $exptime
 */
function time_limit_schedules($schedules, $starttime, $exptime) {
	$limits = array();
	foreach($schedules as $schedule) {
		if ($schedule["s_exptime"]>=$exptime) $limits[] = $schedule;
	}
	return $limits;
}

//============================== get_next_user_starttime
//-- get the next available starttime
function get_next_user_starttime($user) {
	$sql = "SELECT MAX(s_exptime) FROM es_schedule WHERE s_u_id=".$user["user_id"]." ORDER BY s_starttime";
	$row = get_db_items($sql);
	if (empty($row[0][0])) return starttime_to_sunday(mktime(1,0,0,date("m"),date("d"),date("Y")));
	return starttime_to_sunday($row[0][0]);
}

//============================== get_next_position_starttime
//-- get the next available starttime
function get_next_position_starttime($position) {
	$sql = "SELECT MAX(s_exptime) FROM es_schedule WHERE s_p_id=".$position["p_id"]." ORDER BY s_starttime";
	$row = get_db_items($sql);
	if (empty($row[0][0])) return starttime_to_sunday(time());
	return starttime_to_sunday($row[0][0]);
}

//============================== check_user_schedules
//-- check the user's schedules to prevent overlapping
//-- times on user schedules
function check_user_schedules($starttime, $exptime, $s_group, $user) {
	$sql = "SELECT * FROM es_schedule WHERE s_u_id=".$user["user_id"]." AND s_group!='".addslashes($s_group)."' AND ((s_exptime>$starttime AND s_exptime<=$exptime) OR (s_starttime>=$starttime AND s_starttime<$exptime)) ORDER BY s_starttime";
	$schedules = get_db_items($sql);
	if (count($schedules)>0) return false;
	return true;
}

//============================== get_position_schedules
//-- get the schedules for the given position
//-- returns an array of schedule rows
function get_position_schedules($position) {
	global $schedule_cache, $position_cache, $REPEAT;
	
	if (isset($position_cache[$position["p_id"]]["p_schedules"])) return $position_cache[$position["p_id"]]["p_schedules"];
	
	$sql = "SELECT * FROM es_schedule WHERE s_p_id=".$position["p_id"]." AND s_exptime>".time()." ORDER BY s_starttime, s_group";
	$schedules = get_db_items($sql);
	$ct = count($schedules);
	if ($ct<7) $ct = 7;
	for($i=0; $i<$ct; $i++) {
		if (!isset($schedules[$i])) {
			$schedules[$i] = array();
			$schedules[$i]["s_assignments"] = array();
			$schedules[$i]["s_hours"] = "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
			$schedules[$i]["s_comments"] = array();
			$schedules[$i]["s_group"] = "";
			$schedules[$i]["s_repeat"] = $REPEAT[1];
			$schedules[$i]["s_starttime"] = starttime_to_sunday(time());
			$schedules[$i]["s_exptime"] = exptime_to_saturday(time()+(60*60*24*60));
		}
		else {
			$sql = "SELECT * FROM es_schedule_comment WHERE sc_s_id=".$schedules[$i]["s_id"];
			$comments = get_db_items($sql);
			$schedules[$i]["s_comments"] = array();
			foreach($comments as $comment) {
				$schedules[$i]["s_comments"][$comment["sc_hour"]] = $comment;
			}
			$sql = "SELECT * FROM es_position_assignment WHERE pa_s_id=".$schedules[$i]["s_id"];
			$assignments = get_db_items($sql);
			$schedules[$i]["s_assignments"] = array();
			foreach($assignments as $assignment) {
				if (empty($schedules[$i]["s_assignments"][$assignment["pa_hour"]])) $schedules[$i]["s_assignments"][$assignment["pa_hour"]] = array();
				$schedules[$i]["s_assignments"][$assignment["pa_hour"]][] = $assignment;
			}
		}
	}
	if (!isset($position_cache[$position["p_id"]])) $position_cache[$position["p_id"]] = $position;
	$position_cache[$position["p_id"]]["p_schedules"] = $schedules;
	return $schedules;
}

//============================== get_past_position_schedules
//-- get the schedules for the given position
//-- returns an array of schedule rows
function get_past_position_schedules($position) {
	global $REPEAT;
	$sql = "SELECT * FROM es_schedule WHERE s_p_id=".$position["p_id"]." AND s_exptime<".time()." ORDER BY s_group, s_starttime";
	$schedules = get_db_items($sql);
	$ct = count($schedules);
	if ($ct<7) $ct = 7;
	for($i=0; $i<$ct; $i++) {
		 if (!isset($schedules[$i])) {
			$schedules[$i] = array();
			$schedules[$i]["s_assignments"] = array();
			$schedules[$i]["s_hours"] = "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
			$schedules[$i]["s_comments"] = array();
			$schedules[$i]["s_group"] = "";
			$schedules[$i]["s_repeat"] = $REPEAT[1];
			$schedules[$i]["s_starttime"] = starttime_to_sunday(time());
			$schedules[$i]["s_exptime"] = exptime_to_saturday(time()+(60*60*24*60));
		}
		else {
			$sql = "SELECT * FROM es_schedule_comment WHERE sc_s_id=".$schedules[$i]["s_id"];
			$comments = get_db_items($sql);
			$schedules[$i]["s_comments"] = array();
			foreach($comments as $comment) {
				$schedules[$i]["s_comments"][$comment["sc_hour"]] = $comment;
			}
			$sql = "SELECT * FROM es_position_assignment WHERE pa_s_id=".$schedules[$i]["s_id"];
			$assignments = get_db_items($sql);
			$schedules[$i]["s_assignments"] = array();
			foreach($assignments as $assignment) {
				if (empty($schedules[$i]["s_assignments"][$assignment["pa_hour"]])) $schedules[$i]["s_assignments"][$assignment["pa_hour"]] = array();
				$schedules[$i]["s_assignments"][$assignment["pa_hour"]][] = $assignment;
			}
		}
	}
	return $schedules;
}

//============================== get_timed_position_schedules
//-- get the schedules for the given position
//-- returns an array of schedule rows
function get_timed_position_schedules($position, $starttime, $exptime) {
	$sql = "SELECT * FROM es_schedule WHERE s_p_id=".$position["p_id"]." AND ((s_exptime<=$exptime AND s_exptime>$starttime) OR (s_starttime>=$starttime AND s_starttime<$exptime)) ORDER BY s_group, s_starttime";
	$schedules = get_db_items($sql);
	$ct = count($schedules);
	for($i=0; $i<$ct; $i++) {
		$sql = "SELECT * FROM es_schedule_comment WHERE sc_s_id=".$schedules[$i]["s_id"];
		$comments = get_db_items($sql);
		$schedules[$i]["s_comments"] = array();
		foreach($comments as $comment) {
			$schedules[$i]["s_comments"][$comment["sc_hour"]] = $comment;
		}
		$sql = "SELECT * FROM es_position_assignment WHERE pa_s_id=".$schedules[$i]["s_id"];
		$assignments = get_db_items($sql);
		$schedules[$i]["s_assignments"] = array();
		foreach($assignments as $assignment) {
			if (empty($schedules[$i]["s_assignments"][$assignment["pa_hour"]])) $schedules[$i]["s_assignments"][$assignment["pa_hour"]] = array();
			$schedules[$i]["s_assignments"][$assignment["pa_hour"]][] = $assignment;
		}
	}
	return $schedules;
}

//============================== get_schedule_group
//-- get the schedules for the given position and group name
//-- returns an array of schedule rows
function get_schedule_group($group, $p_id) {
	$sql = "SELECT * FROM es_schedule WHERE s_p_id=".$p_id." AND s_group='".addslashes($group)."' AND s_exptime>".time()." ORDER BY s_starttime, s_id";
	$schedules = get_db_items($sql);
	for($i=0; $i<count($schedules); $i++) {
		$sql = "SELECT * FROM es_schedule_comment WHERE sc_s_id=".$schedules[$i]["s_id"];
		$comments = get_db_items($sql);
		$schedules[$i]["s_comments"] = array();
		foreach($comments as $comment) {
			$schedules[$i]["s_comments"][$comment["sc_hour"]] = $comment;
		}
		$sql = "SELECT * FROM es_position_assignment WHERE pa_s_id=".$schedules[$i]["s_id"];
		$assignments = get_db_items($sql);
		$schedules[$i]["s_assignments"] = array();
		foreach($assignments as $assignment) {
			if (empty($schedules[$i]["s_assignments"][$assignment["pa_hour"]])) $schedules[$i]["s_assignments"][$assignment["pa_hour"]] = array();
			$schedules[$i]["s_assignments"][$assignment["pa_hour"]][] = $assignment;
		}
//		$schedules[$i]["s_hours"] = expand_hours($schedules[$i]["s_hours"]);
	}
	return $schedules;
}

//============================== get_user_schedule_group
//-- get the schedules for the given position and group name
//-- returns an array of schedule rows
function get_user_schedule_group($group, $exptime, $user) {
	$sql = "SELECT * FROM es_schedule WHERE s_u_id=".$user["user_id"]." AND s_group='".addslashes($group)."' AND s_exptime=$exptime ORDER BY s_starttime, s_id";
	$schedules = get_db_items($sql);
	if (count($schedules)>0) {
		for($i=0; $i<count($schedules); $i++) {
			$sql = "SELECT * FROM es_schedule_comment WHERE sc_s_id=".$schedules[$i]["s_id"];
			$comments = get_db_items($sql);
			$schedules[$i]["s_comments"] = array();
			foreach($comments as $comment) {
				$schedules[$i]["s_comments"][$comment["sc_hour"]] = $comment;
			}
			$sql = "SELECT * FROM es_position_assignment WHERE pa_us_id=".$schedules[$i]["s_id"];
			$assignments = get_db_items($sql);
			$schedules[$i]["s_assignments"] = array();
			foreach($assignments as $assignment) {
				$schedules[$i]["s_assignments"][$assignment["pa_hour"]] = $assignment;
			}
//			$schedules[$i]["s_hours"] = expand_hours($schedules[$i]["s_hours"]);
		}
	}
	else {
		$schedules = get_user_schedules($user);
	}
	return $schedules;
}

//============================== get_schedule
//-- get the schedule for the given s_id
//-- returns a schedule row
function get_schedule($s_id) {
	$sql = "SELECT * FROM es_schedule WHERE s_id=".$s_id;
	$schedules = get_db_items($sql);
	for($i=0; $i<count($schedules); $i++) {
		$sql = "SELECT * FROM es_schedule_comment WHERE sc_s_id=".$schedules[$i]["s_id"];
		$comments = get_db_items($sql);
		$schedules[$i]["s_comments"] = array();
		foreach($comments as $comment) {
			$schedules[$i]["s_comments"][$comment["sc_hour"]] = $comment;
		}
		$sql = "SELECT * FROM es_position_assignment WHERE pa_s_id=".$schedules[$i]["s_id"];
		$assignments = get_db_items($sql);
		$schedules[$i]["s_assignments"] = array();
		foreach($assignments as $assignment) {
			if (empty($schedules[$i]["s_assignments"][$assignment["pa_hour"]])) $schedules[$i]["s_assignments"][$assignment["pa_hour"]] = array();
			$schedules[$i]["s_assignments"][$assignment["pa_hour"]][] = $assignment;
		}
//		$schedules[$i]["s_hours"] = expand_hours($schedules[$i]["s_hours"]);
	}
	return $schedules[0];
}

//============================== get_area
//-- get an area for the given area id
//-- returns an array
function get_area($a_id) {
	$sql = "SELECT * FROM es_area WHERE a_id=$a_id";
	$areas = get_db_items($sql);
	$area = $areas[0];
	$sql = "SELECT * FROM es_position WHERE p_a_id=".$area["a_id"];
	$positions = get_db_items($sql);
	$area["a_positions"] = $positions;
	return $area;
}

//============================== get_area_supervisors
//-- get the supervisors for the given area id
//-- returns an array
function get_area_supervisors($a_id) {
	if (empty($a_id)) return array();
	$sql = "SELECT * FROM es_user, es_area_sups WHERE as_a_id=$a_id AND user_id=as_u_id ORDER BY user_name";
	$supervisors = get_db_items($sql);
	usort($supervisors, "user_cmp");
	return $supervisors;
}

//============================== get_position
//-- get a position for the given position id
//-- returns an array
function get_position($p_id, $ignore_cache=false) {
	global $position_cache;
	
	
	if (empty($p_id)) return array("p_name"=>"Not Found");
	if (!$ignore_cache && isset($position_cache[$p_id])) $position = $position_cache[$p_id];
	else {
		$sql = "SELECT * FROM es_position WHERE p_id=$p_id";
		$positions = get_db_items($sql);
		if (isset($positions[0])) $position = $positions[0];
		else $position = array("p_id"=>$p_id, "p_name"=>"unknown");
	}
	if (!empty($position["p_a_id"])) {
		$sql = "SELECT * FROM es_area WHERE a_id=".$position["p_a_id"];
		$areas = get_db_items($sql);
		$position["p_area"] = $areas[0];
	}
	else {
		$position["p_area"] = array();
	}
	$position_cache[$p_id] = $position;
	return $position;
}

//============================== get_user_positions
//-- get the positions for the given user
//-- returns an array of position ids
function get_user_positions($user) {
	$schedules = get_user_schedules($user);
	$positions = array();
	foreach($schedules as $schedule) {
		foreach($schedule["s_assignments"] as $assignment) {
			if (isset($positions[$assignment["pa_p_id"]])) $positions[$assignment["pa_p_id"]]+=.25;
			else $positions[$assignment["pa_p_id"]] = .25;
		}
	}
	return $positions;
}

//============================== get_user
//-- get an area for the given user id
//-- returns an array
function get_user($u_id, $ignore_cache=false) {
	global $users_cache;
	
	if (!$ignore_cache && isset($users_cache[$u_id])) $user = $users_cache[$u_id];
	else {
		$sql = "SELECT * FROM es_user WHERE user_id=$u_id";
		$users = get_db_items($sql);
		if (count($users)==0) return false;
		$user = $users[0];
	}
	if (empty($user["user_id"])) return false;
	if (empty($user["user_name"])) $user["user_name"]="No Name";
	if (empty($user["user_min"])) $user["user_min"] = 20;
	if (empty($user["user_color"])) $user["user_color"] = "#".dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15));
	$users_cache[$u_id] = $user;
	return $user;
}

//============================== delete_position
//-- delete the given position from the database
function delete_position($position) {
	if (empty($position["p_id"])) return;
	$schedules = get_position_schedules($position);
	foreach($schedules as $schedule) delete_schedule($schedule);
	$schedules = get_past_position_schedules($position);
	foreach($schedules as $schedule) delete_schedule($schedule);
	$sql = "DELETE FROM es_position WHERE p_id=".$position["p_id"];
	$res = dbquery($sql);
}

//============================== delete_area
//-- delete the given area from the database
function delete_area($area) {
	foreach($area["a_positions"] as $position) {
		delete_position($position);
	}
	$sql = "DELETE FROM es_area_sups WHERE as_a_id=".$area["a_id"];
	$res = dbquery($sql);
	$sql = "DELETE FROM es_area WHERE a_id=".$area["a_id"];
	$res = dbquery($sql);
}

//============================== delete_schedule
//-- delete the given schedule from the database
function delete_schedule($schedule) {	
	if (!empty($schedule["s_id"])) {
		$sql = "DELETE FROM es_schedule WHERE s_id=".$schedule["s_id"];
		$res = dbquery($sql);
		$sql = "DELETE FROM es_schedule_comment WHERE sc_s_id=".$schedule["s_id"];
		$res = dbquery($sql);
		$sql = "DELETE FROM es_position_assignment WHERE pa_s_id=".$schedule["s_id"];
		$res = dbquery($sql);
		$sql = "DELETE FROM es_position_assignment WHERE pa_us_id=".$schedule["s_id"];
		$res = dbquery($sql);
	}
}

//============================== delete_schedule_group
//-- delete the given schedule group from the database
function delete_schedule_group($group, $p_id) { 
	$schedules = get_schedule_group($group, $p_id);
	foreach($schedules as $schedule) {
		delete_schedule($schedule);
	}
}

//============================== delete_user_schedule_group
//-- delete the given schedule group from the database
function delete_user_schedule_group($group, $exptime, $user) {	
	$schedules = get_user_schedule_group($group, $exptime, $user);
	foreach($schedules as $schedule) {
		delete_schedule($schedule);
	}
}

//============================== delete_user
//-- delete the given user id from the database
function delete_user($u_id) {
	$user = get_user($u_id);
	if ($user) {
		if ((!empty($user["user_picture"]))&&(file_exists("./photos/".$user["user_picture"]))) unlink("./photos/".$user["user_picture"]);
		$areas = get_supervisor_areas($user);
		foreach($areas as $area) {
			$sql = "DELETE FROM es_area_sups WHERE as_u_id=$u_id";
			$res = dbquery($sql);
			$sups = get_area_supervisors($area["a_id"]);
			if (count($sups)==0) {
				delete_area($area);
			}
		}
	
		$schedules = get_user_schedules($user);
		foreach($schedules as $schedule) {
			delete_schedule($schedule);
		}
		
		$schedules = get_past_user_schedules($user);
		foreach($schedules as $schedule) {
			delete_schedule($schedule);
		}
		
		$sql = "DELETE FROM es_user WHERE user_id=$u_id";
		$res = dbquery($sql);
		
		$sql = "DELETE FROM es_user_sups WHERE us_sup_id=$u_id OR us_emp_id=$u_id";
		$res = dbquery($sql);
		
	}
}

//============================== get_next_date
//-- Get the next date of a schedule
//-- return a unix timestap
function get_next_date($schedule, $day) {
	$start = $schedule["s_starttime"];
	$start = mktime(0,0,0,date("m", $start),date("d", $start),date("Y", $start));
	//-- add a day of seconds till we get to the day that we want
	while(date("w", $start) != $day) $start = $start + (60*60*24);
	//print date("m/d/Y", $start);
	$current = mktime(0,0,0,date("m"),date("d"),date("Y"));
	//-- while the start time is in the past, add the repeat interval to it
	while(($start < $current)&&($start < $schedule["s_exptime"])) $start = $start + $schedule["s_repeat"];
	//print date("m/d/Y", $start);
	return $start;
}

//============================== ldap_auth
//-- ldap authentication function
function ldap_auth($ldap_host, $ldap_port, $search_base, $user, $pass, $context, $filter, $attributes) {
	global $es_lang;
	
	$error_msg = "";												// -- start out with a blank error message.  Error message is printed at the end
	// -- ldap error codes
	$INVALID_CREDENTIALS = 49;
	$INVALID_USER = 32;
	// -- check for blank password
	if (empty($pass)) {
		return false;
	}
	else {
		// -- connect to ldap server on secure port
		$ds=ldap_connect($ldap_host, $ldap_port);
		// -- if we have a good connection
		if ($ds) {
			//ldap_get_option($ds, LDAP_OPT_PROTOCOL_VERSION,$value);
			ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
			//print_r($value);
			ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
			if (!empty($user)) $dn = "$user,$search_base";							// -- setup authentication string
			else $dn = $search_base;
			$ldap_result=@ldap_bind($ds, $dn, $pass);		// -- bind to the ldap server use @ to suppress printing of error messages
			if (!$ldap_result) {
				$error_number = ldap_errno($ds);							// -- get ldap error number
				if ($error_number==$INVALID_USER) { 			// -- no such user
					$error_msg = "<font color=red><b>".$es_lang["netid_not_exists"]."</b></font><br />";
				}
				else if ($error_number==$INVALID_CREDENTIALS) { 	// -- username password mismatch
					$error_msg = "<font color=red><b>".$es_lang["invalid_credentials"]."</b></font><br />";
				}
				else {
					$error_msg = "<font color=red><b>".ldap_error($ds)."</b></font><br />";
				}
			//	print $error_msg;
			}
			else {
				// -- Authentication successful
				$sr = ldap_search($ds, $context, $filter, $attributes);
				if (!$sr) {
					$error_msg = "<font color=red><b>".ldap_error($ds)."</b></font><br />";
					print $error_msg;
				}
				$info = ldap_get_entries($ds, $sr);
				return $info[0];
			}
			ldap_close($ds);					// -- close LDAP connection
		} else {
			$error_msg = "<h4>".$es_lang["no_ldap"]."</h4>";
		}
	}
//	print $error_msg;
	return false;
}
//-- end function

//============================== user_logged_in
//-- check if a user has logged in
//-- and return a user array
function user_logged_in() {
	if (isset($_SESSION["es_username"])) {
		$sql = "SELECT * FROM rma_user WHERE u_id='".$_SESSION["es_username"]."'";
		$res = dbquery($sql);
		if (mysql_num_rows($res)>0) {
			$user = mysql_fetch_array($res);
			return $user;
		}
		return false;
	}
	return false;
}

/**
 * authenticate a given user name and password 
 *
 * authenticates a user and returns a user array, should be called near the beginning of any
 * page.
 * @return $user returns an $user array or false if the authentication failed
 */
function auth_user() {
	global $LDAP_HOST, $LDAP_PORT, $LDAP_SEARCHBASE, $LDAP_CONTEXT, $LDAP_USER_ID_PROP, $LDAP_ATTRS_ARRAY, $es_lang, $DOUBLE_AUTH, $LANGUAGE, $es_language, $DBNAME, $DBUSER, $DBHOST;
	if ((!empty($_SESSION["es_username"]))&&(empty($_POST["username"]))) {
		$sql = "SELECT * FROM es_user WHERE user_netid='".$_SESSION["es_username"]."'";
		$res = dbquery($sql);
		if (mysql_num_rows($res)>0) {
			$user = db_cleanup(mysql_fetch_array($res));
			if (empty($user["user_min"])) $user["user_min"] = 20;
			$username = $_SESSION["es_username"];
			$_SESSION["es_username"] = $username;
			return db_cleanup($user);
		}
		print_login_form();
		return false;
	}
	else if(!empty($_POST["username"])) {
		$sql = "SELECT * FROM es_user WHERE user_netid='".$_POST["username"]."'";
		$res = dbquery($sql);
		if (mysql_num_rows($res)>0) {
			$user = db_cleanup(mysql_fetch_array($res));
			if (empty($user["user_min"])) $user["user_min"] = 20;
			//-- for non-byu employees do a crypt and check it
			if (preg_match("/LDAP/", $user['user_type'])==0) {
				if (crypt($_POST["userpass"], $user['user_password'])==$user['user_password']) {
					$_SESSION["es_username"] = $_POST["username"];
					$_SESSION["user_type"] = $user["user_type"];
					if (!empty($user["user_language"])) {
						$_SESSION["CLANGUAGE"] = $user["user_language"];
						$LANGUAGE = $user["user_language"];
						if (isset($es_language[$LANGUAGE])) require($es_language[$LANGUAGE]);
					}
					return $user;
				}
			}
			//-- for ldap employees check ldap_auth
			else {
				if (!isset($DOUBLE_AUTH)) {
					//-- perform ldap authentication to bind to ldap server ldap server
					$attrs = ldap_auth($LDAP_HOST, $LDAP_PORT, $LDAP_SEARCHBASE, "uid=".$user['user_netid'], $_POST["userpass"], "ou=people,o=byu.edu", "uid=".$user["user_netid"], $LDAP_ATTRS_ARRAY);
					if ($attrs!==false) {
						$_SESSION["es_username"] = $_POST["username"];
						$_SESSION["user_type"] = $user["user_type"];
						if (!empty($user["user_language"])) {
							$_SESSION["CLANGUAGE"] = $user["user_language"];
							$LANGUAGE = $user["user_language"];
							if (isset($es_language[$LANGUAGE])) require($es_language[$LANGUAGE]);
						}
						return $user;
					}
				}
				else {
					require("es_idaho_auth.php");
					if ($authenticated) {
						if (!empty($user["user_language"])) {
							$_SESSION["CLANGUAGE"] = $user["user_language"];
							$LANGUAGE = $user["user_language"];
							if (isset($es_language[$LANGUAGE])) require($es_language[$LANGUAGE]);
						}
						return $user;
					}
				}
			}
		}
		print_login_form("<font class=\"error\">".$es_lang["no_employee"]."</font>");
	}
	else {
		print_login_form();
	}
	return false;
}

/**
 * authenticate a supervisor
 *
 * authenticate a supervisor with the given user name and password 
 * and return a user array, must be called before any
 * text is printed for cookies to be set properly
 * @return array $user the table row array representing a user
 */
function auth_supervisor() {
	global $es_lang;
	
	$user = auth_user();
	if (preg_match("/(Supervisor)|(Admin)/i", $user["user_type"])==0) show_error_page($es_lang["access_denied"]);
	return $user;
}

/**
 * authenticate an administrator
 *
 * authenticate an administrator with the given user name and password 
 * and return a user array, must be called before any
 * text is printed for cookies to be set properly
 * @return array $user the table row array representing a user
 */
function auth_admin() {
	global $es_lang;
	
	$user = auth_user();
	if (preg_match("/Admin/i", $user["user_type"])==0) show_error_page($es_lang["access_denied"]);
	return $user;
}

//============================== print_login_form
//-- print a form for users to login
//-- the optional $msg parameter allows a message 
//-- to be included when the form is printed
function print_login_form($msg="") {
	global $es_lang;
	if (empty($_POST["username"])) $_POST["username"]="";
	print_header($es_lang["login_title"]);
	?>
	<center>
	<br /><br /><span class="pagetitle"><?php print $es_lang["login_title"];?></span><br />
	<img src="images/bar_1.gif" width="50%" height="2">
	<br /><br /><?php print $msg?>
	<br /><br /><span class="heading"><?php print $es_lang["enter_username"]; ?></span><br />
	<br />
	<form name="loginform" method="post">
	<table>
		<tr>
		<td align="right" class="text"><?php print $es_lang["username"];?> </td><td><input type="text" name="username" value="<?php print $_POST['username']?>" style="width: 150px;"></td>
		</tr>
		<tr>
		<td align="right" class="text"><?php print $es_lang["password"];?> </td><td><input type="password" name="userpass" style="width: 150px;"></td>
		</tr>
	</table>
	<input type="image" src="images/gobutton.gif" value="<?php print $es_lang["login"];?>">
	</form>
	</center>
	<script language="JavaScript">
		document.loginform.username.focus();
	</script>
	<?php
	print_footer();
	exit;
}

//============================== print_header
// -- print the opening html headers
function print_header($title) {
	global $view, $CHARACTER_SET, $es_lang, $COMPANY_NAME, $COMPANY_URL;
	
	header("Content-Type: text/html; charset=$CHARACTER_SET");
?>
<!DOCTYPE html>
<!--[if IE 7]>					<html class="ie7 no-js" lang="en">     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js" lang="en">     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" lang="en">  <!--<![endif]-->
<head>
		<title><?php print $title; ?></title>
		<link rel="icon" type="image/ico" href="./images/favicon.ico" />

		<link rel="stylesheet" type="text/css" href="./stylesheets/style.css" />
		<link rel="stylesheet" type="text/css" href="./stylesheets/shift.css" />


		<!-- initialize jQuery Library -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

		<!--[if lt IE 9]>
    	<script src="js/modernizr.custom.js"></script>
		<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
		<script type="text/javascript" src="js/ie.js"></script>
		<![endif]-->


		<script>
function approve(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","approve.php?q="+str, true);
xmlhttp.send();
}
</script>



<meta charset="UTF-8">

	</head>
<body class="color-1 pattern-1 h-style-1 text-1">
	
	<!-- ***************** - BEGIN Top Holder - ***************** -->
	<div class="top-holder"></div><!--/ top-holder-->
	<!-- ***************** - END Top Holder - ******************* -->
	
	
	<!-- ***************** - BEGIN Wrapper - ******************* -->
	<div id="wrapper">

<?php
	if ($view!="print") {
		include ("header.php");
?>

<!-- ************ - BEGIN Breadcrumbs - ************** -->
				<div id="breadcrumbs">
					<a title="Home" href="#"></a>  
				</div><!--/ breadcrumbs-->	
				<!-- ************ - END Breadcrumbs - ************** -->

				
				<!-- ************ - BEGIN Content Wrapper - ************** -->	
				<div class="content-wrapper">

<!--<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">-->
<tr>
<?php
		global $user;
		if ($user) {
			print '<td width="20%" height="100%" valign="top">';
			if (preg_match("/(Supervisor)|(Admin)/i", $user["user_type"])>0) {
				//print_supervisor_menu();
			}
			else {
				//print_employee_menu();
			}
		}
		print "</td><td width=\"15\">&nbsp;</td>";
	}
	else {
		print '<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0"><tr>';
	}
	print "<td valign=\"top\" class=\"text\">";
}

//============================== print_simple_header
// -- print a simplified version of the opening html headers
function print_simple_header($title) {
	global $view, $CHARACTER_SET;
	
	header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title><?php print $title?></title>
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?php print $CHARACTER_SET;?>">
		<link rel="stylesheet" href="es_style.css" type="text/css">
	</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
	print '<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0"><tr>';
	print "<td valign=\"top\" class=\"text\">";
}

//============================== print_footer
// -- print the closing html footers
function print_footer() {
	include ("footer.php");

}

//============================== print_employee_menu
// -- print the navigation menu for employees
function print_employee_menu() {
	global $user, $es_lang;
?>

<!-- ***************** - BEGIN Sidebar - ******************* -->
		<aside id="sidebar">
			
			
			<!-- ************* - BEGIN Categories Widget - *************** -->
			<div class="package">


<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr valign="top"> 
  <td height="100%" bgcolor="F0F0E2">
	<div style="padding-left: 15px;">
	<br />
	<span class='navtitle'>
	<?php if (!empty($user["user_picture"])) print '<img align="middle" src="photos/'.$user["user_picture"].'" width="40"> '; else print $es_lang["welcome"];?>
	<?php print $user["user_name"];?></span><br /><br />
	<table>
	<tr><td>
	<a href='es_emp_index.php' class='navlink'><b><?php print $es_lang["my_schedule"]; ?></b></a>
	</td></tr>

	<tr><td>
	<a href='emp_change_schedule.php' class='navlink'><b>Change Schedule</b></a>
	</td></tr>

	<tr><td>
	<a href='emp_vacation.php' class='navlink'><b>Ask For Vacation</b></a>
	</td></tr>

	<tr><td>
	<a href='vacation-report.php' class='navlink'><b><?php print $es_lang["reports"]; ?></b></a>
	</td></tr>
	
	<tr><td>
	</td></tr>
	<tr><td>
	<a href='es_emp_edit_info.php' class='navlink'><b><?php print $es_lang["my_info"]; ?></b></a>
	</td></tr>
	<tr><td>
	<a href='es_emp_supervisors.php' class='navlink'><b><?php print $es_lang["my_sups"]; ?></b></a>
	</td></tr>
	<tr><td>
	<a href='es_emp_colleagues.php' class='navlink'><b><?php print $es_lang["my_colleagues"]; ?></b></a>
	</td></tr>
	<tr><td>
	<a href='es_emp_positions.php' class='navlink'><b><?php print $es_lang["my_positions"]; ?></b></a>
	</td></tr>

	<tr><td>
	<a href='register_patients.php' class='navlink'><b>Register Patients</b></a>
	</td></tr>

	<tr><td>
	<a href='view_patients.php' class='navlink'><b>View Patients</b></a>
	</td></tr>


	<tr><td>
	<a href='es_logout.php' class='navlink'><b><?php print $es_lang["logout"]; ?></b></a>
	</td></tr>
	</table>
	</div>
	<br /><br />
  </td>
  <td width="11" height="100%" ><img src="images/shadow.gif" width="11" height="100%"></td>
</tr>
</table>

</div><!--/ package-->
</aside><!--/ sidebar-->
		<div class="clear"></div>
		<!-- ***************** - END Sidebar - ******************* -->
</div><!--/wrapper -->
<?php
}

//============================== print_supervisor_menu
// -- print the navigation menu for supervisors
function print_supervisor_menu() {
	global $user, $es_lang;
?>
<!-- ***************** - BEGIN Sidebar - ******************* -->
		<aside id="sidebar">
			
			
			<!-- ************* - BEGIN Categories Widget - *************** -->
			<div class="package">

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr valign="top"> 
  <td height="100%" bgcolor="F0F0E2">
	<div style="padding-left: 15px;">
	<br />
	<span class='navtitle'>
	<?php if (!empty($user["user_picture"])) print '<img align="middle" src="photos/'.$user["user_picture"].'" width="40"> '; else print $es_lang["welcome"];?>
	<?php print $user["user_name"];?></span><br /><br />
	<table>
	<tr><td>
	<a href='es_sup_index.php' class='navlink'><b><?php print $es_lang["areas_and_positions"]; ?></b></a>
	</td></tr>
	<tr><td>
	<a href='es_sup_employees.php' class='navlink'><b><?php print $es_lang["employees"]; ?></b></a>
	</td></tr>

	<tr><td>
	<a href='view_change_requests.php' class='navlink'><b>View Schedule Change Requests</b></a>
	</td></tr>


	<tr><td>
	<a href='view_vacation_requests.php' class='navlink'><b>View Vacation Requests</b></a>
	</td></tr>


	<tr><td>
	<a href='es_sup_supervisors.php' class='navlink'><b><?php print $es_lang["supervisors"]; ?></b></a>
	</td></tr>
	<tr><td>
	<a href='vacation-report.php' class='navlink'><b><?php print $es_lang["reports"]; ?></b></a>
	</td></tr>
	<tr><td>
	<a href='es_sup_edit_info.php' class='navlink'><b><?php print $es_lang["my_info"]; ?></b></a>
	</td></tr>
	<tr><td>
	<a href='es_emp_index.php' class='navlink'><b><?php print $es_lang["my_schedule"]; ?></b></a>
	</td></tr>
	<tr><td>
	<?php if (strstr($user["user_type"], "Admin")) { ?>
	</td></tr>
	<tr><td>
	<a href='es_admin_settings.php' class='navlink'><b><?php print $es_lang["edit_settings"]; ?></b></a>
	<?php } ?>
	</td></tr>

	<tr><td>
	<a href='register_patients.php' class='navlink'><b>Register Patients</b></a>
	</td></tr>

	<tr><td>
	<a href='view_patients.php' class='navlink'><b>View Patients</b></a>
	</td></tr>

	<tr><td>
	<a href='es_logout.php' class='navlink'><b><?php print $es_lang["logout"]; ?></b></a>
	</td></tr>
	</table>
	</div>
	<br /><br />
  </td>
  <td width="11" height="100%" ><img src="images/shadow.gif" width="11" height="100%"></td>
</tr>
</table>
</div><!--/ package-->
</aside><!--/ sidebar-->
		<div class="clear"></div>
		<!-- ***************** - END Sidebar - ******************* -->
</div><!--/wrapper -->
<?php
}

//============================== user_cmp
// -- Compare employees for usort function
function user_cmp($a, $b) {
	$aname = $a["user_name"];
	$bname = $b["user_name"];
	$anames = preg_split("/ /", $aname);
	$bnames = preg_split("/ /", $bname);
	$anames = array_reverse($anames);
	$bnames = array_reverse($bnames);
	for($i=0; ($i<count($anames))||($i<count($bnames)); $i++) {
		$cmp = strnatcasecmp($anames[$i], $bnames[$i]);
		if ($cmp!=0) return $cmp;
	}
	return 0;
}

//============================== starttime_to_sunday
//-- adjust the start time to the next sunday
function starttime_to_sunday($time) {
	$day = date("w", $time);
	if ($day==0) return $time;
	$time = ($time - $day*60*60*24)+(60*60*24*7);
	return $time;
}

//============================== exptime_to_saturday
//-- adjust the start time to the next saturday
function exptime_to_saturday($time) {
	$day = date("w", $time);
	if ($day==6) return $time;
	$time = $time + (6-$day)*60*60*24;
	return $time;
}

/**
 * expand an old schedule
 *
 * expand an old v1 schedule hours from 24 to 96 entries
 */
function expand_hours($hours) {
	$sections = strlen($hours);
	if ($sections<96) {
		$insert = 96/$sections;
		$newhours = "";
		for($i=0; $i<$sections; $i++) {
			for($j=0; $j<$insert; $j++) {
				$newhours .= $hours[$i];
			}
		}
		return $newhours;
	}
	else return $hours;
}

function print_execution_stats() {
	global $TOTAL_QUERIES, $ES_SHOW_STATS, $EXEC_START_TIME;

	if ($ES_SHOW_STATS) {
		$end_time = getmicrotime();
		$exectime = $end_time - $EXEC_START_TIME;
		print "<br />Execution Time: ";
		printf(" %.3f sec", $exectime);
		print ". Total Queries: $TOTAL_QUERIES";
	}
}



//FUNCTION FOR GENERATING THE SCHEDULE AND SAVING IT INTO THE DATABASE//

function shift_generator() {
			$x = rand(0, 6);
			$y = rand(0, 6);
			$z = rand(0, 6);

			$days_off = $x.$y.$z;

			return $days_off;
	}







function print_header_patients($title) {
	global $view, $CHARACTER_SET, $es_lang, $COMPANY_NAME, $COMPANY_URL;
	
	header("Content-Type: text/html; charset=$CHARACTER_SET");
?>
<!DOCTYPE html>
<!--[if IE 7]>					<html class="ie7 no-js" lang="en">     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js" lang="en">     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" lang="en">  <!--<![endif]-->
<head>
		<title><?php print $title; ?></title>
		<link rel="icon" type="image/ico" href="./images/favicon.ico" />

		<link rel="stylesheet" type="text/css" href="./stylesheets/view-patients-style.css" />
		<link rel="stylesheet" type="text/css" href="./stylesheets/shift.css" />


		<!-- initialize jQuery Library -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

		<!--[if lt IE 9]>
    	<script src="js/modernizr.custom.js"></script>
		<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
		<script type="text/javascript" src="js/ie.js"></script>
		<![endif]-->

<meta charset="UTF-8">

	</head>
<body class="color-1 pattern-1 h-style-1 text-1">
	
	<!-- ***************** - BEGIN Top Holder - ***************** -->
	<div class="top-holder"></div><!--/ top-holder-->
	<!-- ***************** - END Top Holder - ******************* -->
	
	
	<!-- ***************** - BEGIN Wrapper - ******************* -->
	<div id="wrapper">

<?php
	if ($view!="print") {
		include ("header.php");
?>

<!-- ************ - BEGIN Breadcrumbs - ************** -->
				<div id="breadcrumbs">
					<a title="Home" href="#"></a>  
				</div><!--/ breadcrumbs-->	
				<!-- ************ - END Breadcrumbs - ************** -->

				
				<!-- ************ - BEGIN Content Wrapper - ************** -->	
				<div class="content-wrapper">

<!--<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">-->
<tr>
<?php
		global $user;
		if ($user) {
			print '<td width="20%" height="100%" valign="top">';
			if (preg_match("/(Supervisor)|(Admin)/i", $user["user_type"])>0) {
				//print_supervisor_menu();
			}
			else {
				//print_employee_menu();
			}
		}
		print "</td><td width=\"15\">&nbsp;</td>";
	}
	else {
		//print '<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0"><tr>';
	}
	print "<td valign=\"top\" class=\"text\">";
}

?>