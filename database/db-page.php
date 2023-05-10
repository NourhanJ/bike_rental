<?php
    $server_db = "http://localhost/bike_rental/database/";
    
    ///////////////////////user////////////////////////////////////////

    //login
    $SelectUserForLogin = $server_db."user/2_1_SelectUserForLogin.php";

    //register
    $UsersInsert = $server_db."user/1_1_UsersInsert.php";

    //forget password
    $CheckForUpdatePassword = $server_db."user/2_2_CheckForUpdatePassword.php";
    $UserPasswordUpdate = $server_db."user/3_1_UserPasswordUpdate.php";

    //request
    $RequestUpdate = $server_db."user/3_2_RequestUpdate.php";

    //user-profile
    $UsersUpdate = $server_db."user/3_3_UsersUpdate.php";

    //single bike
    $RequestInsert = $server_db."user/1_2_RequestInsert.php";
    $FeedbackInsert = $server_db."user/1_3_FeedbackInsert.php";

    /////////////////////////admin//////////////////////////////

    //login
    $SelectForLogin = $server_db."admin/2_1_SelectForLogin.php";

?>