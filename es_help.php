<?php
/*********************************************************
	File: es_help.php
	Project: Employee Scheduler
	
	Incoming Variables:
		$page - the script they were coming from
	Comments:
		Help lookup file - this file attempts to provide meaningful
		help by using the $page to lookup the appropriate help file.
		If an appropriate help file can not be found then a list of the
		topics is displayed.
		
	
**********************************************************/

require "es_functions.php";
dbconnect();

$user = auth_user();

print_header($es_lang["help_page_title"]);
print '<br><br><span class="pagetitle">'.$es_lang["help"].'</span><br><img src="images/bar_1.gif" width="85%" height="2"><br><a href="es_help.php">'.$es_lang["help_topics"].'</a><br>';

//-- if the page is not empty then only get the script name without the directories
if (!empty($page)) {
	$pos1 = strrpos($page, "/");
	if ($pos1) $page = substr($page, $pos1+1);
}

$help_topic = array();
$help_page = array();
if (preg_match("/(Supervisor)|(Admin)/", $user["user_type"])>0) {
	$help_topic["es_sup_index.php"]["title"] = "Editing Areas and Positions";
	$help_topic["es_sup_position.php"]["title"] = "Position Schedules";
	$help_topic["es_sup_employees.php"]["title"] = "Editing Employees";
	$help_topic["es_sup_employee_schedule.php"]["title"] = "Employee Schedules";
	$help_topic["es_sup_edit_info.php"]["title"] = "My Information";
	$help_topic["es_emp_index.php"]["title"] = "My Schedule";
	$help_topic["es_sup_tutorial.php"]["title"] = "Supervisor Tutorial";
	
	$help_page["es_sup_index.php"] = "es_help_areas.html";
	$help_page["es_sup_edit_area.php"] = "es_help_areas.html";
	$help_page["es_sup_edit_position.php"] = "es_help_areas.html";
	$help_page["es_sup_position.php"] = "es_help_position_schedules.html";
	$help_page["es_sup_edit_position_schedule.php"] = "es_help_position_schedules.html";
	$help_page["es_sup_employees.php"] = "es_help_users.html";
	$help_page["es_sup_edit_employee.php"] = "es_help_users.html";
	$help_page["es_sup_employee_schedule.php"] = "es_help_employee_schedule.html";
	$help_page["es_sup_employee_past_schedule.php"] = "es_help_employee_past_schedule.html";
	$help_page["es_sup_edit_employee_schedule.php"] = "es_help_employee_schedule.html";
	$help_page["es_sup_edit_info.php"] = "es_help_sup_info.html";
	$help_page["es_emp_index.php"] = "es_help_sup_schedule.html";
	$help_page["es_sup_tutorial.php"] = "es_sup_tutorial.html";
}
else {
	include "es_emp_help.html";
	print_footer();
	exit;
}

if (isset($help_page[$page])) include $help_page[$page];
else {
	print "<br><br><b>Help is available for the following topics:</b>\n<ul>\n";
	foreach($help_topic as $page=>$topic) {
		print "\t<li><a href=\"es_help.php?page=$page\">".$topic["title"]."</a>\n";
	}
	print "</ul>\n";
	if (preg_match("/(Supervisor)|(Admin)/", $user["user_type"])>0) print "";
	else print "If your questions are not answered here, then contact your supervisor.<br><br>\n";
}

print_footer();
?>
