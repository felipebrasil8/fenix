'use strict';

angular.module('app')

.controller('visualizarPerfilCtrl', ['$scope', function($scope)
{   
	var perfil = _perfil;
	var acessos = _acessos;

	var init = function()
   	{
		$scope.perfil = perfil; 
		$scope.perfil.created_at = perfil.created_at !== null ? new Date(perfil.created_at) : null;
		$scope.perfil.updated_at = perfil.updated_at !== null ? new Date(perfil.updated_at) : null;
		listarAcessos();
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