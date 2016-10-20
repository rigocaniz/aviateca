<?php
/**
* CONEXION
*/
class Conexion extends Mysqli
{
	private $host = "127.0.0.1";
	private $user = "root";
	private $pass = "root";
	private $db   = "privado_db";

	function __construct()
	{
		parent::__construct( $this->host, $this->user, $this->pass, $this->db );
		if ( $this->errno ) {
			echo "Ocurrio un error al conectarse con el Servidor de Base de Datos";
			exit();
		}else{
			$this->set_charset('utf8');
		}
	}
}
?>