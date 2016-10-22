miApp.controller('ctrlAeronave', function($scope, $http, $timeout){
	$scope.lstContinente = [];
	$scope.lstPais       = [];
	$scope.lstCiudad     = [];
	$scope.lstAeropuerto = [];
	$scope.idContinente  = "";
	$scope.codigoPais    = '';
	$scope.idCiudad      = '';

	$scope.lstClase          = [];
	$scope.lstDia            = [];
	$scope.lstEstadoAeronave = [];
	$scope.lstTipoAeronave   = [];
	$scope.nuevo             = true;
	$scope.aeronave          = {};
	$scope.aeronaveClases    = [];

	($scope.ini = function () {
		$http.post('controller.php', {
			action : 'iniAeronave'
		})
		.success(function ( data ) {
			console.log( data );
			$scope.lstClase          = data.lstClase;
			$scope.lstDia            = data.lstDia;
			$scope.lstEstadoAeronave = data.lstEstadoAeronave;
			$scope.lstTipoAeronave   = data.lstTipoAeronave;
			$timeout(function () {
				$scope.reloadClases();
			})
		});
	})();

	$scope.reloadClases = function () {
		$scope.aeronaveClases = [];
		for (var i = 0; i < $scope.lstClase.length; i++) {
			$scope.aeronaveClases.push({
				'idClase'   : $scope.lstClase[ i ].idClase,
				'clase'     : $scope.lstClase[ i ].clase,
				'precio'    : 0,
				'capacidad' : 0,
			});
		}
	};

	$scope.newAeronave = function () {
		$scope.nuevo             = true;
		$scope.aeronave.aeronave = '';
		$scope.reloadClases();
		$("#mdlAeronave").openModal();
	};

	$scope.modAeronave = function ( aeronave ) {
		$scope.nuevo	= false;
		$scope.aeronave = angular.copy( aeronave );

		$http.post('controller.php', {
			action     : 'lstAeronaveClase',
			idAeronave : $scope.aeronave.idAeronave
		})
		.success(function ( data ) {
			if ( data ) {
				$scope.aeronaveClases = data;
			}
		});

		$("#mdlAeronave").openModal();
	};

	$scope.placeAeronave = function ( aeronave ) {
		$scope.aeronave = angular.copy( aeronave );

		// CONSULTA CLASES AERONAVE
		$http.post('controller.php', {
			action     : 'lstAeronaveClase',
			idAeronave : $scope.aeronave.idAeronave
		})
		.success(function ( data ) {
			if ( data ) {
				$scope.aeronaveClases = data;
			}
		});

		$("#mdlAeronave").openModal();
	};

	($scope.getAeronaves = function () {
		$scope.lstAeronave = [];
		$http.post('controller.php', {
			action     : 'lstAeronave'
		})
		.success(function ( data ) {
			console.log( data );
			if ( data ) {
				$scope.lstAeronave = data;
				$timeout(function () {
					$('.tooltipped').tooltip({delay: 50});
				});
			}
		});
	})();

	$scope.guardarAeronave = function () {
		$("#loading").show();

		$http.post('controller.php', {
			action           : 'guardarAeronave',
			nuevo 		     : $scope.nuevo,
			aeronave         : $scope.aeronave.aeronave,
			idTipoAeronave   : $scope.aeronave.idTipoAeronave,
			aeronaveClases   : $scope.aeronaveClases,
			idAeronave       : $scope.aeronave.idAeronave,
			idEstadoAeronave : $scope.aeronave.idEstadoAeronave
		})
		.success(function ( data ) {
			$("#loading").hide();

			console.log( data );
			if ( data.response ) {
				$("#mdlAeronave").closeModal();
				$scope.aeronave = {};
				$scope.getAeronaves();
				$scope.reloadClases();
				Materialize.toast(data.message, 5000);
			}else{
				Materialize.toast(data.message, 7000);
			}
		})
		.error(function () {
			$("#loading").hide();
		});
	};

	$scope.getContinentes = function () {
		$scope.lstContinente = [];
		$http.post('controller.php', {
			action : 'lstContinente'
		})
		.success(function ( data ) {
			if ( data ) {
				$scope.lstContinente = data;
				$timeout(function () {
					$scope.idContinente  = $scope.lstContinente[ 0 ].idContinente;
				});
			}
		});
	};

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
					$scope.codigoPais = $scope.lstPais[ 0 ].codigoPais;
				});
			}
		});
	};

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
			}
		});
	};

	$scope.$watch('idContinente', function (_new) {
		if ( _new > 0 ) {
			$scope.getPaises( _new );
			$scope.codigoPais = '';
		}else{
			$scope.lstPais    = [];
			$scope.lstCiudad  = [];
			$scope.codigoPais = '';
			$scope.idCiudad   = '';
		}
	});

	$scope.$watch('codigoPais', function (_new) {
		if ( _new ){
			$scope.getCiudades( _new );
		}
		else{ 
			$scope.idCiudad  = '';
			$scope.lstCiudad = [];
		}
	});

	$scope.$watch('idCiudad', function (_new) {
		if ( _new ){
			$scope.getAeropuertos();
		}
		else{ 
			$scope.lstAeropuerto = [];
		}
	});

	$('.modal-trigger').leanModal({
		dismissible: true, // Modal can be dismissed by clicking outside of the modal
		starting_top: '0', // Starting top style attribute
		ending_top: '10%', // Ending top style attribute
	});
});





