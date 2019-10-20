(function(){

    'use strict';

    angular.module('app')
    .controller('listarAreaCtrl', ['$scope', '$http', 'AreaFactory', '$cookies', '$uibModal', function($scope, $http, AreaFactory, $cookies, $uibModal) {
        var $ctrl = this;

        //filtro
        var ativo = _ativo;
        var nome = "";
        var gestor = '';
        var gestores = _gestores;
        var filtro = _filtro;

        var init = function ()
        {
            gestores.unshift({id: '', nome: ''});
            $scope.gestores = gestores;

            $scope.areas = {}; 

            /**
             * Objeto utilizado para manter controle do estado da visualização das areas.
             */
            $scope.filter = {
                coluna: 'nome',
                ordem: 'asc',
                pagina: 1,
                limite: 15,
                nome: '',
                gestor: 0,
                ativo: _ativo,
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

            if( verificaCookies() == false )
            {
                $scope.filter.nome = nome;
                $scope.filter.gestor = gestor;
                $scope.filter.ativo = ativo;
            }
            else
            {
                //Se filtro (NOME, USUARIO, PERFIL) = vazio e ATIVO = true, deve ser carregado fechado   
                $scope.filtro = ( 
                    $cookies.get('rh_area_nome') == "" && 
                        (isNaN(parseInt($cookies.get('rh_area_gestor'))) || $cookies.get('rh_area_gestor') == "0") && 
                                $cookies.get('rh_area_ativo') == "true" ) ? false : true;

                $scope.filter.nome = $cookies.get('rh_area_nome');
                $scope.filter.ativo = $cookies.get('rh_area_ativo');

                if ($scope.filter.ativo == "false"){
                    $scope.filter.ativo = "false";                      
                } else {                    
                    $scope.filter.ativo = "true";  
                }
            }

            $scope.pesquisaArea($scope.filter);
        };

        var verificaCookies = function(){            
            
            if(typeof $cookies.get('rh_area_nome') == "undefined" && 
                typeof $cookies.get('rh_area_gestor') == "undefined" && 
                    typeof $cookies.get('rh_area_ativo') == "undefined"
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
            $scope.pesquisaArea($scope.filter);
        }

        $scope.onGetPage = function (pageNumber) {//ok
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;                
                $scope.pesquisaArea($scope.filter);
            }
        }

        $scope.pesquisaArea = function(pesquisa) {

            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
            $scope.carregando = true;
            
            AreaFactory.pesquisaArea(pesquisa)
            .then(function mySuccess(response) {

                // Define a lista de usuários que será exibida na view.
                $scope.lista = response.data.areas.data;

                // Calcula alguns parametros da paginação.                
                var de = response.data.areas.current_page == 1 ? 1 : 
                    response.data.areas.total < pesquisa.limite ? 
                    ((response.data.areas.current_page-1) * pesquisa.limite) + response.data.areas.total : 
                    ((response.data.areas.current_page - 1) * pesquisa.limite) + 1;

                var ate = pesquisa.limite == 0 ? response.data.areas.total : 
                    response.data.areas.total < pesquisa.limite ? 
                    response.data.areas.total : 
                    response.data.areas.current_page * pesquisa.limite;

                var totalDePaginas = response.data.areas.total % pesquisa.limite === 0 ? 
                    response.data.areas.total / pesquisa.limite : 
                    Math.floor(response.data.areas.total / pesquisa.limite) + 1;

                // Define os parametros da paginação
                $scope.paginacao = {
                    total: response.data.areas.total,
                    pagina: response.data.areas.current_page,
                    de: de,
                    ate: ate,
                    limite: pesquisa.limite,
                    totalDePaginas: totalDePaginas
                };

                // Define os parametros da pesquisa nos cookies.
                $cookies.put('rh_area_nome', pesquisa.nome);
                $cookies.put('rh_area_gestor', response.data.gestor);
                $cookies.put('rh_area_ativo', pesquisa.ativo);
                $cookies.put('rh_area_pagina', pesquisa.pagina);

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
            $scope.filter.ativo = "true";

            $scope.pesquisaArea($scope.filter);
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

        $scope.setSelect = function()
        {
            if( isNaN(parseInt( $cookies.get('rh_area_gestor'))) || $cookies.get('rh_area_gestor') == "0")
            {
                $scope.filter.gestor = $scope.gestores[0];
            }
            else
            {
                var indexObj = 0;

                angular.forEach($scope.gestores, function(value) {
                    if( value.id == $cookies.get('rh_area_gestor') )
                    {
                        indexObj = $scope.gestores.indexOf( value )
                    }
                });

                $scope.filter.gestor = $scope.gestores[indexObj];
            }
        }

        //incializa controller
        init();
        
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();

