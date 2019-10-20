webpackJsonp([10],{

/***/ 267:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(268);
__webpack_require__(269);
__webpack_require__(270);
__webpack_require__(271);
__webpack_require__(272);
__webpack_require__(273);
__webpack_require__(274);
__webpack_require__(275);
__webpack_require__(276);
__webpack_require__(277);
__webpack_require__(278);
module.exports = __webpack_require__(279);


/***/ }),

/***/ 268:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('AreaFactory', ['$http', function ($http) {

        var _cadastrarArea = function _cadastrarArea(param) {
            return $http.post("/rh/area", param);
        };

        var _pesquisaArea = function _pesquisaArea(param) {
            return $http.post("/rh/area/search", param);
        };

        var _atualizarArea = function _atualizarArea(param) {
            return $http.put("/rh/area/" + param.id, param);
        };

        return {
            cadastrarArea: _cadastrarArea,
            pesquisaArea: _pesquisaArea,
            atualizarArea: _atualizarArea
        };
    }]);
})();

/***/ }),

/***/ 269:
/***/ (function(module, exports) {

(function () {

	'use strict';

	angular.module('app').controller('cadastrarAreaCtrl', ['$scope', 'AreaFactory', function ($scope, AreaFactory) {
		var init = function init() {
			$scope.success = "";
		};

		var limparCampos = function limparCampos() {
			$scope.area.nome = '';
			$scope.area.descricao = '';
			$scope.area.gestor = '';
		};

		$scope.cadastrar = function (area) {
			area.nome = area.nome.toUpperCase();
			area.descricao = area.descricao.toUpperCase();

			$scope.success = false;
			$scope.errors = false;

			$scope.form.$valid = false;

			AreaFactory.cadastrarArea(area).then(function mySuccess(response) {

				$scope.success = response.data.mensagem;
				$scope.url = "" + response.data.id + "";
				limparCampos();
			}, function (error) {

				$scope.errors = error.data;
			}).finally(function () {

				$scope.form.$valid = true;
			});
		};

		//inicializa controller cadastrarAcessoCtrl
		init();
	}]);
})();

/***/ }),

/***/ 270:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('listarAreaCtrl', ['$scope', '$http', 'AreaFactory', '$cookies', '$uibModal', function ($scope, $http, AreaFactory, $cookies, $uibModal) {
        var $ctrl = this;

        //filtro
        var ativo = _ativo;
        var nome = "";
        var gestor = '';
        var gestores = _gestores;
        var filtro = _filtro;

        var init = function init() {
            gestores.unshift({ id: '', nome: '' });
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
                $scope.filter.gestor = gestor;
                $scope.filter.ativo = ativo;
            } else {
                //Se filtro (NOME, USUARIO, PERFIL) = vazio e ATIVO = true, deve ser carregado fechado   
                $scope.filtro = $cookies.get('rh_area_nome') == "" && (isNaN(parseInt($cookies.get('rh_area_gestor'))) || $cookies.get('rh_area_gestor') == "0") && $cookies.get('rh_area_ativo') == "true" ? false : true;

                $scope.filter.nome = $cookies.get('rh_area_nome');
                $scope.filter.ativo = $cookies.get('rh_area_ativo');

                if ($scope.filter.ativo == "false") {
                    $scope.filter.ativo = "false";
                } else {
                    $scope.filter.ativo = "true";
                }
            }

            $scope.pesquisaArea($scope.filter);
        };

        var verificaCookies = function verificaCookies() {

            if (typeof $cookies.get('rh_area_nome') == "undefined" && typeof $cookies.get('rh_area_gestor') == "undefined" && typeof $cookies.get('rh_area_ativo') == "undefined") {
                return false;
            }

            return true;
        };

        $scope.defineFiltro = function () {
            var _column = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (_column != $scope.filter.coluna) {
                $scope.filter.coluna = _column;
            }

            $scope.filter.ordem = $scope.filter.ordem == 'asc' ? 'desc' : 'asc';
            $scope.pesquisaArea($scope.filter);
        };

        $scope.onGetPage = function (pageNumber) {
            //ok
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;
                $scope.pesquisaArea($scope.filter);
            }
        };

        $scope.pesquisaArea = function (pesquisa) {

            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
            $scope.carregando = true;

            AreaFactory.pesquisaArea(pesquisa).then(function mySuccess(response) {

                // Define a lista de usuários que será exibida na view.
                $scope.lista = response.data.areas.data;

                // Calcula alguns parametros da paginação.                
                var de = response.data.areas.current_page == 1 ? 1 : response.data.areas.total < pesquisa.limite ? (response.data.areas.current_page - 1) * pesquisa.limite + response.data.areas.total : (response.data.areas.current_page - 1) * pesquisa.limite + 1;

                var ate = pesquisa.limite == 0 ? response.data.areas.total : response.data.areas.total < pesquisa.limite ? response.data.areas.total : response.data.areas.current_page * pesquisa.limite;

                var totalDePaginas = response.data.areas.total % pesquisa.limite === 0 ? response.data.areas.total / pesquisa.limite : Math.floor(response.data.areas.total / pesquisa.limite) + 1;

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
            }, function (error, status) {

                $scope.errors = error.data;
            }).finally(function () {

                $scope.disableButton = false;
                $scope.carregando = false;
            });
        };

        $scope.limpaPesquisa = function () {
            //ok
            $scope.filter.nome = "";
            $scope.filter.gestor = gestores[0];
            $scope.filter.ativo = "true";

            $scope.pesquisaArea($scope.filter);
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

        $scope.setSelect = function () {
            if (isNaN(parseInt($cookies.get('rh_area_gestor'))) || $cookies.get('rh_area_gestor') == "0") {
                $scope.filter.gestor = $scope.gestores[0];
            } else {
                var indexObj = 0;

                angular.forEach($scope.gestores, function (value) {
                    if (value.id == $cookies.get('rh_area_gestor')) {
                        indexObj = $scope.gestores.indexOf(value);
                    }
                });

                $scope.filter.gestor = $scope.gestores[indexObj];
            }
        };

        //incializa controller
        init();
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();

/***/ }),

/***/ 271:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('editarAreaCtrl', ['$scope', 'AreaFactory', '$filter', function ($scope, AreaFactory, $filter) {

        var area = _area;
        var gestores = _gestores;

        var init = function init() {
            $scope.success = "";
            $scope.area = area;
            $scope.gestores = gestores;

            var indexObj = 0;
            angular.forEach($scope.gestores, function (value) {
                if (value.id == area.gestor_id) {
                    indexObj = $scope.gestores.indexOf(value);
                }
            });

            $scope.area.gestor = $scope.gestores[indexObj];
        };

        $scope.editar = function (area) {
            area.nome = area.nome.toUpperCase();
            area.descricao = area.descricao.toUpperCase();

            $scope.success = false;
            $scope.errors = false;

            AreaFactory.atualizarArea(area).then(function mySuccess(response) {

                $scope.success = response.data.mensagem;
                $scope.url = "../" + response.data.id + "";
            }, function (error) {

                $scope.errors = error.data;
            });
        };

        //inicializa controller
        init();
    }]);
})();

/***/ }),

/***/ 272:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('CargoFactory', ['$http', function ($http) {

        var _cadastrarCargo = function _cadastrarCargo(param) {
            return $http.post("/rh/cargo", param);
        };

        var _pesquisaCargo = function _pesquisaCargo(param) {
            return $http.post("/rh/cargo/search", param);
        };

        var _atualizarCargo = function _atualizarCargo(param, id) {
            return $http.put("/rh/cargo/" + id, param);
        };

        return {
            cadastrarCargo: _cadastrarCargo,
            pesquisaCargo: _pesquisaCargo,
            atualizarCargo: _atualizarCargo
        };
    }]);
})();

/***/ }),

/***/ 273:
/***/ (function(module, exports) {

(function () {

	'use strict';

	angular.module('app').controller('cadastrarCargoCtrl', ['$scope', 'CargoFactory', function ($scope, CargoFactory) {

		$scope.success = "";

		$scope.cadastrar = function (cargo) {
			cargo.nome = cargo.nome.toUpperCase();
			cargo.descricao = cargo.descricao.toUpperCase();

			$scope.form.$valid = false;
			$scope.success = false;
			$scope.errors = false;

			CargoFactory.cadastrarCargo(cargo).then(function (response) {

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

			$scope.cargo = {};
		};
	}]);
})();

/***/ }),

/***/ 274:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('listarCargoCtrl', ['$scope', '$http', 'CargoFactory', '$cookies', '$uibModal', 'Upload', '$timeout', function ($scope, $http, CargoFactory, $cookies, $uibModal, Upload, $timeout) {
        var $ctrl = this;

        //filtro
        var nome = "";
        var ativo = _ativo;
        var departamentos = _departamentos;
        var funcionarios = _funcionarios;
        var filtro = _filtro;

        var init = function init() {

            $scope.cargos = {};
            departamentos.unshift({ id: '', nome: '' });
            $scope.departamentos = departamentos;
            funcionarios.unshift({ id: '', nome: '' });
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
                $scope.filter.ativo = ativo;
            } else {

                //Se filtro (NOME, GESTOR, DEPARTAMENTO) = vazio e ATIVO = true, deve ser carregado fechado   
                $scope.filtro = $cookies.get('rh_cargo_nome') == "" && $cookies.get('rh_cargo_gestor') == "" && $cookies.get('rh_cargo_departamento') == "" && $cookies.get('rh_cargo_ativo') == "true" ? false : true;

                $scope.filter.nome = $cookies.get('rh_cargo_nome');
                $scope.filter.funcionario = $cookies.get('rh_cargo_gestor');
                $scope.filter.departamento = $cookies.get('rh_cargo_departamento');
                $scope.filter.ativo = $cookies.get('rh_cargo_ativo');

                if ($scope.filter.ativo == "false") {
                    $scope.filter.ativo = "false";
                } else {
                    $scope.filter.ativo = "true";
                }
            }

            $scope.pesquisaCargo($scope.filter);
        };

        var verificaCookies = function verificaCookies() {

            if (typeof $cookies.get('rh_cargo_nome') == "undefined" && typeof $cookies.get('rh_cargo_gestor') == "undefined" && typeof $cookies.get('rh_cargo_departamento') == "undefined" && typeof $cookies.get('rh_cargo_ativo') == "undefined") {
                return false;
            }

            return true;
        };

        $scope.defineFiltro = function () {
            var _column = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (_column != $scope.filter.coluna) {
                $scope.filter.coluna = _column;
            }

            $scope.filter.ordem = $scope.filter.ordem == 'asc' ? 'desc' : 'asc';
            $scope.pesquisaCargo($scope.filter);
        };

        $scope.onGetPage = function (pageNumber) {
            //ok
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;
                $scope.pesquisaCargo($scope.filter);
            }
        };

        $scope.pesquisaCargo = function (pesquisa) {

            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
            $scope.carregando = true;

            CargoFactory.pesquisaCargo(pesquisa).then(function mySuccess(response) {

                // Define a lista de usuários que será exibida na view.
                $scope.lista = response.data.cargos.data;

                // Calcula alguns parametros da paginação.                
                var de = response.data.cargos.current_page == 1 ? 1 : response.data.cargos.total < pesquisa.limite ? (response.data.cargos.current_page - 1) * pesquisa.limite + response.data.cargos.total : (response.data.cargos.current_page - 1) * pesquisa.limite + 1;

                var ate = pesquisa.limite == 0 ? response.data.cargos.total : response.data.cargos.total < pesquisa.limite ? response.data.cargos.total : response.data.cargos.current_page * pesquisa.limite;

                var totalDePaginas = response.data.cargos.total % pesquisa.limite === 0 ? response.data.cargos.total / pesquisa.limite : Math.floor(response.data.cargos.total / pesquisa.limite) + 1;

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
            //ok
            $scope.filter.nome = "";
            $scope.filter.ativo = "true";
            $scope.filter.funcionario = funcionarios[0];
            $scope.filter.departamento = departamentos[0];

            $scope.pesquisaCargo($scope.filter);
        };

        $scope.setSelectDepartamento = function () {
            if ($cookies.get('rh_cargo_departamento') != "null") {
                var indexObj = 0;

                angular.forEach($scope.departamentos, function (value) {
                    if (value.id == $cookies.get('rh_cargo_departamento')) {
                        indexObj = $scope.departamentos.indexOf(value);
                    }
                });

                $scope.filter.departamento = $scope.departamentos[indexObj];
            } else {
                $scope.filter.departamento = departamentos[0];
            }
        };

        $scope.setSelectFuncionario = function () {
            if ($cookies.get('rh_cargo_gestor') != "null") {
                var indexObj = 0;

                angular.forEach($scope.funcionarios, function (value) {
                    if (value.id == $cookies.get('rh_cargo_gestor')) {
                        indexObj = $scope.funcionarios.indexOf(value);
                    }
                });

                $scope.filter.funcionario = $scope.funcionarios[indexObj];
            } else {
                $scope.filter.funcionario = funcionarios[0];
            }
        };

        //incializa controller
        init();
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();

/***/ }),

/***/ 275:
/***/ (function(module, exports) {

(function () {

	'use strict';

	angular.module('app').controller('editarCargoCtrl', ['$scope', '$http', 'CargoFactory', function ($scope, $http, CargoFactory) {

		var id = _id;
		var nome = _nome;
		var descricao = _descricao;
		var funcionarios = _funcionarios;
		var departamentos = _departamentos;
		var func_selected = _func_selected;
		var depart_selected = _depart_selected;

		var init = function init() {

			$scope.cargo = {};
			$scope.cargo.id = id;
			$scope.cargo.nome = nome;
			$scope.cargo.descricao = descricao;

			$scope.gestores = funcionarios;
			$scope.departamentos = departamentos;

			$scope.func_selected = func_selected;
			$scope.depart_selected = depart_selected;
			listarCargo();
		};

		var listarCargo = function listarCargo() {

			$scope.cargo.gestor = $scope.func_selected[0];
			$scope.cargo.departamento = $scope.depart_selected[0];
		};

		var limparCampos = function limparCampos() {

			$scope.cargo = {};
		};

		$scope.atualizar = function (cargo) {

			cargo.nome = cargo.nome.toUpperCase();
			cargo.descricao = cargo.descricao.toUpperCase();

			$scope.form.$valid = false;
			$scope.success = false;
			$scope.errors = false;

			CargoFactory.atualizarCargo(cargo, cargo.id).then(function mySuccess(response) {

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

/***/ 276:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('DepartamentoFactory', ['$http', function ($http) {

        var _cadastrarDepartamento = function _cadastrarDepartamento(param) {
            return $http.post("/rh/departamento", param);
        };

        var _pesquisaDepartamento = function _pesquisaDepartamento(param) {
            return $http.post("/rh/departamento/search", param);
        };

        var _atualizarDepartamento = function _atualizarDepartamento(param) {
            return $http.put("/rh/departamento/" + param.id, param);
        };

        return {
            cadastrarDep: _cadastrarDepartamento,
            pesquisaDep: _pesquisaDepartamento,
            atualizarDep: _atualizarDepartamento };
    }]);
})();

/***/ }),

/***/ 277:
/***/ (function(module, exports) {

(function () {

	'use strict';

	angular.module('app').controller('cadastrarDepartamentoCtrl', ['$scope', 'DepartamentoFactory', function ($scope, DepartamentoFactory) {
		var init = function init() {
			$scope.success = "";
		};

		var limparCampos = function limparCampos() {
			$scope.departamento.nome = '';
			$scope.departamento.descricao = '';
			$scope.departamento.gestor = '';
			$scope.departamento.area = '';
			$scope.departamento.ticket = '';
		};

		$scope.cadastrar = function (departamento) {
			departamento.nome = departamento.nome.toUpperCase();
			departamento.descricao = departamento.descricao.toUpperCase();

			$scope.success = false;
			$scope.errors = false;

			$scope.form.$valid = false;

			DepartamentoFactory.cadastrarDep(departamento).then(function mySuccess(response) {

				$scope.success = response.data.mensagem;
				$scope.url = "" + response.data.id + "";
				limparCampos();
			}, function (error) {

				$scope.errors = error.data;
			}).finally(function () {

				$scope.form.$valid = true;
			});
		};

		//inicializa controller cadastrarAcessoCtrl
		init();
	}]);
})();

/***/ }),

/***/ 278:
/***/ (function(module, exports) {



(function () {

    'use strict';

    angular.module('app').controller('listarDepartamentoCtrl', ['$scope', '$http', 'DepartamentoFactory', '$cookies', '$uibModal', 'Upload', '$timeout', function ($scope, $http, DepartamentoFactory, $cookies, $uibModal, Upload, $timeout) {
        var $ctrl = this;

        //filtro
        var ativo = _ativo;
        var ticket = '';
        var nome = "";
        var gestor = '';
        var filtro = _filtro;
        var gestores = _gestores;
        var areas = _areas;

        var init = function init() {

            gestores.unshift({ id: '', nome: '' });
            areas.unshift({ id: '', nome: '' });
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
                $scope.filter.gestor = gestor;
                $scope.filter.area = areas;
                $scope.filter.ativo = ativo;
                $scope.filter.ticket = ticket;
            } else {
                //Se filtro (NOME, GESTOR, AREA E TICKET) = vazio e ATIVO = true, deve ser carregado fechado   
                $scope.filtro = $cookies.get('rh_departamento_nome') == "" && (isNaN(parseInt($cookies.get('rh_departamento_gestor'))) || $cookies.get('rh_departamento_gestor') == "0") && (isNaN(parseInt($cookies.get('rh_departamento_area'))) || $cookies.get('rh_departamento_area') == "0") && $cookies.get('rh_departamento_ativo') == "true" && $cookies.get('rh_departamento_ticket') == "" ? false : true;

                $scope.filter.nome = $cookies.get('rh_departamento_nome');
                $scope.filter.gestor = $cookies.get('rh_departamento_gestor');
                $scope.filter.area = $cookies.get('rh_departamento_area');
                $scope.filter.ticket = $cookies.get('rh_departamento_ticket');
                $scope.filter.ativo = $cookies.get('rh_departamento_ativo');

                if ($scope.filter.ativo == "false") {
                    $scope.filter.ativo = "false";
                } else {
                    $scope.filter.ativo = "true";
                }
            }

            $scope.pesquisaDepartamento($scope.filter);
        };

        var verificaCookies = function verificaCookies() {

            if (typeof $cookies.get('rh_departamento_nome') == "undefined" && typeof $cookies.get('rh_departamento_gestor') == "undefined" && typeof $cookies.get('rh_departamento_area') == "undefined" && typeof $cookies.get('rh_departamento_ativo') == "undefined" && typeof $cookies.get('rh_departamento_ticket') == "undefined") {
                return false;
            }

            return true;
        };

        $scope.defineFiltro = function () {
            var _column = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

            if (_column != $scope.filter.coluna) {
                $scope.filter.coluna = _column;
            }

            $scope.filter.ordem = $scope.filter.ordem == 'asc' ? 'desc' : 'asc';
            $scope.pesquisaDepartamento($scope.filter);
        };

        $scope.onGetPage = function (pageNumber) {
            //ok
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;
                $scope.pesquisaDepartamento($scope.filter);
            }
        };

        $scope.pesquisaDepartamento = function (pesquisa) {

            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
            $scope.carregando = true;

            DepartamentoFactory.pesquisaDep(pesquisa).then(function mySuccess(response) {

                // Define a lista de usuários que será exibida na view.
                $scope.lista = response.data.departamentos.data;

                // Calcula alguns parametros da paginação.                
                var de = response.data.departamentos.current_page == 1 ? 1 : response.data.departamentos.total < pesquisa.limite ? (response.data.departamentos.current_page - 1) * pesquisa.limite + response.data.departamentos.total : (response.data.departamentos.current_page - 1) * pesquisa.limite + 1;

                var ate = pesquisa.limite == 0 ? response.data.departamentos.total : response.data.departamentos.total < pesquisa.limite ? response.data.departamentos.total : response.data.departamentos.current_page * pesquisa.limite;

                var totalDePaginas = response.data.departamentos.total % pesquisa.limite === 0 ? response.data.departamentos.total / pesquisa.limite : Math.floor(response.data.departamentos.total / pesquisa.limite) + 1;

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
            }, function (error, status) {

                $scope.errors = error.data;
            }).finally(function () {

                $scope.disableButton = false;
                $scope.carregando = false;
            });
        };

        $scope.limpaPesquisa = function () {
            //ok
            $scope.filter.nome = "";
            $scope.filter.gestor = gestores[0];
            $scope.filter.area = areas[0];
            $scope.filter.ativo = "true";
            $scope.filter.ticket = "";

            $scope.pesquisaDepartamento($scope.filter);
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

        $scope.verificaCookiesAreas = function () {
            $scope.filter.area = $scope.areas[setSelect('rh_departamento_area', $scope.areas)];
        };

        $scope.verificaCookiesGestores = function () {
            $scope.filter.gestor = $scope.gestores[setSelect('rh_departamento_gestor', $scope.gestores)];
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

        //incializa controller
        init();
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();

/***/ }),

/***/ 279:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('editarDepartamentoCtrl', ['$scope', 'DepartamentoFactory', '$filter', function ($scope, DepartamentoFactory, $filter) {

        var areas = _areas;
        var gestores = _gestores;
        var departamento = _departamento;

        var init = function init() {
            $scope.success = "";
            $scope.departamento = departamento;
            $scope.area = areas;
            $scope.gestores = gestores;

            var indexObj = 0;
            angular.forEach($scope.gestores, function (value) {
                if (value.id == departamento.funcionario_id) {
                    indexObj = $scope.gestores.indexOf(value);
                }
            });
            $scope.departamento.gestor = $scope.gestores[indexObj];

            indexObj = 0;
            angular.forEach($scope.area, function (value) {
                if (value.id == departamento.area_id) {
                    indexObj = $scope.area.indexOf(value);
                }
            });
            $scope.departamento.area = $scope.area[indexObj];
        };

        $scope.editar = function (departamento) {
            departamento.nome = departamento.nome.toUpperCase();
            departamento.descricao = departamento.descricao.toUpperCase();

            $scope.success = false;
            $scope.errors = false;

            DepartamentoFactory.atualizarDep(departamento).then(function mySuccess(response) {

                $scope.success = response.data.mensagem;
                $scope.url = "../" + response.data.id + "";
            }, function (error) {

                $scope.errors = error.data;
            });
        };

        //inicializa controller
        init();
    }]);
})();

/***/ })

},[267]);