(function(){

	'use strict';

	angular.module('app')
    .controller('editarParametroCtrl', ['$scope', '$http', 'ParametroFactory', function($scope, $http, ParametroFactory){

		var parametro = _parametro;
		var grupos = _grupos;
		var tipos = _tipos;
		var grupo_selected = _grupo_selected;
		var tipo_selected = _tipo_selected;		

		var init = function(){

			$scope.parametro = {};
			$scope.grupos = grupos;	
			$scope.tipos = tipos;	
			$scope.grupo_selected = grupo_selected;
			$scope.tipo_selected = tipo_selected;
			listarparametro();
    	};	

    	var listarparametro = function(){    		

    		if( parametro.valor_booleano != null )
    		{
    			if( parametro.valor_booleano == true )
    			{
    				parametro.valor = 'VERDADEIRO'	
    			}
    			else
    			{
    				parametro.valor = 'FALSO';
    			}
    		}
    		else if( parametro.valor_texto != null )
    		{
    			parametro.valor = parametro.valor_texto;
    		}
    		else if( parametro.valor_numero != null )
    		{
    			parametro.valor = parametro.valor_numero;
    		}

    		$scope.visualizar_editar = parametro.editar;
    		parametro.editar = ''+parametro.editar;

    		$scope.parametro = parametro;
			$scope.parametro.grupo = $scope.grupo_selected[0];
			$scope.parametro.tipo = $scope.tipo_selected[0];			
    	};

		$scope.atualizar = function(parametro){
			 
            parametro.nome = parametro.nome.toUpperCase();

			$scope.form.$valid = false;   
			$scope.success = false;
        	$scope.errors = false;

			ParametroFactory.atualizarParametro(parametro)
	        .then(function mySuccess(response) {    

     	        $scope.success = response.data.mensagem;				        	
                                                        
            }, function(error){
          
	        	$scope.errors = error.data;

            });

		};

		$scope.keyPress = function ( event )
        {
            var keys = {
                'backspace': 8, 'del': 46,
                '0': 48, '1': 49, '2': 50, '3': 51, '4': 52, '5': 53, '6': 54, '7': 55, '8': 56, '9': 57
            };

            for (var index in keys)
            {
                if (!keys.hasOwnProperty(index)) continue;
                if (event.charCode == keys[index] || event.keyCode == keys[index])
                {
                    return;
                }
            }

            event.preventDefault();
        }

    	//inicia o controller
    	init();

    }]);
    
})();