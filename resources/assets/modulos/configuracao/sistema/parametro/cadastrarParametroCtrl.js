(function(){

	'use strict';

	angular.module('app')
    .controller('cadastrarParametroCtrl', ['$scope', '$http', 'ParametroFactory',function($scope, $http, ParametroFactory){

    	var grupos = _grupos;
    	var tipos = _tipos;

    	var init = function(){

    		$scope.parametro = {};
			listarGrupos();
			listarTipos();
    	};

    	var listarGrupos = function(){
			$scope.grupos = grupos;	
    	};

    	var listarTipos = function(){
			$scope.tipos = tipos;
    	};

    	$scope.cadastrar = function(parametro){

            
    		$scope.form.$valid = false;
    		$scope.success = false;
			$scope.errors = false;

			parametro.nome = parametro.nome.toUpperCase();

			ParametroFactory.cadastrarParametro(parametro)
		   .then(function (response) { 

        		$scope.success = response.data.mensagem;
        		limparCampos();

	        }, function(error){

	        	$scope.errors = error.data;

	        }).finally(function() {

		    	$scope.form.$valid = true;   

		    });
		};

		var limparCampos = function() {
			
			$scope.parametro = {};

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

		//inicializa controller
    	init();
    }]);
})();