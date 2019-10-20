(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('categoriaFactory', ['$http', function($http){


        var _salveCategoria = function(){
            return $http.post("configuracao/ticket/categoria/store", param)
        };


        return {                    
           
            cadastraCategoria: _salveCategoria
            
        };

    }]);
    
})();