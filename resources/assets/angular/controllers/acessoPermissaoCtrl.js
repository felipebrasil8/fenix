(function(){            

    'use strict';

    angular.module('app')
    .controller('acessoPermissaoCtrl', ['$scope', '$rootScope', '$http', 'serviceAcessoCtrl', function($scope, $rootScope, $http, serviceAcessoCtrl)
    {
        var permissoes = _permissoes;

        var init = function()
        {

        	$scope.exibir = true;
        	$scope.todos = false;
        	$scope.isNumber = angular.isNumber;

            $scope.roles = serviceAcessoCtrl.roles( permissoes );
            $scope.permissoes = serviceAcessoCtrl.listarPermissoes( permissoes );

            $scope.user = {
                roles: []
            };

            if( $scope.pagina == 'visualizar' || $scope.pagina == 'editar' || $scope.pagina == 'copiar' )
            {            	
                var acesso_permissao =  _acesso_permissao;
                
                angular.forEach(acesso_permissao, function(itens)
                {
                    $scope.user.roles.push( itens.permissao_id );
                });

                if( $scope.pagina == 'visualizar' || $scope.pagina == 'copiar' )
            	{
            		$scope.exibir = false;
            	}
            	else if( $scope.pagina == 'editar' )
            	{
            		serviceAcessoCtrl.setPermissoesChecados( $scope.user.roles );
            		$scope.todos = serviceAcessoCtrl.verificaTodosPermissoesChecados();
            	}
            }
        };

		$scope.checkAll = function()
		{

			if($scope.todos == true)
			{
				angular.forEach($scope.roles, function( permissao )
				{
					if( permissao.id != '' && $scope.user.roles.indexOf(permissao.id) == -1 )
					{
						$scope.user.roles.push( permissao.id );
					}
				});
			}
			else
			{
				$scope.user.roles = [];
			}

			serviceAcessoCtrl.setPermissoesChecados( $scope.user.roles );
		};

		$scope.verificaClick = function()
		{
			serviceAcessoCtrl.setPermissoesChecados( $scope.user.roles );

			if( serviceAcessoCtrl.getPermissoesChecados().length == serviceAcessoCtrl.getItens() )
			{				
				$scope.todos = true;
			}else{
				$scope.todos = false;
			}
		};

		$rootScope.limparLista = function ()
        {
        	$scope.user.roles = [];
			$scope.todos = false;
			serviceAcessoCtrl.setPermissoesChecados( $scope.user.roles );
        }

        init();

    }]);

})();