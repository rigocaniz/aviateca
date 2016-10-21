<?php
/**
* CLASS: QUERY
*/
class Query
{
	protected $conexion = NULL;
	function __construct( &$conexion )
	{
		$this->conexion = $conexion;
	}

	public function query( $query )
	{
		$error   = false;
		$message = "";

		if ( $rs = $this->conexion->query( $query ) ) {
			@$this->conexion->next_result();

			if ( $row = $rs->fetch_object() ) {
				if ( (bool)$row->response ) {
					$message = $row->message;
				}else{
					$error   = true;
					$message = $row->message;
				}
			}else{
				$error   = true;
				$message = "Error al ejecutar el procedimiento";	
			}
		}
		else{
			$error   = true;
			$message = "Error al realizar la consulta";
		}

		return (object)array( 'error' => $error, 'message' => $message );
	}

	public function queryLst( $query )
	{
		$error   = false;
		$message = "";
		$data    = NULL;

		if ( $rs = $this->conexion->query( $query ) ) {
			@$this->conexion->next_result();
			$data = $rs;
		}
		else{
			$error   = true;
			$message = "Error al realizar la consulta";
		}

		return (object)array( 'error' => $error, 'message' => $message, 'data' => $data );
	}
}
?>