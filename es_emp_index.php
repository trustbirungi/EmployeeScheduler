<?php
/*********************************************************
	File: es_emp_index.php
	Project: Employee Scheduler
	Comments:
		Default page for an employee.  Shows the employee's
		schedule.
		
**********************************************************/

require "es_functions.php";

global $user;


//-- set the default action view
if (empty($action)) $action="week";
if (!isset($gotoedit)) $gotoedit = false;
if (!isset($deleteschedule)) $deleteschedule = false;

if (isset($view_schedule)) {
	$temp = preg_split('/\|/', $view_schedule);
	if (count($temp)==2) {
		$s_group = $temp[0];
		$s_exptime = $temp[1];
	}
}
//-- only allow authenticated users to use this page
$user = auth_user();

if ($gotoedit) {
	if (!empty($s_group) && !empty($s_exptime)) {
		header("Location: es_emp_edit_schedule.php?s_group=$s_group&s_exptime=$s_exptime&".session_name()."=".session_id());
		exit;
	}
}

if ($deleteschedule) {
	delete_user_schedule_group($s_group, $s_exptime, $user);
	$s_group = "";
	$s_exptime = "";
}




print_header($es_lang["my_schedule"]);
print '<br><br><span class="pagetitle">'.$es_lang["my_schedule"].'</span><br><img src="images/bar_1.gif" width="85%" height="2">';


include("shift.php");

print_footer();


		if ($user) {
			print '<td width="20%" height="100%" valign="top">';
			if (preg_match("/(Supervisor)|(Admin)/i", $user["user_type"])>0) {
				print_supervisor_menu();
			}
			else {
				print_employee_menu();
			}
		}else {
	}
	print "<td valign=\"top\" class=\"text\">";
?>
