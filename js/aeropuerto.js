miApp.controller('ctrlAeropuerto', function($scope, $http){
	$scope.lstContinente = [];
	$scope.lstPais       = [];
	$scope.lstCiudad     = [];
	$scope.lstAeropuerto = [];
	$scope.idContinente  = "";
	$scope.codigoPais    = '';
	$scope.idCiudad      = '';

	$scope.pais = {};
	$scope.newPais = {};

	($scope.getContinentes = function () {
		$scope.lstContinente = [];
		$http.post('controller.php', {
			action : 'lstContinente'
		})
		.success(function ( data ) {
			if ( data ) {
				$scope.lstContinente = data;
			}
		});
	})();

	$scope.getPaises = function ( idContinente ) {
		$scope.lstPais = [];
		$http.post('controller.php', {
			action       : 'lstPais',
			idContinente : idContinente
		})
		.success(function ( data ) {
			if ( data ) {
				$scope.lstPais = data;
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

	$scope.getAeropuertos = function () {
		$scope.lstAeropuerto = [];
		$http.post('controller.php', {
			action     : 'lstAeropuerto',
			idCiudad : $scope.idCiudad
		})
		.success(function ( data ) {
			console.log( data );
			if ( data ) {
				$scope.lstAeropuerto = data;
			}
		});
	};

	$scope.guardarAeropuerto = function () {
		$("#loading").show();

		$http.post('controller.php', {
			action     : 'ingresarAeropuerto',
			idCiudad   : $scope.idCiudad,
			aeropuerto : $scope.aeropuerto
		})
		.success(function ( data ) {
			$("#loading").hide();

			console.log( data );
			if ( data.response ) {
				$("#mdlAeropuerto").closeModal();
				$scope.aeropuerto = "";
				$scope.getAeropuertos();
				Materialize.toast(data.message, 5000);
			}else{
				Materialize.toast(data.message, 7000);
			}
		})
		.error(function () {
			$("#loading").hide();
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

	$('.modal-trigger').leanModal();
});





