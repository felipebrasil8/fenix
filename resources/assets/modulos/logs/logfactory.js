(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('LogFactory', ['$http', function($http){      

        var _pesquisaLog = function(param){           
            return $http.post("/log/acessos/search", param)            
        };
        
        return {            
            pesquisaLog: _pesquisaLog            
        };

    }]);
    
})();