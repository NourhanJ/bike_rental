<!DOCTYPE html>
<html lang="en">
<head>

<?php
    require_once('header.php'); 

    //check if user is logged in
    if(!isset($_COOKIE['CR-userID']) || empty($_COOKIE['CR-userID'])){
        //redirect to login if not logged in
        print "<script>location.replace(\"login.php\");</script>";
    }
    else if (isset($_POST['submit'])){
        //extract values from POST request
        extract($_POST);

        $echo_msg = "";

        //Get user ID from cookie
        $userid = $_COOKIE['CR-userID'];

        //Retrieve file information
        $filename = $_FILES["file"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
        $file_ext = substr($filename, strripos($filename, '.')); // get file name
        
        $filesize = $_FILES["file"]["size"];
        $allowed_file_types = array('.jpg','.png','.jpeg','.gif');

        if (in_array($file_ext,$allowed_file_types) && ($filesize < 200000))
        {	
            // Rename file
            $newfilename = "image-" . time() . $file_ext;

            //move uploaded file to destination folder
            move_uploaded_file($_FILES["file"]["tmp_name"], "assets/" . $newfilename);
            $echo_msg = "File uploaded successfully.";

            //insert data into database
            REQUIRE_ONCE('database/db/0_Connection.php');
            if($temp = @mysqli_query($conn, "CALL AccessoryInsertProcedure('$accessoryName', '$desc', '$itemTr', '$rpd', '$color', '$newfilename', '$stock', '$userid', '$BrandType')")){
                while($result = @mysqli_fetch_assoc($temp))
                    if ($result != null) {
                        //redirect to accessories details page with the ID
                        print "<script>location.replace(\"single_accessory.php?id_accessory=".$result['LAST_INSERT_ID()']."\");</script>";
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

        //Display error message using SweetAlert library
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
                            <h3>Post Your New Accessory</h3>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="font-weight-bold pt-4 pb-1">Accessory Name:</h6>
                            <input type="text" class="border-0 w-100 p-2 bg-white text-capitalize" name="accessoryName" id="accessoryName" minlength="2" maxlength="50" placeholder="Accessory Name" required>
                            <h6 class="font-weight-bold pt-4 pb-1">Category:</h6>
                            <div class="row px-3">
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white">
                                    <input type="radio" name="itemTr" value="lights" id="lights" style="cursor:pointer;" checked>
                                    <label for="lights" class="py-2" style="cursor:pointer;">lights</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="locks" id="locks" style="cursor:pointer;">
                                    <label for="locks" class="py-2" style="cursor:pointer;">locks</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="helmets" id="helmets" style="cursor:pointer;">
                                    <label for="helmets" class="py-2" style="cursor:pointer;">helmets</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="bottle" id="bottle" style="cursor:pointer;">
                                    <label for="bottle" class="py-2" style="cursor:pointer;">Water bottle cage</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="other" id="other" style="cursor:pointer;">
                                    <label for="other" class="py-2" style="cursor:pointer;">other</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white">
                                    <input type="radio" name="itemTr" value="Panniers" id="Panniers" style="cursor:pointer;" checked>
                                    <label for="Panniers" class="py-2" style="cursor:pointer;">Panniers</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="kit" id="kit" style="cursor:pointer;">
                                    <label for="kit" class="py-2" style="cursor:pointer;">Repair kit</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="Pump" id="Pump" style="cursor:pointer;">
                                    <label for="Pump" class="py-2" style="cursor:pointer;">Pump</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="Phone" id="Phone" style="cursor:pointer;">
                                    <label for="Phone" class="py-2" style="cursor:pointer;">Phone mount</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="Fenders" id="Fenders" style="cursor:pointer;">
                                    <label for="Fenders" class="py-2" style="cursor:pointer;">Fenders</label>
                                </div>
                                <div class="col-lg-4 mr-lg-4 my-2 rounded bg-white ">
                                    <input type="radio" name="itemTr" value="Kickstand" id="Kickstand" style="cursor:pointer;">
                                    <label for="Kickstand" class="py-2" style="cursor:pointer;">Kickstand</label>
                                </div>
                            </div>
                            <h6 class="font-weight-bold pt-4 pb-1">Select Accessory Color:</h6>
                            <select name="color" id="color" class="border-0 w-100 bg-white text-capitalize">
                                <option value="Red">Red</option>
                                <option value="Blue">Blue</option>
                                <option value="Green">Green</option>
                                <option value="Black">Black</option>
                                <option value="White">White</option>
                                <option value="other">Other</option>
                            </select>
                            <h6 class="font-weight-bold pt-4 pb-1">Description:</h6>
                            <textarea name="desc" id="desc" minlength="20" maxlength="300" class="border-0 p-3 w-100" rows="7" placeholder="Write details about your accessory" required></textarea>
                            <div class="choose-file text-center pt-4 pb-1 rounded">
                                <label for="file">
                                    <span class="d-block font-weight-bold text-dark">Accessory Image</span>
                                    <span class="d-block btn bg-primary text-white my-3 select-files ">Select image</span>
                                    <span class="d-block" id="fileName">You can select only one image.</span>
                                    <input type="file" accept="image/*" size="20" onchange="pop_name()" class="form-control-file d-none" id="file" name="file" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="font-weight-bold pt-4 pb-1">Select Brand:</h6>
                            <select name="BrandType" id="BrandType" class="border-0 w-100 bg-white text-capitalize">
                                <option value="GearMaster">GearMaster</option>
                                <option value="CycleTech">CycleTech</option>
                                <option value="RidePro">RidePro</option>
                                <option value="PedalPower">PedalPower</option>
                                <option value="SwiftRider">SwiftRider</option>
                                <option value="AeroFit">AeroFit</option>
                                <option value="other">Other</option>
                            </select>
                            
                            <h6 class="font-weight-bold pt-4 pb-1" style="margin-top:3%">Daily Rental Price ($ USD):</h6>
                            <input type="number" name="rpd" id="rpd" class="border-0 w-100 p-2 bg-white text-capitalize" min="1" max="200" value="30" required>

                            <h6 class="font-weight-bold pt-4 pb-1" style="margin-top:3%">Stock Quantity:</h6>
                            <input type="number" name="stock" id="stock" class="border-0 w-100 p-2 bg-white text-capitalize" min="1" max="200" value="30" required>
                        </div>
                    </div>
            </fieldset>
            <!-- Post Your ad end -->

            <!-- submit button -->
            <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary d-block mt-2">Post Your Accessory</button>
        </form>
    </div>
</section>

<!--============================
=            Footer            =
=============================-->
<?php require_once('footer.php'); ?>

<script>
function pop_name(){
  var x = document.getElementById("file"); //Get the file input element
  var txt = ""; //Initialize an empty string for storing the file info
  if ('files' in x) {
    //check if files property is supported by browser
    if (x.files.length == 0) {
      txt = "You need to select an image."; //display a message if no files were selected
    } else {
        //Iterate through the selected files
      for (var i = 0; i < x.files.length; i++) {
        var file = x.files[i]; //get the current file
        if ('name' in file) {
            txt += "name: " + file.name; //append file name to the output
        }
        if ('size' in file) {
          txt += ", size: " + file.size + " bytes"; //append file size
        }
      }
    }
  } 
  else {
    //the files property is not supported by the browser
    if (x.value == "") {
      txt += "Select one or more files."; //display a message to select file
    } else {
      txt += "The files property is not supported by your browser!";
      txt  += "<br>The path of the selected file: " + x.value; // If the browser does not support the files property, it will return the path of the selected file instead. 
    }
  }
  //update the content of the HTML element with id "fileName" to display the file information
  document.getElementById("fileName").innerHTML = txt;
}
</script>

</body>

</html>