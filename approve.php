<?php
include("es_functions.php");

$user_id = $_GET["q"];

$new_shift_type = "";
$new_days_off = "";

$query = "SELECT * FROM es_user WHERE user_id = '{$user_id}' AND approved = 'false'";
	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {
		$new_shift_type = $row['temporary_shift_type'];
		$new_days_off = $row['temporary_days_off'];
	}



$qry = "UPDATE es_user SET shift_type = '". $new_shift_type."', days_off = '".$new_days_off."' WHERE user_id = '".$user_id."' ";
$result_2 = mysql_query($qry);


header("Location: view_change_requests.php" );

?>