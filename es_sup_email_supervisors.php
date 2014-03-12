<?php
/*********************************************************
	File: es_sup_email_supervisors.php
	Project: Employee Scheduler
	
	Comments:
		Allows an admin to spam their supervisors
		
	
**********************************************************/

require "es_functions.php";

$user = auth_admin();
print_header($es_lang["supervisors"]);

print '<br><br><span class="pagetitle">'.$es_lang["email_all_sup"].'</span><br><img src="images/bar_1.gif" width="75%" height="2">';

$employees = get_supervisors();

if (count($employees)==0) {
	print "<br><br><br>".$es_lang["no_emp"]."  <a href=\"es_sup_edit_employee.php\">".$es_lang["click_new_emp"]."</a>";
	print_footer();
	exit;
}

print "<br /><br />";

if (empty($action)) $action = "";

if ($action=="send") {
	$subject = $_POST["subject"];
	$message = $_POST["body"];
	foreach($employees as $employee) {
		if (!empty($employee["user_email"])) {
			if (!empty($user["user_email"])) {
				if ($ES_FULL_MAIL_TO) $headers = "From: ".$user["user_name"]." <".$user["user_email"].">\r\n";
				else $headers = "From: ".$user["user_email"]."\r\n";
			}
			if ($ES_FULL_MAIL_TO) $to = $employee["user_name"]." <".$employee["user_email"].">";
			else $to = $employee["user_email"];
			$g = mail($to, $subject, $message, $headers);
			if ($g) print $es_lang["send_successful"]."<b>".$to."</b><br />\n";
		}
	}
}
//-- print the email compose form
else {
	print $es_lang["email_emp_inst"]."<br /><br />\n";
	print "<form method=\"post\" action=\"es_sup_email_supervisors.php\">\n";
	print "<input type=\"hidden\" name=\"action\" value=\"send\">\n";
	print "<table>";
	print "<tr><td align=\"right\" class=\"text\">".$es_lang["subject"]."</td><td><input type=\"text\" size=\"50\" name=\"subject\"></td></tr>\n";
	print "<tr><td align=\"right\" class=\"text\">".$es_lang["body"]."</td><td><textarea rows=\"10\" cols=\"50\" name=\"body\"></textarea></td></tr>\n";
	print "</table>\n";
	print "<input type=\"submit\" value=\"".$es_lang["send"]."\">\n";
	print "</form>\n";
}

print_footer();
?>
