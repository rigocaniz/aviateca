<?php
@session_start();
require 'class/session.class.php';
$_SESSION['user'] = "rigo";

$session = new Session();

$data = json_decode( file_get_contents("php://input") );

if ( !$session->valid() ) {
//	echo json_encode( array( "response" => 0, "msg" => "Sesión expirada, ingrese nuevamente" ) );
//	exit();
}

// SE VALIDA SI SE ESTA RECIBIENDO UNA ACCION VALIDA
if ( !$data OR !isset( $data->action ) ) {
	//echo json_encode( array( "response" => 0, "msg" => "Parametros invalidos" ) );
	//exit();
}

require 'class/conexion.class.php';
require 'class/query.class.php';
require 'class/ubicacion.class.php';
require 'class/aeronave.class.php';
require 'class/persona.class.php';
require 'class/reservacion.class.php';

$conexion = new Conexion();

switch ( $data->action ) {
	# UBICACION
	case 'ingresarContinente':
		$ubicacion = new ubicacion( $conexion );
		$ubicacion->ingresarContinente( $data->continente );

		echo json_encode( $ubicacion->getResponse() );
		break;

	case 'ingresarPais':
		$ubicacion = new ubicacion( $conexion );
		$ubicacion->ingresarPais( $data->codigoPais, $data->pais, $data->nacionalidad, $data->idContinente );

		echo json_encode( $ubicacion->getResponse() );
		break;

	case 'ingresarCiudad':
		$ubicacion = new ubicacion( $conexion );
		$ubicacion->ingresarCiudad( $data->codigoPais, $data->ciudad );

		echo json_encode( $ubicacion->getResponse() );
		break;

	case 'lstCiudad':
		$ubicacion = new ubicacion( $conexion );
		$response = $ubicacion->lstCiudad( $data->codigoPais );

		echo json_encode( $response );
		break;

	case 'lstPais':
		$ubicacion = new ubicacion( $conexion );
		$response = $ubicacion->lstPais( $data->idContinente );

		echo json_encode( $response );
		break;

	case 'lstContinente':
		$ubicacion = new ubicacion( $conexion );
		$response = $ubicacion->lstContinente();

		echo json_encode( $response );
		break;

	# Aeropuerto
	case 'ingresarAeropuerto':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->ingresarAeropuerto( $data->idCiudad, $data->aeropuerto );

		echo json_encode( $aeronave->getResponse() );
		break;
	
	# Aeronave
	case 'iniAeronave':
		$aeronave = new Aeronave( $conexion, $session );
		$datos = array(
			'lstClase'          => $aeronave->lstClase(),
			'lstTipoAeronave'   => $aeronave->lstTipoAeronave(),
			'lstEstadoAeronave' => $aeronave->lstEstadoAeronave(),
		);
			
		echo json_encode( $datos );
		break;

	case 'guardarAeronave':
		$aeronave = new Aeronave( $conexion, $session );

		if ( $data->nuevo )
			$aeronave->ingresarAeronave( $data->aeronave, $data->idTipoAeronave, $data->aeronaveClases );
		
		else
			$aeronave->actualizarAeronave( $data->idAeronave, $data->aeronave, $data->idTipoAeronave, $data->idEstadoAeronave, $data->aeronaveClases );

		echo json_encode( $aeronave->getResponse() );
		break;

	case 'lstAeropuerto':
		$aeronave = new Aeronave( $conexion, $session );
		
		echo json_encode( $aeronave->lstAeropuerto( $data->idAeropuerto, $data->idContinente, $data->codigoPais, $data->idCiudad ) );
		break;
	
	case 'lstAeronave':
		$aeronave = new Aeronave( $conexion, $session );
		
		echo json_encode( $aeronave->lstAeronave( $data->idAeronave, $data->idTipoAeronave, $data->idEstadoAeronave ) );
		break;
	
	case 'lstAeronaveClase':
		$aeronave = new Aeronave( $conexion, $session );
		
		echo json_encode( $aeronave->lstAeronaveClase( $data->idAeronave ) );
		break;
	
	// VUELO
	case 'iniVuelo':
		$aeronave = new Aeronave( $conexion, $session );
		$datos = array(
			'lstTipoAeronave' => $aeronave->lstTipoAeronave(),
			'lstEstadoVuelo'  => $aeronave->lstEstadoVuelo(),
		);
			
		echo json_encode( $datos );
		break;

	case 'ingresarVueloAeronave':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->ingresarVueloAeronave( $data->idAeronave, $data->aeropuertoOrigen, $data->horaSalida, $data->fechaSalida, 
			$data->aeropuertoDestino, $data->horaAterrizaje, $data->fechaAterrizaje, $data->lstClasePrecio );

		echo json_encode( $aeronave->getResponse() );
		break;
	
	case 'actualizarEstadoVuelo':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->actualizarEstadoVuelo( $data->idVuelo, $data->comentario, $data->idEstadoVuelo );

		echo json_encode( $aeronave->getResponse() );
		break;

	case 'lstVueloAeronave':
		$aeronave = new Aeronave( $conexion, $session );
		
		echo json_encode( $aeronave->lstVueloAeronave( $data->idEstadoVuelo, $data->aeropuertoOrigen, $data->idAeronave, 
							$data->idVuelo, $data->idTipoAeronave ) );
		break;

	// INCIDENTES
	case 'ingresarIncidente':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->ingresarIncidente( $data->idVuelo, $data->incidente );

		echo json_encode( $aeronave->getResponse() );
		break;

	case 'lstIncidente':
		$aeronave = new Aeronave( $conexion, $session );
		
		echo json_encode( $aeronave->lstIncidente( $data->idVuelo ) );
		break;

	// INICIO
	case 'iniReservacion':
		$reservacion = new Reservacion( $conexion, $session );
		$aeronave    = new Aeronave( $conexion, $session );

		echo json_encode( 
			array( 
				'today'       => date("Y-m-d"),
				'lstTipoPago' => $reservacion->lstTipoPago(),
				'lstClase'    => $aeronave->lstClase(),
			)
		);
		break;

	case 'guardarReservacion':
		$reservacion = new Reservacion( $conexion, $session );
		$reservacion->ingresarReservacion( $data->idVuelo, $data->idClase, $data->idPersona, 
			$data->encargado, $data->idTipoPago );

		echo json_encode( $reservacion->getResponse() );
		break;

	// PERSON
	case 'ingresarPersona':
		$persona = new Persona( $conexion, $session );
		$persona->ingresarPersona( $data->numeroPasaporte, $data->identificacion, $data->nombres, 
			$data->apellidos, $data->fechaNacimiento, $data->correo, $data->telefono, 
			$data->urlFoto, $data->idGenero, $data->idCiudad );

		echo json_encode( $persona->getResponse() );
		break;

	case 'consultarPersona':
		$persona = new Persona( $conexion, $session );
		$datos = array(
			'persona'  => $persona->getPersona( 0, $data->numeroPasaporte, $data->idVuelo )
		);

		echo json_encode( $datos );
		break;

	case 'consultarDestinos':
		$reservacion = new Reservacion( $conexion, $session );

		echo json_encode( $reservacion->lstVueloPendientes( $data->like ) );
		break;

	// DEFAULT
	default:
		echo json_encode( array( "response" => 0, "msg" => "Opción no válida" ) );
		break;
}

$conexion->close();
?>










