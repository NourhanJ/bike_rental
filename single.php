<!DOCTYPE html>
<html lang="en">
<head>

<?php require_once('header.php'); ?>

<!--===================================
=            Store Section            =
====================================-->
<?php

if(!isset($_COOKIE['CR-userID']) || empty($_COOKIE['CR-userID'])){
    print "<script>location.replace(\"login.php\");</script>";
}

if(isset($_GET['f'])){
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
        title: 'Thanks for feedback'
        });
    </script>";
}


require_once('database/db/0_Connection.php');

if($temp = @mysqli_query($conn, "CALL SelectSinglebikeProcedure('".$_GET['id_bike']."')")){
	while($result = @mysqli_fetch_assoc($temp))
		if ($result != null) {
			extract($result);
            $userid = $_COOKIE['CR-userID'];

			print '
			<section class="section bg-gray">
				<!-- Container Start -->
				<div class="container">
					<div class="row">
						<!-- Left sidebar -->
						<div class="col-md-8">
							<div class="product-details">';
                            if($owner_id == $userid){
                                print '<div style="color:green" class="text-center">
                                <lable>The bike was added by you</lable>
                            </div>';
                            }
                            else{
                                if($stock == 0){
                                    print '	<div style="color:red" class="text-center">
													<lable>This Bike <b>is not</b> available.</lable>
												</div>';
                                }
                                else{
                                    print '<a class="nav-link text-white btn-danger" href="rent.php?id='. $_GET['id_bike'] .'&p='. $rent_price_daily .'" style="text-align: center;"><i class="fa fa-power"></i>Rent Bike</a>';
                                }
                            }
                                print '
                                <br>
								<h1 class="product-title" id="name">'.$bike_name.'</h1>
								<div class="product-meta">
									<ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Brand: <a href="#">'.$bike_brand.'</a></li>
                                    <li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Material: <a href="home.php?brand='.$material.'">'.$material.'</a></li>
                                    <li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Wheel Size: <a href="home.php?brand='.$wheel_size.'">'.$wheel_size.'</a></li>
                                    <li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Color: <a href="home.php?brand='.$color.'">'.$color.'</a></li>
                                    <li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Stock Quantity: <a href="home.php?brand='.$stock.'">'.$stock.'</a></li>
									</ul>
								</div>

								<!-- product slider -->
									<div class="my-4" data-image="assets/'.$bike_image.'">
										<img class="img-fluid w-100" src="assets/'.$bike_image.'" alt="product-img">
									</div>
								<!-- product slider -->

								<div class="content mt-5 pt-5">
									<ul class="nav nav-pills  justify-content-center" id="pills-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home"
											aria-selected="true">Description</a>
										</li>
									</ul>
									<div class="tab-content" id="pills-tabContent">
										<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
											<h3 class="tab-title">Bike Description</h3>
											<p>'.$description.'</p>

										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="sidebar">
								<div class="widget price text-center">
									<h4>Price</h4>
									<p id="rpd">'.$rent_price_daily.'$</p>
								</div>

							</div>
						</div>

					</div>
				</div>
				<!-- Container End -->
			</section>
			';
		}
}
?>

<!--============================
=            Footer            =
=============================-->

<?php require_once('footer.php'); ?>

<script>

//rating
var rate_value = 0;
$('.starrr').starrr();
$('.starrr').on('starrr:change', function(e, value){
	rate_value = value;
});

try{
	var d_req = new Date();
	document.getElementById("res-date").min = d_req.toISOString().slice(0, 10);
	document.getElementById("res-time").min = d_req.toISOString().slice(11, 20);

	function getTotal(d_price,d_nb){
		var total = d_price * d_nb;
		document.getElementById("total-price-req").innerHTML = total;
	}

	function send_request(user,bike){
		if(document.forms["form_r"].checkValidity()){
			Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, send it!'
			}).then((result) => {
				if (result.isConfirmed) {
				getTotal(document.getElementById("nb-rental-days").value,(document.getElementById("rpd").innerHTML).split("$")[0]);
				const toSend = {
						userID: user,
						bikeID: bike,
						res_d:document.getElementById('res-date').value + ' ' + document.getElementById('res-time').value,
						nb_rent_d: document.getElementById('nb-rental-days').value,
						total_price: document.getElementById('total-price-req').innerHTML
						};
				const josnString = JSON.stringify(toSend);
				const xhr = new XMLHttpRequest();
				xhr.open("POST","<?php echo $RequestInsert; ?>", false);
				xhr.setRequestHeader('Content-Type', 'application/json; charset = UTF-8');
				xhr.send(josnString);
				location.reload(); 
				}
			});
			return false;
		}
	}
}catch{}

function send_feedback(user,bike){	
	if(document.forms["form_f"].checkValidity()){
		const toSend = {
				userID: user,
				bikeID: bike,
				rate: rate_value,
				msg: document.getElementById('review').value
		};
				
		const josnString = JSON.stringify(toSend);
		const xhr = new XMLHttpRequest();
		xhr.open("POST","<?php echo $FeedbackInsert; ?>", false);
		xhr.setRequestHeader('Content-Type', 'application/json; charset = UTF-8');
		xhr.send(josnString);

		location.href = "?id_bike="+bike+"&f=";
		return false;
	}
}
</script>

</body>

</html>