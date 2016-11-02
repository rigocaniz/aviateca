miApp.controller('ctrlUsuario', function($scope, $http, $timeout){
	$scope.lstUsuario         = [];
	$scope.lstTipoUsuario     = [];
	$scope.idTipoUsuario      = "";
	$scope.idUsuario          = "";
	$scope.nombre             = "";
	$scope.cui                = "";
	$scope.correo             = "";
	$scope.porcentajeComision = "";

	($scope.iniUsuario = function () {
		$scope.lstTipoUsuario = [];
		$http.post('controller.php', { action : 'iniUsuario' })
		.success(function ( data ) {
			if ( data ) 
				$scope.lstTipoUsuario = data;

			$timeout(function () {
				if ( $scope.lstTipoUsuario.length )
					$scope.idTipoUsuario = $scope.lstTipoUsuario[ 0 ].idTipoUsuario;
			});
		});
	})();

	// USUARIOS
	($scope.getUsuarios = function () {
		$scope.lstUsuario = [];

		$http.post('controller.php', {
			action : 'lstUsuario'
		})
		.success(function ( data ) {
			if ( data ) {
				$scope.lstUsuario = data;
			}
		});
	})();

	$scope.openUsuario = function () {
		$scope.reset();
		$("#mdlUsuario").openModal();
	};

	$scope.guardarUsuario = function () {
		$("#loading").show();

		var datos = {
			action             : 'usuarioNuevo',
			idUsuario          : $scope.idUsuario,
			nombre             : $scope.nombre,
			cui                : $scope.cui,
			correo             : $scope.correo,
			porcentajeComision : $scope.porcentajeComision,
			idTipoUsuario      : $scope.idTipoUsuario
		};

		$http.post('controller.php', datos)
		.success(function ( data ) {
			$("#loading").hide();

			if ( data.response ) {
				$scope.getUsuarios();
				Materialize.toast(data.message, 5000);
				$("#mdlUsuario").closeModal();
			}else{
				Materialize.toast(data.message, 7000);
			}
		})
		.error(function () {
			$("#loading").hide();
		});
	};

	// RESETEAR
	$scope.resetear = function ( idUsuario ) {
		$("#loading").show();

		$http.post('controller.php', {
			action    : 'resetearUsuario',
			idUsuario : idUsuario
		})
		.success(function ( data ) {
			$("#loading").hide();

			if ( data.response ) {
				Materialize.toast(data.message, 5000);
			}else{
				Materialize.toast(data.message, 7000);
			}
		});
	};

	$scope.reset = function () {
		$scope.idUsuario          = "";
		$scope.nombre             = "";
		$scope.cui                = "";
		$scope.correo             = "";
		$scope.porcentajeComision = "";
	};

	$('.modal-trigger').leanModal({
		dismissible: true,
		starting_top: '0',
		ending_top: '10%',
	});
});





