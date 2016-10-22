miApp.controller('ctrlLocalizacion', function($scope, $http, $timeout){
	$scope.tab = 1;
	$scope.lstCiudad = [];
	$scope.lstContinente = [];
	$scope.lstPais = [];
	$scope.idContinente = "";

	$scope.codigoPais = '';
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
				$timeout(function () {
					$scope.idContinente  = $scope.lstContinente[ 0 ].idContinente;
				});
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

	$scope.guardarContinente = function () {
		$("#loading").show();

		$http.post('controller.php', {
			action     : 'ingresarContinente',
			continente : $scope.continente
		})
		.success(function ( data ) {
			$("#loading").hide();

			console.log( data );
			if ( data.response ) {
				$("#mdlContinente").closeModal();
				$scope.continente = "";
				$scope.getContinentes();
				Materialize.toast(data.message, 7000);
			}else{
				Materialize.toast(data.message, 7000);
			}
		})
		.error(function () {
			$("#loading").hide();
		});
	};

	$scope.guardarPais = function () {
		$("#loading").show();

		$http.post('controller.php', {
			action       : 'ingresarPais',
			idContinente : $scope.idContinente,
			codigoPais   : $scope.newPais.codigoPais,
			pais         : $scope.newPais.pais,
			nacionalidad : $scope.newPais.nacionalidad
		})
		.success(function ( data ) {
			$("#loading").hide();

			console.log( data );
			if ( data.response ) {
				$("#mdlPais").closeModal();
				$scope.newPais = {};
				$scope.getPaises( $scope.idContinente );
				Materialize.toast(data.message, 5000);
			}else{
				Materialize.toast(data.message, 7000);
			}
		})
		.error(function () {
			$("#loading").hide();
		});
	};

	$scope.guardarCiudad = function () {
		$("#loading").show();

		$http.post('controller.php', {
			action     : 'ingresarCiudad',
			ciudad     : $scope.newCiudad,
			codigoPais : $scope.codigoPais
		})
		.success(function ( data ) {
			$("#loading").hide();

			console.log( data );
			if ( data.response ) {
				$("#mdlCiudad").closeModal();
				$scope.newCiudad = '';
				$scope.getCiudades( $scope.codigoPais );
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
			$scope.pais       = {};
			$scope.codigoPais = '';
		}
	});

	$scope.$watch('codigoPais', function (_new) {
		console.log( _new );
		if ( _new ){
			$scope.pais = $scope.$parent.getItem( $scope.lstPais, 'codigoPais', _new );
			$scope.getCiudades( _new );
		}
		else
			$scope.lstCiudad = [];
	});

	$('.modal-trigger').leanModal();
});
