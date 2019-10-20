'use strict';

angular.module('app')

.controller('copiarPerfilCtrl', ['$scope', '$window', 'PerfilFactory', function($scope, $window, PerfilFactory)
{   
	var perfil = _perfil;
	var acessos = _acessos;

	var init = function()
   	{
		$scope.perfil = perfil;
		listarAcessos();
	};

	$scope.copiar = function(perfil)
	{
		perfil.nome = perfil.nome.toUpperCase();

		$scope.success = false;
		$scope.errors = false;
		
    	PerfilFactory.copiarPerfil(perfil)    
        .then(function mySuccess(response) {    

 	        $scope.success = response.data.mensagem;
 	        $scope.url = "../"+response.data.id+"";
                                                    
        }, function(error){

        	$scope.errors = error.data;

        });
    };

	var listarAcessos = function()
	{
		$scope.lista = acessos;

		$scope.checkedes = {
			lista: []
		};

		angular.forEach(acessos, function(itens)
		{
			if( itens.checked == true )
			{
				$scope.checkedes.lista[itens.id] = itens.id;			  			  
			}
		});
	};

    init();

}]);