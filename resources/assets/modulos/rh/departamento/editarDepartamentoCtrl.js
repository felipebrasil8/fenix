(function(){

	'use strict';

	angular.module('app')

	.controller('editarDepartamentoCtrl', ['$scope', 'DepartamentoFactory', '$filter', function($scope, DepartamentoFactory, $filter) {
        
		var areas = _areas;
		var gestores = _gestores;
		var departamento = _departamento;

		var init = function()
		{
			$scope.success = "";
			$scope.departamento = departamento;
			$scope.area  = areas;
			$scope.gestores = gestores;

			var indexObj = 0;
            angular.forEach($scope.gestores, function(value) {
                if( value.id == departamento.funcionario_id )
                {
                    indexObj = $scope.gestores.indexOf( value );
                }
            });
            $scope.departamento.gestor = $scope.gestores[indexObj];

            indexObj = 0;
            angular.forEach($scope.area, function(value) {
                if( value.id == departamento.area_id )
                {
                    indexObj = $scope.area.indexOf( value );
                }
            });
            $scope.departamento.area = $scope.area[indexObj];
            
		};

        $scope.editar = function(departamento)
        {  
			departamento.nome = departamento.nome.toUpperCase();
			departamento.descricao = departamento.descricao.toUpperCase();
			
        	$scope.success = false;
        	$scope.errors = false;

            

        	DepartamentoFactory.atualizarDep(departamento)
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




