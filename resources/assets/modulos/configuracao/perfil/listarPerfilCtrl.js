(function(){

    'use strict';

    angular.module('app')
    .controller('listarPerfilCtrl', ['$scope', '$http', 'PerfilFactory', '$cookies', function($scope, $http, PerfilFactory, $cookies) {
        
        var nome = '';
        var ativo = _ativo;
        var acesso = "";
        var acessos = _acessos;
        var filtro = _filtro;  

        var init = function () {//ok
           
            acessos.unshift({id: '', nome: ''});
            $scope.acessos = acessos;

            /**
             * Objeto utilizado para manter controle do estado da visualização dos perfils.
             */
            $scope.filter = {
                coluna: 'nome',
                ordem: 'asc',
                pagina: 1,
                limite: 15,
                nome: '',
                acesso: '',
                ativo: ativo
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
                $scope.filter.acesso = acesso;
                $scope.filter.ativo = ativo;
            }
            else
            {

                console.log($cookies.get('configuracao_perfil_nome') == "");
                //Se filtro NOME = vazio e ATIVO = true, deve ser carregado fechado                
                $scope.filtro = ( 
                    (typeof $cookies.get('configuracao_perfil_nome') == "undefined" || $cookies.get('configuracao_perfil_nome') == "") && 
                        ($cookies.get('configuracao_perfil_acesso') == 'null' ||  $cookies.get('configuracao_perfil_acesso') == "0") &&
                            $cookies.get('configuracao_perfil_ativo') == "true" ) ? false : true;

                $scope.filter.nome = $cookies.get('configuracao_perfil_nome');
                $scope.filter.ativo = $cookies.get('configuracao_perfil_ativo');

                if ($scope.filter.ativo == "false")
                    $scope.filter.ativo = "false";  
                else
                    $scope.filter.ativo = "true";

                if( $cookies.get('configuracao_perfil_acesso') == "null" )
                    $scope.filter.acesso = {};
                else
                    $scope.filter.acesso = $scope.acessos[$cookies.get('configuracao_perfil_acesso')-1];

            }

            $scope.pesquisaPerfil($scope.filter);
        };        

        var verificaCookies = function()//ok
        {
            if(typeof $cookies.get('configuracao_perfil_nome') == "undefined" && 
                typeof $cookies.get('configuracao_perfil_acesso') == "null" && 
                    typeof $cookies.get('configuracao_perfil_ativo') == "undefined") 
            {                   
                return false;  
            }
            
            return true;            
        };

        $scope.defineFiltro = function (_column = '') {//ok
            if (_column != $scope.filter.coluna) {                
                $scope.filter.coluna = _column;            
            } 

            $scope.filter.ordem = $scope.filter.ordem == 'asc' ? 'desc' : 'asc';
            $scope.pesquisaPerfil($scope.filter);
        }

        $scope.onGetPage = function (pageNumber) {//ok
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;

                $scope.pesquisaPerfil($scope.filter);
            }
        }

        $scope.pesquisaPerfil = function(pesquisa) {//ok

            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
            $scope.carregando = true;

            PerfilFactory.pesquisaPerfil($scope.filter)
            .then(function mySuccess(response)
            {
                $scope.lista = response.data.perfis.data;

                var de = response.data.perfis.current_page == 1 ? 1 : 
                    response.data.perfis.total < pesquisa.limite ? 
                    ((response.data.perfis.current_page-1) * pesquisa.limite) + response.data.perfis.total : 
                    ((response.data.perfis.current_page - 1) * pesquisa.limite) + 1;
                
                var ate = pesquisa.limite == 0 ? response.data.perfis.total : 
                    response.data.perfis.total < pesquisa.limite ? 
                    response.data.perfis.total : 
                    response.data.perfis.current_page * pesquisa.limite;

                var totalDePaginas = response.data.perfis.total % pesquisa.limite === 0 ? 
                    response.data.perfis.total / pesquisa.limite : 
                    Math.floor(response.data.perfis.total / pesquisa.limite) + 1;

                $cookies.put('configuracao_perfil_nome', pesquisa.nome);
                $cookies.put('configuracao_perfil_ativo', pesquisa.ativo);
                $cookies.put('configuracao_perfil_acesso', response.data.acesso);

                $scope.paginacao = {
                    total: response.data.perfis.total,
                    pagina: response.data.perfis.current_page,
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
        
        $scope.limpaPesquisa = function(){//ok

            $scope.filter.nome = "";
            $scope.filter.ativo = "true";
            $scope.filter.acesso = acessos[0];

            $scope.pesquisaPerfil($scope.filter);
        };

        $scope.setSelect = function()
        {            
            if( $cookies.get('configuracao_perfil_acesso') != "null" )
            {
                var indexObj = 0;

                angular.forEach($scope.acessos, function(value) {
                    if( value.id == $cookies.get('configuracao_perfil_acesso') )
                    {
                        indexObj = $scope.acessos.indexOf( value )
                    }
                });

                $scope.filter.acesso = $scope.acessos[indexObj];

            }else{
                $scope.filter.acesso = acessos[0];
            }
        }

        //incializa controller
        init();
        
    }]);    

})();