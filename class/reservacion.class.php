<?php
/**
* RESERVACION: reservaciones, personas
*/
class Reservacion extends Query
{
	private $session = NULL;
	function __construct( &$conexion, &$session )
	{
		parent::__construct( $conexion, $session );
		$this->session = $session;
	}

	public function ingresarReservacion( $idVuelo, $idClase, $idPersona, $encargado, $idTipoPago )
	{
		$idVuelo    = (int)$idVuelo;
		$idClase    = (int)$idClase;
		$idPersona  = (int)$idPersona;
		$encargado  = (int)$encargado;
		$idTipoPago = (int)$idTipoPago;

		if ( !$idVuelo ) {
			$this->error   = true;
			$this->message = "Vuelo no definido";
		}
		else if ( !$idClase ) {
			$this->error   = true;
			$this->message = "Clase no definida";
		}
		else if ( !$idPersona ) {
			$this->error   = true;
			$this->message = "Persona no definida";
		}
		else if ( !$idTipoPago ) {
			$this->error   = true;
			$this->message = "Tipo de pago no definido";
		}

		if ( !( $encargado > 0 ) )
			$encargado = "NULL";

		if ( !$this->error ) {
			$sql = "CALL ingresarReservacion( {$idVuelo}, {$idClase}, {$idPersona}, {$encargado}, 
						{$idTipoPago}, '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;

			if ( $result->data > 0 )
				$this->data = $result->data;
		}
	}

	/* CAT */
	public function lstTipoPago()
	{
		$lst = array();

		$sql    = "SELECT idTipoPago, tipoPago FROM tipoPago";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function lstEstadoReservacion()
	{
		$lst = array();

		$sql    = "SELECT idEstadoReservacion, estadoReservacion FROM estadoReservacion";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	/* DESTINOS */
	public function lstVueloPendientes( $like )
	{
		$lst = array();
		$like = str_replace(" ", "%", $like);

		if ( strlen( $like ) < 4 )
			return $lst;

		$sql    = "SELECT 
						idVuelo,
					    aeronave,
					    tipoAeronave,
					    TIMESTAMPDIFF(hour,
							NOW(),
							CONCAT(fechaSalida, ' ', horaSalida)
					    )AS 'horasFaltantes',
					    TIME_FORMAT(horaSalida, '%H:%i')AS 'horaSalida',
    					DATE_FORMAT(fechaSalida, '%d/%m/%Y')AS 'fechaSalida',
					    origen AS 'aeropuertoOrigen',
					    ciudadOrigen,
					    paisOrigen,
					    continenteOrigen,
					    destino AS 'aeropuertoDestino',
					    ciudadDestino,
					    paisDestino,
					    continenteDestino
					FROM vstVueloAeronave 
					WHERE idEstadoVuelo = 1 
						AND CONCAT(fechaSalida, ' ', horaSalida) >= now()
						AND CONCAT( ciudadDestino, paisDestino, continenteDestino ) LIKE '%{$like}%'";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$info = $this->getDisponibilidad( $row->idVuelo );

			$row->disponibilidad = $info->disponibilidad;
			$row->lstClase       = $info->lstClase;

			$lst[] = $row;
		}

		return $lst;
	}

	public function getDisponibilidad( $idVuelo )
	{
		$datos = array(
			"disponibilidad" => false,
			"lstClase"       => array(),
		);

		$sql = "SELECT
					dv.idClase,
					clase,
					capacidad,
					numeroPasajeros,
				    pb.precioBoleto
				FROM vstDisponibilidadVuelo AS dv
					JOIN precioBoleto AS pb ON dv.idVuelo = pb.idVuelo
						AND dv.idClase = pb.idClase
				WHERE dv.idVuelo = {$idVuelo} ";

		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			if ( (int)$row->capacidad > (int)$row->numeroPasajeros )
				$datos['disponibilidad'] = true;

			$row->disponibilidad = false;
			if ( (int)$row->capacidad > (int)$row->numeroPasajeros )
				$row->disponibilidad = true;

			$datos['lstClase'][] = $row;
		}

		return (object)$datos;
	}

	public function getResponse()
	{
		return (object)array( "response" => !$this->error, "message" => $this->message, "data" => $this->data );
	}
}
?>




