<!DOCTYPE html>
<html lang="en">
<head>

<?php
    require_once('header.php'); //include the 'header.php' file

    //check if user is logged in
    if(!isset($_COOKIE['CR-userID']) || empty($_COOKIE['CR-userID'])){
        //redirect the user to the login page
        print "<script>location.replace(\"login.php\");</script>";
    }
    //if the form is submitted
    else if (isset($_POST['submit'])){
        extract($_POST); //extract form data into separate variables

        $echo_msg = ""; //initialize an empty variable for storing error or success message

        $userid = $_COOKIE['CR-userID']; //get user id from cookies

        $filename = $_FILES["file"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
        $file_ext = substr($filename, strripos($filename, '.')); // get file name
        
        $filesize = $_FILES["file"]["size"];
        $allowed_file_types = array('.jpg','.png','.jpeg','.gif');

        if (in_array($file_ext,$allowed_file_types) && ($filesize < 200000))
        {	
            // Rename file
            $newfilename = "image-" . time() . $file_ext;

            move_uploaded_file($_FILES["file"]["tmp_name"], "assets/" . $newfilename);
            $echo_msg = "File uploaded successfully.";

            //insert to database
            REQUIRE_ONCE('database/db/0_Connection.php');
            if($temp = @mysqli_query($conn, "CALL bikeInsertProcedure('$bikeName','$BrandType','$itemTr', '$wheel', '$color', '$newfilename','$desc','$rpd', '$stock', '$start_age', '$end_age', '$userid')")){
                while($result = @mysqli_fetch_assoc($temp))
                    if ($result != null) {
                        print "<script>location.replace(\"single.php?id_bike=".$result['LAST_INSERT_ID()']."\");</script>";
                    }
            }
            else $echo_msg = $conn->error;
            
        }
        elseif (empty($file_basename))
        {	
            // file selection error
            $echo_msg = "Please select a file to upload.";
        } 
        elseif ($filesize > 200000)
        {	
            // file size error
            $echo_msg = "The file you are trying to upload is too large.";
        }
        else
        {
            // file type error
            $echo_msg = "Only these file typs are allowed for upload: " . implode(', ',$allowed_file_types);
            unlink($_FILES["file"]["tmp_name"]);
        }

        //display an error message using the SweetAlert library
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
                            <h3>Post Your New Bike</h3>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="font-weight-bold pt-4 pb-1">Bike Name:</h6>
                            <input type="text" class="border-0 w-100 p-2 bg-white text-capitalize" name="bikeName" id="bikeName" minlength="2" maxlength="50" placeholder="Bike Name" required>
                            <h6 class="font-weight-bold pt-4 pb-1">Frame Material:</h6>
                            <div class="row px-3">
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white">
                                    <input type="radio" name="itemTr" value="Aluminum" id="Aluminum" style="cursor:pointer;" checked>
                                    <label for="Aluminum" class="py-2" style="cursor:pointer;">Aluminum</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="Fiber" id="Fiber" style="cursor:pointer;">
                                    <label for="Fiber" class="py-2" style="cursor:pointer;">bikebon Fiber</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="Steel" id="Steel" style="cursor:pointer;">
                                    <label for="Steel" class="py-2" style="cursor:pointer;">Steel</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="Titanium" id="Titanium" style="cursor:pointer;">
                                    <label for="Titanium" class="py-2" style="cursor:pointer;">Titanium</label>
                                </div>
                            </div>
                            <h6 class="font-weight-bold pt-4 pb-1">Select Bike Color:</h6>
                            <select name="color" id="color" class="border-0 w-100 bg-white text-capitalize">
                                <option value="Red">Red</option>
                                <option value="Blue">Blue</option>
                                <option value="Green">Green</option>
                                <option value="Black">Black</option>
                                <option value="White">White</option>
                                <option value="other">Other</option>
                            </select>
                            <h6 class="font-weight-bold pt-4 pb-1">Description:</h6>
                            <textarea name="desc" id="desc" minlength="20" maxlength="300" class="border-0 p-3 w-100" rows="7" placeholder="Write details about your vehicle" required></textarea>
                            <div class="choose-file text-center pt-4 pb-1 rounded">
                                <label for="file">
                                    <span class="d-block font-weight-bold text-dark">bike Image</span>
                                    <span class="d-block btn bg-primary text-white my-3 select-files ">Select image</span>
                                    <span class="d-block" id="fileName">You can select only one image.</span>
                                    <input type="file" accept="image/*" size="20" onchange="pop_name()" class="form-control-file d-none" id="file" name="file" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="font-weight-bold pt-4 pb-1">Select Bike Brand:</h6>
                            <select name="BrandType" id="BrandType" class="border-0 w-100 bg-white text-capitalize">
                                <option value="Trek">Trek</option>
                                <option value="Specialized">Specialized</option>
                                <option value="Giant">Giant</option>
                                <option value="Cannondale">Cannondale</option>
                                <option value="Scott">Scott</option>
                                <option value="Santa Cruz">Santa Cruz</option>
                                <option value="other">Other</option>
                            </select>

                            <h6 class="font-weight-bold pt-4 pb-1">Wheel Size(inch):</h6>
                            <input type="number" name="wheel" id="wheel" class="border-0 w-100 p-2 bg-white text-capitalize" value="26" required>
                            
                            <h6 class="font-weight-bold pt-4 pb-1" style="margin-top:3%">Daily Rental Price ($ USD):</h6>
                            <input type="number" name="rpd" id="rpd" class="border-0 w-100 p-2 bg-white text-capitalize" min="1" max="200" value="30" required>

                            <h6 class="font-weight-bold pt-4 pb-1" style="margin-top:3%">Stock Quantity:</h6>
                            <input type="number" name="stock" id="stock" class="border-0 w-100 p-2 bg-white text-capitalize" min="1" max="200" value="30" required>

                            <br>
                            <h4 class="widget-header">Age Range:</h4>
                            From: <input type="number" name="start_age" id="stock" class="border-0 p-2 bg-white text-capitalize" min="1" max="200" value="30" required>
                            To: <input type="number" name="end_age" id="stock" class="border-0 p-2 bg-white text-capitalize" min="1" max="200" value="30" required>
                        </div>
                    </div>
            </fieldset>
            <!-- Post Your ad end -->

            <!-- submit button -->
            <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary d-block mt-2">Post Your Bike</button>
        </form>
    </div>
</section>

<!--============================
=            Footer            =
=============================-->
<?php require_once('footer.php'); ?>

<script>
function pop_name(){
  var x = document.getElementById("file");
  var txt = "";
  if ('files' in x) {
    if (x.files.length == 0) {
      txt = "You can select only one image.";
    } else {
      for (var i = 0; i < x.files.length; i++) {
        //txt += "<br><strong>" + (i+1) + ". file</strong><br>";
        var file = x.files[i];
        if ('name' in file) {
            txt += "name: " + file.name;
        }
        if ('size' in file) {
          txt += ", size: " + file.size + " bytes";
        }
      }
    }
  } 
  else {
    if (x.value == "") {
      txt += "Select one or more files.";
    } else {
      txt += "The files property is not supported by your browser!";
      txt  += "<br>The path of the selected file: " + x.value; // If the browser does not support the files property, it will return the path of the selected file instead. 
    }
  }
  document.getElementById("fileName").innerHTML = txt;
}
</script>

</body>

</html>