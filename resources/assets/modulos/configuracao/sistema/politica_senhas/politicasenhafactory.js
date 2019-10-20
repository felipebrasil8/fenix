(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('PoliticaSenhaFactory', ['$http', function($http){

        var _atualizarSenha = function(param){                
            return $http.put("/configuracao/sistema/politica_senhas/"+param.id, param)        
        };

        return {
            atualizarSenha: _atualizarSenha
        };
    }]);
})();