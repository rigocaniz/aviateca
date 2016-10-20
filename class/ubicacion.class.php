<?php
/**
* UBICACION: ciudad, pais, continente
*/
class Ubicacion extends Query
{
	private $conexion = NULL;
	private $error    = false;
	private $message  = "";
	function __construct( &$conexion )
	{
		parent::__construct( $conexion );
		$this->conexion = $conexion;
	}

	public function ingresarContinente( $continente )
	{
		$continente = $this->conexion->real_escape_string( $continente );
		if ( strlen( $continente ) < 3 ) {
			$this->error   = true;
			$this->message = "Número de caracteres demasiado corto";
		}

		if ( !$this->error ) {
			$sql    = "CALL ingresarContinente( '{$continente}' )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function ingresarPais( $codigoPais, $pais, $nacionalidad, $idContinente )
	{
		$pais         = $this->conexion->real_escape_string( $pais );
		$nacionalidad = $this->conexion->real_escape_string( $nacionalidad );
		$codigoPais   = (int)$codigoPais;
		$idContinente = (int)$idContinente;

		if ( strlen( $pais ) < 3 ) {
			$this->error   = true;
			$this->message = "Campo Pais muy corto";
		}

		if ( !$this->error AND strlen( $nacionalidad ) < 3 ) {
			$this->error   = true;
			$this->message = "Campo Nacionalidad muy corto";
		}

		if ( !$this->error AND !( $codigoPais > 0 ) ) {
			$this->error   = true;
			$this->message = "Código de pais no válido";
		}

		if ( !$this->error AND !( $idContinente > 0 ) ) {
			$this->error   = true;
			$this->message = "Continente no válido";
		}

		if ( !$this->error ) {
			$sql    = "CALL ingresarPais( {$codigoPais}, '{$pais}', '{$nacionalidad}', {$idContinente} )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function ingresarCiudad( $codigoPais, $ciudad )
	{
		$ciudad     = $this->conexion->real_escape_string( $ciudad );
		$codigoPais = (int)$codigoPais;

		if ( strlen( $ciudad ) < 3 ) {
			$this->error   = true;
			$this->message = "Campo Ciudad muy corto";
		}

		if ( !$this->error AND !( $codigoPais > 0 ) ) {
			$this->error   = true;
			$this->message = "Código de pais no válido";
		}

		if ( !$this->error ) {
			$sql    = "CALL ingresarCiudad( '{$ciudad}', {$codigoPais} )";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function lstCiudad( $codigoPais )
	{
		$lst = array();
		$codigoPais = (int)$codigoPais;

		$sql    = "SELECT idCiudad, ciudad FROM ciudad WHERE codigoPais = {$codigoPais} ";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function lstPais( $idContinente )
	{
		$lst = array();
		$idContinente = (int)$idContinente;

		$sql    = "SELECT codigoPais, pais, nacionalidad FROM pais WHERE idContinente = {$idContinente} ORDER BY codigoPais ASC";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function lstContinente()
	{
		$lst = array();

		$sql    = "SELECT idContinente, continente FROM continente ORDER BY idContinente ASC";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function getResponse()
	{
		return (object)array( "response" => !$this->error, "message" => $this->message );
	}
}
?>




