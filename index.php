<!DOCTYPE html>
<?php
include 'db.php';
session_start();
$status = 0;

if(isset($_POST['sub'])) {
	$user = $_POST['user'];
	$pass = $_POST['pass'];

	$qs = mysqli_query($conn, "SELECT * FROM accounts where email='$user' and pass='$pass'");
	$count1=mysqli_num_rows($qs);
	
	if($count1 > 0) {
		$_SESSION['user'] = $user;
		$_SESSION['pass'] = $pass;
		
		header("location:home.php");
		exit();
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
			background: url("img/back.jpg")no-repeat center center;
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
				  <a class="nav-link" href="signup.php"><strong>Register</strong></span></a>
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
			<div class="col">
			<div class="flex-center"><h3 class="display-5 text-white"> Analysis and Management of Smart Waste</h3></div>
			<div class="container text-center white-text wow fadeInUp col-md-4">
			<?php
			if($status==-1) {?>
				<div class="alert alert-danger" role="alert">
				  Invalid Credientials, Please try again later.
				</div>
			<?php } ?>
			 <!-- Material form login -->
				<div class="card card-cascade narrower">

				  <h5 class="view view-cascade card-header info-color white-text text-center py-4">
					<strong>Sign in</strong>
				  </h5>

				  <!--Card content-->
				  <div class="card-body px-lg-5 pt-0">

					<!-- Form -->
					<form class="text-center" style="color: #757575;" method="post">

					  <!-- Email -->
					  <div class="md-form">
						<input type="email" id="materialLoginFormEmail" class="form-control" name="user">
						<label for="materialLoginFormEmail">E-mail</label>
					  </div>

					  <!-- Password -->
					  <div class="md-form">
						<input type="password" id="materialLoginFormPassword" class="form-control" name="pass">
						<label for="materialLoginFormPassword">Password</label>
					  </div>

					  <div class="d-flex justify-content-around">
						<div>
						  <!-- Remember me -->
						  <div class="form-check">
							<input type="checkbox" class="form-check-input" id="materialLoginFormRemember">
							<label class="form-check-label" for="materialLoginFormRemember">Remember me</label>
						  </div>
						</div>
						<div>
						  <!-- Forgot password -->
						  <a href="">Forgot password?</a>
						</div>
					  </div>

					  <!-- Sign in button -->
					  <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit" name="sub">Sign in</button>

					  <!-- Register -->
					  <p>Not a member?
						<a href="signup.php">Register</a>
					  </p>

					</form>
					<!-- Form -->

				  </div>

				</div></div>
				<!-- Material form login -->
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
</body>
</html>
