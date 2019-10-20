webpackJsonp([13],{

/***/ 232:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(233);
__webpack_require__(234);
__webpack_require__(235);
__webpack_require__(236);
__webpack_require__(237);
__webpack_require__(238);
module.exports = __webpack_require__(239);


/***/ }),

/***/ 233:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('listarMenuCtrl', ['$scope', '$http', function ($scope, $http) {
        var menus = _menus;

        var init = function init() {
            $scope.menus = menus;
        };

        //incializa controller
        init();
    }]);
})();

/***/ }),

/***/ 234:
/***/ (function(module, exports) {

(function () {

    'use strict';

    //factories

    angular.module('app').factory('HeaderFactory', ['$http', function ($http) {

        var _mudarSenhaFunc = function _mudarSenhaFunc(param) {
            return $http.post("/configuracao/usuario/" + param.id + "/password", param);
        };

        var _atualizarVisualizarSenha = function _atualizarVisualizarSenha(param) {
            return $http.put("/configuracao/usuario/" + param.id + "/solicitarNovaSenha", param);
        };

        var _pegafuncionarios = function _pegafuncionarios() {
            return $http.post("/rh/funcionario/getFuncionariosAniversario");
        };

        var _notificacoes = function _notificacoes() {
            return $http.post("/core/notificacao/getNotificacoes");
        };

        var _setNotificaoVisualizada = function _setNotificaoVisualizada(param) {
            return $http.put("/core/notificacao/" + param.id + "/setNotificaoVisualizada", param);
        };

        return {
            mudarSenhaFunc: _mudarSenhaFunc,
            atualizarVisualizarSenha: _atualizarVisualizarSenha,
            pegafuncionarios: _pegafuncionarios,
            notificacoes: _notificacoes,
            setNotificaoVisualizada: _setNotificaoVisualizada
        };
    }]);
})();

/***/ }),

/***/ 235:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('headerCtrl', ['$scope', '$rootScope', '$http', 'HeaderFactory', '$uibModal', '$filter', '$interval', 'serviceModalAniversarioCtrl', 'serviceModalNotificacaoCtrl', 'serviceHeaderHomeCtrl', 'webNotification', '$window', '$cookies', '$location', function ($scope, $rootScope, $http, HeaderFactory, $uibModal, $filter, $interval, serviceModalAniversarioCtrl, serviceModalNotificacaoCtrl, serviceHeaderHomeCtrl, webNotification, $window, $cookies, $location) {

        var auth = '';

        var politicaSenha = _politicaSenha;
        var strPoliticaSenha = '';

        var tempoAtualizarNotificacao = _notificacao_tempo;
        var atualizarNotificacao = _notificacao_can;

        var init = function init() {
            $scope.menu_aberto = 'true';
            getStatusCookies();
            verificaTelaExpandir();
            disparaTriggerMenu();
            auth = _auth;
            $rootScope.auth = auth;
            $scope.token = auth.api_token;

            HeaderFactory.pegafuncionarios().then(function (response) {

                $scope.funcionarios = serviceHeaderHomeCtrl.getFormataAniversariantes(response.data.funcionarios);
                $scope.funcionarios_niver = response.data.funcionarios_niver;

                angular.forEach($scope.funcionarios_niver, function (value) {
                    value.dt_nascimento = $filter('date')(value.dt_nascimento, 'dd/MM');
                });
            });

            listarPoliticaSenha();
            if (atualizarNotificacao) {

                listarNotificacoes();
                $interval(listarNotificacoes, tempoAtualizarNotificacao * 1000);
            }
        };

        /*
         * Notificações
         */
        var listarNotificacoes = function listarNotificacoes() {
            HeaderFactory.notificacoes().then(function (response) {

                $scope.notificacoes = response.data.notificacoes;
                $scope.count_nao_lidas_int = response.data.notificaoes_nao_lidas;

                /*
                 * Seta notificações não lidas para ser usada na home
                 */
                serviceHeaderHomeCtrl.setNotificacoesNaoLidas($scope.count_nao_lidas_int);

                aplicaEstilo($scope.notificacoes);
                serviceHeaderHomeCtrl.setNotificacoes($scope.notificacoes);

                $scope.count_nao_lidas_str = serviceHeaderHomeCtrl.getNotificacoesNaoLidasStr();

                abreWebNotification(response.data.notificaoes_nao_notificadas);

                if ($scope.count_nao_lidas_int == 0) angular.element(document.querySelector('#favicon'))[0].href = '/img/favicon.ico';else angular.element(document.querySelector('#favicon'))[0].href = '/img/favicon-notificacao.ico';
            }, function (response) {

                // console.log(response);

                // Caso seja falha de login
                if (response.status == 422) {
                    $scope.reload = function () {
                        location.href = '/login';
                    };
                    $scope.reload();
                }
            });
        };

        function abreWebNotification(notificacoes) {
            var count = 0;
            var limitWebNotification = 3;

            angular.forEach(notificacoes, function (value) {
                if (count < limitWebNotification) {
                    webNotification.showNotification(value.titulo, {
                        body: value.mensagem,
                        icon: value.imagem,
                        onClick: function onNotificationClicked() {
                            HeaderFactory.setNotificaoVisualizada(value).then(function (response) {
                                $window.open(value.url + '#' + value.id, '_blank');
                            });
                            this.close();
                        },
                        autoClose: 15000
                    });

                    count++;
                }
            });
        }

        function aplicaEstilo(notificacoes) {
            angular.forEach(notificacoes, function (notificacao) {
                notificacao.color = '';
                notificacao.notificacao_azul = '';
                notificacao.text_light_blue = '';
                notificacao.text_muted = '';
                notificacao.icone_status = 'fa-circle';
                notificacao.title = '';

                if (notificacao.visualizada == false) {
                    notificacao.color = 'background-color: #edf2fa;';
                    notificacao.notificacao_azul = 'notificacao-azul';
                    notificacao.text_light_blue = 'text-light-blue';
                    notificacao.title = 'Marcar como lida';
                } else {
                    notificacao.text_muted = 'text-muted';
                    notificacao.icone_status = 'fa-circle-o';
                    notificacao.title = 'Marcar como não lida';
                }
            });
        }

        $scope.marcarNotificacaoLida = function (notificacao) {
            HeaderFactory.setNotificaoVisualizada(notificacao).then(function (response) {
                if (response.data.status == true) {
                    listarNotificacoes();
                }
            });
        };

        $scope.abrirNotificacao = function (notificacao) {
            if (notificacao.visualizada == false) {
                $scope.marcarNotificacaoLida(notificacao);
            }
        };

        $scope.modalTodasNotificacoes = function () {
            serviceModalNotificacaoCtrl.modal($scope);
        };

        /*
         * End Notificação
         */

        var listarPoliticaSenha = function listarPoliticaSenha() {
            angular.forEach(politicaSenha, function (value) {
                strPoliticaSenha = strPoliticaSenha + '<span style="text-align: left;">- ' + value + "</span><br>";
            });
            $scope.politicaSenha = strPoliticaSenha;
        };

        $scope.abreModalParaTrocarSenha = function (politicaSenha) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: 'trocarSenhaModal.html',
                controller: function controller($scope) {
                    $scope.modal = {
                        id: id,
                        oldPassword: '',
                        newPassword: '',
                        newPasswordConfirmation: '',
                        sucesso: false,
                        erro: false
                    };

                    $scope.politicaSenha = politicaSenha;

                    $scope.modalConfirmarTrocaDeSenha = function () {
                        HeaderFactory.mudarSenhaFunc($scope.modal).then(function (response) {
                            $scope.success = response.data.success;
                            $scope.modal = {
                                oldPassword: '',
                                newPassword: '',
                                newPasswordConfirmation: '',
                                sucesso: true,
                                erro: false
                            };
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

                    $scope.modalCancelarTrocaDeSenha = function () {
                        modalInstance.close();
                    };

                    $scope.close = function () {
                        modalInstance.close();
                    };

                    $scope.isShowBtnSalvar = function () {
                        return $scope.modal.oldPassword != '' && $scope.modal.newPassword != '' && $scope.modal.newPasswordConfirmation != '';
                    };
                }
            });
        };

        $scope.atualizarVisualizarSenha = function () {
            auth.visualizado_senha_alterada = true;

            HeaderFactory.atualizarVisualizarSenha(auth).then(function mySuccess(response) {}).finally(function () {});
        };

        $scope.modalTodosAniversarios = function () {
            serviceModalAniversarioCtrl.modal($scope.funcionarios);
        };

        $scope.abreModalParaCopiarToken = function () {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: 'copiarTokenModal.html',
                scope: $scope,
                controller: function controller($scope) {

                    $scope.close = function () {
                        modalInstance.close();
                    };

                    $scope.modalCopiarToken = function () {
                        var name = $scope.token;
                        var copyElement = document.createElement("textarea");
                        copyElement.style.position = 'fixed';
                        copyElement.style.opacity = '0';
                        copyElement.textContent = decodeURI(name);
                        var body = document.getElementsByTagName('body')[0];
                        body.appendChild(copyElement);
                        copyElement.select();
                        document.execCommand('copy');
                        body.removeChild(copyElement);
                        $scope.success = 'Token copiado com sucesso.';
                    };

                    $scope.modalCopiarUrl = function () {
                        var host = $location.protocol() + '://' + location.host;
                        var absUrl = $location.absUrl();
                        var redirect = '?redirect=' + absUrl.replace(host, '');
                        var api_token = '&api_token=' + $scope.token;
                        var valorCopiar = host + '/token/' + redirect + api_token;
                        var copyElement = document.createElement("textarea");
                        copyElement.style.position = 'fixed';
                        copyElement.style.opacity = '0';
                        copyElement.textContent = decodeURI(valorCopiar);
                        var body = document.getElementsByTagName('body')[0];
                        body.appendChild(copyElement);
                        copyElement.select();
                        document.execCommand('copy');
                        body.removeChild(copyElement);
                        $scope.success = 'URL copiada com sucesso.';
                    };
                }
            });
        };

        //menu
        var disparaTriggerMenu = function disparaTriggerMenu() {

            if ('' + getStatusCookies() + '' != '' + getStatusAtualMenu() + '') {
                $scope.menu_aberto = !$scope.menu_aberto;
                $cookies.put('menu_aberto', '' + $scope.menu_aberto + '');
                angular.element('#sidebar_menu').trigger('click');
                document.getElementById('buscaMenuEsconde').style.cssText = $scope.menu_aberto ? 'display:inherit' : 'display:none';
            }
        };

        var getStatusCookies = function getStatusCookies() {

            return $cookies.get('menu_aberto') == 'false' ? false : true;
        };

        var getStatusAtualMenu = function getStatusAtualMenu() {

            return '' + $scope.menu_aberto + '';
        };

        $scope.setStatusAtualMenu = function () {

            $cookies.put('menu_aberto', '' + !$scope.menu_aberto + '');
            $scope.menu_aberto = !$scope.menu_aberto;

            document.getElementById('buscaMenuEsconde').style.cssText = $scope.menu_aberto ? 'display:inherit' : 'display:none';
        };

        var verificaTelaExpandir = function verificaTelaExpandir() {
            if ($cookies.get('tela_expandir') == 'true') {
                $scope.expandirPagina();
            }
        };

        $scope.expandirPagina = function () {
            $cookies.put('tela_expandir', 'true');
            document.getElementById('main-header').style.cssText = 'display:none';
            document.getElementById('main-sidebar').style.cssText = 'display:none';
            document.getElementById('content-wrapper').style.cssText = 'margin-left: 0px  !important; height: 110vh  !important; padding-top: 0px !important;';
            document.getElementById('main-footer').style.cssText = 'display: none;';
            document.getElementById('main-header').style.cssText = 'display:none';
        };

        $scope.voltaPagina = function () {
            $cookies.put('tela_expandir', 'false');
            document.getElementById('main-header').style.cssText = 'display:block';
            document.getElementById('main-sidebar').style.cssText = 'display:block';
            document.getElementById('content-wrapper').style.cssText = 'margin-left: 230px; min-height: 920px  !important; padding-top: 50px !important;';
            document.getElementById('main-footer').style.cssText = 'display:block';
            document.getElementById('main-header').style.cssText = 'display:block';
        };

        //incializa controller
        init();
    }]);

    angular.module('app').config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
})();

/***/ }),

/***/ 236:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceHeaderHomeCtrl', ['$filter', function ($filter) {
        /*
         * Variáveis
         */
        var notificacoes;

        var notificacoes_nao_lidas;

        /*
         * Métodos set
         */
        this.setNotificacoes = function (notificacoes) {
            this.notificacoes = notificacoes;
        };

        this.setNotificacoesNaoLidas = function (notificacoes_nao_lidas) {
            this.notificacoes_nao_lidas = notificacoes_nao_lidas;
        };

        /*
         * Métodos get
         */
        this.getNotificacoes = function () {
            return this.notificacoes;
        };

        this.getNotificacoesNaoLidas = function () {
            return this.notificacoes_nao_lidas;
        };

        this.getNotificacoesNaoLidasStr = function () {
            var count_nao_lidas_str = '';

            if (this.notificacoes_nao_lidas == 0) {
                count_nao_lidas_str = 'Nenhuma notificação.';
            } else if (this.notificacoes_nao_lidas == 1) {
                count_nao_lidas_str = 'Você possui ' + this.notificacoes_nao_lidas + ' notificação não lida.';
            } else {
                count_nao_lidas_str = 'Você possui ' + this.notificacoes_nao_lidas + ' notificações não lidas.';
            }

            return count_nao_lidas_str;
        };

        /*
         * Função que aplica o estilo e formata a data de todos funcionarios e aniversariantes
         */
        this.getFormataAniversariantes = function (funcionarios) {
            angular.forEach(funcionarios, function (value) {
                value.imagem = 'margin: 0 auto; margin-top: 12px; height: 84px; width: 70px;';
                value.text_class = '';
                value.dt_nascimento = $filter('date')(value.dt_nascimento, 'dd/MM');

                if (value.aniversariante == true) {
                    value.dt_nascimento = 'PARABÉNS !';
                    value.imagem = 'margin: 0 auto; margin-top: 0px; height: 96px; width: 80;';
                    value.text_class = 'text-orange';
                }
            });

            return funcionarios;
        };
    }]);
})();

/***/ }),

/***/ 237:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceModalAniversarioCtrl', ['$uibModal', function ($uibModal) {
        this.modal = function (_funcionarios) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'todosAniversariosModal.html',
                resolve: {
                    funcionarios: function funcionarios() {
                        return _funcionarios;
                    }
                },
                controller: function controller($scope, $uibModalInstance, funcionarios) {
                    $scope.funcionarios = funcionarios;

                    $scope.modalCancelarTodosAniversarios = function () {
                        modalInstance.close();
                    };
                }
            });
        };
    }]);
})();

/***/ }),

/***/ 238:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceModalNotificacaoCtrl', ['$uibModal', 'HeaderFactory', 'serviceHeaderHomeCtrl', '$window', function ($uibModal, HeaderFactory, serviceHeaderHomeCtrl, $window) {
        this.modal = function ($scope) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'sm',
                templateUrl: 'todasNotificacoesModal.html',
                resolve: {},
                scope: $scope,
                controller: function controller($scope, $uibModalInstance, $interval) {
                    var time;

                    // Atualiza a lista de notificacoes
                    var listarNotificacoes = function listarNotificacoes() {
                        if ($scope.$parent) {
                            $scope.notificacoes = $scope.$parent.notificacoes;
                            $scope.count_nao_lidas_int = serviceHeaderHomeCtrl.getNotificacoesNaoLidas();
                            $scope.count_nao_lidas_str = serviceHeaderHomeCtrl.getNotificacoesNaoLidasStr();
                        } else {
                            $interval.cancel(time);
                        }
                    };

                    listarNotificacoes();
                    time = $interval(listarNotificacoes, 1000);

                    $scope.marcarNotificacaoLida = function (notificacao) {

                        HeaderFactory.setNotificaoVisualizada(notificacao).then(function (response) {
                            if (response.data.status == true) {
                                for (var i = 0; i < $scope.$parent.notificacoes.length; i++) {
                                    if ($scope.$parent.notificacoes[i] == notificacao) {
                                        $scope.$parent.notificacoes[i].visualizada = !$scope.$parent.notificacoes[i].visualizada;
                                    }
                                }
                                listarNotificacoes();
                            }
                        });
                    };

                    $scope.abrirNotificacao = function (notificacao) {
                        if (notificacao.visualizada == false) {
                            $scope.marcarNotificacaoLida(notificacao);
                        }
                    };

                    $scope.modalFecharTodasNotificacoes = function () {
                        modalInstance.close();
                    };
                }
            });
        };
    }]);
})();

/***/ }),

/***/ 239:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').service('serviceTicketCtrl', ['$uibModal', 'CamposAdicionaisFactory', 'TicketFactory', '$window', function ($uibModal, CamposAdicionaisFactory, TicketFactory, $window) {
        var global_id;
        this.modal = function (oldValue, $scope) {
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: 'confirmaAlterarDepartamento.html',
                scope: $scope,
                resolve: {},
                controller: function controller($scope, $uibModalInstance) {

                    $scope.confirmaModal = function () {

                        $scope.$parent.ticket.solicitante = {};
                        $scope.$parent.ticket.prioridade = {};
                        $scope.$parent.ticket.assunto = '';
                        $scope.$parent.ticket.categoria = {};
                        $scope.$parent.ticket.subcategoria = {};
                        $scope.$parent.ticket.descricao = '';
                        $scope.$parent.subcategoria = '';

                        $scope.$parent.mostraSolicitante();
                        $scope.$parent.mostraPrioridade();
                        $scope.$parent.mostraCategoria();
                        $scope.$parent.mostraCamposAdicionais();

                        modalInstance.close();
                    };

                    $scope.cancelarModal = function () {

                        var index = $scope.$parent.departamentos.map(function (item) {
                            return item.id;
                        }).indexOf(oldValue.id);

                        $scope.$parent.ticket.departamento = $scope.$parent.departamentos[index];

                        modalInstance.close();
                    };

                    modalInstance.result.finally(function () {});
                }
            });
        };

        this.modalImagem = function (dadosImagemTicket, $scope) {

            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'lg',
                templateUrl: 'imagemModal.html',
                scope: $scope,
                resolve: {},
                controller: function controller($scope) {

                    $scope.id = null;
                    //$scope.imagem = dadosImagemTicket[0].imagem;
                    $scope.carregandoModalImagem = true;
                    $scope.descricao = dadosImagemTicket[0].descricao;
                    $scope.data = dadosImagemTicket[0].created_at;
                    $scope.usuario = dadosImagemTicket[0].nome;
                    $scope.id = dadosImagemTicket[0].id;
                    $scope.permissaoImagemTicketExcluir = dadosImagemTicket[0].permissaoImagemTicketExcluir;

                    TicketFactory.getImagemTicket(dadosImagemTicket[0].id).then(function mySuccess(response) {
                        $scope.imagem = response.data.imagem;
                        $scope.carregandoModalImagem = false;
                    });

                    var hash = moment().format('YYYYMMDDHHmmss');
                    $scope.nome_imagem = 'ticket_imagem_' + hash + '.jpeg';

                    $scope.confirmaModal = function () {
                        //console.log();
                    };

                    $scope.closeModal = function () {
                        modalInstance.close();
                    };

                    $scope.deletarImagem = function () {

                        var mensagem = "Confirma a exclusão da imagem ?";

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
                                    TicketFactory.destroyImagemTicket($scope.id).then(function mySuccess(response) {
                                        $window.location.reload();
                                    }, function (error) {
                                        modalInstance.close();
                                    });
                                };

                                $scope.modalCancelarAlterarStatus = function () {
                                    modalInstance.close();
                                };
                            }
                        });
                    };

                    modalInstance.result.finally(function () {});
                }

            });
        };
    }]);
})();

/***/ })

},[232]);