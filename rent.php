<!DOCTYPE html>
<html lang="en">
<head>

<?php
    require_once('header.php'); 

    $bike_id = 0;
    $total = 0;
    if(isset($_GET['id'])){
        $bike_id = $_GET['id'];
    }
    
    if(!isset($_COOKIE['CR-userID']) || empty($_COOKIE['CR-userID'])){
        header("Location:login.php");
    }
    else if (isset($_POST['submit'])){
        $userid = $_COOKIE['CR-userID'];
        extract($_POST);

        $echo_msg = "";
        if(isset($_GET['p'])){
            $total = $_GET['p'] * $rent_days;
        }

        //insert to database
        REQUIRE_ONCE('database/db/0_Connection.php');
        $query = "INSERT INTO request (`id_user`, `id_bike`, `request_type`, `reservation_date`, `nb_rent_days`, `total_price`) VALUES ('$userid', '$bike_id', 'bike', '$reservation', '$rent_days', '$total')";
        include('database/db/0_Connection.php');
        if(mysqli_query($conn, $query)){
            print "<script>location.replace(\"request.php\");</script>";
            // header("Location:request.php");
        }
        else{
            echo "Error: " . mysqli_error($conn);
        }
        // if($temp = @mysqli_query($conn, "CALL RequestInsertProcedure('$userid','$bike_id','$reservation', '$rent_days', '$total')")){
        //     while($result = @mysqli_fetch_assoc($temp))
        //         if ($result != null) {
        //             print "<script>location.replace(\"reguest.php?id_bike=".$result['LAST_INSERT_ID()']."\");</script>";
        //         }
        // }
        // else $echo_msg = $conn->error;

        echo "<script>
            Swal.fire({
                icon: 'error',
                text: '".$echo_msg."',
                confirmButtonText: 'OK'
            });
        </script>";
    }

?>

<section class="bg-gray py-5">
    <div class="container">
        <form action="" enctype="multipart/form-data" method="POST">
            <!-- Post Your ad start -->
            <fieldset class="border border-gary p-4 mb-5">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3>Rent Bike</h3>
                        </div>
                        <div class="col-lg-6">
                            <input type="hidden" name="price" id="price" value="<?php echo $_GET['p']; ?>" />
                            <h6 class="font-weight-bold pt-4 pb-1">Reservation Date:</h6>
                            <input type="date" name="reservation" class="border p-3 w-100 my-2" required>
                            <h6 class="font-weight-bold pt-4 pb-1">Number of days:</h6>
                            <input type="number" name="rent_days" id="rent_days" class="border-0 w-100 p-2 bg-white text-capitalize" value="1" required>
                            Total = <span class="totalprice">0</span>
                        </div>
                    </div>
            </fieldset>
            <!-- Post Your ad end -->

            <!-- submit button -->
            <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary d-block mt-2">Rent Bike</button>
        </form>
    </div>
</section>

<!--============================
=            Footer            =
=============================-->
<?php require_once('footer.php'); ?>

<script>
    $(document).ready(function() {
  // Attach an event handler to the "change" event of the input field
  $('#rent_days').on('change', function() {
    // Get the new value of the input field
    var rentDays = $(this).val();

    // Calculate the new total price based on the rent days value (replace this with your actual calculation logic)
    var totalPrice = rentDays * $('#price').val();

    // Update the text of the span element with the new total price
    $('.totalprice').text(totalPrice);
  });
});

</script>

</body>

</html>