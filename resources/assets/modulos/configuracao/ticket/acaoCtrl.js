(function(){

	'use strict';

	angular.module('app')
    .controller('acaoCtrl', ['$scope', '$http', 'acaoFactory' , 'serviceAcaoCtrl', '$uibModal', '$sce', function($scope, $http, acaoFactory, serviceAcaoCtrl, $uibModal, $sce){
    	
        var departamentos = _departamentos;
        var departamento = _departamento;
        var icones = _icones;
        var status = _status;
        var acoes = _acoes;
        
        var init = function()
        {
            $scope.departamentos = departamentos;
            $scope.icones = icones;
            $scope.status = status;
            $scope.acoes = acoes;

            $scope.campos = [
                {value: 'assunto', nome:'ASSUNTO'},
                {value: 'avaliacao', nome:'AVALIAÇÃO'},
                {value: 'campos_adicionais', nome:'CAMPOS ADICIONAIS'},
                {value: 'categoria', nome:'CATEGORIA'},
                {value: 'data_notificacao', nome:'DATA NOTIFICAÇÃO'},
                {value: 'data_previsao', nome:'DATA PREVISÃO'},
                {value: 'prioridade', nome:'PRIORIDADE'},
                {value: 'responsavel', nome:'RESPONSÁVEL'},
                {value: 'solicitante', nome:'SOLICITANTE'}
            ];

            var indexObj = 0;

            if( departamento != false )
            {                
                angular.forEach($scope.departamentos, function(value) {
                    if( value.id == departamento )
                    {
                        indexObj = $scope.departamentos.indexOf( value );
                    }
                });
                   
                $scope.acao_departamento =  $scope.departamentos[indexObj];
            }
            
            /*
             * Drag and Drop
             * http://marceljuenemann.github.io/angular-drag-and-drop-lists/demo/#/simple
             */ 
            $scope.models = {
                selected: null,
                list: {}
            };

            $scope.models.list = $scope.acoes;
            $scope.list = $scope.models.list;
        };

        $scope.atualizaAcoes = function(){
            
            acaoFactory.atualizaAcoes( $scope.models.list )
            .then(function mySuccess(response)
            {
                //sucesso
            }, function(error){
                $window.location.reload();
            });
        }

        $scope.update = function()
        {
            document.departamento_acao.action = "/configuracao/ticket/acao/departamento/"+$scope.acao_departamento.id;
            document.departamento_acao.submit();
        }

        $scope.store = function() {
            document.acao.action = "/configuracao/ticket/acao/store/";
            document.acao.submit();
        }

        $scope.abrirModal = function ( tipo, acao )
        {
            $scope.modal_tipo = tipo;

            /**
             * Variaveis de inputs do modal
             */
            $scope.status_atual = {
                status: []
            };

            $scope.status_novo = {
                status: []
            };

            $scope.campos_adicionais = {
                campos: []
            };

            $scope.mensagem = {};

            $scope.nome = '';
            $scope.modal_icone = '';
            $scope.icone_view = '';
            $scope.modal_icone_view = '';
            $scope.descricao = '';
            $scope.solicitante_executa = false;
            $scope.responsavel_executa = false;
            $scope.trata_executa = false;

            if( tipo == 'cadastrar' )
            {
                $scope.modal_title = 'Adicionar ação';
                
                $scope.mensagem.tipo_mensagem = {
                    interacao : false,
                    nota_interna : false
                };

                $scope.modal_action = '/configuracao/ticket/acao/store';
            }
            else if( tipo == 'editar' )
            {
                $scope.modal_title = 'Editar ação';
                                    
                angular.forEach($scope.status, function( value )
                {
                    if( JSON.parse(acao.status_atual).indexOf( value.id ) != -1 )
                    {
                        $scope.status_atual.status.push( value );
                    }

                    if( JSON.parse(acao.status_novo).indexOf( value.id ) != -1 )
                    {
                        $scope.status_novo.status.push( value );
                    }
                });

                angular.forEach($scope.campos, function( value )
                {
                    if( acao.campos.indexOf( value.value ) != -1 )
                    {
                        $scope.campos_adicionais.campos.push( value );
                    }
                });

                $scope.acao_id = acao.id;
                $scope.nome = acao.nome;
                $scope.modal_icone = acao.icone;
                $scope.icone_view = acao.icone;
                $scope.modal_icone_view = acao.icone_nome;
                $scope.descricao = acao.descricao;
                $scope.solicitante_executa = acao.solicitante_executa;
                $scope.responsavel_executa = acao.responsavel_executa;
                $scope.trata_executa = acao.trata_executa; 

                $scope.mensagem.tipo_mensagem = {
                    interacao : acao.interacao,
                    nota_interna : acao.nota_interna
                };

                $scope.modal_action = '/configuracao/ticket/acao/update/'+$scope.acao_departamento.id;
            }

            $scope.modalAcao();
        }

        $scope.modalAcao = function(){
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                scope: $scope,
                templateUrl: 'acaoModal.html',
                resolve: {
                    
                },
                controller: function ($scope, $sce)
                {
                    $scope.trustAsHtml = function(string) {
                         return $sce.trustAsHtml(string);
                    };

                    $scope.setIcone = function ( $icone ){
                        $scope.icone_view = $icone.icone;
                        $scope.modal_icone_view = $icone.nome;
                        $scope.modal_icone = $icone.icone;
                    }

                    $scope.modalCancelarAcao = function (){
                        modalInstance.close();
                    }

                    $scope.modalConfirmAcao = function()
                    {
                        var modalInstanceConfirme = $uibModal.open({
                            animation: true,
                            ariaLabelledBy: 'modal-title-bottom',
                            ariaDescribedBy: 'modal-body-bottom',
                            size: 'lg',
                            scope: $scope,
                            templateUrl: '/templates/modal-confirm.html',
                            scope: $scope,
                            controller: function ($scope)
                            {
                                $scope.mensagem = "Confirma a exclusão da ação "+ $scope.nome +" ?";
                                $scope.errors = false;

                                $scope.alterarStatus = function()
                                {
                                    $http({
                                        method : "DELETE",
                                        url : "/configuracao/ticket/acao/destroy/"+$scope.acao_id,
                                    }).then(function mySuccess(response)
                                    {
                                        document.departamento_acao.submit();
                                    },
                                    function(data, status, headers, config)
                                    {  
                                        $scope.errors = data.data.errors[0];
                                    });       
                                }

                                $scope.modalCancelarAlterarStatus = function () {
                                    modalInstanceConfirme.close();
                                }
                            }
                        });
                    }
                }
            });
        }  

        init();
    }]);
})();




        
