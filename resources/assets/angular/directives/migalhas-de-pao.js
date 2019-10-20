(function(){

    'use strict';

    angular.module('app')
    .directive('migalha', function() {
        
        return {
            
            restrict: 'E',  
            replace: true,          
            controller: 'migalhaDePaoCtrl',
            templateUrl: '/templates/migalhas-de-pao.html',
            scope: {
            	titulo: "@",
            	descricao: "@"
            }
        };
    });
    
})();