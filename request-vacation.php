<?php
include("es_functions.php");

$tdays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

print_header("Request Vacation");

?>
<h3>Enter details of your desired vacation period</h3>
<form>
	<p>
		Start Date: <input type = "text" />
		
	</p>

	<p>
		End Date: <input type = "text" />

	</p>

	<p>
		<input type = "submit" value = "Submit" />
	</p>


</form>

<h3>Your supervisor retains the right to accept or reject your requested vacation time.</h3>

<?php
print_footer();


print_employee_menu();
print "<td valign=\"top\" class=\"text\">";


?>