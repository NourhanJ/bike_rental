<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	REQUIRE_ONCE('../db/0_Connection.php');
	
	$requestPayLoad = file_get_contents("php://input");
	$object = json_decode($requestPayLoad, true);

	//collect values of input fields
	$userName = $object['userName'];
	$pass = $object['password'];
	$fn = $object['f_name'];
	$ln = $object['l_name'];
	$tel = $object['tel'];
	$addres = $object['addres'];
	$dob = $object['dob'];
	$driving_license = $object['driving_license'];
	$exp_dl = $object['exp_dl'];

	$sql = "CALL UsersInsertProcedure(
		'$userName',
		'$pass',
		'$fn',
		'$ln',
		'$tel',
		'$addres',
		'$dob',
		'$driving_license',
		'$exp_dl'
		);
	";

$result = @mysqli_query($conn, $sql);
$result = @mysqli_fetch_assoc($result);

if(isset($result) && array_key_exists('TRUE', $result))
	echo "Data inserted successfully";
else {
	echo "Username or Tel Already Exists!";
}

	$conn->close();	
}



?>