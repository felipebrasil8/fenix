(function(){

	'use strict';

	angular.module('app')
    .controller('editarCargoCtrl', ['$scope', '$http', 'CargoFactory', function($scope, $http, CargoFactory){

    	var id              = _id;     	
		var nome            = _nome; 
	    var descricao       = _descricao; 
	    var funcionarios    = _funcionarios; 
	    var departamentos   = _departamentos; 	    
	    var func_selected   = _func_selected; 
	    var depart_selected = _depart_selected; 

		var init = function(){

			$scope.cargo = {};
			$scope.cargo.id = id;
			$scope.cargo.nome = nome;
			$scope.cargo.descricao = descricao;

			$scope.gestores    = funcionarios;	
			$scope.departamentos   = departamentos;

			$scope.func_selected   = func_selected;
			$scope.depart_selected = depart_selected;
			listarCargo();
    	};	

    	var listarCargo = function(){

			$scope.cargo.gestor = $scope.func_selected[0];
			$scope.cargo.departamento = $scope.depart_selected[0];			
    	};

    	var limparCampos = function() {
			
			$scope.cargo = {};
		};

		$scope.atualizar = function(cargo){
			
			cargo.nome = cargo.nome.toUpperCase();
			cargo.descricao = cargo.descricao.toUpperCase();
						
			$scope.form.$valid = false;   
			$scope.success = false;
        	$scope.errors = false;

        	CargoFactory.atualizarCargo(cargo, cargo.id)
	        .then(function mySuccess(response) {    

     	        $scope.success = response.data.mensagem;
     	        $scope.url = "../"+response.data.id+"";
                                                        
            }, function(error){
          
	        	$scope.errors = error.data;
            });
		};

    	//inicia o controller
    	init();
    }]);    
})();