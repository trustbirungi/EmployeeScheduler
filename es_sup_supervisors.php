<?php
/*********************************************************
	File: es_sup_employees.php
	Project: Employee Scheduler
	
	Comments:
		Shows the supervisor a list of their employees
		
	
**********************************************************/

require "es_functions.php";
dbconnect();

$user = auth_supervisor();

if ((!empty($su))&&(preg_match("/Admin/i", $_SESSION["u_type"])>0)) {
	$sql = "SELECT * FROM es_user WHERE user_id='".$su."'";
	$res = dbquery($sql);
	if (mysql_num_rows($res)>0) {
		$user = mysql_fetch_array($res);
		$user = db_cleanup($user);
		$_SESSION["es_username"] = $user["user_netid"];
	}
}

print_header($es_lang["supervisors"]);

print '<br><br><span class="pagetitle">'.$es_lang["supervisors"].' '.$es_lang["list"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';
if (preg_match("/Admin/i", $_SESSION["user_type"])>0) {
	if ($view!="print") {
		print "<br><a href=\"es_sup_edit_employee.php?user_type=Supervisor\">".$es_lang["new_supervisor"]."</a>";
		print " | <a href=\"es_sup_email_supervisors.php\">".$es_lang["email_all_sup"]."</a>\n";
	}
	if (!empty($delete_id)) {
		delete_user($delete_id);
	}
}

$employees = get_supervisors();
print "<br>";

foreach($employees as $employee) {
	print '<br><table width="85%" cellpadding="0" cellspacing="0"><tr><td><span class="sectitle">';
	if (!empty($employee["user_picture"])) print '<img align="bottom" src="photos/'.$employee["user_picture"].'" height="100"> ';
	print $employee["user_name"].'</span></td><td valign="bottom" align="right" class="text">';
	if ($view!="print") {
		if (preg_match("/Admin/i", $_SESSION["user_type"])>0) {
			print '<a href="es_sup_edit_employee.php?user_id='.$employee["user_id"].'">'.$es_lang["edit"].'</a> | ';
			print '<a href="es_sup_supervisors.php?su='.$employee["user_id"].'">Assume User</a> | ';
			if ($user["user_id"]!=$employee["user_id"]) print '<a href="es_sup_supervisors.php?delete_id='.$employee["user_id"].'" onclick="return confirm(\''.$es_lang["confirm_sup"].'\');">'.$es_lang["delete"].'</a>';
		}
	}
	print '</td></tr>';
	print '<tr><td colspan="2"><img src="images/bar_1.gif" width="100%" height="2"></td></tr>';
	print "<tr><td colspan=\"2\"><div class=\"text\" style=\"padding-left: 15px;\">";
	print "<table><tr><td valign=\"top\" class=\"text\">\n";
	if (preg_match("/Admin/i", $_SESSION["user_type"])>0) {
		if (!empty($employee["user_netid"]))print $es_lang["username"]." ".$employee["user_netid"]."<br>\n";
	}
	if (!empty($employee["user_major"])) print $es_lang["major"]." ".$employee["user_major"]."<br>\n";
	if (!empty($employee["user_workphone"]))print $es_lang["work_phone"]." ".$employee["user_workphone"]."<br>\n";
	if (!empty($employee["user_homephone"]))print $es_lang["home_phone"]." ".$employee["user_homephone"]."<br>\n";
	if (!empty($employee["user_email"]))print $es_lang["email"]." <a href=\"mailto:".$employee["user_email"]."\">".$employee["user_email"]."</a><br>\n";
	print "</td><td width=\"50\"><br></td><td valign=\"top\" class=\"text\">\n";
	$areas = get_supervisor_areas($employee);
	if (count($areas)>0) {
		print "<b>Area List:</b><br>";
		foreach($areas as $area) {
			print $area["a_name"]."<br>\n";
		}
	}
	print "</td></tr></table>\n";
	print "</div>\n</td></tr></table>";
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
