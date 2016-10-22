<?php
/**
* AERONAVE: aeronave, estado, clase (precio, capacidad), aeropuerto
*/
class Aeronave extends Query
{
	private $error    = false;
	private $session  = NULL;
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
			$sql    = "CALL ingresarAeropuerto( {$idCiudad}, '{$aeropuerto}', '{$this->session->getUser()}' )";
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
				$this->ingresarClaseAeronave( $idAeronave, $clase->idClase, $clase->precio, $clase->capacidad );
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
				$this->actualizarClaseAeronave( $idAeronave, $clase->idClase, $clase->precio, $clase->capacidad );
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

	public function ingresarClaseAeronave( $idAeronave, $idClase, $precioBoleto, $capacidad )
	{
		$idAeronave   = (int)$idAeronave;
		$idClase      = (int)$idClase;
		$precioBoleto = (double)$precioBoleto;
		$capacidad    = (int)$capacidad;

		if ( !( $idAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "La aeronave no esta definida";
		}

		if ( !$this->error AND !( $idClase > 0 ) ) {
			$this->error   = true;
			$this->message = "Clase no seleccionada";
		}

		if ( !$this->error ) {
			$sql    = "CALL ingresarClaseAeronave( {$idAeronave}, {$idClase}, {$precioBoleto}, {$capacidad}, '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function actualizarClaseAeronave( $idAeronave, $idClase, $precioBoleto, $capacidad )
	{
		$idAeronave   = (int)$idAeronave;
		$idClase      = (int)$idClase;
		$precioBoleto = (double)$precioBoleto;
		$capacidad    = (int)$capacidad;

		if ( !( $idAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "La aeronave no esta definida";
		}

		if ( !$this->error AND !( $idClase > 0 ) ) {
			$this->error   = true;
			$this->message = "Clase no seleccionada";
		}

		if ( !$this->error ) {
			$sql    = "CALL actualizarClaseAeronave( {$idAeronave}, {$idClase}, {$precioBoleto}, {$capacidad}, '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function ingresarAeronaveDia( $idAeronave, $idDia, $aeropuertoDestino, $horaSalida )
	{
		$idAeronave        = (int)$idAeronave;
		$idDia             = (int)$idDia;
		$aeropuertoDestino = (int)$aeropuertoDestino;

		if ( !( $idAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "La aeronave no esta definida";
		}

		if ( !$this->error AND !( $idDia > 0 ) ) {
			$this->error   = true;
			$this->message = "Seleccione un día válido";
		}

		if ( !$this->error AND !( $aeropuertoDestino > 0 ) ) {
			$this->error   = true;
			$this->message = "Seleccione un aeropuerto de destino válido";
		}

		if ( !$this->error ) {
			$sql    = "CALL ingresarAeronaveDia( {$idAeronave}, {$idDia}, {$aeropuertoDestino}, '{$horaSalida}', '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function actualizarAeronaveDia( $idAeronave, $idDia, $aeropuertoDestino, $horaSalida )
	{
		$idAeronave        = (int)$idAeronave;
		$idDia             = (int)$idDia;
		$aeropuertoDestino = (int)$aeropuertoDestino;

		if ( !( $idAeronave > 0 ) ) {
			$this->error   = true;
			$this->message = "La aeronave no esta definida";
		}

		if ( !$this->error AND !( $idDia > 0 ) ) {
			$this->error   = true;
			$this->message = "Seleccione un día válido";
		}

		if ( !$this->error AND !( $aeropuertoDestino > 0 ) ) {
			$this->error   = true;
			$this->message = "Seleccione un aeropuerto de destino válido";
		}

		if ( !$this->error ) {
			$sql    = "CALL actualizarAeronaveDia( {$idAeronave}, {$idDia}, {$aeropuertoDestino}, '{$horaSalida}', '{$this->session->getUser()}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	/* CAT */
	public function lstDia()
	{
		$lst = array();

		$sql    = "SELECT idDia, dia FROM dia ORDER BY idDia ASC ";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

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
			$where = " WHERE idTipoAeronave = $idTipoAeronave ";

		if ( $idEstadoAeronave > 0 )
			$where = " WHERE idEstadoAeronave = $idEstadoAeronave ";

		if ( $idAeronave > 0 )
			$where = " WHERE idAeronave = $idAeronave ";

		$sql    = "SELECT * FROM vstAeronave $where ";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
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
				$row->precio    = (double)$row->precioBoleto;
				
				unset( $row->precioBoleto );
				unset( $row->idAeronave );

				$lst[] = $row;
			}
		}

		return $lst;
	}

	public function lstAeronaveDestino( $idAeronave = 0 )
	{
		$lst        = array();
		$idAeronave = (int)$idAeronave;

		if ( $idAeronave > 0 ) {
			$sql    = "SELECT * FROM vstAeronaveDestino WHERE idAeronave = {$idAeronave} ";
			$result = $this->queryLst( $sql );
			while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
				$lst[] = $row;
			}
		}

		return $lst;
	}

	public function getResponse()
	{
		return (object)array( "response" => !$this->error, "message" => $this->message );
	}
}
?>




