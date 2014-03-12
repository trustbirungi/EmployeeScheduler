<?php

include("es_functions.php");
$user = auth_supervisor();


print_header("View Vacation Requests");

$query = "SELECT * FROM vacation_table WHERE approved = 'false' AND declined = 'false'";
$result = mysql_query($query);

$count=mysql_num_rows($result);

?>

<!--<table class = 'feature-table dark-gray'>-->
<table class = 'feature-table dark-gray'>
<td><form name="form1" method="POST" action="">

						<thead>
							<tr> 
								<th>User ID</th>
								<th>Name</th> 
								<th>Start Date</th>
								<th>End Date</th> 
								<th>Supervisor</th>
								<th>Approve</th> 
								<th>Decline</th>								
							</tr>
						</thead>

<?php
while($rows=mysql_fetch_array($result)){
?>

<tr>

<td><? echo $rows['user_id']; ?></td>
<td><? echo $rows['name']; ?></td>
<td><? echo $rows['start_date']; ?></td>
<td><? echo $rows['end_date']; ?></td>
<td><? echo $rows['supervisor']; ?></td>

<td><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['user_id']; ?>"></td>

<td><input name="decline[]" type="checkbox" id="decline[]" value="<?php echo $rows['user_id']; ?>"></td>
</tr>

<?php
}
?>

<tr>
<td colspan="5" align="center"><input name="approve" type="submit" id="approve" value="Submit"></td>
</tr>

<?php
// Check if approve button active, start this 
if($approve) {

for ($i=0; $i < $count; $i++) {
$user_id = $checkbox[$i];
$new_shift_type = $shift_types[$i];
$new_days_off = $days_off[$i];

echo $del_id."<br />";
$sql = "UPDATE vacation_table SET approved = 'true' WHERE user_id = '{$user_id}' ";
$result_2 = mysql_query($sql);
}


for ($j=0; $j < $count; $j++) {
$user_id = $decline[$j];
$new_shift_type = $shift_types[$i];
$new_days_off = $days_off[$i];

echo $del_id."<br />";
$qry = "UPDATE vacation_table SET declined = 'true' WHERE user_id = '{$user_id}' ";
$result_3 = mysql_query($qry);
}



if($result_2){
echo "<meta http-equiv=\"refresh\" content=\"0;URL=view_vacation_requests.php\">";
}
}

mysql_close();
?>

</table>
</form>
</td>
</tr>
</table>


<?php
print_footer();

print_supervisor_menu();


?>