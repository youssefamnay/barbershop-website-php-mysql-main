<!DOCTYPE html>
<html lang="en">

	<head>
				<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
		<meta charset="utf-8">
	  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  	<meta name="description" content="Barbershop Booking Website">
	  	<meta name="author" content="ISGA Students">

  		<title>Tableau de bord</title>

  		<!-- FONTS FILE -->
  		<link href="fonts/css/all.min.css" rel="stylesheet" type="text/css">

  		<!-- Nunito FONT FAMILY FILE -->
  		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  		<!-- CSS FILES -->
  		<link href="sb-admin-2.min.css" rel="stylesheet">
  		<link href="main.css" rel="stylesheet">

  		<!-- Barber Icons -->
  		<link rel="stylesheet" type="text/css" href="fonts/css/all.min.css">
  		<link rel="stylesheet" type="text/css" href="css/barber-icons.css">
<style>

	.bg-gradient-primary {
		background-color: #6a6b6c;
		background-image: linear-gradient(180deg, #373737 10%, #000000 100%);
		background-size: cover;
	}
</style>
	</head>
<?php
include "connect.php";

if (isset($_SESSION['user_id'])) {
    // User is logged in, retrieve user's first name
    $user_id = $_SESSION['user_id'];
	$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions for errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Fetch data as associative arrays
    // Add any other desired options
];
    
	$pdo = new PDO($dsn, $user, $pass, $options);

        // Retrieve user's first name from the database
        $sql = "SELECT first_name FROM clients WHERE client_id = :client_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':client_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get the user's first name
        $first_name = $user['first_name'];
	
}
?>
	<body id="page-top">

  		<!-- Page Wrapper -->
  		<div id="wrapper">
		
			<!-- Sidebar -->
			<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

		  		<!-- Sidebar - Brand -->
		  		<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
					<div class="sidebar-brand-icon rotate-n-15">
			  			<i class="bs bs-scissors-1"></i>
					</div>
					<div class="sidebar-brand-text mx-3">Barbershop</div>
		  		</a>

		  		<!-- Divider -->
		  		<hr class="sidebar-divider my-0">

			  	<!-- Nav Item - Dashboard -->
			  	<li class="nav-item active">
					<a class="nav-link" href="index.php">
				  		<i class="fas fa-fw fa-tachometer-alt"></i>
				  		<span>Tableau de bord</span>
				  	</a>
			  	</li>

	  			<!-- Divider -->
	  			<hr class="sidebar-divider">

	  			<!-- Heading -->
	  			<div class="sidebar-heading">
					Autres
	  			</div>

	  			<!-- Nav Item - Pages Collapse Menu -->
	  			<li class="nav-item">
					<a class="nav-link" href="monprofil.php">
		  				<i class="fas fa-list"></i>
		  				<span>Mon profil</span>
		  			</a>
	  			</li>
	  		

	  			<!-- Divider -->
	  			<hr class="sidebar-divider">

	  		
	  		
	  
	  			<!-- Divider -->
	  			<hr class="sidebar-divider d-none d-md-block">

	  			<!-- Sidebar Toggler (Sidebar) -->
	  			<div class="text-center d-none d-md-inline">
					<button class="rounded-circle border-0" id="sidebarToggle"></button>
	  			</div>
			</ul>
			
			<!-- End of Sidebar -->

			<!-- Content Wrapper -->
			<div id="content-wrapper" class="d-flex flex-column">
			  
			  	<!-- Main Content -->
			  	<div id="content">
					
					<!-- Topbar -->
					<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					  
					 	<!-- Sidebar Toggle (Topbar) -->
					  	<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
							<i class="fa fa-bars"></i>
					  	</button>

					  	<!-- Topbar Navbar -->
					  	<ul class="navbar-nav ml-auto">
							<li class="nav-item">
		              			<a class="nav-link" href="../" target="_blank">
		              				<i class="far fa-eye"></i>
		                			<span style="margin-left: 5px;">Afficher le site</span>
		              			</a>
		          			</li>
							<div class="topbar-divider d-none d-sm-block"></div>

							<!-- Nav Item - User Information -->
							<li class="nav-item dropdown no-arrow">
					  			<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="mr-2 d-none d-lg-inline text-gray-600 small">
										<?php 
										if (isset($first_name)) { 
											echo $first_name; 
										} else { 
											echo "Guest"; // Display "Guest" if not logged in
										} 
										?>
									</span>
									<i class="fas fa-caret-down"></i>
								</a>
							  	
							  	<!-- Dropdown - User Information -->
							  	<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
									<a class="dropdown-item" href="monprofil.php">
								  		<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
								  		Profile
									</a>
		
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
								  		<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
								  		Se deconnecter
									</a>
							  	</div>
							</li>
				  		</ul>
					</nav>
					<!-- End of Topbar -->

	