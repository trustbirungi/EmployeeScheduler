<?php
include("es_functions.php");

print_header("Edit Schedule");	

$tdays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

$user_id = $_GET["user_id"];

?>

<?php include("sup_shift.php");?>


<br />

<form action = "sup_edit_schedule-exec.php" method = "POST" >
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
		<input type = "submit" value = "Change" />
	</p>

</form>


<?php
	print_footer();

	print_supervisor_menu();
	print "<td valign=\"top\" class=\"text\">";

?>