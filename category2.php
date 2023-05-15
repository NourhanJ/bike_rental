

<!DOCTYPE html>
<html lang="en">
<head>

<?php 

require_once('header.php'); 

if(!isset($_COOKIE['CR-userID']) || empty($_COOKIE['CR-userID'])){
	print "<script>location.replace(\"login.php\");</script>";
}

function filter($list){

	//text
	if(isset($_GET['text']) && !empty($_GET['text'])){
		if (strpos($list['bike_name'], $_GET['text']) === FALSE && strpos($list['description'], $_GET['text']) === FALSE )
			return false;
	}

	//material
	if(isset($_GET['material']) && !empty($_GET['material'])){
		if($list['material'] != $_GET['tr'])
			return false;
	}

	//color
	if(isset($_GET['color']) && !empty($_GET['color'])){
		if($list['color'] != $_GET['color'])
			return false;
	}

	//age
	if(isset($_GET['ym']) && !empty($_GET['ym'])){
		$range_date = explode(" - ", $_GET['ym']);
		if($list['end_age'] > $range_date[1] || $list['start_age'] < $range_date[0])
			return false;
	}

	//price
	if(isset($_GET['prd']) && !empty($_GET['prd'])){
		$range_date = explode(" - ", $_GET['prd']);
		if($list['rent_price_daily'] > $range_date[1] || $list['rent_price_daily'] < $range_date[0])
			return false;
	}

	//available
	if(isset($_GET['ad']) && !empty($_GET['ad'])){

		$origin = date_create($list['available_date'] );
		$target = date_create($_GET['ad']);
		$interval = date_diff($origin, $target);
		$check = explode(" ",$interval->format('%R %a'));

		if($check == "-")
			return false;
	}

	return true;
}

REQUIRE_ONCE('database/db/0_Connection.php');
?>

<section class="page-search">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- Advance Search -->
				<div class="advance-search">
					<?php require_once('search-bar.php'); ?>
				</div>
			</div>
		</div>
	</div>
</section>


<section class="section-sm">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="search-result bg-gray">
					<h2>Results For <i>
						<?php 
							$list_bike = array();
							$brand_selected = 'All';
							if (isset($_GET['brand']) && !empty($_GET['brand'])){
								$brand_selected = $_GET['brand'];
							}
							echo "'".$brand_selected."' ";
						?>
						</i>Brand
					</h2>
					<!--<p>123 Results on 12 Oct, 2020</p>-->
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="category-sidebar">
					<div class="widget category-list">
						<h4 class="widget-header">Items</h4>
						<ul class="category-list">
							<li><a href="category.php?item=bike">Bikes</a></li>	
							<li><a href="category.php?item=accessory">Accessories</a></li>
						</ul>
					</div>
					<?php
					if(isset($_GET['item']) && $_GET['item'] == "bike"){
						?>
						<div class="widget category-list">
						<h4 class="widget-header">Bike Brand</h4>
						<ul class="category-list">
							<li><a href="category.php?item=bike">All </a></li>
							<?php 

								if($temp = @mysqli_query($conn, "CALL SelectAllBrandProcedure()")){
									while($result = @mysqli_fetch_assoc($temp))
										if ($result != null) {
											extract($result);

											print '
												<li><a href="category.php?item=bike&brand='.$brand.'">'.$brand.' <span>'.$count.'</span></a></li>
											';
										}
								}
							?>		
						</ul>
					</div>
						<?php
					}
					?>

					<?php 
					if(isset($_GET['item']) && $_GET['item'] == "accessory"){
						?>
						<div class="widget category-list">
						<h4 class="widget-header">Accessory Brand</h4>
						<ul class="category-list">
							<li><a href="category.php?item=accessory">All </a></li>
							<?php 
								if($temp = @mysqli_query($conn, "CALL SelectAllAccessoryBrandProcedure()")){
									while($result = @mysqli_fetch_assoc($temp))
										if ($result != null) {
											extract($result);

											print '
												<li><a href="category.php?item=accessory&brand='.$brand.'">'.$brand.' <span>'.$count.'</span></a></li>
											';
										}
								}
							?>		
						</ul>
					</div>
						<?php
					}
					?>

					<?php
					if(isset($_GET['item']) && $_GET['item'] == "bike"){
					?>
					<div class="widget price-range w-100">
						<h4 class="widget-header">Bike Material</h4>
						<input type="radio" name="bikeMaterial" value="All" id="All" style="cursor:pointer;" checked>
						<label for="All" class="py-2" style="cursor:pointer;">All</label>
						<br>
						<input type="radio" name="bikeMaterial" value="Aluminum" id="Aluminum" style="cursor:pointer;" <?php echo (isset($_GET['tr']) && $_GET['tr']=='Aluminum')? 'checked' : ''; ?>>
						<label for="Aluminum" class="py-2" style="cursor:pointer;">Aluminum</label>
						<br>	
						<input type="radio" name="bikeMaterial" value="Fiber" id="Fiber" style="cursor:pointer;" <?php echo (isset($_GET['tr']) && $_GET['tr']=='Fiber')? 'checked' : ''; ?>>
						<label for="Fiber" class="py-2" style="cursor:pointer;">bikebon Fiber</label>
						<br>
						<input type="radio" name="bikeMaterial" value="Steel" id="Steel" style="cursor:pointer;" <?php echo (isset($_GET['tr']) && $_GET['tr']=='Steel')? 'checked' : ''; ?>>
						<label for="Steel" class="py-2" style="cursor:pointer;">Steel</label>
						<br>
						<input type="radio" name="bikeMaterial" value="Titanium" id="Titanium" style="cursor:pointer;" <?php echo (isset($_GET['tr']) && $_GET['tr']=='Titanium')? 'checked' : ''; ?>>
						<label for="Titanium" class="py-2" style="cursor:pointer;">Titanium</label>
					</div>
					<?php } ?>

					<?php 
					if(isset($_GET['item']) && $_GET['item'] == "accessory"){
					?>
					<div class="widget price-range w-100">
						<h4 class="widget-header">Accessories Category</h4>
						<input type="radio" name="accessoryCategory" value="lights" id="lights" style="cursor:pointer;" checked>
						<label for="lights" class="py-2" style="cursor:pointer;">lights</label>
						<br>
						<input type="radio" name="accessoryCategory" value="locks" id="locks" style="cursor:pointer;" <?php echo (isset($_GET['tr']) && $_GET['tr']=='Aluminum')? 'checked' : ''; ?>>
						<label for="locks" class="py-2" style="cursor:pointer;">locks</label>
						<br>	
						<input type="radio" name="accessoryCategory" value="helmets" id="helmets" style="cursor:pointer;" <?php echo (isset($_GET['tr']) && $_GET['tr']=='Fiber')? 'checked' : ''; ?>>
						<label for="helmets" class="py-2" style="cursor:pointer;">helmets</label>
						<br>
						<input type="radio" name="accessoryCategory" value="other" id="other" style="cursor:pointer;" <?php echo (isset($_GET['tr']) && $_GET['tr']=='Steel')? 'checked' : ''; ?>>
						<label for="other" class="py-2" style="cursor:pointer;">other</label>
					</div>
					<?php } ?>

					<?php 
					if(isset($_GET['item']) && $_GET['item'] == "bike"){
					?>
					<div class="widget price-range w-100">
						<h4 class="widget-header">Bike Color</h4>
						<select name="bikeColor" id="bikeColor" class="border-0 w-100 bg-white text-capitalize">
								<option value="All">All</option>
                                <option value="Red">Red</option>
                                <option value="Blue">Blue</option>
                                <option value="Green">Green</option>
                                <option value="Black">Black</option>
                                <option value="White">White</option>
                                <option value="other">Other</option>
                        </select>
					</div>
					<?php } ?>

					<?php 
					if(isset($_GET['item']) && $_GET['item'] == "accessory"){
					?>
					<div class="widget price-range w-100">
						<h4 class="widget-header">Accessory Color</h4>
						<select name="accessoryColor" id="accessoryColor" class="border-0 w-100 bg-white text-capitalize">
								<option value="All">All</option>
                                <option value="Red">Red</option>
                                <option value="Blue">Blue</option>
                                <option value="Green">Green</option>
                                <option value="Black">Black</option>
                                <option value="White">White</option>
                                <option value="other">Other</option>
                        </select>
					</div>
					<?php } ?>
					
					<div class="widget price-range w-100">
						<h4 class="widget-header">Age</h4>
						<div class="block">
							<input class="range-track-y w-100" type="text" data-slider-min="5" data-slider-max="50" data-slider-step="1" data-slider-value="[5,50]">
							<div class="justify-content-between mt-2">
								<span class="year-value"><?php echo (isset($_GET['ym']) && !empty($_GET['ym']))? $_GET['ym'] : '5 - 50'; ?></span>
							</div>
						</div>
					</div>

					<div class="widget price-range w-100">
						<h4 class="widget-header">Price Range Daily</h4>
						<div class="block">
							<input class="range-track w-100" type="text" data-slider-min="0" data-slider-max="200" data-slider-step="5" data-slider-value="[0,200]">
							<div class="d-flex justify-content-between mt-2">
								<span class="value"><?php if(isset($_GET['prd']) && !empty($_GET['prd'])) echo "$".explode(" - ",$_GET['prd'])[0]." - $".explode(" - ",$_GET['prd'])[1]; else echo'0$ - 200$'; ?></span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-9">
				<div class="category-search-filter">
					<div class="row">
						<div class="col-md-6">
							<strong>Sort</strong>
							<select onchange="sort_by_price()">
								<option value="lowe">Lowest Price</option>
								<option value="hight">Highest Price</option>
							</select>
						</div>
						<div class="col-md-6">
							<div class="view">
								<strong>Views</strong>
								<ul class="list-inline view-switcher">
									<li class="list-inline-item">
										<a id="Gv" href="#grid-view" onclick="switch_g_l('g')" class="text-info"><i class="fa fa-th-large"></i></a>
									</li>
									<li class="list-inline-item">
										<a id="Lv" href="#list-view" onclick="switch_g_l('l')"><i class="fa fa-reorder"></i></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<div class="product-grid-list" id="grid-view">
					<div class="row mt-30" id="g-view">
					<?php
						@mysqli_next_result($conn);

						if(isset($_GET['item']) && $_GET['item'] == "bike"){
							if($temp = @mysqli_query($conn, "CALL SelectAllbikesProcedure('".$brand_selected."')")){
								while($result = @mysqli_fetch_assoc($temp))
									if ($result != null) {
	
										if(filter($result)){
	
											$list_bike[] = $result;
											extract($result);
	
											//put bike here
											$body_bike = '
											<div class="col-sm-12 col-lg-4">
												<!-- product biked -->
												<div class="product-item bg-light">
													<div class="biked">
														<div class="thumb-content">
															<div class="price">$'.$rent_price_daily.'/d</div>
															<a href="single.php?id_bike='.$id_bike.'">
																<img class="biked-img-top img-fluid" src="assets/'.$image.'" alt="biked image cap">
															</a>
														</div>
														<div class="biked-body">
															<h4 class="biked-title"><a href="single.php?id_bike='.$id_bike.'">'.$bike_name.'</a></h4>
															<ul class="list-inline product-meta">
																<li class="list-inline-item">
																	<a href="category.php?brand='.$brand.'"><i class="fa fa-folder-open-o"></i> '.$brand.'</a>
																</li>
																<li class="list-inline-item">
																	<i class="fa fa-calendar"></i> '.$brand.'
																</li>
															</ul>
															<p class="biked-text" style="height:60px; overflow: hidden; text-overflow: ellipsis;">'.$description.'</p>
															<div class="product-ratings">
																<ul class="list-inline">';
																	$bike_rating = '';
																	$i=0;
																	for($i;$i<$avg_rating;$i++)
																		$bike_rating.= '<li class="list-inline-item selected"><i class="fa fa-star" style="cursor:default;"></i></li>';
																	for($i;$i<5;$i++)
																		$bike_rating.= '<li class="list-inline-item"><i class="fa fa-star-o" style="cursor:default;"></i></li>';
																$body_bike.= $bike_rating.'</ul>
															</div>
														</div>
													</div>
												</div>
											</div>
											';
											print $body_bike;
										}
									}
								}
						}
						else{
							if($temp = @mysqli_query($conn, "CALL SelectAllAccessoriesProcedure('".$brand_selected."')")){
							while($result = @mysqli_fetch_assoc($temp))
								if ($result != null) {

									if(filter($result)){

										$list_bike[] = $result;
										extract($result);

										//put bike here
										$body_bike = '
										<div class="col-sm-12 col-lg-4">
											<!-- product biked -->
											<div class="product-item bg-light">
												<div class="biked">
													<div class="thumb-content">
														<div class="price">$'.$rent_price_daily.'/d</div>
														<a href="single_accessory.php?id_accessory='.$id_accessory.'">
															<img class="biked-img-top img-fluid" src="assets/'.$image.'" alt="biked image cap">
														</a>
													</div>
													<div class="biked-body">
														<h4 class="biked-title"><a href="single_accessory.php?id_accessory='.$id_accessory.'">'.$accessory_name.'</a></h4>
														<ul class="list-inline product-meta">
															<li class="list-inline-item">
																<a href="category.php?brand='.$brand.'"><i class="fa fa-folder-open-o"></i> '.$brand.'</a>
															</li>
															<li class="list-inline-item">
																<i class="fa fa-calendar"></i> '.$brand.'
															</li>
														</ul>
														<p class="biked-text" style="height:60px; overflow: hidden; text-overflow: ellipsis;">'.$description.'</p>
													</div>
												</div>
											</div>
										</div>
										';
										print $body_bike;
									}
								}
							}
						}
						?>
					</div>
				</div>

				<div class="product-grid-list" id="list-view" style="display:none;">
				<?php
					for($j=0;$j<sizeof($list_bike);$j++){
						extract($list_bike[$j]);
						print '
						<!-- ad listing list  -->
						<div class="ad-listing-list mt-20">
							<div class="row p-lg-3 p-sm-5 p-4">
								<div class="col-lg-4 align-self-center">
									<div class="product-item bg-light" style="margin-bottom:0px">
										<div class="biked">
											<div class="thumb-content">
												<div class="price">$'.$rent_price_daily.'</div>
												<a href="single_accessory.php?id_bike='.$id_bike.'">
													<img src="assets/'.$image.'" class="img-fluid" alt="">
												</a>
											</div>
										</div>
									</div>	
								</div>
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-8 col-md-10">
											<div class="ad-listing-content">
												<div>
													<a href="single_accessory.php?id_bike='.$id_bike.'" class="font-weight-bold">'.$bike_name.'</a>
												</div>
												<ul class="list-inline mt-2 mb-3">
													<li class="list-inline-item"><a href="category.php?brand='.$brand.'"> <i class="fa fa-folder-open-o"></i>'.$brand.'</a></li>
												</ul>
												<p class="pr-5" style="height:60px; overflow: hidden; text-overflow: ellipsis;">'.$description.'</p>
											</div>
										</div>
										<div class="col-lg-4 align-self-center">
											<div class="product-ratings float-lg-right pb-3">
												<ul class="list-inline">';
													$bike_rating = '';
													$i=0;
													for($i;$i<$avg_rating;$i++)
														$bike_rating.= '<li class="list-inline-item selected"><i class="fa fa-star" style="cursor:default;"></i></li>';
													for($i;$i<5;$i++)
														$bike_rating.= '<li class="list-inline-item"><i class="fa fa-star-o" style="cursor:default;"></i></li>';
												print $bike_rating.'</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						';
					}
				?>
				</div>

			</div>
		</div>
	</div>
</section>
<!--============================
=            Footer            =
=============================-->

<?php require_once('footer.php'); ?>

<script>
function switch_g_l(view_type){
	if(view_type == "l"){
		document.getElementById("list-view").style.display = "";
		document.getElementById("grid-view").style.display = "none";
		document.getElementById("Lv").setAttribute("class","text-info");
		document.getElementById("Gv").removeAttribute("class");
	}
	else{
		document.getElementById("grid-view").style.display = "";
		document.getElementById("list-view").style.display = "none";
		document.getElementById("Gv").setAttribute("class","text-info");
		document.getElementById("Lv").removeAttribute("class");
	}
}

function sort_by_price(){
	$.fn.reverseChildren = function() {
		return this.each(function(){
			var $this = $(this);
			$this.children().each(function(){
			$this.prepend(this);
			});
		});
	};
	$('#g-view').reverseChildren();
	$('#list-view').reverseChildren();
}

function search_filter(brand){
	
	var link = "category.php?brand="+brand;
	
	//bike material
	var tr_val = $('input[name="bikeMaterial"]:checked').val();
	link+= "&material="+tr_val;

	//color
	var color = document.getElementById('color').value;
	if(color != "All") link+= "&color="+color;

	//year model
	var ym_val = document.querySelector(".year-value").innerHTML;
		link+= "&ym="+ym_val;

	//Price Range Daily
	var prd_v = document.querySelector(".value").innerHTML;
	if(prd_v != "$0 - $200"){
		var p = prd_v.split(" - $");
		var hp = p[0].split("$");
		prd_val = hp[1]+" - "+p[1];
		link+= "&prd="+prd_val;
	}

	//Available Date
	// if(document.getElementById('av-date').value)
	// 	link += "&ad="+document.getElementById('av-date').value;

	//text
	if(document.getElementById('search_text').value != "")
		link += "&text=" + document.getElementById('search_text').value;

	location.href= link;
	return false;
}
</script>

</body>

</html>