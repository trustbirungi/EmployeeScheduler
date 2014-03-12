<?php

include("es_functions.php");
$user = auth_supervisor();

$tdays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");


print_header("View Change Requests");

$query = "SELECT * FROM es_user WHERE approved = 'false' AND declined = 'false'";
$result = mysql_query($query);

$count=mysql_num_rows($result);

?>

<table class = 'feature-table dark-gray'>
<tr>
<td><form name="form1" method="POST" action="">
<table>
						<thead>
							<tr> 
								<th>User ID</th>
								<th>Name</th> 
								<th>Current Shift Type</th>
								<th>Requested Shift Type</th> 
								<th>Current Days Off</th>
								<th>Requested Days Off</th>
								<th>Suggested Days Off</th>
								<th>Approve</th>
								<th>Decline</th> 								
							</tr>
						</thead>

<?php
while($rows=mysql_fetch_array($result)){
?>

<tr>

<td><? echo $rows['user_id']; ?></td>
<td><? echo $rows['user_name']; ?></td>
<td><? echo $rows['shift_type']; ?></td>
<td><? echo $rows['temporary_shift_type']; ?></td>
<td><? echo $rows['days_off']; ?></td>
<td><? echo $rows['temporary_days_off']; ?></td>
<td>
	<select name = "daysoff[]" multiple = "multiple">
			<?php 
				for($i = 0; $i < count($tdays); $i++) {
					echo "<option value = '".$i."'>".$tdays[$i]."</option>";
				}
			?>
		</select>
</td>

<td><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $rows['user_id']; ?>"></td>

<td><input name="decline[]" type="checkbox" id="decline[]" value="<?php echo $rows['user_id']; ?>"></td>

<input type = "hidden" name = "shift_types[]" value = "<?php echo $rows['temporary_shift_type']; ?>" >

<input type = "hidden" name = "days_off[]" value = "<?php echo $rows['temporary_days_off']; ?>" > 

</tr>

<?php
}
?>

<tr>
<td colspan="5" align="center"><input name="approve" type="submit" id="approve" value="Submit"></td>
</tr>

<?php
// Check if delete button active, start this 
if($approve) {

for ($i=0; $i < $count; $i++) {
$user_id = $checkbox[$i];
$new_shift_type = $shift_types[$i];
$new_days_off = $days_off[$i];
$suggested_days_off = implode("", $daysoff);

if(count($daysoff) > 0) {

echo $del_id."<br />";
$sql = "UPDATE es_user SET shift_type = '{$new_shift_type}', days_off = '{$suggested_days_off}', approved = 'true' WHERE user_id = '{$user_id}' ";
$result_2 = mysql_query($sql);
}else {

	$sql = "UPDATE es_user SET shift_type = '{$new_shift_type}', days_off = '{$new_days_off}', approved = 'true' WHERE user_id = '{$user_id}' ";
	$result_2 = mysql_query($sql);
}

}


for ($j=0; $j < $count; $j++) {
$user_id = $decline[$j];
//$new_shift_type = $shift_types[$i];
//$new_days_off = $days_off[$i];

echo $del_id."<br />";
$qry = "UPDATE es_user SET declined = 'true' WHERE user_id = '{$user_id}' ";
$result_3 = mysql_query($qry);
}


if($result_2){
echo "<meta http-equiv=\"refresh\" content=\"0;URL=view_change_requests.php\">";
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