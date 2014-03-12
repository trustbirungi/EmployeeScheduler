<?php
include("es_functions.php");

$tdays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

print_header("Change Schedule");

?>
<h3>Enter details of your desired schedule</h3>
<form>
	<p>
		Schedule Type: 

		<select>
			<option>Day</option>
			<option>Night</option>
		</select>
		
	</p>

	<p>
		Working Days / Nights:

		<select multiple = "multiple">
			<?php 
				for($i = 0; $i < count($tdays); $i++) {
					echo "<option value =" .'$i'. ">". $tdays[$i]. "</option>";
				}
			?>
		</select>

	</p>

	<p>
		<input type = "submit" value = "Submit" />
	</p>


</form>

<h3>Your supervisor retains the right to accept or reject your requested schedule change.</h3>

<?php
print_footer();


print_employee_menu();
print "<td valign=\"top\" class=\"text\">";


?>