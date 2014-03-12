<?php
	include("es_functions.php");

	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}


	$firstname = clean($_POST['fname']);
	$lastname = clean($_POST['lname']);
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

	$patient_id = $_POST['patient_id'];



	$query = "UPDATE patients SET firstname = '{$firstname}', lastname = '{$lastname}', email = '{$email}', phone = '{$phone}', sex = '{$sex}', birthday = '{$birthday}', register_date = '{$register_date}', register_time = '{$register_time}', address = '{$address}', symptoms = '{$symptoms}', lab_results = '{$lab_results}', diagnosis = '{$diagnosis}', doctor = '{$doctor}', nurse = '{$nurse}', allergies = '{$allergies}', notes = '{$notes}' WHERE patient_id ='{$patient_id}' ";
		
		$result = mysql_query($query);
		if($result) {
			header("Location: view_patients.php");
		}else {
			echo mysql_error();
			echo "<br /> Updating patient information has failed.";
		}

?>