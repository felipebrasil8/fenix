(function(){
	'use strict';

	angular.module('app')
	.controller('visualizarUsuarioCtrl', ['$scope', '$http', 'UsuarioFactory', function($scope, $http, UsuarioFactory){

		var usuario = _usuario;
		
		var init = function(){
			$scope.usuario = {};
			listarUsuarios();
	    };

	    var listarUsuarios = function(){
	    	$scope.usuario = usuario;
	    	$scope.usuario.created_at = usuario.created_at !== null ? new Date(usuario.created_at) : null;
	    	$scope.usuario.updated_at = usuario.updated_at !== null ? new Date(usuario.updated_at) : null;
        };  

	    //inicializa controller
	    init();

	}]);
})();