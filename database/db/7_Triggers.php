<?php

REQUIRE_ONCE('0_Connection.php');

//Create after request update Trigger
$conn->query("DROP TRIGGER IF EXISTS AfterRequestUpdateTrigger");
$sql = "CREATE TRIGGER AfterRequestUpdateTrigger
        AFTER UPDATE
        ON request FOR EACH ROW
        BEGIN
            IF new.request_status = 1 THEN
                UPDATE bike c SET c.available = 0
                WHERE c.id_bike = new.id_bike;
            ELSEIF new.request_status = 2 THEN
                UPDATE bike c SET c.available = 1
                WHERE c.id_bike = new.id_bike;
            END IF;
        END;
	";

if ($conn->query($sql) === TRUE) {
	echo "TRIGGER AfterRequestUpdateTrigger created successfully<br>";
} else {
	echo "Error creating TRIGGER AfterRequestUpdateTrigger: " . $conn->error . "<br>";
}


//Create after bike update Trigger
$conn->query("DROP TRIGGER IF EXISTS AfterbikeUpdateTrigger");
$sql = "CREATE TRIGGER AfterbikeUpdateTrigger
        AFTER UPDATE
        ON bike FOR EACH ROW
        BEGIN
            IF new.available = 1 AND old.available = 0 THEN
                IF EXISTS( SELECT * FROM request r WHERE r.id_bike = new.id_bike AND r.request_status = 1)
                THEN
                    UPDATE request r SET r.request_status = 2
                    WHERE r.id_bike = new.id_bike AND r.request_status = 1;
                END IF;
            END IF;
        END;
	";

if ($conn->query($sql) === TRUE) {
	echo "TRIGGER AfterbikeUpdateTrigger created successfully<br>";
} else {
	echo "Error creating TRIGGER AfterbikeUpdateTrigger: " . $conn->error . "<br>";
}

?>