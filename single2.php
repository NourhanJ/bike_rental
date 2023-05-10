<!DOCTYPE html>
<html lang="en">
<head>

<?php require_once('header.php'); 

/*<!--===================================
=            Store Section            =
====================================-->*/

require_once('database/db/0_Connection.php');

if($temp = @mysqli_query($conn, "CALL SelectSinglebikeProcedure('".$_GET['id_bike']."')")){
	while($result = @mysqli_fetch_assoc($temp))
		if ($result != null) {
			extract($result);

			print '
			<section class="section bg-gray">
				<!-- Container Start -->
				<div class="container">
					<div class="row">
						<!-- Left sidebar -->
						<div class="col-md-8">
							<div class="product-details">';
								if ($stock > 0) {
								echo '<a class="nav-link text-white btn-danger" href="rent.php?id='. $_GET['id_bike'] .'&p='. $rent_price_daily .'" style="text-align: center;"><i class="fa fa-power"></i>Rent Bike</a>';
								} else {
								echo '<button id="submit" name="submit" value="submit" class="btn btn-primary d-block mt-2" disabled>Rent Bike</button>';
								}
								print '
								<br>
								<h1 class="product-title" id="name">'.$bike_name.'</h1>
								<div class="product-meta">
									<ul class="list-inline">
										<li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Brand: <a href="home.php?brand='.$brand.'">'.$brand.'</a></li>
										<li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Material: <a href="home.php?brand='.$material.'">'.$material.'</a></li>
										<li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Wheel Size: <a href="home.php?brand='.$wheel_size.'">'.$wheel_size.'</a></li>
										<li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Color: <a href="home.php?brand='.$color.'">'.$color.'</a></li>
										<li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Accessories: <a href="home.php?brand='.$accessories.'">'.$accessories.'</a></li>
										<li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Stock Quantity: <a href="home.php?brand='.$stock.'">'.$stock.'</a></li>
									</ul>
								</div>

								<!-- product slider -->
									<div class="my-4" data-image="assets/'.$image.'">
										<img class="img-fluid w-100" src="assets/'.$image.'" alt="product-img">
									</div>
								<!-- product slider -->

								<div class="content mt-5 pt-5">
									<ul class="nav nav-pills  justify-content-center" id="pills-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home"
											aria-selected="true">Description</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact"
											aria-selected="false">Reviews</a>
										</li>
									</ul>
									<div class="tab-content" id="pills-tabContent">
										<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
											<h3 class="tab-title">Bike Description</h3>
											<p>'.$description.'</p>

										</div>
										
										<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
										<h3 class="tab-title">Bike Review</h3>
										<div id="product-review" class="product-review">';

											@mysqli_next_result($conn);

											if($temp2 = @mysqli_query($conn, "CALL SelectFeedbackForSinglebikeProcedure('".$_GET['id_bike']."')")){
												while($result2 = @mysqli_fetch_assoc($temp2))
													if ($result2 != null) {
														extract($result2);
														
														print '
														<div class="media">
															<!-- Avater -->
															<img src="../images/user/avatar.png" alt="avater">
															<div class="media-body">
																<!-- Ratings -->
																<div class="ratings">
																	<ul class="list-inline">';

																		$f_date = strtotime($creation_date);
																		$f_d = date('d, M Y', $f_date);
																		$i=0;
																		$bike_rating='';
																		for($i;$i<$rating;$i++)
																			$bike_rating.= '<li class="list-inline-item selected"><i class="fa fa-star" style="cursor:default;"></i></li>';
																		for($i;$i<5;$i++)
																			$bike_rating.= '<li class="list-inline-item"><i class="fa fa-star-o" style="cursor:default;"></i></li>';
																	print $bike_rating.'
																	</ul>
																</div>
																<div class="name">
																	<h5>'.$f_name.' '.$l_name.'</h5>
																</div>
																<div class="date">
																	<p>'.$f_d.'</p>
																</div>
																<div class="review-comment">
																	<p>'.$description.'</p>
																</div>
															</div>
														</div>';
													}
											}
											print'
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>	
					</div>
				</div>
				<!-- Container End -->
			</section>';
		}
	}
	?>
<!--============================
=            Footer            =
=============================-->
<?php require_once('footer.php'); ?>

</body>

</html>