'use strict';

angular.module('app')

.controller('editarPerfilCtrl', ['$scope', 'PerfilFactory', function($scope, PerfilFactory)
{   
	var perfil = _perfil;
	var acessos = _acessos;
	var qtde_itens_checados = 0;

	var init = function()
   	{
		$scope.perfil = perfil; 
		listarAcessos();
	};

	var listarAcessos = function()
	{
		$scope.lista = acessos;

		$scope.checkedes = {
			lista: []
		};

		var checados = 0;
		angular.forEach(acessos, function(itens)
		{
			if( itens.checked == true )
			{
				$scope.checkedes.lista[itens.id] = itens.id;
				checados++;
			}
		});

		if( acessos.length == checados ){
		  $scope.todos = true;
		}

		//apos listar, ira atualizar qtdade de itens checados
		qtde_itens_checados = checados;
	};

	$scope.checkAll = function()
	{
		if($scope.todos == true)
		{
			$scope.checkedes.lista = $scope.lista.map(function(item) { return item.id; });
			qtde_itens_checados = acessos.length;
		}
		else
		{
			$scope.checkedes.lista = [];
			qtde_itens_checados = 0;
		}
	};

	$scope.atualizar = function(perfil)
	{		
		// perfil.nome = perfil.nome.toUpperCase();

		var acesso = {};
		$scope.success = false;
		$scope.errors = false;

		angular.forEach($scope.checkedes.lista, function(itens)
		{
			acesso[itens] = itens;
		});

		perfil.acesso = acesso;

    	PerfilFactory.atualizarPerfil(perfil)
        .then(function mySuccess(response) {    

 	        $scope.success = response.data.mensagem;
 	        $scope.url = "../"+response.data.id+"";
                                                    
        }, function(error){

        	$scope.errors = error.data;

        });
    };

    $scope.verificaClick = function()
    {
		var vm = this;

		if( vm.checked == true ){
			qtde_itens_checados++;
		}else{
			qtde_itens_checados--;
		}			

		if( getIntensChecados() == getItens()){				
			$scope.todos = true;
		}else{
			$scope.todos = false;
		}
	};

	var getIntensChecados = function(){
		return qtde_itens_checados;
	};

	var getItens = function(){
		return acessos.length;
	};

    init();

}]);