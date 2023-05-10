<?php
	
	if ($dbc = @mysqli_connect('localhost','root', '')){
		
		print '<p>Successfully connected to MySQL!</p>';
		// Try to create the database:
		if (@mysqli_query($dbc,'CREATE DATABASE bike_db'))
		{
			print '<p>The database bike_db has been created!</p>';
		}
		else
		{ 
			print '<p style="color: red;">Could not create the database because: <br />' . mysqli_error($dbc) . '.</p>';
		}
		
		if (@mysqli_select_db($dbc,'bike_db'))
		{
			print '<p>The database bike_db has been selected.</p>';
					


			$query= "
			create table bike
			(
			   id_bike              int(10) not null AUTO_INCREMENT,
			   bike_name            text(50) not null,
			   brand                text(50) not null,
			   material             text(50) not null,
			   wheel_size           int(11) not null,
			   color                text(50) not null,
			   accessories          text(500) not null,
			   image                longtext not null,
			   description          text(300) not null,
			   rent_price_daily     float(5) not null,
			   stock				int(11) not null,
			   start_age			int(11) not null,
			   end_age				int(11) not null,
			   available            int(1) not null DEFAULT '1',
			   creation_date        datetime not null DEFAULT CURRENT_TIMESTAMP,
			   owner_id             int(11) not null,
			   primary key (id_bike)
			)		
			";

			if(@mysqli_query($dbc,$query)){
				print "table bike has created <br>";
			}
			else print 	'<p style="color: red;">Could not exec query because:<br/>' . mysqli_error($dbc) . '.</p>';
					


			$query= "		
			create table contact_us
			(
			   id_contact           int(10) not null AUTO_INCREMENT,
			   name                 text(50) not null,
   			   email                text(50) not null,
			   description          text(300) not null,
			   creation_date        datetime not null DEFAULT CURRENT_TIMESTAMP,
			   primary key (id_contact)
			)		
			";

			if(@mysqli_query($dbc,$query)){
				print "table contact_us has created <br>";
			}
			else print 	'<p style="color: red;">Could not exec query because:<br/>' . mysqli_error($dbc) . '.</p>';
					


			$query= "
			create table feedback
			(
			   id_user              int(10) not null,
			   id_bike               int(10) not null,
			   rating               int(1) not null,
			   description          text(300) not null,
			   creation_date        datetime not null DEFAULT CURRENT_TIMESTAMP,
			   primary key (id_user, id_bike)
			)
			";

			if(@mysqli_query($dbc,$query)){
				print "table feedback has created <br>";
			}
			else print 	'<p style="color: red;">Could not exec query because:<br/>' . mysqli_error($dbc) . '.</p>';
					


			$query= "
			create table request
			(
			   id_request           int(10) not null AUTO_INCREMENT,
			   id_user              int(10) not null,
			   id_bike               int(10) not null,
			   reservation_date     datetime not null,
			   request_status       int(1) not null DEFAULT '0',
			   nb_rent_days         int(3) not null,
			   total_price          float(5) not null,
			   creation_date        datetime not null DEFAULT CURRENT_TIMESTAMP,
			   primary key (id_request)
			)
			";

			if(@mysqli_query($dbc,$query)){
				print "table request has created <br>";
			}
			else print 	'<p style="color: red;">Could not exec query because:<br/>' . mysqli_error($dbc) . '.</p>';
					


			$query= "
			create table users
			(
			   id_user              int(10) not null AUTO_INCREMENT,
			   username             varchar(20) not null UNIQUE,
			   password             text(200) not null,
			   f_name               text(50) not null,
			   l_name               text(50) not null,
			   tel                  varchar(9) not null UNIQUE,
			   addres               text(100) not null,
			   date_of_birth        date not null,
			   creation_date        datetime not null DEFAULT CURRENT_TIMESTAMP,
			   primary key (id_user)
			)
			";

			if(@mysqli_query($dbc,$query)){
				print "table users has created <br>";
			}
			else print 	'<p style="color: red;">Could not exec query because:<br/>' . mysqli_error($dbc) . '.</p>';
				
			

			$query= "
				alter table feedback add constraint fk_give foreign key (id_user)
      			references users (id_user) on delete restrict on update restrict
			";

			if(@mysqli_query($dbc,$query)){
				print "constraint fk_give has created <br>";
			}
			else print 	'<p style="color: red;">Could not exec query because:<br/>' . mysqli_error($dbc) . '.</p>';
					


			$query= "
				alter table feedback add constraint fk_to foreign key (id_bike)
      			references bike (id_bike) on delete restrict on update restrict
			";

			if(@mysqli_query($dbc,$query)){
				print "constraint fk_to has created <br>";
			}
			else print 	'<p style="color: red;">Could not exec query because:<br/>' . mysqli_error($dbc) . '.</p>';
				
			

			$query= "
				alter table request add constraint fk_apply foreign key (id_user)
				references users (id_user) on delete restrict on update restrict
			";

			if(@mysqli_query($dbc,$query)){
				print "constraint fk_apply has created <br>";
			}
			else print 	'<p style="color: red;">Could not exec query because:<br/>' . mysqli_error($dbc) . '.</p>';
				
			

			$query= "
				alter table request add constraint fk_from foreign key (id_bike)
      			references bike (id_bike) on delete restrict on update restrict
			";

			if(@mysqli_query($dbc,$query)){
				print "constraint fk_from has created <br>";
			}
			else print 	'<p style="color: red;">Could not exec query because:<br/>' . mysqli_error($dbc) . '.</p>';
					
		}
		else
		{
			print '<p style="color: red;">Could not select the database chance because:<br/>' . mysqli_error($dbc) . '.</p>';
		}
		mysqli_close($dbc);
		
	}
	
	else
	{
		print '<p style="color: red;">Could not connect to MySQL:<br />' . mysqli_error($dbc) . '.</p>';
	}
?>