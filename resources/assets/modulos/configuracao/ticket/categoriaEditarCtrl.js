(function(){

	'use strict';

	angular.module('app')
    .controller('categoriaEditarCtrl', ['$scope', '$http', '$sce', 
        function($scope, $http, $sce){
    	
        
        $scope.update = function() {
            
            document.departamento.action = "/configuracao/ticket/categoria/"+$scope.categoria.departamento;
            document.departamento.submit();    
           
        }

    }]);
})();