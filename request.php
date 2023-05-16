<!DOCTYPE html>
<html lang="en">
<head>

<?php require_once('header.php'); ?>

<!--==================================
=            User Profile            =
===================================-->

<?php
REQUIRE_ONCE('database/db/0_Connection.php');

//check if user is logged in
if(!isset($_COOKIE['CR-userID']) || empty($_COOKIE['CR-userID'])){
  print "<script>location.replace(\"login.php\");</script>";
}

$filter_status = 'All';
if (isset($_GET['s']) && !empty($_GET['s'])){
  $filter_status = $_GET['s'];
}

$req_count = [0,0,0,0];
$w_req = array();
$a_req = array();
$h_req = array();

//get all requests from database
// if($temp = @mysqli_query($conn, "CALL SelectAllRequestProcedure('".$_COOKIE['CR-userID']."')")){
  if($temp = @mysqli_query($conn, "SELECT * FROM request LEFT JOIN bike ON bike.id_bike = request.id_bike LEFT JOIN bike_accessories ON bike_accessories.id_accessory = request.id_accessory JOIN users ON request.id_user = users.id_user WHERE request.id_user = '".$_COOKIE['CR-userID']."' GROUP BY request.id_request;")){
  while($result = @mysqli_fetch_assoc($temp))
    if ($result != null) {
      $req_count[0]++;
      switch ($result['request_status']) {
        case 0:
          $w_req[] = $result;
          $req_count[1]++;
          break;
        case 1:
          $a_req[] = $result;
          $req_count[2]++;
          break;
        case 4:
          $a_req[] = $result;
          $req_count[2]++;
          break;
        default:
          $h_req[] = $result;
          $req_count[3]++;
      }
    }
}

function filter($list){
  //car name
  if(isset($_GET['carName']) && !empty($_GET['carName'])){
    if (strpos($list['car_name'], $_GET['carName']) === FALSE)
      return false;
  }
  return true;
}
?>

<section class="dashboard section">
  <!-- Container Start -->
  <div class="container">
    <!-- Row Start -->
    <div class="row">
      <div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
        <div class="sidebar">
          <!-- User Widget -->
          <div class="widget user-dashboard-profile">
            <!-- User Image -->
            <div class="profile-thumb">
              <img src="../images/user/avatar.png" alt="" class="rounded-circle">
            </div>
            <!-- User Name -->
            <?php 
            @mysqli_next_result($conn);
            if($temp = @mysqli_query($conn, "CALL SelectNameDateUserProcedure('".$_COOKIE['CR-userID']."')")){
              while($result = @mysqli_fetch_assoc($temp))
                if ($result != null) {
                  extract($result);
                  $joined_date = strtotime($creation_date);
                  $j_d = date('d, M Y', $joined_date);
                  print '
                        <h5 class="text-center">'.$f_name.' '.$l_name.'</h5>
                        <p class="text-center">Joined '.$j_d.'</p>
                        ';
                }
            }
            ?>
            <a href="user-profile.php" class="btn btn-main-sm">Edit Profile</a>
          </div>
          <!-- Dashboard Links -->
          <div class="widget user-dashboard-menu">
            <ul>
              <li <?php if($filter_status == 'All') print 'class="active"'; ?> ><a href="request.php"><i class="fa fa-user"></i>All Requests<span><?php echo $req_count[0]; ?></span></a></li>
              <li <?php if($filter_status == 'w') print 'class="active"'; ?>><a href='request.php?s=w'><i class="fa fa-clock-o"></i>Requests Waiting<span><?php echo $req_count[1]; ?></span></a></li>
              <li <?php if($filter_status == 'a') print 'class="active"'; ?>><a href="request.php?s=a"><i class="fa fa-bolt"></i>Requests Accept<span><?php echo $req_count[2]; ?></span></a></li>
              <li <?php if($filter_status == 'h') print 'class="active"'; ?>><a href="request.php?s=h"><i class="fa fa-file-archive-o"></i>Requests History<span><?php echo $req_count[3]; ?></span></a></li>
            </ul>
          </div>
        </div>
      </div>


      <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
        <!-- Recently Favorited -->
        <div class="widget dashboard-container my-adslist">
          <h3 class="widget-header" id="d-selected">My Requests</h3>
          <table class="table table-responsive product-dashboard-table">
            <thead>
              <tr>
                <th>Image</th>
                <th>Details</th>
                <th class="text-center">Brand</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
            <?php 
              $put_req = array();
              $c = 0;
              if(!isset($_GET['s']) || empty($_GET['s'])){
                $put_req = array_merge($w_req,$a_req,$h_req);
                $c = $req_count[0];
              }
              else{
                switch($_GET['s']){
                  case 'w':
                    $put_req = $w_req;
                    $c = $req_count[1];
                    break;
                  case 'a':
                    $put_req = $a_req;
                    $c = $req_count[2];
                    break;
                  case 'h':
                    $put_req = $h_req;
                    $c = $req_count[3];
                    break;
                  default:
                    $put_req = array_merge($w_req,$a_req,$h_req);
                    $c = $req_count[0];
                }
              }
              
              for($i=0;$i<$c;$i++){

                if(filter($put_req[$i])){
                    
                  extract($put_req[$i]);

                  $st = 'waiting';
                  $color = 'red';
                  if($request_status == 1){
                    $st = 'active';
                    $color = 'green';
                  }
                  else if($request_status == 2){
                    $st = 'finished';
                    $color = 'blue';
                  }
                  else if($request_status == 3){
                    $st = 'rejected';
                    $color = 'red';
                  }
                  else if($request_status == 4){
                    $st = 'accepted';
                    $color = 'green';
                  }

                  if($request_type == "bike"){
                    $single = "single.php?id_bike=" . $id_bike;
                    $brand = $bike_brand;
                    $image = $bike_image;
                  }
                  else{
                    $single = "single_accessory.php?id_accessory=" . $id_accessory;
                    $brand = $accessory_brand;
                    $image = $accessory_image;
                  }

                  $body_req = '
                    <tr>
                      <td class="product-thumb">
                        <img width="90px" height="auto" src="assets/'.$image.'" alt="image description"></td>
                      <td class="product-details">
                        <h3 class="title"> '.$bike_name.'</h3>
                        <span class="add-id"><strong> Date:</strong><time>'.$reservation_date.'</time></span>
                        <span class="location"><strong> Rental day:</strong>'.$nb_rent_days.'</span>
                        <span><strong> Total price: </strong>'.$total_price.'$</span>
                        <span style="color:'.$color.';"><strong> Status:</strong>'.$st.'</span>
                      </td>
                      <td class="product-category"><span class="categories">'.$brand.'</span></td>
                      <td class="action" data-title="Action">
                        <div class="">
                          <ul class="list-inline justify-content-center">
                            <li class="list-inline-item">
                              <a data-toggle="tooltip" data-placement="top" title="view" class="view" href="'.$single.'">
                                <i class="fa fa-eye"></i>
                              </a>
                            </li>';
                            if($request_status == 0){
                              $body_req.= '
                              <li class="list-inline-item">
                                <a class="delete" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return delete_conf('.$id_request.','.$request_status.')" href="#">
                                  <i class="fa fa-trash"></i>
                                </a>
                              </li>
                              ';
                            }
                            $body_req.='
                          </ul>
                        </div>
                      </td>
                    </tr>
                  ';
                  print $body_req;
                }
              }
            ?>
              
            </tbody>
          </table>

        </div>

      </div>
    </div>
    <!-- Row End -->
  </div>
  <!-- Container End -->
</section>
<!--============================
=            Footer            =
=============================-->

<?php require_once('footer.php'); ?>

<script>
function delete_conf(requestID,req_st){
  Swal.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#d33',
  cancelButtonColor: '#3085d6',
  confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      const toSend = {
            reqID: requestID,
            reqST: req_st
              };
      const josnString = JSON.stringify(toSend);
      const xhr = new XMLHttpRequest();
      xhr.open("POST","<?php echo $RequestUpdate; ?>", false);
      xhr.setRequestHeader('Content-Type', 'application/json; charset = UTF-8');
      xhr.send(josnString);
      location.reload(); 
      return false;
    }
  });
}

function search_filter(f_type){
  if(f_type == 'All'){
    location.href= "request.php?carName=" + document.getElementById('search_text').value;
  }
  else {
    location.href= "request.php?s="+f_type+"&carName=" + document.getElementById('search_text').value;
  }
  return false;
}
</script>

</body>

</html>