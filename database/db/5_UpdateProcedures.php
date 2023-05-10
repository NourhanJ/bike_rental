<?php
REQUIRE_ONCE('0_Connection.php');

//Create Users update Procedure
$conn->query("DROP PROCEDURE IF EXISTS UsersUpdateProcedure");
$sql = " CREATE PROCEDURE UsersUpdateProcedure (
			IN userID int(10),
			IN fname NVARCHAR(50),
			IN lname NVARCHAR(50),
			IN tele NVARCHAR(9),
			IN address NVARCHAR(100),
			IN dob date,
			IN dl NVARCHAR(2),
			IN exp_dl date
		)
		BEGIN 
			UPDATE users As user SET
					user.f_name = fname,
					user.l_name = lname,
					user.tel = tele,
					user.addres = address,
					user.date_of_birth = CAST(dob AS DATE),
					user.driving_license = dl,
					user.expired_date_license = CAST(exp_dl AS DATE)
			WHERE user.id_user = userID;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE UsersUpdateProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE UsersUpdateProcedure: " . $conn->error . "<br>";
}


//Create UserPassword update Procedure
$conn->query("DROP PROCEDURE IF EXISTS UserPasswordUpdateProcedure");
$sql = " CREATE PROCEDURE UserPasswordUpdateProcedure (
			IN userID int(10),
			IN pass text(20)
		)
		BEGIN 
			UPDATE users SET password = pass
			WHERE id_user = userID;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE UserPasswordUpdateProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE UserPasswordUpdateProcedure: " . $conn->error . "<br>";
}


//Create UserForgetPassword update Procedure
$conn->query("DROP PROCEDURE IF EXISTS UserForgetPasswordUpdateProcedure");
$sql = " CREATE PROCEDURE UserForgetPasswordUpdateProcedure (
			IN user varchar(20),
			IN exp_dl date
		)
		BEGIN 
			SELECT id_user, username
			FROM users AS u
			WHERE u.username=user AND expired_date_license=exp_dl;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE UserForgetPasswordUpdateProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE UserForgetPasswordUpdateProcedure: " . $conn->error . "<br>";
}


//Create bike update Procedure
$conn->query("DROP PROCEDURE IF EXISTS bikeUpdateProcedure");
$sql = " CREATE PROCEDURE bikeUpdateProcedure (
			IN bikeID 			int(10),
			IN bikeName          text(50),
			IN trans	        text(20),
			IN img              text(50),
			IN descr         	text(300),
			IN rpd  		   	float(5),
			IN av 				int(1)
		)
		BEGIN 
			UPDATE bike As bike SET
				bike.bike_name = bikeName,
				bike.image = img,
				bike.description = descr,
				bike.rent_price_daily = rpd,
				bike.available = av
			WHERE bike.id_bike = bikeID;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE bikeUpdateProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE bikeUpdateProcedure: " . $conn->error . "<br>";
}


//Create Request update Procedure
$conn->query("DROP PROCEDURE IF EXISTS RequestUpdateProcedure");
$sql = " CREATE PROCEDURE RequestUpdateProcedure (
			IN reqId int(10),
			IN RequestStatus int(1)
		)
		BEGIN 
			DECLARE max_date datetime;
			DECLARE min_date datetime;
			DECLARE d2_date datetime;
			DECLARE res_date datetime;
			DECLARE bikeID int(10);

			IF(RequestStatus = 0)
			THEN
				DELETE FROM request WHERE id_request = reqId;

			ELSEIF(RequestStatus = 3)	#reject
			THEN
				UPDATE request SET request_status = RequestStatus
				WHERE id_request = reqId;

			ELSEIF(RequestStatus = 2)	#finish
			THEN
				UPDATE request SET request_status = RequestStatus
				WHERE id_request = reqId;

			ELSE	#accept or active
				START TRANSACTION;
				set bikeID = (SELECT id_bike FROM request WHERE id_request = reqId);
				set max_date = (SELECT MAX(DATE_ADD(r.reservation_date, INTERVAL r.nb_rent_days Day)) FROM request r WHERE r.id_bike = bikeID AND (r.request_status = 1 || r.request_status = 4));
				set min_date = (SELECT MIN(DATE_ADD(r.reservation_date, INTERVAL r.nb_rent_days Day)) FROM request r WHERE r.id_bike = bikeID AND (r.request_status = 1 || r.request_status = 4));

				#update
				IF(max_date IS NULL)
				THEN
					UPDATE request SET request_status = RequestStatus
					WHERE id_request = reqId;
				ELSE
					UPDATE request SET request_status = RequestStatus
					WHERE id_request = reqId AND (reservation_date > max_date || reservation_date < min_date);
				END IF;

				set d2_date = (SELECT DATE_ADD(reservation_date, INTERVAL nb_rent_days Day) FROM request WHERE id_request = reqId AND request_status = RequestStatus);
				set res_date = (SELECT reservation_date FROM request WHERE id_request = reqId AND request_status = RequestStatus);

				#reject all other request
				UPDATE request SET request_status = 3
				WHERE id_bike = bikeID AND request_status = 0 AND reservation_date < d2_date AND reservation_date > res_date;

				COMMIT;
			END IF;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE RequestUpdateProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE RequestUpdateProcedure: " . $conn->error . "<br>";
}

$conn->close();	
?>