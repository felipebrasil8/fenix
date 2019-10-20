(function(){

    'use strict';

    angular.module('app')
    .service('serviceModalCampoAdicionalPrioridadeCtrl', ['$uibModal', 'CamposAdicionaisFactory', function($uibModal, CamposAdicionaisFactory) 
    {
        var global_id;
        var prioridade_alterado = false;//se houve cadastro
        this.modalAdd = function ( id )
        {
            global_id =id;
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'addCampoAdicionalPrioridadeModal.html',
                resolve: {
                    id: function ( ) {
                      return  id;
                    }
                },
                controller: function ($scope, $uibModalInstance, id) {

                    $scope.close = function(){
                        modalInstance.close();
                    }

                    $scope.cadastrar = function(param){

                        param.departamento_id = id;

                        CamposAdicionaisFactory.addCampoAdicionalPrioridade(param)
                        .then(function mySuccess(response) {
                            $scope.errors = false;
                            prioridade_alterado = true;
                            $scope.success = response.data.mensagem;
                            $scope.prioridade = {};
                            $scope.prioridade.cor = '#000000';
                            
                        }, function(error, status){
                            prioridade_alterado = false;
                            $scope.success = false;
                            $scope.errors = error.data.errors;
                        });
                    }
                }
            });

            modalInstance.result.finally(function(){
                if( prioridade_alterado ){
                    window.location = "/configuracao/ticket/campo_adicional/departamento/"+global_id;                    
                }
            });            
        }

        this.modalEditar = function ( $scope )
        {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'editarCampoAdicionalPrioridadeModal.html',
                scope: $scope,
                resolve: {},
                controller: function ($scope, $uibModalInstance) {

                    $scope.lista = [];
                    $scope.setExcluirCampoAdicionalPrioridade = function(id){
                        $scope.lista.push({
                            id: id                            
                        });
                    }

                    $scope.setForm = function(){

                        document.form_editar_prioridade.submit();
                    }

                    $scope.modalExcluirCampoAdicionalPrioridade = function(id){

                        angular.forEach($scope.lista, function(value, key) {

                            CamposAdicionaisFactory.excluirCampoAdicionalPrioridade(value.id)                        
                            .then(function mySuccess(response) {
                                //
                            }, function(error, status){                           
                                //
                            }).finally(function() {                                                      
                                //
                            });
                        });

                        $scope.setForm();
                    }

                    $scope.close = function(){
                        modalInstance.close();
                    }

                }

            });

            modalInstance.result.finally(function(){                
                    window.location = "/configuracao/ticket/campo_adicional/departamento/"+$scope.departamento_id;                    
                 
            });
            
        }

    }]);

})();