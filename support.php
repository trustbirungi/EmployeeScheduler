<?php
	include("es_functions.php");
	print_header("Help");
?>
	<br><br><b>Employee Schedules</b><br><br>

You can view or edit an employee's schedule by going to the <a href="es_sup_employees.php">Employee List</a> section and clicking on the "view schedule" to the right of the employee's name.<br><br>

This screen shows you the employee's schedule as they see it.  You can edit the schedule by clicking on the "Edit Schedule" button.


<br><br><b>My Information</b><br><br>

You can update your personal information by clicking on the <a href="es_sup_edit_info.php">My Information</a> link in the left hand menubar.  From this page you can change your name and contact

information.<br><br>



You may also change your picture.  To add a photo, click the "Browse" button next to the "Upload Picture" field.  This will provide you with a window where you can choose a photo on your computer to upload to the site.  

You can delete a picture by checking the "Delete Picture" box.  You may replace a picture by uploading a new one.<br><br>



From this page you can also assign yourself to show up as an employee of a supervisor.  This is helpful if you want to assign yourself to work on a position schedule.  You can do this by choosing your own name from the 

"Supervisors" list.<br><br>



<br><br><b>Editing Employees</b><br><br>

You can view all of the employees who are assigned to you by clicking on the <a href="es_sup_employees.php">Employees</a> link in the left hand menu bar.  To add a new employee select the "Add New Employee" link.

To edit an employee select the "edit" link to the right of the employee's name.  You can also delete an employee by selecting the "delete" link.  Deleting an employee will delete them from all supervisors lists.

You can reassign employees to other supervisors from the employee edit page.  The "view schedule" link will allow you to view and edit an employee's schedule.  Refer to the <a href="es_help.php?page=es_help_employee_schedule.php">Employee's Schedule Help Topic</a> for more information about using the "view schedule" link.<br><br>




<?php
	print_footer();

?>