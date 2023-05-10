<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	REQUIRE_ONCE('../db/0_Connection.php');
	
	$requestPayLoad = file_get_contents("php://input");
	$object = json_decode($requestPayLoad, true);
	
	// collect value of input field
	$username = $object['username'];
	$ExpLDate = $object['ExpLDate'];
	
	$sql = "CALL UserForgetPasswordUpdateProcedure(
			'$username',
			'$ExpLDate'
			);
		";
	
	if($temp = @mysqli_query($conn, $sql)){
		while($result = @mysqli_fetch_assoc($temp))
			if ($result != null) {
				header('Content-Type', 'application/json; charset = UTF-8');
				echo json_encode($result);
			}
	}else {
		echo "Error";
	}
	$conn->close();	
}

?>