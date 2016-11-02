<?php 
@session_start();
require 'class/session.class.php';
$session      = new Session();
$mensaje      = "";
$cambiarClave = false;

// SI LA SESION NO ES VALIDA
if ( $session->valid() ) {
	header("location: ./");
}

// SI SE RECIBEN DATOS
if ( isset( $_POST['submit'] ) ) {
	// PARAMETROS RECIBIDOS
	$user     = $_POST['usuario'];
	$pass     = $_POST['clave'];
	@$passNew = $_POST['claveNueva'];

	// REQUIRES
	require 'class/conexion.class.php';
	require 'class/query.class.php';
	require 'class/usuario.class.php';

	// REALIZA CONEXION CON BD
	$conexion = new Conexion();

	// OBJETO USUARIO
	$usuario = new Usuario( $conexion, $session );

	// SI ES CAMBIO DE CLAVE
	if ( strlen( $passNew ) > 3 ) {
		// REALIZA CAMBIO DE CLAVE
		$usuario->cambiarClave( $user, $pass, $passNew );

		$respuesta = $usuario->getResponse();

		// SI OCURRIO ALGUN PROBLEMA AL CAMBIAR LA CLAVE
		if ( !$respuesta->response ) {
			$mensaje = $respuesta->message;
		}
	}
	// SI ES LOGIN DE USUARIO
	else{
		// REALIZA EL LOGIN DEL USAURIO
		$respuesta = $usuario->login( $user, $pass );

		// SI ES NECESARIO CAMBIAR DE CLAVE
		if ( $respuesta->data === '2' ) {
			$cambiarClave = true;
			$mensaje      = $respuesta->message;
		}
		// SI EL LOGIN SE REALIZO DE LA MANERA CORRECTA
		else if ( $respuesta->response ) {
			
			// DATOS DE USUARIO
			$_SESSION['login']   = true;
			$_SESSION['user']    = $respuesta->info->idUsuario;
			$_SESSION['name']    = $respuesta->info->nombreCompleto;
			$_SESSION['profile'] = (int)$respuesta->info->idTipoUsuario;
			
			// REDIRIGE A LA RUTA RAIZ DE LA APLICACION
			header("location: ./");

		}
		// SI OCURRIO UN ERROR EN EL LOGIN
		else{
			$mensaje = $respuesta->message;
		}
	}

	// CIERRA LA CONEXION
	$conexion->close();
}

?>
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
		<div class="col s12">
			<?php if ( strlen( $mensaje ) ) { ?>
				<div class="card-panel red">
					<span class="red-text text-lighten-5"><b><?= $mensaje;?></b></span>
				</div>
			<?php } ?>
		</div>
		<div class="col m6 s12 offset-m3">
			<form class="col s12" method="POST" action="login.php" autocomplete="off">
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">account_circle</i>
						<input type="text" id="usuario" name="usuario" class="validate" placeholder="" autofocus>
						<label for="usuario">Usuario</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">vpn_key</i>
						<input type="password" id="clave" name="clave" class="validate" placeholder="">
						<label for="clave">Contraseña</label>
					</div>
				</div>
				<?php if ( $cambiarClave ) { ?>
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">vpn_key</i>
						<input type="password" id="claveNueva" name="claveNueva" class="validate" placeholder="">
						<label for="clave">Contraseña Nueva</label>
					</div>
				</div>
				<?php } ?>
				<div class="col s10 offset-s1">
					<button type="submit" name="submit" class="btn waves-effect">
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