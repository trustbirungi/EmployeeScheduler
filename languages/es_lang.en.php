<?php
/*********************************************************
	File: es_lang.en.php
	Project: Employee Scheduler
	Author: John Finlay
	Revision: $Revision: 1.17 $
	Date: $Date: 2004/12/02 18:16:59 $
	Comments:
		English Language file.
	
	Copyright (C) 2003  Brigham Young University

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
**********************************************************/

//-- the starting date of the week: 0 = Sunday
$WEEKSTART = 0;
$TIME_FORMAT = "g:i a";

$es_lang["hour"]		= "Hour";
$es_lang["min"]			= "Min";
$es_lang["preferred_language"]	= "Preferred Language";
$es_lang["first_hour"]	= "First Hour:";
$es_lang["last_hour"]	= "Last Hour:";
$es_lang["resolution"]	= "Resolution:";

//-- days of the week
$es_lang["sunday"]		= "Sunday";
$es_lang["monday"]		= "Monday";
$es_lang["tuesday"]		= "Tuesday";
$es_lang["wednesday"]	= "Wednesday";
$es_lang["thursday"]	= "Thursday";
$es_lang["friday"]		= "Friday";
$es_lang["saturday"]	= "Saturday";

$es_lang["weekly"]		= "Weekly";
$es_lang["weeks"]		= "Weeks";

//-- es_functions.php file messages
$es_lang["error_page_title"]	= "Employee Scheduler Error";
$es_lang["no_db_connect"]		= "Unable to connect to MySQL database on host $DBHOST with user $DBUSER";
$es_lang["no_db_select"]		= "Unable to select database $DBNAME on $DBHOST";
$es_lang["query_error"]			= "There was an error executing the following query:";
$es_lang["netid_not_exists"]	= "The Username does not exist.";
$es_lang["invalid_credentials"]	= "Username or Password are incorrect.";
$es_lang["no_ldap"]				= "Unable to connect to LDAP server";
$es_lang["no_employee"]			= "Username or password do not match a valid employee record.";
$es_lang["access_denied"]		= "Access Denied.  Only Supervisors may have access to the requested area.";
$es_lang["login_title"]			= "Employee Scheduler Login";
$es_lang["enter_username"]		= "Please enter your Username and Password to login";
$es_lang["username"]			= "Username:";
$es_lang["password"]			= "Password:";
$es_lang["login"]				= "Login";
$es_lang["help"]				= "Help";
$es_lang["printer_friendly"]	= "Printer Friendly";
$es_lang["logout"]				= "Logout";
$es_lang["welcome"]				= "Welcome ";
$es_lang["my_schedule"]			= "My Schedule";
$es_lang["my_past_schedule"]	= "My Past Schedules";
$es_lang["my_info"]				= "My Information";
$es_lang["my_sups"]				= "My Supervisors";
$es_lang["my_colleagues"]		= "My Colleagues";
$es_lang["my_positions"]		= "My Positions";
$es_lang["reports"]				= "Reports";
$es_lang["supervisors"]			= "Supervisors";
$es_lang["employees"]			= "Employees";
$es_lang["areas_and_positions"]	= "Areas &amp; Positions";
$es_lang["update_successful"]	= "Update Successful";
$es_lang["area_list"]			= "Area List";
$es_lang["default_user"]		= "Create a default supervisor user to login and begin using the system.";
$es_lang["edit_settings"]		= "Edit Settings";

//-- edit employee messages
$es_lang["edit_employee"]		= "Edit Employee";
$es_lang["information"]			= "Information";
$es_lang["password_mismatch"]	= "The passwords you entered do not match.";
$es_lang["username_exists"]		= "There is already a user with this Username.";
$es_lang["contact_users_sup"]	= "Please contact this user's supervisor, ";
$es_lang["have_assigned"]		= ", to have them also assigned to you.";
$es_lang["username_required"]	= "You must enter a Username.";
$es_lang["name_required"]		= "You must enter a Name.";
$es_lang["employee_type"]		= "Employee Type:";
$es_lang["employee"]			= "Employee";
$es_lang["ldap_employee"]		= "LDAP Employee";
$es_lang["supervisor"]			= "Supervisor";
$es_lang["ldap_supervisor"]		= "LDAP Supervisor";
$es_lang["admin"]				= "Administrator";
$es_lang["ldap_admin"]			= "LDAP Administrator";
$es_lang["confirm_password"]	= "Confirm Password";
$es_lang["full_name"]			= "Full Name:";
$es_lang["major"]				= "Major:";
$es_lang["work_phone"]			= "Work Phone:";
$es_lang["home_phone"]			= "Home Phone:";
$es_lang["location"]			= "Location:";
$es_lang["email"]				= "Email:";
$es_lang["minimum_hours"]		= "Minimum Required Hours:";
$es_lang["maximum_hours"]		= "Maximum Required Hours:";
$es_lang["desired_hours"]		= "Desired Hours:";
$es_lang["notes"]				= "Notes:";
$es_lang["sup_notes"]			= "Supervisor Notes:";
$es_lang["upload_picture"]		= "Upload Picture:";
$es_lang["update"]				= "Update";
$es_lang["delete_pic"]			= "Delete Picture";
$es_lang["user_color"]			= "User Color";

//-- colleagues messages
$es_lang["my_colleagues"]		= "My Colleagues";
$es_lang["no_colleagues"]		= "There are currently no other employees assigned to work with you.";
$es_lang["view_schedule"]		= "view schedule";
$es_lang["position_assignments"]	= "Position Assignments";

//-- My Information messages
$es_lang["my_info"] 			= "My Information";

//-- Edit schedule messages
$es_lang["email_subject"]		= "Schedule Updated";
$es_lang["hello"]				= "Hello";
$es_lang["email_msg1"]			= "A schedule has been updated for one of your employees, ";
$es_lang["email_msg2"]			= "\nYou may view the schedule by clicking the following link and logging in:";
$es_lang["email_msg3"]			= "If you have any questions about this new schedule you should contact";
$es_lang["edit_my_schedule"]	= "Edit My Schedule";
$es_lang["confirm_comment"]		= "Are you sure you want to delete this comment?";
$es_lang["confirm_course"]		= "Are you sure you want to delete this course?";
$es_lang["edit_instructions"]	= "Edit your schedule below.  All time slots default to \"Unavailable\".  Mark the times you are available to work by selecting a preference level and then clicking a box in the schedule.  A preference level of 3 means you really want to work at that time. A preference level of 1 means you are available to work that time if necessary. <br /><br />If you have marked a time with a preference level, and would like to make that time slot unavailable again, then select the \"Unavailable\" option and click on the desired time slot.<br /><br /> Your supervisor may want to you to include your class shedule.  You may enter your classes by selecting the \"Course\" color and clicking on a box in the schedule and filling in the required information.  To remove a course from your schedule, select the remove link next to that schedule.<br /><br />You may also make a comment for your supervisor about a particular hour, by selecting the \"Comment\" color and clicking on a box in the schedule.  To remove a comment, select the remove link next to that comment.<br /><br />When your supervisor schedules you to work at a time slot, that slot will become white with the name of the position you are scheduled to work.  You must contact your supervisor to make changes to a time slot for which you have already been scheduled to work.";
$es_lang["edit_schedule"]	= "Edit Schedule";
$es_lang["course"]			= "Course";
$es_lang["unavailable"]		= "Unavailable";
$es_lang["pref1"]			= "Preference Level 1 (lowest)";
$es_lang["pref2"]			= "Preference Level 2";
$es_lang["pref3"]			= "Preference Level 3 (highest)";
$es_lang["comment"]			= "Comment";
$es_lang["min_left"]		= "Hours left to reach minimum:";
$es_lang["max_left"]		= "Hours left to reach maximum:";
$es_lang["total_hours"]		= "Total Hours Available:";
$es_lang["hours_desired"]	= "Total Hours Desired:";
$es_lang["total_scheduled"]	= "Total Hours Scheduled:";
$es_lang["view24"]			= "View 24 hours";
$es_lang["view18"]			= "View 18 hours";
$es_lang["hours"]			= "hours";
$es_lang["sun"]				= "SUN";
$es_lang["mon"]				= "MON";
$es_lang["tue"]				= "TUE";
$es_lang["wed"]				= "WED";
$es_lang["thu"]				= "THU";
$es_lang["fri"]				= "FRI";
$es_lang["sat"]				= "SAT";
$es_lang["finished"]		= "Finished";
$es_lang["building"]		= "Building:";
$es_lang["fill"]			= "Fill";
$es_lang["email_sup"]		= "Email my supervisors informing them of my updated schedule?";
$es_lang["save_schedule"]	= "Save Schedule";

//-- view schedule messages
$es_lang["schedule"]		= "Schedule";
$es_lang["emp_schedule"]	= "Employee Schedule";
$es_lang["not_created"]		= "has not yet created a schedule.";
$es_lang["have_note_created"]	= "You have not yet created a schedule.";
$es_lang["click_to_create"]	= "Click here to create a new one.";
$es_lang["to_help"]			= "To help you get started, you should review the";
$es_lang["tutorial"]		= "Tutorial";
$es_lang["list"]			= "List";
$es_lang["hide_weekends"]	= "Hide Weekends";
$es_lang["show_weekends"]	= "Show Weekends";
$es_lang["week"]			= "Week";
$es_lang["pref_level"]		= "Preference Level";
$es_lang["past_schedules"]	= "Past schedules";

//-- position schedule
$es_lang["no_pos_schedule"]	= "You have not yet created a schedule for this position.";
$es_lang["select_schedule"]	= "Select another schedule below:";
$es_lang["view"]			= "View";
$es_lang["not_scheduled"]	= "You are not currently scheduled to work at any positions.  When your supervisor schedules you to work in positions, those positions will appear in this list.";

//-- help page
$es_lang["help_page_title"]	= "Employee Scheduler Help";
$es_lang["help_topics"]		= "Help Topics";

//-- areas
$es_lang["edit_area"]		= "Edit Area";
$es_lang["area_name"]		= "Area Name:";
$es_lang["description"]		= "Description:";
$es_lang["area_sups"]		= "Supervisors Assigned to Area:";
$es_lang["save"]			= "Save";
$es_lang["no_areas"]		= "You are not assigned to any areas.";
$es_lang["click_new_area"]	= "Click here to create a new area.";
$es_lang["sup_instructions"]	= "To help you get started, you might also want to run through the <a href=\"es_help.php?page=es_help_sup_tutorial.php\" target=\"help\">Supervisor Tutorial</a> which will guide you through the supervisor functions of the <b>Employee Scheduler</b><br /><br />\n";
$es_lang["add_area"]		= "Add New Area";
$es_lang["area_confirm"]	= "Are you sure you want to delete this area?  Deleting and area will delete all positions and their schedules for this area.  It will also delete this area from all supervisors assigned to this area.";
$es_lang["add_position"]	= "add position";
$es_lang["pos_confirm"]		= "Are you sure you want to delete this position?";

//-- edit employee schedule
$es_lang["hello"]			= "Hello";
$es_lang["email_msg4"]		= "has updated your schedule.\nYou may view the schedule by clicking the following link and logging in:";
$es_lang["email_msg5"]		= "If you have any questions about this new schedule you should contact";
$es_lang["schedule_update"]	= "Schedule Update";
$es_lang["assign_to_pos"]	= "Assign to Position";
$es_lang["no_pos_schedules"]	= "<b>You have not created any schedules.</b>  <br />First go to <a href=\"es_sup_index.php\">Areas and Positions</a> and create a schedule for your positions.<br />Then you can return and assign employees to position schedules.<br />\n";
$es_lang["show_on_schedule"]	= "Show this schedule on the employee's schedule?";
$es_lang["change_pos_sched"]	= "Change Position Schedule";
$es_lang["email_employee"]	= "Email this employee informing him/her of an updated schedule?";
$es_lang["schedule_conflict"]	= "The times you have entered for this schedule conflict with another schedule.  You may not have schedules that overlap in time.";

//-- edit position
$es_lang["edit_position"]	= "Edit Position";
$es_lang["pos_name"]		= "Position Name:";
$es_lang["area"]			= "Area:";

//-- edit position schedule
$es_lang["new_name"]		= "This position already has a schedule set by that name.  Please enter a new name for this schedule.";
$es_lang["save_sucess"]		= "Schedule Saved Successfully";
$es_lang["email_msg6"]		= "A new schedule has been created for the position ";
$es_lang["email_msg7"]		= "You may view the schedule by clicking the following link and logging in:";
$es_lang["email_success"]	= "Emails sent successfully to";
$es_lang["need_name"]		= "You must enter a name for this schedule.";
$es_lang["no_hyphen"]		= "Please do not use hyphens '-' in your schedule name.";
$es_lang["max_reached"]		= "This employee has reached their maximum allowed hours.";
$es_lang["schedule_name"]	= "Schedule Name:";
$es_lang["repeat_interval"]	= "Repeat Interval:";
$es_lang["start_date"]		= "Start Date:";
$es_lang["end_date"]		= "End Date:";
$es_lang["email_employees"]	= "Email these employees informing them of an updated schedule?";
$es_lang["no_schedule"]		= "You have not yet created a schedule for this position.";
$es_lang["confirm_schedule"]	= "Are you sure you want to delete the selected schedule?";
$es_lang["select_schedule"]	= "Select another schedule below:";

//-- employee list
$es_lang["no_emp"]			= "There are currently no employees assigned to you.";
$es_lang["click_new_emp"]	= "Click here to add a new employee.";
$es_lang["add_new_emp"]		= "Add New Employee";
$es_lang["edit"]			= "Edit";
$es_lang["delete"]			= "Delete";
$es_lang["new"]				= "New";
$es_lang["emp_confirm"]		= "Are you sure you want to delete this employee?";
$es_lang["email_emp"]		= "Email All Employees";
$es_lang["email_all_sup"]           = "Email All Supervisors";

//-- email employees
$es_lang["email_emp_inst"]	= "Use this form to send an email to all of your employees.  Please fill in the text for the subject and the body of the message and then click the send button.";
$es_lang["subject"]			= "Subject";
$es_lang["body"]			= "Message Body";
$es_lang["send"]			= "Send Message";
$es_lang["send_successful"]	= "Successfully sent message to ";

//-- supervisor list
$es_lang["new_supervisor"]	= "Add New Supervisor";
$es_lang["confirm_sup"]		= "Are you sure you want to delete this supervisor?";

//-- admin
$es_lang["settings_saved"]	= "Settings Saved.";
$es_lang["se_SITE_URL"] 	= "Site URL";
$es_lang["se_SESSION_COOKIE_TIMEOUT"]	= "Session Timeout";
$es_lang["se_CHARACTER_SET"]	= "Character Set";
$es_lang["se_SITE_ADMIN_EMAIL"]	= "Site Administrator Email";
$es_lang["se_COMPANY_URL"]		= "Company URL";
$es_lang["se_COMPANY_NAME"]		= "Company Name";
$es_lang["se_START_HOUR"]		= "Earliest start hour";
$es_lang["se_END_HOUR"]			= "Latest end hour";
$es_lang["se_DEFAULT_TIME_BLOCKS"]	= "Default time resolution";
$es_lang["se_ES_SHOW_STATS"]	= "Show Execution Statistics";
$es_lang["yes"]					= "Yes";
$es_lang["no"]					= "No";
$es_lang["se_ES_FULL_MAIL_TO"]	= "Use full names in mail headers";

//-- reports
$es_lang["emp_report"]			= "Employees Hourly Report";
$es_lang["emp_report_descr"]	= "This report allows you to see how many hours employees were scheduled to work by position within a time period.";
$es_lang["pos_report"]			= "Area Position Hourly Report";
$es_lang["pos_report_descr"]	= "This report allows you to see how many total hours were scheduled for your areas and positions within a time period.";
$es_lang["view_report"]			= "View Report";
$es_lang["select_report"]		= "Select a report to run below:";
$es_lang["position"]			= "Position";
$es_lang["total_for_area"]		= "Total hours for area";
$es_lang["total"]				= "Total";

?>
