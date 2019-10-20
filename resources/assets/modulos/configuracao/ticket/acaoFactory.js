(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('acaoFactory', ['$http', function($http)
    {
        var _salveCategoria = function()
        {
            return $http.post("configuracao/ticket/acao/store", param)
        };       

        var _atualizaAcoes = function(param){
            return $http.post("/configuracao/ticket/acao/storeMultiplo", param);
        }        

        return {                    

            cadastraCategoria: _salveCategoria,
            atualizaAcoes: _atualizaAcoes
            
        };

    }]);
    
})();