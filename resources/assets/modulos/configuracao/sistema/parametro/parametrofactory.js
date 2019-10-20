(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('ParametroFactory', ['$http', function($http){

        var _cadastrarParametro = function(param){
            return $http.post("/configuracao/sistema/parametro", param);
        };

        var _pesquisaParametro = function(param){
            return $http.post("/configuracao/sistema/parametro/search", param)      
        };

        var _atualizarParametro = function(param){                
            return $http.put("/configuracao/sistema/parametro/"+param.id, param)        
        };

        return {
            cadastrarParametro: _cadastrarParametro,
            pesquisaParametro: _pesquisaParametro,
            atualizarParametro: _atualizarParametro
        };
    }]);
})();