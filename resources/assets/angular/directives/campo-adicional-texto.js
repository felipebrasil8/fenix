(function(){

    'use strict';

    angular.module('app')
    .directive('adicionalTexto', function() {
        
        return {
            
            restrict: 'E',            
            controller: 'adicionalTextoCtrl'
        };
    });
    
})();