<?php
// echo password_hash('1234', PASSWORD_DEFAULT);
require_once('database/db-page.php');
if(isset($_COOKIE['CR-userID']) && !empty($_COOKIE['CR-userID'])){
    header("Location:index.php");
}    
else if(isset($_POST['submit'])){
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $age = $_POST['age'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = md5($_POST['pass']);
    echo $_POST['pass'];
    // $password = md5($password);
    // echo password_hash($password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO users (`username`, `password`, `f_name`, `l_name`, `tel`, `addres`, `age`) VALUES ('$username', '$password', '$firstName', '$lastName', '$tel', '$address', '$age')";
    include('database/db/0_Connection.php');
    if(mysqli_query($conn, $query)){
        $userID = mysqli_insert_id($conn);
        header("Location:login.php?r=Registration");
    }
    else{
        echo "Error: " . mysqli_error($conn);
    }
}    
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <!-- SITE TITTLE -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bike Rental</title>
  
  <!-- LOGO -->
  <link href="images/logo.png" rel="shortcut icon">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap-slider.css">
  <!-- Font Awesome -->
  <link href="plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- Owl Carousel -->
  <link href="plugins/slick-carousel/slick/slick.css" rel="stylesheet">
  <link href="plugins/slick-carousel/slick/slick-theme.css" rel="stylesheet">
  <!-- Fancy Box -->
  <link href="plugins/fancybox/jquery.fancybox.pack.css" rel="stylesheet">
  <link href="plugins/jquery-nice-select/css/nice-select.css" rel="stylesheet">
  <!-- CUSTOM CSS -->
  <link href="css/style.css" rel="stylesheet">
  <script src="alert_style/sweetalert2.all.min.js"></script>

</head>

<body class="body-wrapper">

<section class="login py-5 border-top-1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8 align-item-center">
                    <div class="border border">
                        <h3 class="bg-gray p-4">Register Now</h3>
                        <form id="form_val" method="POST">
                          <!-- User Image -->
                          <div class="image d-flex justify-content-center">
                            <img src="images/logo.png" style="width:50%;margin-top:5%;" alt="" class="">
                          </div>
                            <fieldset class="p-4">
                              <div class="form-group">
                                <label for="first-name">First Name</label>
                                <input type="text" name="first-name" maxlength="50" minlength="3" placeholder="First Name" class="border p-3 w-100 my-2" required>
                              </div>
                              <div class="form-group">
                                <label for="last-name">Last Name</label>
                                <input type="text" name="last-name" maxlength="50" minlength="3" placeholder="Last Name" class="border p-3 w-100 my-2" required>
                              </div>
                              <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" name="age" class="border p-3 w-100 my-2" required>
                              </div>
                              <div class="form-group">
                                <label for="tel">Telphone Number</label>
                                <input type="tel" name="tel" size="9" placeholder="00-000000" pattern="[0-9]{2}-[0-9]{3}[0-9]{3}" class="border p-3 w-100 my-2" required>
                              </div>
                              <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" name="address" maxlength="300" minlength="4" placeholder="Address" class="border p-3 w-100 my-2" required>
                              </div>
                              <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" placeholder="Username" maxlength="20" minlength="4" class="border p-3 w-100 my-2" required>
                              </div>
                              <div class="form-group">
                                <label for="pass">Password</label>
                                <input type="password" name="pass" onkeyup="validate()" placeholder="Password" maxlength="20" minlength="3" class="border p-3 w-100 my-2" required>
                              </div>
                              <div class="form-group">
                                <label for="c-pass">Confirm Password</label>
                                <input type="password" name="conf-pass" placeholder="Confirm Password" maxlength="20" minlength="3" class="border p-3 w-100 my-2" required>
                              </div>  
                                <div class="loggedin-forgot d-inline-flex my-3">
                                        <!--<input type="checkbox" id="registering" class="mt-1">
                                        <label for="registering" class="px-2">By registering, you accept our <a class="text-primary font-weight-bold" href="terms-condition.php">Terms & Conditions</a></label>
                                      -->
                                </div>
                                <button name="submit" id="submit" type="submit" class="d-block py-3 px-4 bg-primary text-white border-0 rounded font-weight-bold">Register Now</button>
                                <a class="mt-3 d-inline-block text-primary" href="login.php">Do you have an account? Login</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!--============================
=            Footer            =
=============================-->
<!-- Footer Bottom -->

<script>

//set max dob date
// var gt_18 = new Date();
// gt_18.setDate(gt_18.getDate() - 6570);
// document.getElementById("dob").max = gt_18.toISOString().slice(0, 10);

function validate(){
  var pass1 = document.getElementById("pass");
  var pass2 = document.getElementById("conf-pass");
  pass2.setAttribute("pattern",pass1.value);
}

// function register(){
//   if(document.forms["form_val"].checkValidity()){
//     const toSend = {
//           userName: document.getElementById('username').value,
//         	password: document.getElementById('pass').value,
//           f_name: document.getElementById('first-name').value,
//         	l_name: document.getElementById('last-name').value,
//           tel: document.getElementById('tel').value,
//         	addres: document.getElementById('address').value,
//           dob: document.getElementById('dob').value,
//         	exp_dl: document.getElementById('license-expiration-date').value,
//           driving_license: document.getElementById('driving-license').value
//             };
//     const josnString = JSON.stringify(toSend);
//     const xhr = new XMLHttpRequest();
//     xhr.onreadystatechange = function() {
//         if (this.readyState == 4 && this.status == 200) {
//           if((this.responseText).trim() == "Data inserted successfully"){
//             location.href = 'login.php?r=Registration';
//           }
//           else{
//             Swal.fire({
//                 icon: 'error',
//                 text: this.responseText,
//                 confirmButtonText: "OK",
//             });
//           }
//         }
//     };
//     xhr.open("POST","<?php echo $UsersInsert; ?>", true);
//     xhr.setRequestHeader('Content-Type', 'application/json; charset = UTF-8');
//     xhr.send(josnString);
//     return false;
//   }
// }
</script>

<?php require_once('footer.php'); ?>

</body>

</html>