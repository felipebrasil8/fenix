webpackJsonp([11],{

/***/ 283:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(284);
__webpack_require__(285);
__webpack_require__(286);
__webpack_require__(287);
__webpack_require__(288);
__webpack_require__(289);
__webpack_require__(290);
module.exports = __webpack_require__(291);


/***/ }),

/***/ 284:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('TicketFactory', ['$http', function ($http) {

        var _pesquisarTicket = function _pesquisarTicket(param) {
            return $http.post("/ticket/search", param);
        };
        var _pesquisarTicketProprio = function _pesquisarTicketProprio(param) {
            return $http.post("/ticket/searchProprio", param);
        };

        var _cadastrarTicket = function _cadastrarTicket(param) {
            return $http.post("/ticket", param);
        };

        var _pesquisaSubcategoria = function _pesquisaSubcategoria(param) {
            return $http.post("/ticket/pesquisaSubcategoria", param);
        };

        var _atualizarTicket = function _atualizarTicket(param) {
            return $http.put("/ticket/" + param.id, param);
        };

        var _interacaoTicket = function _interacaoTicket(param) {
            return $http.post("/ticket/interacao", param);
        };

        var _getStatus = function _getStatus(id) {
            return $http.post("/ticket/status/" + id);
        };

        var _getSolicitante = function _getSolicitante(id) {
            return $http.post("/ticket/solicitante/" + id);
        };

        var _getPrioridade = function _getPrioridade(id) {
            return $http.post("/ticket/prioridade/" + id);
        };

        var _getCategoria = function _getCategoria(id) {
            return $http.post("/ticket/categoria/" + id);
        };

        var _getCamposAdicionais = function _getCamposAdicionais(id) {
            return $http.post("/ticket/campos_adicionais/" + id);
        };

        //imagem ticket        
        var _getImagemTicketDados = function _getImagemTicketDados(id) {
            return $http.get("/ticket/imagemDados/" + id);
        };
        var _getImagemTicket = function _getImagemTicket(id) {
            return $http.get("/ticket/imagem/" + id);
        };

        var _destroyImagemTicket = function _destroyImagemTicket(id) {
            return $http.delete("/ticket/imagem/" + id);
        };

        var _getAcaoMacro = function _getAcaoMacro(id, param) {
            return $http.post("/ticket/acaoMacro/" + id, param);
        };

        var _executaMacro = function _executaMacro(param) {
            return $http.post("/ticket/executaMacro", param);
        };

        return {
            cadastrarTicket: _cadastrarTicket,
            pesquisaSubcategoria: _pesquisaSubcategoria,
            atualizarTicket: _atualizarTicket,
            pesquisarTicket: _pesquisarTicket,
            interacaoTicket: _interacaoTicket,
            getStatus: _getStatus,
            getSolicitante: _getSolicitante,
            getPrioridade: _getPrioridade,
            getCategoria: _getCategoria,
            getCamposAdicionais: _getCamposAdicionais,
            pesquisarTicketProprio: _pesquisarTicketProprio,
            getImagemTicket: _getImagemTicket,
            getImagemTicketDados: _getImagemTicketDados,
            destroyImagemTicket: _destroyImagemTicket,
            getAcaoMacro: _getAcaoMacro,
            executaMacro: _executaMacro
        };
    }]);
})();

/***/ }),

/***/ 285:
/***/ (function(module, exports) {

(function () {

	'use strict';

	angular.module('app').controller('cadastrarTicketCtrl', ['$scope', '$uibModal', '$window', 'TicketFactory', '$sce', 'serviceTicketCtrl', function ($scope, $uibModal, $window, TicketFactory, $sce, serviceTicketCtrl) {
		var departamento = _departamentos;
		var init = function init() {
			$scope.success = "";
			$scope.subcategoria = '';
			$scope.departamentos = departamento;
			$scope.oldValue = '';
		};

		/**
   * Irá carregar o campo solicitante conforme o departamento escolhido
   */
		$scope.mostraSolicitante = function () {

			if ($scope.ticket.departamento) {

				TicketFactory.getSolicitante($scope.ticket.departamento.id).then(function mySuccess(response) {
					var selecionado = response.data.filter(function (selecionado) {
						return selecionado.selected == 'selected';
					});

					$scope.solicitantes = response.data;

					var index = $scope.solicitantes.map(function (item) {
						return item.id;
					}).indexOf(selecionado[0].id);

					$scope.ticket.solicitante = $scope.solicitantes[index];
				}, function (error) {
					$scope.solicitantes = null;
				}).finally(function () {
					//
				});
			}
		};

		/**
   * Irá carregar o campo prioridade conforme o departamento escolhido
   */
		$scope.mostraPrioridade = function () {

			if ($scope.ticket.departamento) {

				TicketFactory.getPrioridade($scope.ticket.departamento.id).then(function mySuccess(response) {
					var selecionado = response.data.filter(function (selecionado) {
						return selecionado.selected == 'selected';
					});

					$scope.prioridades = response.data;

					var index = $scope.prioridades.map(function (item) {
						return item.id;
					}).indexOf(selecionado[0].id);

					$scope.ticket.prioridade = $scope.prioridades[index];
				}, function (error) {
					$scope.prioridades = null;
				}).finally(function () {
					//
				});
			}
		};

		$scope.verificaDescricaoPrioridade = function (id) {

			var obj = $scope.prioridades.filter(function (obj) {
				return obj.id == id;
			});

			if (!(obj[0].descricao == null || obj[0].descricao == '')) {
				$scope.dicaModalPrioridades($scope, obj[0].descricao);
			}
		};

		/**
   * Irá carregar o campo categoria conforme o departamento escolhido
   */
		$scope.mostraCategoria = function () {

			if ($scope.ticket.departamento) {

				TicketFactory.getCategoria($scope.ticket.departamento.id).then(function mySuccess(response) {
					$scope.categorias = response.data;
				}, function (error) {
					$scope.categorias = null;
				}).finally(function () {
					//
				});
			}
		};

		$scope.renderHtml = function (html_code) {
			return $sce.trustAsHtml(html_code);
		};

		/**
   * Irá carregar o campo adicional conforme o departamento escolhido
   */
		$scope.mostraCamposAdicionais = function () {

			if ($scope.ticket.departamento) {

				TicketFactory.getCamposAdicionais($scope.ticket.departamento.id).then(function mySuccess(response) {
					$scope.campos_adicionais = response.data;
				}, function (error) {
					$scope.campos_adicionais = null;
				}).finally(function () {
					//
				});
			}
		};

		$scope.mostrarSubcategoria = function (id_categoria) {
			if (id_categoria != undefined) {
				TicketFactory.pesquisaSubcategoria(id_categoria).then(function mySuccess(response) {
					$scope.dicaModal = response.data.categoriaDica[0].dicas;
					$scope.subcategoria = response.data.subcategoria;

					if ($scope.dicaModal != '') {
						$scope.abreModalDica($scope.dicaModal);
					}
				}, function (error) {
					$scope.errors = error.data;
				}).finally(function () {
					//
				});
			} else {
				$scope.subcategoria = '';
			}
		};

		$scope.dicaSubcategoria = function (subcategoria) {
			if (subcategoria != undefined && subcategoria.dicas != '') {
				$scope.abreModalDica(subcategoria.dicas);
			}
		};

		$scope.alterarDepartamento = function (oldValue) {

			if (($scope.ticket.solicitante || $scope.ticket.prioridade || $scope.ticket.assunto || $scope.ticket.categoria || $scope.ticket.descricao) && oldValue != undefined) {
				serviceTicketCtrl.modal(oldValue, $scope);
			} else {
				$scope.mostraSolicitante();
				$scope.mostraPrioridade();
				$scope.mostraCategoria();
				$scope.mostraCamposAdicionais();
			}
		};

		$scope.cadastrar = function (ticket) {
			$scope.form.$valid = false;
			$scope.success = false;
			$scope.errors = false;
			/*
    * Pegas os campos via javascript e passa para o angular
    * para enviar via ajax
    */
			var campos_adicionais = angular.element(document.getElementsByName("campo_adicional"));
			ticket.campo_adicional = [];

			angular.forEach(campos_adicionais, function (campo) {
				var obj = { 'id': campo.attributes['data-campo-id'].value, 'value': campo.value };
				ticket.campo_adicional.push(obj);
			});

			TicketFactory.cadastrarTicket(ticket).then(function mySuccess(response) {
				$scope.success = response.data.mensagem;
				$scope.url = "" + response.data.id + "";
				$window.location.href = response.data.id;
			}, function (error) {
				$scope.errors = error.data;
			}).finally(function () {
				//
			});
		};

		$scope.abreModalDica = function (dicaModal) {
			var modalInstance = $uibModal.open({
				animation: true,
				ariaLabelledBy: 'modal-title-bottom',
				ariaDescribedBy: 'modal-body-bottom',
				size: 'md',
				templateUrl: 'todasDicaModal.html',
				controller: function controller($scope) {
					$scope.dicaModal = nl2br(dicaModal);

					$scope.continuarTicket = function () {
						modalInstance.close();
					};

					$scope.pesquisarTicket = function () {
						$window.location.href = '/ticket';
					};
				}
			});
		};

		$scope.dicaModalPrioridades = function ($scope, dicaModal) {

			var modalInstance = $uibModal.open({
				animation: true,
				ariaLabelledBy: 'modal-title-bottom',
				ariaDescribedBy: 'modal-body-bottom',
				size: 'md',
				templateUrl: 'dicaModalPrioridades.html',
				scope: $scope,
				controller: function controller($scope) {
					$scope.dicaModal = nl2br(dicaModal);

					$scope.continuarTicket = function () {
						modalInstance.close();
					};

					$scope.checarMenorPrioridade = function () {
						$scope.mostraPrioridade();
						modalInstance.close();
					};
				}
			});
		};

		function nl2br(str, is_xhtml) {
			var breakTag = is_xhtml || typeof is_xhtml === 'undefined' ? '<br />' : '<br>';
			return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
		}

		//inicializa controller cadastrarAcessoCtrl
		init();
	}]);
})();

/***/ }),

/***/ 286:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('visualizarTicketCtrl', ['$scope', '$uibModal', '$window', 'TicketFactory', 'Upload', '$location', 'serviceTicketCtrl', '$q', '$filter', function ($scope, $uibModal, $window, TicketFactory, Upload, $location, serviceTicketCtrl, $q, $filter) {
        var ticket_id;
        var acao;
        var acao_id;
        var date_min;

        var init = function init() {
            ticket_id = _ticket_id;

            acao_id = '';

            $scope.acao = {
                responsavel: '',
                solicitante: '',
                categoria: '',
                subcategoria: ''
            };

            $scope.rating = 0;
            $scope.ratings = [{
                current: 1,
                max: 5
            }];

            $scope.verificaTab();

            $scope.date_min = $filter('date')(new Date(), 'dd-MM-yyyy HH:mm');
        };

        $scope.interacao = function () {
            var ticket = setTicket();

            ticket.interno = false;

            setInteracao(ticket);
        };

        $scope.notaInterna = function () {
            var ticket = setTicket();

            ticket.interno = true;

            setInteracao(ticket);
        };

        var setTicket = function setTicket() {
            $scope.form.$valid = false;

            var ticket = {};

            ticket.id = ticket_id;
            ticket.mensagem = $scope.ticket.mensagem.toUpperCase();

            return ticket;
        };

        var setInteracao = function setInteracao(ticket) {
            $scope.success = false;
            $scope.errors = false;

            $scope.form.$valid = false;

            TicketFactory.interacaoTicket(ticket).then(function mySuccess(response) {
                if (response.data.status == true) {
                    $window.location.reload();
                }
            }, function (error) {

                $scope.errors = error.data;
            }).finally(function () {

                $scope.form.$valid = false;
            });
        };

        $scope.escondeErro = function () {
            $scope.errors = '';
        };

        $scope.keyPressDescricaoImagem = function (event) {
            if (event.which === 13) {
                $scope.confirmarImagem();
                event.preventDefault();
            }
        };

        $scope.confirmarImagem = function () {

            $scope.errors = {};
            $scope.formImagem.$valid = false;

            if ($scope.imagem.file && $scope.imagem.texto) {
                Upload.upload({
                    url: '/ticket/imagem',
                    data: { imagem: $scope.imagem.file, ticket_id: $scope.imagem.ticket_id, texto: $scope.imagem.texto },
                    headers: { 'Content-Type': 'multipart/form-data' }
                }).then(function (response) {

                    $scope.success = response.data.mensagem;
                    $scope.imagem.file = false;
                    $scope.formImagem.$valid = true;
                    $scope.imagem.texto = '';

                    setTimeout(function () {
                        $window.location.reload();
                    }, 2000);
                }, function (error) {
                    $scope.errors = error.data;
                });
            } else if (!$scope.imagem.file) {

                var obj = {
                    'errors': {
                        '0': 'Por favor selecione uma imagem'
                    }
                };

                $scope.errors = obj;
            } else {

                var obj = {
                    'errors': {
                        '0': 'Campo descrição é obrigatório'
                    }
                };

                $scope.errors = obj;
            }
        };

        $scope.verificaTab = function () {

            var url = $window.location.href;
            var res = url.split("#!#");

            if (res[1] == 'interacao') {
                $scope.tab = 1;
            } else if (res[1] == 'imagem') {
                $scope.tab = 2;
            } else if (res[1] == 'historico') {
                $scope.tab = 3;
            } else if (res[1] == 'acao') {
                $scope.tab = 4;
                $scope.campos_macro = '';
            } else {
                $scope.tab = 1;
            }
        };

        $scope.showModal = function (ticket_id) {

            $scope.carregandoModalImagemDados = true;

            var getImagemTicket = TicketFactory.getImagemTicketDados(ticket_id).then(function mySuccess(response) {
                return response.data;
            });

            $q.all([getImagemTicket]).then(function (result) {
                serviceTicketCtrl.modalImagem(result);
                $scope.carregandoModalImagemDados = false;
            });
        };

        $scope.acaoMacro = function (macro_id) {
            $scope.success = false;
            $scope.errors = false;

            acao_id = macro_id;

            var obj = { 'ticket_id': ticket_id };

            TicketFactory.getAcaoMacro(macro_id, obj).then(function mySuccess(response) {
                if (response.data.status == true) {
                    $scope.tab = 4;

                    $scope.campos_macro = response.data.acao;

                    /**
                     * Seleciona os select que veio do banco se houver
                     */
                    $scope.acao_responsavel = response.data.responsavel;
                    $scope.acao.responsavel = $scope.acao_responsavel[setSelect(response.data.responsavel, $scope.acao_responsavel)];

                    $scope.acao_status = response.data.ticket_status;
                    if ($scope.acao_status != '') {
                        var index = setSelect(response.data.ticket_status, $scope.acao_status);
                        if (index == 0) {
                            $scope.acao.status = $scope.acao_status[index].id;
                        } else {
                            $scope.acao.status = $scope.acao_status[index].id.toString();
                        }
                    }

                    $scope.acao.dt_previsao = response.data.dt_previsao;

                    $scope.acao_solicitante = response.data.solicitantes;
                    $scope.acao.solicitante = $scope.acao_solicitante[setSelect(response.data.solicitantes, $scope.acao_solicitante)];

                    $scope.acao_categoria = response.data.categoria;
                    $scope.acao.categoria = $scope.acao_categoria[setSelect(response.data.categoria, $scope.acao_categoria)];

                    $scope.acao_subcategoria = response.data.subcategoria;
                    $scope.acao.subcategoria = $scope.acao_subcategoria[setSelect(response.data.subcategoria, $scope.acao_subcategoria)];

                    $scope.acao.assunto = response.data.assunto;

                    $scope.acao_prioridade = response.data.prioridade;
                    $scope.acao.prioridade = $scope.acao_prioridade[setSelect(response.data.prioridade, $scope.acao_prioridade)];

                    $scope.acao.dt_notificacao = response.data.dt_notificacao;

                    if ($scope.campos_macro.interacao == true && $scope.campos_macro.nota_interna == true) {
                        $scope.acao.interacao = false;
                    }

                    if ($scope.campos_macro.interacao == true && $scope.campos_macro.nota_interna == false) {
                        $scope.acao.interacao = false;
                    }

                    if ($scope.campos_macro.interacao == false && $scope.campos_macro.nota_interna == true) {
                        $scope.acao.interacao = true;
                    }
                }
            }, function (error) {

                $scope.tab = 4;
                $scope.errors = error.data;
            });
        };

        $scope.mostrarSubcategoria = function (id_categoria) {
            if (id_categoria != undefined) {
                TicketFactory.pesquisaSubcategoria(id_categoria).then(function mySuccess(response) {
                    $scope.acao_subcategoria = response.data.subcategoria;
                    $scope.acao.subcategoria = $scope.acao_subcategoria[0];
                }, function (error) {
                    $scope.errors = error.data;
                });
            } else {
                $scope.acao.subcategoria = '';
            }
        };

        $scope.executaMacro = function () {
            $scope.success = false;
            $scope.errors = false;

            $scope.formMacro.$valid = false;

            $scope.acao.ticket_id = ticket_id;
            $scope.acao.acao_id = acao_id;
            //$scope.acao.texto_interacao = $filter('uppercase')($scope.acao.texto_interacao);

            if ($scope.campos_macro.campos.indexOf('campos_adicionais') > 0) {
                inserirCamposAdicionais();
            }

            if ($scope.campos_macro.campos.indexOf('avaliacao') > 0) {
                $scope.acao.avaliacao = $scope.ratings[0].current;
            }

            var obj = { 'acoes_macro': $scope.acao };

            TicketFactory.executaMacro(obj).then(function mySuccess(response) {
                $scope.success = response.data.mensagem;
                $scope.iconeCarregando = true;

                if (response.data.status == true) {
                    setTimeout(function () {
                        var url = $window.location.href;
                        var res = url.split("#!#");
                        $window.location.href = res[0] + '#interacao';
                        $window.location.reload();
                    }, 2000);
                }
            }, function (error) {

                $scope.errors = error.data;
            });
        };

        var setSelect = function setSelect(response, lista) {
            var indexObj = 0;

            angular.forEach(response, function (value) {
                if (value.selected == 'selected') {
                    indexObj = lista.indexOf(value);
                }
            });

            return indexObj;
        };

        var inserirCamposAdicionais = function inserirCamposAdicionais(acao) {
            var campos_adicionais = angular.element(document.getElementsByName("campo_adicional"));
            $scope.acao.campo_adicional = [];
            /* 
             * Percorre o array de campos adicionais
             * Cria um objeto com o id : campo.attributes['data-campo-id'].value
             * e
             * seu valor : campo.value.toUpperCase()
             * para enviar via ajax
             */
            angular.forEach(campos_adicionais, function (campo) {
                var obj_campo = { 'id': campo.attributes['data-campo-id'].value, 'value': campo.value.toUpperCase() };
                $scope.acao.campo_adicional.push(obj_campo);
            });
        };

        //inicializa controller cadastrarAcessoCtrl
        init();
    }]);
})();

/***/ }),

/***/ 287:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('listarTicketCtrl', ['$scope', '$rootScope', '$http', 'TicketFactory', '$cookies', '$filter', '$location', function ($scope, $rootScope, $http, TicketFactory, $cookies, $filter, $location) {
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

        var init = function init() {
            departamentos.unshift({ id: '', nome: '' });
            $scope.departamentos = departamentos;

            //categorias.unshift({id: '', nome: ''});
            //$scope.categorias = categorias;

            //statuses.unshift({id: '', nome: ''});
            //$scope.statuses = statuses;

            usuarios.unshift({ id: '', nome: '' });
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
            } else {
                $scope.filtro = $cookies.get('ticket_codigo') == "" && $cookies.get('ticket_assunto') == "" && (isNaN(parseInt($cookies.get('ticket_usuario_responsavel'))) || $cookies.get('ticket_usuario_responsavel') == "0") && $cookies.get('ticket_aberto') == "true" && (isNaN(parseInt($cookies.get('ticket_ticket_de'))) || $cookies.get('ticket_ticket_de') == "") && (isNaN(parseInt($cookies.get('ticket_ticket_ate'))) || $cookies.get('ticket_ticket_ate') == "") && (isNaN(parseInt($cookies.get('ticket_usuario_solicitante'))) || $cookies.get('ticket_usuario_solicitante') == "0") && (isNaN(parseInt($cookies.get('ticket_departamento'))) || $cookies.get('ticket_departamento') == "0") ? false : true;

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

        var verificaCookies = function verificaCookies() {

            if (typeof $cookies.get('ticket_codigo') == "undefined" && typeof $cookies.get('ticket_assunto') == "undefined" && typeof $cookies.get('ticket_ticket_de') == "undefined" && typeof $cookies.get('ticket_ticket_ate') == "undefined" && typeof $cookies.get('ticket_aberto') == "undefined" && typeof $cookies.get('ticket_usuario_responsavel') == "undefined" && typeof $cookies.get('ticket_usuario_solicitante') == "undefined" && typeof $cookies.get('ticket_categoria') == "undefined" && typeof $cookies.get('ticket_statuse') == "undefined" && typeof $cookies.get('ticket_prioridade') == "undefined") {
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
            $scope.pesquisaTicket($scope.filter);
        };

        $scope.onGetPage = function (pageNumber) {
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;
                $scope.pesquisaTicket($scope.filter);
            }
        };

        $scope.pesquisaTicket = function (pesquisa) {

            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
            $scope.carregando = true;

            TicketFactory.pesquisarTicket($scope.filter).then(function mySuccess(response) {

                // Define a lista de usuários que será exibida na view.
                $scope.lista = response.data.ticket.data;

                // Armazena ID do usuário logado
                $scope.usuario_logado = $rootScope.auth.id;

                // Calcula alguns parametros da paginação.                
                var de = response.data.ticket.current_page == 1 ? 1 : response.data.ticket.total < pesquisa.limite ? (response.data.ticket.current_page - 1) * pesquisa.limite + response.data.ticket.total : (response.data.ticket.current_page - 1) * pesquisa.limite + 1;

                var ate = pesquisa.limite == 0 ? response.data.ticket.total : response.data.ticket.total < pesquisa.limite ? response.data.ticket.total : response.data.ticket.current_page * pesquisa.limite;

                var totalDePaginas = response.data.ticket.total % pesquisa.limite === 0 ? response.data.ticket.total / pesquisa.limite : Math.floor(response.data.ticket.total / pesquisa.limite) + 1;

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
                $cookies.put('ticket_ticket_de', pesquisa.de);
                $cookies.put('ticket_ticket_ate', pesquisa.ate);
                // Define os parametros da paginação
                $scope.paginacao = {
                    total: response.data.ticket.total,
                    pagina: response.data.ticket.current_page,
                    de: de,
                    ate: ate,
                    limite: pesquisa.limite,
                    totalDePaginas: totalDePaginas
                };
            }, function (response) {

                $scope.errors = response.data;

                // Caso seja falha de login
                if (response.status == 401) {
                    $scope.reload = function () {
                        location.href = '/login';
                    };
                    $scope.reload();
                }
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

        $scope.selecionaDepartamento = function () {
            if ($scope.filter.departamento.id != 0) {

                TicketFactory.getPrioridade($scope.filter.departamento.id).then(function mySuccess(response) {
                    //  response.data.unshift({id: '', nome: ''});
                    $scope.prioridades = response.data;
                }, function (response) {
                    $scope.errors = response.data;
                }).finally(function () {});

                TicketFactory.getStatus($scope.filter.departamento.id).then(function mySuccess(response) {
                    //  response.data.unshift({id: '', nome: ''});
                    $scope.statuses = response.data;
                }, function (response) {
                    $scope.errors = response.data;
                }).finally(function () {});

                TicketFactory.getCategoria($scope.filter.departamento.id).then(function mySuccess(response) {
                    // response.data.unshift({id: '', nome: ''});
                    $scope.categorias = response.data;
                }, function (response) {
                    $scope.errors = response.data;
                }).finally(function () {});
            }

            if ($scope.filter.departamento.id == 0) {
                $scope.categorias = "";
                $scope.statuses = "";
                $scope.prioridades = "";
            }
        };

        $scope.verificaCookiesDepartamento = function () {
            $scope.filter.departamento = $scope.departamentos[setSelect('ticket_departamento', $scope.departamentos)];
        };

        $scope.verificaCookiesStatuse = function () {
            //  $scope.filter.statuse = $scope.statuses[ setSelect('ticket_statuse', $scope.statuses) ];

        };

        $scope.verificaCookiesCategoria = function () {
            // $scope.filter.categoria = $scope.categorias[ setSelect('ticket_categoria', $scope.categorias) ];
        };

        $scope.verificaCookiesPrioridade = function () {
            // $scope.filter.prioridade = $scope.prioridades[ setSelect('ticket_prioridade', $scope.prioridades) ];
        };

        $scope.verificaCookiesSolicitante = function () {
            $scope.filter.usuario_solicitante = $scope.usuarios[setSelect('ticket_usuario_solicitante', $scope.usuarios)];
        };

        $scope.verificaCookiesResponsavel = function () {
            $scope.filter.usuario_responsavel = $scope.usuarios[setSelect('ticket_usuario_responsavel', $scope.usuarios)];
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

        $scope.csv = function () {
            document.exportar.action = "/ticket/download/csv";
            document.exportar.submit();
        };

        $scope.xlsx = function () {
            document.exportar.action = "/ticket/download/xlsx";
            document.exportar.submit();
        };

        var getPadraoDataInicio = function getPadraoDataInicio() {
            var date = new Date();
            var dataInicio = new Date(date.getFullYear(), date.getMonth(), 1);
            return $filter('date')(dataInicio, 'dd/MM/y');
        };

        var getPadraoDataFim = function getPadraoDataFim() {
            var date = new Date();
            var dataFim = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            return $filter('date')(dataFim, 'dd/MM/y');
        };

        //incializa controller
        init();
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();

/***/ }),

/***/ 288:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('editarTicketCtrl', ['$scope', '$filter', 'TicketFactory', function ($scope, $filter, TicketFactory) {

        var subcategoria = _subcategoria;
        var ticket_dt_previsao = _ticket_dt_previsao;

        var init = function init() {
            $scope.ticket = {};

            $scope.subcategoria = subcategoria;
            $scope.subcategoria.unshift({ id: '', nome: '', dicas: '', selected: '' });

            var indexObj = 0;
            angular.forEach($scope.subcategoria, function (value) {

                if (value.selected != '') {
                    indexObj = $scope.subcategoria.indexOf(value);
                }
            });

            $scope.ticket.subcategoria = $scope.subcategoria[indexObj];

            if (ticket_dt_previsao != '') {
                $scope.ticket.dt_previsao = $filter('date')(ticket_dt_previsao, 'dd/MM/yyyy');
            }
        };

        init();

        $scope.customChange = function (event) {
            var id_categoria = angular.element(document.querySelector('#categoria'))[0].value;

            if (id_categoria != undefined) {
                TicketFactory.pesquisaSubcategoria(id_categoria).then(function mySuccess(response) {
                    $scope.subcategoria = response.data.subcategoria;
                    $scope.subcategoria.unshift({ id: '', nome: '', dicas: '' });
                    $scope.ticket.subcategoria = $scope.subcategoria[0];
                }, function (error) {

                    $scope.errors = error.data;
                }).finally(function () {

                    $scope.form.$valid = true;
                });
            } else {
                $scope.subcategoria = '';
            }
        };

        $scope.atualizar = function (ticket) {
            /*
             * Pegas os campos via javascript e passa para o angular
             * para enviar via ajax
             */
            ticket.status = angular.element(document.querySelector('#status'))[0].value;
            ticket.solicitante = angular.element(document.querySelector('#solicitante'))[0].value;
            ticket.prioridade = angular.element(document.querySelector('#prioridade'))[0].value;

            ticket.assunto = angular.element(document.querySelector('#assunto'))[0].value;
            ticket.categoria = angular.element(document.querySelector('#categoria'))[0].value;
            // subcategoria já está no ticket

            var campos_adicionais = angular.element(document.getElementsByName("campo_adicional"));

            ticket.responsavel = angular.element(document.querySelector('#responsavel'))[0].value;

            ticket.campo_adicional = [];
            /* 
             * Percorre o array de campos adicionais
             * Cria um objeto com o id : campo.attributes['data-campo-id'].value
             * e
             * seu valor : campo.value.toUpperCase()
             * para enviar via ajax
             */
            angular.forEach(campos_adicionais, function (campo) {
                var obj = { 'id': campo.attributes['data-campo-id'].value, 'value': campo.value.toUpperCase() };
                ticket.campo_adicional.push(obj);
            });

            ticket.assunto = ticket.assunto.toUpperCase();
            ticket.mensagem = ticket.mensagem.toUpperCase();

            ticket.id = _ticket_id;

            $scope.success = false;
            $scope.errors = false;

            TicketFactory.atualizarTicket(ticket).then(function mySuccess(response) {

                $scope.ticket.mensagem = "";
                $scope.success = response.data.mensagem;
                $scope.url = "../" + response.data.id + "";
            }, function (error) {

                $scope.errors = error.data;
            });
        };

        $scope.cancelar = function (ticket) {
            location.href = '../' + _ticket_id;
        };
    }]);
})();

/***/ }),

/***/ 289:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('listarProprioTicketCtrl', ['$scope', '$http', 'TicketFactory', '$cookies', '$filter', '$location', function ($scope, $http, TicketFactory, $cookies, $filter, $location) {
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

        var init = function init() {
            departamentos.unshift({ id: '', nome: '' });
            $scope.departamentos = departamentos;

            usuarios.unshift({ id: '', nome: '' });
            $scope.usuarios = usuarios;
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
                categoria: 0,
                statuse: 0,
                prioridade: 0,
                assunto: '',
                codigo: ''

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
                $scope.filter.de = "";
                $scope.filter.ate = "";
                $scope.filter.aberto = "true";
                $scope.filter.codigo = codigo;
                $scope.filter.assunto = assunto;
                $scope.filter.departamento = departamento;
                $scope.filter.usuario_responsavel = usuario_responsavel;
                $scope.filter.categoria = categoria;
                $scope.filter.statuse = statuse;
                $scope.filter.prioridade = prioridade;
            } else {
                $scope.filtro = $cookies.get('ticket_proprio_codigo') == "" && $cookies.get('ticket_proprio_assunto') == "" && (isNaN(parseInt($cookies.get('ticket_proprio_usuario_responsavel'))) || $cookies.get('ticket_proprio_usuario_responsavel') == "0") && $cookies.get('ticket_proprio_aberto') == "true" && (isNaN(parseInt($cookies.get('ticket_proprio_ticket_de'))) || $cookies.get('ticket_proprio_ticket_de') == "") && (isNaN(parseInt($cookies.get('ticket_proprio_ticket_ate'))) || $cookies.get('ticket_proprio_ticket_ate') == "") && (isNaN(parseInt($cookies.get('ticket_proprio_usuario_solicitante'))) || $cookies.get('ticket_proprio_usuario_solicitante') == "0") && (isNaN(parseInt($cookies.get('ticket_proprio_departamento'))) || $cookies.get('ticket_proprio_departamento') == "0") ? false : true;

                $scope.filter.de = $cookies.get('ticket_proprio_ticket_de');
                $scope.filter.ate = $cookies.get('ticket_proprio_ticket_ate');
                $scope.filter.codigo = $cookies.get('ticket_proprio_codigo');
                $scope.filter.assunto = $cookies.get('ticket_proprio_assunto');
                $scope.filter.usuario_responsavel = $cookies.get('ticket_proprio_usuario_responsavel');
                $scope.filter.departamento = $cookies.get('ticket_proprio_departamento');
                $scope.filter.aberto = $cookies.get('ticket_proprio_aberto');
                $scope.filter.categoria = $cookies.get('ticket_proprio_categoria');
                $scope.filter.statuse = $cookies.get('ticket_proprio_statuse');
                $scope.filter.prioridade = $cookies.get('ticket_proprio_prioridade');
            }

            $scope.pesquisaTicket($scope.filter);
        };

        var verificaCookies = function verificaCookies() {

            if (typeof $cookies.get('ticket_proprio_codigo') == "undefined" && typeof $cookies.get('ticket_proprio_assunto') == "undefined" && typeof $cookies.get('ticket_proprio_ticket_de') == "undefined" && typeof $cookies.get('ticket_proprio_ticket_ate') == "undefined" && typeof $cookies.get('ticket_proprio_aberto') == "undefined" && typeof $cookies.get('ticket_proprio_usuario_responsavel') == "undefined" && typeof $cookies.get('ticket_proprio_categoria') == "undefined" && typeof $cookies.get('ticket_proprio_statuse') == "undefined" && typeof $cookies.get('ticket_proprio_prioridade') == "undefined") {
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
            $scope.pesquisaTicket($scope.filter);
        };

        $scope.onGetPage = function (pageNumber) {
            if (pageNumber >= 1 && pageNumber <= $scope.paginacao.totalDePaginas) {
                $scope.filter.to = pageNumber;
                $scope.pesquisaTicket($scope.filter);
            }
        };

        $scope.pesquisaTicket = function (pesquisa) {

            $scope.disableButton = true;
            $scope.success = false;
            $scope.errors = false;
            $scope.carregando = true;

            TicketFactory.pesquisarTicketProprio($scope.filter).then(function mySuccess(response) {
                // Define a lista de usuários que será exibida na view.
                $scope.lista = response.data.ticket.data;

                // Calcula alguns parametros da paginação.                
                var de = response.data.ticket.current_page == 1 ? 1 : response.data.ticket.total < pesquisa.limite ? (response.data.ticket.current_page - 1) * pesquisa.limite + response.data.ticket.total : (response.data.ticket.current_page - 1) * pesquisa.limite + 1;

                var ate = pesquisa.limite == 0 ? response.data.ticket.total : response.data.ticket.total < pesquisa.limite ? response.data.ticket.total : response.data.ticket.current_page * pesquisa.limite;

                var totalDePaginas = response.data.ticket.total % pesquisa.limite === 0 ? response.data.ticket.total / pesquisa.limite : Math.floor(response.data.ticket.total / pesquisa.limite) + 1;

                // Define os parametros da pesquisa nos cookies.
                $cookies.put('ticket_proprio_assunto', response.data.assunto);
                $cookies.put('ticket_proprio_codigo', response.data.codigo);
                $cookies.put('ticket_proprio_departamento', response.data.departamento);
                $cookies.put('ticket_proprio_aberto', response.data.aberto);
                $cookies.put('ticket_proprio_usuario_responsavel', response.data.usuario_responsavel);
                // $cookies.put('ticket_proprio_categoria', response.data.categoria);
                // $cookies.put('ticket_proprio_statuse', response.data.statuse);
                // $cookies.put('ticket_proprio_prioridade', response.data.prioridade);
                $cookies.put('ticket_proprio_pagina', pesquisa.pagina);
                $cookies.put('ticket_proprio_ticket_de', pesquisa.de);
                $cookies.put('ticket_proprio_ticket_ate', pesquisa.ate);
                // Define os parametros da paginação
                $scope.paginacao = {
                    total: response.data.ticket.total,
                    pagina: response.data.ticket.current_page,
                    de: de,
                    ate: ate,
                    limite: pesquisa.limite,
                    totalDePaginas: totalDePaginas
                };
            }, function (response) {

                $scope.errors = response.data;

                // Caso seja falha de login
                if (response.status == 401) {
                    $scope.reload = function () {
                        location.href = '/login';
                    };
                    $scope.reload();
                }
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

        $scope.selecionaDepartamento = function () {
            if ($scope.filter.departamento.id != 0) {

                TicketFactory.getPrioridade($scope.filter.departamento.id).then(function mySuccess(response) {
                    $scope.prioridades = response.data;
                }, function (response) {
                    $scope.errors = response.data;
                }).finally(function () {});

                TicketFactory.getStatus($scope.filter.departamento.id).then(function mySuccess(response) {

                    $scope.statuses = response.data;
                }, function (response) {
                    $scope.errors = response.data;
                }).finally(function () {});

                TicketFactory.getCategoria($scope.filter.departamento.id).then(function mySuccess(response) {
                    $scope.categorias = response.data;
                }, function (response) {
                    $scope.errors = response.data;
                }).finally(function () {});
            }

            if ($scope.filter.departamento.id == 0) {
                $scope.categorias = "";
                $scope.statuses = "";
                $scope.prioridades = "";
            }
        };

        $scope.verificaCookiesDepartamento = function () {
            $scope.filter.departamento = $scope.departamentos[setSelect('ticket_proprio_departamento', $scope.departamentos)];
        };

        $scope.verificaCookiesStatuse = function () {
            // $scope.filter.statuse = $scope.statuses[ setSelect('ticket_proprio_statuse', $scope.statuses) ];
        };

        $scope.verificaCookiesCategoria = function () {
            //  $scope.filter.categoria = $scope.categorias[ setSelect('ticket_proprio_categoria', $scope.categorias) ];
        };

        $scope.verificaCookiesPrioridade = function () {
            // $scope.filter.prioridade = $scope.prioridades[ setSelect('ticket_proprio_prioridade', $scope.prioridades) ];
        };

        $scope.verificaCookiesSolicitante = function () {
            $scope.filter.usuario_solicitante = $scope.usuarios[setSelect('ticket_proprio_usuario_solicitante', $scope.usuarios)];
        };

        $scope.verificaCookiesResponsavel = function () {
            $scope.filter.usuario_responsavel = $scope.usuarios[setSelect('ticket_proprio_usuario_responsavel', $scope.usuarios)];
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

        $scope.csv = function () {
            document.exportar.action = "/ticket/download/csv";
            document.exportar.submit();
        };

        $scope.xlsx = function () {
            document.exportar.action = "/ticket/download/xlsx";
            document.exportar.submit();
        };

        var getPadraoDataInicio = function getPadraoDataInicio() {
            var date = new Date();
            var dataInicio = new Date(date.getFullYear(), date.getMonth(), 1);
            return $filter('date')(dataInicio, 'dd/MM/y');
        };

        var getPadraoDataFim = function getPadraoDataFim() {
            var date = new Date();
            var dataFim = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            return $filter('date')(dataFim, 'dd/MM/y');
        };

        //incializa controller
        init();
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();

/***/ }),

/***/ 290:
/***/ (function(module, exports) {

(function () {

	'use strict';

	// dashboard

	angular.module('app').controller('dashboardTicketCtrl', ['$scope', '$timeout', 'DashboardFactory', '$cookies', '$uibModal', function ($scope, $timeout, DashboardFactory, $cookies, $uibModal) {
		var migalhas = _migalhas;
		var departamentos = _departamentos;
		var visualizar_todos = _visualizar_todos;
		var tempoPing = _timeout;

		var init = function init() {
			$scope.grafico = '';
			$scope.migalhas = migalhas;

			/**
    * Váriaveis de filtro de departamento e data
    */
			$scope.departamentos = departamentos;
			$scope.visualizar_todos = visualizar_todos;
			$scope.departamento = {};
			$scope.datas = [{ value: 'hoje', nome: 'HOJE' }, { value: 'mes_atual', nome: 'MÊS ATUAL' }, { value: 'ultimos_trinta_dias', nome: 'ÚLTIMOS 30 DIAS' }, { value: 'ano_atual', nome: 'ANO ATUAL' }, { value: 'ultimo_ano', nome: 'ÚLTIMO ANO' }, { value: 'customizado', nome: 'CUSTOMIZADO' }];
			$scope.data = {};
			$scope.viewDepartamento = true;
			$scope.viewData = true;
			$scope.carregando = false;
			$scope.viewTela = false;

			verificaCookiesDepartamento();
			verificaCookiesData();

			montaDashboard();
			setTimeout(function () {
				pingaDash();
			}, 1000);
		};

		//funcao criada para atualizar a pagina e corrigir provisóriamente, erro de  tela travada do dash na tv.
		//ira atualizar a pagina por (x) minutos
		setInterval(function () {
			location.reload();
		}, 60000 * 20);

		var pingaDash = function pingaDash() {
			montaDashboard();
			setTimeout(function () {
				pingaDash();
			}, tempoPing * 1000);
		};

		var viewDataCustomizado = function viewDataCustomizado(data) {
			$scope.stringData = '';

			if (data.value == 'customizado') {
				if (typeof $cookies.get('dashboard_ticket_data_de') != "undefined" && typeof $cookies.get('dashboard_ticket_data_ate') != "undefined") {
					$scope.stringData = '' + $cookies.get('dashboard_ticket_data_de') + ' até ' + $cookies.get('dashboard_ticket_data_ate');
				} else {
					$scope.stringData = $scope.data.nome;
				}

				return true;
			}

			$scope.stringData = $scope.data.nome;
			return false;
		};

		var montaDashboard = function montaDashboard() {
			if (typeof $cookies.get('dashboard_ticket_departamento') != "undefined" && typeof $cookies.get('dashboard_ticket_data') != "undefined") {
				var enviar = true;

				var filter = {
					id: $cookies.get('dashboard_ticket_departamento'),
					data: $cookies.get('dashboard_ticket_data')
				};

				if (viewDataCustomizado($scope.data)) {
					if (typeof $cookies.get('dashboard_ticket_data_de') != "undefined" && typeof $cookies.get('dashboard_ticket_data_ate') != "undefined") {
						filter.data_de = $cookies.get('dashboard_ticket_data_de');
						$scope.data.data_de = $cookies.get('dashboard_ticket_data_de');
						filter.data_ate = $cookies.get('dashboard_ticket_data_ate');
						$scope.data.data_ate = $cookies.get('dashboard_ticket_data_ate');
					} else {
						enviar = false;
					}
				}

				if (enviar) {
					$scope.carregando = true;
					$scope.viewTela = true;

					montaGraficos(filter);

					$timeout(function () {
						$scope.carregando = false;
					}, 2000);
				}
			}
		};

		var montaGraficos = function montaGraficos(filter) {
			DashboardFactory.getDashboardTicket(filter).then(function mySuccess(response) {
				$scope.ticketsNovos = response.data.ticketsNovos;
				$scope.totalDeTicketsAbertos = response.data.totalDeTicketsAbertos;
				$scope.ticketsFechadosNoPeriodo = response.data.ticketsFechadosNoPeriodo;
				$scope.avaliacao = response.data.avaliacao;
				$scope.avaliacaoQtde = response.data.avaliacaoQtde == 0 ? '' : response.data.avaliacaoQtde;

				if (response.data.avaliacaoQtde == 0) $scope.avaliacaoQtdeTexto = '';else if (response.data.avaliacaoQtde == 1) $scope.avaliacaoQtdeTexto = 'AVALIAÇÃO';else $scope.avaliacaoQtdeTexto = 'AVALIAÇÕES';

				$scope.tempoMedioDeAtendimento = response.data.tempoMedioDeAtendimento;

				$scope.responsaveis = response.data.responsaveis;

				$scope.ticketsAbertosPorDepartamento = response.data.ticketsAbertosPorDepartamento;
				google.charts.load("current", { packages: ['corechart', 'bar'] });
				google.charts.setOnLoadCallback(drawChart_ticketsPorResponsavelAbertosCtrl(response));
				google.charts.setOnLoadCallback(drawChart_ticketsPorResponsavelFechadosCtrl(response));
				google.charts.setOnLoadCallback(drawChart_ticketsPorStatusCtrl(response));
				google.charts.setOnLoadCallback(drawChart_ticketsAbertosPorPrioridadeCtrl(response));
				google.charts.setOnLoadCallback(drawChart_ticketsPorCategoria(response));
			});
		};

		$scope.hoverInDepartamento = function () {
			$scope.viewDepartamento = false;
		};

		$scope.hoverOutDepartamento = function () {
			$scope.viewDepartamento = true;
		};

		$scope.hoverInData = function () {
			$scope.viewData = false;
		};

		$scope.hoverOutData = function () {
			$scope.viewData = true;
		};

		var setSelect = function setSelect(response, lista) {
			var indexObj = 0;
			var encontrado = false;

			angular.forEach(response, function (value) {
				if (value.selected == 'selected') {
					indexObj = lista.indexOf(value);
					encontrado = true;
				}
			});

			if (encontrado) {
				return indexObj;
			}

			return -1;
		};

		var verificaCookiesDepartamento = function verificaCookiesDepartamento() {
			if ($scope.departamentos != '') {
				// Se não tive cookie definido
				if (typeof $cookies.get('dashboard_ticket_departamento') == "undefined") {
					var index = setSelect(departamentos, $scope.departamentos);
					if (index == -1) {
						$scope.departamento.nome = "SELECIONE UM DEPARTAMENTO";
					} else {
						$scope.departamento = $scope.departamentos[setSelect(departamentos, $scope.departamentos)];
						$cookies.put('dashboard_ticket_departamento', $scope.departamento.id);
					}
				} else {
					var indexObj = 0;

					angular.forEach($scope.departamentos, function (value) {
						if (value.id == $cookies.get('dashboard_ticket_departamento')) {
							indexObj = $scope.departamentos.indexOf(value);
						}
					});

					$scope.departamento = $scope.departamentos[indexObj];
				}
			}
		};

		var verificaCookiesData = function verificaCookiesData() {
			// Se não tive cookie definido
			if (typeof $cookies.get('dashboard_ticket_data') == "undefined") {
				$scope.data = { value: 'mes_atual', nome: 'MÊS ATUAL' };
				$cookies.put('dashboard_ticket_data', $scope.data.value);
			} else {
				var indexObj = 0;

				angular.forEach($scope.datas, function (value) {
					if (value.value == $cookies.get('dashboard_ticket_data')) {
						indexObj = $scope.datas.indexOf(value);
					}
				});

				$scope.data = $scope.datas[indexObj];
			}

			viewDataCustomizado($scope.data);
		};

		$scope.setDepartamentoCookie = function (departamento) {
			// Define os parametros da pesquisa nos cookies.
			$cookies.put('dashboard_ticket_departamento', departamento.id);
			$scope.viewDepartamento = true;
			montaDashboard();
		};

		$scope.setDataCookie = function (data) {
			// Define os parametros da pesquisa nos cookies.
			$cookies.put('dashboard_ticket_data', data.value);
			$scope.viewData = true;

			if (data.value == 'customizado') {
				$scope.modalPeriodo();
			} else {
				montaDashboard();
			}

			$scope.stringData = $scope.data.nome;
		};

		$scope.modalPeriodo = function () {
			var modalDataCusmotizado = $uibModal.open({
				animation: true,
				ariaLabelledBy: 'modal-title-bottom',
				ariaDescribedBy: 'modal-body-bottom',
				size: 'md',
				scope: $scope,
				templateUrl: 'periodoModal.html',
				resolve: {},
				controller: function controller($scope, $filter) {
					var cookies = function cookies() {
						// Se tem cookies
						if (typeof $cookies.get('dashboard_ticket_data_de') != "undefined" && typeof $cookies.get('dashboard_ticket_data_ate') != "undefined") {
							$scope.data_de = $cookies.get('dashboard_ticket_data_de');
							$scope.data_ate = $cookies.get('dashboard_ticket_data_ate');
						} else {
							$scope.data_de = $filter('date')(new Date(), 'dd/MM/yyyy');
							$scope.data_ate = $filter('date')(new Date(), 'dd/MM/yyyy');
						}
					};

					cookies();

					$scope.modalFecharCustomizado = function () {
						$scope.$parent.stringData = $scope.data.nome;
						modalDataCusmotizado.close();
					};

					$scope.modalData = function () {
						$cookies.put('dashboard_ticket_data_de', $scope.data_de);
						$cookies.put('dashboard_ticket_data_ate', $scope.data_ate);
						$scope.$parent.stringData = $cookies.get('dashboard_ticket_data_de') + ' até ' + $cookies.get('dashboard_ticket_data_ate');
						montaDashboard();
						modalDataCusmotizado.close();
					};
				}
			});
		};

		$scope.porResponsavel = function () {
			document.exportar.action = "dashboard/download/responsavel";
			document.exportar.submit();
		};
		$scope.porStatus = function () {
			document.exportar.action = "dashboard/download/status";
			document.exportar.submit();
		};
		$scope.porPrioridade = function () {
			document.exportar.action = "dashboard/download/prioridade";
			document.exportar.submit();
		};
		$scope.porCategoria = function () {
			document.exportar.action = "dashboard/download/categoria";
			document.exportar.submit();
		};
		$scope.porAvaliacao = function () {
			document.exportar.action = "dashboard/download/avaliacao";
			document.exportar.submit();
		};

		//ticketsPorResponsavelAbertosCtrl
		var drawChart_ticketsPorResponsavelAbertosCtrl = function drawChart_ticketsPorResponsavelAbertosCtrl(response) {

			var cores_responsaveisAbertos = [];
			response.data.responsaveisAbertos.forEach(function (x) {
				cores_responsaveisAbertos.push(x.cor);
			});

			var data_ticketsPorResponsavelAbertosCtrl = new google.visualization.DataTable();
			data_ticketsPorResponsavelAbertosCtrl.addColumn('string', 'nome');
			data_ticketsPorResponsavelAbertosCtrl.addColumn('number', 'total');

			response.data.responsaveisAbertos.forEach(function (row) {
				data_ticketsPorResponsavelAbertosCtrl.addRow([row.nome, row.total]);
			});

			var options_ticketsPorResponsavelAbertosCtrl = {
				pieHole: 0.5,
				pieSliceTextStyle: {
					color: '#333333',
					bold: true,
					fontSize: 18
				},
				legend: 'none',
				chartArea: { width: "100%", height: "100%" },
				colors: cores_responsaveisAbertos,
				pieSliceText: 'value'
			};

			var x = document.getElementById("idNoDataAbertos");
			var theDiv = document.getElementById("idTicketsPorResponsavelAbertos");
			x.style.display = "none";
			theDiv.style.display = "none";

			if (data_ticketsPorResponsavelAbertosCtrl.getNumberOfRows() == 0) {

				x.style.display = "block";
			} else {

				theDiv.style.display = "block";
				var chart_ticketsPorResponsavelAbertosCtrl = new google.visualization.PieChart(document.getElementById('idTicketsPorResponsavelAbertos'));
				chart_ticketsPorResponsavelAbertosCtrl.draw(data_ticketsPorResponsavelAbertosCtrl, options_ticketsPorResponsavelAbertosCtrl);
			}
		};

		//_ticketsPorResponsavelFechadosCtrl
		function drawChart_ticketsPorResponsavelFechadosCtrl(response) {

			var cores_responsaveisFechados = [];
			response.data.responsaveisFechados.forEach(function (x) {
				cores_responsaveisFechados.push(x.cor);
			});
			var data_ticketsPorResponsavelFechadosCtrl = new google.visualization.DataTable();

			data_ticketsPorResponsavelFechadosCtrl.addColumn('string', 'nome');
			data_ticketsPorResponsavelFechadosCtrl.addColumn('number', 'total');

			response.data.responsaveisFechados.forEach(function (row) {
				data_ticketsPorResponsavelFechadosCtrl.addRow([row.nome, row.total]);
			});

			var options_ticketsPorResponsavelFechadosCtrl = {
				pieHole: 0.5,
				pieSliceTextStyle: {
					color: '#333333',
					bold: true,
					fontSize: 18
				},
				legend: 'none',
				chartArea: { width: "100%", height: "100%" },
				colors: cores_responsaveisFechados,
				pieSliceText: 'value'
			};

			var x = document.getElementById("idNoDataFechados");
			var theDiv = document.getElementById("idTicketsPorResponsavelFechados");
			x.style.display = "none";
			theDiv.style.display = "none";

			if (data_ticketsPorResponsavelFechadosCtrl.getNumberOfRows() == 0) {

				x.style.display = "block";
			} else {

				theDiv.style.display = "block";
				var chart_ticketsPorResponsavelFechadosCtrl = new google.visualization.PieChart(document.getElementById('idTicketsPorResponsavelFechados'));
				chart_ticketsPorResponsavelFechadosCtrl.draw(data_ticketsPorResponsavelFechadosCtrl, options_ticketsPorResponsavelFechadosCtrl);
			}
		}

		//Tickets por status abertos no período
		function drawChart_ticketsPorStatusCtrl(response) {

			var cores_status = [];
			response.data.status.forEach(function (x) {
				cores_status.push(x.cor);
			});

			var data_ticketsPorStatusCtrl = new google.visualization.DataTable();

			data_ticketsPorStatusCtrl.addColumn('string', 'nome');
			data_ticketsPorStatusCtrl.addColumn('number', 'total');

			response.data.status.forEach(function (row) {
				data_ticketsPorStatusCtrl.addRow([row.nome, row.total]);
			});

			var options_ticketsPorStatusCtrl = {
				pieHole: 0.5,
				pieSliceTextStyle: {
					color: '#333333',
					bold: true,
					fontSize: 18
				},
				legend: 'none',
				chartArea: { width: "100%", height: "100%" },
				colors: cores_status,
				pieSliceText: 'value'
			};

			var x = document.getElementById("idNoDataStatus");
			var theDiv = document.getElementById("ticketsPorStatusCtrl");
			x.style.display = "none";
			theDiv.style.display = "none";

			if (data_ticketsPorStatusCtrl.getNumberOfRows() == 0) {

				x.style.display = "block";
			} else {

				var i = 0;
				response.data.status.forEach(function (row) {
					if (row.total == 0) {
						i++;
					}
				});
				var tamanho = response.data.status.length;

				if (i == tamanho) {
					x.style.display = "block";
				} else {

					theDiv.style.display = "block";
					$scope.status = response.data.status;
					var chart_ticketsPorStatusCtrl = new google.visualization.PieChart(document.getElementById('ticketsPorStatusCtrl'));
					chart_ticketsPorStatusCtrl.draw(data_ticketsPorStatusCtrl, options_ticketsPorStatusCtrl);
				}
			}
		}

		//Tickets abertos por prioridade
		function drawChart_ticketsAbertosPorPrioridadeCtrl(response) {

			var cores_prioridades = [];
			$scope.prioridades = response.data.prioridades;
			response.data.prioridades.forEach(function (x) {
				cores_prioridades.push(x.cor);
			});

			var data_ticketsAbertosPorPrioridadeCtrl = new google.visualization.DataTable();

			data_ticketsAbertosPorPrioridadeCtrl.addColumn('string', 'nome');
			data_ticketsAbertosPorPrioridadeCtrl.addColumn('number', 'total');

			response.data.prioridades.forEach(function (row) {
				data_ticketsAbertosPorPrioridadeCtrl.addRow([row.nome, row.total]);
			});

			var options_ticketsAbertosPorPrioridadeCtrl = {
				pieHole: 0.5,
				pieSliceTextStyle: {
					color: '#333333',
					bold: true,
					fontSize: 18
				},
				legend: 'none',
				chartArea: { width: "100%", height: "100%" },
				colors: cores_prioridades,
				pieSliceText: 'value'
			};

			var x = document.getElementById("idNoDataPrioridades");
			var theDiv = document.getElementById("ticketsAbertosPorPrioridadeCtrl");
			x.style.display = "none";
			theDiv.style.display = "none";

			if (data_ticketsAbertosPorPrioridadeCtrl.getNumberOfRows() == 0) {
				x.style.display = "block";
			} else {
				theDiv.style.display = "block";
				var chart_ticketsAbertosPorPrioridadeCtrl = new google.visualization.PieChart(document.getElementById('ticketsAbertosPorPrioridadeCtrl'));
				chart_ticketsAbertosPorPrioridadeCtrl.draw(data_ticketsAbertosPorPrioridadeCtrl, options_ticketsAbertosPorPrioridadeCtrl);
			}
		}

		function drawChart_ticketsPorCategoria(response) {
			var data_ticketsPorCategoria = new google.visualization.DataTable();

			data_ticketsPorCategoria.addColumn('string', 'Categoria');
			data_ticketsPorCategoria.addColumn('number', 'Qtd. de tickets');
			data_ticketsPorCategoria.addColumn({ type: 'string', role: 'annotation' });

			response.data.ticketsPorCategoria.forEach(function (row) {
				data_ticketsPorCategoria.addRow(['', row.total, '' + row.nome + ': ' + row.total]);
			});

			var options_ticketsPorCategoria = {
				chartArea: { width: "90%", height: "90%" },
				bar: { groupWidth: "30" },
				legend: { position: "none" },
				colors: ['#0073b7'],
				annotations: {
					//alwaysOutside: true,
					textStyle: {
						fontSize: 12,
						bold: true,
						color: '#333'
					}
				},
				vAxis: {
					minValue: 0,
					viewWindow: { min: 0 },
					textStyle: {
						fontSize: 12,
						bold: true,
						color: '#444'
					}
				},
				hAxis: {
					gridlines: { count: 0 },
					minValue: 0,
					viewWindow: { min: 0 }
				}
			};

			var chart_ticketsPorCategoria = new google.visualization.BarChart(document.getElementById("columnchart_values"));
			chart_ticketsPorCategoria.draw(data_ticketsPorCategoria, options_ticketsPorCategoria);
		}

		//inicializa controller cadastrarAcessoCtrl
		init();
	}]);
})();

/***/ }),

/***/ 291:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('DashboardFactory', ['$http', function ($http) {

        var _getDashboardTicket = function _getDashboardTicket(param) {
            return $http.post("/ticket/dashboard/" + param.id, param);
        };

        return {
            getDashboardTicket: _getDashboardTicket

        };
    }]);
})();

/***/ })

},[283]);