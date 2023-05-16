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
		if (strpos($list['accessory_name'], $_GET['text']) === FALSE && strpos($list['description'], $_GET['text']) === FALSE )
			return false;
	}

	//category
	if(isset($_GET['tr']) && !empty($_GET['tr'])){
		if($list['category'] != $_GET['tr'])
			return false;
	}

	//color
	if(isset($_GET['color']) && !empty($_GET['color'])){
		if($list['color'] != $_GET['color'])
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
						<h4 class="widget-header">Brand</h4>
						<ul class="category-list">
							<li><a href="accessories.php">All </a></li>
							<?php 

								if($temp = @mysqli_query($conn, "CALL SelectAllAccessoryBrandProcedure()")){
									while($result = @mysqli_fetch_assoc($temp))
										if ($result != null) {
											extract($result);

											print '
												<li><a href="accessories.php?brand='.$accessory_brand.'">'.$accessory_brand.' <span>'.$count.'</span></a></li>
											';
										}
								}
							?>		
						</ul>
					</div>

					<div class="widget price-range w-100">
						<h4 class="widget-header">Accessories Category</h4>
                        <input type="radio" name="accessoryCategory" value="All" id="All" style="cursor:pointer;" checked>
						<label for="All" class="py-2" style="cursor:pointer;">All</label>
						<br>
						<input type="radio" name="accessoryCategory" value="lights" id="lights" style="cursor:pointer;">
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
					
					<div class="widget price-range w-100">
						<h4 class="widget-header">Color</h4>
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
															<img class="biked-img-top img-fluid" src="assets/'.$accessory_image.'" alt="biked image cap">
														</a>
													</div>
													<div class="biked-body">
														<h4 class="biked-title"><a href="single_accessory.php?id_accessory='.$id_accessory.'">'.$accessory_name.'</a></h4>
														<ul class="list-inline product-meta">
															<li class="list-inline-item">
																<a href=""><i class="fa fa-folder-open-o"></i> '.$accessory_brand.'</a>
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
												<a href="single_accessory.php?id_accessory='.$id_accessory.'">
													<img src="assets/'.$accessory_image.'" class="img-fluid" alt="">
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
													<a href="single_accessory.php?id_accessory='.$id_accessory.'" class="font-weight-bold">'.$accessory_name.'</a>
												</div>
												<ul class="list-inline mt-2 mb-3">
													<li class="list-inline-item"><a href=""> <i class="fa fa-folder-open-o"></i>'.$accessory_brand.'</a></li>
												</ul>
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
	
	var link = "accessories.php?brand="+brand;
	
	//transmition
	var tr_val = $('input[name="accessoryCategory"]:checked').val();
	if(tr_val != "All")
		link+= "&tr="+tr_val;

	//color
	var ym_val = document.getElementById('bikeColor').value;
	if(ym_val != "All")
		link+= "&color="+ym_val;

	//Price Range Daily
	var prd_v = document.querySelector(".value").innerHTML;
	if(prd_v != "$0 - $200"){
		var p = prd_v.split(" - $");
		var hp = p[0].split("$");
		prd_val = hp[1]+" - "+p[1];
		link+= "&prd="+prd_val;
	}

	//text
	if(document.getElementById('search_text').value != "")
		link += "&text=" + document.getElementById('search_text').value;

        console.log("link");
    console.log(link);

	location.href= link;
	return false;
}
</script>

</body>

</html>