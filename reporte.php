<?php
session_start();
include 'lib_pdf/Cezpdf.php';
require 'class/session.class.php';
require 'class/conexion.class.php';
require 'class/query.class.php';

$session = new Session();
$conexion = new Conexion();

$pdf = new Cezpdf('letter', 'landscape', 'color', array(1,1,1) );

$pdf->selectFont('lib_pdf/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(2.5,2.5,2.5,2.5);

$izquierda=array(
		'left'=>-50,
		'right'=>0,
		'justification'=>'left'
);

$justificado=array(
		'left'=>0,
		'right'=>10,
		'justification'=>'full'
);

$options = array(
	'showBgCol'       => 0,
	'showHeadings'    => 2,
	'shaded'          => 1,
	'showLines'       => 1,
	'xOrientation'    => 'center',
	'fontSize'        => 9.5,
	'width'           => 750,
	'textCol'         => array(30/255,30/255,30/255),
	'shadeHeadingCol' => array(210/255,210/255,210/255),
	'lineCol'         => array(220/255,220/255,220/255),
);

$pdf->addInfo( array(
		'Title'    => 'Reporte - Aviateca',
		'Author'   => 'Rigo Caniz',
		'Subject'  => 'Reporte',
		'Creator'  => 'rigocaniz@gmail.com',
		'Producer' => 'Aviateca Airlines'
) );

$fuente = 11.5;
$y      = 544;
$x      = 30;
$cols   = $data = array();

// REPORTE
switch ( $_GET['type'] ) {
	case 'asignacion':
		$idVuelo = $_GET['idVuelo'];
		$nombreReporte = "Asignación de Pasajeros, # Vuelo: " . $idVuelo;

		require 'class/reservacion.class.php';
		$reservacion = new Reservacion( $conexion, $session );
		$resultado = $reservacion->lstAsignacionPasajeros( $idVuelo );

		$cols = array(
			'numeroAsiento'     => '<b># Asiento</b>',
			'clase'             => '<b>clase</b>',
			'numeroPasaporte'   => '<b>Número Pasaporte</b>',
			'nombreCompleto'    => '<b>Nombre Completo</b>',
			'edad'              => '<b>Edad</b>',
			'nombreEncargado'   => '<b>Encargado</b>',
			'estadoReservacion' => '<b>Estado Reserv.</b>',
			'tipoPago'          => '<b>Forma Pago</b>',
			'idUsuario'         => '<b>Usuario</b>',
		);

		// RECORRE RESULTADO
		foreach ($resultado->lst as $item) {
			$data[] = array(
				'numeroAsiento'     => $item->numeroAsiento,
				'clase'             => $item->clase,
				'numeroPasaporte'   => $item->numeroPasaporte,
				'nombreCompleto'    => $item->nombreCompleto,
				'edad'              => $item->edad,
				'nombreEncargado'   => $item->nombreEncargado,
				'estadoReservacion' => $item->estadoReservacion,
				'tipoPago'          => $item->tipoPago,
				'idUsuario'         => $item->idUsuario,
			);
		}

		break;
	
	default:
		$nombreReporte = "Tipo de Reporte NO DEFINIDO";
		break;
}

// HEADER AVIATECA
$pdf->addJpegFromFile('images/fondo_aviateca_land.jpg', 0, $y, 792);
//$pdf->addJpegFromFile('images/fondo_aviateca.jpg', 0, $y, 612);

$pdf->ezSetY( $y += 25 );
$pdf->setColor( 1, 1, 1 );
$y = $pdf->ezText( "» " . $nombreReporte, 14, $izquierda );	
$pdf->setColor(30/255, 30/255, 30/255);

$pdf->ezSetY( $y -= 30 );
$pdf->ezTable($data, $cols, '', $options);


//$pdf->addText($x+20, $y -= 15, $fuente, "Monto:" );

$conexion->close();

$pdf->ezStream(array('compress'=>0));
?>



