(function(){

	'use strict';

	angular.module('app')

	.controller('editarPoliticaSenhaCtrl', ['$scope', 'PoliticaSenhaFactory', 'serviceFuncionarioCtrl', '$filter', function($scope, PoliticaSenhaFactory, serviceFuncionarioCtrl, $filter) {
        
		$scope.editarSenha = function()
		{          
        	$scope.senha = senha;
        };

        $scope.atualizar = function(senha)
        {  
        	$scope.success = false;
        	$scope.errors = false;

        	PoliticaSenhaFactory.atualizarSenha(senha)
            .then(function mySuccess(response) {    

     	        $scope.success = response.data.mensagem;
     	        $scope.senha = response.data.senha;
     	
            }, function(error){
          
	        	$scope.errors = error.data;
            });
        };

        $scope.keyPress = function( event )
		{
		   	if( serviceFuncionarioCtrl.keyPress( event ) )
		   	{
		   		return;
		   	}
		   	else
		   	{
		   		event.preventDefault();
		   	}
		}

        $scope.editarSenha();

	}]);

})();