<?php

REQUIRE_ONCE('0_Connection.php');


//Create ranting average Function
$conn->query("DROP FUNCTION IF EXISTS AverageRatingFunction");
$sql = " CREATE FUNCTION AverageRatingFunction (bikeID int(10)) 
		RETURNS float(5) 
		READS SQL DATA
		BEGIN
			Declare rate float(5);
			SET rate = (
				SELECT AVG(rating)
				FROM `feedback` AS feedback
				WHERE feedback.id_bike = bikeID
				GROUP BY feedback.id_bike
			);
			RETURN rate;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "FUNCTION AverageRatingFunction created successfully<br>";
} else {
	echo "Error creating FUNCTION AverageRatingFunction: " . $conn->error . "<br>";
}


//Create available date Function
$conn->query("DROP FUNCTION IF EXISTS AvailableDateFunction");
$sql = " CREATE FUNCTION AvailableDateFunction (bikeID int(10)) 
		RETURNS date
		READS SQL DATA
		BEGIN
			Declare av_date date;

			SET av_date = (
				SELECT DATE_ADD(`reservation_date`, INTERVAL `nb_rent_days` Day)
				FROM request
				WHERE request.id_bike = bikeID AND (request.request_status = 1 OR request.request_status = 4)
			);
			RETURN av_date;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "FUNCTION AvailableDateFunction created successfully<br>";
} else {
	echo "Error creating FUNCTION AvailableDateFunction: " . $conn->error . "<br>";
}


//Create test if bike is available Function
$conn->query("DROP FUNCTION IF EXISTS AvailabilitybikeFunction");
$sql = " CREATE FUNCTION AvailabilitybikeFunction (bikeID int(10)) 
		RETURNS int(10)
		READS SQL DATA
		BEGIN
			DECLARE userID int(10);
			SET userID = (SELECT id_user FROM request as r, bike as c WHERE r.id_bike=c.id_bike AND r.id_bike = bikeID AND (r.request_status = 1 OR r.request_status = 4) AND DATE_ADD(r.reservation_date, INTERVAL r.nb_rent_days Day) >= CURDATE());
			IF (userID is null)
				THEN RETURN 0;
			ELSE RETURN userID;
			END IF;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "FUNCTION AvailabilitybikeFunction created successfully<br>";
} else {
	echo "Error creating FUNCTION AvailabilitybikeFunction: " . $conn->error . "<br>";
}


//Create count user rental Function
$conn->query("DROP FUNCTION IF EXISTS CountUserRentalFunction");
$sql = " CREATE FUNCTION CountUserRentalFunction (userID int(10)) 
		RETURNS int(10) 
		READS SQL DATA
		BEGIN
			Declare rentCount int(10);
			SET rentCount = (
				SELECT count(*)
				FROM request AS r
				WHERE r.id_user = userID
			);
			RETURN rentCount;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "FUNCTION CountUserRentalFunction created successfully<br>";
} else {
	echo "Error creating FUNCTION CountUserRentalFunction: " . $conn->error . "<br>";
}


//Create count user rating Function
$conn->query("DROP FUNCTION IF EXISTS CountUserRatingFunction");
$sql = " CREATE FUNCTION CountUserRatingFunction (userID int(10)) 
		RETURNS float(5) 
		READS SQL DATA
		BEGIN
			Declare rateCount int(10);
			SET rateCount = (
				SELECT count(*)
				FROM feedback AS f
				WHERE f.id_user = userID
			);
			RETURN rateCount;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "FUNCTION CountUserRatingFunction created successfully<br>";
} else {
	echo "Error creating FUNCTION CountUserRatingFunction: " . $conn->error . "<br>";
}


$conn->close();	
?>