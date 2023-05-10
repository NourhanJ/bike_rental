<?php
REQUIRE_ONCE('0_Connection.php');


//Create Users insert Procedure
$conn->query("DROP PROCEDURE IF EXISTS UsersInsertProcedure");
$sql = " CREATE PROCEDURE UsersInsertProcedure (
		IN userName NVARCHAR(20),
		IN pass NVARCHAR(20),
		IN fname NVARCHAR(50),
		IN lname NVARCHAR(50),
		IN tell NVARCHAR(9),
		IN address NVARCHAR(100),
		IN dob date,
		IN dl NVARCHAR(2),
		IN exp_dl date
		)
		BEGIN
			Declare counter INT;
			SET counter = 0;

			INSERT INTO `users`(`username`, `password`, `f_name`, `l_name`, `tel`, `addres`, `date_of_birth`, `driving_license`, `expired_date_license`)
			VALUES (userName, pass, fname, lname, tell, address, CAST(dob AS DATE), dl, CAST(exp_dl AS DATE));
			SET counter = counter + ROW_COUNT();
			
			IF (counter = 0)
			THEN 
				SELECT FALSE;
			ELSE
				SELECT TRUE;
			END IF;	

		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE UsersInsertProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE UsersInsertProcedure: " . $conn->error . "<br>";
}

//Create Feedback insert Procedure
$conn->query("DROP PROCEDURE IF EXISTS FeedbackInsertProcedure");
$sql = " CREATE PROCEDURE FeedbackInsertProcedure (
		IN userId              int(10),
		IN bikeId               int(10),
		IN rate               int(1),
		IN msg          text(300)
		)
	BEGIN 

		IF (EXISTS (SELECT * FROM `feedback` WHERE id_user=userId AND id_bike=bikeId))
		THEN 
			UPDATE `feedback` SET `rating`=rate, `description`=msg 
			WHERE id_user=userId AND id_bike=bikeId;
		ELSE
			INSERT INTO `feedback`(`id_user`, `id_bike`, `rating`, `description`)
			VALUES (userId,bikeId,rate,msg);
		END IF;
		
	END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE FeedbackInsertProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE FeedbackInsertProcedure: " . $conn->error . "<br>";
}

//Create Request insert Procedure
$conn->query("DROP PROCEDURE IF EXISTS RequestInsertProcedure");
$sql = " CREATE PROCEDURE RequestInsertProcedure (
		IN userID              int(10),
		IN bikeID               int(10),
		IN reservationDate     datetime,
		IN nb_rentDays         int(3),
		IN totalPrice          float(5)
		)
	BEGIN 
		DECLARE reqID int(10);
		SET reqID = (SELECT r.id_request FROM request AS r WHERE r.id_bike = bikeID AND r.id_user = userID AND r.request_status=0);
		IF (reqID is NULL)
		THEN
			INSERT INTO `request`(`id_user`, `id_bike`, `reservation_date`, `nb_rent_days`, `total_price`)
			VALUES (userID, bikeID, CAST(reservationDate AS DATETIME), nb_rentDays, totalPrice);
		ELSE
			UPDATE request SET reservation_date=reservationDate, nb_rent_days=nb_rentDays, total_price=totalPrice
			WHERE id_request = reqID;
		END IF;
	END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE RequestInsertProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE RequestInsertProcedure: " . $conn->error . "<br>";
}

//Create ContactUs insert Procedure
$conn->query("DROP PROCEDURE IF EXISTS ContactUsInsertProcedure");
$sql = " CREATE PROCEDURE ContactUsInsertProcedure (
		IN name_val                 text(50),
		IN email_val                text(50),
		IN description_val          text(300)
		)
	BEGIN 
		INSERT INTO `contact_us`(`name`, `email`, `description`)
		VALUES (name_val,email_val,description_val);
	END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE ContactUsInsertProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE ContactUsInsertProcedure: " . $conn->error . "<br>";
}

//Create bike insert Procedure
$conn->query("DROP PROCEDURE IF EXISTS bikeInsertProcedure");
$sql = " CREATE PROCEDURE bikeInsertProcedure (
		IN bikeName      text(50),
		IN brand         text(50),
		IN material      text(50),
		IN wheel_size    int(11),
		IN color         text(50),
		IN accessories   text(500),
		IN img           text(50),
		IN descr         text(300),
		IN rpd     		 float(5),
		IN stock         int(11),
		IN start_age     int(11),
		IN end_age       int(11),
		IN owner_id      int(11)
		)
	BEGIN 
		INSERT INTO `bike`(`bike_name`, `brand`, `material`, `wheel_size`, `color`, `accessories`, `image`, `description`, `rent_price_daily`, `stock`, `start_age`, `end_age`, `owner_id`)
		VALUES (bikeName, brand, material, wheel_size, color, accessories, img, descr, rpd, stock, start_age, end_age, owner_id);

		SELECT LAST_INSERT_ID();
	END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE bikeInsertProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE bikeInsertProcedure: " . $conn->error . "<br>";
}

$conn->close();	
?>