<?php
/**
* AERONAVE: aeronave, estado, clase (precio, capacidad), aeropuerto
*/
class Aeronave extends Query
{
	private $error    = false;
	private $session  = NULL;
	private $data  	  = NULL;
	private $message  = "";
	function __construct( &$conexion, &$session )
	{
		parent::__construct( $conexion );
		$this->session = $session;
	}

	public function ingresarAeropuerto( $idCiudad, $aeropuerto )
	{
		$aeropuerto = $this->conexion->real_escape_string( $aeropuerto );
		if ( strlen( $aeropuerto ) < 3 ) {
			$this->error   = true;
			$this->message = "Número de caracteres demasiado corto";
		}

		if ( !$this->error ) {
			$sql    = "CALL ingresarAeropuerto( {$idCiudad}, '{$aeropuerto}', '0', '0', '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function ingresarAeronave( $aeronave, $idTipoAeronave, $aeronaveClases )
	{
		$aeronave       = $this->conexion->real_escape_string( $aeronave );
		$idAeronave     = 0;
		$capacidadTotal = 0;

		$this->conexion->query( "START TRANSACTION" );

		if ( strlen( $aeronave ) < 3 ) {
			$this->error   = true;
			$this->message = "Descripción Aeronave muy corto";
		}

		if ( !$this->error AND !( $idTipoAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "Tipo de Aeronave no especificado";
		}

		if ( !$this->error ) {
			$sql    = "CALL ingresarAeronave( '{$aeronave}', '{$idTipoAeronave}', '{$this->session->getUser()}' )";
			$result = $this->query( $sql );

			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
			$idAeronave    = $result->data;
		}

		// INGRESAR CAPACIDAD Y PRECIO POR EL TIPO DE CLASE
		if ( !$this->error ) {
			foreach ($aeronaveClases as $clase) {
				$this->ingresarClaseAeronave( $idAeronave, $clase->idClase, $clase->capacidad );
				$capacidadTotal += $clase->capacidad;

				if ( $this->error )
					break;
			}

			if ( !$this->error AND $capacidadTotal <= 0 ) {
				$this->error   = true;
				$this->message = "La capacidad de la Aeronave no puede ser cero";
			}
		}

		if ( $this->error )
			$this->conexion->query( "ROLLBACK" );

		else
			$this->conexion->query( "COMMIT" );
	}

	public function actualizarAeronave( $idAeronave, $aeronave, $idTipoAeronave, $idEstadoAeronave, $aeronaveClases )
	{
		$capacidadTotal = 0;
		$this->conexion->query( "START TRANSACTION" );

		if ( !( $idAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "Seleccione una Aeronave";
		}

		if ( !$this->error AND strlen( $aeronave ) < 3 ) {
			$this->error   = true;
			$this->message = "Descripción Aeronave muy corto";
		}

		if ( !$this->error AND !( $idTipoAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "Tipo de Aeronave no especificado";
		}

		if ( !$this->error AND !( $idEstadoAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "Estado no definido";
		}

		if ( !$this->error ) {
			$sql    = "CALL actualizarAeronave( {$idAeronave}, '{$aeronave}', {$idTipoAeronave}, {$idEstadoAeronave}, '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}

		if ( !$this->error ) {
			foreach ($aeronaveClases as $clase) {
				$this->actualizarClaseAeronave( $idAeronave, $clase->idClase, $clase->capacidad );
				$capacidadTotal += $clase->capacidad;

				if ( $this->error )
					break;
			}

			if ( !$this->error AND $capacidadTotal <= 0 ) {
				$this->error   = true;
				$this->message = "La capacidad de la Aeronave no puede ser cero";
			}
		}

		if ( $this->error )
			$this->conexion->query( "ROLLBACK" );

		else
			$this->conexion->query( "COMMIT" );
	}

	public function ingresarClaseAeronave( $idAeronave, $idClase, $capacidad )
	{
		$idAeronave = (int)$idAeronave;
		$idClase    = (int)$idClase;
		$capacidad  = (int)$capacidad;

		if ( !( $idAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "La aeronave no esta definida";
		}

		if ( !$this->error AND !( $idClase > 0 ) ) {
			$this->error   = true;
			$this->message = "Clase no seleccionada";
		}

		if ( !$this->error ) {
			$sql    = "CALL ingresarClaseAeronave( {$idAeronave}, {$idClase}, {$capacidad}, '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function actualizarClaseAeronave( $idAeronave, $idClase, $capacidad )
	{
		$idAeronave = (int)$idAeronave;
		$idClase    = (int)$idClase;
		$capacidad  = (int)$capacidad;

		if ( !( $idAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "La aeronave no esta definida";
		}

		if ( !$this->error AND !( $idClase > 0 ) ) {
			$this->error   = true;
			$this->message = "Clase no seleccionada";
		}

		if ( !$this->error ) {
			$sql    = "CALL actualizarClaseAeronave( {$idAeronave}, {$idClase}, {$capacidad}, '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function ingresarVueloAeronave( $idAeronave, $aeropuertoOrigen, $horaSalida, $fechaSalida, $aeropuertoDestino, $horaAterrizaje, $fechaAterrizaje, $lstClasePrecio )
	{
		$idAeronave        = (int)$idAeronave;
		$aeropuertoDestino = (int)$aeropuertoDestino;
		$aeropuertoOrigen  = (int)$aeropuertoOrigen;
		$fechaSalida       = (string)$fechaSalida;
		$fechaAterrizaje   = (string)$fechaAterrizaje;

		if ( !( $idAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "La aeronave no esta definida";
		}

		if ( !$this->error AND !( $aeropuertoOrigen > 0 ) ) {
			$this->error   = true;
			$this->message = "Seleccione un aeropuerto de origen válido";
		}

		if ( !$this->error AND !( $aeropuertoDestino > 0 ) ) {
			$this->error   = true;
			$this->message = "Seleccione un aeropuerto de destino válido";
		}

		if ( !$this->error AND $aeropuertoOrigen == $aeropuertoDestino ) {
			$this->error   = true;
			$this->message = "El aeropuerto de origen debe ser diferente al de destino";
		}

		if ( !$this->error AND strlen( $fechaSalida ) != 10 ) {
			$this->error   = true;
			$this->message = "Fecha de salida no es válida";
		}

		if ( !$this->error AND strlen( $fechaAterrizaje ) != 10 ) {
			$this->error   = true;
			$this->message = "Fecha de aterrizaje no es válida";
		}

		if ( !$this->error AND !( strtotime( $fechaSalida . " " . $horaSalida ) > strtotime("now") ) ) {
			$this->error   = true;
			$this->message = "La fecha y hora de SALIDA debe ser mayor a la actual";
		}

		if ( !$this->error AND !( strtotime( $fechaAterrizaje . " " . $horaAterrizaje ) > strtotime( $fechaSalida . " " . $horaSalida ) ) ) {
			$this->error   = true;
			$this->message = "La fecha y hora de ATERRIZAJE debe ser mayor a la de salida";
		}

		if ( !$this->error ) {
			$this->queryOriginal( "START TRANSACTION" );
			
			$sql = "CALL ingresarVueloAeronave( {$idAeronave}, {$aeropuertoOrigen}, '{$horaSalida}', '{$fechaSalida}', 
						{$aeropuertoDestino}, '{$horaAterrizaje}', '{$fechaAterrizaje}', '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;

			if ( $result->data > 0 )
				$this->data = $this->lstVueloAeronave( 0, 0, 0, $result->data );

			// INGRESAR PRECIO DE BOLETO DEL VIAJE
			if ( !$this->error ) {
				$precioTotal = 0;

				foreach ($lstClasePrecio as $clase) {
					$this->precioBoleto( $result->data, $clase->idClase, $clase->precio );
					$precioTotal += $clase->precio;

					if ( $this->error )
						break;
				}

				if ( !$this->error AND $precioTotal <= 0 ) {
					$this->error   = true;
					$this->message = "El precio del vuelo no esta definido";
				}
			}

			// SI OCURRIO UN ERROR
			if ( $this->error ) {
				$this->data = NULL;
				$this->queryOriginal( "ROLLBACK" );
			}

			// SI NO EXISTE NINGUN ERROR
			else
				$this->queryOriginal( "COMMIT" );
		}
	}

	private function precioBoleto( $idVuelo, $idClase, $precioBoleto )
	{
		$idVuelo      = (int)$idVuelo;
		$idClase      = (int)$idClase;
		$precioBoleto = (double)$precioBoleto;

		if ( !( $idVuelo > 0 ) ) {
			$this->error   = true;
			$this->message = "El ID del Vuelo no esta definido";
		}

		if ( !$this->error AND !( $idClase > 0 ) ) {
			$this->error   = true;
			$this->message = "Clase no seleccionada";
		}

		if ( !$this->error ) {
			$sql = "CALL ingresarPrecioBoleto( {$idVuelo}, {$idClase}, {$precioBoleto} )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function actualizarEstadoVuelo( $idVuelo, $comentario, $idEstadoVuelo )
	{
		$idVuelo       = (int)$idVuelo;
		$comentario    = $this->conexion->real_escape_string( $comentario );
		$idEstadoVuelo = (int)$idEstadoVuelo;

		if ( !( $idVuelo > 0 ) ) {
			$this->error   = true;
			$this->message = "El vuelo no esta definido";
		}

		if ( !$this->error AND !( $idEstadoVuelo > 0 ) ) {
			$this->error   = true;
			$this->message = "Seleccione un estado de vuelo válido";
		}

		if ( !$this->error AND $idEstadoVuelo == 5 AND strlen( $comentario ) < 4 ) {
			$this->error   = true;
			$this->message = "Debe de dejar un comentario";
		}

		if ( strlen( $comentario ) > 3 )
			$comentario = "'" . $comentario . "'";

		else
			$comentario = "NULL";

		if ( !$this->error ) {
			$sql    = "CALL actualizarEstadoVuelo( {$idVuelo}, {$comentario}, {$idEstadoVuelo}, '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function ingresarIncidente( $idVuelo, $incidente )
	{
		$idVuelo   = (int)$idVuelo;
		$incidente = $this->conexion->real_escape_string( $incidente );

		if ( !( $idVuelo > 0 ) ) {
			$this->error   = true;
			$this->message = "Id de vuelo no definido";
		}

		if ( !$this->error AND !( strlen( $incidente ) > 3 ) ) {
			$this->error   = true;
			$this->message = "Incidente demasiado corto";
		}

		if ( !$this->error ) {
			$sql = "CALL ingresarIncidente( {$idVuelo}, '{$incidente}', '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;

			if ( $result->data > 0 )
				$this->data = $this->lstVueloAeronave( 0, 0, 0, $result->data );
		}
	}

	/* CAT */

	public function lstClase()
	{
		$lst = array();

		$sql    = "SELECT idClase, clase FROM clase ORDER BY idClase ASC";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function lstTipoAeronave()
	{
		$lst = array();

		$sql    = "SELECT idTipoAeronave, tipoAeronave FROM tipoAeronave";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function lstEstadoAeronave()
	{
		$lst = array();

		$sql    = "SELECT idEstadoAeronave, estadoAeronave FROM estadoAeronave";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function lstEstadoVuelo()
	{
		$lst = array();

		$sql    = "SELECT idEstadoVuelo, estadoVuelo FROM estadoVuelo";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	/* LST */
	public function lstAeropuerto( $idAeropuerto = 0, $idContinente = 0, $codigoPais = 0, $idCiudad = 0 )
	{
		$lst          = array();
		$idContinente = (int)$idContinente;
		$codigoPais   = (int)$codigoPais;
		$idCiudad     = (int)$idCiudad;
		$idAeropuerto = (int)$idAeropuerto;
		$where        = "";

		if ( $idContinente > 0 )
			$where = " WHERE idContinente = $idContinente ";

		if ( $codigoPais > 0 )
			$where = " WHERE codigoPais = $codigoPais ";

		if ( $idCiudad > 0 )
			$where = " WHERE idCiudad = $idCiudad ";

		if ( $idAeropuerto > 0 )
			$where = " WHERE idAeropuerto = $idAeropuerto ";

		$sql    = "SELECT * FROM vstAeropuerto $where ";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function lstAeronave( $idAeronave = 0, $idTipoAeronave = 0, $idEstadoAeronave = 0 )
	{
		$lst              = array();
		$idAeronave       = (int)$idAeronave;
		$idTipoAeronave   = (int)$idTipoAeronave;
		$idEstadoAeronave = (int)$idEstadoAeronave;
		$where            = "";

		if ( $idTipoAeronave > 0 )
			$where .= " AND idTipoAeronave = $idTipoAeronave ";

		if ( $idEstadoAeronave > 0 )
			$where .= " AND idEstadoAeronave = $idEstadoAeronave ";

		if ( $idAeronave > 0 )
			$where .= " AND idAeronave = $idAeronave ";

		if ( strlen( $where ) )
			$where = " WHERE " . substr( $where, 4 );

		$sql    = "SELECT * FROM vstAeronave $where ";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$row->lstClase = $this->lstAeronaveClase( $row->idAeronave );
			$lst[] = $row;
		}

		return $lst;
	}

	public function lstAeronaveClase( $idAeronave = 0 )
	{
		$lst        = array();
		$idAeronave = (int)$idAeronave;

		if ( $idAeronave > 0 ) {
			$sql    = "SELECT * FROM vstAeronaveClase WHERE idAeronave = $idAeronave";
			$result = $this->queryLst( $sql );
			while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
				$row->capacidad = (int)$row->capacidad;
				$row->precio    = 0;
				
				unset( $row->idAeronave );

				$lst[] = $row;
			}
		}

		return $lst;
	}

	public function lstVueloAeronave( $idEstadoVuelo = 0, $aeropuertoOrigen = 0, $idAeronave = 0, $idVuelo = 0, $idTipoAeronave = 0 )
	{
		$lst   = array();
		$where = "";

		$idEstadoVuelo    = (int)$idEstadoVuelo;
		$aeropuertoOrigen = (int)$aeropuertoOrigen;
		$idAeronave       = (int)$idAeronave;
		$idVuelo          = (int)$idVuelo;
		$idTipoAeronave   = (int)$idTipoAeronave;

		if ( $idEstadoVuelo > 0 )
			$where .= " AND idEstadoVuelo = $idEstadoVuelo ";

		if ( $aeropuertoOrigen > 0 )
			$where .= " AND aeropuertoOrigen = $aeropuertoOrigen ";

		if ( $idAeronave > 0 )
			$where .= " AND idAeronave = $idAeronave ";

		if ( $idVuelo > 0 )
			$where .= " AND idVuelo = $idVuelo ";

		if ( $idTipoAeronave > 0 )
			$where .= " AND idTipoAeronave = $idTipoAeronave ";

		if ( strlen( $where ) > 3 ) {
			$where = substr($where, 5);
			$sql = "SELECT 
				idVuelo,
			    idAeronave,
			    aeronave,
			    idTipoAeronave,
			    tipoAeronave,
			    aeropuertoOrigen,
			    TIME_FORMAT(horaSalida, '%H:%i')AS 'horaSalida',
			    DATE_FORMAT(fechaSalida, '%d/%m/%Y')AS 'fechaSalida',
			    origen,
			    ciudadOrigen,
			    paisOrigen,
			    continenteOrigen,
				aeropuertoDestino,
				TIME_FORMAT(horaAterrizaje, '%H:%i')AS 'horaAterrizaje',
			    DATE_FORMAT(fechaAterrizaje, '%d/%m/%Y')AS 'fechaAterrizaje',
			    tiempoViaje,
			    destino,
			    ciudadDestino,
			    paisDestino,
			    continenteDestino,
			    idEstadoVuelo,
			    estadoVuelo,
			    fechaEstado
			FROM vstVueloAeronave WHERE $where 
			ORDER BY fechaEstado DESC";

			$disponibilidad = new Reservacion( $this->conexion, $this->session );

			$result = $this->queryLst( $sql );
			while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
				
				if ( $row->tiempoViaje > 60 )
					$row->tiempoViaje = ROUND( $row->tiempoViaje / 60, 2) . " hora(s)";

				else
					$row->tiempoViaje = $row->tiempoViaje . " minutos";

				$row->lstClase = $disponibilidad->getDisponibilidad( $row->idVuelo );

				$lst[] = $row;
			}
		}

		return $lst;
	}

	public function lstIncidente( $idVuelo )
	{
		$lst = array();

		$idVuelo = (int)$idVuelo;

		if ( $idVuelo > 0 ) {
			$sql = "SELECT 
						idVuelo,
					    incidente,
					    fechaHora,
						idUsuario,
					    nombreCompleto
					FROM vstIncidente WHERE idVuelo = {$idVuelo} 
					ORDER BY fechaHora ASC";

			$result = $this->queryLst( $sql );
			while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
				$lst[] = $row;
			}
		}

		return $lst;
	}

	public function getResponse()
	{
		return (object)array( "response" => !$this->error, "message" => $this->message, "data" => $this->data );
	}
}
?>




