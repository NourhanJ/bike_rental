<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	REQUIRE_ONCE('../db/0_Connection.php');
	
	$requestPayLoad = file_get_contents("php://input");
	$object = json_decode($requestPayLoad, true);

	//collect values of input fields
	$userID = $object['userID'];
	$bikeID = $object['bikeID'];
	$rate = $object['rate'];
	$msg = $object['msg'];
	
	$sql = "CALL FeedbackInsertProcedure(
		'$userID',
		'$bikeID',
		'$rate',
		'$msg'
		);
	";
	if (@mysqli_query($conn, $sql)) {
		echo "Thanks for feedback";
	}
	else {
		//echo $conn->error;
		echo "Feedback sended failed!";
	}

	$conn->close();	
}



?>