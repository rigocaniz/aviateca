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

$piePagina = "";

// REPORTE
switch ( $_GET['type'] ) {
	// REPORTE DE ASIGNACION
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

	// REPORTE DE CANCELACIONES
	case 'resCancelados':
		$deFecha       = $_GET['deFecha'];
		$paraFecha     = $_GET['paraFecha'];
		$nombreReporte = "Reservaciones Canceladas ";

		require 'class/reporte.class.php';
		$reporte = new Reporte( $conexion );
		$resultado = $reporte->lstReservacionesCanceladas( $deFecha, $paraFecha );

		$cols = array(
			'idReservacion'   => '<b># Reser.</b>',
			'idVuelo'         => '<b># Vuelo</b>',
			'numeroAsiento'   => '<b>Número Asiento</b>',
			'numeroPasaporte' => '<b>numero Pasaporte</b>',
			'nombres'         => '<b>Nombres</b>',
			'apellidos'       => '<b>Apellidos</b>',
			'clase'           => '<b>Clase</b>',
			'montoRecargo'    => '<b>Monto Recargo</b>',
			'fechaSalida'     => '<b>Fecha Salida</b>',
		);

		// RECORRE RESULTADO
		$montoCancelacion = 0;
		foreach ($resultado as $item) {
			$data[] = array(
				'idReservacion'   => $item->idReservacion,
				'idVuelo'         => $item->idVuelo,
				'numeroAsiento'   => $item->numeroAsiento,
				'numeroPasaporte' => $item->numeroPasaporte,
				'nombres'         => $item->nombres,
				'apellidos'       => $item->apellidos,
				'clase'           => $item->clase,
				'montoRecargo'    => $item->montoRecargo,
				'fechaSalida'     => $item->fechaSalida,
			);

			$montoCancelacion += (double)$item->montoRecargo;
		}

		$piePagina = "Monto Total Cancelación: <b>$ ".number_format($montoCancelacion, 2)."</b>";

		break;

	// REPORTE TOP DE VENTAS POR USUARIO
	case 'topAgentes':
		$deFecha       = $_GET['deFecha'];
		$paraFecha     = $_GET['paraFecha'];
		$nombreReporte = "Top Venta por Agente de Viaje";

		require 'class/reporte.class.php';
		$reporte = new Reporte( $conexion );
		$resultado = $reporte->lstTopVentas( $deFecha, $paraFecha );

		$cols = array(
			'idUsuario'      => '<b>Usuario</b>',
			'nombreCompleto' => '<b>Nombre Usuario</b>',
			'cancelados'     => '<b>Cancelados</b>',
			'canceladosPor'  => '<b>% Cancelados</b>',
			'total'          => '<b>Total Boletos</b>',
			'totalPor'       => '<b>% Total Boletos</b>',
		);

		// RECORRE RESULTADO
		foreach ($resultado->lst as $item) {
			$canceladosPor = $item->cancelados > 0 ? ( $item->cancelados / $resultado->totalCanc ) * 100 : 0;
			$totalPor      = $item->total > 0 ? ( $item->total / $resultado->total ) * 100 : 0;

			$data[] = array(
				'idUsuario'      => $item->idUsuario,
				'nombreCompleto' => $item->nombreCompleto,
				'cancelados'     => $item->cancelados,
				'canceladosPor'  => number_format( $canceladosPor, 2 ) . "%",
				'total'          => $item->total,
				'totalPor'       => number_format( $totalPor, 2 ) . "%",
			);
		}
		break;
	
	// REPORTE INGRESOS
	case 'repIngresos':
		$deFecha       = $_GET['deFecha'];
		$paraFecha     = $_GET['paraFecha'];
		$agruparPor    = $_GET['agruparPor'];

		if ( $agruparPor == 'tipoPago' ) {
			$nombreReporte = "Total Ingresos por Tipo de Pago";
			$cols = array(
				'tipoPago'        => '<b>Tipo de Pago</b>',
				'precioBoleto'    => '<b>Total Boleto</b>',
				'montoAsesoria'   => '<b>Total Asesoria</b>',
				'total'           => '<b>Total</b>',
				'totalBoletos'    => '<b>Total Boletos</b>',
				'porcentaje'      => '<b>% de Boletos</b>',
				'porIngresoTotal' => '<b>% de Ingresos</b>',
			);
		}
		else {
			$nombreReporte = "Total Ingresos por Agente";
			$cols = array(
				'idUsuario'       => '<b>Usuario</b>',
				'nombreCompleto'  => '<b>Nombre Completo</b>',
				'precioBoleto'    => '<b>Total Boleto</b>',
				'montoAsesoria'   => '<b>Total Asesoria</b>',
				'total'           => '<b>Total</b>',
				'totalBoletos'    => '<b>Total Boletos</b>',
				'porcentaje'      => '<b>% de Boletos</b>',
				'porIngresoTotal' => '<b>% de Ingresos</b>',
			);
		}

		require 'class/reporte.class.php';
		$reporte = new Reporte( $conexion );
		$resultado = $reporte->lstIngresos( $deFecha, $paraFecha, $agruparPor );

		// RECORRE RESULTADO
		foreach ($resultado->lst as $item) {
			$porBoletos  = $item->totalBoletos > 0 ? ( $item->totalBoletos / $resultado->totalBoletos ) * 100 : 0;
			$porIngresos = $item->totalIngresos > 0 ? ( $item->totalIngresos / $resultado->totalIngresos ) * 100 : 0;

			if ( $agruparPor == 'tipoPago' ) {
				$data[] = array(
					'tipoPago'        => $item->tipoPago,
					'precioBoleto'    => '$ ' . number_format( $item->precioBoleto, 2 ),
					'montoAsesoria'   => '$ ' . number_format( $item->montoAsesoria, 2 ),
					'total'           => '$ ' . number_format( $item->totalIngresos, 2 ),
					'totalBoletos'    => $item->totalBoletos,
					'porcentaje'      => number_format( $porBoletos, 2 ) . "%",
					'porIngresoTotal' => number_format( $porIngresos, 2 ) . "%",
				);
			}
			else {
				$data[] = array(
					'idUsuario'       => $item->idUsuario,
					'nombreCompleto'  => $item->nombreCompleto,
					'precioBoleto'    => '$ ' . number_format( $item->precioBoleto, 2 ),
					'montoAsesoria'   => '$ ' . number_format( $item->montoAsesoria, 2 ),
					'total'           => '$ ' . number_format( $item->totalIngresos, 2 ),
					'totalBoletos'    => $item->totalBoletos,
					'porcentaje'      => number_format( $porBoletos, 2 ) . "%",
					'porIngresoTotal' => number_format( $porIngresos, 2 ) . "%",
				);
			}
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
$y = $pdf->ezTable($data, $cols, '', $options);


$pdf->addText($x+20, $y -= 15, $fuente, $piePagina );

$conexion->close();

$pdf->ezStream(array('compress'=>0));
?>



