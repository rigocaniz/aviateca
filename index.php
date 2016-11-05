<?php 
@session_start();
require 'class/session.class.php';

$session = new Session();

// SI LA SESION NO ES VALIDA
if ( !$session->valid() )
	header("location: login.php");

?>
<!DOCTYPE html>
<html lang="es-GT" ng-app="app">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
	<link type="text/css" rel="stylesheet" href="fonts/material-icons.css"/>
	<title>Aviateca</title>
</head>
<body ng-controller="principal">
<!-- LOADING -->
<div class="loading" id="loading">
	<div class="foot">
		<h4>Espere un momento...</h4>
		<div class="preloader-wrapper big active">
			<div class="spinner-layer spinner-blue-only">
				<div class="circle-clipper left">
					<div class="circle"></div>
				</div>
				<div class="gap-patch">
					<div class="circle"></div>
				</div>
				<div class="circle-clipper right">
					<div class="circle"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="navbar-fixed">
	<nav>
		<div class="nav-wrapper">
			<a href="#!" class="brand-logo right">
				<img src="images/aviateca.png" height="29">
				<span class="currentUser"><?= $session->getName(); ?></span>
			</a>
			<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
			<ul class="left hide-on-med-and-down">
				<li><a href="#/localizacion">Ubicaciones</a></li>
				<li><a href="#/aeropuerto">Aeropuerto</a></li>
				<li><a href="#/aeronave">Aeronave</a></li>
				<li><a href="#/vuelo">Vuelo</a></li>
				<li><a href="#/reservacion">Reservación</a></li>
				<li><a href="#/usuario">Usuarios</a></li>
				<li>
					<a href="logout.php"><i class="material-icons">exit_to_app</i></a>
				</li>
			</ul>
			<ul class="side-nav" id="mobile-demo">
				<li>
					<a href="#/"><i class="material-icons left">home</i>Inicio</a>
				</li>
				<li><a href="#/localizacion">Ubicaciones</a></li>
				<li><a href="#/aeropuerto">Aeropuerto</a></li>
				<li><a href="#/aeronave">Aeronave</a></li>
				<li><a href="#/vuelo">Vuelo</a></li>
				<li><a href="#/reservacion">Reservación</a></li>
				<li><a href="#/usuario">Usuarios</a></li>
				<li><a href="#logout.php">Salir</a></li>
			</ul>
		</div>
	</nav>
</div>

<div class="col s12 m12" style="margin: 5px">
	<div ng-view></div>
</div>
        
<script src="js/libs/angular.min.js"></script>
<script src="js/libs/angular-route.min.js"></script>
<script src="js/libs/jquery-1.12.1.min.js"></script>
<script src="js/libs/materialize.min.js"></script>
<script src="js/libs/jquery.html5uploader.js"></script>
<script src="js/libs/moment.min.js"></script>

<script src="js/main.js"></script>
<script src="js/_route.js"></script>

<script src="js/localizacion.js"></script>
<script src="js/aeropuerto.js"></script>
<script src="js/aeronave.js"></script>
<script src="js/vuelo.js"></script>
<script src="js/reservacion.js"></script>
<script src="js/usuario.js"></script>
</body>
</html>