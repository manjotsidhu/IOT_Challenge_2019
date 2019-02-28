<!DOCTYPE html>
<?php
include 'db.php';
session_start();

if(isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
	$pass = $_SESSION['pass'];
	
	$qs = mysqli_query($conn, "SELECT * FROM accounts where email='$user' and pass='$pass'");
	$count1=mysqli_num_rows($qs);
	
	if($count1 > 0) {
		while($acc = mysqli_fetch_array($qs)){
			$fname = $acc['fname'];
			$lname = $acc['lname'];
			$pno = $acc['pno'];
			$role = $acc['role'];
		}
	} else {
		header('Location: index.php');
		exit();
	}
} else {
	header('Location: index.php');
	exit();
}

$dump_query = mysqli_query($conn, "SELECT * FROM dumps");
$garbage_dumps = mysqli_num_rows($dump_query);

if ($garbage_dumps > 0) {
	$i = 0;
	while($row = mysqli_fetch_assoc($dump_query)) {
    	$dumpsId[$i] = $row["id"];
    	$dumpsLat[$i] = $row["lat"];
    	$dumpsLong[$i] = $row["longi"];
    	$dumpsHeight[$i] = $row["height"];
    	$dumpsCap[$i] = $row["capacity"];
		$i++;
	}
}

if(isset($_POST['sel_sub'])) {
	$as = "INSERT INTO settings (vol,history,pred) VALUES ('0','0','0')";
	mysqli_query($conn, $as);
	
	foreach ($_POST['sel'] as $selectedOption) {
		
		$as = "UPDATE settings SET $selectedOption='1' ORDER BY id DESC LIMIT 1";
	
		if(mysqli_query($conn, $as)) {
			//$status = 1;
		} else {
			//$status = -1;
		}
	}
}

if(isset($_POST['logout'])) {
	session_destroy();
	header('Location:index.php');
}

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Analysis and Management of Waste</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" href="css/mdb.min.css">

    <!-- Your custom styles (optional) -->
<style>
    .card.card-cascade .view.gradient-card-header {
            padding: 1.2rem 1rem;
        }

        .map-container{
			overflow:hidden;
			padding-bottom:56.25%;
			position:relative;
			height:0;
			}
			.map-container iframe{
			left:0;
			top:0;
			height:100%;
			width:100%;
			position:absolute;
			}
			
</style>
</head>

<body class="fixed-sn white-skin">

    <!--Main Navigation-->
    <header>

        <!-- Sidebar navigation -->
        <div id="slide-out" class="side-nav sn-bg-4 fixed">
			<ul class="custom-scrollbar">
            <!-- Logo -->
            <li class="collapsible-header waves-effect pb-5 pt-2">
                <div class="text-center">
                    <a href="#" class="pl-0"><img src="img/slogo.png" class="img" height="100px" width="130px"></a>
                </div>
            </li>
            <!--/. Logo -->

            <li>
			
				<ul class="collapsible collapsible-accordion">
                    
					<li><a href="index.php" class="collapsible-header waves-effect"><i class="fas fa-home"></i> Home</a></li>

					<?php for($d = 1; $d <= $garbage_dumps; $d++) {?>
                    <li><a href="gd.php?id=<?php echo $d;?>" class="collapsible-header waves-effect"><i class="fas fa-bolt"></i> Garbage Dump <?php echo $d;?></a></li>
					<?php }?>
					
					<?php if($role == "auth") {?>
					<li><a class="collapsible-header waves-effect" data-toggle="modal" href="#set" ><i class="fas fa-cogs"></i> Settings</a></li>			
					
					<?php } ?>
                </ul>
			
			</li>
			<!--/. Side navigation links -->
            </ul>
            <div class="sidenav-bg mask-strong"></div>
        </div>
		
        <!--/. Sidebar navigation -->

        <!-- Navbar -->
        <nav class="navbar fixed-top navbar-expand-lg scrolling-navbar double-nav">
            <!-- SideNav slide-out button -->
            <div class="float-left">
                <a href="#" data-activates="slide-out" class="button-collapse black-text"><i class="fa fa-bars"></i></a>
            </div>
			<!-- Collapse -->
			
            <!-- Breadcrumb-->
            <div class="breadcrumb-dn mr-auto ml-2">
                <p> Dashboard - Analysis and Management of Waste
                    <a class="btn btn-md btn-outline-primary btn-rounded waves-effect"><span class=""><?php echo $garbage_dumps;?> Garbage Dumps</span></a>
				</p>
			</div>

            <!--Navbar links-->
            <ul class="nav navbar-nav nav-flex-icons ml-auto">

                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect" href="#" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user"></i> <span class="clearfix d-none d-sm-inline-block">Welcome, <strong><?php echo $fname;?></strong></span></a>
                    </a>
					<form method="post">
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <button class="dropdown-item" href="#" name="logout" type="submit">Log Out</button>
                        <!--<a class="dropdown-item" href="#">My account</a>-->
                    </div></form>
                </li>

            </ul>
            <!--/Navbar links-->
        </nav>
        <!-- /.Navbar -->

        <!-- Fixed button 
        <div class="fixed-action-btn clearfix d-none d-xl-block" style="bottom: 45px; right: 24px;">
            <a class="btn-floating btn-lg red">
                <i class="fa fa-pencil"></i>
            </a>

            <ul class="list-unstyled">
                <li><a class="btn-floating red"><i class="fa fa-star"></i></a></li>
                <li><a class="btn-floating yellow darken-1"><i class="fa fa-user"></i></a></li>
                <li><a class="btn-floating green"><i class="fa fa-envelope"></i></a></li>
                <li><a class="btn-floating blue"><i class="fa fa-shopping-cart"></i></a></li>
            </ul>
        </div>-->
        <!-- Fixed button -->

    </header>
    <!--Main Navigation-->

    <!--Main layout-->
        <!--Main layout-->
    <main>

        <div class="container-fluid">
			<?php
				for($a = 1; $a<=$garbage_dumps;$a++) {
					$sq = mysqli_query($conn, "SELECT * FROM gd$a ORDER BY id DESC LIMIT 1;");
					$dn = mysqli_num_rows($sq);

					if ($dn > 0) {
						while($row = mysqli_fetch_assoc($sq)) {
							$u1 = $row["y1"];
							$u2 = $row["y2"];
							$u3 = $row["y3"];
							$u4 = $row["y4"];
							$time = $row["time"];
						}
					}
			?>
                    <section>
            <!--Section: Analytical panel-->
            <section class="mb-5">

                <!--Card-->
                <div class="card card-cascade narrower">

                    <!--Section: Chart-->
					

                        <!--Grid row-->
                        <div class="row">

                            <!--Grid column-->
                            <div class="col-xl-5 col-md-12 mr-0">

                                <!--Card image-->
                                <div class="view view-cascade gradient-card-header primary-color">
                                    <h4 class="h4-responsive mb-0 font-weight-bold">Garbage Dump <?php echo $a;?></h4>
                                </div>
                                <!--/Card image-->

                                <!--Card content-->
                                <div class="card-body card-body-cascade pb-0">

                                    <!--Panel data-->
                                    <div class="row card-body pt-3">

                                        <!--First column-->
                                        <div class="col-md-12">

                                            <div class="progress mb-1 mt-1">
												<div class="progress-bar warning-color" role="progressbar" style="width: <?php echo $u1;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
											<!--Text-->
											<p class="grey-text mb-4">Dry Waste : <?php echo $u1;?>%</p>

											<div class="progress mb-1">
												<div class="progress-bar red accent-2" role="progressbar" style="width: <?php echo $u2;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
											<!--Text-->
											<p class="grey-text mb-4">Wet Waste : <?php echo $u2;?>%</p>

											<div class="progress mb-1">
												<div class="progress-bar primary-color" role="progressbar" style="width: <?php echo $u3;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
											<!--Text-->
											<p class="grey-text mb-4">Mixed Waste : <?php echo $u3;?>%</p>

											<div class="progress mb-1">
												<div class="progress-bar light-blue lighten-1" role="progressbar" style="width: <?php echo $u4;?>%" aria-valuenow="25" aria-valuemin="0"
													aria-valuemax="100"></div>
											</div>
											<!--Text-->
											<p class="grey-text mb-2">Medi Waste : <?php echo $u4;?>%</p>
											<p class="h6 font">Last Updated: <?php echo $time; ?></p>
										
											<a href="gd.php?id=<?php echo $a;?>" class="btn peach-gradient float-right">See More</a>
                                        </div>
                                        <!--/First column-->

                                    </div>
                                    <!--/Panel data-->

                                </div>
                                <!--/.Card content-->

                            </div>
                            <!--Grid column-->

                            <!--Grid column-->
                            <div class="col-xl-7 col-md-12 mb-4">

                                <!--Card image-->
                                <div class="view view-cascade gradient-card-header primary-color">

                                    <div id="map-container-google-1" class="z-depth-1-half map-container" style="height: 300px">
									  <iframe src="https://maps.google.com/maps?q=<?php echo $dumpsLat[$a-1]; ?>,<?php echo $dumpsLong[$a-1]; ?>&amp;t=&amp;z=10&amp;ie=UTF8&amp;iwloc=&amp;output=embed" frameborder="0" style="border:0" allowfullscreen=""></iframe>
									</div>

                                </div>
                                <!--/Card image-->

                            </div>
                            <!--Grid column-->

                        </div>
                        <!--Grid row-->

                    </section>
                    <!--Section: Chart-->
					
					
                </div>
                <!--/.Card-->

            </section>
			<?php
					}
					?>
			</div>
	</main>
    <!--Main layout-->

    <!--Footer
    <footer class="page-footer pt-0 mt-5 rgba-stylish-light">
        <div class="footer-copyright py-3 text-center">
              <div class="container-fluid">
                 © 2018 Copyright: <a href="https://mdbootstrap.com/bootstrap-tutorial/" target="_blank"> MDBootstrap.com </a>

            </div>
        </div>

    </footer>
    /.Footer-->
	
	<!-- Central Modal Small --><form method="post">
	<div class="modal fade" id="set" tabindex="-1" role="dialog" aria-labelledby="set"
	  aria-hidden="true">
	<!-- Change class .modal-sm to change the size of the modal -->
	  <div class="modal-dialog modal-lg" role="document">


		<div class="modal-content">
		  <div class="modal-header">
			<h4 class="modal-title w-100" id="myModalLabel">Settings</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			
			
			<select class="mdb-select md-form colorful-select dropdown-danger" name="sel[]" multiple>
			  <option value="" disabled selected>Choose your country</option>
			  <option value="vol">Volume and Capacity</option>
			  <option value="history">History</option>
			  <option value="pred">Predictions</option>
			</select>
			<label><h6>Select which information users can see</h6></label>
			<button class="btn-save btn btn-danger btn-sm" type="button">Save</button>
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
			<button type="submit" name="sel_sub" class="btn btn-primary btn-sm">Save changes</button>
		</div>
		</div>
	  </div>
	</div></form>
	<!-- Central Modal Small -->


    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <!--Initializations-->
    <script>
	
        // SideNav Initialization
        $(".button-collapse").sideNav();

        var container = document.querySelector('.custom-scrollbar');
        Ps.initialize(container, {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });

        // Data Picker Initialization
        $('.datepicker').pickadate();

        // Material Select Initialization
        $(document).ready(function () {
            $('.mdb-select').material_select();
        });

        // Tooltips Initialization
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <!-- Charts -->
</body>
</html>
