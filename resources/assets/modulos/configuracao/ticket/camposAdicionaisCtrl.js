(function(){

	'use strict';

	angular.module('app')
    .controller('camposAdicionaisCtrl', ['$scope', '$http', 'CamposAdicionaisFactory', 'serviceCamposAdicionaisCtrl', '$uibModal', 'serviceExcluirCamposAdicionaisCtrl', '$sce', 'serviceModalCampoAdicionalPrioridadeCtrl', 'serviceModalCampoAdicionalStatusCtrl',
        function($scope, $http, CamposAdicionaisFactory, serviceCamposAdicionaisCtrl, $uibModal, serviceExcluirCamposAdicionaisCtrl, $sce, serviceModalCampoAdicionalPrioridadeCtrl, serviceModalCampoAdicionalStatusCtrl){
    	
        var departamentos = _departamentos;
        var departamentos_selected = _departamentos_selected;                

        var init = function(){

            $scope.campos_adicionais = [];
            $scope.campo_adicional = [];
            $scope.departamentos_selected = false;
            $scope.campos_adicionais_lista = [];            
            $scope.departamentos = _departamentos;
            $scope.departamentos_selected = _departamentos_selected;  
                
            $scope.campos = [];
            listar();            
        };        

        var listar = function(){
            if( departamentos_selected != false ){ 

                var campos_adicionais_lista=[];
                var campos_adicionais_texto=[];
                var campos_adicionais_longo=[];
                
                $scope.campo_adicional.departamento = departamentos_selected[0];                
                $scope.departamentos_selected = true;
                
                angular.forEach(campos_adicionais, function(value, key) {
                    if(value['template'] == 'LISTA'){

                        var elementos = JSON.parse( value.dados ); 

                        campos_adicionais_lista.push( {
                            'id':value.id
                            ,'nome': (value.nome.toLowerCase()).charAt(0).toUpperCase() + value.nome.toLowerCase().slice(1)
                            ,'descricao': value.descricao
                            ,'padrao':  elementos[value.padrao-1]
                            ,'dados': elementos
                        } );                        

                    }else if(value['template'] == 'TEXTO'){

                        campos_adicionais_texto.push( {
                            'id':value.id
                            ,'nome': (value.nome.toLowerCase()).charAt(0).toUpperCase() + value.nome.toLowerCase().slice(1)
                            ,'descricao': value.descricao
                            ,'padrao': value.padrao
                        } );

                    }else if(value['template'] == 'TEXTO LONGO'){

                        campos_adicionais_longo.push( {
                            'id':value.id
                            ,'nome': (value.nome.toLowerCase()).charAt(0).toUpperCase() + value.nome.toLowerCase().slice(1)
                            ,'descricao': value.descricao
                            ,'padrao': value.padrao
                        } );

                    }
                });

                $scope.campos_adicionais_lista = campos_adicionais_lista;
                $scope.campos_adicionais_texto = campos_adicionais_texto;
                $scope.campos_adicionais_longo = campos_adicionais_longo;                
            }
        };

        $scope.setSelect = function()
        {            
            var indexObj = 0;

            angular.forEach($scope.perfis, function(value) {
                if( value.id == $cookies.get('configuracao_usuario_perfil') )
                {
                    indexObj = $scope.perfis.indexOf( value )
                }
            });

            $scope.filter.perfil = $scope.perfis[indexObj];

            
        }

        //Campos Adiconais
        $scope.modalAddCamposAdicionais = function( tipo ) {
            serviceCamposAdicionaisCtrl.modalAdd( tipo, $scope );
        };             

        $scope.modalEditarCamposAdicionais = function(id){            
            serviceCamposAdicionaisCtrl.modalEdit( id, $scope );
        }

        $scope.modalExcluirCamposAdicionais = function(id) {
            serviceExcluirCamposAdicionaisCtrl.modal( id, $scope );
        }

        //Departamento
        $scope.update = function() {
            document.departamento.action = "/configuracao/ticket/campo_adicional/departamento/"+$scope.campo_adicional.departamento.id;            
            document.departamento.submit();
        }

        //Status        
        $scope.modalAddCampoAdicionalStatus = function(){
            serviceModalCampoAdicionalStatusCtrl.modalAdd( $scope.departamento_id );
        }

        $scope.modalEditarCampoAdicionalStatus = function(){
            serviceModalCampoAdicionalStatusCtrl.modalEditar( $scope );
        }

        //Prioridade
        $scope.modalAddCampoAdicionalPrioridade = function(){
            serviceModalCampoAdicionalPrioridadeCtrl.modalAdd( $scope.departamento_id );   
        }

        $scope.modalEditarCampoAdicionalPrioridade = function(){            
            serviceModalCampoAdicionalPrioridadeCtrl.modalEditar( $scope );
        }        


		//inicializa controller
    	init();
    }]);

})();

