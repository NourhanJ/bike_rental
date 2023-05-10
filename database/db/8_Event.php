<?php

REQUIRE_ONCE('0_Connection.php');

//Create delete request passed Event
$conn->query("DROP EVENT IF EXISTS RequestPassedDeleteEvent");
$sql = "CREATE EVENT RequestPassedDeleteEvent
        ON SCHEDULE 
        EVERY 1 HOUR
        COMMENT 'check res_date form request waiting each hour.'
        DO
        DELETE FROM request WHERE reservation_date<NOW();
	";

if ($conn->query($sql) === TRUE) {
	echo "Event RequestPassedDeleteEvent created successfully<br>";
} else {
	echo "Error creating Event RequestPassedDeleteEvent: " . $conn->error . "<br>";
}


?>