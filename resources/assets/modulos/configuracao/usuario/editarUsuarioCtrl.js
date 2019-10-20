(function(){

	'use strict';

	angular.module('app')
    .controller('editarUsuarioCtrl', ['$scope', '$http', 'UsuarioFactory', function($scope, $http, UsuarioFactory){

		var usuario = _usuario;
		var perfis = _perfis;
		var funcionarios = _funcionarios;
		var func_selected = _func_selected;
		var perf_selected = _perf_selected;		

		var init = function(){

			$scope.usuario = {};
			$scope.perfis = perfis;	
			$scope.funcionarios = funcionarios;	
			$scope.func_selected = func_selected;
			$scope.perf_selected = perf_selected;
			listarUsuario();
    	};	

    	var listarUsuario = function(){    		

    		$scope.usuario = usuario;
			$scope.usuario.funcionario = $scope.func_selected[0];
			$scope.usuario.perfil = $scope.perf_selected[0];			
    	};

    	var limparCampos = function() {
			
			$scope.usuario = {};
		};

		$scope.atualizar = function(usuario){
			
			usuario.nome = usuario.nome.toUpperCase();
			usuario.usuario = usuario.usuario.toLowerCase();
			
			$scope.form.$valid = false;   
			$scope.success = false;
        	$scope.errors = false;

			UsuarioFactory.atualizarUsuario(usuario, usuario.id)
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