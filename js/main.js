var miApp = angular.module('app', ['ngRoute']);

miApp.controller('principal', function($scope, $http){
	$("#loading").hide();
	
	$scope.getItem = function (_array, _campo, _valor) {
		var item = {};

		for (var i = 0; i < _array.length; i++) {
			if( _array[ i ][ _campo ] == _valor ) {
				item = _array[ i ];
				break;
			}
		}

		return item;
	};

	$scope.getIndex = function (_array, _campo, _valor) {
		var index = -1;
		
		for (var i = 0; i < _array.length; i++) {
			if( _array[ i ][ _campo ] == _valor ) {
				index = i;
				break;
			}
		}

		return index;
	};
});


miApp.directive("select", function() {
  return {
    restrict: "E",
    require: "?ngModel",
    scope: false,
    link: function (scope, element, attrs, ngModel) {
      if (!ngModel) {
        return;
      }
      element.bind("keyup", function() {
        element.triggerHandler("change");
      })
    }
  }
})

$(function () {
	$(".button-collapse").sideNav();

	$("#mobile-demo>li>a").on('click',function (ev) {
		$("#sidenav-overlay").click();
	});

	$(".bottom-sheet:before").on('click',function (ev) {
		console.log("close jejej");
	});
});

