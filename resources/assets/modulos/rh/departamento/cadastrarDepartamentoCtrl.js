(function(){

	'use strict';

	angular.module('app')
	.controller('cadastrarDepartamentoCtrl', ['$scope', 'DepartamentoFactory', function($scope, DepartamentoFactory)
	{			
		var init = function()
		{
			$scope.success = "";
		};

		var limparCampos = function()
		{
			$scope.departamento.nome = '';
			$scope.departamento.descricao = '';
			$scope.departamento.gestor = '';
			$scope.departamento.area = '';
			$scope.departamento.ticket = '';
		};
                
		$scope.cadastrar = function( departamento )
		{
			departamento.nome = departamento.nome.toUpperCase();
			departamento.descricao = departamento.descricao.toUpperCase();

			$scope.success = false;
            $scope.errors = false;
			
			$scope.form.$valid = false;   

			DepartamentoFactory.cadastrarDep(departamento)     			
	        .then(function mySuccess(response) { 			

	        	$scope.success = response.data.mensagem;
	        	$scope.url = ""+response.data.id+"";
        		limparCampos();

	        }, function(error){

			   	$scope.errors = error.data;	        	

	        }).finally(function() {                                        

		    	$scope.form.$valid = true;   

		    });		

		};
		
		//inicializa controller cadastrarAcessoCtrl
		init();	        
	}]);
})();