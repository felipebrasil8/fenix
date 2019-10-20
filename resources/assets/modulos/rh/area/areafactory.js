(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('AreaFactory', ['$http', function($http){

        var _cadastrarArea = function(param){
            return $http.post("/rh/area", param);
        };

        var _pesquisaArea = function(param){
            return $http.post("/rh/area/search", param)      
        };

        var _atualizarArea = function(param){                
            return $http.put("/rh/area/"+param.id, param)        
        };

        return {
            cadastrarArea: _cadastrarArea,
            pesquisaArea: _pesquisaArea,
            atualizarArea: _atualizarArea
        };
    }]);
})();