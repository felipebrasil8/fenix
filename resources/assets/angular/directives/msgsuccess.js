(function(){

	'use strict';

	angular.module('app')
	.directive('msgSuccess', function() {
	        
	    return {            
	        restrict: 'E',            
	        templateUrl: '/templates/msg-success.html'
	    };
	});

})();