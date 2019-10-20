(function(){

    'use strict';

    angular.module('app')
    .controller('listarCargoCtrl', ['$scope', '$http', 'CargoFactory', '$cookies', '$uibModal', 'Upload', '$timeout', function($scope, $http, CargoFactory, $cookies, $uibModal, Upload, $timeout) {
        var $ctrl = this;

        //filtro
        var nome = "";        
        var ativo = _ativo;
        var departamentos = _departamentos;
        var funcionarios = _funcionarios;
        var filtro = _filtro;

        var init = function () {
            
            $scope.cargos = {};
            departamentos.unshift({id: '', nome: ''});
            $scope.departamentos = departamentos;
            funcionarios.unshift({id: '', nome: ''});
            $scope.funcionarios = funcionarios;

            /**
             * Objeto utilizado para manter controle do estado da visualização dos cargos.
             */
            $scope.filter = {
                coluna: 'nome',
                ordem: 'asc',
                pagina: 1,
                limite: 15,
                nome: '',
                email: '',
                ativo: "_ativo",
                reverso: true
            }

            /**
             * Objeto utilizado para definir o estado da paginação.
             */
            $scope.paginacao = {
                de: 0,
                ate: 0,
                total: 0,
                pagina: 0
            }        

            if( verificaCookies() == false ) {
                
                $scope.filter.nome = nome;                
                $scope.filter.ativo = ativo;
                
            }else{

                //Se filtro (NOME, GESTOR, DEPARTAMENTO) = vazio e ATIVO = true, deve ser carregado fechado   
                $scope.filtro = ( 
                    $cookies.get('rh_cargo_nome') == "" && 
                        $cookies.get('rh_cargo_gestor') == "" && 
                            $cookies.get('rh_cargo_departamento') == "" && 
                                $cookies.get('rh_cargo_ativo') == "true" ) ? false : true;                

                $scope.filter.nome         = $cookies.get('rh_cargo_nome');
                $scope.filter.funcionario  = $cookies.get('rh_cargo_gestor');
                $scope.filter.departamento = $cookies.get('rh_cargo_departamento');
                $scope.filter.ativo        = $cookies.get('rh_cargo_ativo');
                
                if ($scope.filter.ativo == "false"){
                    $scope.filter.ativo = "false";                      
                } else {                    
                    $scope.filter.ativo = "true";  
                }
            }

            $scope.pesquisaCargo($scope.filter);
        };

        var verificaCookies = function(){            
            
            if(typeof $cookies.get('rh_cargo_nome') == "undefined" && 
                typeof $cookies.get('rh_cargo_gestor') == "undefined" && 
                    typeof $cookies.get('rh_cargo_departamento') == "undefined" && 
                        typeof $cookies.get('rh_cargo_ativo') == "undefined"
                        ) 
            {
                return false;
            }

            return true;
        };

        $scope.defineFiltro = function (_column = '') {
            if (_column != $scope.filter.coluna) {
                $scope.filter.coluna = _column;
            }

            $scope.filter.ordem = $scope.filter.ordem == 'asc' ? 'desc' : 'asc';
            $scope.pesquisaCargo($scope.filter);
        }

        $scope.onGetPage = function (pageNumber) {//ok
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;                
                $scope.pesquisaCargo($scope.filter);
            }
        }

        $scope.pesquisaCargo = function(pesquisa) {

            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
            $scope.carregando = true;

            CargoFactory.pesquisaCargo(pesquisa)
            .then(function mySuccess(response) {

                // Define a lista de usuários que será exibida na view.
                $scope.lista = response.data.cargos.data;

                // Calcula alguns parametros da paginação.                
                var de = response.data.cargos.current_page == 1 ? 1 : 
                    response.data.cargos.total < pesquisa.limite ? 
                    ((response.data.cargos.current_page-1) * pesquisa.limite) + response.data.cargos.total : 
                    ((response.data.cargos.current_page - 1) * pesquisa.limite) + 1;

                var ate = pesquisa.limite == 0 ? response.data.cargos.total : 
                    response.data.cargos.total < pesquisa.limite ? 
                    response.data.cargos.total : 
                    response.data.cargos.current_page * pesquisa.limite;

                var totalDePaginas = response.data.cargos.total % pesquisa.limite === 0 ? 
                    response.data.cargos.total / pesquisa.limite : 
                    Math.floor(response.data.cargos.total / pesquisa.limite) + 1;

                // Define os parametros da paginação
                $scope.paginacao = {
                    total: response.data.cargos.total,
                    pagina: response.data.cargos.current_page,
                    de: de,
                    ate: ate,
                    limite: pesquisa.limite,
                    totalDePaginas: totalDePaginas
                };                

                // Define os parametros da pesquisa nos cookies.
                $cookies.put('rh_cargo_nome', pesquisa.nome);                
                $cookies.put('rh_cargo_gestor', pesquisa.funcionario.id);
                $cookies.put('rh_cargo_departamento', pesquisa.departamento.id);
                $cookies.put('rh_cargo_ativo', pesquisa.ativo);

            }, function(error, status){

                $scope.errors = error.data;

            }).finally(function() {

                $scope.disableButton = false;
                $scope.carregando = false;
            });
        };

         $scope.abrePesquisa = function() {

            if ($scope.filtro == true){
                $scope.filtro = false;
                $scope.filtrando = false;
            }else{
                $scope.filtro = true;
                $scope.filtrando = true;
            }

        }


        $scope.limpaPesquisa = function() {//ok
            $scope.filter.nome = "";            
            $scope.filter.ativo = "true";
            $scope.filter.funcionario = funcionarios[0];
            $scope.filter.departamento = departamentos[0];            

            $scope.pesquisaCargo($scope.filter);
        };

        $scope.setSelectDepartamento = function()
        {            
            if( $cookies.get('rh_cargo_departamento') != "null" )
            {
                var indexObj = 0;

                angular.forEach($scope.departamentos, function(value) {
                    if( value.id == $cookies.get('rh_cargo_departamento') )
                    {
                        indexObj = $scope.departamentos.indexOf( value )
                    }
                });

                $scope.filter.departamento = $scope.departamentos[indexObj];

            }else{
                $scope.filter.departamento = departamentos[0];
            }
        }

        $scope.setSelectFuncionario = function()
        {            
            if( $cookies.get('rh_cargo_gestor') != "null" )
            {
                var indexObj = 0;

                angular.forEach($scope.funcionarios, function(value) {
                    if( value.id == $cookies.get('rh_cargo_gestor') )
                    {
                        indexObj = $scope.funcionarios.indexOf( value )
                    }
                });

                $scope.filter.funcionario = $scope.funcionarios[indexObj];

            }else{
                $scope.filter.funcionario = funcionarios[0];
            }
        }
        

        //incializa controller
        init();
        
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();

