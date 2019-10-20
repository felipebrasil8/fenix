(function(){

	'use strict';

	angular.module('app')
	.controller('cadastrarAreaCtrl', ['$scope', 'AreaFactory', function($scope, AreaFactory)
	{			
		var init = function()
		{
			$scope.success = "";
		};

		var limparCampos = function()
		{
			$scope.area.nome = '';
			$scope.area.descricao = '';
			$scope.area.gestor = '';
		};
                
		$scope.cadastrar = function( area )
		{
			area.nome = area.nome.toUpperCase();
			area.descricao = area.descricao.toUpperCase();

			$scope.success = false;
            $scope.errors = false;
			
			$scope.form.$valid = false;   

			AreaFactory.cadastrarArea(area)     			
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