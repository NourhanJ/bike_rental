<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	REQUIRE_ONCE('../db/0_Connection.php');
	
	$requestPayLoad = file_get_contents("php://input");
	$object = json_decode($requestPayLoad, true);

	//collect values of input fields
	$userID = $object['userID'];
	$bikeID = $object['bikeID'];
	$res_d = $object['res_d'];
	$nb_rent_d = $object['nb_rent_d'];
	$total_price = $object['total_price'];
	
	$sql = "CALL RequestInsertProcedure(
		'$userID',
		'$bikeID',
		'$res_d',
		'$nb_rent_d',
		'$total_price'
		);
	";
	if (@mysqli_query($conn, $sql)) {
		echo "Request sended successfully";
	}
	else {
		echo "Request sended failed!";
	}

	$conn->close();	
}



?>