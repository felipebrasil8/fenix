(function(){

    'use strict';
   
    angular.module('app')
    .controller('listarUsuarioCtrl', ['$scope', '$http', 'UsuarioFactory', '$uibModal', '$cookies', function($scope, $http, UsuarioFactory, $uibModal, $cookies) {

        //filtro
        var nome = "";
        var usuario = "";
        var perfil = "";
        var perfis = _perfis;
        var ativo = _ativo;      
        var filtro = _filtro;
        var politicaSenha = _politicaSenha;
        var strPoliticaSenha = '';

        var init = function(){//ok
           
            perfis.unshift({id: '', nome: ''});
            $scope.perfis = perfis;
           
            /**
             * Objeto utilizado para manter controle do estado da visualização dos perfils.
             */
            $scope.filter = {
                coluna: 'nome',
                ordem: 'asc',
                pagina: 1,
                limite: 15,
                nome: '',
                usuario: '',
                ativo: _ativo,
                perfil: 0
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

            if( verificaCookies() == false ){
               
                $scope.filter.nome = nome;
                $scope.filter.usuario = usuario;
                $scope.filter.perfil = perfil;
                $scope.filter.ativo = ativo;               

            }else{

                //Se filtro (NOME, USUARIO, PERFIL) = vazio e ATIVO = true, deve ser carregado fechado  
                $scope.filtro = (
                    (typeof $cookies.get('configuracao_usuario_nome') == "undefined" || $cookies.get('configuracao_usuario_nome') == "") && 
                        (typeof $cookies.get('configuracao_usuario_usuario') == "undefined" || $cookies.get('configuracao_usuario_usuario') == "") && 
                            ($cookies.get('configuracao_usuario_perfil') == 'null' ||  $cookies.get('configuracao_usuario_perfil') == "0") &&
                                $cookies.get('configuracao_usuario_ativo') == "true" ) ? false : true;

                $scope.filter.nome = $cookies.get('configuracao_usuario_nome');
                $scope.filter.usuario = $cookies.get('configuracao_usuario_usuario');
                $scope.filter.ativo = $cookies.get('configuracao_usuario_ativo');
                if ($scope.filter.ativo == "false")
                    $scope.filter.ativo = "false"; 
                else
                    $scope.filter.ativo = "true"; 

                if( $cookies.get('configuracao_usuario_perfil') == "null" )
                    $scope.filter.perfil = {};
                else
                    $scope.filter.perfil = $scope.perfis[$cookies.get('configuracao_usuario_perfil')-1];
               

            }
            $scope.pesquisaUsuario($scope.filter);

            listarPoliticaSenha();
        };

        var verificaCookies = function(){//ok

            if(typeof $cookies.get('configuracao_usuario_nome') == "undefined" && 
                typeof $cookies.get('configuracao_usuario_usuario') == "undefined" && 
                    typeof $cookies.get('configuracao_usuario_perfil') == "null" && 
                        typeof $cookies.get('configuracao_usuario_ativo') == "undefined"
                        )
            {
                return false; 
            }

            return true;           
        };

        $scope.pesquisaUsuario = function(pesquisa){//ok

            $scope.disableButton = true;     
            $scope.carregando = true;


            UsuarioFactory.pesquisaUsuario(pesquisa) 
            .then(function mySuccess(response) {

                $scope.lista = response.data.usuarios.data;
                //console.log(response.data);
                // Calcula alguns parametros da paginação.               
                var de = response.data.usuarios.current_page == 1 ? 1 :
                    response.data.usuarios.total < pesquisa.limite ?
                    ((response.data.usuarios.current_page-1) * pesquisa.limite) + response.data.usuarios.total :
                    ((response.data.usuarios.current_page - 1) * pesquisa.limite) + 1;

                var ate = pesquisa.limite == 0 ? response.data.usuarios.total :
                    response.data.usuarios.total < pesquisa.limite ?
                    response.data.usuarios.total :
                    response.data.usuarios.current_page * pesquisa.limite;

                var totalDePaginas = response.data.usuarios.total % pesquisa.limite === 0 ?
                    response.data.usuarios.total / pesquisa.limite :
                    Math.floor(response.data.usuarios.total / pesquisa.limite) + 1;

                // // Define os parametros da paginação
                $scope.paginacao = {
                    total: response.data.usuarios.total,
                    pagina: response.data.usuarios.current_page,
                    de: de,
                    ate: ate,
                    limite: pesquisa.limite,
                    totalDePaginas: totalDePaginas
                };

                // Setting cookies
                $cookies.put('configuracao_usuario_nome', pesquisa.nome);
                $cookies.put('configuracao_usuario_usuario', pesquisa.usuario);
                $cookies.put('configuracao_usuario_ativo', pesquisa.ativo);
                $cookies.put('configuracao_usuario_perfil', response.data.perfil);
                                       
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



        var listarPoliticaSenha = function(){
            angular.forEach(politicaSenha, function(value)
            {
                strPoliticaSenha = strPoliticaSenha+'<span style="text-align: left;">- '+value+"</span><br>";
            });
            $scope.politicaSenha = strPoliticaSenha;
        };

        $scope.limpaPesquisa = function(){//ok

            $scope.filter.nome = "";
            $scope.filter.usuario = "";
            $scope.filter.perfil = perfis[0];
            $scope.filter.ativo = "true";

            $scope.pesquisaUsuario($scope.filter);
        };

        $scope.abreModalSolicitarSenha = function (usuario) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: 'solicitarSenhaModal.html',
                controller: function ($scope) {

                    usuario.senha_alterada = !usuario.senha_alterada;
                    usuario.visualizado_senha_alterada = usuario.senha_alterada;
                    usuario.perfil = usuario.perfil_id;
             
                    $scope.modalConfirmarSolicitarSenha = function () {
                        UsuarioFactory.solicitarSenhaFunc( usuario )
                        .then(
                            function (response) {
                               
                                var icone = '';
                                var texto = '';

                                if( usuario.senha_alterada == false )
                                {
                                    icone = 'check';
                                    texto = 'Será solicitada a alteração de senha no próximo acesso, clique para alterar.';
                                }
                                else
                                {
                                    icone = 'close';
                                    texto = 'Não será solicitada a alteração de senha no próximo acesso, clique para alterar.';
                                }

                                usuario.solicitar_senha_icone = icone;
                                usuario.solicitar_senha_texto = texto;
                            },
                            function (error, status) {
                                $scope.erro = "Não foi possível efetuar a operação.";
                            }
                        ).finally(function () {
                            $scope.modalCancelarSolicitarSenha();
                        });
                    }

                    $scope.modalCancelarSolicitarSenha = function () {
                        modalInstance.close();
                    }
                }
            });
        }

        $scope.abreModalNovaSenha = function (usuario, politicaSenha) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: 'novaSenhaModal.html',
                controller: function ($scope) {

                    $scope.usuario = usuario;
                    $scope.usuario.perfil = usuario.perfil_id;

                    $scope.modal = {
                        sucesso: false,
                        erro: false
                    };

                    $scope.politicaSenha = politicaSenha;

                    $scope.modalConfirmarNovaSenha = function () {
                        UsuarioFactory.novaSenhaFunc( $scope.usuario )
                        .then(function (response) {
                            $scope.modal.sucesso = true;
                            $scope.modal.erro = false;
                            $scope.success = response.data.success;
                        }, function (error, status) {
                            $scope.modal.erro = true;
                            $scope.modal.sucesso = false;
                            if (Object.prototype.toString.call(error.data) === '[object Array]') {
                                var errors = [];
                                angular.forEach(error.data, function(value)
                                {
                                    errors.push(value);
                                });
                            } else {
                                errors = error.data;
                            }
                           
                            $scope.errors = errors;
                        }).finally(function () {
                        });
                    }

                    $scope.modalCancelarNovaSenha = function () {
                        modalInstance.close();
                    }
                }
            });
        }

        $scope.onGetPage = function (pageNumber) {
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;               
                $scope.pesquisaUsuario($scope.filter);
            }
        }

        $scope.defineFiltro = function (_column = '') {//ok           
            if (_column != $scope.filter.coluna){               
                $scope.filter.coluna = _column;               
            }           

            $scope.filter.ordem = $scope.filter.ordem == 'asc' ? 'desc' : 'asc';           
            $scope.pesquisaUsuario($scope.filter);
        }

        $scope.setSelect = function()
        {            
            if( $cookies.get('configuracao_usuario_perfil') != "null" )
            {
                var indexObj = 0;

                angular.forEach($scope.perfis, function(value) {
                    if( value.id == $cookies.get('configuracao_usuario_perfil') )
                    {
                        indexObj = $scope.perfis.indexOf( value )
                    }
                });

                $scope.filter.perfil = $scope.perfis[indexObj];

            }else{
                $scope.filter.perfil = perfis[0];
            }
        }

        //inicia controller
        init();
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);

})()