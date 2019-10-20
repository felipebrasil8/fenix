(function(){

	'use strict';

	angular.module('app')
	.directive('msgError', function() {
	        
	    return {            
	        restrict: 'E',            
	        replace: true,
	        templateUrl: '/templates/msg-error.html'
	    };
	});

})();