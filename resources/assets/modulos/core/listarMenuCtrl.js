(function(){

    'use strict';

    angular.module('app')
    .controller('listarMenuCtrl', ['$scope', '$http', function($scope, $http)
    {    
        var menus = _menus;

        var init = function ()
        {               
            $scope.menus = menus;
        };  

        //incializa controller
        init();

    }]);

})();