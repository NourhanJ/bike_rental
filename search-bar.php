<?php
$br = "All";
if(isset($_GET['brand']) && !empty($_GET['brand']))
    $br = $_GET['brand'];
if(isset($_GET['item']) && !empty($_GET['item'])){
    $br .= "&item=" . $_GET['item'];
}
print '
<form action="#">
<div class="form-row">
    <div class="form-group col-md-10">
        <input type="text" class="form-control my-2 my-lg-1" id="search_text" placeholder="What are you looking for..">
    </div>
    <!--<div class="form-group col-md-4">
        <select class="w-100 form-control mt-lg-1 mt-md-2">
            <option>Brand</option>
            <option value="1">Top rated</option>
            <option value="2">Lowest Price</option>
            <option value="4">Highest Price</option>
        </select>
    </div>-->
    
    <div class="form-group col-md-2 align-self-center">
        <button onclick="return search_filter(\''.$br.'\')" class="btn btn-primary">Search Now</button>
    </div>
</div>
</form>
';
?>