<!DOCTYPE html>
<?php
include 'db.php';
$status = 0;

if(isset($_POST['sub'])){
	$i1 = $_POST['i1'];
	$i2 = $_POST['i2'];
	$i3 = $_POST['i3'];
	$i4 = $_POST['i4'];
	$i5 = $_POST['i5'];
	$i6 = $_POST['i6'];
	
	$ps = "INSERT INTO accounts (fname, lname, email, pass, pno, role) VALUES ('$i1', '$i2', '$i3', '$i4', '$i5', '$i6')";
	
	if(mysqli_query($conn, $ps)) {
		$status = 1;
	} else {
		$status = -1;
	}
}

?>
<html lang="en" class="full-height">
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

	<style>
		.views {
			background: url("https://cdn.pixabay.com/photo/2017/12/29/18/47/nature-3048299_1280.jpg")no-repeat center center;
			background-size: cover;
		}

		.navbar {
			background-color: transparent;
		}

		.top-nav-collapse {
			background-color: #4285F4;
		}

		@media only screen and (max-width: 768px) {
			.navbar {
				background-color: #4285F4;
			}
		}

		html,
		body,
		header,
		.view {
		  height: 100%;
		}
	</style>
</head>

<body class="">

    <!--Main Navigation-->
    <header>

        <!-- Navbar -->
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark scrolling-navbar">
			<div class="container">
			<a class="navbar-brand" href="#"><strong>IOT Challenge 2019</strong></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
			  aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			  <span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
			  <ul class="navbar-nav mr-auto">
				
			  </ul>
			  <ul class="navbar-nav nav-flex-icons">
				<li class="nav-item">
				  <a class="nav-link" href="index.php"><strong>Sign in</strong></span></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link" href="#"><strong>About Us</strong></span></a>
				</li>
			  </ul>
			</div></div>
		  </nav>
        <!-- /.Navbar -->
    
    <!--Main Navigation-->

    <!--Main layout-->
        <!--Main layout-->
    
	
		<div class="view views intro-2" style="">
		<div class="full-bg-img">
		  <div class="mask rgba-purple-light flex-center">
			<div class="col mt-5">
			<div class="flex-center"><h3 class="display-5 text-white"> Analysis and Management of Smart Waste</h3></div>
			<div class="container text-center white-text wow fadeInUp col-md-4">
			
			<?php
			if($status==1) { 
			header( "refresh:5;url=index.php" );?>
				<div class="alert alert-success" role="alert">
				  Successfully Registered, Redirecting to Login Page in 5 seconds.
				</div>
			<?php } else if($status==-1) {?>
				<div class="alert alert-danger" role="alert">
				  Failed to Register, Please try again later.
				</div>
			<?php } ?>
			 <!-- Material form register -->
			<div class="card card-cascade narrower">

				<h5 class="view view-cascade card-header info-color white-text text-center py-4">
					<strong>Sign up</strong>
				</h5>

				<!--Card content-->
				<div class="card-body px-lg-5 pt-0">

					<!-- Form -->
					<form class="text-center" style="color: #757575;" method="post">

						<div class="form-row">
							<div class="col">
								<!-- First name -->
								<div class="md-form">
									<input type="text" id="materialRegisterFormFirstName" class="form-control" name="i1" required>
									<label for="materialRegisterFormFirstName">First name</label>
								</div>
							</div>
							<div class="col">
								<!-- Last name -->
								<div class="md-form">
									<input type="text" id="materialRegisterFormLastName" class="form-control" name="i2" required>
									<label for="materialRegisterFormLastName">Last name</label>
								</div>
							</div>
						</div>

						<!-- E-mail -->
						<div class="md-form mt-0">
							<input type="email" id="materialRegisterFormEmail" class="form-control" name="i3" required>
							<label for="materialRegisterFormEmail">E-mail</label>
						</div>

						<!-- Password -->
						<div class="md-form">
							<input type="password" id="materialRegisterFormPassword" class="form-control" aria-describedby="materialRegisterFormPasswordHelpBlock" name="i4" required>
							<label for="materialRegisterFormPassword">Password</label>
							<small id="materialRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
								At least 8 characters
							</small>
						</div>

						<!-- Phone number -->
						<div class="md-form">
							<input type="password" id="materialRegisterFormPhone" class="form-control" name="i5" aria-describedby="materialRegisterFormPhoneHelpBlock">
							<label for="materialRegisterFormPhone">Phone number</label>
							<small id="materialRegisterFormPhoneHelpBlock" class="form-text text-muted mb-4">
								Optional
							</small>
						</div>
						
						<div class="md-form">
							<select class="mdb-select md-form" name="i6">
							  <option value="" disabled selected>Role</option>
							  <option value="user">User</option>
							  <option value="auth">Authority</option>
							</select>
						</div>

						<!-- Sign up button -->
						<button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit" name="sub">Sign up</button>

						<hr>

						<!-- Terms of service -->
						<p>By clicking
							<em>Sign up</em> you agree to our
							<a href="" target="_blank">terms of service</a>

					</form>
					<!-- Form -->

				</div>

			</div></div>
			<!-- Material form register -->
			</div>
		  </div>
		</div>
	  </div>
	</header>
	

    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
	<script type="text/javascript">
		// Material Select Initialization
		$(document).ready(function() {
		 $('.mdb-select').materialSelect();
		});
	</script>
</body>
</html>
