(function(){

    'use strict';

    angular.module('app')
    .service('serviceExcluirCamposAdicionaisCtrl', ['$uibModal', 'CamposAdicionaisFactory', function($uibModal, CamposAdicionaisFactory) 
    {
        var global_id;
        this.modal = function ( id, $scope )
        {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'excluirCamposAdicionaisModal.html',
                scope: $scope,
                resolve: {
                    id: function ( ) {
                      return  id;
                    }
                },
                controller: function ($scope, $uibModalInstance, id) {

                    var id = id;

                    $scope.alterarStatus = function(departamento_id){

                        global_id=departamento_id;                        

                        CamposAdicionaisFactory.excluirCampoAdicional(id)
                        .then(function mySuccess(response) {
                            //
                        }, function(error, status){
                            //
                        }).finally(function() {
                            window.location = "/configuracao/ticket/campo_adicional/departamento/"+$scope.departamento_id;
                            modalInstance.close();                           
                        });
                    }

                    $scope.modalCancelarAlterarStatus = function () {
                        modalInstance.close();
                    }
                }
            });
        }
    }]);
})();