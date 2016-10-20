miApp.config(function($routeProvider) {
	$routeProvider
	.when('/',{
		templateUrl : 'home.php'
	})
	.when('/localizacion',{
		templateUrl : 'views/localizacion.php',
		controller : 'ctrlLocalizacion'
	})
	.otherwise({
		redirectTo: '/'
	});
});
