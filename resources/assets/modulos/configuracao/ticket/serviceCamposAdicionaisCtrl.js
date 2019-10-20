(function(){

    'use strict';

    angular.module('app')
    .service('serviceCamposAdicionaisCtrl', ['$uibModal', 'CamposAdicionaisFactory', function($uibModal, CamposAdicionaisFactory) 
    {
        this.modalAdd = function ( tipo, $scope )
        {            

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'addCamposAdicionaisModal.html',
                scope: $scope,
                resolve: {
                    id: function () {
                      return null;
                    }
                  },
                controller: function ($scope, $uibModalInstance, id) {
                    
                    if( tipo == 'lista' ){                        

                        $scope.mostrar_lista = true;
                        $scope.tipo = 'LISTA';

                    }else if( tipo == 'longo' ){                        

                        $scope.longo = true;
                        $scope.tipo = 'TEXTO LONGO';

                    }else if( tipo == 'texto' ){                        

                        $scope.texto = true;
                        $scope.tipo = 'TEXTO';

                    }

                    $scope.lista = [{
                        id: 1,
                        padrao: false
                    }];

                    $scope.lista_input = JSON.stringify( $scope.lista );
  
                    $scope.atualiza = function( id ) {

                        angular.forEach($scope.lista, function(value, key) {

                            if( (key+1) == id ){

                                value.padrao = "true";
                            }else{
                                value.padrao = "false";
                            }
                    
                        });                        

                        $scope.lista_input = JSON.stringify( $scope.lista );
                        
                    };

                    $scope.removeItem = function() {
                        var lastItem = $scope.lista.length-1;
                        $scope.lista.splice(lastItem);
                        $scope.lista_input = JSON.stringify( $scope.lista );
                    };

                    $scope.addItem = function(){
                        var newItemNo = $scope.lista.length+1;
                        $scope.lista.push({
                            'id':newItemNo,
                            'padrao': false
                        });
                        $scope.lista_input = JSON.stringify( $scope.lista );
                    }
                    
                    $scope.close = function(){
                        modalInstance.close();
                    }
                    
                }
            });

            modalInstance.opened.then(function() {
                $scope.tipo = true;
            }); 
        }

        //editar
        this.modalEdit = function ( id, $scope )
        {            

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'editCamposAdicionaisModal.html',
                scope: $scope,
                resolve: {
                    id: function () {
                      return id;
                    }
                  },
                controller: function ($scope, $uibModalInstance, id) {                    
                    
                    $scope.campo = {};
                    
                    CamposAdicionaisFactory.getCampoAdicional( id )
                    .then(function mySuccess(response) {

                        $scope.campo.departamento_id = response.data[0].departamento_id;                        
                        $scope.campo.campo_id = response.data[0].id;                        
                        $scope.campo.tipo = response.data[0].tipo;                        
                        $scope.campo.nome = response.data[0].nome;
                        $scope.campo.descricao = response.data[0].descricao;
                        $scope.campo.obrigatorio = ''+response.data[0].obrigatorio+'';
                        $scope.campo.visivel = ''+response.data[0].visivel+'';

                        if( response.data[0].tipo == 'LISTA' ){

                            $scope.mostrar_lista_edit = true;                           
                            
                            $scope.campo.lista=JSON.parse(response.data[0].dados);

                            $scope.lista_input = JSON.stringify( $scope.campo.lista );

                            angular.forEach($scope.campo.lista, function(value, key) {
                                
                                if( value.padrao == true ){
                                    value.padrao = 'true';
                                }else if( value.padrao == false ){
                                    value.padrao = 'false';
                                }
                        
                            });                            

                        }else if( response.data[0].tipo == 'TEXTO LONGO' ){

                            $scope.longo_edit = true;
                            $scope.campo.padrao = response.data[0].padrao;                            
                            

                        }else if( response.data[0].tipo == 'TEXTO' ){

                            $scope.texto_edit = true;
                            $scope.campo.padrao = response.data[0].padrao;                        

                        }
 

                    }, function(error, status){
                        window.location = "/configuracao/ticket/campo_adicional/departamento/"+$scope.departamento_id;                        
                    }).finally(function() {
                        //                       
                    });   

                    $scope.removeItem = function() {
                        var lastItem = $scope.campo.lista.length-1;
                        $scope.campo.lista.splice(lastItem);
                        $scope.lista_input = JSON.stringify( $scope.campo.lista );
                    };

                    $scope.addItem = function(){
                        var newItemNo = $scope.campo.lista.length+1;
                        $scope.campo.lista.push({
                            'id':newItemNo,
                            'padrao':  "false"
                        });
                        $scope.lista_input = JSON.stringify( $scope.campo.lista );
                    }
                    
                    $scope.close = function(){
                        modalInstance.close();
                    }

                    $scope.atualiza = function(id){

                        angular.forEach($scope.campo.lista, function(value, key) {

                            if( (key+1) == id ){

                                value.padrao = "true";
                            }else{
                                value.padrao = "false";
                            }
                    
                        });

                        $scope.lista_input = JSON.stringify( $scope.campo.lista );                        
                    }
                }
            });
            
        }

    }]);

})();