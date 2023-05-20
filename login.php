<?php
require_once('database/db-page.php');
if(isset($_COOKIE['CR-userID']) && !empty($_COOKIE['CR-userID'])){
    header("Location:index.php");
}    
else if(isset($_GET['submit'])){
    $username = $_GET['username'];
    $password = $_GET['password'];
    echo $password;
    echo '<br />';

    // $query = "SELECT * FROM users WHERE users.username = ".$username;
    include('database/db/0_Connection.php');
    $user = $conn->query("SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . md5($_GET['password']) . "'")->fetch_assoc();

    // echo md5($password);
    // echo '<br />';
    // echo $user['password'];
    
    if($user != null){
        // $userID = mysqli_insert_id($conn);
        setcookie('CR-userID', $user['id_user'], time() + (24*60*60*1000), "/");
        setcookie('CR-userAGE', $user['age'], time() + (24*60*60*1000), "/");
        // Passwords match, allow user to log in
        header("Location:index.php");
        
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
  <title>bikeRental</title>
  
  <!-- LOGO -->
  <link href="images/logo.png" rel="shortcut icon">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap-slider.css">
  <!-- Font Awesome -->
  <link href="plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- Owl bikeousel -->
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

<?php
if(isset($_GET['r'])){
    print"
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
        icon: 'success',
        title: '".$_GET['r']." Successfully'
        });
    </script>";
}
?>

<section class="login py-5 border-top-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8 align-item-center">
                <div class="border">
                    <h3 class="bg-gray p-4">Login Now</h3>
                    <form action="#" id="form_val" method="GET">
                        <!-- User Image -->
                        <div class="image d-flex justify-content-center">
                          <img src="images/logo.png" style="width:50%;margin-top:5%;" alt="" class="">
                        </div>
                        
                        <fieldset class="p-4">
                            <input type="text" name="username" maxlength="20" minlength="4" placeholder="Username" class="border p-3 w-100 my-2" required>
                            <input type="password" name="password" maxlength="20" minlength="3" placeholder="Password" class="border p-3 w-100 my-2" required>
                            <!--<div class="loggedin-forgot">
                                    <input type="checkbox" id="keep-me-logged-in">
                                    <label for="keep-me-logged-in" class="pt-3 pb-2">Keep me logged in</label>
                            </div>-->
                            <button name="submit" class="d-block py-3 px-5 bg-primary text-white border-0 rounded font-weight-bold mt-3">Log in</button>
                            <a class="mt-3 d-block  text-primary" href="forget-password.php">Forget Password?</a>
                            <a class="mt-3 d-inline-block text-primary" href="register.php">Register Now</a>
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

<?php require_once('footer.php'); ?>

<script>
// function login(){
//     var u_v = document.getElementById('username');
//     var p_v = document.getElementById('password');
//     if(document.forms["form_val"].checkValidity()){
//         const toSend = {
//                 userID: document.getElementById('username').value,
//                 pass: document.getElementById('password').value
//                 };
//         const josnString = JSON.stringify(toSend);
//         const xhr = new XMLHttpRequest();
//         xhr.onreadystatechange = function() {
//             if (this.readyState == 4 && this.status == 200) {
//                 try{    
//                     var db = JSON.parse(this.responseText);
//                     var d = new Date();
//                     d.setTime(d.getTime() + (24*60*60*1000));
//                     document.cookie = "CR-userID = " + db['id_user'] + "; expires = " + d.toUTCString();
//                     location.href = 'index.php';
//                 }
//                 catch{
//                     Swal.fire({
//                         icon: 'error',
//                         text: "Invalid Username or Password",
//                         confirmButtonText: "OK"
//                     });
//                 }
//             }
//         };
//         xhr.open("POST","<?php echo $SelectUserForLogin; ?>", true);
//         xhr.setRequestHeader('Content-Type', 'application/json; charset = UTF-8');
//         xhr.send(josnString);

//         return false;
//     }
// }
</script>

</body>

</html>