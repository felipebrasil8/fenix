(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('gatilhoFactory', ['$http', function($http){


        var _salveCategoria = function(){
            return $http.post("configuracao/ticket/gatilho/store", param)
        };

        var _atualizaGatilhos = function(param){
            return $http.post("/configuracao/ticket/gatilho/storeMultiplo", param);
        }

        return {
           
            cadastraCategoria: _salveCategoria,
            atualizaGatilhos: _atualizaGatilhos
            
        };

    }]);
    
})();