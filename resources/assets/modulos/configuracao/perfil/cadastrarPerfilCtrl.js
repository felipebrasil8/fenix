(function(){

	'use strict';

	angular.module('app')
	.controller('cadastrarPerfilCtrl', ['$scope', 'PerfilFactory', function($scope, PerfilFactory)
	{
		var acessos = _acessos;
		var qtde_itens_checados = 0;

		var init = function()
       	{
       		$scope.acesso = {};
       		$scope.success = "";
			listaAcessos();    
		};

		var listaAcessos = function()
		{
			$scope.lista = acessos;

			$scope.checkedes = {
				lista: []
			};
		}

		$scope.checkAll = function()
		{
			if($scope.todos == true)
			{
				$scope.checkedes.lista = $scope.lista.map(function(item) { return item.id; });
			}
			else
			{
				$scope.checkedes.lista = [];
			}
		};

		var limparCampos = function()
		{
			$scope.checkedes.lista = [];
			$scope.acesso = {};
			$scope.todos = false;
		    $scope.perfil.nome = '';
		};
        
		$scope.cadastrar = function(perfil)
		{

			// perfil.nome = perfil.nome.toUpperCase();
			
			$scope.form.$valid = false;
			$scope.success = false;
			$scope.errors = false;

			var acesso = {};
			angular.forEach($scope.checkedes.lista, function(itens)
			{
				acesso[itens] = itens;
			});

			perfil.acesso = acesso;
			
			PerfilFactory.cadastrarPerfil(perfil)
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

		$scope.verificaClick = function()
		{
			setItensChecados();			

			if( getIntensChecados() == getItens())
			{				
				$scope.todos = true;
			}
			else
			{
				$scope.todos = false;
			}
		};

		var setItensChecados = function()
		{
			qtde_itens_checados = $scope.checkedes.lista.length;
		}

		var getIntensChecados = function()
		{
			return qtde_itens_checados;
		};

		var getItens = function()
		{
			return acessos.length;
		};

		init();
	        
	}]);

})();