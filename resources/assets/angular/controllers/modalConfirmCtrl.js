(function(){            

    'use strict';

    angular.module('app')
    .controller('modalConfirmCtrl', ['$scope', '$http', '$uibModal', function($scope, $http, $uibModal){

        var _elemento = "";
        var _lista = "";
        var _entidade = "";
        var _index = "";

        $scope.modalConfirm = function(elemento, lista, entidade){

            _elemento = elemento;
            _lista = lista;
            _entidade = entidade;
            $scope.elemento = elemento;
            var mensagem = "Confirma a alteração de status de "+ _elemento.nome +" ?";

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
                            url : "/"+_entidade+"/"+_elemento.id
                        }).then(function mySuccess(response) {

                            _index = $scope.lista.indexOf(_elemento);
                            $scope.lista[_index].ativo = !$scope.lista[_index].ativo;
                            
                            modalInstance.close();                            
                            
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
    }]);
})(); 