<?php
/*********************************************************
	File: es_emp_employees.php
	Project: Employee Scheduler
	
	Comments:
		Shows the employee a list of their supervisors
		
	
**********************************************************/

require "es_functions.php";
dbconnect();

$user = auth_user();
print_header($es_lang["my_sups"]);

print '<br><br><span class="pagetitle">'.$es_lang["my_sups"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';

$employees = get_employee_supervisors($user);

print "<br>";

foreach($employees as $employee) {
	print '<br><table width="85%" cellpadding="0" cellspacing="0"><tr><td><span class="sectitle">';
	if (!empty($employee["user_picture"])) print '<img align="bottom" src="photos/'.$employee["user_picture"].'" height="100"> ';
	print $employee["user_name"].'</span></td><td valign="bottom" align="right" class="text">';
	print '</td></tr>';
	print '<tr><td colspan="2"><img src="images/bar_1.gif" width="100%" height="2"></td></tr>';
	print "<tr><td colspan=\"2\"><div class=\"text\" style=\"padding-left: 15px;\">";
	print "<table><tr><td valign=\"top\" class=\"text\">\n";
	if (!empty($employee["user_major"])) print $es_lang["major"]." ".$employee["user_major"]."<br>\n";
	if (!empty($employee["user_workphone"]))print $es_lang["work_phone"]." ".$employee["user_workphone"]."<br>\n";
	if (!empty($employee["user_homephone"]))print $es_lang["home_phone"]." ".$employee["user_homephone"]."<br>\n";
	if (!empty($employee["user_email"]))print $es_lang["email"]." <a href=\"mailto:".$employee["user_email"]."\">".$employee["user_email"]."</a><br>\n";
	print "</td><td width=\"50\"><br></td><td valign=\"top\" class=\"text\">\n";
	$areas = get_supervisor_areas($employee);
	if (count($areas)>0) {
		print "<b>".$es_lang["area_list"].":</b><br>";
		foreach($areas as $area) {
			print $area["a_name"]."<br>\n";
		}
	}
	print "</td></tr></table>\n";
	print "</div>\n</td></tr></table>";
}

print_footer();
?>