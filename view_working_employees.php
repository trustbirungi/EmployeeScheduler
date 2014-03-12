<?php
include("es_functions.php");
print_header("View Employees Working Per Day");


$query1 = "SELECT user_name, days_off, shift_type FROM es_user WHERE days_off LIKE '%0%' ";
$query2 = "SELECT user_name, days_off, shift_type FROM es_user WHERE days_off LIKE '%1%' ";
$query3 = "SELECT user_name, days_off, shift_type FROM es_user WHERE days_off LIKE '%2%' ";
$query4 = "SELECT user_name, days_off, shift_type FROM es_user WHERE days_off LIKE '%3%' ";
$query5 = "SELECT user_name, days_off, shift_type FROM es_user WHERE days_off LIKE '%4%' ";
$query6 = "SELECT user_name, days_off, shift_type FROM es_user WHERE days_off LIKE '%5%' ";
$query7 = "SELECT user_name, days_off, shift_type FROM es_user WHERE days_off LIKE '%6%' ";


$result1 = mysql_query($query1);
$result2 = mysql_query($query2);
$result3 = mysql_query($query3);
$result4 = mysql_query($query4);
$result5 = mysql_query($query5);
$result6 = mysql_query($query6);
$result7 = mysql_query($query7);



?>

<table class = 'feature-table dark-gray'>

						<thead>
							<tr> 
								<th>Monday</th> 
								<th>Tuesday</th> 
								<th>Wednesday</th>
								<th>Thursday</th>
								<th>Friday</th>
								<th>Saturday</th>
								<th>Sunday</th> 								
							</tr>
						</thead>

<?php


$monday = array();
$tuesday = array();
$wednesday = array();
$thursday = array();
$friday = array();
$saturday = array();
$sunday = array();


while($row1 = mysql_fetch_array($result1)) {
	$monday[] = $row1;
}

while($row2 = mysql_fetch_array($result2)) {
	$tuesday[] = $row2;
}

while($row3 = mysql_fetch_array($result3)) {
	$wednesday[] = $row3;
}

while($row4 = mysql_fetch_array($result4)) {
	$thursday[] = $row4;
}

while($row5 = mysql_fetch_array($result5)) {
	$friday[] = $row5;
}

while($row6 = mysql_fetch_array($result6)) {
	$saturday[] = $row6;
}

while($row7 = mysql_fetch_array($result7)) {
	$sunday[] = $row7;
}


$count1 = mysql_num_rows($result1);
$count2 = mysql_num_rows($result2);
$count3 = mysql_num_rows($result3);
$count4 = mysql_num_rows($result4);
$count5 = mysql_num_rows($result5);
$count6 = mysql_num_rows($result6);
$count7 = mysql_num_rows($result7);


$max_length = max($count1, $count2, $count3, $count4, $count5, $count6, $count7);


$i = 0;
while($i < $max_length){

?>



<tr>

<td><? echo $monday[$i]['user_name']; ?></td>
<td><? echo $tuesday[$i]['user_name']; ?></td>
<td><? echo $wednesday[$i]['user_name']; ?></td>
<td><? echo $thursday[$i]['user_name']; ?></td>
<td><? echo $friday[$i]['user_name']; ?></td>
<td><? echo $saturday[$i]['user_name']; ?></td>
<td><? echo $sunday[$i]['user_name']; ?></td>


<?php
$i++;

}//end while
?>


</table>




<?php
print_footer();
print_supervisor_menu();
?>