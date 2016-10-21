/****
=============================== USUARIO ================================
***/

CREATE FUNCTION secretText( _text VARCHAR(75) )
RETURNS VARCHAR(75)
BEGIN
	RETURN SHA1( _text );
END$$

CREATE PROCEDURE newUser( _idUsuario VARCHAR(15), _idAerolinea INT, _nombreCompleto VARCHAR(75), _idTipoUsuario INT,
	_cui VARCHAR(13), _correo VARCHAR(65), _porcentajeComision DOUBLE(2,2) )
BEGIN
	# IF DUPLICATE
	DECLARE EXIT HANDLER FOR 1062 BEGIN
		SELECT response, message FROM messageApp WHERE idMessage = 'duplicateUser';
    END;
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;
    
	# INSERTA NUEVO USUARIO
	INSERT INTO usuario 
	(idUsuario, idAerolinea, idEstadoUsuario, idTipoUsuario, pass, nombreCompleto, cui, correo, fechaCreado, porcentajeComision) 
		VALUES 
	(_idUsuario, _idAerolinea, 1, _idTipoUsuario, secretText( _idUsuario ), _nombreCompleto, _cui, _correo, NOW(), _porcentajeComision);
	
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

CREATE PROCEDURE login( _idUsuario VARCHAR(15), _pass VARCHAR(55) )
BEGIN
	DECLARE _idEstadoUsuario INT DEFAULT 0;
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;
    
	# CONSULTA SI USUARIO Y CONTRASEÑA COINCIDEN
	SELECT idEstadoUsuario INTO _idEstadoUsuario FROM usuario 
		WHERE idUsuario = _idUsuario ANd pass = secretText( _pass );
	
    # SI EL USUARIO ESTA ACTIVO
    IF _idEstadoUsuario = 1 THEN
    	# SI EL USUARIO Y LA CLAVE SON IGUALES
		IF _idUsuario = _pass THEN
			SELECT response, message FROM messageApp WHERE idMessage = 'changePass';
		# SI NO ES NECESARIO CAMBIAR DE CLAVE
		ELSE
			SELECT response, message FROM messageApp WHERE idMessage = 'loginSuccess';
		END IF;
	# SI NO COINCIDE USUAIRO Y CONTRASEÑA
    ELSEIF _idEstadoUsuario = 0 THEN
		SELECT response, message FROM messageApp WHERE idMessage = 'loginError';
	# SI EL USUARIO ESTA BLOQUEADO
	ELSE
		SELECT response, message FROM messageApp WHERE idMessage = 'userOff';
    END IF;
END$$

CREATE PROCEDURE changePass( _idUsuario VARCHAR(15), _pass VARCHAR(55), _newPass VARCHAR(55) )
BEGIN
	DECLARE _idEstadoUsuario INT DEFAULT 0;
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;
    
    # VALIDA USUARIO Y CLAVE ACTUAL
    SELECT IF( idEstadoUsuario = 1, 1, 2 ) INTO _idEstadoUsuario FROM usuario
		WHERE idUsuario = _idUsuario AND pass = secretText( _pass );
	
    # SI EL ESTADO ES ACTIVO Y EL USUARIO Y CLAVE ES CORRECTA
    IF _idEstadoUsuario = 1 THEN
		UPDATE usuario SET pass = secretText( _newPass )
			WHERE idUsuario = _idUsuario;
            
		SELECT response, message FROM messageApp WHERE idMessage = 'changePassOk';
    # SI EL USUARIO ESTA BLOQUEADO
    ELSEIF _idEstadoUsuario = 2 THEN
		SELECT response, message FROM messageApp WHERE idMessage = 'userOff';
    # SI USUARIO Y CLAVE ANTERIOR NO ES VALIDO
    ELSE
		SELECT response, message FROM messageApp WHERE idMessage = 'loginError';
    END IF;
END$$

CREATE PROCEDURE resetUser( _idUsuario VARCHAR(15) )
BEGIN
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN
        SELECT response, message FROM messageApp WHERE idMessage = 'changePassError';
	END;
	
    # SI EL ESTADO ES ACTIVO Y EL USUARIO Y CLAVE ES CORRECTA
    IF _idEstadoUsuario = 1 THEN
		UPDATE usuario SET pass = secretText( _newPass )
			WHERE idUsuario = _idUsuario;
            
		SELECT response, message FROM messageApp WHERE idMessage = 'changePassOk';
    # SI EL USUARIO ESTA BLOQUEADO
    ELSEIF _idEstadoUsuario = 2 THEN
		SELECT response, message FROM messageApp WHERE idMessage = 'userOff';
    # SI USUARIO Y CLAVE ANTERIOR NO ES VALIDO
    ELSE
		SELECT response, message FROM messageApp WHERE idMessage = 'loginError';
    END IF;
END$$



/****
=============================== UBICACION ================================
***/

CREATE PROCEDURE ingresarContinente( _continente VARCHAR(75) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062, 1023 BEGIN # IF DUPLICATE
		SELECT response, message FROM messageApp WHERE idMessage = 'duplicate';
    END;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;
    
    INSERT INTO continente (continente) VALUES (_continente);
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

CREATE PROCEDURE ingresarPais( _codigoPais INT, _pais VARCHAR(95), _nacionalidad VARCHAR(95), _idContinente INT )
BEGIN
	DECLARE EXIT HANDLER FOR 1062, 1023 BEGIN # IF DUPLICATE
		SELECT response, message FROM messageApp WHERE idMessage = 'duplicate';
    END;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN  # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;
    
    INSERT INTO pais (codigoPais, pais, nacionalidad, idContinente) 
    	VALUES (_codigoPais, _pais, _nacionalidad, _idContinente);
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

CREATE PROCEDURE ingresarCiudad( _ciudad VARCHAR(85), _codigoPais INT )
BEGIN
	DECLARE EXIT HANDLER FOR 1062, 1023 BEGIN # IF DUPLICATE
		SELECT response, message FROM messageApp WHERE idMessage = 'duplicate';
    END;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;
    
    INSERT INTO ciudad (codigoPais, ciudad) VALUES (_codigoPais, _ciudad);
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

/****
=============================== PASAJERO-RESERVACION ================================
***/

CREATE PROCEDURE ingresarPersona( _numeroPasaporte VARCHAR(15), _nombres VARCHAR(75), _apellidos VARCHAR(75), _fechaNacimiento DATE, 
	_correo VARCHAR(75), _telefono VARCHAR(15), _urlFoto VARCHAR(135), _idGenero CHAR(1), _menorEdad BOOLEAN, _idCiudad INT )
BEGIN
	DECLARE EXIT HANDLER FOR 1062, 1023 BEGIN # IF DUPLICATE
		SELECT response, message FROM messageApp WHERE idMessage = 'duplicate';
    END;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;
    
    INSERT INTO persona
    (numeroPasaporte, nombres, apellidos, fechaNacimiento, correo, telefono, urlFoto, idGenero, menorEdad, idCiudad, fechaHora) 
    	VALUES
    (_numeroPasaporte, _nombres, _apellidos, _fechaNacimiento, _correo, _telefono, _urlFoto, _idGenero, _menorEdad, _idCiudad, NOW());
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$


CREATE PROCEDURE ingresarReservacion( _idVuelo INT, _idClase INT, _idPersona INT, _encargado INT, _idTipoPago INT, _idUsuario VARCHAR(15) )
BEGIN
	DECLARE _montoAsesoria DOUBLE(10,2) DEFAULT 0;
	DECLARE _precioVoleto DOUBLE(10,2) DEFAULT 0;
	DECLARE _capacidad INT DEFAULT 0;
	DECLARE _totalPasajeros INT DEFAULT 0;
	DECLARE _idEstadoVuelo INT DEFAULT 0;
	DECLARE _numeroMayorEdad INT DEFAULT 18;
	DECLARE _numeroAsiento INT DEFAULT -1;

	DECLARE EXIT HANDLER FOR 1062, 1023 BEGIN # IF DUPLICATE
		SELECT response, message FROM messageApp WHERE idMessage = 'duplicate';
    END;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

	SET @idUsuario = _idUsuario;
    
	# SI NO TIENE ENCARGADO
    IF ISNULL( _encargado ) THEN
    	# SI ES MENOR DE EDAD Y NO TIENE ENCARGADO, montoAsesoria = 100.00
    	SELECT 100 INTO _montoAsesoria FROM persona 
    		WHERE idPersona = _idPersona AND TIMESTAMPDIFF( YEAR, DATE(fechaNacimiento), CURDATE() ) < _numeroMayorEdad;
    END IF;

    # CONSULTA: precio, capacidad, estadoVuelo, Total Pasajeros en Vuelo
    SELECT 
    	precioVoleto, capacidad, idEstadoVuelo, count( r.idReservacion ) 
    		INTO 
    	_precioVoleto, _capacidad, _idEstadoVuelo, _totalPasajeros
    FROM vuelo AS v
    	JOIN aeronave_clase AS ac
    		ON v.idAeronave = ac.idAeronave AND ac.idClase = _idClase 
    	LEFT JOIN reservacion AS r
    		ON r.idVuelo = v.idVuelo AND r.idClase = _idClase
    WHERE idVuelo = _idVuelo;

    # SI EL VUELO ESTA PROGRAMADO
    IF _idEstadoVuelo = 1 THEN
    	# SI SE EXCEDIO EL LIMITE DE PASAJEROS
    	IF ( _totalPasajeros + 1 ) > _capacidad THEN
	    	SELECT response, message FROM messageApp WHERE idMessage = 'limitError';
	    # SI CUMPLE CON TODOS LOS REQUISITOS
    	ELSE
    		# NUMERO DE ASIENTO
    		SET _numeroAsiento = ( _totalPasajeros + 1 );

		    INSERT INTO reservacion
		    (idVuelo, idClase, numeroAsiento, idPersona, encargado, idEstadoReservacion, idTipoPago, precioVoleto, montoRecargo, 
		    	montoAsesoria, idUsuario, fechaHora)
		    	VALUES
		    (_idVuelo, _idClase, _numeroAsiento, _idPersona, _encargado, 1, _idTipoPago, _precioVoleto, 0, 
		    	_montoAsesoria, _idUsuario, NOW());
		    
		    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
    	END IF;
	# SI EL VUELO ESTA: FINALIZADO O CANCELADO
    ELSE
	    SELECT response, message FROM messageApp WHERE idMessage = 'statusError';
    END IF;
END$$

CREATE PROCEDURE cancelarReservacion( _idReservacion INT, _idUsuario VARCHAR(15) )
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

	SET @idUsuario = _idUsuario;
    
	UPDATE reservacion SET 
		precioVoleto = 0,
		montoAsesoria = 0,
		montoRecargo = 100.00,
		idEstadoReservacion = 3
	WHERE idReservacion = _idReservacion;

	SELECT response, message FROM messageApp WHERE idMessage = 'cancelSuccess';
END$$


/****
=============================== AERONAVE ================================
***/

CREATE PROCEDURE ingresarAeronave( _aeronave VARCHAR(45), _idTipoAeronave INT, _idUsuario VARCHAR(15) )
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

    INSERT INTO aeronave (aeronave, idTipoAeronave, idEstadoAeronave) 
    	VALUES (_aeronave, _idTipoAeronave, 1);

    INSERT INTO bit_aeronave_estadoAeronave (idAeronave, idEstadoAeronave, idUsuario, fechaHora) 
    	VALUES (last_insert_id(), 1, _idUsuario, NOW());
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

CREATE PROCEDURE actualizarEstadoAeronave( _idAeronave INT, _idEstadoAeronave INT, _idUsuario VARCHAR(15) )
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

    UPDATE aeronave SET idEstadoAeronave = _idEstadoAeronave
    	WHERE idAeronave = _idAeronave;

    INSERT INTO bit_aeronave_estadoAeronave (idAeronave, idEstadoAeronave, idUsuario, fechaHora) 
    	VALUES (_idAeronave, _idEstadoAeronave, _idUsuario, NOW());
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

CREATE PROCEDURE ingresarClaseAeronave( _idAeronave INT, _idClase INT, _precioVoleto DOUBLE(10,2), _capacidad INT, _idUsuario VARCHAR(15) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062, 1023 BEGIN # IF DUPLICATE
		SELECT response, message FROM messageApp WHERE idMessage = 'duplicate';
    END;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

    INSERT INTO aeronave_clase (idAeronave, idClase, precioVoleto, capacidad) 
    	VALUES (_idAeronave, _idClase, _precioVoleto, _capacidad);

    INSERT INTO bit_aeronave_clase (idAeronave, idClase, precioVoleto, capacidad, idUsuario, fechaHora) 
    	VALUES (_idAeronave, _idClase, _precioVoleto, _capacidad, _idUsuario, NOW());
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

CREATE PROCEDURE actualizarClaveAeronave( _idAeronave INT, _idClase INT, _precioVoleto DOUBLE(10,2), _capacidad INT, _idUsuario VARCHAR(15) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062, 1023 BEGIN # IF DUPLICATE
		SELECT response, message FROM messageApp WHERE idMessage = 'duplicate';
    END;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

    UPDATE aeronave_clase 
    	SET precioVoleto = _precioVoleto, 
    		capacidad = _capacidad 
    	WHERE idAeronave = _idAeronave AND idClase = _idClase;

    INSERT INTO bit_aeronave_clase (idAeronave, idClase, precioVoleto, capacidad, idUsuario, fechaHora) 
    	VALUES (_idAeronave, _idClase, _precioVoleto, _capacidad, _idUsuario, NOW());
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

CREATE PROCEDURE ingresarAeronaveDia( _idAeronave INT, _idDia INT, _aeropuertoDestino INT, _horaSalida TIME, _idUsuario VARCHAR(15) )
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

    INSERT INTO aeronave_dia (idAeronave, idDia, aeropuertoDestino, horaSalida) 
    	VALUES (_idAeronave, _idDia, _aeropuertoDestino, _horaSalida);

    INSERT INTO bit_aeronave_dia (idAeronave, idDia, aeropuertoDestino, horaSalida, idUsuario, fechaHora) 
    	VALUES (_idAeronave, _idDia, _aeropuertoDestino, _horaSalida, _idUsuario, NOW());
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

CREATE PROCEDURE actualizarAeronaveDia( _idAeronave INT, _idDia INT, _aeropuertoDestino INT, _horaSalida TIME, _idUsuario VARCHAR(15) )
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

    UPDATE aeronave_dia SET 
    	aeropuertoDestino = _aeropuertoDestino,
    	horaSalida = _horaSalida 
    WHERE idAeronave = _idAeronave AND idDia = _idDia AND aeropuertoDestino = _aeropuertoDestino;

    INSERT INTO bit_aeronave_dia (idAeronave, idDia, aeropuertoDestino, horaSalida, idUsuario, fechaHora) 
    	VALUES (_idAeronave, _idDia, _aeropuertoDestino, _horaSalida, _idUsuario, NOW());
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$


/****
=============================== VUELOS ================================
***/

CREATE PROCEDURE ingresarAeropuerto( _idCiudad INT, _aeropuerto VARCHAR(95), _idUsuario VARCHAR(15) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062, 1023 BEGIN # IF DUPLICATE
		SELECT response, message FROM messageApp WHERE idMessage = 'duplicate';
    END;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

    INSERT INTO aeropuerto (idCiudad, aeropuerto, idUsuario) 
    	VALUES (_idCiudad, _aeropuerto, _idUsuario);
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

CREATE PROCEDURE ingresarVuelo( _idAeronave INT, _horaSalida TIME, _fechaSalida DATE, _horaAterrizaje TIME, _fechaAterrizaje DATE,
	_aeropuertoOrigen INT, _aeropuertoDestino INT, _idUsuario VARCHAR(15) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062, 1023 BEGIN # IF DUPLICATE
		SELECT response, message FROM messageApp WHERE idMessage = 'duplicate';
    END;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

	SET @idUsuario = _idUsuario;
    
    INSERT INTO vuelo
    (idAeronave, horaSalida, fechaSalida, horaAterrizaje, fechaAterrizaje, aeropuertoOrigen, aeropuertoDestino, idEstadoVuelo, idUsuario) 
    	VALUES
    (_idAeronave, _horaSalida, _fechaSalida, _horaAterrizaje, _fechaAterrizaje,	_aeropuertoOrigen, _aeropuertoDestino, 1, _idUsuario);
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$

CREATE PROCEDURE ingresarIncidente( _idVuelo INT, _incidente TEXT, _idUsuario VARCHAR(15) )
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN # OTHERS ERRORS
        SELECT response, message FROM messageApp WHERE idMessage = 'error';
	END;

    INSERT INTO incidente (idVuelo, incidente, idUsuario) 
    	VALUES (_idVuelo, _incidente, _idUsuario);
    
    SELECT response, message FROM messageApp WHERE idMessage = 'saveSuccess';
END$$












