(function(){

	'use strict';

	angular.module('app')
    .controller('cadastrarUsuarioCtrl', ['$scope', '$http', 'UsuarioFactory',function($scope, $http, UsuarioFactory){

    	var perfis = _perfis;
    	var funcionarios = _funcionarios;
    	var politicaSenha = _politicaSenha;

    	var init = function(){

    		$scope.usuario = {};
			listarPerfis();
			listarFuncionarios();
			listarPoliticaSenha();
    	};

    	var listarPerfis = function(){
			$scope.perfis = perfis;	
    	};

    	var listarFuncionarios = function(){
			$scope.funcionarios = funcionarios;
    	};

    	var listarPoliticaSenha = function(){
    		var strPoliticaSenha = '';
    		angular.forEach(politicaSenha, function(value) 
            {
                strPoliticaSenha = strPoliticaSenha+'<span style="text-align: left;">- '+value+"</span><br>";
            }); 
			$scope.politicaSenha = strPoliticaSenha;
    	};

    	$scope.cadastrar = function(usuario){

    		usuario.nome = usuario.nome.toUpperCase();
			usuario.usuario = usuario.usuario.toLowerCase();

    		$scope.form.$valid = false;
    		$scope.success = false;
			$scope.errors = false;

			UsuarioFactory.cadastrarUsuario(usuario)
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
			
			$scope.usuario = {};

		};

		//inicializa controller
    	init();
    }]);
})();