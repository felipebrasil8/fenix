(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('PerfilFactory', ['$http', function($http){

        var _cadastrarPerfil = function(param){
            return $http.post("/configuracao/perfil", param);
        };

        var _pesquisaPerfil = function(param){
            return $http.post("/configuracao/perfil/search", param)      
        };

        var _atualizarPerfil = function(param){                
            return $http.put("/configuracao/perfil/"+param.id, param)        
        };

        var _copiarPerfil = function(param){
            return $http.post("/configuracao/perfil", param);
        };

        return {
            cadastrarPerfil: _cadastrarPerfil,
            pesquisaPerfil: _pesquisaPerfil,
            atualizarPerfil: _atualizarPerfil,
            copiarPerfil: _copiarPerfil
        };
    }]);
})();