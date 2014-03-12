<?php
/*********************************************************
	File: es_sup_employees.php
	Project: Employee Scheduler
	
	Comments:
		Shows the supervisor a list of their employees
		
	
**********************************************************/

require "es_functions.php";

$user = auth_supervisor();
print_header($es_lang["employees"]);

print '<h3>'.$es_lang["employee"].' '.$es_lang["list"].'</h3>';

if ((!empty($delete_id))&&($delete_id!=$user["user_id"])) {
	delete_user($delete_id);
}	
$employees = get_supervisor_employees($user);


if (count($employees)==0) {
	print "<br><br><br>".$es_lang["no_emp"]."  <a href=\"es_sup_edit_employee.php\">".$es_lang["click_new_emp"]."</a>\n";
	print_footer();
	exit;
}

if ($view!="print") {
	print "<br><a href=\"es_sup_edit_employee.php\">".$es_lang["add_new_emp"]."</a> | ";
	print "<a href=\"es_sup_email_employees.php\">".$es_lang["email_emp"]."</a>\n";
}
print "<br>";

foreach($employees as $employee) {
	print '<br><table class = "feature-table dark gray"><tr><td width="50%"><span class="sectitle">';
	if (!empty($employee["user_picture"])) print '<img align="bottom" src="photos/'.$employee["user_picture"].'" height="100" style="border: solid '.$employee["user_color"].' 5px;" />';
	print $employee["user_name"].'</span></td><td width="50%" valign="bottom" align="right" class="text">';
	if ($view!="print") {
		print '<a href="es_sup_edit_employee.php?user_id='.$employee["user_id"].'">'.$es_lang["edit"].'</a> | ';
		if ($user["user_id"]!=$employee["user_id"]) print '<a href="es_sup_employees.php?delete_id='.$employee["user_id"].'" onclick="return confirm(\''.$es_lang["emp_confirm"].'\');">'.$es_lang["delete"].'</a> | ';
		print '<a href="es_sup_employee_schedule.php?user_id='.$employee["user_id"].'">'.'View Schedule </a> | ';
		print '<a href = "sup_edit_schedule.php?user_id='.$employee["user_id"].'">'.'Edit Schedule</a>';

	}
	print '</td></tr>';
	
	print '<tr><td colspan="2"></td></tr>';
	print "<tr><td><div class=\"text\" style=\"padding-left: 15px;\">";
	if (!empty($employee["user_netid"])) print $es_lang["username"]." ".$employee["user_netid"]."<br>\n";
	if (!empty($employee["user_major"])) print $es_lang["major"]." ".$employee["user_major"]."<br>\n";
	if (!empty($employee["user_workphone"])) print $es_lang["work_phone"]." ".$employee["user_workphone"]."<br>\n";
	if (!empty($employee["user_homephone"])) print $es_lang["home_phone"]." ".$employee["user_homephone"]."<br>\n";
	if (!empty($employee["user_email"])) print $es_lang["email"]." <a href=\"mailto:".$employee["user_email"]."\">".$employee["user_email"]."</a><br>\n";
	print "</div>\n</td>\n";
	print "<td class=\"text\">";
	$positions = get_user_positions($employee);
	$total_hours = 0;
	if (count($positions)>0) {
		print "<b>".$es_lang["position_assignments"]."</b><br>";
		foreach($positions as $key=>$value) {
			$total_hours += $value;
			$position = get_position($key);
			print $position["p_name"].": ".$value." ".$es_lang["hours"]."<br>\n";
		}
		print "<b>".$es_lang["total_scheduled"]." $total_hours ".$es_lang["hours"]."</b><br>\n";
	}
	print "</td>\n";
	print "</tr></table>";

}

print_footer();

		global $user;
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
