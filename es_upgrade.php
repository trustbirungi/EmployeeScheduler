<?php
/*********************************************************
	File: es_upgrade.php
	Project: Employee Scheduler
	
	Comments:
		This file will upgrade the databases to version 2.0
	
**********************************************************/

require "es_functions.php";
$user = auth_supervisor();

print_header("Upgrading Scheduler Database");

if ($DBVERSION=="1.0") {
	set_time_limit(0);
	$sql = "CREATE TABLE es_settings (
		se_version VARCHAR(30),
		se_SITE_URL VARCHAR(255),
		se_SESSION_COOKIE_TIMEOUT INT,
		se_CHARACTER_SET VARCHAR(30),
		se_SITE_ADMIN_EMAIL VARCHAR(255),
		se_COMPANY_URL VARCHAR(255),
		se_COMPANY_NAME VARCHAR(255),
		se_START_HOUR INT,
		se_END_HOUR INT,
		se_DEFAULT_TIME_BLOCKS INT,
		se_ES_SHOW_STATS CHAR(1),
		se_ES_FULL_MAIL_TO CHAR(1),
		se_PRIORITY_0 VARCHAR(10),
		se_PRIORITY_1 VARCHAR(10),
		se_PRIORITY_2 VARCHAR(10),
		se_PRIORITY_3 VARCHAR(10),
		se_PRIORITY_4 VARCHAR(10),
		se_PRIORITY_5 VARCHAR(10),
		se_PRIORITY_6 VARCHAR(10),
		se_PRIORITY_7 VARCHAR(10),
		se_PRIORITY_8 VARCHAR(10))";
	$res = dbquery($sql);
	if ($res) print "Successfully created <b>es_settings</b> table.<br />";
	
	if (!isset($SITE_URL)) $SITE_URL = "http://www.yourdomain.com/";
	if (!isset($SESSION_COOKIE_TIMEOUT)) $SESSION_COOKIE_TIMEOUT = 1800;
	if (!isset($CHARACTER_SET)) $CHARACTER_SET = "UTF-8";
	if (!isset($SITE_ADMIN_EMAIL)) $SITE_ADMIN_EMAIL = "you@yourdomain.com";
	if (!isset($COMPANY_URL)) $COMPANY_URL = "http://empscheduler.sourceforge.net";
	if (!isset($COMPANY_NAME)) $COMPANY_NAME = "Employee Scheduler Home Page";
	if (!isset($START_HOUR)) $START_HOUR = 0;
	if (!isset($END_HOUR)) $END_HOUR = 24;
	if (!isset($DEFAULT_TIME_BLOCKS)) $DEFAULT_TIME_BLOCKS = 24;
	if (!isset($ES_SHOW_STATS)) $ES_SHOW_STATS = 0;
	if (!isset($ES_FULL_MAIL_TO)) $ES_FULL_MAIL_TO = 0;
	if (!isset($PRIORITY)) $PRIORITY = array();
	if (!isset($PRIORITY[0])) $PRIORITY[0] = "#BBBBBB";
	if (!isset($PRIORITY[1])) $PRIORITY[1] = "#AAAAff";
	if (!isset($PRIORITY[2])) $PRIORITY[2] = "#CCCCFF";
	if (!isset($PRIORITY[3])) $PRIORITY[3] = "#EEEEFF";
	if (!isset($PRIORITY[4])) $PRIORITY[4] = "#DDDDFF";
	if (!isset($PRIORITY[5])) $PRIORITY[5] = "#EEEEFF";
	if (!isset($PRIORITY[6])) $PRIORITY[6] = "#999999";
	if (!isset($PRIORITY[7])) $PRIORITY[7] = "#FFFFFF";
	if (!isset($PRIORITY[8])) $PRIORITY[8] = "#CC9988";
	
	$sql = "INSERT INTO es_settings VALUES (
		'2.1',
		'$SITE_URL',
		$SESSION_COOKIE_TIMEOUT,
		'$CHARACTER_SET',
		'$SITE_ADMIN_EMAIL',
		'$COMPANY_URL',
		'$COMPANY_NAME',
		$START_HOUR,
		$END_HOUR,
		$DEFAULT_TIME_BLOCKS,
		'$ES_SHOW_STATS',
		'$ES_FULL_MAIL_TO',
		'$PRIORITY[0]',
		'$PRIORITY[1]',
		'$PRIORITY[2]',
		'$PRIORITY[3]',
		'$PRIORITY[4]',
		'$PRIORITY[5]',
		'$PRIORITY[6]',
		'$PRIORITY[7]',
		'$PRIORITY[8]')";
	$res = dbquery($sql);
	if ($res) print "Successfully saved default settings in database.<br />";
	
	$sql = "ALTER TABLE es_user ADD u_color VARCHAR(10)";
	$res = dbquery($sql);
	if ($res) print "Successfully added color field to users table.<br />";
	
	$sql = "ALTER TABLE es_user ADD u_language VARCHAR(30)";
	$res = dbquery($sql);
	if ($res) print "Successfully added language field to users table.<br />";
	
	$sql = "ALTER TABLE es_user CHANGE u_type u_type ENUM('LDAP Admin','Admin','LDAP Supervisor','Supervisor','LDAP Employee','Employee')";
	$res = dbquery($sql);
	if ($res) print "Successfully added color field to users table.<br />";
	
	$sql = "ALTER TABLE es_schedule CHANGE s_hours s_hours VARCHAR(96)";
	$res = dbquery($sql);
	$sql = "ALTER TABLE es_schedule ADD INDEX user (s_u_id)";
	$res = dbquery($sql);
	$sql = "ALTER TABLE es_schedule ADD INDEX position (s_p_id)";
	$res = dbquery($sql);
	if ($res) print "Successfully updated es_schedule table.<br />";
	
	$sql = "ALTER TABLE es_user_sups ADD INDEX sup (us_sup_id)";
	$res = dbquery($sql);
	$sql = "ALTER TABLE es_user_sups ADD INDEX emp (us_emp_id)";
	$res = dbquery($sql);
	if ($res) print "Successfully updated es_user_sups table.<br />";
	
	$sql = "ALTER TABLE es_area_sups ADD INDEX area (as_a_id)";
	$res = dbquery($sql);
	$sql = "ALTER TABLE es_area_sups ADD INDEX user (as_u_id)";
	$res = dbquery($sql);
	if ($res) print "Successfully updated es_area_sups table.<br />";
	
	$sql = "ALTER TABLE es_position ADD INDEX area (p_a_id)";
	$res = dbquery($sql);
	if ($res) print "Successfully updated es_position table.<br />";
	
	$sql = "ALTER TABLE es_position_assignment ADD INDEX user (pa_u_id)";
	$res = dbquery($sql);
	$sql = "ALTER TABLE es_position_assignment ADD INDEX position (pa_p_id)";
	$res = dbquery($sql);
	$sql = "ALTER TABLE es_position_assignment ADD INDEX schedule (pa_s_id)";
	$res = dbquery($sql);
	$sql = "ALTER TABLE es_position_assignment ADD INDEX user_schedule (pa_us_id)";
	$res = dbquery($sql);
	if ($res) print "Successfully updated es_position_assignment table.<br />";
	
	$sql = "ALTER TABLE es_schedule_comment ADD INDEX schedule (sc_s_id)";
	$res = dbquery($sql);
	if ($res) print "Successfully updated es_schedule_comment table.<br />";
	
	flush();
	
	//-- upgrade the schedule table to support 96 entries instead of just 24
	$sql = "SELECT * FROM es_schedule";
	$res = dbquery($sql);
	while($row = mysql_fetch_array($res)) {
		$row["s_hours"] = expand_hours($row["s_hours"]);
		$sql2 = "UPDATE es_schedule SET s_hours='".$row["s_hours"]."' WHERE s_id=".$row["s_id"];
		$res2 = dbquery($sql2);
	}
	if ($res2) print "Successfully updated s_hours field of es_schedule table.<br />";
	
	$sql = "UPDATE es_position_assignment SET pa_hour=pa_hour*4";
	$res = dbquery($sql);
	
	//-- upgrade the position_assignment table to support 96
	$sql = "SELECT * FROM es_position_assignment";
	$res = dbquery($sql);
	while($row = mysql_fetch_array($res)) {
		for($i=1; $i<4; $i++) {
			$sql2 = "INSERT INTO es_position_assignment (pa_u_id, pa_p_id, pa_s_id, pa_us_id, pa_hour) VALUES (".$row["pa_u_id"].", ".$row["pa_p_id"].", ".$row["pa_s_id"].", ".$row["pa_us_id"].", ".($row["pa_hour"]+$i).")";
			$res2 = dbquery($sql2);
		}
	}
	if ($res2) print "Successfully updated pa_hour field of es_position_assignment table.<br />";
	
	flush();
	
	$sql = "UPDATE es_schedule_comment SET sc_hour=sc_hour*4";
	$res = dbquery($sql);
	
	//-- upgrade the schedule_comment table to support 96
	$sql = "SELECT * FROM es_schedule_comment";
	$res = dbquery($sql);
	while($row = mysql_fetch_array($res)) {
		for($i=1; $i<4; $i++) {
			$sql2 = "INSERT INTO es_schedule_comment (sc_s_id, sc_hour, sc_course, sc_building, sc_comment) VALUES (".$row["sc_s_id"].", ".($row["sc_hour"]+$i).", '".$row["sc_course"]."', '".$row["sc_building"]."', '".$row["sc_comment"]."')";
			$res2 = dbquery($sql2);
		}
	}
	if ($res2) print "Successfully updated sc_hour field of es_schedule_comment table.<br />";
	
	flush();
	if (strstr($user["u_type"], "LDAP")===false) $sql = "UPDATE es_user SET u_type='Admin' WHERE u_id=".$user["u_id"];
	else $sql = "UPDATE es_user SET u_type='LDAP Admin' WHERE u_id=".$user["u_id"];
	$res = dbquery($sql);
	if ($res) print "Successfully updated your user to Admin.<br />";
	print "Upgrade completed successfully.<br />";
	$_SESSION["u_type"]="Admin";
}
else if ($DBVERSION=="2.0") {
	$sql = "ALTER TABLE es_user ADD u_language VARCHAR(30)";
	$res = dbquery($sql);
	if ($res) print "Successfully added language field to users table.<br />";
	
	$sql = "UPDATE es_settings SET se_version='2.1'";
	$res = dbquery($sql);
	print "Successfully updated settings table.<br />";
	print "Upgrade completed successfully.<br />";
}
else {
	print "<br /><br /><b>No upgrade necessary.</b>";
}

print_footer();

?>
