(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('UsuarioFactory', ['$http', function($http){

        var _cadastrarUsuario = function(param){
            return $http.post("/configuracao/usuario", param);
        };

        var _pesquisaUsuario = function(param){
            return $http.post("/configuracao/usuario/search", param)      
        };

        var _atualizarUsuario = function(param, id){                
            return $http.put("/configuracao/usuario/"+id, param)        
        };

        var _solicitarSenhaFunc = function(param){
            return $http.put("/configuracao/usuario/" + param.id + "/solicitarNovaSenha", param)
        };

        var _novaSenhaFunc = function(param){
            return $http.put("/configuracao/usuario/" + param.id + "/novaSenha", param)
        };

        return {
            cadastrarUsuario: _cadastrarUsuario,
            pesquisaUsuario: _pesquisaUsuario,
            atualizarUsuario: _atualizarUsuario,
            solicitarSenhaFunc: _solicitarSenhaFunc,
            novaSenhaFunc: _novaSenhaFunc
        };

    }]);
    
})();