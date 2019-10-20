


(function(){

    'use strict';

    angular.module('app')
    .controller('listarDepartamentoCtrl', ['$scope', '$http', 'DepartamentoFactory', '$cookies', '$uibModal', 'Upload', '$timeout', function($scope, $http, DepartamentoFactory, $cookies, $uibModal, Upload, $timeout) {
        var $ctrl = this;

        //filtro
        var ativo = _ativo;
        var ticket = '';
        var nome = "";
        var gestor = '';
        var filtro = _filtro;
        var gestores = _gestores;
        var areas = _areas;
     
        var init = function () {        
            
            gestores.unshift({id: '', nome: ''});
            areas.unshift({id: '', nome: ''});
            $scope.gestores = gestores;
            $scope.areas = areas;
            $scope.departamento = {};       

        /**
         * Objeto utilizado para manter controle do estado da visualização dos perfils.
         */
        $scope.filter = {
            coluna: 'nome',
            ordem: 'asc',
            pagina: 1,
            limite: 15,
            nome: '',
            gestor: 0,
            areas: 0,
            //ticket: '',
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
                $scope.filter.gestor = gestor;
                $scope.filter.area = areas;
                $scope.filter.ativo = ativo;
                $scope.filter.ticket = ticket;
                
            }  else
            {
                //Se filtro (NOME, GESTOR, AREA E TICKET) = vazio e ATIVO = true, deve ser carregado fechado   
                $scope.filtro = ( 
                    $cookies.get('rh_departamento_nome') == "" && 
                        (isNaN(parseInt($cookies.get('rh_departamento_gestor'))) || $cookies.get('rh_departamento_gestor') == "0") && 
                            (isNaN(parseInt($cookies.get('rh_departamento_area'))) || $cookies.get('rh_departamento_area') == "0") && 
                                $cookies.get('rh_departamento_ativo') == "true" &&
                                    $cookies.get('rh_departamento_ticket') == "" ) ? false : true;

                $scope.filter.nome = $cookies.get('rh_departamento_nome');
                 $scope.filter.gestor = $cookies.get('rh_departamento_gestor');
                  $scope.filter.area = $cookies.get('rh_departamento_area');
                   $scope.filter.ticket = $cookies.get('rh_departamento_ticket');
                    $scope.filter.ativo = $cookies.get('rh_departamento_ativo');

                if ($scope.filter.ativo == "false"){
                    $scope.filter.ativo = "false";                      
                } else {                    
                    $scope.filter.ativo = "true";  
                }
            }

            $scope.pesquisaDepartamento($scope.filter);
        };

        var verificaCookies = function(){            
            
           if(typeof $cookies.get('rh_departamento_nome') == "undefined" && 
                typeof $cookies.get('rh_departamento_gestor') == "undefined" && 
                    typeof $cookies.get('rh_departamento_area') == "undefined" && 
                        typeof $cookies.get('rh_departamento_ativo') == "undefined" &&
                            typeof $cookies.get('rh_departamento_ticket') == "undefined" 
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
            $scope.pesquisaDepartamento($scope.filter);
        }

        $scope.onGetPage = function (pageNumber) {//ok
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;                
                $scope.pesquisaDepartamento($scope.filter);
            }
        }

        $scope.pesquisaDepartamento = function(pesquisa) {

            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
            $scope.carregando = true;

            DepartamentoFactory.pesquisaDep (pesquisa)
            .then(function mySuccess(response) {

                // Define a lista de usuários que será exibida na view.
                $scope.lista = response.data.departamentos.data;

                // Calcula alguns parametros da paginação.                
                var de = response.data.departamentos.current_page == 1 ? 1 : 
                    response.data.departamentos.total < pesquisa.limite ? 
                    ((response.data.departamentos.current_page-1) * pesquisa.limite) + response.data.departamentos.total : 
                    ((response.data.departamentos.current_page - 1) * pesquisa.limite) + 1;

                var ate = pesquisa.limite == 0 ? response.data.departamentos.total : 
                    response.data.departamentos.total < pesquisa.limite ? 
                    response.data.departamentos.total : 
                    response.data.departamentos.current_page * pesquisa.limite;

                var totalDePaginas = response.data.departamentos.total % pesquisa.limite === 0 ? 
                    response.data.departamentos.total / pesquisa.limite : 
                    Math.floor(response.data.departamentos.total / pesquisa.limite) + 1;

                // Define os parametros da paginação
                $scope.paginacao = {
                    total: response.data.departamentos.total,
                    pagina: response.data.departamentos.current_page,
                    de: de,
                    ate: ate,
                    limite: pesquisa.limite,
                    totalDePaginas: totalDePaginas
                };

                // Define os parametros da pesquisa nos cookies.
                $cookies.put('rh_departamento_nome', pesquisa.nome);
                $cookies.put('rh_departamento_gestor', response.data.gestor);
                $cookies.put('rh_departamento_area', response.data.area);
                $cookies.put('rh_departamento_ativo', pesquisa.ativo);
                $cookies.put('rh_departamento_ticket', pesquisa.ticket);
                $cookies.put('rh_departamento_pagina', pesquisa.pagina);

            }, function(error, status){

                $scope.errors = error.data;

            }).finally(function() {

                $scope.disableButton = false;
                $scope.carregando = false;
            });
        };

        $scope.limpaPesquisa = function() {//ok
            $scope.filter.nome = "";
            $scope.filter.gestor = gestores[0];
            $scope.filter.area = areas[0];
            $scope.filter.ativo = "true";
            $scope.filter.ticket = "";

            $scope.pesquisaDepartamento($scope.filter);
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

      $scope.verificaCookiesAreas = function()
        {
            $scope.filter.area = $scope.areas[ setSelect('rh_departamento_area', $scope.areas) ];
        }

        $scope.verificaCookiesGestores = function()
        {
            $scope.filter.gestor = $scope.gestores[ setSelect('rh_departamento_gestor', $scope.gestores) ];
        }

        var setSelect = function( cookie, lista )
        {
            if( isNaN(parseInt( $cookies.get(cookie))) || $cookies.get(cookie) == "0" )
            {
                return 0;
            }
            else
            {
                var indexObj = 0;

                angular.forEach(lista, function(value) {
                    if( value.id == $cookies.get(cookie) )
                    {
                        indexObj = lista.indexOf( value )
                    }
                });

                return indexObj;
            }
        }

      

        //incializa controller
        init();
        
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();
