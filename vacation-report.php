<?php
include("es_functions.php");
print_header("Reports");
$user = auth_user();

$query = "SELECT * FROM vacation_table";
$result = mysql_query($query);


echo "<h2>Vacation Report</h2>";

echo "<table class = 'feature-table dark-gray'>";
						echo "<thead>
							<tr> 
								<th>Name</th> 
								<th>Start Date</th>
								<th>End Date</th> 
								<th>Approving Supervisor</th>
								<th>Pending</th>
								<th>Declined</th>
								<th>Approved</th>
							</tr>
						</thead>";



						if(true) {
							while ($row = mysql_fetch_array($result)) {		
						echo "<tbody>
							<tr>";

							echo "<td>". $row['name']. "</td>";
							echo "<td>" . $row['start_date'] . "</td>";
							echo "<td>" . $row['end_date'] . "</td>";
							echo "<td>" . $row['supervisor'] . "</td>";

							if($row['approved'] == "false" && $row['declined'] == "false") {
								echo "<td><img src = './images/check.png' /></td>";
							}else {
								echo "<td></td>";
							}

							if($row['approved'] == "false" && $row['declined'] == "true") {
								echo "<td><img src = './images/check.png' /></td>";
							}else {
								echo "<td></td>";
							}

							if($row['approved'] == "true" && $row['declined'] == "false") {
								echo "<td><img src = './images/check.png' /></td>";
							}else {
								echo "<td></td>";
							}

							echo "</tr>
						</tbody>";

							}
						echo "</table>";
						}




echo "<p><b>Report generated on ";  
echo date("D M d, Y G:i a") ."</b></p>";

echo "<br />";
echo "<br />";

$qry = "SELECT approved FROM es_user WHERE user_id = {$user['user_id']}";
$res = mysql_query($qry);
while($rows = mysql_fetch_array($res)) {
	if($rows['approved'] == true) {
		echo "<h3>Your schedule change request has been approved.</h3>";
	}elseif($rows['approved'] == false && $user[user_type] != "Admin") {
		echo "<h3>Your schedule change request has not been approved.</h3>";
	}

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