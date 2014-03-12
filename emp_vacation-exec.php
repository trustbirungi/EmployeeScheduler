<?php 


include("es_functions.php");
$user = auth_user();


	//Function to sanitize values received from the form. Prevents SQL injection. The function "clean()" is a custom function that combines several PHP functions to sanitize data that has been submitted through the form. This eliminates illegal characters, SQL commands and any other programming language code
	
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}


	$start_month = clean($_POST['start_month']);
	$start_day = clean($_POST['start_day']);
	$start_year = clean($_POST['start_year']);
	$start_date = clean($_POST['start_month']. $_POST['start_day']. $_POST['start_year']);

	$end_month = clean($_POST['end_month']);
	$end_day = clean($_POST['end_day']);
	$end_year = clean($_POST['end_year']);
	$end_date = clean($_POST['end_month']. $_POST['end_day']. $_POST['end_year']);


	$supervisors = get_employee_supervisors($user);

	$supervisor = $supervisors[0][4];

	$name = $user['user_name'];

	$query = "INSERT INTO vacation_table (name, start_date, end_date, supervisor, approved, declined) VALUES('$name', '$start_date', '$end_date', '$supervisor', 'false', 'false')";

	$result = mysql_query($query);

	//Check whether the query was successful or not
	if($result) {



		//If the query was successful, redirect to the registration sucess page
		header("location: es_emp_index.php");
		exit();
	}else {
		echo mysql_error();
		echo "<br />";
		die("Query failed");
	}

?>