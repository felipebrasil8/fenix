(function(){

	'use strict';

	angular.module('app')
    .controller('categoriaCtrl', ['$scope', '$http', 'categoriaFactory' , 'serviceCategoriasCtrl', '$uibModal', '$sce', function($scope, $http, categoriaFactory, serviceCategoriasCtrl, $uibModal, $sce){
    	
        var departamentos = _departamentos;
        var departamento = _departamento;
        var categorias_filha = _categorias_filha; 
        var categorias_pai = _categorias_pai; 
        
        var init = function(){

            $scope.departamentos = departamentos;
            $scope.categorias_filha = categorias_filha;
            $scope.categorias_pai = categorias_pai;
          
            var indexObj = 0;

            if( departamento != false ){                
                angular.forEach($scope.departamentos, function(value) {
                    if( value.id == departamento )
                    {
                        indexObj = $scope.departamentos.indexOf( value );
                    }
                });
                   
                $scope.categoria_departamento =  $scope.departamentos[indexObj];
            }

        };        

       
        
        $scope.update = function() {
            document.departamento_categoria.action = "/configuracao/ticket/categoria/departamento/"+$scope.categoria_departamento.id;
            document.departamento_categoria.submit();    
           
        }


        $scope.store = function() {
            document.categoria.action = "/configuracao/ticket/categoria/store/";
            document.categoria.submit();    
           
        }



        $scope.modalCategoria = function(){
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'categoriaModal.html',
                resolve: {
                    categorias: function () {
                      //return funcionarios;
                    }
                  },
                controller: function ($scope, $uibModalInstance, serviceCategoriasCtrl) {
                    
                    
                    $scope.modalCancelarCategoria = function () {
                        modalInstance.close();
                    }


                }
            });
        }


        $scope.modalConfirmCategoria = function(elemento, entidade){

            $scope.elemento = elemento;
            var mensagem = "Confirma a exclusão da categoria "+ elemento.nome +" ?";

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
                            url : "/"+entidade+"/"+elemento.id
                        }).then(function mySuccess(response) {
                             

                            document.departamento_categoria.action = "/configuracao/ticket/categoria/departamento/"+$scope.categoria_departamento.id;
                            document.departamento_categoria.submit();                        
                            

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




        $scope.modalEditCategoria = function(elemento){

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'editarCategoriaModal.html',
                scope: $scope, 
                resolve: {
                    elemento: function () {
                      return elemento;
                    }
                  },
                controller: function ($scope, $uibModalInstance, elemento) {
                   
                    $scope.elemento = elemento;
                    $scope.modal_nome = elemento.nome;
                    $scope.modal_descricao = elemento.descricao;
                    $scope.modal_dicas = elemento.dicas;
                    $scope.categoria_id = elemento.id;
                    
                    $scope.permite_pai = !isNaN(parseInt(elemento.ticket_categoria_id));              
                     
                                       
                     $scope.categorias_pai_new = $scope.categorias_pai;
                    
                    var indexObj = 0;
                    if( $scope.permite_pai ){                
                       
                        angular.forEach($scope.categorias_pai, function(value) {
                            if( value.id == elemento.ticket_categoria_id )
                            {
                                indexObj = $scope.categorias_pai.indexOf( value );
                            }
                        });
                           
                        $scope.modal_pai =  $scope.categorias_pai[indexObj];
                    }
                   
                   var obj = 0;
                 
                    if( !$scope.permite_pai ){    
                        angular.forEach($scope.categorias_filha, function(value){
                            if(value.ticket_categoria_id == elemento.id){
                                obj = 1; 
                            }

                        });

                        if(obj == 0){
                            $scope.permite_pai = true;
                        }
                    }
 


                    $scope.modalExcluirCategoria = function() {
                        document.categoria.action = "/configuracao/ticket/categoria/destroy/"+id;
                        document.categoria.submit();    
                    }

                    $scope.modalCancelarCategoria = function () {
                        modalInstance.close();
                    }


                }
            });
        }



        init();
    }]);
})();
