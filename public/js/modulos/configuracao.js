webpackJsonp([9],{

/***/ 242:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(243);
__webpack_require__(244);
__webpack_require__(245);
__webpack_require__(246);
__webpack_require__(247);
__webpack_require__(248);
__webpack_require__(249);
__webpack_require__(250);
__webpack_require__(251);
__webpack_require__(252);
__webpack_require__(253);
__webpack_require__(254);
__webpack_require__(255);
__webpack_require__(256);
__webpack_require__(257);
__webpack_require__(258);
__webpack_require__(259);
__webpack_require__(260);
__webpack_require__(261);
__webpack_require__(262);
__webpack_require__(263);
__webpack_require__(264);
__webpack_require__(265);
module.exports = __webpack_require__(266);


/***/ }),

/***/ 243:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('UsuarioFactory', ['$http', function ($http) {

        var _cadastrarUsuario = function _cadastrarUsuario(param) {
            return $http.post("/configuracao/usuario", param);
        };

        var _pesquisaUsuario = function _pesquisaUsuario(param) {
            return $http.post("/configuracao/usuario/search", param);
        };

        var _atualizarUsuario = function _atualizarUsuario(param, id) {
            return $http.put("/configuracao/usuario/" + id, param);
        };

        var _solicitarSenhaFunc = function _solicitarSenhaFunc(param) {
            return $http.put("/configuracao/usuario/" + param.id + "/solicitarNovaSenha", param);
        };

        var _novaSenhaFunc = function _novaSenhaFunc(param) {
            return $http.put("/configuracao/usuario/" + param.id + "/novaSenha", param);
        };

        return {
            cadastrarUsuario: _cadastrarUsuario,
            pesquisaUsuario: _pesquisaUsuario,
            atualizarUsuario: _atualizarUsuario,
            solicitarSenhaFunc: _solicitarSenhaFunc,
            novaSenhaFunc: _novaSenhaFunc
        };
    }]);
})();

/***/ }),

/***/ 244:
/***/ (function(module, exports) {

(function () {

  'use strict';

  angular.module('app').controller('cadastrarUsuarioCtrl', ['$scope', '$http', 'UsuarioFactory', function ($scope, $http, UsuarioFactory) {

    var perfis = _perfis;
    var funcionarios = _funcionarios;
    var politicaSenha = _politicaSenha;

    var init = function init() {

      $scope.usuario = {};
      listarPerfis();
      listarFuncionarios();
      listarPoliticaSenha();
    };

    var listarPerfis = function listarPerfis() {
      $scope.perfis = perfis;
    };

    var listarFuncionarios = function listarFuncionarios() {
      $scope.funcionarios = funcionarios;
    };

    var listarPoliticaSenha = function listarPoliticaSenha() {
      var strPoliticaSenha = '';
      angular.forEach(politicaSenha, function (value) {
        strPoliticaSenha = strPoliticaSenha + '<span style="text-align: left;">- ' + value + "</span><br>";
      });
      $scope.politicaSenha = strPoliticaSenha;
    };

    $scope.cadastrar = function (usuario) {

      usuario.nome = usuario.nome.toUpperCase();
      usuario.usuario = usuario.usuario.toLowerCase();

      $scope.form.$valid = false;
      $scope.success = false;
      $scope.errors = false;

      UsuarioFactory.cadastrarUsuario(usuario).then(function (response) {

        $scope.success = response.data.mensagem;
        $scope.url = "" + response.data.id + "";
        limparCampos();
      }, function (error) {

        $scope.errors = error.data;
      }).finally(function () {

        $scope.form.$valid = true;
      });
    };

    var limparCampos = function limparCampos() {

      $scope.usuario = {};
    };

    //inicializa controller
    init();
  }]);
})();

/***/ }),

/***/ 245:
/***/ (function(module, exports) {

(function () {
	'use strict';

	angular.module('app').controller('visualizarUsuarioCtrl', ['$scope', '$http', 'UsuarioFactory', function ($scope, $http, UsuarioFactory) {

		var usuario = _usuario;

		var init = function init() {
			$scope.usuario = {};
			listarUsuarios();
		};

		var listarUsuarios = function listarUsuarios() {
			$scope.usuario = usuario;
			$scope.usuario.created_at = usuario.created_at !== null ? new Date(usuario.created_at) : null;
			$scope.usuario.updated_at = usuario.updated_at !== null ? new Date(usuario.updated_at) : null;
		};

		//inicializa controller
		init();
	}]);
})();

/***/ }),

/***/ 246:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('listarUsuarioCtrl', ['$scope', '$http', 'UsuarioFactory', '$uibModal', '$cookies', function ($scope, $http, UsuarioFactory, $uibModal, $cookies) {

        //filtro
        var nome = "";
        var usuario = "";
        var perfil = "";
        var perfis = _perfis;
        var ativo = _ativo;
        var filtro = _filtro;
        var politicaSenha = _politicaSenha;
        var strPoliticaSenha = '';

        var init = function init() {
            //ok

            perfis.unshift({ id: '', nome: '' });
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

                /**
                 * Objeto utilizado para definir o estado da paginação.
                 */
            };$scope.paginacao = {
                de: 0,
                ate: 0,
                total: 0,
                pagina: 0
            };

            if (verificaCookies() == false) {

                $scope.filter.nome = nome;
                $scope.filter.usuario = usuario;
                $scope.filter.perfil = perfil;
                $scope.filter.ativo = ativo;
            } else {

                //Se filtro (NOME, USUARIO, PERFIL) = vazio e ATIVO = true, deve ser carregado fechado  
                $scope.filtro = (typeof $cookies.get('configuracao_usuario_nome') == "undefined" || $cookies.get('configuracao_usuario_nome') == "") && (typeof $cookies.get('configuracao_usuario_usuario') == "undefined" || $cookies.get('configuracao_usuario_usuario') == "") && ($cookies.get('configuracao_usuario_perfil') == 'null' || $cookies.get('configuracao_usuario_perfil') == "0") && $cookies.get('configuracao_usuario_ativo') == "true" ? false : true;

                $scope.filter.nome = $cookies.get('configuracao_usuario_nome');
                $scope.filter.usuario = $cookies.get('configuracao_usuario_usuario');
                $scope.filter.ativo = $cookies.get('configuracao_usuario_ativo');
                if ($scope.filter.ativo == "false") $scope.filter.ativo = "false";else $scope.filter.ativo = "true";

                if ($cookies.get('configuracao_usuario_perfil') == "null") $scope.filter.perfil = {};else $scope.filter.perfil = $scope.perfis[$cookies.get('configuracao_usuario_perfil') - 1];
            }
            $scope.pesquisaUsuario($scope.filter);

            listarPoliticaSenha();
        };

        var verificaCookies = function verificaCookies() {
            //ok

            if (typeof $cookies.get('configuracao_usuario_nome') == "undefined" && typeof $cookies.get('configuracao_usuario_usuario') == "undefined" && typeof $cookies.get('configuracao_usuario_perfil') == "null" && typeof $cookies.get('configuracao_usuario_ativo') == "undefined") {
                return false;
            }

            return true;
        };

        $scope.pesquisaUsuario = function (pesquisa) {
            //ok

            $scope.disableButton = true;
            $scope.carregando = true;

            UsuarioFactory.pesquisaUsuario(pesquisa).then(function mySuccess(response) {

                $scope.lista = response.data.usuarios.data;
                //console.log(response.data);
                // Calcula alguns parametros da paginação.               
                var de = response.data.usuarios.current_page == 1 ? 1 : response.data.usuarios.total < pesquisa.limite ? (response.data.usuarios.current_page - 1) * pesquisa.limite + response.data.usuarios.total : (response.data.usuarios.current_page - 1) * pesquisa.limite + 1;

                var ate = pesquisa.limite == 0 ? response.data.usuarios.total : response.data.usuarios.total < pesquisa.limite ? response.data.usuarios.total : response.data.usuarios.current_page * pesquisa.limite;

                var totalDePaginas = response.data.usuarios.total % pesquisa.limite === 0 ? response.data.usuarios.total / pesquisa.limite : Math.floor(response.data.usuarios.total / pesquisa.limite) + 1;

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
            }, function (error, status) {

                $scope.errors = error.data;
            }).finally(function () {

                $scope.disableButton = false;
                $scope.carregando = false;
            });
        };

        $scope.abrePesquisa = function () {

            if ($scope.filtro == true) {
                $scope.filtro = false;
                $scope.filtrando = false;
            } else {
                $scope.filtro = true;
                $scope.filtrando = true;
            }
        };

        var listarPoliticaSenha = function listarPoliticaSenha() {
            angular.forEach(politicaSenha, function (value) {
                strPoliticaSenha = strPoliticaSenha + '<span style="text-align: left;">- ' + value + "</span><br>";
            });
            $scope.politicaSenha = strPoliticaSenha;
        };

        $scope.limpaPesquisa = function () {
            //ok

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
                controller: function controller($scope) {

                    usuario.senha_alterada = !usuario.senha_alterada;
                    usuario.visualizado_senha_alterada = usuario.senha_alterada;
                    usuario.perfil = usuario.perfil_id;

                    $scope.modalConfirmarSolicitarSenha = function () {
                        UsuarioFactory.solicitarSenhaFunc(usuario).then(function (response) {

                            var icone = '';
                            var texto = '';

                            if (usuario.senha_alterada == false) {
                                icone = 'check';
                                texto = 'Será solicitada a alteração de senha no próximo acesso, clique para alterar.';
                            } else {
                                icone = 'close';
                                texto = 'Não será solicitada a alteração de senha no próximo acesso, clique para alterar.';
                            }

                            usuario.solicitar_senha_icone = icone;
                            usuario.solicitar_senha_texto = texto;
                        }, function (error, status) {
                            $scope.erro = "Não foi possível efetuar a operação.";
                        }).finally(function () {
                            $scope.modalCancelarSolicitarSenha();
                        });
                    };

                    $scope.modalCancelarSolicitarSenha = function () {
                        modalInstance.close();
                    };
                }
            });
        };

        $scope.abreModalNovaSenha = function (usuario, politicaSenha) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: 'novaSenhaModal.html',
                controller: function controller($scope) {

                    $scope.usuario = usuario;
                    $scope.usuario.perfil = usuario.perfil_id;

                    $scope.modal = {
                        sucesso: false,
                        erro: false
                    };

                    $scope.politicaSenha = politicaSenha;

                    $scope.modalConfirmarNovaSenha = function () {
                        UsuarioFactory.novaSenhaFunc($scope.usuario).then(function (response) {
                            $scope.modal.sucesso = true;
                            $scope.modal.erro = false;
                            $scope.success = response.data.success;
                        }, function (error, status) {
                            $scope.modal.erro = true;
                            $scope.modal.sucesso = false;
                            if (Object.prototype.toString.call(error.data) === '[object Array]') {
                                var errors = [];
                                angular.forEach(error.data, function (value) {
                                    errors.push(value);
                                });
                            } else {
                                errors = error.data;
                            }

                            $scope.errors = errors;
                        }).finally(function () {});
                    };

                    $scope.modalCancelarNovaSenha = function () {
                        modalInstance.close();
                    };
                }
            });
        };

        $scope.onGetPage = function (pageNumber) {
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;
                $scope.pesquisaUsuario($scope.filter);
            }
        };

        $scope.defineFiltro = function () {
            var _column = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            //ok           
            if (_column != $scope.filter.coluna) {
                $scope.filter.coluna = _column;
            }

            $scope.filter.ordem = $scope.filter.ordem == 'asc' ? 'desc' : 'asc';
            $scope.pesquisaUsuario($scope.filter);
        };

        $scope.setSelect = function () {
            if ($cookies.get('configuracao_usuario_perfil') != "null") {
                var indexObj = 0;

                angular.forEach($scope.perfis, function (value) {
                    if (value.id == $cookies.get('configuracao_usuario_perfil')) {
                        indexObj = $scope.perfis.indexOf(value);
                    }
                });

                $scope.filter.perfil = $scope.perfis[indexObj];
            } else {
                $scope.filter.perfil = perfis[0];
            }
        };

        //inicia controller
        init();
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();

/***/ }),

/***/ 247:
/***/ (function(module, exports) {

(function () {

	'use strict';

	angular.module('app').controller('editarUsuarioCtrl', ['$scope', '$http', 'UsuarioFactory', function ($scope, $http, UsuarioFactory) {

		var usuario = _usuario;
		var perfis = _perfis;
		var funcionarios = _funcionarios;
		var func_selected = _func_selected;
		var perf_selected = _perf_selected;

		var init = function init() {

			$scope.usuario = {};
			$scope.perfis = perfis;
			$scope.funcionarios = funcionarios;
			$scope.func_selected = func_selected;
			$scope.perf_selected = perf_selected;
			listarUsuario();
		};

		var listarUsuario = function listarUsuario() {

			$scope.usuario = usuario;
			$scope.usuario.funcionario = $scope.func_selected[0];
			$scope.usuario.perfil = $scope.perf_selected[0];
		};

		var limparCampos = function limparCampos() {

			$scope.usuario = {};
		};

		$scope.atualizar = function (usuario) {

			usuario.nome = usuario.nome.toUpperCase();
			usuario.usuario = usuario.usuario.toLowerCase();

			$scope.form.$valid = false;
			$scope.success = false;
			$scope.errors = false;

			UsuarioFactory.atualizarUsuario(usuario, usuario.id).then(function mySuccess(response) {

				$scope.success = response.data.mensagem;
				$scope.url = "../" + response.data.id + "";
			}, function (error) {

				$scope.errors = error.data;
			});
		};

		//inicia o controller
		init();
	}]);
})();

/***/ }),

/***/ 248:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('ParametroFactory', ['$http', function ($http) {

        var _cadastrarParametro = function _cadastrarParametro(param) {
            return $http.post("/configuracao/sistema/parametro", param);
        };

        var _pesquisaParametro = function _pesquisaParametro(param) {
            return $http.post("/configuracao/sistema/parametro/search", param);
        };

        var _atualizarParametro = function _atualizarParametro(param) {
            return $http.put("/configuracao/sistema/parametro/" + param.id, param);
        };

        return {
            cadastrarParametro: _cadastrarParametro,
            pesquisaParametro: _pesquisaParametro,
            atualizarParametro: _atualizarParametro
        };
    }]);
})();

/***/ }),

/***/ 249:
/***/ (function(module, exports) {

(function () {

  'use strict';

  angular.module('app').controller('cadastrarParametroCtrl', ['$scope', '$http', 'ParametroFactory', function ($scope, $http, ParametroFactory) {

    var grupos = _grupos;
    var tipos = _tipos;

    var init = function init() {

      $scope.parametro = {};
      listarGrupos();
      listarTipos();
    };

    var listarGrupos = function listarGrupos() {
      $scope.grupos = grupos;
    };

    var listarTipos = function listarTipos() {
      $scope.tipos = tipos;
    };

    $scope.cadastrar = function (parametro) {

      $scope.form.$valid = false;
      $scope.success = false;
      $scope.errors = false;

      parametro.nome = parametro.nome.toUpperCase();

      ParametroFactory.cadastrarParametro(parametro).then(function (response) {

        $scope.success = response.data.mensagem;
        limparCampos();
      }, function (error) {

        $scope.errors = error.data;
      }).finally(function () {

        $scope.form.$valid = true;
      });
    };

    var limparCampos = function limparCampos() {

      $scope.parametro = {};
    };

    $scope.keyPress = function (event) {
      var keys = {
        'backspace': 8, 'del': 46,
        '0': 48, '1': 49, '2': 50, '3': 51, '4': 52, '5': 53, '6': 54, '7': 55, '8': 56, '9': 57
      };

      for (var index in keys) {
        if (!keys.hasOwnProperty(index)) continue;
        if (event.charCode == keys[index] || event.keyCode == keys[index]) {
          return;
        }
      }

      event.preventDefault();
    };

    //inicializa controller
    init();
  }]);
})();

/***/ }),

/***/ 250:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('listarParametroCtrl', ['$scope', '$http', 'ParametroFactory', '$cookies', function ($scope, $http, ParametroFactory, $cookies) {

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

            /**
             * Objeto utilizado para definir o estado da paginação.
             */
        };$scope.paginacao = {
            de: 0,
            ate: 0,
            total: 0,
            pagina: 0
        };

        var init = function init() {

            grupos.unshift({ id: '', nome: '' });
            tipos.unshift({ id: '', nome: '' });

            $scope.grupos = grupos;
            $scope.tipos = tipos;

            if (verificaCookies() == false) {
                $scope.filter.nome = nome;
                $scope.filter.grupo = grupo;
                $scope.filter.tipo = tipo;
            } else {
                // Se o cookie retornar null é isNaN
                $scope.filtro = $cookies.get('config_sistema_parametro_nome') == "" && (isNaN(parseInt($cookies.get('config_sistema_parametro_grupo'))) || $cookies.get('config_sistema_parametro_grupo') == "0") && (isNaN(parseInt($cookies.get('config_sistema_parametro_tipo'))) || $cookies.get('config_sistema_parametro_tipo') == "0") ? false : true;

                $scope.filter.nome = $cookies.get('config_sistema_parametro_nome');
            }

            $scope.pesquisaParametro($scope.filter);
        };

        var verificaCookies = function verificaCookies() {
            if (typeof $cookies.get('config_sistema_parametro_nome') == "undefined" && typeof $cookies.get('config_sistema_parametro_grupo') == "undefined" && typeof $cookies.get('config_sistema_parametro_tipo') == "undefined") {
                return false;
            }

            return true;
        };

        $scope.verificaCookiesGrupo = function () {
            $scope.filter.grupo = $scope.grupos[setSelect('config_sistema_parametro_grupo', $scope.grupos)];
        };

        $scope.verificaCookiesTipo = function () {
            $scope.filter.tipo = $scope.tipos[setSelect('config_sistema_parametro_tipo', $scope.tipos)];
        };

        var setSelect = function setSelect(cookie, lista) {
            if (isNaN(parseInt($cookies.get(cookie))) || $cookies.get(cookie) == "0") {
                return 0;
            } else {
                var indexObj = 0;

                angular.forEach(lista, function (value) {
                    if (value.id == $cookies.get(cookie)) {
                        indexObj = lista.indexOf(value);
                    }
                });

                return indexObj;
            }
        };

        $scope.defineFiltro = function () {
            var _column = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            //ok
            if (_column != $scope.filter.coluna) {
                $scope.filter.coluna = _column;
            }

            $scope.filter.ordem = $scope.filter.ordem == 'asc' ? 'desc' : 'asc';
            $scope.pesquisaParametro($scope.filter);
        };

        $scope.onGetPage = function (pageNumber) {
            //ok
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;

                $scope.pesquisaParametro($scope.filter);
            }
        };

        $scope.pesquisaParametro = function (pesquisa) {

            $scope.disableButton = true;
            $scope.carregando = true;

            ParametroFactory.pesquisaParametro($scope.filter).then(function mySuccess(response) {
                // Define a lista de parametros que será exibida na view.
                $scope.lista = response.data.parametros.data;

                var de = response.data.parametros.current_page == 1 ? 1 : response.data.parametros.total < pesquisa.limite ? (response.data.parametros.current_page - 1) * pesquisa.limite + response.data.parametros.total : (response.data.parametros.current_page - 1) * pesquisa.limite + 1;

                var ate = pesquisa.limite == 0 ? response.data.parametros.total : response.data.parametros.total < pesquisa.limite ? response.data.parametros.total : response.data.parametros.current_page * pesquisa.limite;

                var totalDePaginas = response.data.parametros.total % pesquisa.limite === 0 ? response.data.parametros.total / pesquisa.limite : Math.floor(response.data.parametros.total / pesquisa.limite) + 1;

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
            }, function (error, status) {
                $scope.errors = error.data;
            }).finally(function () {
                $scope.disableButton = false;
                $scope.carregando = false;
            });
        };

        $scope.abrePesquisa = function () {
            if ($scope.filtro == true) {
                $scope.filtro = false;
                $scope.filtrando = false;
            } else {
                $scope.filtro = true;
                $scope.filtrando = true;
            }
        };

        $scope.limpaPesquisa = function () {

            $scope.filter.nome = "";
            $scope.filter.grupo = grupos[0];
            $scope.filter.tipo = tipos[0];

            $scope.pesquisaParametro($scope.filter);
        };

        //incializa controller
        init();
    }]);
})();

/***/ }),

/***/ 251:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('editarParametroCtrl', ['$scope', '$http', 'ParametroFactory', function ($scope, $http, ParametroFactory) {

        var parametro = _parametro;
        var grupos = _grupos;
        var tipos = _tipos;
        var grupo_selected = _grupo_selected;
        var tipo_selected = _tipo_selected;

        var init = function init() {

            $scope.parametro = {};
            $scope.grupos = grupos;
            $scope.tipos = tipos;
            $scope.grupo_selected = grupo_selected;
            $scope.tipo_selected = tipo_selected;
            listarparametro();
        };

        var listarparametro = function listarparametro() {

            if (parametro.valor_booleano != null) {
                if (parametro.valor_booleano == true) {
                    parametro.valor = 'VERDADEIRO';
                } else {
                    parametro.valor = 'FALSO';
                }
            } else if (parametro.valor_texto != null) {
                parametro.valor = parametro.valor_texto;
            } else if (parametro.valor_numero != null) {
                parametro.valor = parametro.valor_numero;
            }

            $scope.visualizar_editar = parametro.editar;
            parametro.editar = '' + parametro.editar;

            $scope.parametro = parametro;
            $scope.parametro.grupo = $scope.grupo_selected[0];
            $scope.parametro.tipo = $scope.tipo_selected[0];
        };

        $scope.atualizar = function (parametro) {

            parametro.nome = parametro.nome.toUpperCase();

            $scope.form.$valid = false;
            $scope.success = false;
            $scope.errors = false;

            ParametroFactory.atualizarParametro(parametro).then(function mySuccess(response) {

                $scope.success = response.data.mensagem;
            }, function (error) {

                $scope.errors = error.data;
            });
        };

        $scope.keyPress = function (event) {
            var keys = {
                'backspace': 8, 'del': 46,
                '0': 48, '1': 49, '2': 50, '3': 51, '4': 52, '5': 53, '6': 54, '7': 55, '8': 56, '9': 57
            };

            for (var index in keys) {
                if (!keys.hasOwnProperty(index)) continue;
                if (event.charCode == keys[index] || event.keyCode == keys[index]) {
                    return;
                }
            }

            event.preventDefault();
        };

        //inicia o controller
        init();
    }]);
})();

/***/ }),

/***/ 252:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('categoriaFactory', ['$http', function ($http) {

        var _salveCategoria = function _salveCategoria() {
            return $http.post("configuracao/ticket/categoria/store", param);
        };

        return {

            cadastraCategoria: _salveCategoria

        };
    }]);
})();

/***/ }),

/***/ 253:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('categoriaCtrl', ['$scope', '$http', 'categoriaFactory', 'serviceCategoriasCtrl', '$uibModal', '$sce', function ($scope, $http, categoriaFactory, serviceCategoriasCtrl, $uibModal, $sce) {

        var departamentos = _departamentos;
        var departamento = _departamento;
        var categorias_filha = _categorias_filha;
        var categorias_pai = _categorias_pai;

        var init = function init() {

            $scope.departamentos = departamentos;
            $scope.categorias_filha = categorias_filha;
            $scope.categorias_pai = categorias_pai;

            var indexObj = 0;

            if (departamento != false) {
                angular.forEach($scope.departamentos, function (value) {
                    if (value.id == departamento) {
                        indexObj = $scope.departamentos.indexOf(value);
                    }
                });

                $scope.categoria_departamento = $scope.departamentos[indexObj];
            }
        };

        $scope.update = function () {
            document.departamento_categoria.action = "/configuracao/ticket/categoria/departamento/" + $scope.categoria_departamento.id;
            document.departamento_categoria.submit();
        };

        $scope.store = function () {
            document.categoria.action = "/configuracao/ticket/categoria/store/";
            document.categoria.submit();
        };

        $scope.modalCategoria = function () {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'categoriaModal.html',
                resolve: {
                    categorias: function categorias() {
                        //return funcionarios;
                    }
                },
                controller: function controller($scope, $uibModalInstance, serviceCategoriasCtrl) {

                    $scope.modalCancelarCategoria = function () {
                        modalInstance.close();
                    };
                }
            });
        };

        $scope.modalConfirmCategoria = function (elemento, entidade) {

            $scope.elemento = elemento;
            var mensagem = "Confirma a exclusão da categoria " + elemento.nome + " ?";

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: '/templates/modal-confirm.html',
                scope: $scope,
                controller: function controller($scope) {

                    $scope.mensagem = mensagem;
                    $scope.errors = false;

                    $scope.alterarStatus = function () {
                        $http({
                            method: "DELETE",
                            url: "/" + entidade + "/" + elemento.id
                        }).then(function mySuccess(response) {

                            document.departamento_categoria.action = "/configuracao/ticket/categoria/departamento/" + $scope.categoria_departamento.id;
                            document.departamento_categoria.submit();
                        }, function (data, status, headers, config) {

                            $scope.errors = data.data.errors[0];
                        });
                    };

                    $scope.modalCancelarAlterarStatus = function () {
                        modalInstance.close();
                    };
                }
            });
        };

        $scope.modalEditCategoria = function (_elemento) {

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'editarCategoriaModal.html',
                scope: $scope,
                resolve: {
                    elemento: function elemento() {
                        return _elemento;
                    }
                },
                controller: function controller($scope, $uibModalInstance, elemento) {

                    $scope.elemento = elemento;
                    $scope.modal_nome = elemento.nome;
                    $scope.modal_descricao = elemento.descricao;
                    $scope.modal_dicas = elemento.dicas;
                    $scope.categoria_id = elemento.id;

                    $scope.permite_pai = !isNaN(parseInt(elemento.ticket_categoria_id));

                    $scope.categorias_pai_new = $scope.categorias_pai;

                    var indexObj = 0;
                    if ($scope.permite_pai) {

                        angular.forEach($scope.categorias_pai, function (value) {
                            if (value.id == elemento.ticket_categoria_id) {
                                indexObj = $scope.categorias_pai.indexOf(value);
                            }
                        });

                        $scope.modal_pai = $scope.categorias_pai[indexObj];
                    }

                    var obj = 0;

                    if (!$scope.permite_pai) {
                        angular.forEach($scope.categorias_filha, function (value) {
                            if (value.ticket_categoria_id == elemento.id) {
                                obj = 1;
                            }
                        });

                        if (obj == 0) {
                            $scope.permite_pai = true;
                        }
                    }

                    $scope.modalExcluirCategoria = function () {
                        document.categoria.action = "/configuracao/ticket/categoria/destroy/" + id;
                        document.categoria.submit();
                    };

                    $scope.modalCancelarCategoria = function () {
                        modalInstance.close();
                    };
                }
            });
        };

        init();
    }]);
})();

/***/ }),

/***/ 254:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceCategoriasCtrl', ['$uibModal', function ($uibModal) {
        this.modal = function (funcionarios) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'categoriaModal.html',
                resolve: {
                    funcionarios: function funcionarios() {
                        //return funcionarios;
                    }
                },
                controller: function controller($scope, $uibModalInstance, funcionarios) {}
            });
        };
    }]);
})();

/***/ }),

/***/ 255:
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

(function () {

    'use strict';

    angular.module('app').controller('acaoCtrl', ['$scope', '$http', 'acaoFactory', 'serviceAcaoCtrl', '$uibModal', '$sce', function ($scope, $http, acaoFactory, serviceAcaoCtrl, $uibModal, $sce) {

        var departamentos = _departamentos;
        var departamento = _departamento;
        var icones = _icones;
        var status = _status;
        var acoes = _acoes;

        var init = function init() {
            $scope.departamentos = departamentos;
            $scope.icones = icones;
            $scope.status = status;
            $scope.acoes = acoes;

            $scope.campos = [{ value: 'assunto', nome: 'ASSUNTO' }, { value: 'avaliacao', nome: 'AVALIAÇÃO' }, { value: 'campos_adicionais', nome: 'CAMPOS ADICIONAIS' }, { value: 'categoria', nome: 'CATEGORIA' }, { value: 'data_notificacao', nome: 'DATA NOTIFICAÇÃO' }, { value: 'data_previsao', nome: 'DATA PREVISÃO' }, { value: 'prioridade', nome: 'PRIORIDADE' }, { value: 'responsavel', nome: 'RESPONSÁVEL' }, { value: 'solicitante', nome: 'SOLICITANTE' }];

            var indexObj = 0;

            if (departamento != false) {
                angular.forEach($scope.departamentos, function (value) {
                    if (value.id == departamento) {
                        indexObj = $scope.departamentos.indexOf(value);
                    }
                });

                $scope.acao_departamento = $scope.departamentos[indexObj];
            }

            /*
             * Drag and Drop
             * http://marceljuenemann.github.io/angular-drag-and-drop-lists/demo/#/simple
             */
            $scope.models = {
                selected: null,
                list: {}
            };

            $scope.models.list = $scope.acoes;
            $scope.list = $scope.models.list;
        };

        $scope.atualizaAcoes = function () {

            acaoFactory.atualizaAcoes($scope.models.list).then(function mySuccess(response) {
                //sucesso
            }, function (error) {
                $window.location.reload();
            });
        };

        $scope.update = function () {
            document.departamento_acao.action = "/configuracao/ticket/acao/departamento/" + $scope.acao_departamento.id;
            document.departamento_acao.submit();
        };

        $scope.store = function () {
            document.acao.action = "/configuracao/ticket/acao/store/";
            document.acao.submit();
        };

        $scope.abrirModal = function (tipo, acao) {
            $scope.modal_tipo = tipo;

            /**
             * Variaveis de inputs do modal
             */
            $scope.status_atual = {
                status: []
            };

            $scope.status_novo = {
                status: []
            };

            $scope.campos_adicionais = {
                campos: []
            };

            $scope.mensagem = {};

            $scope.nome = '';
            $scope.modal_icone = '';
            $scope.icone_view = '';
            $scope.modal_icone_view = '';
            $scope.descricao = '';
            $scope.solicitante_executa = false;
            $scope.responsavel_executa = false;
            $scope.trata_executa = false;

            if (tipo == 'cadastrar') {
                $scope.modal_title = 'Adicionar ação';

                $scope.mensagem.tipo_mensagem = {
                    interacao: false,
                    nota_interna: false
                };

                $scope.modal_action = '/configuracao/ticket/acao/store';
            } else if (tipo == 'editar') {
                $scope.modal_title = 'Editar ação';

                angular.forEach($scope.status, function (value) {
                    if (JSON.parse(acao.status_atual).indexOf(value.id) != -1) {
                        $scope.status_atual.status.push(value);
                    }

                    if (JSON.parse(acao.status_novo).indexOf(value.id) != -1) {
                        $scope.status_novo.status.push(value);
                    }
                });

                angular.forEach($scope.campos, function (value) {
                    if (acao.campos.indexOf(value.value) != -1) {
                        $scope.campos_adicionais.campos.push(value);
                    }
                });

                $scope.acao_id = acao.id;
                $scope.nome = acao.nome;
                $scope.modal_icone = acao.icone;
                $scope.icone_view = acao.icone;
                $scope.modal_icone_view = acao.icone_nome;
                $scope.descricao = acao.descricao;
                $scope.solicitante_executa = acao.solicitante_executa;
                $scope.responsavel_executa = acao.responsavel_executa;
                $scope.trata_executa = acao.trata_executa;

                $scope.mensagem.tipo_mensagem = {
                    interacao: acao.interacao,
                    nota_interna: acao.nota_interna
                };

                $scope.modal_action = '/configuracao/ticket/acao/update/' + $scope.acao_departamento.id;
            }

            $scope.modalAcao();
        };

        $scope.modalAcao = function () {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                scope: $scope,
                templateUrl: 'acaoModal.html',
                resolve: {},
                controller: function controller($scope, $sce) {
                    $scope.trustAsHtml = function (string) {
                        return $sce.trustAsHtml(string);
                    };

                    $scope.setIcone = function ($icone) {
                        $scope.icone_view = $icone.icone;
                        $scope.modal_icone_view = $icone.nome;
                        $scope.modal_icone = $icone.icone;
                    };

                    $scope.modalCancelarAcao = function () {
                        modalInstance.close();
                    };

                    $scope.modalConfirmAcao = function () {
                        var _$uibModal$open;

                        var modalInstanceConfirme = $uibModal.open((_$uibModal$open = {
                            animation: true,
                            ariaLabelledBy: 'modal-title-bottom',
                            ariaDescribedBy: 'modal-body-bottom',
                            size: 'lg',
                            scope: $scope,
                            templateUrl: '/templates/modal-confirm.html'
                        }, _defineProperty(_$uibModal$open, 'scope', $scope), _defineProperty(_$uibModal$open, 'controller', function controller($scope) {
                            $scope.mensagem = "Confirma a exclusão da ação " + $scope.nome + " ?";
                            $scope.errors = false;

                            $scope.alterarStatus = function () {
                                $http({
                                    method: "DELETE",
                                    url: "/configuracao/ticket/acao/destroy/" + $scope.acao_id
                                }).then(function mySuccess(response) {
                                    document.departamento_acao.submit();
                                }, function (data, status, headers, config) {
                                    $scope.errors = data.data.errors[0];
                                });
                            };

                            $scope.modalCancelarAlterarStatus = function () {
                                modalInstanceConfirme.close();
                            };
                        }), _$uibModal$open));
                    };
                }
            });
        };

        init();
    }]);
})();

/***/ }),

/***/ 256:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('acaoFactory', ['$http', function ($http) {
        var _salveCategoria = function _salveCategoria() {
            return $http.post("configuracao/ticket/acao/store", param);
        };

        var _atualizaAcoes = function _atualizaAcoes(param) {
            return $http.post("/configuracao/ticket/acao/storeMultiplo", param);
        };

        return {

            cadastraCategoria: _salveCategoria,
            atualizaAcoes: _atualizaAcoes

        };
    }]);
})();

/***/ }),

/***/ 257:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceAcaoCtrl', ['$uibModal', function ($uibModal) {
        this.modal = function (funcionarios) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'acaoModal.html',
                resolve: {
                    funcionarios: function funcionarios() {
                        //return funcionarios;
                    }
                },
                controller: function controller($scope, $uibModalInstance, funcionarios) {}
            });
        };
    }]);
})();

/***/ }),

/***/ 258:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('gatilhoCtrl', ['$scope', '$http', 'gatilhoFactory', 'serviceGatilhoCtrl', '$uibModal', '$sce', function ($scope, $http, gatilhoFactory, serviceGatilhoCtrl, $uibModal, $sce) {

        var departamentos = _departamentos;
        var departamento = _departamento;
        var gatilhos = _gatilhos;

        var responsaveis = _responsaveis;
        var cargos = _cargos;
        var usuarios = _usuarios;
        var statuses = _status;
        var gatilhos = _gatilhos;

        var init = function init() {

            $scope.departamentos = departamentos;
            $scope.gatilhos = gatilhos;

            var indexObj = 0;

            if (departamento != false) {
                angular.forEach($scope.departamentos, function (value) {
                    if (value.id == departamento) {
                        indexObj = $scope.departamentos.indexOf(value);
                    }
                });

                $scope.gatilho_departamento = $scope.departamentos[indexObj];
            }

            $scope.models = {
                selected: null,
                list: {}
            };

            $scope.models.list = $scope.gatilhos;
            $scope.list = $scope.models.list;
        };

        $scope.atualizaGatilhos = function () {

            gatilhoFactory.atualizaGatilhos($scope.models.list).then(function mySuccess(response) {
                //sucesso
            }, function (error) {
                $window.location.reload();
            });
        };

        $scope.update = function () {
            document.departamento_gatilho.action = "/configuracao/ticket/gatilho/departamento/" + $scope.gatilho_departamento.id;
            document.departamento_gatilho.submit();
        };

        $scope.store = function () {
            document.gatilho.action = "/configuracao/ticket/gatilho/store/";
            document.gatilho.submit();
        };

        $scope.modalGatilho = function (tipo, _elemento) {
            $scope.url = '/configuracao/ticket/gatilho/store';
            $scope.putedit = false;

            $scope.funcao = 'false';
            $scope.teste = 'false';

            $scope.nomeGatilho = '';
            $scope.decricaoGatilho = '';
            $scope.statuscombo = '';
            $scope.responsavelcombo = '';
            $scope.solicitantenotificacao = 'false';
            $scope.responsavelnotificacao = 'false';
            $scope.datateste = '';
            $scope.acaodataaltera = '';

            $scope.responsaveis = responsaveis;
            $scope.statuses = statuses;

            if (tipo == 'editar') {
                $scope.putedit = 'put';
                $scope.url = '/configuracao/ticket/gatilho/update/' + _elemento.id;
                $scope.elemento = _elemento;

                $scope.nomeGatilho = _elemento.nome;
                $scope.decricaoGatilho = _elemento.descricao;

                $scope.quando = JSON.parse(_elemento.quanto_executar);

                if ($scope.quando.status != undefined) {
                    $scope.teste = 'status';
                    var indexObj = 0;

                    angular.forEach($scope.statuses, function (value) {
                        if (value.id == $scope.quando.status) {
                            indexObj = $scope.statuses.indexOf(value);
                        }
                    });

                    $scope.statuscombo = $scope.statuses[indexObj];
                } else if ($scope.quando.dt_notificacao != undefined) {
                    $scope.teste = 'not';
                } else if ($scope.quando.responsavel != undefined) {
                    $scope.teste = 'res';
                    var indexObj = 0;

                    angular.forEach($scope.responsaveis, function (value) {
                        if (value.id == $scope.quando.responsavel) {
                            indexObj = $scope.responsaveis.indexOf(value);
                        }
                    });

                    $scope.responsavelcombo = $scope.responsaveis[indexObj];
                }

                $scope.acao = JSON.parse(_elemento.acao);

                if ($scope.acao.notificacao != undefined && $scope.quando.dt_notificacao == undefined) {
                    $scope.funcao = 'notif';

                    $scope.mensagemacao = $scope.acao.notificacao.mensagem;

                    if ($scope.acao.notificacao.solicitante != false) {
                        $scope.solicitantenotificacao = true;
                    }
                    if ($scope.acao.notificacao.responsavel != false) {
                        $scope.responsavelnotificacao = true;
                    }
                    if ($scope.acao.notificacao.departamento != false) {
                        $scope.departamentonotificacao = true;

                        var temporario = [];

                        angular.forEach($scope.acao.notificacao.departamento, function (value) {

                            temporario.push('' + value + '');
                        });

                        $scope.deplist = temporario;
                    }
                    if ($scope.acao.notificacao.cargo != false) {
                        $scope.cargonotificacao = true;
                        var temporario = [];

                        angular.forEach($scope.acao.notificacao.cargo, function (value) {

                            temporario.push('' + value + '');
                        });

                        $scope.carglist = temporario;
                    }

                    if ($scope.acao.notificacao.usuario != false) {
                        $scope.usuarionotificacao = true;
                        var temporario = [];

                        angular.forEach($scope.acao.notificacao.usuario, function (value) {

                            temporario.push('' + value + '');
                        });

                        $scope.usualist = temporario;
                    }
                } else if ($scope.acao.responsavel != undefined) {
                    $scope.funcao = 'respon';
                    var indexObj = 0;

                    angular.forEach($scope.responsaveis, function (value) {
                        if (value.id == $scope.acao.responsavel) {
                            indexObj = $scope.responsaveis.indexOf(value);
                        }
                    });

                    $scope.responsavelacaocombo = $scope.responsaveis[indexObj];
                } else if ($scope.quando.dt_notificacao != undefined) {
                    $scope.funcao = 'false';

                    $scope.notmesagem = $scope.acao.notificacao.mensagem;
                } else {
                    $scope.funcao = 'data';

                    if ($scope.acao.dt_fechamento != undefined) {
                        $scope.datateste = 'dt_fechamento';
                        $scope.acaodataaltera = $scope.acao.dt_fechamento;
                    } else if ($scope.acao.dt_notificacao != undefined) {
                        $scope.datateste = 'dt_notificacao';
                        $scope.acaodataaltera = $scope.acao.dt_notificacao;
                    } else if ($scope.acao.dt_previsao != undefined) {
                        $scope.datateste = 'dt_previsao';
                        $scope.acaodataaltera = $scope.acao.dt_previsao;
                    } else if ($scope.acao.dt_resolucao != undefined) {
                        $scope.datateste = 'dt_resolucao';
                        $scope.acaodataaltera = $scope.acao.dt_resolucao;
                    }
                }
            }

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'gatilhoModal.html',
                scope: $scope,
                resolve: {
                    elemento: function elemento() {
                        return _elemento;
                    }
                },
                controller: function controller($scope, $uibModalInstance, elemento) {

                    $scope.excluir = false;

                    if (tipo == 'editar') {
                        $scope.excluir = true;
                    }

                    $scope.modalCancelarGatilho = function () {
                        modalInstance.close();
                    };

                    $scope.modalExcluirCategoria = function () {
                        document.categoria.action = "/configuracao/ticket/categoria/destroy/" + id;
                        document.categoria.submit();
                    };
                }
            });
        };

        $scope.modalConfirmaGatilho = function (entidade) {

            var mensagem = "Confirma a exclusão da gatilho " + $scope.elemento.nome + " ?";

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: '/templates/modal-confirm.html',
                scope: $scope,
                controller: function controller($scope) {

                    $scope.mensagem = mensagem;
                    $scope.errors = false;

                    $scope.alterarStatus = function () {
                        $http({
                            method: "DELETE",
                            url: "/" + entidade + "/" + $scope.elemento.id
                        }).then(function mySuccess(response) {

                            document.departamento_gatilho.action = "/configuracao/ticket/gatilho/departamento/" + $scope.gatilho_departamento.id;
                            document.departamento_gatilho.submit();
                        }, function (data, status, headers, config) {

                            $scope.errors = data.data.errors[0];
                        });
                    };

                    $scope.modalCancelarAlterarStatus = function () {
                        modalInstance.close();
                    };
                }
            });
        };

        init();
    }]);
})();

/***/ }),

/***/ 259:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('gatilhoFactory', ['$http', function ($http) {

        var _salveCategoria = function _salveCategoria() {
            return $http.post("configuracao/ticket/gatilho/store", param);
        };

        var _atualizaGatilhos = function _atualizaGatilhos(param) {
            return $http.post("/configuracao/ticket/gatilho/storeMultiplo", param);
        };

        return {

            cadastraCategoria: _salveCategoria,
            atualizaGatilhos: _atualizaGatilhos

        };
    }]);
})();

/***/ }),

/***/ 260:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceGatilhoCtrl', ['$uibModal', function ($uibModal) {
        this.modal = function (funcionarios) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'Modal.html',
                resolve: {
                    funcionarios: function funcionarios() {
                        //return funcionarios;
                    }
                },
                controller: function controller($scope, $uibModalInstance, funcionarios) {}
            });
        };
    }]);
})();

/***/ }),

/***/ 261:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('CamposAdicionaisFactory', ['$http', function ($http) {

        var _addTipoCampo = function _addTipoCampo(tipo) {
            return $http.post("campo_adicional/tipo/" + tipo);
        };

        var _getCampoAdicional = function _getCampoAdicional(id) {
            return $http.post("/configuracao/ticket/campo_adicional/" + id);
        };

        var _excluirCampoAdicional = function _excluirCampoAdicional(id) {
            return $http.delete("/configuracao/ticket/campo_adicional/" + id);
        };

        //PRIORIDADE
        var _getPrioridade = function _getPrioridade(id) {
            return $http.post("ticket/campo_adicional/departamento/" + id + "/prioridade");
        };

        var _addCampoAdicionalPrioridade = function _addCampoAdicionalPrioridade(param) {
            //ok            
            return $http.post("/configuracao/ticket/campo_adicional/prioridade", param);
        };

        var _excluirCampoAdicionalPrioridade = function _excluirCampoAdicionalPrioridade(id) {
            return $http.delete("/configuracao/ticket/campo_adicional/prioridade/" + id);
        };

        //STATUS
        var _getStatus = function _getStatus(id) {
            return $http.post("ticket/campo_adicional/departamento/" + id + "/status");
        };

        var _addCampoAdicionalStatus = function _addCampoAdicionalStatus(param) {
            //ok            
            return $http.post("/configuracao/ticket/campo_adicional/status", param);
        };

        var _excluirCampoAdicionalStatus = function _excluirCampoAdicionalStatus(id) {
            return $http.delete("/configuracao/ticket/campo_adicional/status/" + id);
        };

        return {
            addTipoCampo: _addTipoCampo,
            excluirCampoAdicional: _excluirCampoAdicional,
            getCampoAdicional: _getCampoAdicional,

            //prioridade
            getPrioridade: _getPrioridade,
            addCampoAdicionalPrioridade: _addCampoAdicionalPrioridade,
            excluirCampoAdicionalPrioridade: _excluirCampoAdicionalPrioridade,

            //status
            getStatus: _getStatus,
            addCampoAdicionalStatus: _addCampoAdicionalStatus,
            excluirCampoAdicionalStatus: _excluirCampoAdicionalStatus

        };
    }]);
})();

/***/ }),

/***/ 262:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('camposAdicionaisCtrl', ['$scope', '$http', 'CamposAdicionaisFactory', 'serviceCamposAdicionaisCtrl', '$uibModal', 'serviceExcluirCamposAdicionaisCtrl', '$sce', 'serviceModalCampoAdicionalPrioridadeCtrl', 'serviceModalCampoAdicionalStatusCtrl', function ($scope, $http, CamposAdicionaisFactory, serviceCamposAdicionaisCtrl, $uibModal, serviceExcluirCamposAdicionaisCtrl, $sce, serviceModalCampoAdicionalPrioridadeCtrl, serviceModalCampoAdicionalStatusCtrl) {

        var departamentos = _departamentos;
        var departamentos_selected = _departamentos_selected;

        var init = function init() {

            $scope.campos_adicionais = [];
            $scope.campo_adicional = [];
            $scope.departamentos_selected = false;
            $scope.campos_adicionais_lista = [];
            $scope.departamentos = _departamentos;
            $scope.departamentos_selected = _departamentos_selected;

            $scope.campos = [];
            listar();
        };

        var listar = function listar() {
            if (departamentos_selected != false) {

                var campos_adicionais_lista = [];
                var campos_adicionais_texto = [];
                var campos_adicionais_longo = [];

                $scope.campo_adicional.departamento = departamentos_selected[0];
                $scope.departamentos_selected = true;

                angular.forEach(campos_adicionais, function (value, key) {
                    if (value['template'] == 'LISTA') {

                        var elementos = JSON.parse(value.dados);

                        campos_adicionais_lista.push({
                            'id': value.id,
                            'nome': value.nome.toLowerCase().charAt(0).toUpperCase() + value.nome.toLowerCase().slice(1),
                            'descricao': value.descricao,
                            'padrao': elementos[value.padrao - 1],
                            'dados': elementos
                        });
                    } else if (value['template'] == 'TEXTO') {

                        campos_adicionais_texto.push({
                            'id': value.id,
                            'nome': value.nome.toLowerCase().charAt(0).toUpperCase() + value.nome.toLowerCase().slice(1),
                            'descricao': value.descricao,
                            'padrao': value.padrao
                        });
                    } else if (value['template'] == 'TEXTO LONGO') {

                        campos_adicionais_longo.push({
                            'id': value.id,
                            'nome': value.nome.toLowerCase().charAt(0).toUpperCase() + value.nome.toLowerCase().slice(1),
                            'descricao': value.descricao,
                            'padrao': value.padrao
                        });
                    }
                });

                $scope.campos_adicionais_lista = campos_adicionais_lista;
                $scope.campos_adicionais_texto = campos_adicionais_texto;
                $scope.campos_adicionais_longo = campos_adicionais_longo;
            }
        };

        $scope.setSelect = function () {
            var indexObj = 0;

            angular.forEach($scope.perfis, function (value) {
                if (value.id == $cookies.get('configuracao_usuario_perfil')) {
                    indexObj = $scope.perfis.indexOf(value);
                }
            });

            $scope.filter.perfil = $scope.perfis[indexObj];
        };

        //Campos Adiconais
        $scope.modalAddCamposAdicionais = function (tipo) {
            serviceCamposAdicionaisCtrl.modalAdd(tipo, $scope);
        };

        $scope.modalEditarCamposAdicionais = function (id) {
            serviceCamposAdicionaisCtrl.modalEdit(id, $scope);
        };

        $scope.modalExcluirCamposAdicionais = function (id) {
            serviceExcluirCamposAdicionaisCtrl.modal(id, $scope);
        };

        //Departamento
        $scope.update = function () {
            document.departamento.action = "/configuracao/ticket/campo_adicional/departamento/" + $scope.campo_adicional.departamento.id;
            document.departamento.submit();
        };

        //Status        
        $scope.modalAddCampoAdicionalStatus = function () {
            serviceModalCampoAdicionalStatusCtrl.modalAdd($scope.departamento_id);
        };

        $scope.modalEditarCampoAdicionalStatus = function () {
            serviceModalCampoAdicionalStatusCtrl.modalEditar($scope);
        };

        //Prioridade
        $scope.modalAddCampoAdicionalPrioridade = function () {
            serviceModalCampoAdicionalPrioridadeCtrl.modalAdd($scope.departamento_id);
        };

        $scope.modalEditarCampoAdicionalPrioridade = function () {
            serviceModalCampoAdicionalPrioridadeCtrl.modalEditar($scope);
        };

        //inicializa controller
        init();
    }]);
})();

/***/ }),

/***/ 263:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceCamposAdicionaisCtrl', ['$uibModal', 'CamposAdicionaisFactory', function ($uibModal, CamposAdicionaisFactory) {
        this.modalAdd = function (tipo, $scope) {

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'addCamposAdicionaisModal.html',
                scope: $scope,
                resolve: {
                    id: function id() {
                        return null;
                    }
                },
                controller: function controller($scope, $uibModalInstance, id) {

                    if (tipo == 'lista') {

                        $scope.mostrar_lista = true;
                        $scope.tipo = 'LISTA';
                    } else if (tipo == 'longo') {

                        $scope.longo = true;
                        $scope.tipo = 'TEXTO LONGO';
                    } else if (tipo == 'texto') {

                        $scope.texto = true;
                        $scope.tipo = 'TEXTO';
                    }

                    $scope.lista = [{
                        id: 1,
                        padrao: false
                    }];

                    $scope.lista_input = JSON.stringify($scope.lista);

                    $scope.atualiza = function (id) {

                        angular.forEach($scope.lista, function (value, key) {

                            if (key + 1 == id) {

                                value.padrao = "true";
                            } else {
                                value.padrao = "false";
                            }
                        });

                        $scope.lista_input = JSON.stringify($scope.lista);
                    };

                    $scope.removeItem = function () {
                        var lastItem = $scope.lista.length - 1;
                        $scope.lista.splice(lastItem);
                        $scope.lista_input = JSON.stringify($scope.lista);
                    };

                    $scope.addItem = function () {
                        var newItemNo = $scope.lista.length + 1;
                        $scope.lista.push({
                            'id': newItemNo,
                            'padrao': false
                        });
                        $scope.lista_input = JSON.stringify($scope.lista);
                    };

                    $scope.close = function () {
                        modalInstance.close();
                    };
                }
            });

            modalInstance.opened.then(function () {
                $scope.tipo = true;
            });
        };

        //editar
        this.modalEdit = function (_id, $scope) {

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'editCamposAdicionaisModal.html',
                scope: $scope,
                resolve: {
                    id: function id() {
                        return _id;
                    }
                },
                controller: function controller($scope, $uibModalInstance, id) {

                    $scope.campo = {};

                    CamposAdicionaisFactory.getCampoAdicional(id).then(function mySuccess(response) {

                        $scope.campo.departamento_id = response.data[0].departamento_id;
                        $scope.campo.campo_id = response.data[0].id;
                        $scope.campo.tipo = response.data[0].tipo;
                        $scope.campo.nome = response.data[0].nome;
                        $scope.campo.descricao = response.data[0].descricao;
                        $scope.campo.obrigatorio = '' + response.data[0].obrigatorio + '';
                        $scope.campo.visivel = '' + response.data[0].visivel + '';

                        if (response.data[0].tipo == 'LISTA') {

                            $scope.mostrar_lista_edit = true;

                            $scope.campo.lista = JSON.parse(response.data[0].dados);

                            $scope.lista_input = JSON.stringify($scope.campo.lista);

                            angular.forEach($scope.campo.lista, function (value, key) {

                                if (value.padrao == true) {
                                    value.padrao = 'true';
                                } else if (value.padrao == false) {
                                    value.padrao = 'false';
                                }
                            });
                        } else if (response.data[0].tipo == 'TEXTO LONGO') {

                            $scope.longo_edit = true;
                            $scope.campo.padrao = response.data[0].padrao;
                        } else if (response.data[0].tipo == 'TEXTO') {

                            $scope.texto_edit = true;
                            $scope.campo.padrao = response.data[0].padrao;
                        }
                    }, function (error, status) {
                        window.location = "/configuracao/ticket/campo_adicional/departamento/" + $scope.departamento_id;
                    }).finally(function () {
                        //                       
                    });

                    $scope.removeItem = function () {
                        var lastItem = $scope.campo.lista.length - 1;
                        $scope.campo.lista.splice(lastItem);
                        $scope.lista_input = JSON.stringify($scope.campo.lista);
                    };

                    $scope.addItem = function () {
                        var newItemNo = $scope.campo.lista.length + 1;
                        $scope.campo.lista.push({
                            'id': newItemNo,
                            'padrao': "false"
                        });
                        $scope.lista_input = JSON.stringify($scope.campo.lista);
                    };

                    $scope.close = function () {
                        modalInstance.close();
                    };

                    $scope.atualiza = function (id) {

                        angular.forEach($scope.campo.lista, function (value, key) {

                            if (key + 1 == id) {

                                value.padrao = "true";
                            } else {
                                value.padrao = "false";
                            }
                        });

                        $scope.lista_input = JSON.stringify($scope.campo.lista);
                    };
                }
            });
        };
    }]);
})();

/***/ }),

/***/ 264:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceExcluirCamposAdicionaisCtrl', ['$uibModal', 'CamposAdicionaisFactory', function ($uibModal, CamposAdicionaisFactory) {
        var global_id;
        this.modal = function (_id, $scope) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'excluirCamposAdicionaisModal.html',
                scope: $scope,
                resolve: {
                    id: function id() {
                        return _id;
                    }
                },
                controller: function controller($scope, $uibModalInstance, id) {

                    var id = id;

                    $scope.alterarStatus = function (departamento_id) {

                        global_id = departamento_id;

                        CamposAdicionaisFactory.excluirCampoAdicional(id).then(function mySuccess(response) {
                            //
                        }, function (error, status) {
                            //
                        }).finally(function () {
                            window.location = "/configuracao/ticket/campo_adicional/departamento/" + $scope.departamento_id;
                            modalInstance.close();
                        });
                    };

                    $scope.modalCancelarAlterarStatus = function () {
                        modalInstance.close();
                    };
                }
            });
        };
    }]);
})();

/***/ }),

/***/ 265:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceModalCampoAdicionalPrioridadeCtrl', ['$uibModal', 'CamposAdicionaisFactory', function ($uibModal, CamposAdicionaisFactory) {
        var global_id;
        var prioridade_alterado = false; //se houve cadastro
        this.modalAdd = function (_id) {
            global_id = _id;
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'addCampoAdicionalPrioridadeModal.html',
                resolve: {
                    id: function id() {
                        return _id;
                    }
                },
                controller: function controller($scope, $uibModalInstance, id) {

                    $scope.close = function () {
                        modalInstance.close();
                    };

                    $scope.cadastrar = function (param) {

                        param.departamento_id = id;

                        CamposAdicionaisFactory.addCampoAdicionalPrioridade(param).then(function mySuccess(response) {
                            $scope.errors = false;
                            prioridade_alterado = true;
                            $scope.success = response.data.mensagem;
                            $scope.prioridade = {};
                            $scope.prioridade.cor = '#000000';
                        }, function (error, status) {
                            prioridade_alterado = false;
                            $scope.success = false;
                            $scope.errors = error.data.errors;
                        });
                    };
                }
            });

            modalInstance.result.finally(function () {
                if (prioridade_alterado) {
                    window.location = "/configuracao/ticket/campo_adicional/departamento/" + global_id;
                }
            });
        };

        this.modalEditar = function ($scope) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'editarCampoAdicionalPrioridadeModal.html',
                scope: $scope,
                resolve: {},
                controller: function controller($scope, $uibModalInstance) {

                    $scope.lista = [];
                    $scope.setExcluirCampoAdicionalPrioridade = function (id) {
                        $scope.lista.push({
                            id: id
                        });
                    };

                    $scope.setForm = function () {

                        document.form_editar_prioridade.submit();
                    };

                    $scope.modalExcluirCampoAdicionalPrioridade = function (id) {

                        angular.forEach($scope.lista, function (value, key) {

                            CamposAdicionaisFactory.excluirCampoAdicionalPrioridade(value.id).then(function mySuccess(response) {
                                //
                            }, function (error, status) {
                                //
                            }).finally(function () {
                                //
                            });
                        });

                        $scope.setForm();
                    };

                    $scope.close = function () {
                        modalInstance.close();
                    };
                }

            });

            modalInstance.result.finally(function () {
                window.location = "/configuracao/ticket/campo_adicional/departamento/" + $scope.departamento_id;
            });
        };
    }]);
})();

/***/ }),

/***/ 266:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceModalCampoAdicionalStatusCtrl', ['$uibModal', 'CamposAdicionaisFactory', function ($uibModal, CamposAdicionaisFactory) {
        var global_id; //departamento_id  
        this.modalAdd = function (_id) {
            global_id = _id;
            var status_alterado = false; //se houve cadastro

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'addCampoAdicionalStatusModal.html',
                resolve: {
                    id: function id() {
                        return _id;
                    }
                },
                controller: function controller($scope, $uibModalInstance, id) {

                    $scope.close = function () {
                        modalInstance.close();
                    };

                    $scope.cadastrar = function (param) {

                        param.departamento_id = id;

                        CamposAdicionaisFactory.addCampoAdicionalStatus(param).then(function mySuccess(response) {
                            status_alterado = true;
                            $scope.errors = false;
                            $scope.success = response.data.mensagem;
                            $scope.status = {};
                            $scope.status.cor = '#000000';
                        }, function (error, status) {
                            status_alterado = false;
                            $scope.success = false;
                            $scope.errors = error.data.errors;
                        });
                    };
                }
            });

            modalInstance.result.finally(function () {
                if (status_alterado) {
                    window.location = "/configuracao/ticket/campo_adicional/departamento/" + global_id;
                }
            });
        };

        this.modalEditar = function ($scope, _id2) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'editarCampoAdicionalStatusModal.html',
                scope: $scope,
                resolve: {
                    id: function id() {
                        return _id2;
                    }
                },
                controller: function controller($scope, $uibModalInstance, id) {

                    $scope.lista = [];
                    $scope.setExcluirCampoAdicionalStatus = function (id) {
                        $scope.lista.push({
                            id: id
                        });
                    };

                    $scope.setForm = function () {

                        document.form_editar_status.submit();
                    };

                    $scope.modalExcluirCampoAdicionalStatus = function () {

                        angular.forEach($scope.lista, function (value, key) {

                            CamposAdicionaisFactory.excluirCampoAdicionalStatus(value.id).then(function mySuccess(response) {
                                //
                            }, function (error, status) {
                                //
                            }).finally(function () {
                                //
                            });
                        });

                        $scope.setForm();
                    };

                    $scope.close = function () {
                        modalInstance.close();
                    };
                }
            });

            modalInstance.result.finally(function () {
                window.location = "/configuracao/ticket/campo_adicional/departamento/" + $scope.departamento_id;
            });
        };
    }]);
})();

/***/ })

},[242]);