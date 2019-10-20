(function(){            

    'use strict';

    angular.module('app')
    .controller('migalhaDePaoCtrl', ['$scope', '$http', function($scope, $http){

        var migalhas = _migalhas;        
		      	
        var init = function(){
             
        $scope.migalhas = migalhas;
        
         };

        init();

    }]);


})(); 

/*
*/