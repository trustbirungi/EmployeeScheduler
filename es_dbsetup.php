<?php
/*********************************************************
	File: es_dbsetup.php
	Project: Employee Scheduler
	
	Comments:
		This file sets up the database tables for the 
		employee scheduler
	
	
**********************************************************/

require "es_functions.php";

print_header("Creating Scheduler Database");

if (empty($action)) $action = "setupdb";

if ($action=="setupdb") {
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
if ($res) print "Successfully created <b>es_settings</b> table.<br>";

$sql = "INSERT INTO es_settings VALUES (
	'2.1',
	'http://www.yourdomain.com/',
	1800,
	'UTF-8',
	'you@yourdomain.com',
	'http://empscheduler.sourceforge.net',
	'Employee Scheduler Home Page',
	0,
	24,
	24,
	0,
	1,
	'#BBBBBB',
	'#AAAAff',
	'#CCCCFF',
	'#EEEEFF',
	'#DDDDFF',
	'#EEEEFF',
	'#999999',
	'#FFFFFF',
	'#CC9988')";
$res = dbquery($sql);
if ($res) print "Successfully saved default settings in database.<br>";
	
$sql = "CREATE TABLE es_area (
	a_id INT NOT NULL auto_increment,
	a_name VARCHAR(50) NOT NULL,
	a_description TEXT,
	PRIMARY KEY(a_id))";
$res = dbquery($sql);
if ($res) print "Successfully created <b>es_area</b> table.<br>";

$sql = "CREATE TABLE es_user (
	u_id INT NOT NULL auto_increment,
	u_netid VARCHAR(30) NOT NULL,
	u_password VARCHAR(255),
	u_type ENUM('LDAP Admin','Admin','LDAP Supervisor','Supervisor','LDAP Employee','Employee'),
	u_name VARCHAR(255),
	u_major VARCHAR(50),
	u_workphone VARCHAR(15),
	u_homephone VARCHAR(15),
	u_location VARCHAR(255),
	u_email VARCHAR(255),
	u_min INT,
	u_max INT,
	u_hours INT,
	u_picture TEXT,
	u_notes TEXT,
	u_supnotes TEXT,
	u_color VARCHAR(10),
	u_language VARCHAR(30),
	PRIMARY KEY(u_id))";
$res = dbquery($sql);
if ($res) print "Successfully created <b>es_user</b> table.<br>";

$sql = "CREATE TABLE es_user_sups (
	us_id INT NOT NULL auto_increment,
	us_sup_id INT NOT NULL,
	us_emp_id INT NOT NULL,
	KEY sup (us_sup_id),
	KEY emp (us_emp_id),
	PRIMARY KEY(us_id))";
$res = dbquery($sql);
if ($res) print "Successfully created <b>es_user_sups</b> table.<br>";

$sql = "CREATE TABLE es_area_sups (
	as_id INT NOT NULL auto_increment,
	as_a_id INT NOT NULL,
	as_u_id INT NOT NULL,
	KEY area (as_a_id),
	KEY user (as_u_id),
	PRIMARY KEY(as_id))";
$res = dbquery($sql);
if ($res) print "Successfully created <b>es_area_sups</b> table.<br>";

$sql = "CREATE TABLE es_position (
	p_id INT NOT NULL auto_increment,
	p_name VARCHAR(20) NOT NULL,
	p_description TEXT,
	p_a_id INT NOT NULL,
	KEY area (p_a_id),
	PRIMARY KEY(p_id))";
$res = dbquery($sql);
if ($res) print "Successfully created <b>es_position</b> table.<br>";

$sql = "CREATE TABLE es_schedule (
	s_id INT NOT NULL auto_increment,
	s_u_id INT,
	s_p_id INT,
	s_group VARCHAR(30),
	s_starttime INT NOT NULL,
	s_hours VARCHAR(96) NOT NULL,
	s_repeat INT NOT NULL,
	s_exptime INT NOT NULL,
	s_notes TEXT,
	s_lastupdated TIMESTAMP(14),
	KEY user (s_u_id),
	KEY position (s_p_id),
	PRIMARY KEY(s_id))";
$res = dbquery($sql);
if ($res) print "Successfully created <b>es_schedule</b> table.<br>";

$sql = "CREATE TABLE es_position_assignment (
	pa_id INT NOT NULL auto_increment,
	pa_u_id INT NOT NULL,
	pa_p_id INT NOT NULL,
	pa_s_id INT NOT NULL,
	pa_us_id INT NOT NULL,
	pa_hour INT NOT NULL,
	pa_note TEXT,
	KEY user (pa_u_id),
	KEY position (pa_p_id),
	KEY schedule (pa_s_id),
	KEY user_schedule (pa_us_id),
	PRIMARY KEY(pa_id))";
$res = dbquery($sql);
if ($res) print "Successfully created <b>es_position_assignment</b> table.<br>";

$sql = "CREATE TABLE es_schedule_comment (
	sc_id INT NOT NULL auto_increment,
	sc_s_id INT NOT NULL,
	sc_hour INT NOT NULL,
	sc_course VARCHAR(30),
	sc_building VARCHAR(30),
	sc_comment TEXT,
	KEY schedule (sc_s_id),
	PRIMARY KEY(sc_id))";
$res = dbquery($sql);
if ($res) print "Successfully created <b>es_schedule_comment</b> table.<br>";

$supervisors = get_admins();
if (count($supervisors)==0) {
	print "<form method=\"post\">\n<input type=\"hidden\" name=\"action\" value=\"adduser\">";
	?>
	<script language="JavaScript">
	//-- the following function will display the fields that allows adminsitrators to enter passwords
	function show_password(type) {
		row1 = document.getElementById("passrow1");
		row2 = document.getElementById("passrow2");
		if (type.selectedIndex==2) {
			row1.style.display="block";
			row2.style.display="block";
		}
		else {
			row1.style.display="none";
			row2.style.display="none";
		}
	}
	</script>
<b><?php print $es_lang["default_user"]; ?></b>
	<table>
<tr><td align="right" class="text"><?php print $es_lang["username"];?></td><td><input type="text" name="u_netid" value="" maxlength="30"></td>
</tr>
<tr><td align="right" class="text"><?php print $es_lang["employee_type"];?></td><td><select name="u_type" onchange="show_password(this);">
	<option value="Admin"><?php print $es_lang["admin"];?></option>
	<option value="LDAP Admin"><?php print $es_lang["ldap_admin"];?></option>
</select>
</td></tr>
<?php
	$disp="block";
	print '<tr id="passrow1" style="display: '.$disp.'"><td align="right" class="text">'.$es_lang["password"].'</td><td><input type="password" name="pass1"></td></tr>';
	print '<tr id="passrow2" style="display: '.$disp.'"><td align="right" class="text">'.$es_lang["confirm_password"].'</td><td><input type="password" name="pass2"></td></tr>';
	print '<tr><td align="right" class="text">'.$es_lang["full_name"].'</td><td><input type="text" name="u_name" value=""></td></tr><tr><td colspan="3"><br></td></tr>';
	print '<tr><td></td><td><input type="submit" value="'.$es_lang["update"].'"></td></tr></table>';
	print "</form>\n";
}

} //-- end setupdb action

if ($action=="adduser") {
	$supervisors = get_supervisors();
	//-- only add the user if there are not other supervisors in the system
	if (count($supervisors)==0) {
		//-- create the sql statement
		$sql = "INSERT INTO es_user (u_netid, u_type, u_name";
		if (!empty($pass1)) $sql .= ", u_password";
		$sql .= ") VALUES ('".addslashes($u_netid)."', 
			'".addslashes($u_type)."', 
			'".addslashes($u_name)."'"; 
		if (!empty($pass1)) $sql .= ", '".addslashes(crypt($pass1))."'";
		$sql .= ")";
		$res = dbquery($sql);
		if ($res) print "<br>".$es_lang["update_successful"];
		print "<br /><br /><a href=\"index.php\">Click here to continue</a>\n";
	}
}

print_footer();

?>
