(function(){

    'use strict';

    angular.module('app')
    .service('serviceModalNotificacaoCtrl', ['$uibModal', 'HeaderFactory', 'serviceHeaderHomeCtrl', '$window', function($uibModal, HeaderFactory, serviceHeaderHomeCtrl, $window) 
    {
        this.modal = function ( $scope )
        {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'sm',
                templateUrl: 'todasNotificacoesModal.html',
                resolve: { },
                scope: $scope,
                controller: function ($scope, $uibModalInstance, $interval)
                {
                    var time;

                    // Atualiza a lista de notificacoes
                    var listarNotificacoes = function()
                    {
                        if( $scope.$parent )
                        {
                            $scope.notificacoes = $scope.$parent.notificacoes;
                            $scope.count_nao_lidas_int = serviceHeaderHomeCtrl.getNotificacoesNaoLidas();
                            $scope.count_nao_lidas_str = serviceHeaderHomeCtrl.getNotificacoesNaoLidasStr();
                        }
                        else
                        {
                            $interval.cancel(time);
                        }
                    }

                    listarNotificacoes();
                    time = $interval(listarNotificacoes, 1000);

                    $scope.marcarNotificacaoLida = function( notificacao )
                    {

                        HeaderFactory.setNotificaoVisualizada( notificacao )
                        .then(function (response)
                        {
                            if( response.data.status == true )
                            {
                                for (var i = 0; i < $scope.$parent.notificacoes.length; i++) {
                                    if ($scope.$parent.notificacoes[i] == notificacao){
                                        $scope.$parent.notificacoes[i].visualizada = !$scope.$parent.notificacoes[i].visualizada;
                                    }
                                }
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

                    $scope.modalFecharTodasNotificacoes = function () {
                        modalInstance.close();
                    }
                }
            });
        }

    }]);

})();