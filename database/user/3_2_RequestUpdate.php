<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	REQUIRE_ONCE('../db/0_Connection.php');
	
	$requestPayLoad = file_get_contents("php://input");
	$object = json_decode($requestPayLoad, true);
	
	//collect values of input fields
	$reqID = $object['reqID'];
	$reqST = $object['reqST'];
	
	$sql = "CALL RequestUpdateProcedure(
			'$reqID',
			'$reqST'
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