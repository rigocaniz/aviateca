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

	# Aeronave / Aeropuerto
	case 'ingresarAeropuerto':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->ingresarAeropuerto( $data->idCiudad, $data->aeropuerto );

		echo json_encode( $aeronave->getResponse() );
		break;
	
	case 'ingresarAeronave':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->ingresarAeronave( $data->aeronave, $data->idTipoAeronave );

		echo json_encode( $aeronave->getResponse() );
		break;
	
	case 'actualizarEstadoAeronave':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->actualizarEstadoAeronave( $data->idAeronave, $data->idEstadoAeronave );

		echo json_encode( $aeronave->getResponse() );
		break;
	
	case 'ingresarClaseAeronave':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->ingresarClaseAeronave( $data->idAeronave, $data->idClase, $data->precioVoleto, $data->capacidad );

		echo json_encode( $aeronave->getResponse() );
		break;
	
	case 'actualizarClaseAeronave':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->actualizarClaseAeronave( $data->idAeronave, $data->idClase, $data->precioVoleto, $data->capacidad );

		echo json_encode( $aeronave->getResponse() );
		break;
	
	case 'ingresarAeronaveDia':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->ingresarAeronaveDia( $data->idAeronave, $data->idDia, $data->aeropuertoDestino, $data->horaSalida );

		echo json_encode( $aeronave->getResponse() );
		break;
	
	case 'actualizarAeronaveDia':
		$aeronave = new Aeronave( $conexion, $session );
		$aeronave->actualizarAeronaveDia( $data->idAeronave, $data->idDia, $data->aeropuertoDestino, $data->horaSalida );

		echo json_encode( $aeronave->getResponse() );
		break;

	case 'lstDia':
		$aeronave = new Aeronave( $conexion, $session );
		
		echo json_encode( $aeronave->lstDia() );
		break;
	
	case 'lstClase':
		$aeronave = new Aeronave( $conexion, $session );
		
		echo json_encode( $aeronave->lstClase() );
		break;
	
	case 'lstTipoAeronave':
		$aeronave = new Aeronave( $conexion, $session );
		
		echo json_encode( $aeronave->lstTipoAeronave() );
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
	
	case 'lstAeronaveDestino':
		$aeronave = new Aeronave( $conexion, $session );
		
		echo json_encode( $aeronave->lstAeronaveDestino( $data->idAeronave ) );
		break;

	default:
		echo json_encode( array( "response" => 0, "msg" => "Opción no válida" ) );
		break;
}

$conexion->close();
?>










