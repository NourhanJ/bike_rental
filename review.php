<!DOCTYPE html>
<html lang="en">
<head>

<?php require_once('header.php'); ?>

<!--==================================
=            User Profile            =
===================================-->


<?php
REQUIRE_ONCE('database/db/0_Connection.php');

if(!isset($_COOKIE['CR-userID']) || empty($_COOKIE['CR-userID'])){
  print "<script>location.replace(\"login.php\");</script>";
}

$filter_status = 'All';
if (isset($_GET['s']) && !empty($_GET['s'])){
  $filter_status = $_GET['s'];
}

if(isset($_GET['request']) && isset($_GET['status'])){
    $query = "UPDATE request SET request_status = ". $_GET['status'] ." WHERE id_request = " . $_GET['request'];

    if ($conn->query($query) === TRUE) {
        if($temp = @mysqli_query($conn, "CALL SelectSinglebikeProcedure('".$id_bike."')")){
            while($result = @mysqli_fetch_assoc($temp)){
              if ($result != null) {
                  extract($result);
                  $s = $stock - 1;
                  if($temp = @mysqli_query($conn, "CALL bikeStockUpdateProcedure('".$id_bike.", '".$s."')")){
                    "<script>location.replace(\"review.php\");</script>";
                  }
              }
            }
        }
        
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$req_count = [0,0,0,0];
$w_req = array();
$a_req = array();
$h_req = array();

// if($temp = @mysqli_query($conn, "CALL SelectReviewRequestProcedure('".$_COOKIE['CR-userID']."')")){
if($temp = @mysqli_query($conn, "SELECT DISTINCT * FROM request JOIN users ON request.id_user = users.id_user JOIN bike ON request.id_bike = bike.id_bike WHERE bike.owner_id = '".$_COOKIE['CR-userID']."';")){
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

<section class="page-search">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- Advance Search -->
				<div class="advance-search">
					<form action="#" id="form_search">
						<div class="form-row">
							<div class="form-group col-md-10">
								<input type="text" class="form-control my-2 my-lg-1" id="search_text" placeholder="What are you looking for..">
							</div>
							<div class="form-group col-md-2 align-self-center">
								<button onclick="return search_filter('<?php echo $filter_status; ?>')" class="btn btn-primary">Search Now</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

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
              <img src="../images/user/vehicle.png" alt="" class="rounded-circle">
            </div>
            <!-- add vehicle -->
            <h5 class="text-center">Add Your New Bike</h5>
            <a href="add-bike.php" class="btn btn-main-sm">Add Bike</a>
          </div>
          <!-- Dashboard Links -->
          <div class="widget user-dashboard-menu">
            <ul>
              <li <?php if($filter_status == 'All') print 'class="active"'; ?> ><a href="review.php"><i class="fa fa-user"></i>All Requests<span><?php echo $req_count[0]; ?></span></a></li>
              <li <?php if($filter_status == 'w') print 'class="active"'; ?>><a href='review.php?s=w'><i class="fa fa-clock-o"></i>Requests Waiting<span><?php echo $req_count[1]; ?></span></a></li>
              <li <?php if($filter_status == 'a') print 'class="active"'; ?>><a href="review.php?s=a"><i class="fa fa-bolt"></i>Requests Accept<span><?php echo $req_count[2]; ?></span></a></li>
              <li <?php if($filter_status == 'h') print 'class="active"'; ?>><a href="review.php?s=h"><i class="fa fa-file-archive-o"></i>Requests History<span><?php echo $req_count[3]; ?></span></a></li>
            </ul>
          </div>
        </div>
      </div>


      <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
        <!-- Recently Favorited -->
        <div class="widget dashboard-container my-adslist">
          <h3 class="widget-header" id="d-selected">All Requests</h3>
          <table class="table table-responsive product-dashboard-table">
            <thead>
              <tr>
                <th>Image</th>
                <th>Details</th>
                <th class="text-center">Tenant</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              
            <?php 
              $put_req = array();
              $c = 0;
              if($filter_status == 'All'){
                $put_req = array_merge($w_req,$a_req,$h_req);
                $c = $req_count[0];
              }
              else{
                switch($filter_status){
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

                  print '
                    <tr id=req-'.$id_request.'>
                      <td class="product-thumb">
                        <img width="90px" height="auto" src="assets/'.$image.'" alt="image description"></td>
                      <td class="product-details">
                        <a href="single.php?id_bike='.$id_bike.'"><h3 class="title"> '.$bike_name.'</h3></a>
                        <span class="add-id"><strong> Date:</strong><time>'.$reservation_date.'</time></span>
                        <span class="location"><strong> Rental day:</strong>'.$nb_rent_days.'</span>
                        <span><strong> Total price: </strong>'.$total_price.'$</span>
                        <span style="color:'.$color.';"><strong> Status:</strong>'.$st.'</span>
                      </td>
                      <td class="product-category"><a href="user-profile.php?id_user='.$id_user.'"><span class="categories">'.$f_name.' '.$l_name.'</span></a></td>
                      <td class="action" data-title="Action">
                        <div class="">
                          <ul class="list-inline justify-content-center">';
                            if($request_status == 0){
                              print '
                              <li class="list-inline-item">
                                <a class="edit" data-toggle="tooltip" data-placement="top" title="Accept" href="review.php?request='.$id_request.'&status=4">
                                  <i class="fa fa-check"></i>
                                </a>
                              </li>
                              <li class="list-inline-item">
                                <a class="delete" data-toggle="tooltip" data-placement="top" title="Reject" href="review.php?request='.$id_request.'&status=3">
                                  <i class="fa fa-trash"></i>
                                </a>
                              </li>
                              ';
                            }
                            else if($request_status == 4){
                              print '
                              <li class="list-inline-item">
                                <a class="edit" data-toggle="tooltip" data-placement="top" title="Active" href="review.php?request='.$id_request.'&status=1">
                                  <i class="fa fa-check"></i>
                                </a>
                              </li>
                              <li class="list-inline-item">
                                <a class="delete" data-toggle="tooltip" data-placement="top" title="Reject" href="review.php?request='.$id_request.'&status=3">
                                  <i class="fa fa-trash"></i>
                                </a>
                              </li>
                              ';
                            }else if($request_status == 1){
                              print '
                              <li class="list-inline-item">
                                <a class="edit" data-toggle="tooltip" data-placement="top" title="Finish" href="review.php?request='.$id_request.'&status=2">
                                  <i class="fa fa-handshake-o"></i>
                                </a>
                              </li>
                              ';
                            }
                            print '
                          </ul>
                        </div>
                      </td>
                    </tr>
                  ';
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
function accept_reject(requestID,req_st){
  Swal.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#d33',
  cancelButtonColor: '#3085d6',
  confirmButtonText: 'Yes, do it!'
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
    location.href= "review.php?carName=" + document.getElementById('search_text').value;
  }
  else {
    location.href= "review.php?s="+f_type+"&carName=" + document.getElementById('search_text').value;
  }
  return false;
}
</script>

</body>

</html>