<?php

include("es_functions.php");

$user = auth_user();
print_header_patients("View Patients  By Month");
	
	$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

	$month_values = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

?>
<h3>Select the month for which you would like to view patients.</h3>

	<form action = "" method = "POST">
		<p>Month
			<select name = "month">
				<?php for ($i = 0; $i < count($months); $i++) 
						echo "<option value = '".$month_values[$i]."'> ".$months[$i]." </option> ";
				?>
			</select>
		</p>

		<p>Year
			<select name = "year">
				<?php for($j = date("Y"); $j > 1899; $j--){
					echo "<option value = '".$j."'> ".$j."</option>";

					}

				?>
			</select>
		</p>

		<p>
			<input type = "submit" value = "Submit" />
		</p>

	</form>


<?php


	$month = $_POST["month"];
	$year = $_POST["year"];
	$actual_month = $year."-".$month;


	$query = "SELECT * FROM patients WHERE register_date LIKE '{$actual_month}%' ";

	$result = mysql_query($query);
	if($result && mysql_num_rows($result) > 0) {
		

						echo "<table class = 'feature-table dark-gray'>";
						echo "<thead>
							<tr> 
								<th>Name</th> 
								<th>Phone Number</th>
								<th>Gender</th>
								<th>DOB</th>
								<th>Register Date</th>
								<th>Address</th>
								<th>Symptoms</th>
								<th>Lab Results</th>
								<th>Diagnosis</th>
								<th>Doctor</th>
								<th>Nurse</th>
								<th>Allergies</th>
								<th>Notes</th>
								<th>Update</th>
							</tr>
						</thead>";


					 while ($row = mysql_fetch_array($result)) {
						
								
						echo "<tbody>
							<tr>";

							echo "<td>". $row['firstname']." ". $row['lastname']. "</td>";
							echo "<td>" . $row['phone'] . "</td>";
							echo "<td>" . $row['sex'] . "</td>";
							echo "<td>" . $row['birthday'] . "</td>";
							echo "<td>" . $row['register_date']. " | " . $row['register_time'] . "</td>";
							echo "<td>" . $row['address'] . "</td>";
							echo "<td>" . $row['symptoms'] . "</td>";
							echo "<td>" . $row['lab_results'] . "</td>";
							echo "<td>" . $row['diagnosis'] . "</td>";
							echo "<td>" . $row['doctor'] . "</td>";
							echo "<td>" . $row['nurse'] . "</td>";
							echo "<td>" . $row['allergies'] . "</td>";
							echo "<td>" . $row['notes'] . "</td>";
							echo "<td><a href = 'update_patients.php?id=".$row['patient_id']."'>Update</a></td>";

							echo "</tr>
						</tbody>";

							}
						echo "</table>";
						}else {
							echo "<h3>No patients were found for that month. Please choose another month</h3>";
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