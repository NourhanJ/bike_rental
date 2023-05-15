<?php

    $p1 = explode("/",$_SERVER['REQUEST_URI']);
    $p2 = explode(".php",$p1[sizeof($p1)-1]);
    $this_page = $p2[0];

    print ' 
    <!-- SITE TITTLE -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BikeRental</title>
    
    <!-- LOGO -->
    <link href="images/logo.png" rel="shortcut icon">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap-slider.css">
    <!-- Font Awesome -->
    <link href="plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Owl Carousel -->
    <link href="plugins/slick-carousel/slick/slick.css" rel="stylesheet">
    <link href="plugins/slick-carousel/slick/slick-theme.css" rel="stylesheet">
    <!-- Fancy Box -->
    <link href="plugins/fancybox/jquery.fancybox.pack.css" rel="stylesheet">
    <link href="plugins/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <!-- CUSTOM CSS -->
    <link href="css/style.css" rel="stylesheet">
    <script src="alert_style/sweetalert2.all.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <!-- jQuery and Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNSqn/E" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
  </head>
  
  <body class="body-wrapper">
  
  
  <section>
      <div class="container">
          <div class="row">
              <div class="col-md-12">
                  <nav class="navbar navbar-expand-lg navbar-light navigation">
                      <a class="navbar-brand" href="index.php">
                          <img src="images/logo.png" alt="">
                      </a>
                      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                       aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon"></span>
                      </button>
                      <div class="collapse navbar-collapse" id="navbarSupportedContent">
                          <ul class="navbar-nav ml-auto main-nav ">
                              <li class="nav-item '; if($this_page == "index" || $this_page == "" ) print 'active';  print'">
                                  <a class="nav-link" href="index.php">Home</a>
                              </li>
                              <li class="nav-item dropdown dropdown-slide '; if($this_page == "add-bike" || $this_page == "add-accessory") print 'active';  print'">
                                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="">Add<span><i class="fa fa-angle-down"></i></span>
                                  </a>
                                  <!-- Dropdown list -->
                                  <div class="dropdown-menu">
                                      <a class="dropdown-item" href="add-bike.php">Bikes</a>
                                      <a class="dropdown-item" href="add-accessory.php">Accessories</a>
                                  </div>
                              </li>
                              <li class="nav-item dropdown dropdown-slide '; if($this_page == "bikes" || $this_page == "accessories") print 'active';  print'">
                                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="">Items<span><i class="fa fa-angle-down"></i></span>
                                  </a>
                                  <!-- Dropdown list -->
                                  <div class="dropdown-menu">
                                      <a class="dropdown-item" href="bikes.php">Bikes</a>
                                      <a class="dropdown-item" href="accessories.php">Accessories</a>
                                  </div>
                              </li>
                              <li class="nav-item dropdown dropdown-slide '; if($this_page == "request") print 'active';  print'">
                                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="">Requests<span><i class="fa fa-angle-down"></i></span>
                                  </a>
  
                                  <!-- Dropdown list -->
                                  <div class="dropdown-menu">
                                      <a class="dropdown-item" href="request.php">My Requests</a>
                                      <a class="dropdown-item" href="request.php?s=w">Requests Waiting</a>
                                      <a class="dropdown-item" href="request.php?s=a">Requests Accepts</a>
                                      <a class="dropdown-item" href="request.php?s=h">Requests History</a>
                                  </div>
                              </li>
                              <li class="nav-item '; if($this_page == "review_requests") print 'active';  print'">
                                  <a class="nav-link" href="review.php ">Review Requests</a>
                              </li>
                          </ul>
                          <ul class="navbar-nav ml-auto mt-10">
                            <li class="nav-item">';

                              if(!isset($_COOKIE['CR-userID']) || empty($_COOKIE['CR-userID'])){
                                print '<a class="nav-link login-button" href="login.php">Login</a>';
                              }else{
                                print '<a class="nav-link text-white btn-danger" data-toggle="modal" data-target="#logout" href="#"><i class="fa fa-power"></i>Logout</a>';
                              }

                      print '</li> 
                          </ul>
                      </div>
                  </nav>
              </div>
          </div>
      </div>
  </section>
    ';
    if(isset($_COOKIE['CR-userID']) && !empty($_COOKIE['CR-userID'])){
        print '
          <!-- logout modal -->
              <!-- logout popup modal start-->
              <!-- Modal -->
              <div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body text-center">
                      <img src="images/account/Account1.png" class="img-fluid mb-2" alt="">
                      <h6 class="py-2">LOGOUT</h6>
                      <p>Are you sure you want to logout? This process cannot be undone.</p>
                    </div>
                    <div class="modal-footer border-top-0 mb-3 mx-5 justify-content-lg-between justify-content-center">
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-danger" onclick="logout_fct()">Logout</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- logout popup modal end-->
          <!-- logout modal -->

          <script>
          function logout_fct(){
            var d = new Date();
            d.setTime(d.getTime() - (24 * 60 * 60 * 1000)); // set expiration to 24 hours ago
            document.cookie = "CR-userID=; expires=" + d.toUTCString() + "; path=/";
            location.href = "index.php";
          } 
          </script>
        ';
    }
?>