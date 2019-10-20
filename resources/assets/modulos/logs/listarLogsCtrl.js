(function(){
    'use strict';

    angular.module('app')
    .controller('listarLogsCtrl', ['$scope', '$http', 'LogFactory', '$cookies', '$filter', '$location', function($scope, $http, LogFactory, $cookies, $filter, $location) {
        
        //filtro
        var ip = '';
        var perfil = '';
        var usuario = '';
        var mensagem = '';
        var tipo = '';
        var logs = _logs;
        var filtro = _filtro;
        var perfis = _perfis;        
        var tipos = _tipos;        

        var init = function () {//ok

            perfis.unshift('');
            tipos.unshift('');
            
            $scope.logs = logs;
            $scope.perfis = perfis;
            $scope.tipos = tipos;

            /**
             * Objeto utilizado para manter controle do estado da visualização dos perfils.
             */
            $scope.filter = {
                coluna: 'created_at',
                ordem: 'desc',
                pagina: 1,
                limite: 50
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

            if(verificaCookies() == false) {
                
                $scope.filter.de = $filter('date')(new Date(), 'dd/MM/y');
                $scope.filter.ate = $filter('date')(new Date(), 'dd/MM/y');                
                $scope.filter.usuario = usuario;                                
                $scope.filter.ip = ip;
                $scope.filter.tipo = tipo;
                $scope.filter.perfil = perfil;

            }else{

                $scope.filtro =  
                    ( $cookies.get('log_acessos_usuario') == ""
                        && $cookies.get('log_acessos_ip') == ""  
                            && $cookies.get('log_acessos_tipo') == "" 
                                && $cookies.get('log_acessos_perfil') == "" 
                                ) ? false : true; 

                $scope.filter.de = $cookies.get('log_acessos_de');
                $scope.filter.ate = $cookies.get('log_acessos_ate');
                $scope.filter.ip = $cookies.get('log_acessos_ip');
                $scope.filter.usuario = $cookies.get('log_acessos_usuario');
            }            
            $scope.pesquisaLog($scope.filter);
        };

        var verificaCookies = function(){//ok
            
            if( typeof $cookies.get('log_acessos_usuario') == "undefined" && 
                    typeof $cookies.get('log_acessos_ip') == "undefined"  &&  
                        typeof $cookies.get('log_acessos_tipo') == "undefined" &&
                            typeof $cookies.get('log_acessos_perfil') == "undefined" ) 
            {                   
                return false; 
            }            
            return true;
        };

        $scope.verificaCookiesPerfil = function()
        {
            $scope.filter.perfil = $scope.perfis[ setSelect('log_acessos_perfil', $scope.perfis) ];
        }

        $scope.verificaCookiesTipo = function()
        {
            $scope.filter.tipo = $scope.tipos[ setSelect('log_acessos_tipo', $scope.tipos) ];
        }

        var setSelect = function( cookie, lista )
        {
            if( $cookies.get(cookie) == "" )
            {
                return 0;
            }
            else
            {
                var indexObj = 0;

                angular.forEach(lista, function(value) {
                    if( value == $cookies.get(cookie) )
                    {
                        indexObj = lista.indexOf( value )
                    }
                });

                return indexObj;
            }
        }

        $scope.defineFiltro = function (_column = '') {//ok
            if (_column != $scope.filter.coluna){
                $scope.filter.coluna = _column;            
            }            

            $scope.filter.ordem = $scope.filter.ordem == 'asc' ? 'desc' : 'asc';
            $scope.pesquisaLog($scope.filter);
        }

        $scope.onGetPage = function (pageNumber) {//ok
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;                
                $scope.pesquisaLog($scope.filter);
            }
        }
        
        $scope.pesquisaLog = function( pesquisa ){

            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
            $scope.carregando = true;

            LogFactory.pesquisaLog(pesquisa)  
            .then(function mySuccess(response) {

                $scope.lista = response.data.logs.data;

                // Calcula alguns parametros da paginação.                
                var de = response.data.logs.current_page == 1 ? 1 : 
                    response.data.logs.total < pesquisa.limite ? 
                    ((response.data.logs.current_page-1) * pesquisa.limite) + response.data.logs.total : 
                    ((response.data.logs.current_page - 1) * pesquisa.limite) + 1;

                var ate = pesquisa.limite == 0 ? response.data.logs.total : 
                    response.data.logs.total < pesquisa.limite ? 
                    response.data.logs.total : 
                    response.data.logs.current_page * pesquisa.limite;

                var totalDePaginas = response.data.logs.total % pesquisa.limite === 0 ? 
                    response.data.logs.total / pesquisa.limite : 
                    Math.floor(response.data.logs.total / pesquisa.limite) + 1;                    

                $cookies.put( 'log_acessos_de', pesquisa.de );
                $cookies.put( 'log_acessos_ate', pesquisa.ate );
                $cookies.put( 'log_acessos_usuario', pesquisa.usuario );                
                $cookies.put( 'log_acessos_ip', pesquisa.ip );
                $cookies.put( 'log_acessos_tipo', pesquisa.tipo );
                $cookies.put( 'log_acessos_perfil', pesquisa.perfil );
                                
                // Define os parametros da paginação
                $scope.paginacao = {
                    total: response.data.logs.total,
                    pagina: response.data.logs.current_page,
                    de: de,
                    ate: ate,
                    limite: pesquisa.limite,
                    totalDePaginas: totalDePaginas
                };

            }, function(error, status){

                $scope.errors = error.data;

            }).finally(function() {

                $scope.disableButton = false;
                $scope.carregando = false;

            });
        };

        $scope.limpaPesquisa = function() {//ok
            $scope.filter.usuario = "";
            $scope.filter.ip = "";
            $scope.filter.perfil = perfis[0];
            $scope.filter.tipo = tipos[0];
            $scope.filter.de = $filter('date')(new Date(), 'dd/MM/y');
            $scope.filter.ate = $filter('date')(new Date(), 'dd/MM/y');

            $scope.pesquisaLog($scope.filter);
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

        $scope.csv = function(){
            document.exportar.action = "/log/acessos/download/csv";
            document.exportar.submit();
        }

        $scope.xlsx = function(){
            document.exportar.action = "/log/acessos/download/xlsx";
            document.exportar.submit();
        }
        
        //incializa controller
        init();
    }]);
})();