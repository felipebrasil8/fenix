(function(){

    'use strict';

    angular.module('app')
    .directive('modalConfirm', function() {
        
        return {
            
            restrict: 'E',            
            controller: 'modalConfirmCtrl'
        };
    });
    
})();