<?php
$archivo   = $_FILES['foto'];
$extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
$error     = false;
$mensaje   = "";
$nombre    = "";

// EXTENSIONES VALIDAS
$extensiones = array("jpg", "jpeg", "png", "gif");

if ( in_array( strtolower( $extension ), $extensiones ) ) {
	$nombre = uniqid() . "." . $extension;
	if ( !move_uploaded_file( $archivo['tmp_name'], "fotos/" . $nombre ) ) {
		$mensaje = "Error al subir la foto";
		$error   = true;
	}
}else{
	$mensaje = "Error, tipo de archivo no válido: " . $extension;
	$error   = true;
}

echo json_encode( array("response" => !$error, "mensaje" => $mensaje, "nombreImg" => $nombre ) );
?>
