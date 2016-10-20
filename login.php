<!DOCTYPE html>
<html lang="es-GT">
<head>
	<meta charset="UTF-8">
	<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
	<link type="text/css" rel="stylesheet" href="fonts/material-icons.css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Aviateca</title>
</head>
<body>
<div class="navbar-fixed">
	<nav>
		<div class="nav-wrapper">
			<a href="#!" class="brand-logo right">
				<img src="images/aviateca.png" height="39" style="vertical-align: middle;">
			</a>
			<span class="left" style="margin-left: 9px">LOGIN</span>
		</div>
	</nav>
</div>
<div class="container">
	<div class="row">
		<div class="col m6 s12 offset-m3">
			<form class="col s12" method="POST" action="login.php">
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">account_circle</i>
						<input id="usuario" type="text" class="validate" placeholder="" autofocus>
						<label for="usuario">Usuario</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">vpn_key</i>
						<input id="clave" type="password" class="validate" placeholder="">
						<label for="clave">Contraseña</label>
					</div>
				</div>
				<div class="col s10 offset-s1">
					<button type="submit" class="btn waves-effect">
						<i class="material-icons left">input</i>
						Ingresar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="js/libs/jquery-1.12.1.min.js"></script>
<script src="js/libs/materialize.min.js"></script>
</body>
</html>