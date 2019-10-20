(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('DashboardFactory', ['$http', function($http){

        var _getDashboardTicket = function( param ){
            return $http.post("/ticket/dashboard/"+ param.id, param)
        };


        return {
            getDashboardTicket: _getDashboardTicket,            
          
        };

    }]);
})();