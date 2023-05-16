<?php

REQUIRE_ONCE('0_Connection.php');


//Create SelectUserForLogin
$conn->query("DROP PROCEDURE IF EXISTS SelectUserForLoginProcedure");
$sql = "CREATE PROCEDURE SelectUserForLoginProcedure (
			IN user_n varchar(20),
			IN pass Text(20)
		)
		READS SQL DATA
		BEGIN 
			SELECT id_user, username
			FROM users AS user
			WHERE	user.username = user_n AND
					user.password = pass;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectUserForLoginProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectUserForLoginProcedure: " . $conn->error . "<br>";
}


//Create SelectAllBrand
$conn->query("DROP PROCEDURE IF EXISTS SelectAllBrandProcedure");
$sql = "CREATE PROCEDURE SelectAllBrandProcedure ()
		READS SQL DATA
		BEGIN 
			SELECT bike_brand, COUNT(*) as count 
			FROM bike 
			GROUP BY bike_brand 
			ORDER BY count DESC;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectAllBrandProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectAllBrandProcedure: " . $conn->error . "<br>";
}

//Create SelectAllAccessoryBrand
$conn->query("DROP PROCEDURE IF EXISTS SelectAllAccessoryBrandProcedure");
$sql = "CREATE PROCEDURE SelectAllAccessoryBrandProcedure ()
		READS SQL DATA
		BEGIN 
			SELECT accessory_brand, COUNT(*) as count 
			FROM bike_accessories 
			GROUP BY accessory_brand 
			ORDER BY count DESC;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectAllAccessoryBrandProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectAllAccessoryBrandProcedure: " . $conn->error . "<br>";
}


//Create SelectAllbikes
$conn->query("DROP PROCEDURE IF EXISTS SelectAllbikesProcedure");
$sql = "CREATE PROCEDURE SelectAllbikesProcedure (
			IN brand_value text(20)
		)
		READS SQL DATA
		BEGIN 
			IF (brand_value = 'All') THEN
				SELECT bike.id_bike, bike_name, bike_brand,material,bike_image,color,wheel_size,start_age,end_age,stock,bike.description,rent_price_daily,AverageRatingFunction(bike.id_bike) 'avg_rating',AvailableDateFunction(bike.id_bike) 'available_date'
				FROM bike AS bike
				ORDER BY bike.rent_price_daily;
			ELSEIF (brand_value = '5-All') THEN
				SELECT bike.id_bike, bike_name, bike_brand,material,bike_image,color,wheel_size,start_age,end_age,stock,bike.description,rent_price_daily,AverageRatingFunction(bike.id_bike) 'avg_rating',AvailableDateFunction(bike.id_bike) 'available_date'
				FROM bike AS bike
				ORDER BY bike.rent_price_daily
				LIMIT 5;
			ELSE
				SELECT bike.id_bike, bike_name, bike_brand,material,bike_image,color,wheel_size,start_age,end_age,stock,bike.description,rent_price_daily,AverageRatingFunction(bike.id_bike) 'avg_rating',AvailableDateFunction(bike.id_bike) 'available_date'
				FROM bike AS bike
				WHERE bike.bike_brand = brand_value
				ORDER BY bike.rent_price_daily;
			END IF;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectAllbikesProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectAllbikesProcedure: " . $conn->error . "<br>";
}

//Create SelectAllbikes
$conn->query("DROP PROCEDURE IF EXISTS SelectAllAccessoriesProcedure");
$sql = "CREATE PROCEDURE SelectAllAccessoriesProcedure (
			IN brand_value text(20)
		)
		READS SQL DATA
		BEGIN 
			IF (brand_value = 'All') THEN
				SELECT bike.id_accessory, accessory_name, accessory_brand,category,accessory_image,color,stock,bike.description,rent_price_daily
				FROM bike_accessories AS bike
				ORDER BY bike.rent_price_daily;
			ELSEIF (brand_value = '5-All') THEN
				SELECT bike.id_accessory, accessory_name, accessory_brand,category,accessory_image,color,stock,bike.description,rent_price_daily
				FROM bike_accessories AS bike
				ORDER BY bike.rent_price_daily
				LIMIT 5;
			ELSE
				SELECT bike.id_accessory, accessory_name, accessory_brand,category,accessory_image,color,stock,bike.description,rent_price_daily
				FROM bike_accessories AS bike
				WHERE bike.accessory_brand = brand_value
				ORDER BY bike.rent_price_daily;
			END IF;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectAllAccessoriesProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectAllAccessoriesProcedure: " . $conn->error . "<br>";
}


//Create SelectSinglebike
$conn->query("DROP PROCEDURE IF EXISTS SelectSinglebikeProcedure");
$sql = "CREATE PROCEDURE SelectSinglebikeProcedure (
			IN bikeID int(10)
		)
		READS SQL DATA
		BEGIN 
			DECLARE userID int(10);
			SET userID = (SELECT AvailabilitybikeFunction(bikeID));
			IF (userID = 0)
			THEN
				SELECT *, userID 'id_user'
				FROM bike
				Where id_bike = bikeID;
			ELSE
				SELECT *,AvailableDateFunction(bikeID) 'available_date', userID 'id_user'
				FROM bike
				Where id_bike = bikeID;
			END IF;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectSinglebikeProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectSinglebikeProcedure: " . $conn->error . "<br>";
}

//Create SelectSinglebike
$conn->query("DROP PROCEDURE IF EXISTS SelectSingleaccessoryProcedure");
$sql = "CREATE PROCEDURE SelectSingleaccessoryProcedure (
			IN accessoryID int(10)
		)
		READS SQL DATA
		BEGIN 
			DECLARE userID int(10);
			SET userID = (SELECT AvailabilitybikeFunction(accessoryID));
			SELECT *, userID 'id_user'
			FROM bike_accessories
			Where id_accessory = accessoryID;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectSingleaccessoryProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectSingleaccessoryProcedure: " . $conn->error . "<br>";
}


//Create SelectFeedbackForSinglebike
$conn->query("DROP PROCEDURE IF EXISTS SelectFeedbackForSinglebikeProcedure");
$sql = "CREATE PROCEDURE SelectFeedbackForSinglebikeProcedure (
			IN bikeID int(10)
		)
		READS SQL DATA
		BEGIN 
			SELECT u.f_name,u.l_name,f.rating,f.description,f.creation_date, f.id_user 'id_user_f'
			FROM feedback AS f, users AS u 
			WHERE f.id_bike=bikeID AND f.id_user=u.id_user 
			ORDER BY f.creation_date DESC;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectFeedbackForSinglebikeProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectFeedbackForSinglebikeProcedure: " . $conn->error . "<br>";
}


//Create SelectSingleUser
$conn->query("DROP PROCEDURE IF EXISTS SelectSingleUserProcedure");
$sql = "CREATE PROCEDURE SelectSingleUserProcedure (
			IN userID int(10)
		)
		READS SQL DATA
		BEGIN 
			SELECT username, password, f_name, l_name, tel, addres, date_of_birth, driving_license, expired_date_license, CountUserRatingFunction(id_user) 'total_rating', CountUserRentalFunction(id_user) 'total_rental'
			FROM users
			WHERE id_user=userID;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectSingleUserProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectSingleUserProcedure: " . $conn->error . "<br>";
}


//Create SelectAllRequest
$conn->query("DROP PROCEDURE IF EXISTS SelectAllRequestProcedure");
$sql = "CREATE PROCEDURE SelectAllRequestProcedure (
			IN userID int(10)
		)
		READS SQL DATA
		BEGIN 
			IF(userID = 0) THEN
				SELECT id_request,u.f_name,u.l_name,r.id_user,r.id_bike,reservation_date,request_status,nb_rent_days,total_price,r.creation_date, c.bike_name, c.image, c.brand, r.id_accessory, a.id_accessory, a.accessory_name, a.description, a.category, a.rent_price_daily, a.stock, a.owner_id, a.color, a.image, a.brand, a.creation_date
				FROM request AS r, bike AS c, users AS u, bike_accessories AS a
				WHERE (r.id_bike=c.id_bike OR r.id_accessory=a.id_accessory) AND r.id_user=u.id_user
				ORDER BY r.creation_date DESC;
			ELSE
				SELECT id_request,r.id_user,r.id_bike,reservation_date,request_status,nb_rent_days,total_price,r.creation_date, c.bike_name, c.image, c.brand, a.id_accessory, a.accessory_name, a.description, a.category, a.rent_price_daily, a.stock, a.owner_id, a.color, a.image, a.brand, a.creation_date
				FROM request AS r, bike AS c, bike_accessories AS a
				WHERE r.id_user=userID AND (r.id_bike=c.id_bike OR r.id_accessory=a.id_accessory)
				ORDER BY r.creation_date DESC;
			END IF;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectAllRequestProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectAllRequestProcedure: " . $conn->error . "<br>";
}

//Create SelectReviewRequest
$conn->query("DROP PROCEDURE IF EXISTS SelectReviewRequestProcedure");
$sql = "CREATE PROCEDURE SelectReviewRequestProcedure(IN user_id INT)
		BEGIN
		SELECT id_request,u.f_name,u.l_name,r.id_user,r.id_bike,reservation_date,request_status,nb_rent_days,total_price,r.creation_date, c.bike_name, c.image, c.brand, c.owner_id, a.id_accessory, a.accessory_name, a.description, a.category, a.rent_price_daily, a.stock, a.owner_id, a.color, a.image, a.brand, a.creation_date
		FROM request AS r, bike AS c, users AS u, bike_accessories AS a
		WHERE (c.owner_id=user_id OR a.owner_id=user_id) AND (r.id_bike=c.id_bike OR r.id_accessory=a.id_accessory)
		ORDER BY r.creation_date DESC;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectReviewRequestProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectReviewRequestProcedure: " . $conn->error . "<br>";
}


//Create SelectUsersbikesNb
$conn->query("DROP PROCEDURE IF EXISTS SelectUsersbikesNbProcedure");
$sql = "CREATE PROCEDURE SelectUsersbikesNbProcedure ()
		READS SQL DATA
		BEGIN 
			SELECT  (SELECT COUNT(*) FROM users) as users, 
					(SELECT COUNT(*) FROM bike) as bikes;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectUsersbikesNbProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectUsersbikesNbProcedure: " . $conn->error . "<br>";
}


//Create SelectContactus
$conn->query("DROP PROCEDURE IF EXISTS SelectContactusProcedure");
$sql = "CREATE PROCEDURE SelectContactusProcedure ()
		READS SQL DATA
		BEGIN 
			SELECT id_contact, name, email, description, creation_date
			FROM contact_us
			ORDER BY creation_date DESC;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectContactusProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectContactusProcedure: " . $conn->error . "<br>";
}


//Create SelectNameDateUser
$conn->query("DROP PROCEDURE IF EXISTS SelectNameDateUserProcedure");
$sql = "CREATE PROCEDURE SelectNameDateUserProcedure (IN userID int(10))
		READS SQL DATA
		BEGIN 
			SELECT f_name, l_name, creation_date
			FROM users
			WHERE id_user=userID;
		END;
	";

if ($conn->query($sql) === TRUE) {
	echo "PROCEDURE SelectNameDateUserProcedure created successfully<br>";
} else {
	echo "Error creating PROCEDURE SelectNameDateUserProcedure: " . $conn->error . "<br>";
}


$conn->close();	
?>