<?php
include("es_functions.php");
$user = auth_user();

$user_id = $user["user_id"];

print_header("Ask For Change In Schedule");

include("shift.php");
$tdays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");


echo "<br /><h3>Fill in your desired changes in schedule.</h3>";
echo "<br /><h3>Your supervisor retains the sole right to accept or reject your desired schedule change.</h3><br />";
echo "<h3>Free days cannot be more than 3.</h3>";

?>



<form action = "emp_change_schedule-exec.php" method = "POST">
	<p>Schedule Type
		<select name = "shift_type">
			<option value = "day">Day</option>
			<option value = "night">Night</option>
		</select>
	</p>

	<p>Free Days
		<select name = "days_off[]" multiple = "multiple">
			<?php 
				for($i = 0; $i < count($tdays); $i++) {
					echo "<option value = '".$i."'>".$tdays[$i]."</option>";
				}
			?>
		</select>

	</p>

	<input type = "hidden" name = "user_id" value = "<?php print $user_id ?>" />

	<p>
		<input type = "submit" value = "Request" />
	</p>


</form>





<?php
print_footer();

print_employee_menu();

?>