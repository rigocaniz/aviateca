miApp.config(function($routeProvider) {
	$routeProvider
	.when('/',{
		templateUrl : 'home.php'
	})
	.when('/localizacion',{
		templateUrl : 'views/localizacion.php',
		controller : 'ctrlLocalizacion'
	})

	.when('/aeropuerto',{
		templateUrl : 'views/aeropuerto.php',
		controller : 'ctrlAeropuerto'
	})

	.when('/aeronave',{
		templateUrl : 'views/aeronave.php',
		controller : 'ctrlAeronave'
	})
	.otherwise({
		redirectTo: '/'
	});
});
