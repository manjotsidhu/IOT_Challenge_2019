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

$id = $_GET["id"];

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

$settings_query = mysqli_query($conn, "SELECT * FROM settings ORDER BY id DESC LIMIT 1");
$settings_row = mysqli_fetch_assoc($settings_query);

$vol = $settings_row["vol"];
$history = $settings_row["history"];
$pred = $settings_row["pred"];

if(isset($_POST['logout'])) {
	session_destroy();
	header('Location:index.php');
}

$history_q = mysqli_query($conn, "SELECT * FROM gd$id");
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

// Predictions

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

?>
<!DOCTYPE html>
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
	<!-- MDBootstrap Datatables  -->
	<link href="css/datatables.min.css" rel="stylesheet">

    <!-- Your custom styles (optional) -->
    <style>
	.sidebar-fixed{height:100vh;width:270px;-webkit-box-shadow:0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);box-shadow:0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);z-index:1050;background-color:#fff;padding:0 1.5rem 1.5rem}.sidebar-fixed .list-group .active{-webkit-box-shadow:0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);box-shadow:0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);-webkit-border-radius:5px;border-radius:5px}.sidebar-fixed .logo-wrapper{padding:2.5rem}.sidebar-fixed .logo-wrapper img{max-height:50px}@media (min-width:1200px){.navbar,.page-footer,main{padding-left:270px}}@media (max-width:1199.98px){.sidebar-fixed{display:none}}

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

            <!-- Side navigation links -->
            
			<li>
			
				<ul class="collapsible collapsible-accordion">
                    
					<li><a href="home.php" class="collapsible-header waves-effect"><i class="fas fa-home"></i> Home</a></li>

					<?php for($d = 1; $d <= $garbage_dumps; $d++) {?>
                    <li><a href="gd.php?id=<?php echo $d;?>" class="collapsible-header waves-effect <?php if($id==$d) echo "active";?>"><i class="fas fa-bolt"></i> Garbage Dump <?php echo $d;?></a></li>
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
                <a href="#" data-activates="slide-out" class="button-collapse black-text"><i class="fas fa-bars"></i></a>
            </div>
            <!-- Breadcrumb-->
            <div class="breadcrumb-dn mr-auto ml-2">
                <p>Garbage Dump <?php echo $id;?>
				
				<?php
					
						$sq = mysqli_query($conn, "SELECT * FROM gd$id ORDER BY id DESC LIMIT 1;");
						$dn = mysqli_num_rows($sq);
						$cond = 0;
						
						if ($dn > 0) {
							while($row = mysqli_fetch_assoc($sq)) {
								$u1 = $row["y1"];
								$u2 = $row["y2"];
								$u3 = $row["y3"];
								$u4 = $row["y4"];
								$time = $row["time"];
							}
						}
						
						if ($u1 < 85 && $u2 < 85 && $u3 < 85 && $u4 < 85) {
							$cond = 1;
						}
					
					?>
					<a class="btn btn-md <?php if($cond == 1) {?>btn-outline-success<?php } else {?>btn-outline-danger<?php }?> btn-rounded waves-effect"><span class="">Condition : 
					<?php if ($cond == 1) { echo "Healthy"; } else { echo "Un-Healthy"; }?>
					</span></a>
					<?php if($vol == 1 || !($role == 'user')) {?>
					<a class="btn btn-md btn-outline-primary btn-rounded waves-effect"><span class="">Capacity : <?php echo $dumpsCap[$id-1];?> Litres
					</span></a>
					<?php } ?>
				</p>
			</div>

            <!--Navbar links-->
            <ul class="nav navbar-nav nav-flex-icons ml-auto">

                <!-- Dropdown 
                <li class="nav-item">
                    <a class="nav-link waves-effect"><i class="fas fa-envelope"></i> <span class="clearfix d-none d-sm-inline-block">Location</span></a>
                </li>-->
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

    </header>
    <!--Main Navigation-->

    <!--Main layout-->
    <main>
        <div class="container-fluid">

            <!--Section: Intro-->
            <section class="mt-lg-5">

                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-xl-3 col-md-6 mb-4">

                        <!--Card-->
                        <div class="card card-cascade cascading-admin-card narrower">
                            <!--Card Data-->

							
                            <div class="card primary-color">    
                                <div class="data">
									<i class="view view-cascade card-header fas fa-trash info-color fa-3x float-right" style="color:white"></i>
                                    <div class="ml-2 mt-2 text-white">
									DRY WASTE
                                    <h4 class="font-weight-bold white-text"><?php echo $u1;?>%</h4>
									</div>
								</div>
                            </div>
                            <!--/.Card Data-->

                            <!--Card content-->
                            <div class="card-body card-body-cascade">
                                <div class="progress mb-3">
                                    <div class="progress-bar <?php if($u1<85) { echo "bg-primary"; } else { echo "red accent-2";}?>" role="progressbar" style="width: <?php echo $u1;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <!--Text -->
                                <?php if($vol == 1 || !($role == 'user')) {?><p class="card-text"><?php echo ($dumpsCap[$id-1]*$u1)/100;?>L is filled</p><?php }?>
                            </div>
                            <!--/.Card content-->

                        </div>
                        <!--/.Card-->

                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-xl-3 col-md-6 mb-4">

                        <!--Card-->
                        <div class="card card-cascade cascading-admin-card narrower">

                            <!--Card Data-->
                            <div class="card primary-color">    
                                <div class="data">
									<i class="view view-cascade card-header fas fa-trash success-color fa-3x float-right" style="color:white"></i>
                                    <div class="ml-2 mt-2 text-white">
									WET WASTE
                                    <h4 class="font-weight-bold white-text"><?php echo $u2;?>%</h4>
									</div>
								</div>
                            </div>

                            <!--Card content-->
                            <div class="card-body card-body-cascade">
                                <div class="progress mb-3">
                                    <div class="progress-bar <?php if($u2<85) { echo "bg-primary"; } else { echo"red accent-2";}?>" role="progressbar" style="width: <?php echo $u2;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <!--Text-->
                                <?php if($vol == 1 || !($role == 'user')) {?><p class="card-text"><?php echo ($dumpsCap[$id-1]*$u2)/100;?>L is filled</p><?php }?>
                            </div>
                            <!--/.Card content-->

                        </div>
                        <!--/.Card-->

                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-xl-3 col-md-6 mb-4">

                        <!--Card-->
                        <div class="card card-cascade cascading-admin-card narrower">

                            <!--Card Data-->
                            <div class="card primary-color">    
                                <div class="data">
									<i class="view view-cascade card-header fas fa-trash warning-color fa-3x float-right" style="color:white"></i>
                                    <div class="ml-2 mt-2 text-white">
									MIXED WASTE
                                    <h4 class="font-weight-bold white-text"><?php echo $u3;?>%</h4>
									</div>
								</div>
                            </div>
                            <!--/.Card Data-->

                            <!--Card content-->
                            <div class="card-body card-body-cascade">
                                <div class="progress mb-3">
                                    <div class="progress-bar <?php if($u3<85) { echo "bg-primary"; } else { echo"red accent-2";}?>" role="progressbar" style="width: <?php echo $u3;?>%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <!--Text-->
                                <?php if($vol == 1 || !($role == 'user')) {?><p class="card-text"><?php echo ($dumpsCap[$id-1]*$u3)/100;?>L is filled</p><?php }?>
                            </div>
                            <!--/.Card content-->

                        </div>
                        <!--/.Card-->

                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-xl-3 col-md-6 mb-4">

                        <!--Card-->
                        <div class="card card-cascade cascading-admin-card narrower">

                            <!--Card Data-->
                            <div class="card primary-color">    
                                <div class="data">
									<i class="view view-cascade card-header fas fa-trash danger-color-dark fa-3x float-right" style="color:white"></i>
                                    <div class="ml-2 mt-2 text-white">
									MEDICAL WASTE
                                    <h4 class="font-weight-bold white-text"><?php echo $u4;?>%</h4>
									</div>
								</div>
                            </div>
                            <!--/.Card Data-->

                            <!--Card content-->
                            <div class="card-body card-body-cascade">
                                <div class="progress mb-3">
                                    <div class="progress-bar <?php if($u4<85) { echo "bg-primary"; } else { echo"red accent-2";}?>" role="progressbar" style="width: <?php echo $u4;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <!--Text-->
                                <?php if($vol == 1 || !($role == 'user')) {?><p class="card-text"><?php echo ($dumpsCap[$id-1]*$u4)/100;?>L is filled</p><?php }?>
                            </div>
                            <!--/.Card content-->

                        </div>
                        <!--/.Card-->

                    </div>
                    <!--Grid column-->

                </div>
                <!--Grid row-->

            </section>
            <!--Section: Intro-->

            <div style="height: 5px"></div>

            <!--Section: Main panel-->
			<?php if($history == 1 || !($role == 'user')) {?>
            <section class="mb-5">

                <!--Card-->
                <div class="card card-cascade narrower">

                    <!--Section: Chart-->
                    <section>
                            <!--Grid column-->
                            <div class="mb-2">

                                <!--Card image-->
                                <div class="view view-cascade gradient-card-header blue-gradient">
									<h5>Garbage Dump Analysis and History</h5>
                                    <!-- Chart -->
                                    <canvas id="lineChart"></canvas>

                                </div>
                                <!--/Card image-->
								
								<!--Card content-->
							  <div class="card-body card-body-cascade blue-panel text-center">

								<!--Second row-->
								<div class="row mx-3 mb-4 text-center">
								  <div class="col mt-1 mb-2">

									<div class="btn-group mt-1" data-toggle="buttons">
									  <label class="btn btn-info btn-md">
										<input type="checkbox" name="options" id="option1" autocomplete="off" checked>Dry Waste 
										<i class="fas <?php if($u1c>0) {echo "fa-arrow-up";} else { echo "fa-arrow-down";}?> ml-2 white-text"></i> <strong> <?php echo abs($u1c);?>%</strong>
									  </label>
									</div>
									<div class="btn-group mt-1" data-toggle="buttons">
									  <label class="btn btn-success btn-md">
										<input type="checkbox" name="options" id="option2" autocomplete="off">Wet Waste 
										<i class="fas <?php if($u2c>0) {echo "fa-arrow-up";} else { echo "fa-arrow-down";}?> ml-2 white-text"></i> <strong> <?php echo abs($u2c);?>%</strong>
									  </label>
									</div>
									<div class="btn-group mt-1" data-toggle="buttons">
									  <label class="btn btn-warning btn-md">
										<input type="checkbox" name="options" id="option2" autocomplete="off">Mixed Waste 
										<i class="fas <?php if($u3c>0) {echo "fa-arrow-up";} else { echo "fa-arrow-down";}?> ml-2 white-text"></i> <strong> <?php echo abs($u3c);?>%</strong>
									  </label>
									</div>
									<div class="btn-group mt-1" data-toggle="buttons">
									  <label class="btn btn-danger btn-md">
										<input type="checkbox" name="options" id="option2" autocomplete="off">Medical Waste 
										<i class="fas <?php if($u4c>0) {echo "fa-arrow-up";} else { echo "fa-arrow-down";}?> ml-2 white-text"></i> <strong> <?php echo abs($u4c);?>%</strong>
									  </label>
									</div>

								  </div>
								</div>
								<!--/Second row-->

								<!--Third row-->
								<div class="row mb-0">
								  <!--First column-->
								  <div class="col-md-12">

									<!--Panel content-->
									<div class="card-body pt-0">
									  <!--Table-->
									  <div class="table-responsive">
										<table id="dtBasicExample" class="table table-striped table-bordered" cellspacing="0" width="100%">
										  <thead>
											<tr>
											  <th class="th-sm">Dry Waste (%)
											  </th>
											  <th class="th-sm">Wet Waste (%)
											  </th>
											  <th class="th-sm">Mixed Waste (%)
											  </th>
											  <th class="th-sm">Medical Waste (%)
											  </th>
											  <th class="th-sm">Date
											  </th>
											  <th class="th-sm">Time
											  </th>
											</tr>
										  </thead>
										  <tbody>
										  <?php
											for ($entry = 0; $entry < $history_n; $entry++) {
										  ?>
											<tr>
												<td><?php echo $h1[$entry];?></td>
												<td><?php echo $h2[$entry];?></td>
												<td><?php echo $h3[$entry];?></td>
												<td><?php echo $h4[$entry];?></td>
												<td><?php echo $formatted_date[$entry];?></td>
												<td><?php echo $formatted_time[$entry];?></td>
											</tr>
										  <?php } ?>
										  <tfoot>
											<tr>
											  <th>Dry Waste (%)
											  </th>
											  <th>Wet Waste (%)
											  </th>
											  <th>Mixed Waste (%)
											  </th>
											  <th>Medical Waste (%)
											  </th>
											  <th>Date
											  </th>
											  <th>Time
											  </th>
											</tr>
										  </tfoot>
										</table>

									  </div>
									  <!--/Table-->

									</div>
									<!--/.Panel content-->

								  </div>
								  <!--/First column-->
								</div>
								<!--/Third row-->

							  </div>
							  <!--/.Card content-->

                            </div>
                            <!--Grid column-->

                    </section>
                    <!--Section: Chart-->
                </div>
                <!--/.Card-->

            </section>
            <!--Section: Main panel-->
			<?php } ?>
            
			<!--Section: Main panel-->
			<?php if($pred == 1 || !($role == 'user')) {?>
            <section class="mb-5">

                <!--Card-->
                <div class="card card-cascade narrower">
					<div class="view view-cascade gradient-card-header peach-gradient">
						<h5>Garbage Dump Predictions</h5>
					</div>
					
					<div class="card-body card-body-cascade">
						<section class="text-center">

						  <!-- Grid row -->
						  <div class="row">

							<!-- Grid column -->
							<div class="col-lg-3 col-md-12 mb-lg-0 mb-2">

							  <!-- Card -->
							  <div class="pricing-card card">

								<!-- Content -->
								<div class="card-body">
								  <h5 class="font-weight-bold mt-3">Dry Waste Collection</h5>

								  <!--Price -->
									<div class="pt-2">
									  <h2 class="number red-text mb-2"><?php echo Date('F j, Y', strtotime("+$prediction_days1 days")); ?></h2>
									</div>

									<ul class="striped mb-0 pb-0">
									  <li>
										<p><strong>85%</strong> fill prediction</p>
									  </li>
									  <li>
										<p>Every <strong><?php echo $prediction_days1;?></strong> day(s)</p>
									  </li>
									  <li>
										<p>Last Collected <strong><?php echo $last1;?></strong></p>
									  </li>
									  <li>
										<p><strong><?php echo $predc1;?></strong> times waste collected</p>
									  </li>
									</ul>
								  </div>
								  <!-- Content -->

							  </div>
							  <!-- Card -->

							</div>
							<!-- Grid column -->

							<!--  Grid column  -->
							<div class="col-lg-3 col-md-6 mb-md-0 mb-2">

						
								<!-- Pricing card -->
								<div class="pricing-card card">

								  <!-- Content -->
								  <div class="card-body">
									<h5 class="font-weight-bold mt-2">Wet Waste Collection</h5>
									<!--Price -->
									<div class="pt-2">
									  <h2 class="number red-text mb-2"><?php echo Date('F j, Y', strtotime("+$prediction_days2 days")); ?></h2>
									</div>

									<ul class="striped mb-0 pb-0">
									  <li>
										<p><strong>85%</strong> fill prediction</p>
									  </li>
									  <li>
										<p>Every <strong><?php echo $prediction_days2;?></strong> day(s)</p>
									  </li>
									  <li>
										<p>Last Collected <strong><?php echo $last2;?></strong></p>
									  </li>
									  <li>
										<p><strong><?php echo $predc2;?></strong> times waste collected</p>
									  </li>
									</ul>
								  </div>
								  <!-- Content -->

								</div>
								<!-- Pricing card -->

							</div>
							<!-- Grid column -->

							<!-- Grid column -->
							<div class="col-lg-3 col-md-12 mb-lg-0 mb-4">

							  <!-- Card -->
							  <div class="pricing-card card">

								<!-- Content -->
								<div class="card-body">
								  <h5 class="font-weight-bold mt-3">Mixed Waste Collection</h5>

								  <!--Price -->
									<div class="pt-2">
									  <h2 class="number red-text mb-2"><?php echo Date('F j, Y', strtotime("+$prediction_days3 days")); ?></h2>
									</div>

									<ul class="striped mb-0 pb-0">
									  <li>
										<p><strong>85%</strong> fill prediction</p>
									  </li>
									  <li>
										<p>Every <strong><?php echo $prediction_days3;?></strong> day(s)</p>
									  </li>
									  <li>
										<p>Last Collected <strong><?php echo $last3;?></strong></p>
									  </li>
									  <li>
										<p><strong><?php echo $predc3;?></strong> times waste collected</p>
									  </li>
									</ul>
								  </div>
								  <!-- Content -->

							  </div>
							  <!-- Card -->

							</div>
							<!-- Grid column -->
							
							<!-- Grid column -->
							<div class="col-lg-3 col-md-6">

							  <!-- Card -->
							  <div class="pricing-card card">

								<!-- Content -->
								<div class="card-body">
								  <h5 class="font-weight-bold mt-3">Medical Waste Collection</h5>

								  <!--Price -->
									<div class="pt-2">
									  <h2 class="number red-text mb-2"><?php echo Date('F j, Y', strtotime("+$prediction_days4 days")); ?></h2>
									</div>

									<ul class="striped pb-0 mb-0">
									  <li>
										<p><strong>85%</strong> fill prediction</p>
									  </li>
									  <li>
										<p>Every <strong><?php echo $prediction_days4;?></strong> day(s)</p>
									  </li>
									  <li>
										<p>Last Collected <strong><?php echo $last4;?></strong></p>
									  </li>
									  <li>
										<p><strong><?php echo $predc4;?></strong> times waste collected</p>
									  </li>
									</ul>
								  </div>
								  <!-- Content -->

							  </div>
							  <!-- Card -->

							</div>
							<!-- Grid column -->

						  </div>
						  <!-- Grid row -->

						</section>
					</div>
                    
                </div>
                <!--/.Card-->

            </section>
            <!--Section: Main panel-->
			<?php } ?>

            </div>
    </main>
    <!--Main layout-->

    <!--Footer-->
    <footer class="page-footer pt-0 mt-5 rgba-stylish-light">

        <!--Copyright
        <div class="footer-copyright py-3 text-center">
              <div class="container-fluid">
                 © 2019 Copyright: <a href="" target="_blank">  </a>
            </div>
        </div>-->
        <!--/.Copyright-->

    </footer>
    <!--/.Footer-->
	
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
    <script type="text/javascript" src="js/mdbs.min.js"></script>
	<!-- MDBootstrap Datatables  -->
	<script type="text/javascript" src="js/datatables.min.js"></script>
    <!--Initializations-->
    <script>
		// DataTable
		$(document).ready(function () {
		$('#dtBasicExample').DataTable({
			autoWidth: true,
			
		});
		$('.dataTables_length').addClass('bs-select');
		});
	
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
    <script>
        // Small chart
        $(function () {
            $('.min-chart#chart-sales').easyPieChart({
                barColor: "#FF5252",
                onStep: function (from, to, percent) {
                    $(this.el).find('.percent').text(Math.round(percent));
                }
            });
        });

        //Main chart
        var ctxL = document.getElementById("lineChart").getContext('2d');
        var myLineChart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: [
				<?php 
				for($m=0; $m < $history_n; $m++) {
					echo "\"$formatted_datetime[$m]\"";
					if(!($m == $history_n)) {
					echo ",";
					}
				}
				?>
				],
                
				
				datasets: [{
                    label: "Dry Waste",
                    fillColor: "#fff",
                    backgroundColor: 'rgba(255, 255, 255, .3)',
                    borderColor: '#33b5e5',
                    data: [
					<?php
						for($m=0; $m < $history_n; $m++) {
						echo "$h1[$m]";
						if(!($m == $history_n)) {
						echo ",";
						}
					}?>
					],
                
				}, {
					label: "WetWaste ",
					fillColor: "#FFF",
                    backgroundColor: 'rgba(255, 255, 255, .3)',
                    borderColor: '#00C851',
                    data: [
					<?php
						for($m=0; $m < $history_n; $m++) {
						echo "$h2[$m]";
						if(!($m == $history_n)) {
						echo ",";
						}
					}?>
					],
				
				}, {
					label: "Mixed Waste",
                    fillColor: "#FFF",
                    backgroundColor: 'rgba(255, 255, 255, .3)',
                    borderColor: '#ffbb33',
                    data: [
					<?php
						for($m=0; $m < $history_n; $m++) {
						echo "$h3[$m]";
						if(!($m == $history_n)) {
						echo ",";
						}
					}?>
					],
					
				}, {
					label: "Medical Waste",
                    fillColor: "#FFF",
                    backgroundColor: 'rgba(255, 255, 255, .3)',
                    borderColor: '#ff4444',
                    data: [
					<?php
						for($m=0; $m < $history_n; $m++) {
						echo "$h4[$m]";
						if(!($m == $history_n)) {
						echo ",";
						}
					}?>
					],
					
				}]
            },
            options: {
                legend: {
                    labels: {
                        fontColor: "#fff",
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: true,
                            color: "rgba(255,255,255,.25)"
                        },
                        ticks: {
                            fontColor: "#fff",
                        },
                    }],
                    yAxes: [{
                        display: true,
                        gridLines: {
                            display: true,
                            color: "rgba(255,255,255,.25)"
                        },
                        ticks: {
                            fontColor: "#fff",
                        },
                    }],
                }
            }
        });
    </script>

</body>
</html>
