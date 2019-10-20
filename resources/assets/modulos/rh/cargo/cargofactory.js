(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('CargoFactory', ['$http', function($http){

        var _cadastrarCargo = function(param){
            return $http.post("/rh/cargo", param);
        };

        var _pesquisaCargo = function(param){
            return $http.post("/rh/cargo/search", param)      
        };

        var _atualizarCargo = function(param, id ){          
            return $http.put("/rh/cargo/"+id, param)        
        };

        return {
            cadastrarCargo: _cadastrarCargo,
            pesquisaCargo: _pesquisaCargo,
            atualizarCargo: _atualizarCargo
        };
    }]);
})();