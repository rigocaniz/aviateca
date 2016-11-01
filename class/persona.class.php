<?php
/**
* PERSONA: persona
*/
class Persona extends Query
{
	private $error    = false;
	private $message  = "";
	private $session  = NULL;
	private $data  	  = NULL;
	function __construct( &$conexion, &$session )
	{
		parent::__construct( $conexion );
		$this->session = $session;
	}

	public function ingresarPersona( $numeroPasaporte, $identificacion, $nombres, $apellidos, $fechaNacimiento, $correo, 
		$telefono, $urlFoto, $idGenero, $idCiudad )
	{
		$menorEdad      = (bool)$menorEdad;
		$idCiudad       = (int)$idCiudad;
		$telefono       = (int)$telefono;
		$identificacion = strlen( $identificacion ) > 3 ? " '{$identificacion}' " : "NULL";

		$edad = (int)( ( strtotime( date( 'Y-m-d' ) ) - strtotime( $fechaNacimiento ) ) /24/60/60/365 );

		if ( !( strlen( $numeroPasaporte ) > 3 ) ) {
			$this->error   = true;
			$this->message = "Número de Pasaporte no válid";
		}
		else if ( !( strlen( $nombres ) > 3 ) ) {
			$this->error   = true;
			$this->message = "Campo de nombres no válido";
		}
		else if ( !( strlen( $apellidos ) > 3 ) ) {
			$this->error   = true;
			$this->message = "Campo de apellidos no válido";
		}
		else if ( strlen( $fechaNacimiento ) != 10 ) {
			$this->error   = true;
			$this->message = "Fecha de Nacimiento no válido";
		}
		else if ( !( strlen( $correo ) > 3 ) AND $edad >= 18 ) {
			$this->error   = true;
			$this->message = "Correo no válido";
		}
		else if ( !( strlen( $urlFoto ) > 3 ) ) {
			$this->error   = true;
			$this->message = "Foto no définida";
		}
		else if ( $idCiudad == 0 ) {
			$this->error   = true;
			$this->message = "Ciudad de la persona no definida";
		}

		if ( !$this->error ) {
			$sql = "CALL ingresarPersona( '{$numeroPasaporte}', $identificacion, '{$nombres}', '{$apellidos}', '{$fechaNacimiento}', 
					'{$correo}', '{$telefono}', '{$urlFoto}', '{$idGenero}', {$idCiudad}, '{$this->session->getUser()}' )";

			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;

			if ( $result->data > 0 )
				$this->data = $this->getPersona( $result->data );
		}
	}

	public function getPersona( $idPersona = 0, $numeroPasaporte = '', $idVuelo = 0 )
	{
		$lst = array();

		$idPersona = (int)$idPersona; 
		$idVuelo   = (int)$idVuelo; 

		if ( $idPersona > 0 )
			$where = "idPersona = {$idPersona}";
		else
			$where = "numeroPasaporte = '{$numeroPasaporte}'";

		$sql = "SELECT 
					idPersona,
				    numeroPasaporte,
				    identificacion,
				    nombres,
				    apellidos,
				    fechaNacimiento,
				    TIMESTAMPDIFF(year, fechaNacimiento, curdate()) AS 'edad',
				    correo,
				    telefono,
				    urlFoto,
				    genero,
				    ciudad,
				    pais,
				    nacionalidad,
				    (SELECT r.idReservacion
						FROM reservacion AS r 	JOIN persona AS p 	ON r.idPersona = p.idPersona
						WHERE r.idVuelo = {$idVuelo}
							AND r.idEstadoReservacion = 1 
							AND p.numeroPasaporte = '{$numeroPasaporte}'
						LIMIT 1
					)AS 'idReservacion'
    			FROM vstPersona WHERE $where ";

		$result = $this->queryLst( $sql );
		if ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$row->menorEdad = false;
			
			if ( $row->edad < 18 )
				$row->menorEdad = true;
			
			$lst = $row;
		}

		return $lst;
	}

	public function getResponse()
	{
		return (object)array( "response" => !$this->error, "message" => $this->message, "data" => $this->data );
	}
}
?>




