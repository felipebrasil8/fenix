(function(){

	'use strict';

	angular.module('app')
	.controller('cadastrarCargoCtrl', ['$scope', 'CargoFactory', function($scope, CargoFactory) {

		$scope.success = "";
		
		$scope.cadastrar = function(cargo)
		{
	  		cargo.nome = cargo.nome.toUpperCase();
			cargo.descricao = cargo.descricao.toUpperCase();
			
			$scope.form.$valid = false;
			$scope.success = false;
			$scope.errors = false;

			CargoFactory.cadastrarCargo(cargo)     			
	        .then(function (response) { 
	        	
	        	$scope.success = response.data.mensagem;
        		$scope.url = ""+response.data.id+"";
        		limparCampos();

	        }, function(error){

	        	$scope.errors = error.data;	        	

	        }).finally(function() {

		    	$scope.form.$valid = true;   

		    });
		};

		var limparCampos = function() {

			$scope.cargo = {};

		};
		
	}]);
})();