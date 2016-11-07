<?php
/**
* REPORTE
*/
class Reporte extends Query
{
	function __construct( &$conexion )
	{
		parent::__construct( $conexion );
	}

	public function lstReservacionesCanceladas( $deFecha, $paraFecha )
	{
		$lst = array();

		$sql = "SELECT 
					r.idReservacion,
				    r.numeroAsiento,
					p.numeroPasaporte,
				    p.nombres,
				    p.apellidos,
				    c.clase,
				    r.idEstadoReservacion,
				    r.montoRecargo,
				    v.idVuelo,
				    v.fechaSalida
				FROM reservacion AS r
					JOIN vuelo AS v ON r.idVuelo = v.idVuelo
				    JOIN clase AS c ON c.idClase = r.idClase
				    JOIN persona AS p ON p.idPersona = r.idPersona
				    JOIN bit_reservacionEstado AS re ON re.idReservacion = r.idReservacion 
				    	AND re.idEstadoReservacion = 5 
				WHERE DATE( re.fechaHora ) BETWEEN '{$deFecha}' AND '{$paraFecha}'
					AND r.idEstadoReservacion = 5 
				ORDER BY r.idVuelo ASC, r.idClase ASC, r.numeroAsiento ASC";

		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function lstTopVentas( $deFecha, $paraFecha )
	{
		$datos = array( 
			'lst'       => array(),
			'total'     => 0,
			'totalCanc' => 0,
		);

		$sql = "SELECT 
					SUM( IF( r.idEstadoReservacion = 5, 1, 0 ) )AS 'cancelados',
				    COUNT(*)AS 'total',
				    u.nombreCompleto,
				    u.idUsuario
				FROM reservacion AS r
					JOIN vuelo AS v ON r.idVuelo = v.idVuelo
				    JOIN usuario AS u ON u.idUsuario = r.idUsuario
				WHERE DATE( r.fechaHora ) BETWEEN '{$deFecha}' AND '{$paraFecha}'
				GROUP BY r.idUsuario 
				ORDER BY total DESC";

		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$datos['lst'][]     = $row;
			$datos['total']     += (int)$row->total;
			$datos['totalCanc'] += (int)$row->cancelados;
		}

		return (object)$datos;
	}

	public function lstIngresos( $deFecha, $paraFecha, $agruparPor )
	{
		$datos = array( 
			'lst'           => array(),
			'totalIngresos' => 0,
			'totalBoletos'  => 0,
		);

		if ( $agruparPor == 'tipoPago' )
			$groupBy = 'r.idTipoPago';

		else
			$groupBy = 'r.idUsuario';

		$sql = "SELECT
					SUM( r.precioBoleto )AS 'precioBoleto',
				    SUM( r.montoAsesoria )AS 'montoAsesoria',
				    COUNT( * )AS 'totalBoletos',
				    u.idUsuario,
				    u.nombreCompleto,
				    tp.idTipoPago,
				    tp.tipoPago
				FROM reservacion AS r
				    JOIN usuario AS u ON u.idUsuario = r.idUsuario
				    JOIN tipoPago AS tp ON tp.idTipoPago = r.idTipoPago
				WHERE DATE( r.fechaHora ) BETWEEN '{$deFecha}' AND '{$paraFecha}'
					AND r.idEstadoReservacion != 5
				GROUP BY $groupBy ";

		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$datos['lst'][]         = $row;
			$datos['totalIngresos'] += (double)$row->precioBoleto + (double)$row->montoAsesoria;
			$row->totalIngresos     += (double)$row->precioBoleto + (double)$row->montoAsesoria;
			$datos['totalBoletos']  += (int)$row->totalBoletos;
		}

		return (object)$datos;
	}
}
?>
