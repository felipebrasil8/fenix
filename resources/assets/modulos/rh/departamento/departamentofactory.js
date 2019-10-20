(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('DepartamentoFactory', ['$http', function($http){

        var _cadastrarDepartamento = function(param){
            return $http.post("/rh/departamento", param);
        };

        var _pesquisaDepartamento = function(param){
            return $http.post("/rh/departamento/search", param)      
        };

        var _atualizarDepartamento = function(param){                
            return $http.put("/rh/departamento/"+param.id, param)        
        };

        return {
            cadastrarDep: _cadastrarDepartamento,
            pesquisaDep: _pesquisaDepartamento,
            atualizarDep: _atualizarDepartamento        };
    }]);
})();