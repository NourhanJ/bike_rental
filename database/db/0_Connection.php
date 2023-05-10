 <?php

  $host_name = 'localhost';
  $database = 'bike_db';
  $user_name = 'root';
  $password = '';
  $conn = mysqli_connect($host_name, $user_name, $password, $database);

  if (mysqli_connect_errno()) {
    die('<p>Failed to connect to MySQL: '.mysqli_connect_error().'</p>');
  } else {
    //echo '<p>Connection to MySQL server successfully established.</p >';
  }

?> 