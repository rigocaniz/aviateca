<?php
@session_start();
require 'class/session.class.php';

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

$conexion = new Conexion();

switch ( $data->action ) {
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

	default:
		echo json_encode( array( "response" => 0, "msg" => "Opción no válida" ) );
		break;
}

$conexion->close();
?>