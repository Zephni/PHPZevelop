<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title><?php echo $PHPZevelop->CFG->SiteTitle; ?> - <?php echo $PHPZevelop->CFG->PageTitle; ?></title>

		<!-- JAVASCRIPT -->
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="<?php $PHPZevelop->Path->GetScript("main.js?".time()); ?>"></script>

		<!-- Bootstrap 4 -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" crossorigin="anonymous">

		<!-- DataTables -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>

		<!-- Custom CSS -->
		<link rel="stylesheet" href="<?php $PHPZevelop->Path->GetCSS("style.css?".time()); ?>" />
	</head>
	<body>

		
		<style type="text/css">
			html,body {height: 100%; padding-top: 28px;}
			#sidebar {width: 300px;}
			#content {background: white;}
			#logo-container {width: 50%; margin: auto;}

			.sticky-nav {z-index: 2; position: fixed; top: 0; width: 100%;}
		</style>

		<!-- Navigation -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-info sticky-nav">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">
				<a href="" class="text-light">PHPZevelop</a>
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item active">
					<a class="nav-link" href="<?php $PHPZevelop->Path->GetPage("/"); ?>">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php $PHPZevelop->Path->GetPage("manage-account"); ?>">Manage Account <small>(<?php echo $Administrator->Data["username"]; ?>)</small></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php $PHPZevelop->Path->GetPage("logout"); ?>">Logout</a>
				</li>
			</ul>
			</div>
		</div>
		</nav>

		<div class="container-fluid h-100">

			<div class="row justify-content-center h-100"> 
				<div id="sidebar" class="col-md-auto d-none d-lg-block p-4 border-right border-info bg-light">
					<div id="logo-container">
						<img src="<?php $PHPZevelop->Path->GetImage("Logo.png"); ?>" alt="Logo" class="img-fluid" />
					</div>
				</div>

				<div id="content" class="col-md p-5 bg-light">
					<?php echo $PHPZevelop->PageContent; ?>
				</div>
			</div>

		</div>
	</body>
</html>