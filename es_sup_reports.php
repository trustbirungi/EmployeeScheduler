<?php
/*********************************************************
	File: es_sup_reports.php
	Project: Employee Scheduler
	
	Comments:
		Supervisor reporting features
		
	
**********************************************************/

require "es_functions.php";
dbconnect();

$user = auth_supervisor();
print_header($es_lang["reports"]);

print '<br /><br /><span class="pagetitle">'.$es_lang["reports"].'</span><br /><img src="images/bar_1.gif" width="85%" height="2">';

print "<br /><br /><b>".$es_lang["select_report"]."</b><br /><br />";
print "<ul style=\"width: 70%;\">\n";
print "<li><a href=\"es_sup_report_employees.php\">".$es_lang["emp_report"]."</a> - ".$es_lang["emp_report_descr"]."<br /><br /></li>\n";
print "<li><a href=\"es_sup_report_positions.php\">".$es_lang["pos_report"]."</a> - ".$es_lang["pos_report_descr"]."<br /><br /></li>\n";
print "</ul><br />\n";

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
