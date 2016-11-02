<?php
/**
* USUARIO
*/
class Usuario extends Query
{
	private $error   = false;
	private $message = "";
	private $session = NULL;
	private $data    = NULL;

	function __construct( &$conexion, &$session )
	{
		parent::__construct( $conexion );
		$this->session = $session;
	}

	public function usuarioNuevo( $idUsuario, $nombreCompleto, $idTipoUsuario, $cui, $correo, $porcentajeComision )
	{
		$idTipoUsuario      = (int)$idTipoUsuario;
		$cui                = (int)$cui;
		$porcentajeComision = (double)$porcentajeComision;

		if ( !$porcentajeComision ) {
			$porcentajeComision = "NULL";
		}

		if ( !$idTipoUsuario ) {
			$this->error   = true;
			$this->message = "Tipo de Usuario no definido";
		}
		else if ( strlen( $cui ) != 13 ) {
			$this->error   = true;
			$this->message = "CUI debe ser de 13 caracteres";
		}
		else if ( !( strlen( $idUsuario ) > 3 ) ) {
			$this->error   = true;
			$this->message = "Usuario muy corto";
		}
		else if ( !( strlen( $nombreCompleto ) > 3 ) ) {
			$this->error   = true;
			$this->message = "Nombre muy corto";
		}
		else if ( !( strlen( $correo ) > 3 ) ) {
			$this->error   = true;
			$this->message = "El correo ingresado no es valido";
		}
		else if ( !( $porcentajeComision > 0 ) AND $idTipoUsuario == 1 ) {
			$this->error   = true;
			$this->message = "Porcentaje comisión no definido";
		}


		if ( !$this->error ) {
			$sql = "CALL newUser( '{$idUsuario}', 0, '{$nombreCompleto}', {$idTipoUsuario}, '{$cui}', '{$correo}', {$porcentajeComision})";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;

			if ( $result->data > 0 )
				$this->data = $result->data;
		}
	}

	public function login( $idUsuario, $pass )
	{
		$info = NULL;

		$sql = "CALL login( '{$idUsuario}', '{$pass}' ) ";
		$result = $this->query( $sql );
		// RESULT
		$this->error   = $result->error;
		$this->message = $result->message;
		$this->data    = $result->response;

		if ( !$this->error ) {
			$usuario = $this->lstUsuario( $idUsuario );
			if ( count( $usuario ) ) {
				$info = $usuario[ 0 ];
			}
		}

		return (object)array( "response" => !$this->error, "message" => $this->message, "data" => $this->data, "info" => $info );
	}

	public function cambiarClave( $idUsuario, $pass, $newPass )
	{
		$idTipoUsuario      = (int)$idTipoUsuario;
		$porcentajeComision = (double)$porcentajeComision;

		if ( !( strlen( $newPass ) > 5 ) ) {
			$this->error   = true;
			$this->message = "La nueva contraseña debe ser de minimo 6 digitos";
		}

		if ( !$this->error ) {
			$sql = "CALL changePass( '{$idUsuario}', '{$pass}', '{$newPass}' ) ";
			$result = $this->query( $sql );
			// RESULT
			$this->error   = $result->error;
			$this->message = $result->message;
		}
	}

	public function resetearUsuario( $idUsuario )
	{
		$idTipoUsuario      = (int)$idTipoUsuario;
		$porcentajeComision = (double)$porcentajeComision;

		$sql = "CALL resetUser( '{$idUsuario}' ) ";
		$result = $this->query( $sql );
		// RESULT
		$this->error   = $result->error;
		$this->message = $result->message;
	}

	/*LSTS*/
	public function lstUsuario( $idUsuario = "")
	{
		$lst   = array();
		$where = "";

		if ( strlen( $idUsuario ) > 3 )
			$where = " WHERE idUsuario = '{$idUsuario}' ";

		$sql = "SELECT 
					idUsuario,
				    idEstado,
				    estado,
				    idTipoUsuario,
				    tipoUsuario,
				    nombreCompleto,
				    cui,
				    correo,
				    porcentajeComision
				FROM vstUsuario $where ";
		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function lstTipoUsuario()
	{
		$lst = array();

		$sql = "SELECT 
					idTipoUsuario,
					tipoUsuario
				FROM tipoUsuario ";

		$result = $this->queryLst( $sql );
		while ( $result->data AND ( $row = $result->data->fetch_object() ) ) {
			$lst[] = $row;
		}

		return $lst;
	}

	public function getResponse()
	{
		return (object)array( "response" => !$this->error, "message" => $this->message, "data" => $this->data );
	}
}
?>
