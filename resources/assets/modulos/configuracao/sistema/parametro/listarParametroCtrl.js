(function(){

    'use strict';

    angular.module('app')
    .controller('listarParametroCtrl', ['$scope', '$http', 'ParametroFactory', '$cookies', function($scope, $http, ParametroFactory, $cookies) {
        
        var ativo = _ativo;
        var filtro = _filtro;
        var grupos = _grupos;
        var tipos = _tipos;

        var nome = '';
        var grupo = '';
        var tipo = '';
        
        /**
         * Objeto utilizado para manter controle do estado da visualização dos parametros.
         */
        $scope.filter = {
            coluna: 'grupo_nome',
            ordem: 'asc',
            pagina: 1,
            limite: 15,            
            ativo: _ativo,
            nome: '',
            grupo: 0,
            tipo: 0
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

        var init = function () {

            grupos.unshift({id: '', nome: ''});
            tipos.unshift({id: '', nome: ''});

            $scope.grupos = grupos;
            $scope.tipos = tipos;

            if( verificaCookies() == false )
            {
                $scope.filter.nome = nome;
                $scope.filter.grupo = grupo;
                $scope.filter.tipo = tipo;
            }
            else
            {
                // Se o cookie retornar null é isNaN
                $scope.filtro = ( 
                    $cookies.get('config_sistema_parametro_nome') == "" && 
                        (isNaN(parseInt( $cookies.get('config_sistema_parametro_grupo'))) ||  $cookies.get('config_sistema_parametro_grupo') == "0") && 
                            (isNaN(parseInt( $cookies.get('config_sistema_parametro_tipo'))) ||  $cookies.get('config_sistema_parametro_tipo') == "0") ) ? false : true;

                $scope.filter.nome = $cookies.get('config_sistema_parametro_nome');
            }

            $scope.pesquisaParametro($scope.filter);
        };        

        var verificaCookies = function()
        {
            if(typeof $cookies.get('config_sistema_parametro_nome') == "undefined" && 
                typeof $cookies.get('config_sistema_parametro_grupo') == "undefined" && 
                    typeof $cookies.get('config_sistema_parametro_tipo') == "undefined" 
                        ) 
            {
                return false;
            }

            return true;          
        };

        $scope.verificaCookiesGrupo = function()
        {
            $scope.filter.grupo = $scope.grupos[ setSelect('config_sistema_parametro_grupo', $scope.grupos) ];
        }

        $scope.verificaCookiesTipo = function()
        {
            $scope.filter.tipo = $scope.tipos[ setSelect('config_sistema_parametro_tipo', $scope.tipos) ];
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

        $scope.defineFiltro = function (_column = '') {//ok
            if (_column != $scope.filter.coluna) {                
                $scope.filter.coluna = _column;            
            } 

            $scope.filter.ordem = $scope.filter.ordem == 'asc' ? 'desc' : 'asc';
            $scope.pesquisaParametro($scope.filter);
        }

        $scope.onGetPage = function (pageNumber) {//ok
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;

                $scope.pesquisaParametro($scope.filter);
            }
        }

        $scope.pesquisaParametro = function(pesquisa) {

            $scope.disableButton = true;
            $scope.carregando = true;

            ParametroFactory.pesquisaParametro($scope.filter)
            .then(function mySuccess(response)
            {
                // Define a lista de parametros que será exibida na view.
                $scope.lista = response.data.parametros.data;

                var de = response.data.parametros.current_page == 1 ? 1 : 
                    response.data.parametros.total < pesquisa.limite ? 
                    ((response.data.parametros.current_page-1) * pesquisa.limite) + response.data.parametros.total : 
                    ((response.data.parametros.current_page - 1) * pesquisa.limite) + 1;
                
                var ate = pesquisa.limite == 0 ? response.data.parametros.total : 
                    response.data.parametros.total < pesquisa.limite ? 
                    response.data.parametros.total : 
                    response.data.parametros.current_page * pesquisa.limite;

                var totalDePaginas = response.data.parametros.total % pesquisa.limite === 0 ? 
                    response.data.parametros.total / pesquisa.limite : 
                    Math.floor(response.data.parametros.total / pesquisa.limite) + 1;

                $cookies.put('config_sistema_parametro_nome', pesquisa.nome);
                $cookies.put('config_sistema_parametro_grupo', response.data.grupo);
                $cookies.put('config_sistema_parametro_tipo', response.data.tipo);

                $scope.paginacao = {
                    total: response.data.parametros.total,
                    pagina: response.data.parametros.current_page,
                    de: de,
                    ate: ate,
                    limite: pesquisa.limite,
                    totalDePaginas: totalDePaginas
                };

            },
            function(error, status)
            {
                $scope.errors = error.data;
            })
            .finally(function()
            {
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



        $scope.limpaPesquisa = function(){

            $scope.filter.nome = "";
            $scope.filter.grupo = grupos[0];
            $scope.filter.tipo = tipos[0];

            $scope.pesquisaParametro($scope.filter);
        };

        //incializa controller
        init();
        
    }]);    

})();