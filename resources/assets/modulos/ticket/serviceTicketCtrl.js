(function(){
    
        'use strict';
    
        angular.module('app')
        .service('serviceTicketCtrl', ['$uibModal', 'CamposAdicionaisFactory', 'TicketFactory', '$window', function($uibModal, CamposAdicionaisFactory, TicketFactory, $window) 
        {
            var global_id;
            this.modal = function ( oldValue, $scope )
            {
                var modalInstance = $uibModal.open({
                    animation: true,
                    ariaLabelledBy: 'modal-title-bottom',
                    ariaDescribedBy: 'modal-body-bottom',
                    size: 'md',
                    templateUrl: 'confirmaAlterarDepartamento.html',
                    scope: $scope,
                    resolve: {},
                    controller: function ($scope, $uibModalInstance) {
    
                        $scope.confirmaModal = function(){

                            $scope.$parent.ticket.solicitante = {};
                            $scope.$parent.ticket.prioridade = {};
                            $scope.$parent.ticket.assunto = '';
                            $scope.$parent.ticket.categoria = {};
                            $scope.$parent.ticket.subcategoria = {};
                            $scope.$parent.ticket.descricao = '';      
                            $scope.$parent.subcategoria = '';                      

                            $scope.$parent.mostraSolicitante();
                            $scope.$parent.mostraPrioridade();
                            $scope.$parent.mostraCategoria();
                            $scope.$parent.mostraCamposAdicionais();

                            modalInstance.close();
                        }
                            
                        $scope.cancelarModal = function () {

                            var index = $scope.$parent.departamentos.map(function (item) {
                                return item.id;
                            }).indexOf(oldValue.id);                            

                            $scope.$parent.ticket.departamento = $scope.$parent.departamentos[index];                            
                            
                            modalInstance.close();
                        }
                        
                        modalInstance.result.finally(function(){  });
                    }
                });

            }


            this.modalImagem = function( dadosImagemTicket, $scope ){

                var modalInstance = $uibModal.open({
                    animation: true,
                    ariaLabelledBy: 'modal-title-bottom',
                    ariaDescribedBy: 'modal-body-bottom',
                    size: 'lg',
                    templateUrl: 'imagemModal.html',
                    scope: $scope,
                    resolve: {},
                    controller: function ($scope) {

                        $scope.id = null;
                        //$scope.imagem = dadosImagemTicket[0].imagem;
                        $scope.carregandoModalImagem = true;
                        $scope.descricao = dadosImagemTicket[0].descricao;
                        $scope.data = dadosImagemTicket[0].created_at;
                        $scope.usuario = dadosImagemTicket[0].nome;
                        $scope.id = dadosImagemTicket[0].id;
                        $scope.permissaoImagemTicketExcluir = dadosImagemTicket[0].permissaoImagemTicketExcluir;

                        TicketFactory.getImagemTicket( dadosImagemTicket[0].id )
                        .then(function mySuccess(response) {
                            $scope.imagem = response.data.imagem;
                            $scope.carregandoModalImagem = false;
                        });
                                                
                        var hash = moment().format('YYYYMMDDHHmmss');
                        $scope.nome_imagem = 'ticket_imagem_'+hash+'.jpeg';
                        
                        $scope.confirmaModal = function(){
                            //console.log();
                        }
                            
                        $scope.closeModal = function () {
                            modalInstance.close();
                        }                        

                        $scope.deletarImagem = function(){

                            var mensagem = "Confirma a exclusão da imagem ?";

                            var modalInstance = $uibModal.open({
                                animation: true,
                                ariaLabelledBy: 'modal-title-bottom',
                                ariaDescribedBy: 'modal-body-bottom',
                                size: 'md',
                                templateUrl: '/templates/modal-confirm.html',
                                scope: $scope,
                                controller: function ($scope) {
                                    
                                    $scope.mensagem = mensagem;
                                    $scope.errors = false;

                                    $scope.alterarStatus = function()
                                    {
                                        TicketFactory.destroyImagemTicket( $scope.id )
                                        .then(function mySuccess(response) {
                                            $window.location.reload();
                                        }, function(error){
                                            modalInstance.close();
                                        });
                                    }

                                    $scope.modalCancelarAlterarStatus = function () {
                                        modalInstance.close();
                                    }

                                }
                            });                            
                        }
                        
                        modalInstance.result.finally(function(){ });
                    }

                });

            }

        }]);

    })();