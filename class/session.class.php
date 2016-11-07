<?php
/**
* CONEXION
*/
class Session
{
	public function valid()
	{
		return ( ISSET( $_SESSION['login'] ) AND $_SESSION['login'] );
	}

	public function getUser()
	{
		return $_SESSION['user'];
	}

	public function getName()
	{
		return $_SESSION['name'];
	}

	public function getProfile()
	{
		return (int)$_SESSION['profile'];
	}
}
?>