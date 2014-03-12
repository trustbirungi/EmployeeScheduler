<?php
	include("es_functions.php");

	$shift_type = $_POST["shift_type"];
	$days_off = implode("", $_POST["days_off"]);
	$user_id = $_POST["user_id"];

	$query = "UPDATE es_user SET temporary_shift_type = '". $shift_type."', temporary_days_off = '".$days_off."', approved = 'false', declined = 'false' WHERE user_id = '".$user_id."' ";

	mysql_query($query);

	header("Location: es_emp_index.php");

?>