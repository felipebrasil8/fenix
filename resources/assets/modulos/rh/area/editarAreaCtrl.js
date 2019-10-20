(function(){

	'use strict';

	angular.module('app')

	.controller('editarAreaCtrl', ['$scope', 'AreaFactory', '$filter', function($scope, AreaFactory, $filter) {
        
		var area = _area;
		var gestores = _gestores;

		var init = function()
		{
			$scope.success = "";
			$scope.area = area;
			$scope.gestores = gestores;

			var indexObj = 0;
            angular.forEach($scope.gestores, function(value) {
                if( value.id == area.gestor_id )
                {
                    indexObj = $scope.gestores.indexOf( value );
                }
            });

            $scope.area.gestor = $scope.gestores[indexObj];
		};

        $scope.editar = function(area)
        {  
			area.nome = area.nome.toUpperCase();
			area.descricao = area.descricao.toUpperCase();
			
        	$scope.success = false;
        	$scope.errors = false;

        	AreaFactory.atualizarArea(area)
            .then(function mySuccess(response) {    

     	        $scope.success = response.data.mensagem;
     	        $scope.url = "../"+response.data.id+"";
                                                        
            }, function(error){
          
	        	$scope.errors = error.data;
            });
        };

        //inicializa controller
		init();

	}]);

})();