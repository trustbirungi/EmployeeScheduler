=====================================================================
	Employee Scheduler
	
	Version 2.1
	
	Author: John Finlay
	Copyright (C) 2003 Brigham Young University
	
	README Documentation
	
	This documentation and latest updates can be found at
	http://empscheduler.sourceforge.net/
	
	1. LICENSE
	2. INTRODUCTION
	3. WHAT'S NEW
	4. INSTALLATION
	5. UPGRADING
	6. SUPPORT
	7. CHANGES
	8. FILE STRUCTURE
	9. LANGUAGES
	
=====================================================================

LICENSE

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

See GPL.txt for full license information.

---------------------------------------------------------------------

INTRODUCTION

If you've ever tried to sit down and schedule part-time employees, you 
know it can be a hassle.  Especially if you have to work around student 
class schedules.  This project simplifies the process by putting everything 
online and allowing employees to login and enter their preferred weekly 
work schedule.  Supervisors can then schedule their employees to work in 
different positions by choosing from these hours.

It provides a simple, easy to use interface for scheduling hours through 
fancy JavaScript programming.  It includes options for LDAP authentication 
if desired or a standard MySQL authentication can be used.

It requires PHP 4.2 or higher and a MySQL database.

---------------------------------------------------------------------

WHAT'S NEW

Version 2.1 enhances the Scheduler by adding the German language translation
and a new user language field so that each user can set their preferred
language when they login.
- Locale based Time / Date Format support

Version 2.0 enhances the Employee Scheduler allowing scheduling to be done in 
1 hour, 1/2 hour, and 1/4 hour intervals.  It also enhances the user interface 
allowing you change the start and stop time of schedules.  Version 2.0 also 
improves security and performance.

Following is a detailed list of new features:
 - 1/2 hour scheduling option on every schedule
 - 15 min scheduling option on every schedule
 - Supervisor can set colors for their employees
 - Employees can create future schedules
 - Users can lookup past schedules
 - Users can change the start and end hours they want to see on the schedules
 - New site Administrator user level that is above the supervisor
 - Only Administrators can add new supervisors
 - Administrators can assume other supervisor users to edit their schedules
 - Administrators can set many configuration variables online
 - Supervisors can email all their employees
 - Employee hourly report
 - Position / Area hourly report

---------------------------------------------------------------------

INSTALLATION

To install this software, upload the files to a directory on your webserver.
Set write privileges for the "Photos" directory so that employee photos can
be uploaded to the site.

Edit the es_config.php file and enter the database connection variables you
are using.  The program assumes that a database has already been created
and that the given database user has select, insert, and update privileges
on that database.

To setup the database tables, point your browser to es_dbsetup.php.  After 
the tables are created you will be asked to create a Administrative user that 
you can use to login and create other supervisors and employees.

In order to allow users to upload photos, you may need to change the permissions
of the photos directory.  Under Linux, the Apache/PHP user will need to have
write permissions to this directory (chmod 775).  Depending on your server 
setup, you might have to grant write permissions to everyone (chmod 777).

You can customize the look and feel of the site by editing the es_style.css,
es_header.html, and es_footer.html files.

---------------------------------------------------------------------

UPGRADING

Use these in steps to upgrade from version 2.0 to version 2.1.
1. Upload all of the new files except es_config.php, es_header.html, es_style.css,
   and es_footer.html if you modified them since they have not changed in
   this release.
2. Point your browser to es_upgrade.php where you will be asked to login
   as a supervisor.  The es_upgrade.php file will updated the database tables
   and will upgrade your user to an administrator.
   
These instructions will help you to upgrade from version 1.x to 2.1.
1. Copy the values from your old es_config.php file and add them to the
   new es_config.php file you received in the version 2.1 package.
2. Upload all of the new files.  You do not need to upload es_header.html 
   and es_footer.html if you modified them since they have not changed in
   this release.
3. Point your browser to es_upgrade.php where you will be asked to login
   as a supervisor.  The es_upgrade.php file will updated the database tables
   and will upgrade your user to an administrator.
4. As an Administrator, you will see a new "Edit Settings" menu, where you can
   change many of the program settings and colors.

---------------------------------------------------------------------

SUPPORT

There is a lot of online help included in the project.  It includes 
instructions on how supervisors and employees use the program.

If you can't find the answers you are looking for in the online help,
then please use the project's sourceforge.net site.  
http://sourceforge.net/projects/empscheduler/

Post bugs to the BUGs sections, post feature requests to the RFE section,
post patches, updates, and language files to the Patches section, and 
post questions and support requests to the public forums.

---------------------------------------------------------------------

FILE STRUCTURE

/empscheduler						# Main package directory
   |__images/						# Directory where images are stored
   |__languages/					# Directory where language files are stored
   |__photos/						# Directory where employee photos will be uploaded
   |__es_about.php					# Tells about the project
   |__es_config.php					# Configuration file, edit this file for your site
   |__es_dbsetup.php				# Creates database tables
   |__es_emp_colleagues.php			# Shows an employee a list of colleagues who work the same positions they do
   |__es_emp_edit_info.php			# Allows an employee the ability to edit their contact information
   |__es_emp_edit_schedule.php		# Employees can edit their preferred work schedule
   |__es_emp_employee_schedule.php	# Show a lightweight version of another employees scheduler to the employee logged in
   |__es_emp_help.html				# Help file for employees
   |__es_emp_index.html				# Employees home page where they can view their schedule
   |__es_emp_past_schedules.php		# Employees can view past schedules
   |__es_emp_position_schedule.php	# Employees can view the schedule of one of their positions
   |__es_emp_positions.php			# Shows an employee a list of the positions they are scheduled to work
   |__es_emp_supervisors.php		# Shows an employee's supervisors
   |__es_footer.html				# Footer html file
   |__es_functions.php				# global functions for the project
   |__es_header.html				# customizable header file that appears at the top of every page
   |__es_help.php					# script for showing help files
   |__es_help_areas.html			# Help file for working with areas
   |__es_help_employee_schedule.html	# Help file for working with employees' schedules
   |__es_help_position_schedules.html	# Help file for working with position schedules
   |__es_help_sup_info.html			# Help file for editting supervisor information
   |__es_help_sup_schedule.html		# Help file for working with a supervisors schedule
   |__es_help_users.html			# Help file for working with users
   |__es_logout.php					# logout user from site
   |__es_style.css					# Cascading Stylesheet File
   |__es_sup_area_schedule.php		# Shows a supervisor a combined area schedule
   |__es_sup_edit_area.php			# Allows a supervisor to edit an area
   |__es_sup_edit_employee.php		# Allows a supervisor to edit employees
   |__es_sup_edit_employee_schedule.php # Edit an employee's schedule
   |__es_sup_edit_info.php			# Allows a supervisor to edit their contact info
   |__es_sup_edit_position.php		# Edit a position's info
   |__es_sup_edit_position_schedule.php # Edit a position's schedule and assign employees
   |__es_sup_employee_past_schedule.php	# View an employee's past schedules
   |__es_sup_employee_schedule.php	# View an employees schedules
   |__es_sup_employee_simple_Schedule.php # View a simplified employee schedule in a popup window
   |__es_sup_employees.php			# View a list of employees
   |__es_sup_index.php				# home page for supervisors, lists areas and positions
   |__es_sup_position.php			# view the schedule for a position
   |__es_sup_position_past.php		# view past schedules for a position
   |__es_sup_reports.php			# For supervisor reports (not implemented yet)
   |__es_sup_supervisors.php		# Show a list of supervisors
   |__es_sup_tutorial.html			# Introductory Supervisor Tutorial
   |__index.php						# Start page to login an user and determine if they are employees or supervisors
   |__pnindex.php					# PostNuke index module interface
   |__README.txt					# This file

---------------------------------------------------------------------

LANGUAGES

The Employee Scheduler currently only comes with English and Germand 
language files. If you would like to translate the file into another 
language, make a copy of the languages/es_lang.en.php file and translate 
the variables into your language.  Then edit the es_functions.php file 
and add your language to the $es_languages array.  The site defaults to 
the UTF-8 character encoding.

For example if you wanted to make a Japanese translation, you would copy
languages/es_lang.en.php to languages/es_lang.jp.php and translate the 
$es_lang array values to Japanese.  You would then add the following entry
to the $es_languages array
$es_language["japanese"]		="languages/es_lang.jp.php";

Submit language files to the "Patches" section of the sourceforge.net site
to have the files included in future releases of the project.

---------------------------------------------------------------------