<?php
/*
This is the script that processes the data that has been submitted through the patient registration form. It checks for errors, sanitizes and validates form data and then submits it to the appropriate table in the database.
*/
	
include("es_functions.php");
	
	//Function to sanitize values received from the form. Prevents SQL injection. The function "clean()" is a custom function that combines several PHP functions to sanitize data that has been submitted through the form. This eliminates illegal characters, SQL commands and any other programming language code
	
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values. All the data submitted through the form is stored in a super global array called "$_POST". Pass each input field data to the function "clean()". After that, assign it to another variable
	
	$fname = clean($_POST['fname']);
	$lname = clean($_POST['lname']);
	$email = clean($_POST['email']);
	$phone = clean ($_POST['phone']);
	$sex = clean ($_POST['sex']);
	$month = clean($_POST['month']);
	$day = clean($_POST['day']);
	$year = clean($_POST['year']);
	$birthday = clean($_POST['day']. $_POST['month']. $_POST['year']);
	$register_date = clean($_POST['register_date']);
	$register_time = clean($_POST['register_time']);
	$address = clean ($_POST['address']);
	$symptoms = clean($_POST['symptoms']);
	$lab_results = clean($_POST['lab_results']);
	$diagnosis = clean($_POST['diagnosis']);
	$doctor = clean($_POST['doctor']);
	$nurse = clean($_POST['nurse']);
	$allergies = clean($_POST['allergies']);
	$notes = clean($_POST['notes']);


	
	//Create INSERT query. This is the query which, after all the submitted data has been validated, submits it to the database table that stores the details of the registered patients
	$query = "INSERT INTO patients(firstname, lastname, email, phone, sex, birthday, register_date, register_time, address, symptoms, lab_results, diagnosis, doctor, nurse, allergies, notes) VALUES('$fname','$lname','$email', '$phone', '$sex', '$birthday', '$register_date', '$register_time', '$address', '$symptoms', '$lab_results', '$diagnosis', '$doctor', '$nurse', '$allergies', '$notes')";

	$result = mysql_query($query);
	
	//Check whether the query was successful or not
	if($result) {
		//If the query was successful, redirect to the registration sucess page
		header("location: register_patients-success.php");
		exit();
	}else {
		echo mysql_error();
		echo "<br />";
		die("Query failed");
	}
?>