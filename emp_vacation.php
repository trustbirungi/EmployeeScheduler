<?php
include("es_functions.php");

print_header("Ask For A Vacation");
$user = auth_user();

?>

<h3>Fill in the details of your desired vacation.</h3><br />
<h3>Your supervisor retains the right to accept or reject your request for a vacation</h3>

<br />

<form action = "emp_vacation-exec.php" method = "POST">
			<p class = "labell">
					<label for 'month'>Start Date</label>
					<?php
					
					//File is a PHP function for reading a file, line by line. It then returns the read data as an array which is stored in "$months"
					$months = file("MONTH.txt") ;
						echo("<select id = 'selMonth' name = 'start_month'>");
						
						//Loop through the array returned from reading the file and then use its elements as options for the drop down list
					foreach ($months as $month) 
					{
						echo ("<option class = 'optionn' value = '$month'>$month</option>") ;
					}
						echo ("</select>") ;
					?>
						
					
					<?php
					//File is a PHP function for reading a file, line by line. It then returns the read data as an array which is stored in "$days"
					$days = file("DAY.txt");
						echo ("<select id = 'selDay' name = 'start_day'>");
						
						//Loop through the array returned from reading the file and then use its elements as options for the drop down list

					foreach ($days as $day)
					{
						echo ("<option class = 'optionn' value = '$day'>$day</option>") ;
					} 
						echo ("</select>") ;
					?>
					
					<select id = "year" name = "start_year">
					<option class = "optionn" value = "YEAR">YEAR</option>
					<?php
						//date("Y") is a PHP function for returning the current year. Assign the value returned by the function to "$i" and then use it as a loop controller for the loop creating those years as options for the years drop down list
						$i = date("Y");
							echo "<option class = 'optionn' value = '$i'>$i</option>";
						
					?>
					
					</select>
					
				 </p>


				 <p class = "labell">
					<label for 'month'>End Date</label>
					<?php
					
					//File is a PHP function for reading a file, line by line. It then returns the read data as an array which is stored in "$months"
					$months = file("MONTH.txt") ;
						echo("<select id = 'selMonth' name = 'end_month'>");
						
						//Loop through the array returned from reading the file and then use its elements as options for the drop down list
					foreach ($months as $month) 
					{
						echo ("<option class = 'optionn' value = '$month'>$month</option>") ;
					}
						echo ("</select>") ;
					?>
						
					
					<?php
					//File is a PHP function for reading a file, line by line. It then returns the read data as an array which is stored in "$days"
					$days = file("DAY.txt");
						echo ("<select id = 'selDay' name = 'end_day'>");
						
						//Loop through the array returned from reading the file and then use its elements as options for the drop down list

					foreach ($days as $day)
					{
						echo ("<option class = 'optionn' value = '$day'>$day</option>") ;
					} 
						echo ("</select>") ;
					?>
					
					<select id = "year" name = "end_year">
					<option class = "optionn" value = "YEAR">YEAR</option>
					<?php
						//date("Y") is a PHP function for returning the current year. Assign the value returned by the function to "$i" and then use it as a loop controller for the loop creating those years as options for the years drop down list
						$i = date("Y");
							echo "<option class = 'optionn' value = '$i'>$i</option>";
						
					?>
					
					</select>
					
				 </p>


				 <!-- This is JavaScript that enables the submit button image to actually submit data -->
		<script>
			$(document).ready(function () {
			$("#submit").click(function () {
				$("loginForm")[0].reset();
				});
			});
			</script>
	
			<p class="submit">
			<input type="image" value="" src = "images/submit.png" class = "reg_button" />
			</p>

</form>







<?php
print_footer();

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