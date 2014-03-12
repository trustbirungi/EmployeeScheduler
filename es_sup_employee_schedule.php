<?php
/*********************************************************
	File: es_sup_employee_schedule.php
	Project: Employee Scheduler
	
	Comments:
		Shows the employee's schedule to supervisors.
		
	
**********************************************************/

require "es_functions.php";
dbconnect();

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
$user = auth_supervisor();
if (!isset($u_id)) $u_id = $user["user_id"];
$employee = get_user($u_id);

if ($gotoedit) {
	if (!empty($s_group) && !empty($s_exptime)) {
		header("Location: es_sup_edit_employee_schedule.php?user_id=$user_id&s_group=$s_group&s_exptime=$s_exptime&".session_name()."=".session_id());
		exit;
	}
}

if ($deleteschedule) {
	delete_user_schedule_group($s_group, $s_exptime, $employee);
	$s_group = "";
	$s_exptime = "";
}

$user_id = $_GET["user_id"];


$query = "SELECT user_name, shift_type, days_off FROM es_user WHERE user_id = '$user_id'";

	$result = mysql_query($query);

	$row = mysql_fetch_array($result);




print_header($es_lang["emp_schedule"]." - ".$row["user_name"]);

print '<br /><br /><span class="pagetitle">'.$es_lang["schedule"].' - '.$row["user_name"].'</span><br /><img src="images/bar_1.gif" width="75%" height="2">';


include("sup_shift.php");

print_footer();

print_supervisor_menu();
print "<td valign=\"top\" class=\"text\">";

?>
