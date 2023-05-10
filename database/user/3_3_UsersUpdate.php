<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	REQUIRE_ONCE('../db/0_Connection.php');
	
	$requestPayLoad = file_get_contents("php://input");
	$object = json_decode($requestPayLoad, true);
	
	//collect values of input fields
	$userId = $object['userId'];
	$fn = $object['f_name'];
	$ln = $object['l_name'];
	$tel = $object['tel'];
	$addres = $object['addres'];
	$dob = $object['dob'];
	$driving_license = $object['driving_license'];
	$exp_dl = $object['exp_dl'];

	$sql = "CALL UsersUpdateProcedure(
		'$userId',
		'$fn',
		'$ln',
		'$tel',
		'$addres',
		'$dob',
		'$driving_license',
		'$exp_dl'
		);
	";

	if (@mysqli_query($conn, $sql)) {
		echo "Data updated successfully";
	}
	else {
		echo "Data updated failed!";
	}

	$conn->close();	
}



?>