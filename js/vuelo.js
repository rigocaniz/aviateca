miApp.controller('ctrlVuelo', function($scope, $http, $timeout){
	$scope.idEstadoVuelo    = '';
	$scope.idTipoAeronave   = '';
	$scope.idAeronave   	= '';
	$scope.lstTipoAeronave  = [];
	$scope.lstEstadoVuelo   = [];
	$scope.lstVueloAeronave = [];
	$scope.lstAeronave      = [];
	$scope.lstHora          = [];
	$scope.lstMinuto        = [];
	$scope.horaOrigen = $scope.minutoOrigen = $scope.horaDestino = $scope.minutoDestino;


	for (var i = 0; i < 24; i++) {
		var hora = ( i > 9 ? i : '0' + i );
		$scope.lstHora.push( hora.toString() );
	}

	for (var i = 0; i <= 50; i+=10) {
		var minuto = ( i == 0 ? '00' : i );
		$scope.lstMinuto.push( minuto.toString() );
	}


	($scope.iniVuelo = function () {
		$scope.horaOrigen    = $scope.lstHora[ 0 ];
		$scope.minutoOrigen  = $scope.lstMinuto[ 0 ];
		$scope.horaDestino   = $scope.lstHora[ 0 ];
		$scope.minutoDestino = $scope.lstMinuto[ 0 ];

		$http.post('controller.php', {
			action : 'iniVuelo'
		})
		.success(function ( data ) {
			if ( data ) {
				console.log( data );
				$scope.lstTipoAeronave = data.lstTipoAeronave;
				$scope.lstEstadoVuelo  = data.lstEstadoVuelo;
				$timeout(function () {
					$scope.idTipoAeronave = $scope.lstTipoAeronave[ 0 ].idTipoAeronave;
					$scope.idEstadoVuelo  = $scope.lstEstadoVuelo[ 0 ].idEstadoVuelo;
				});
			}
		});
	})();

	$scope.getVuelos = function () {
		$("#loading").show();

		$http.post('controller.php', {
			action        : 'lstVueloAeronave',
			idEstadoVuelo : $scope.idEstadoVuelo
		})
		.success(function ( data ) {
			$("#loading").hide();

			if ( data )
				$scope.lstVueloAeronave = data;
		})
		.error(function () {
			$("#loading").hide();
		});
	};

	$scope.getAeronaves = function () {
		$scope.lstAeronave = [];
		$http.post('controller.php', {
			action           : 'lstAeronave',
			idTipoAeronave   : $scope.idTipoAeronave,
			idEstadoAeronave : 1
		})
		.success(function ( data ) {
			console.log( "aeronave", data );
			
			if ( data ) {
				$scope.lstAeronave = data;
				$timeout(function () {
					if ( $scope.lstAeronave.length )
						$scope.idAeronave = $scope.lstAeronave[ 0 ].idAeronave;
				});
			}
		});
	};

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





	$scope.lstContinente = [];
	$scope.lstPais       = [];
	$scope.lstCiudad     = [];
	$scope.lstAeropuerto = [];
	$scope.idContinente  = "";
	$scope.codigoPais    = '';
	$scope.idCiudad      = '';
	$scope.idAeropuerto  = '';

	$scope.placeAeronave = function () {
		$("#mdlDestino").openModal();
	};

	$scope.guardarDestinoAeronave = function () {
		$("#loading").show();

		var horaSalida     = $scope.horaOrigen + ':' + $scope.minutoOrigen + ':00';
		var horaAterrizaje = $scope.horaDestino + ':' + $scope.minutoDestino + ':00';
		var fechaSalida     = $("#deFecha").val();
		var fechaAterrizaje = $("#paraFecha").val();

		var datos = {
			action            : 'ingresarVueloAeronave',
			idAeronave 	      : $scope.idAeronave,
			horaSalida 	      : horaSalida,
			fechaSalida 	  : fechaSalida,
			aeropuertoOrigen  : $scope.idAeropuertoOrigen,
			horaAterrizaje 	  : horaAterrizaje,
			aeropuertoDestino : $scope.idAeropuertoDestino,
			fechaAterrizaje   : fechaAterrizaje
		};

		$http.post('controller.php', datos)
		.success(function ( data ) {
			$("#loading").hide();

			if ( data.response ) {
				if ( data.data.length )
					$scope.lstVueloAeronave.unshift( data.data[ 0 ] );

				Materialize.toast(data.message, 5000);
				$("#mdlDestino").closeModal();
			}else{
				Materialize.toast(data.message, 7000);
			}
		})
		.error(function () {
			$("#loading").hide();
		});
	};

	//
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
						$scope.idContinenteOrigen  = $scope.lstContinente[ 0 ].idContinente;
						$scope.idContinenteDestino = $scope.lstContinente[ 0 ].idContinente;
					}
					else {
						$scope.idContinenteOrigen  = '';
						$scope.idContinenteDestino = '';
					}
				});
			}
		});
	})();

	$scope.getPaises = function ( idContinente, tipo ) {
		if ( tipo == 'origen' )
			$scope.lstPaisOrigen = [];
		else
			$scope.lstPaisDestino = [];

		$http.post('controller.php', {
			action       : 'lstPais',
			idContinente : idContinente
		})
		.success(function ( data ) {
			if ( data ) {
				if ( tipo == 'origen' )
					$scope.lstPaisOrigen = data;
				else
					$scope.lstPaisDestino = data;

				$timeout(function () {
					if ( tipo == 'origen' ) {
						if ( $scope.lstPaisOrigen.length )
							$scope.codigoPaisOrigen = $scope.lstPaisOrigen[ 0 ].codigoPais;
						else
							$scope.codigoPaisOrigen = '';
					}
					else {
						if ( $scope.lstPaisDestino.length )
							$scope.codigoPaisDestino = $scope.lstPaisDestino[ 0 ].codigoPais;
						else
							$scope.codigoPaisDestino = '';
					}
				});
			}
		});
	};

	$scope.getCiudades = function ( codigoPais, tipo ) {
		if ( tipo == 'origen' )
			$scope.lstCiudadOrigen = [];
		else
			$scope.lstCiudadDestino = [];

		$http.post('controller.php', {
			action     : 'lstCiudad',
			codigoPais : codigoPais
		})
		.success(function ( data ) {
			console.log( data );
			if ( data ) {
				if ( tipo == 'origen' )
					$scope.lstCiudadOrigen = data;
				else
					$scope.lstCiudadDestino = data;

				$timeout(function () {
					if ( tipo == 'origen' ) {
						if ( $scope.lstCiudadOrigen.length )
							$scope.idCiudadOrigen = $scope.lstCiudadOrigen[ 0 ].idCiudad;
						else
							$scope.idCiudadOrigen = '';
					}
					else {
						if ( $scope.lstCiudadDestino.length )
							$scope.idCiudadDestino = $scope.lstCiudadDestino[ 0 ].idCiudad;
						else
							$scope.idCiudadDestino = '';
					}
				});
			}
		});
	};

	$scope.getAeropuertos = function ( idCiudad, tipo ) {
		if ( tipo == 'origen' )
			$scope.lstAeropuertoOrigen = [];
		else
			$scope.lstAeropuertoDestino = [];

		$http.post('controller.php', {
			action   : 'lstAeropuerto',
			idCiudad : idCiudad
		})
		.success(function ( data ) {
			console.log( data );
			if ( data ) {
				if ( tipo == 'origen' )
					$scope.lstAeropuertoOrigen = data;
				else
					$scope.lstAeropuertoDestino = data;

				$timeout(function () {
					if ( tipo == 'origen' ) {
						if ( $scope.lstAeropuertoOrigen.length )
							$scope.idAeropuertoOrigen = $scope.lstAeropuertoOrigen[ 0 ].idAeropuerto;
						else
							$scope.idAeropuertoOrigen = '';
					}
					else {
						if ( $scope.lstAeropuertoDestino.length )
							$scope.idAeropuertoDestino = $scope.lstAeropuertoDestino[ 0 ].idAeropuerto;
						else
							$scope.idAeropuertoDestino = '';
					}
				});
			}
		});
	};

	$scope.$watch('idContinenteOrigen', function (_new) {
		$scope.codigoPaisOrigen = '';
		if ( _new > 0 ) {
			$scope.getPaises( _new, 'origen' );
		}else{
			$scope.lstPaisOrigen    = [];
			$scope.lstCiudadOrigen  = [];
			$scope.codigoPaisOrigen = '';
		}
	});

	$scope.$watch('codigoPaisOrigen', function (_new) {
		$scope.idCiudadOrigen = '';
		if ( _new ){
			$scope.getCiudades( _new, 'origen' );
		}
		else{ 
			$scope.lstCiudadOrigen = [];
		}
	});

	$scope.$watch('idCiudadOrigen', function (_new) {
		$scope.idAeropuertoOrigen = '';

		if ( _new ){
			$scope.getAeropuertos(_new, 'origen');
		}
		else{ 
			$scope.lstAeropuertoOrigen = [];
		}
	});

	$scope.$watch('idContinenteDestino', function (_new) {
		$scope.codigoPaisDestino = '';
		if ( _new > 0 ) {
			$scope.getPaises( _new, 'destino' );
		}else{
			$scope.lstPaisDestino    = [];
			$scope.lstCiudadDestino  = [];
			$scope.codigoPaisDestino = '';
		}
	});

	$scope.$watch('codigoPaisDestino', function (_new) {
		$scope.idCiudadDestino = '';
		if ( _new ){
			$scope.getCiudades( _new, 'destino' );
		}
		else{ 
			$scope.lstCiudadDestino = [];
		}
	});

	$scope.$watch('idCiudadDestino', function (_new) {
		$scope.idAeropuertoDestino = '';

		if ( _new ){
			$scope.getAeropuertos(_new, 'destino');
		}
		else{ 
			$scope.lstAeropuertoDestino = [];
		}
	});

	$('.modal-trigger').leanModal({
		dismissible: true, // Modal can be dismissed by clicking outside of the modal
		starting_top: '0', // Starting top style attribute
		ending_top: '10%', // Ending top style attribute
	});

	$('.datepicker').pickadate({
		selectMonths: true,
		selectYears: 2,
		closeOnSelect: true,
  		closeOnClear: true,
  		format: 'yyyy-mm-dd',
	});
});





