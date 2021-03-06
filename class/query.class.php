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
		$error    = false;
		$message  = "";
		$data     = NULL;
		$response = NULL;

		if ( $rs = $this->conexion->query( $query ) ) {
			@$this->conexion->next_result();

			if ( $row = $rs->fetch_object() ) {
				if ( (int)$row->response ) {
					$message = $row->message;

					if ( isset( $row->data ) )
						$data = $row->data;
				}else{
					$error   = true;
					$message = $row->message;
				}
				
				$response = $row->response;

			}else{
				$error   = true;
				$message = "Error al ejecutar el procedimiento";	
			}
		}
		else{
			$error   = true;
			$message = "Error al realizar la consulta";
		}

		return (object)array( 'error' => $error, 'message' => $message, 'data' => $data, 'response' => $response );
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

	public function queryOriginal( $query )
	{
		$error   = false;
		$message = "";

		if ( !$this->conexion->query( $query ) ) {
			$error   = true;
			$message = "No se pudo ejecutar la consulta";
		}

		return (object)array( 'error' => $error, 'message' => $message, 'data' => $data );
	}
}
?>