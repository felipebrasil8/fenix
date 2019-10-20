(function(){

    'use strict';

    angular.module('app')
    .service('serviceAcaoCtrl', ['$uibModal', function($uibModal) 
    {
        this.modal = function ( funcionarios )
        {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'acaoModal.html',
                resolve: {
                    funcionarios: function () {
                      //return funcionarios;
                    }
                  },
                controller: function ($scope, $uibModalInstance, funcionarios) {
                    
                }
            });
        }

    }]);

})();