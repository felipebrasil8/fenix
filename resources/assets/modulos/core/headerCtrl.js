(function(){

    'use strict';

    angular.module('app')
    .controller( 'headerCtrl', 
        [
            '$scope', '$rootScope', '$http', 'HeaderFactory', '$uibModal', '$filter', '$interval', 'serviceModalAniversarioCtrl', 'serviceModalNotificacaoCtrl', 'serviceHeaderHomeCtrl', 'webNotification', '$window', '$cookies', '$location', 
        function($scope, $rootScope, $http, HeaderFactory, $uibModal, $filter, $interval, serviceModalAniversarioCtrl, serviceModalNotificacaoCtrl, serviceHeaderHomeCtrl, webNotification, $window, $cookies, $location) {

        var auth = '';

        var politicaSenha = _politicaSenha;
        var strPoliticaSenha = '';

        var tempoAtualizarNotificacao = _notificacao_tempo;
        var atualizarNotificacao = _notificacao_can;

        var init = function ()
        {   
            $scope.menu_aberto = 'true';
            getStatusCookies();
            verificaTelaExpandir();
            disparaTriggerMenu();
            auth = _auth;
            $rootScope.auth = auth;            
            $scope.token = auth.api_token;

            HeaderFactory.pegafuncionarios()
            .then(function (response) {

                $scope.funcionarios = serviceHeaderHomeCtrl.getFormataAniversariantes( response.data.funcionarios );
                $scope.funcionarios_niver = response.data.funcionarios_niver;

                angular.forEach($scope.funcionarios_niver, function(value) 
                {
                    value.dt_nascimento = $filter('date')(value.dt_nascimento, 'dd/MM');
                });

            });

            listarPoliticaSenha();
            if( atualizarNotificacao ){
                
                listarNotificacoes();
                $interval(listarNotificacoes, tempoAtualizarNotificacao*1000);
                
            }

            
        };

        /*
         * Notificações
         */
        var listarNotificacoes = function()
        {
            HeaderFactory.notificacoes()
            .then(function (response) {
                
                $scope.notificacoes = response.data.notificacoes;
                $scope.count_nao_lidas_int = response.data.notificaoes_nao_lidas;

                /*
                 * Seta notificações não lidas para ser usada na home
                 */
                serviceHeaderHomeCtrl.setNotificacoesNaoLidas( $scope.count_nao_lidas_int );

                aplicaEstilo( $scope.notificacoes );
                serviceHeaderHomeCtrl.setNotificacoes( $scope.notificacoes );

                $scope.count_nao_lidas_str = serviceHeaderHomeCtrl.getNotificacoesNaoLidasStr( );

                abreWebNotification( response.data.notificaoes_nao_notificadas );

                if ( $scope.count_nao_lidas_int == 0)
                    angular.element( document.querySelector( '#favicon' ) )[0].href = '/img/favicon.ico';
                else
                    angular.element( document.querySelector( '#favicon' ) )[0].href = '/img/favicon-notificacao.ico';
                
            }, function(response){

                // console.log(response);

                // Caso seja falha de login
                if (response.status == 422){
                    $scope.reload = function(){
                        location.href = '/login';
                    }
                    $scope.reload();
                }
             
            });
        }

        function abreWebNotification( notificacoes )
        {
            var count = 0;
            var limitWebNotification = 3; 

            angular.forEach(notificacoes, function(value) {
                if( count < limitWebNotification )
                {
                    webNotification.showNotification(value.titulo, {
                        body: value.mensagem,
                        icon: value.imagem,
                        onClick: function onNotificationClicked()
                        {
                            HeaderFactory.setNotificaoVisualizada( value )
                            .then(function (response)
                            {
                                $window.open(value.url+'#'+value.id, '_blank');
                            });
                            this.close();
                        },
                        autoClose: 15000
                    });

                    count++;
                } 
            });
        }

        function aplicaEstilo( notificacoes )
        {    
            angular.forEach(notificacoes, function(notificacao)
            {
                notificacao.color = '';
                notificacao.notificacao_azul = '';
                notificacao.text_light_blue = '';
                notificacao.text_muted = '';
                notificacao.icone_status = 'fa-circle';
                notificacao.title = '';

                if( notificacao.visualizada == false )
                {
                    notificacao.color = 'background-color: #edf2fa;';
                    notificacao.notificacao_azul = 'notificacao-azul';
                    notificacao.text_light_blue = 'text-light-blue';
                    notificacao.title = 'Marcar como lida';
                }
                else
                {
                    notificacao.text_muted = 'text-muted';
                    notificacao.icone_status = 'fa-circle-o';
                    notificacao.title = 'Marcar como não lida';
                }
            });
        }

        $scope.marcarNotificacaoLida = function( notificacao )
        {
            HeaderFactory.setNotificaoVisualizada( notificacao )
            .then(function (response)
            {
                if( response.data.status == true )
                {
                    listarNotificacoes();
                }                
            });
        };

        $scope.abrirNotificacao = function( notificacao )
        {
            if( notificacao.visualizada == false )
            {
                $scope.marcarNotificacaoLida( notificacao );
            }
        };

        $scope.modalTodasNotificacoes = function()
        {
            serviceModalNotificacaoCtrl.modal( $scope );
        };

        /*
         * End Notificação
         */

        var listarPoliticaSenha = function(){
            angular.forEach(politicaSenha, function(value) 
            {
                strPoliticaSenha = strPoliticaSenha+'<span style="text-align: left;">- '+value+"</span><br>";
            });
            $scope.politicaSenha = strPoliticaSenha;
        };

        $scope.abreModalParaTrocarSenha = function ( politicaSenha ) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: 'trocarSenhaModal.html',
                controller: function ($scope) {
                    $scope.modal = {
                        id: id,
                        oldPassword: '',
                        newPassword: '',
                        newPasswordConfirmation: '',
                        sucesso: false,
                        erro: false
                    };

                    $scope.politicaSenha = politicaSenha;

                    $scope.modalConfirmarTrocaDeSenha = function () {
                        HeaderFactory.mudarSenhaFunc($scope.modal)
                        .then(function (response) {
                            $scope.success = response.data.success;
                            $scope.modal = {
                                oldPassword: '',
                                newPassword: '',
                                newPasswordConfirmation: '',
                                sucesso: true,
                                erro: false
                            };

                        }, function (error, status) {
                            $scope.modal.erro = true;
                            $scope.modal.sucesso = false;

                            if (Object.prototype.toString.call(error.data) === '[object Array]') {
                                var errors = [];
                                angular.forEach(error.data, function(value) 
                                {
                                    errors.push(value);
                                });   
                            } else {
                                errors = error.data;
                            }
                            
                            $scope.errors = errors;
                        }).finally(function () {
                        });
                    }

                    $scope.modalCancelarTrocaDeSenha = function () {
                        modalInstance.close();
                    }

                    $scope.close = function(){
                        modalInstance.close();
                    }

                    $scope.isShowBtnSalvar = function(){
                        return ( 
                            $scope.modal.oldPassword != '' && 
                            $scope.modal.newPassword != '' && 
                            $scope.modal.newPasswordConfirmation != '' 
                        );
                    }
                }
            });
        }

        $scope.atualizarVisualizarSenha = function()
        {
            auth.visualizado_senha_alterada = true;

            HeaderFactory.atualizarVisualizarSenha( auth )
            .then(function mySuccess(response) {
            }).finally(function() {
            });
        };

        $scope.modalTodosAniversarios = function()
        {
            serviceModalAniversarioCtrl.modal( $scope.funcionarios );
        };

        $scope.abreModalParaCopiarToken = function (  ) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: 'copiarTokenModal.html',
                scope: $scope,
                controller: function ($scope) {
                    
                    $scope.close = function(){
                        modalInstance.close();
                    }
                    
                    $scope.modalCopiarToken = function(){
                        var name = $scope.token;                        
                        var copyElement = document.createElement("textarea");
                        copyElement.style.position = 'fixed';
                        copyElement.style.opacity = '0';
                        copyElement.textContent = decodeURI(name);
                        var body = document.getElementsByTagName('body')[0];
                        body.appendChild(copyElement);
                        copyElement.select();
                        document.execCommand('copy');
                        body.removeChild(copyElement);                        
                        $scope.success = 'Token copiado com sucesso.'; 
                    }
                    
                    $scope.modalCopiarUrl = function(){
                        var host = $location.protocol() + '://' + location.host;
                        var absUrl = $location.absUrl();
                        var redirect = '?redirect=' + absUrl.replace(host,'');
                        var api_token = '&api_token='+$scope.token;
                        var valorCopiar = host + '/token/' + redirect + api_token;
                        var copyElement = document.createElement("textarea");
                        copyElement.style.position = 'fixed';
                        copyElement.style.opacity = '0';
                        copyElement.textContent = decodeURI(valorCopiar);
                        var body = document.getElementsByTagName('body')[0];
                        body.appendChild(copyElement);
                        copyElement.select();
                        document.execCommand('copy');
                        body.removeChild(copyElement);
                        $scope.success = 'URL copiada com sucesso.';
                    }
                }
            });
        }

        //menu
        var disparaTriggerMenu = function(){

            if( ''+getStatusCookies()+'' != ''+getStatusAtualMenu()+'' ){
                $scope.menu_aberto = !$scope.menu_aberto;
                $cookies.put('menu_aberto', ''+$scope.menu_aberto+'');            
                angular.element('#sidebar_menu').trigger('click');
                document.getElementById('buscaMenuEsconde').style.cssText = $scope.menu_aberto ? 'display:inherit':'display:none';

            }
        }

        var getStatusCookies = function(){

            return  $cookies.get('menu_aberto') == 'false' ? false : true;
        }

        var getStatusAtualMenu = function(){

            return ''+$scope.menu_aberto+'';
        }
        
        $scope.setStatusAtualMenu = function(){

            $cookies.put('menu_aberto', ''+!$scope.menu_aberto+'');            
            $scope.menu_aberto = !$scope.menu_aberto;   
            
            document.getElementById('buscaMenuEsconde').style.cssText = $scope.menu_aberto ? 'display:inherit':'display:none';

        }

        var verificaTelaExpandir = function(){
            if($cookies.get('tela_expandir') == 'true'){
                $scope.expandirPagina();
            }
        }

        $scope.expandirPagina = function() {
            $cookies.put('tela_expandir', 'true');            
            document.getElementById('main-header').style.cssText = 'display:none';
            document.getElementById('main-sidebar').style.cssText = 'display:none';
            document.getElementById('content-wrapper').style.cssText = 'margin-left: 0px  !important; height: 110vh  !important; padding-top: 0px !important;';
            document.getElementById('main-footer').style.cssText = 'display: none;';
            document.getElementById('main-header').style.cssText = 'display:none';


        }

        $scope.voltaPagina = function() {
            $cookies.put('tela_expandir', 'false');   
            document.getElementById('main-header').style.cssText = 'display:block';
            document.getElementById('main-sidebar').style.cssText = 'display:block';
            document.getElementById('content-wrapper').style.cssText = 'margin-left: 230px; min-height: 920px  !important; padding-top: 50px !important;';
            document.getElementById('main-footer').style.cssText = 'display:block';
            document.getElementById('main-header').style.cssText = 'display:block';

        }




        //incializa controller
        init();

    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);

})();
