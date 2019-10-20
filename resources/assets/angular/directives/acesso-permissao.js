(function(){

    'use strict';

    angular.module('app')
    .directive('acessoPermissao', function() {
        return {
            restrict: 'E',
            controller: 'acessoPermissaoCtrl',
            templateUrl: '/templates/acesso-permissao.html',
            scope: { pagina: '@' }
        };
    });
    
})();