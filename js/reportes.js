miApp.controller('ctrlReportes', function($scope, $http, $timeout){
	$('#deFecha,#paraFecha').pickadate({
		selectMonths: false,
		selectYears: false,
		closeOnSelect: true,
  		closeOnClear: true,
  		hiddenName: true,
  		showMonthsShort: false,
  		container: 'body',
  		max: 'today',
  		format: 'yyyy-mm-dd',
  		onSet: function(context) {
		}
	});
});





