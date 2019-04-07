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
	$as = "INSERT INTO settings (vol,history,pred,direc) VALUES ('0','0','0','0')";
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

$settings_query = mysqli_query($conn, "SELECT * FROM settings ORDER BY id DESC LIMIT 1");
$settings_row = mysqli_fetch_assoc($settings_query);

$direc = $settings_row["direc"];

$d=1;
$gstopc = 0;
while ($d <= $garbage_dumps) {
	
$history_q = mysqli_query($conn, "SELECT * FROM gd$d");
$history_n = mysqli_num_rows($history_q);
	
if ($history_n > 0) {
	$h = 0;
	while($row = mysqli_fetch_assoc($history_q)) {
	$h1[$h] = $row["y1"];
	$h2[$h] = $row["y2"];
	$h3[$h] = $row["y3"];
	$h4[$h] = $row["y4"];
	$formatted_datetime[$h] = date('Y-m-d H:i:s', strtotime($row["time"]));
	$formatted_time[$h] = date('g:i a', strtotime($row["time"]));
	$formatted_date[$h] = date('F j, Y', strtotime($row["time"]));
	$h++;
	}
	
	$u1c = $h1[$history_n-1]-$h1[$history_n-2];
	$u2c = $h2[$history_n-1]-$h2[$history_n-2];
	$u3c = $h3[$history_n-1]-$h3[$history_n-2];
	$u4c = $h4[$history_n-1]-$h4[$history_n-2];
}

$initial1 = $formatted_date[0];
$initial2 = $formatted_date[0];
$initial3 = $formatted_date[0];
$initial4 = $formatted_date[0];

$prediction_days1 = 0;
$prediction_days2 = 0;
$prediction_days3 = 0;
$prediction_days4 = 0;

$predc1 = 0;
$predc2 = 0;
$predc3 = 0;
$predc4 = 0;

$last1 = 0;
$last2 = 0;
$last3 = 0;
$last4 = 0;

for ($predn = 0; $predn < $history_n; $predn++) {
	if($h1[$predn] > 85) {
		if(!($predn>0)) { 
			$predc1++;
			$prediction_days1 += (strtotime($formatted_date[$predn]) - strtotime($initial1))/ 86400;
			$initial1 = $formatted_date[$predn];
			$last1 = ($predn < $history_n-1) ? $formatted_date[$predn+1] : $formatted_date[$predn];
		} else if(!($h1[$predn-1] > 85)) {
			$predc1++;
			$prediction_days1 += (strtotime($formatted_date[$predn]) - strtotime($initial1))/ 86400;
			$initial1 = $formatted_date[$predn];
			$last1 = ($predn < $history_n-1) ? $formatted_date[$predn+1] : $formatted_date[$predn];
		}
	}
	if($h2[$predn] > 85) {
		if(!($predn>0)) {
			$predc2++;
			$prediction_days2 += (strtotime($formatted_date[$predn]) - strtotime($initial2))/ 86400;
			$initial2 = $formatted_date[$predn];
			$last2 = ($predn < $history_n-1) ? $formatted_date[$predn+1] : $formatted_date[$predn];
		} else if(!($h2[$predn-1] > 85)) {
			$predc2++;
			$prediction_days2 += (strtotime($formatted_date[$predn]) - strtotime($initial2))/ 86400;
			$initial2 = $formatted_date[$predn];
			$last2 = ($predn < $history_n-1) ? $formatted_date[$predn+1] : $formatted_date[$predn];	
		}
	}
	if($h3[$predn] > 85) {
		if(!($predn>0)) {
			$predc3++;
			$prediction_days3 += (strtotime($formatted_date[$predn]) - strtotime($initial3))/ 86400;
			$initial3 = $formatted_date[$predn];
			$last3 = ($predn < $history_n-1) ? $formatted_date[$predn+1] : $formatted_date[$predn];
		} else if(!($h3[$predn-1] > 85)) {
			$predc3++;
			$prediction_days3 += (strtotime($formatted_date[$predn]) - strtotime($initial3))/ 86400;
			$initial3 = $formatted_date[$predn];
			$last3 = ($predn < $history_n-1) ? $formatted_date[$predn+1] : $formatted_date[$predn];	
		}
		
	}
	if($h4[$predn] > 85) {
		if(!($predn>0)) {
			$predc4++;
			$prediction_days4 += (strtotime($formatted_date[$predn]) - strtotime($initial4))/ 86400;
			$initial4 = $formatted_date[$predn];
			$last4 = ($predn < $history_n-1) ? $formatted_date[$predn+1] : $formatted_date[$predn];
		} else if(!($h4[$predn-1] > 85)) {
			$predc4++;
			$prediction_days4 += (strtotime($formatted_date[$predn]) - strtotime($initial4))/ 86400;
			$initial4 = $formatted_date[$predn];
			$last4 = ($predn < $history_n-1) ? $formatted_date[$predn+1] : $formatted_date[$predn];	
		}

	}
}

if($predc1 != 0) $prediction_days1 /= $predc1;
if($predc2 != 0) $prediction_days2 /= $predc2;
if($predc3 != 0) $prediction_days3 /= $predc3;
if($predc4 != 0) $prediction_days4 /= $predc4;

$predavg = strtotime(($prediction_days1 + $prediction_days2 + $prediction_days3 + $prediction_days4)/4);
$newpred = time();
if ($predavg-$newpred < 3) {
	$gstops[$gstopc] = $d;
	$gstopc++;
}	

$d++;
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
                    
					<li><a href="home.php" class="collapsible-header waves-effect"><i class="fas fa-home"></i> Home</a></li>

					<?php for($d = 1; $d <= $garbage_dumps; $d++) {?>
                    <li><a href="gd.php?id=<?php echo $d;?>" class="collapsible-header waves-effect"><i class="fas fa-bolt"></i> Garbage Dump <?php echo $d;?></a></li>
					<?php } if($direc == 1 || !($role == 'user')) {?>
					<li><a href="direc.php" class="collapsible-header waves-effect active"><i class="fab fa-google"></i> Directions </a></li>
					<?php } ?>
					
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
                <p> Directions for Garbage Collection
                    <a class="btn btn-md btn-outline-green waves-effect"><span class=""><?php echo $gstopc;?> Garbage Dump Stops</span></a>
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
  <main class=" m-0">
    <div class="container-fluid m-0 p-0">

      <!--Google map-->
      <div id="map-container" class="map-container" style="height: 500px">
        <?php
			$tstring = "";
			for ($fd = 0; $fd < $gstopc; $fd++) {
				$tstring .= $dumpsLat[$gstops[$fd]-1];
				$tstring .= ",";
				$tstring .= $dumpsLong[$gstops[$fd]-1];
				if($fd != $gstopc-1) {
					$tstring .= "|";
				}
			}
			
		?>
		<iframe src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyDxBPatrzw6Amjm8OpOTwsSTMhaLKbN5jI&origin=IIT+Bombay&destination=IIT+Bombay&waypoints=<?php echo $tstring; ?>&avoid=tolls|highways" frameborder="0" style="border:0"
          ></iframe>
      </div>

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
			  <option value="" disabled selected>Choose your options</option>
			  <option value="vol">Volume and Capacity</option>
			  <option value="history">History</option>
			  <option value="pred">Predictions</option>
			  <option value="direc">Directions</option>
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
