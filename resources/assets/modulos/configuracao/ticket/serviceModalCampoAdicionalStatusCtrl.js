(function(){

    'use strict';

    angular.module('app')
    .service('serviceModalCampoAdicionalStatusCtrl', ['$uibModal', 'CamposAdicionaisFactory', function($uibModal, CamposAdicionaisFactory) 
    {   
        var global_id;//departamento_id  
        this.modalAdd = function ( id )
        {   
            global_id = id;          
            var status_alterado = false;//se houve cadastro

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'addCampoAdicionalStatusModal.html',
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
                        
                        CamposAdicionaisFactory.addCampoAdicionalStatus(param)
                        .then(function mySuccess(response) { 
                            status_alterado = true;                           
                            $scope.errors = false;
                            $scope.success = response.data.mensagem;
                            $scope.status = {};
                            $scope.status.cor = '#000000';

                        }, function(error, status){
                            status_alterado = false; 
                            $scope.success = false;
                            $scope.errors = error.data.errors;
                        });
                    }
                }
            });            

            modalInstance.result.finally(function(){
                if( status_alterado ){
                    window.location = "/configuracao/ticket/campo_adicional/departamento/"+global_id;
                }
            });            
        }

        this.modalEditar = function ( $scope, id )
        {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'editarCampoAdicionalStatusModal.html',
                scope: $scope,
                resolve: {
                    id: function ( ) {
                      return  id;
                    }
                },
                controller: function ($scope, $uibModalInstance, id) {

                    $scope.lista = [];
                    $scope.setExcluirCampoAdicionalStatus = function(id){
                        $scope.lista.push({
                            id: id                            
                        });
                    }

                    $scope.setForm = function(){

                        document.form_editar_status.submit();
                    }
                    
                    $scope.modalExcluirCampoAdicionalStatus = function(){

                        angular.forEach($scope.lista, function(value, key) {
                        
                            CamposAdicionaisFactory.excluirCampoAdicionalStatus(value.id)                        
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