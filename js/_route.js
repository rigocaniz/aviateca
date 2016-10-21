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
	.otherwise({
		redirectTo: '/'
	});
});
