(function(){

    'use strict';

    angular.module('app')
    .controller('listarTicketCtrl', ['$scope', '$rootScope', '$http', 'TicketFactory', '$cookies', '$filter', '$location', function($scope, $rootScope, $http, TicketFactory, $cookies, $filter, $location) {
        var $ctrl = this;

        //filtro
        var filtro = _filtro;
         
        
        var assunto = "";
        var codigo = "";
        var departamento;
        var usuario_responsavel;
        var usuario_solicitante;
        var aberto;
        var categoria;
        var statuse;
        var prioridade;

        var departamentos = _departamentos;
        var usuarios = _usuarios;
        var categorias;
        var statuses;
        var prioridades;


        var init = function ()
        {
            departamentos.unshift({id: '', nome: ''});
            $scope.departamentos = departamentos;
        
            //categorias.unshift({id: '', nome: ''});
            //$scope.categorias = categorias;
            
            //statuses.unshift({id: '', nome: ''});
            //$scope.statuses = statuses;
            
            usuarios.unshift({id: '', nome: ''});
            $scope.usuarios = usuarios;

            //prioridades.unshift({id: '', nome: ''});
            //$scope.prioridades = prioridades;
            
            /**
             * Objeto utilizado para manter controle do estado da visualização das tickets.
             */
            $scope.filter = {
                coluna: 'status_ordem',
                ordem: 'asc',
                pagina: 1,
                limite: 50,
                reverso: true,
                aberto: true,
                departamento: 0,
                usuario_responsavel: 0,
                usuario_solicitante: 0,
                categoria: 0,
                statuse: 0,
                prioridade: 0,
                assunto: '',
                codigo: ''
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
                $scope.filter.de = "";
                $scope.filter.ate = "";
                $scope.filter.aberto = "true";
                $scope.filter.codigo = codigo;
                $scope.filter.assunto = assunto;
                $scope.filter.departamento = departamento;
                $scope.filter.usuario_responsavel = usuario_responsavel;
                $scope.filter.usuario_solicitante = usuario_solicitante;
                $scope.filter.categoria = categoria;
                $scope.filter.statuse = statuse;
                $scope.filter.prioridade = prioridade;
              
            }
            else
            {
                $scope.filtro = ( 
                    $cookies.get('ticket_codigo') == "" && 
                    $cookies.get('ticket_assunto') == "" && 
                        (isNaN(parseInt($cookies.get('ticket_usuario_responsavel'))) || $cookies.get('ticket_usuario_responsavel') == "0") && 
                            ($cookies.get('ticket_aberto') == "true") &&
                              (isNaN(parseInt($cookies.get('ticket_ticket_de'))) || $cookies.get('ticket_ticket_de') == "") && 
                                  (isNaN(parseInt($cookies.get('ticket_ticket_ate'))) || $cookies.get('ticket_ticket_ate') == "") && 
                                    (isNaN(parseInt($cookies.get('ticket_usuario_solicitante'))) || $cookies.get('ticket_usuario_solicitante') == "0") &&
                                         (isNaN(parseInt($cookies.get('ticket_departamento'))) || $cookies.get('ticket_departamento') == "0"))? false : true;


                $scope.filter.de = $cookies.get('ticket_ticket_de');
                $scope.filter.ate = $cookies.get('ticket_ticket_ate');    
                $scope.filter.codigo = $cookies.get('ticket_codigo'); 
                $scope.filter.assunto = $cookies.get('ticket_assunto'); 
                $scope.filter.usuario_responsavel = $cookies.get('ticket_usuario_responsavel');
                $scope.filter.usuario_solicitante = $cookies.get('ticket_usuario_solicitante');
                $scope.filter.departamento = $cookies.get('ticket_departamento');
                $scope.filter.aberto = $cookies.get('ticket_aberto');
                $scope.filter.categoria = $cookies.get('ticket_categoria');
                $scope.filter.statuse = $cookies.get('ticket_statuse');
                $scope.filter.prioridade = $cookies.get('ticket_prioridade');
             
            }
           
            $scope.pesquisaTicket($scope.filter);
            
        };

        var verificaCookies = function(){            
            
            if(typeof $cookies.get('ticket_codigo') == "undefined" && 
                typeof $cookies.get('ticket_assunto') == "undefined" && 
                    typeof $cookies.get('ticket_ticket_de') == "undefined" && 
                        typeof $cookies.get('ticket_ticket_ate') == "undefined" && 
                            typeof $cookies.get('ticket_aberto') == "undefined" && 
                                typeof $cookies.get('ticket_usuario_responsavel') == "undefined" &&
                                        typeof $cookies.get('ticket_usuario_solicitante') == "undefined" &&
                                            typeof $cookies.get('ticket_categoria') == "undefined" &&
                                                typeof $cookies.get('ticket_statuse') == "undefined" &&
                                                    typeof $cookies.get('ticket_prioridade') == "undefined"           
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
            $scope.pesquisaTicket($scope.filter);
        }


        $scope.onGetPage = function (pageNumber) {
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;                
                $scope.pesquisaTicket($scope.filter);
            }
        }

        $scope.pesquisaTicket = function(pesquisa) {
            
            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
             $scope.carregando = true;

            TicketFactory.pesquisarTicket($scope.filter)
            .then(function mySuccess(response)
            {

                // Define a lista de usuários que será exibida na view.
                $scope.lista = response.data.ticket.data;

                // Armazena ID do usuário logado
                $scope.usuario_logado = $rootScope.auth.id;
                
                // Calcula alguns parametros da paginação.                
                var de = response.data.ticket.current_page == 1 ? 1 : 
                response.data.ticket.total < pesquisa.limite ? 
                ((response.data.ticket.current_page-1) * pesquisa.limite) + response.data.ticket.total : 
                ((response.data.ticket.current_page - 1) * pesquisa.limite) + 1;
                
                var ate = pesquisa.limite == 0 ? response.data.ticket.total : 
                response.data.ticket.total < pesquisa.limite ? 
                response.data.ticket.total : 
                response.data.ticket.current_page * pesquisa.limite;
                
                var totalDePaginas = response.data.ticket.total % pesquisa.limite === 0 ? 
                response.data.ticket.total / pesquisa.limite : 
                Math.floor(response.data.ticket.total / pesquisa.limite) + 1;
                
                // Define os parametros da pesquisa nos cookies.
                $cookies.put('ticket_assunto', response.data.assunto);
                $cookies.put('ticket_codigo', response.data.codigo);
                $cookies.put('ticket_departamento', response.data.departamento);
                $cookies.put('ticket_aberto', response.data.aberto);
                $cookies.put('ticket_usuario_responsavel', response.data.usuario_responsavel);
                $cookies.put('ticket_usuario_solicitante', response.data.usuario_solicitante);
                //$cookies.put('ticket_categoria', response.data.categoria);
                //$cookies.put('ticket_statuse', response.data.statuse);
                //$cookies.put('ticket_prioridade', response.data.prioridade);
                $cookies.put('ticket_pagina', pesquisa.pagina);
                $cookies.put('ticket_ticket_de', pesquisa.de );
                $cookies.put('ticket_ticket_ate', pesquisa.ate );
                // Define os parametros da paginação
                $scope.paginacao = {
                    total: response.data.ticket.total,
                    pagina: response.data.ticket.current_page,
                    de: de,
                    ate: ate,
                    limite: pesquisa.limite,
                    totalDePaginas: totalDePaginas
            };

            }, function(response){

                $scope.errors = response.data;

                // Caso seja falha de login
                if (response.status == 401){
                    $scope.reload = function(){
                        location.href = '/login';
                    }
                    $scope.reload();
                }
            
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
            
            $scope.filter.de = "";
            $scope.filter.ate = "";
            $scope.filter.aberto = "true";
            $scope.filter.assunto = "";
            $scope.filter.codigo = "";
            $scope.filter.usuario_responsavel = usuarios[0];
            $scope.filter.usuario_solicitante = usuarios[0];
            $scope.filter.departamento = departamentos[0];
            $scope.filter.categoria = 0;
            $scope.filter.statuse = 0;
            $scope.filter.prioridade = 0;
        
            $scope.pesquisaTicket($scope.filter);
        };

        $scope.selecionaDepartamento = function()
        {
            if ($scope.filter.departamento.id != 0){
                
                TicketFactory.getPrioridade($scope.filter.departamento.id)
                .then(function mySuccess(response)
                {
                  //  response.data.unshift({id: '', nome: ''});
                    $scope.prioridades = response.data;
                }, function(response){
                    $scope.errors = response.data;
                }).finally(function() {
                        
                
                });
                
                TicketFactory.getStatus($scope.filter.departamento.id)
                .then(function mySuccess(response)
                {
                  //  response.data.unshift({id: '', nome: ''});
                    $scope.statuses = response.data;
                }, function(response){
                    $scope.errors = response.data;
                }).finally(function() {
                        
                
                });
               
                TicketFactory.getCategoria($scope.filter.departamento.id)
                .then(function mySuccess(response)
                {   
                   // response.data.unshift({id: '', nome: ''});
                    $scope.categorias = response.data;
                }, function(response){
                    $scope.errors = response.data;
                }).finally(function() {
                 
                });
            } 
            
            if ($scope.filter.departamento.id == 0){
                $scope.categorias = "";
                $scope.statuses = "";
                $scope.prioridades = "";
            }
        }


        $scope.verificaCookiesDepartamento = function()
        {
            $scope.filter.departamento = $scope.departamentos[ setSelect('ticket_departamento', $scope.departamentos) ];
        }

        $scope.verificaCookiesStatuse = function()
        {
        //  $scope.filter.statuse = $scope.statuses[ setSelect('ticket_statuse', $scope.statuses) ];
          
        }

        $scope.verificaCookiesCategoria = function()
        {   
         // $scope.filter.categoria = $scope.categorias[ setSelect('ticket_categoria', $scope.categorias) ];
        }

        $scope.verificaCookiesPrioridade = function()
        {   
        // $scope.filter.prioridade = $scope.prioridades[ setSelect('ticket_prioridade', $scope.prioridades) ];
        }

        $scope.verificaCookiesSolicitante = function()
        {
            $scope.filter.usuario_solicitante = $scope.usuarios[ setSelect('ticket_usuario_solicitante', $scope.usuarios) ];
        }

        $scope.verificaCookiesResponsavel = function()
        {
            $scope.filter.usuario_responsavel = $scope.usuarios[ setSelect('ticket_usuario_responsavel', $scope.usuarios) ];
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

        $scope.csv = function(){            
            document.exportar.action = "/ticket/download/csv";            
            document.exportar.submit();
        }

        $scope.xlsx = function(){            
            document.exportar.action = "/ticket/download/xlsx";
            document.exportar.submit();
        }

        var getPadraoDataInicio = function(){
            var date = new Date();
            var dataInicio = new Date(date.getFullYear(), date.getMonth(), 1);
            return $filter('date')(dataInicio, 'dd/MM/y');
        }

        var getPadraoDataFim = function(){
            var date = new Date();
            var dataFim = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            return $filter('date')(dataFim, 'dd/MM/y');
        }



    //incializa controller
    init();
        
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();


