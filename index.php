<!DOCTYPE html>
<html lang="en">
<head>

  <?php require_once('header.php'); ?>

<!--===============================
=            Hero Area            =
================================-->

<section class="hero-area bg-1 text-center overly">
	<!-- Container Start -->
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- Header Contetnt -->
				<div class="content-block">
					<h1>Rent a Bike You Love!</h1>
					<p>Whether you are travelling and want to see the sights, <br />or are looking for a high performance ride in the back-country, we've got your bike.</p>
				</div>
				<!-- Advance Search -->
				<div class="advance-search">
					<div class="container">
						<div class="row justify-content-center">
							<div class="col-lg-12 col-md-12 align-content-center">
								<?php require_once('search-bar.php'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container End -->
</section>

<!--===========================================
=            bikes section            		  =
============================================-->

<section class="popular-deals section bg-gray">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="section-title">
					<h2>Instant Booking!</h2>
					<p>Guaranteed reservations in the time and place you need it.</p>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- offer 01 -->
			<div class="col-lg-12">
				<div class="trending-ads-slide">
					
					<?php 
						REQUIRE_ONCE('database/db/0_Connection.php');

						if($temp = @mysqli_query($conn, "CALL SelectAllbikesProcedure('5-All')")){
							while($result = @mysqli_fetch_assoc($temp))
								if ($result != null) {
									extract($result);

									//put bike here
									$body_bike = '
									<div class="col-sm-12 col-lg-8">
										<!-- product biked -->
										<div class="product-item bg-light">
											<div class="biked">
												<div class="thumb-content">
													<div class="price">$'.$rent_price_daily.'/d</div>
													<a href="single.php?id_bike='.$id_bike.'">
														<img class="biked-img-top img-fluid" src="assets/'.$bike_image.'" alt="biked image cap">
													</a>
												</div>
												<div class="biked-body">
													<h4 class="biked-title"><a href="single.php?id_bike='.$id_bike.'">'.$bike_name.'</a></h4>
													<ul class="list-inline product-meta">
														<li class="list-inline-item">
															<a href="single.php?id_bike='.$id_bike.'"><i class="fa fa-folder-open-o"></i> '.$bike_brand.'</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									';
									print $body_bike;
								}
						}
					?>
				</div>
			</div>
		</div>
	</div>
</section>



<!--==========================================
=            All Category Section            =
===========================================-->

<section class=" section">
	<!-- Container Start -->
	<div class="container">
		<div class="row">
			<div class="col-12">
				<!-- Section title -->
				<div class="section-title">
					<h2>All Accessories</h2>
					<p>Select your favorite!</p>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="trending-ads-slide">
					<?php 
						@mysqli_next_result($conn);

						if($temp2 = @mysqli_query($conn, "CALL SelectAllAccessoriesProcedure('5-All')")){
							$bg_count = 0;
							while($result2 = @mysqli_fetch_assoc($temp2))
								if ($result2 != null) {
									extract($result2);
									
									$icon_bg = $bg_count + 1;
									//put brand here
									print'
									<div class="col-sm-12 col-lg-8">
										<!-- product biked -->
										<div class="product-item bg-light">
											<div class="biked">
												<div class="thumb-content">
													<div class="price">$'.$rent_price_daily.'/d</div>
													<a href="single_accessory.php?id_accessory='.$id_accessory.'">
														<img class="biked-img-top img-fluid" src="assets/'.$accessory_image.'" alt="biked image cap">
													</a>
												</div>
												<div class="biked-body">
													<h4 class="biked-title"><a href="single_accessory.php?id_accessory='.$id_accessory.'">'.$accessory_name.'</a></h4>
													<ul class="list-inline product-meta">
														<li class="list-inline-item">
															<a href="single_accessory.php?id_accessory='.$id_accessory.'"><i class="fa fa-folder-open-o"></i> '.$accessory_brand.'</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									';
									$bg_count = ($bg_count+1)%8;

								}
						}
						$conn->close();	
					?>
						</div></div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container End -->
</section>


<!--====================================
=            Call to Action            =
=====================================-->
<?php
if(!isset($_COOKIE['CR-userID']) || empty($_COOKIE['CR-userID'])){
	print'
	<section class="call-to-action overly bg-3 section-sm">
		<!-- Container Start -->
		<div class="container">
			<div class="row justify-content-md-center text-center">
				<div class="col-md-8">
					<div class="content-holder">
						<h2>Start today to get more exposure</h2>
						<ul class="list-inline mt-30">
							<li class="list-inline-item"><a class="btn btn-main" href="login.php">Login</a></li>
							<li class="list-inline-item"><a class="btn btn-secondary" href="register.php">Register</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- Container End -->
	</section>
	';
}
?>
<!--============================
=            Footer            =
=============================-->
<!-- Footer Bottom -->
<?php require_once('footer.php'); ?>

<script>
document.getElementById('search_text').setAttribute("required");
function search_filter(brand){
	//text
	if(document.getElementById('search_text').value != ""){
		location.href = "bikes.php?brand=All&text=" + document.getElementById('search_text').value;
	}
	return false;
}
</script>

</body>

</html>



