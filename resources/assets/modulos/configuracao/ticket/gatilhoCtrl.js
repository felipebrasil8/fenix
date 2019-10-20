 (function(){

	'use strict';

	angular.module('app')
    .controller('gatilhoCtrl', ['$scope', '$http', 'gatilhoFactory' , 'serviceGatilhoCtrl', '$uibModal', '$sce', function($scope, $http, gatilhoFactory, serviceGatilhoCtrl, $uibModal, $sce){
    	
        var departamentos = _departamentos;
        var departamento = _departamento;
        var gatilhos = _gatilhos;
        
        var responsaveis = _responsaveis 
        var cargos = _cargos 
        var usuarios = _usuarios
        var statuses = _status 
        var gatilhos = _gatilhos


        var init = function(){

            $scope.departamentos = departamentos;
            $scope.gatilhos = gatilhos;
           
            var indexObj = 0;

            if( departamento != false ){                
                angular.forEach($scope.departamentos, function(value) {
                    if( value.id == departamento )
                    {
                        indexObj = $scope.departamentos.indexOf( value );
                    }
                });
                   
                $scope.gatilho_departamento =  $scope.departamentos[indexObj];
            }

            $scope.models = {
                selected: null,
                list: {}
            };

            $scope.models.list = $scope.gatilhos;
            $scope.list = $scope.models.list;

        };        

        $scope.atualizaGatilhos = function(){
            
            gatilhoFactory.atualizaGatilhos( $scope.models.list )
            .then(function mySuccess(response)
            {
                //sucesso
            }, function(error){
                $window.location.reload();
            });
        }       
        
        $scope.update = function() {
            document.departamento_gatilho.action = "/configuracao/ticket/gatilho/departamento/"+$scope.gatilho_departamento.id;
            document.departamento_gatilho.submit();    
           
        }


        $scope.store = function() {
            document.gatilho.action = "/configuracao/ticket/gatilho/store/";
            document.gatilho.submit();    
        }


        $scope.modalGatilho = function( tipo, elemento ){
            $scope.url =  '/configuracao/ticket/gatilho/store';
            $scope.putedit = false;
            
            
            $scope.funcao = 'false';
            $scope.teste = 'false';
            
            $scope.nomeGatilho = '';
            $scope.decricaoGatilho = '';     
            $scope.statuscombo = '';
            $scope.responsavelcombo = '';
            $scope.solicitantenotificacao = 'false';
            $scope.responsavelnotificacao = 'false';
            $scope.datateste = '';
            $scope.acaodataaltera = '';


          $scope.responsaveis = responsaveis;
          $scope.statuses = statuses;

           if ( tipo == 'editar'){
                $scope.putedit = 'put';
                $scope.url = '/configuracao/ticket/gatilho/update/'+elemento.id;
                $scope.elemento = elemento;

                $scope.nomeGatilho = elemento.nome;
                $scope.decricaoGatilho = elemento.descricao;

                $scope.quando = JSON.parse(elemento.quanto_executar);
                
                if (  $scope.quando.status != undefined ) {
                    $scope.teste = 'status';
                      var indexObj = 0;
               
                    angular.forEach($scope.statuses, function(value) {
                        if( value.id ==  $scope.quando.status )
                        {
                            indexObj = $scope.statuses.indexOf( value );
                        }
                    });
                       
                    $scope.statuscombo =  $scope.statuses[indexObj];

                } else if (  $scope.quando.dt_notificacao != undefined ) {
                    $scope.teste = 'not';
                   

                } else if (  $scope.quando.responsavel != undefined ) {
                    $scope.teste = 'res';
                    var indexObj = 0;
               
                    angular.forEach($scope.responsaveis, function(value) {
                        if( value.id == $scope.quando.responsavel )
                        {
                            indexObj = $scope.responsaveis.indexOf( value );
                        }
                    });
                       
                    $scope.responsavelcombo =  $scope.responsaveis[indexObj];
                }



                $scope.acao = JSON.parse(elemento.acao);

               
                if (  $scope.acao.notificacao != undefined &&  $scope.quando.dt_notificacao == undefined ) {
                     $scope.funcao = 'notif';

                        $scope.mensagemacao = $scope.acao.notificacao.mensagem;

                     if ( $scope.acao.notificacao.solicitante != false) {
                        $scope.solicitantenotificacao = true;

                     } 
                     if ( $scope.acao.notificacao.responsavel != false) {
                        $scope.responsavelnotificacao = true;

                     } 
                     if ( $scope.acao.notificacao.departamento != false) {
                        $scope.departamentonotificacao = true;
                   
                        var temporario = [];

                        angular.forEach( $scope.acao.notificacao.departamento, function(value){
                            
                            temporario.push(''+value+'');
                           
                        });
                        
                        $scope.deplist = temporario;
                        


                     } 
                     if ( $scope.acao.notificacao.cargo != false) {
                        $scope.cargonotificacao = true;
                        var temporario = [];

                        angular.forEach( $scope.acao.notificacao.cargo, function(value){
                            
                            temporario.push(''+value+'');
                           
                        });
                        
                        $scope.carglist = temporario;

                     } 
                   
                     if ( $scope.acao.notificacao.usuario != false) {
                        $scope.usuarionotificacao = true;
                          var temporario = [];

                        angular.forEach( $scope.acao.notificacao.usuario, function(value){
                            
                            temporario.push(''+value+'');
                           
                        });
                        
                        $scope.usualist = temporario;
                     }
                        

                } else if (  $scope.acao.responsavel != undefined ) {
                     $scope.funcao = 'respon'; 
                       var indexObj = 0;
               
                    angular.forEach($scope.responsaveis, function(value) {
                        if( value.id == $scope.acao.responsavel )
                        {
                            indexObj = $scope.responsaveis.indexOf( value );
                        }
                    });
                       
                    $scope.responsavelacaocombo =  $scope.responsaveis[indexObj];

                } else if ( $scope.quando.dt_notificacao != undefined ){
                    $scope.funcao = 'false';
                    
                    $scope.notmesagem = $scope.acao.notificacao.mensagem;

                    
                } 
                else {
                    $scope.funcao = 'data';
                    
                    if($scope.acao.dt_fechamento != undefined){
                        $scope.datateste = 'dt_fechamento';
                        $scope.acaodataaltera = $scope.acao.dt_fechamento;

                    }else if($scope.acao.dt_notificacao != undefined){
                         $scope.datateste = 'dt_notificacao';
                          $scope.acaodataaltera = $scope.acao.dt_notificacao;

                    }else if($scope.acao.dt_previsao != undefined){
                        $scope.datateste = 'dt_previsao';
                      $scope.acaodataaltera = $scope.acao.dt_previsao;

                    }else if($scope.acao.dt_resolucao != undefined) {
                         $scope.datateste = 'dt_resolucao';
                      $scope.acaodataaltera =  $scope.acao.dt_resolucao;

                    }
                }
            }




            var modalInstance = $uibModal.open({
                 animation: true,
                 ariaLabelledBy: 'modal-title-bottom',
                 ariaDescribedBy: 'modal-body-bottom',
                 size: 'lg',
                 templateUrl: 'gatilhoModal.html',
                 scope: $scope,
                 resolve: {
                    elemento: function () {
                      return elemento;
                    }
                  },
            controller: function ($scope, $uibModalInstance,  elemento) {
                             
               
                $scope.excluir = false;
                   
                if( tipo == 'editar') {
                    $scope.excluir = true;
                }
         

               
                $scope.modalCancelarGatilho = function () {
                    modalInstance.close();
                }



                 $scope.modalExcluirCategoria = function() {
                        document.categoria.action = "/configuracao/ticket/categoria/destroy/"+id;
                        document.categoria.submit();    
                    }  







               }
             });
         }


        $scope.modalConfirmaGatilho = function(entidade){
           
            var mensagem = "Confirma a exclusão da gatilho "+$scope.elemento.nome+" ?";

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
                        $http({
                            method : "DELETE",
                            url : "/"+entidade+"/"+$scope.elemento.id
                        }).then(function mySuccess(response) {
                             
                            document.departamento_gatilho.action = "/configuracao/ticket/gatilho/departamento/"+$scope.gatilho_departamento.id;
                            document.departamento_gatilho.submit();                        

                        }, function(data, status, headers, config){
                            
                            $scope.errors = data.data.errors[0];
                        });       
                    }

                    $scope.modalCancelarAlterarStatus = function () {
                        modalInstance.close();
                    }
                }
            });
        };


        init();
    }]);
})();
