miApp.controller('ctrlAeronave', function($scope, $http, $timeout){
	$scope.lstClase          = [];
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
			$scope.lstEstadoAeronave = data.lstEstadoAeronave;
			$scope.lstTipoAeronave   = data.lstTipoAeronave;
			$timeout(function () {
				$scope.aeronave.idTipoAeronave = $scope.lstTipoAeronave[ 0 ].idTipoAeronave;
				$scope.reloadClases();
			})
		});
	})();

	($scope.getAeronaves = function () {
		$scope.lstAeronave = [];
		$http.post('controller.php', {
			action : 'lstAeronave'
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

	$('.modal-trigger').leanModal({
		dismissible: true, // Modal can be dismissed by clicking outside of the modal
		starting_top: '0', // Starting top style attribute
		ending_top: '10%', // Ending top style attribute
	});
});





