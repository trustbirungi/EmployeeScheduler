<?php
	include("es_functions.php");
	$user = auth_user();

	$patient_id = $_GET['id'];

	print_header("Update Patient Info");


	$query = "SELECT * FROM patients WHERE patient_id = '{$patient_id}'";
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		?>
			<form action = "update_patients-exec.php" method = "POST">
				<p class = "labell">
					<label>First Name</label>
					<input type = "text" id = "fname" name = "fname" value = "<?php echo $row['firstname'];?>" />
				</p>

				<p class = "labell">
					<label>Last Name</label>
					<input type = "text" id = "lname" name = "lname" value = "<?php echo $row['lastname'];?>" />
				</p>
				

				<p class = "labell">
				   <label>E-mail Address</label>
				   <input type = "email" id = "email" name = "email" value = "<?php echo $row['email'];?>" />
				</p>

				<p class = "labell">
					<label for = 'Phone Number'>Phone Number</label>
					<input type = "text" id = "phone" name = "phone" value = "<?php echo $row['phone'];?>" />
				</p>
				
				<p class = "labell">
					<label for = 'Male'>Male</label>
					<input type = "radio" 
							 name = "sex"
							 value = "Male"
							 <?php if($row['sex'] == "Male") { echo "checked = 'checked'"; }?>
							 />
				</p>
				<p class = "labell">			 
				   <label for = 'Female'>Female</label>
				   <input type = "radio" 
							 name = "sex"
							 value = "Female"
							 <?php if($row['sex'] == "Female") { echo "checked = 'checked'"; }?>
							 />
				
				</p>
					
				<p class = "labell">
					<label for 'month'>Date Of Birth</label>
					<?php
					
					//File is a PHP function for reading a file, line by line. It then returns the read data as an array which is stored in "$months"
					//$months = file("MONTH.txt") ;
					$months = array("MONTH", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

						echo("<select id = 'selMonth' name = 'month'>");
						
						//Loop through the array returned from reading the file and then use its elements as options for the drop down list

						

						

					foreach ($months as $month) 
					{
						$mo = $row["birthday"];

						$mon = substr($mo, 3, -4);
						$mon = trim($mon);
						

						if(strcmp($month, $mon) == 0) {
							echo ("<option class = 'optionn' value = '$month' selected = 'selected'>$month</option>");
						}else {
						echo ("<option class = 'optionn' value = '$month'>$month</option>") ;
						}
					}
						echo ("</select>") ;
					?>
						
					
					<?php
					//File is a PHP function for reading a file, line by line. It then returns the read data as an array which is stored in "$days"
					$days = file("DAY.txt");
						echo ("<select id = 'selDay' name = 'day'>");
						
						//Loop through the array returned from reading the file and then use its elements as options for the drop down list

					foreach ($days as $day)
					{
						$sel = substr($row['birthday'], 0, 2);
						$sel = $sel + 0;

						if($day == $sel) {
							echo ("<option class = 'optionn' value = '$day' selected = 'selected'>$day</option>") ;
							continue;
						}else {
						echo ("<option class = 'optionn' value = '$day'>$day</option>") ;
						}
					} 
						echo ("</select>") ;
					?>
					
					<select id = "year" name = "year">
					<option class = "optionn" value = "YEAR">YEAR</option>
					<?php
						//date("Y") is a PHP function for returning the current year. Assign the value returned by the function to "$i" and then use it as a loop controller for the loop creating those years as options for the years drop down list
					$yea = substr($row['birthday'], -4);
					$yea = $yea + 0;

						for ($i = date("Y"); $i > 1900; $i--){
							if($i == $yea) {
								echo "<option class = 'optionn' value = '$i' selected = 'selected'>$i</option>";
							}
							echo "<option class = 'optionn' value = '$i'>$i</option>";
						}
					?>
					
					</select>
					
				 </p>
				 

				 <p class = "labell">
				 	<label for = 'address'>Address</label>
				 	<input type = "text" name = "address" value = "<?php echo $row['address'];?>" />
				 </p>

				 <p class = "labell">
				 	<label for = 'symptoms'>Symptoms</label>
				 	<textarea name = "symptoms" rows = "5" columns = "50" maxlength = "10000">
				 		<?php echo $row['symptoms'];?>
				 	</textarea>
				 </p>

				 <p class = "labell">
				 	<label for = 'lab_results'>Lab Results</label>
				 	<textarea name = "lab_results" rows = "5" columns = "50" maxlength = "10000">
				 		<?php echo $row['lab_results'];?>
				 	</textarea>
				 </p>

				 <p class = "labell">
				 	<label for = 'diagnosis'>Diagnosis</label>
				 	<textarea name = "diagnosis" rows = "5" columns = "50" maxlength = "10000" >
				 		<?php echo $row['diagnosis'];?>
				 	</textarea>
				 </p>


				 <p class = "labell">
				 	<label for = 'doctor'>Doctor</label>
				 	<input type = "text" name = "doctor" value = "<?php echo $row['doctor'];?>" />
				 </p>

				 <p class = "labell">
				 	<label for = 'nurse'>Nurse</label>
				 	<input type = "text" name = "nurse" value = "<?php echo $row['nurse'];?>" />
				 </p>

				 <p class = "labell">
				 	<label for = 'allergies'>Allergies</label>
				 	<textarea name = "allergies" rows = "5" columns = "50" maxlength = "100000">
				 		<?php echo $row['allergies'];?>
				 	</textarea>
				 </p>

				 <p class = "labell">
				 	<label for = 'Notes'>Notes</label>
				 	<textarea name = "notes" rows = "5" columns = "50" maxlength = "10000">
				 		<?php echo $row['notes'];?>
				 	</textarea>
				 </p>

				 <input type = "hidden" name = "patient_id" value = "<?php echo $patient_id?>" />


				 
				<p class = "labell">
				   <label for = 'Reset'>Reset</label>
				   <input type = "reset" id = "reset" name = "reset" />
				 </p>

		</fieldset>
		
		<!-- This is JavaScript that enables the submit button image to actually submit data -->
		<script>
			$(document).ready(function () {
			$("#submit").click(function () {
				$("loginForm")[0].reset();
				});
			});
			</script>
	
			<p class="submit">
			<input type="image" value="" src = "images/submit.png" class = "reg_button" name = "submit">
			</p>

			</form>

	<?php
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