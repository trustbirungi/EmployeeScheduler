<?php
include("es_functions.php");

print_header("Registration Successful");

$user = auth_user();

echo "<h3>You have successfully registered the patient</h3><br />";
echo "<a href = './register_patients.php'>Register More Patients</a>";

for($i = 0; $i <5; $i++) {
	echo "<br />";

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


