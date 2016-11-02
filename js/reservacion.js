miApp.controller('ctrlReservacion', function($scope, $http, $timeout){
	$scope.lstTipoPago   = [];
	$scope.lstContinente = [];
	$scope.lstPais       = [];
	$scope.lstCiudad     = [];
	$scope.idContinente  = "";
	$scope.codigoPais    = '';
	$scope.idCiudad      = '';
	$scope.responsable   = {};
	$scope.today         = '';
	$scope.persona       = {};
	$scope.nuevaPersona  = false;
	$scope.newPersona    = {
		idGenero : 'm'
	};

	$scope.pasaporteResponsable = "";
	$scope.numeroPasaporte = "";
	$scope.buscarDestino   = "";
	$scope.lstDestino      = [];
	$scope.consultando     = false;
	$scope.destino         = {};

	// INI => RESERVACION
	($scope.iniReservacion = function () {
		$http.post('controller.php', {
			action : 'iniReservacion'
		})
		.success(function ( data ) {
			console.log( 'ddd', data );

			$scope.today       = data.today;
			$scope.lstTipoPago = data.lstTipoPago;
			$timeout(function () {
				$scope.idTipoPago = $scope.lstTipoPago[ 0 ].idTipoPago;
			});
		});
	})();

	// GUARDA PERSONA NUEVA
	$scope.guardarPersona = function () {
		$("#loading").show();

		var fechaNacimiento = $("#fechaNacimiento").val();

		var datos = {
			action          : 'ingresarPersona',
			fechaNacimiento : fechaNacimiento,
			numeroPasaporte : $scope.newPersona.numeroPasaporte,
			identificacion  : $scope.newPersona.identificacion,
			urlFoto         : $scope.newPersona.urlFoto,
			nombres         : $scope.newPersona.nombres,
			apellidos       : $scope.newPersona.apellidos,
			idGenero        : $scope.newPersona.idGenero,
			correo          : $scope.newPersona.correo,
			telefono        : $scope.newPersona.telefono,
			idCiudad        : $scope.newPersona.idCiudad
		};

		$http.post('controller.php', datos)
		.success(function ( data ) {
			$("#loading").hide();

			if ( data.response ) {
				if ( data.data )
					$scope.persona = data.data;

				Materialize.toast(data.message, 5000);
				$("#mdlPersona").closeModal();
			}else{
				Materialize.toast(data.message, 7000);
			}
		})
		.error(function () {
			$("#loading").hide();
		});
	};

	// CONSULTA UNA PERSONA
	$scope.consultarPersona = function ( titular ) {
		if ( $scope.numeroPasaporte.length < 3 && titular ) {
			Materialize.toast("Campo no puede estar vacio", 4000);
			return true;
		}

		if ( $scope.pasaporteResponsable.length < 3 && !titular ) {
			Materialize.toast("Campo no puede estar vacio", 4000);
			return true;
		}

		$("#loading").show();

		$http.post('controller.php', {
			action          : 'consultarPersona',
			numeroPasaporte : ( titular ? $scope.numeroPasaporte : $scope.pasaporteResponsable ),
			idVuelo         : $scope.destino.idVuelo
		})
		.success(function ( data ) {
			$("#loading").hide();

			if ( data.persona.idPersona ) {
				if ( titular ) 
					$scope.persona = data.persona;

				else {
					
					if ( data.persona.idReservacion > 0 )
						$scope.responsable = data.persona;

					else{
						var nombreCompleto = data.persona.nombres + " " + data.persona.apellidos;
						Materialize.toast( nombreCompleto + " no se encuentra en el Vuelo Actual", 4000);
					}
				}
			}else{
				Materialize.toast("No se encontro la persona", 4000);

				if ( titular ) {
					$scope.newPersona = {
						idGenero        : 'm',
						numeroPasaporte : $scope.numeroPasaporte
					};
					$("#mdlPersona").openModal();
					$timeout(function() { Materialize.updateTextFields(); });
				}
			}
		})
		.error(function () {
			$("#loading").hide();
		});
	};

	// CONSULTA DESTINO
	$scope.consultarDestinos = function () {

		if ( $scope.buscarDestino.length < 3 ) {
			$scope.lstDestino = [];
			return true;
		}

		$scope.consultando = true;
		var destino = angular.copy( $scope.buscarDestino );

		$http.post('controller.php', {
			action : 'consultarDestinos',
			like   : destino
		})
		.success(function ( data ) {
			$scope.consultando = false;
			if ( data )
				$scope.lstDestino = data;

			// SI EL DESTINO CAMBIO
			if ( destino != $scope.buscarDestino )
				$scope.consultarDestinos();
		})
		.error(function () {
			$scope.consultando = false;
		});
	};

	// SELECCIONAR DESTINO
	$scope.selDestino = function (item) {
		$scope.destino = angular.copy( item );
	};

	// ===== GUARDAR RESERVACION =====
	$scope.guardarReservacion = function () {
		$("#loading").show();
		var datos = {
			action     : 'guardarReservacion',
			idVuelo    : $scope.destino.idVuelo,
			idClase    : $scope.idClase,
			idPersona  : $scope.persona.idPersona,
			encargado  : $scope.responsable.idPersona,
			idTipoPago : $scope.idTipoPago,
		};

		$http.post('controller.php', datos)
		.success(function ( data ) {
			$("#loading").hide();

			if ( data.response ) {
				if ( data.data )
					$scope.reservacion = data.data;

				Materialize.toast(data.message, 5000);
				$("#mdlReservacion").closeModal();
				$("#mdlReservacionConf").openModal();
			}else{
				Materialize.toast(data.message, 7000);
			}
		})
		.error(function () {
			$("#loading").hide();
		});
	};

	// ABRIR DIALOGO PARA NUEVA RESERVACION
	$scope.openReservacion = function () {
		$scope.reset();
		$("#mdlReservacion").openModal();
	};

	$scope.reset = function () {
		$scope.persona              = {};
		$scope.destino              = {};
		$scope.idClase              = '';
		$scope.totalReservacion     = 0;
		$scope.responsable          = {};
		$scope.buscarResponsable    = '';
		$scope.numeroPasaporte 		= '';
		$scope.pasaporteResponsable = '';
	};

	// LOCALIZACION
	($scope.getContinentes = function () {
		$scope.lstContinente = [];
		$http.post('controller.php', {
			action : 'lstContinente'
		})
		.success(function ( data ) {
			if ( data ) {
				$scope.lstContinente = data;
				$timeout(function () {
					if ( $scope.lstContinente.length ) {
						$scope.idContinente  = $scope.lstContinente[ 0 ].idContinente;
					}
					else {
						$scope.idContinente  = '';
					}
				});
			}
		});
	})();

	// OBTIENE PAISES
	$scope.getPaises = function ( idContinente ) {
		$scope.lstPais = [];

		$http.post('controller.php', {
			action       : 'lstPais',
			idContinente : idContinente
		})
		.success(function ( data ) {
			if ( data ) {
				$scope.lstPais = data;

				$timeout(function () {
					if ( $scope.lstPais.length )
						$scope.codigoPais = $scope.lstPais[ 0 ].codigoPais;
					else
						$scope.codigoPais = '';
				});
			}
		});
	};

	// OBTIENE CIUDADES
	$scope.getCiudades = function ( codigoPais ) {
		$scope.lstCiudad = [];

		$http.post('controller.php', {
			action     : 'lstCiudad',
			codigoPais : codigoPais
		})
		.success(function ( data ) {
			console.log( data );
			if ( data ) {
				$scope.lstCiudad = data;

				$timeout(function () {
					if ( $scope.lstCiudad.length )
						$scope.newPersona.idCiudad = $scope.lstCiudad[ 0 ].idCiudad;
				});
			}
		});
	};

	// CALCULA EL TOTAL A CANCELAR
	$scope.calcularTotal = function () {
		$scope.totalReservacion = 0;
		if ( $scope.idClase > 0 ) {
			if ( $scope.destino.lstClase ) {
				for (var i = 0; i < $scope.destino.lstClase.length; i++) {
					if ( $scope.destino.lstClase[ i ].idClase == $scope.idClase ) {
						$scope.totalReservacion = parseFloat( $scope.destino.lstClase[ i ].precioBoleto );
						break;
					}
				}
			}
		}

		if ( $scope.persona.menorEdad && $scope.responsable.idPersona === undefined ) {
			$scope.totalReservacion += 100.00;
		}
	};

	$scope.$watch('idClase', function (_new) {
		$scope.calcularTotal();
	});

	$scope.$watch('responsable.idPersona', function (_new) {
		console.log( "cambio responsable" );
		$scope.calcularTotal();
	});

	$scope.$watch('buscarDestino', function (_new) {
		$scope.consultarDestinos();
	});

	$scope.$watch('idEstadoVuelo', function (_new) {
		$scope.idAeropuertoDestino = '';

		if ( _new ){
			$scope.getVuelos();
		}
		else{ 
			$scope.lstVueloAeronave = [];
		}
	});

	$scope.$watch('idTipoAeronave', function (_new) {
		$scope.idAeronave = "";
		if ( _new ){
			$scope.getAeronaves();
		}
	});

	$scope.$watch('idContinente', function (_new) {
		$scope.codigoPais = '';
		if ( _new > 0 ) {
			$scope.getPaises( _new );
		}else{
			$scope.lstPais    = [];
			$scope.lstCiudad  = [];
			$scope.codigoPais = '';
		}
	});

	$scope.$watch('codigoPais', function (_new) {
		$scope.newPersona.idCiudad = '';
		if ( _new ){
			$scope.getCiudades( _new );
		}
		else{ 
			$scope.lstCiudad = [];
		}
	});

	// JQUERY-MATERIALIZE
	$('.modal-trigger').leanModal({
		dismissible: true,
		starting_top: '0',
		ending_top: '10%',
	});

	$('#fechaNacimiento').pickadate({
		selectMonths: true,
		selectYears: 80,
		closeOnSelect: true,
  		closeOnClear: true,
  		max: 'today',
  		format: 'yyyy-mm-dd',
  		onSet: function(context) {
			//$scope.edadPersona = moment( $scope.today ).diff( $("#fechaNacimiento").val(), 'years');
			//$scope.$apply();
		}
	});

	$("#multiple").html5Uploader({
        name: "foto",
        postUrl: "upload.php",
        onServerLoadStart: function () {
			$("#loading").show();
        	$scope.newPersona.urlFoto = "";
			$scope.$apply();
        },
        onServerError: function (d) {
			$("#loading").hide();
        },
        onSuccess: function (res, other, data) {
			$("#loading").hide();
        	if ( data.length > 0 ) {
        		var respuesta = JSON.parse( data );
        		if ( respuesta.response ) {
					$scope.newPersona.urlFoto = respuesta.nombreImg;
					$scope.$apply();
        		}else{
					Materialize.toast(respuesta.mensaje, 5000);
				}
        	}else{
        		console.error( "No se recibio respuesta" );
        	}
        }
    });
});





