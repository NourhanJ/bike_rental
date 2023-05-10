<?php
	
	if ($dbc = @mysqli_connect('localhost','root', '')){
		
        print '<p>Successfully connected to MySQL!</p>';
        
        if (@mysqli_query($dbc,'DROP DATABASE bike_db'))
		{
			print '<p>The database bike_db has been droped!</p>';
		}
		else
		{ 
			print '<p style="color: red;">Could not drop the database because: <br />' . mysqli_error($dbc) . '.</p>';
		}

        mysqli_close($dbc);
		
	}
	
	else
	{
		print '<p style="color: red;">Could not connect to MySQL:<br />' . mysqli_error($dbc) . '.</p>';
	}
?>