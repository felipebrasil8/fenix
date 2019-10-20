webpackJsonp([15],{

/***/ 226:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(227);
__webpack_require__(228);
module.exports = __webpack_require__(229);


/***/ }),

/***/ 227:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('modalConfirmCtrl', ['$scope', '$http', '$uibModal', function ($scope, $http, $uibModal) {

        var _elemento = "";
        var _lista = "";
        var _entidade = "";
        var _index = "";

        $scope.modalConfirm = function (elemento, lista, entidade) {

            _elemento = elemento;
            _lista = lista;
            _entidade = entidade;
            $scope.elemento = elemento;
            var mensagem = "Confirma a alteração de status de " + _elemento.nome + " ?";

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
                            url: "/" + _entidade + "/" + _elemento.id
                        }).then(function mySuccess(response) {

                            _index = $scope.lista.indexOf(_elemento);
                            $scope.lista[_index].ativo = !$scope.lista[_index].ativo;

                            modalInstance.close();
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
    }]);
})();

/***/ }),

/***/ 228:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').controller('migalhaDePaoCtrl', ['$scope', '$http', function ($scope, $http) {

        var migalhas = _migalhas;

        var init = function init() {

            $scope.migalhas = migalhas;
        };

        init();
    }]);
})();

/*
*/

/***/ }),

/***/ 229:
/***/ (function(module, exports) {

(function () {

  'use strict';

  angular.module('app').controller('acessoPermissaoCtrl', ['$scope', '$rootScope', '$http', 'serviceAcessoCtrl', function ($scope, $rootScope, $http, serviceAcessoCtrl) {
    var permissoes = _permissoes;

    var init = function init() {

      $scope.exibir = true;
      $scope.todos = false;
      $scope.isNumber = angular.isNumber;

      $scope.roles = serviceAcessoCtrl.roles(permissoes);
      $scope.permissoes = serviceAcessoCtrl.listarPermissoes(permissoes);

      $scope.user = {
        roles: []
      };

      if ($scope.pagina == 'visualizar' || $scope.pagina == 'editar' || $scope.pagina == 'copiar') {
        var acesso_permissao = _acesso_permissao;

        angular.forEach(acesso_permissao, function (itens) {
          $scope.user.roles.push(itens.permissao_id);
        });

        if ($scope.pagina == 'visualizar' || $scope.pagina == 'copiar') {
          $scope.exibir = false;
        } else if ($scope.pagina == 'editar') {
          serviceAcessoCtrl.setPermissoesChecados($scope.user.roles);
          $scope.todos = serviceAcessoCtrl.verificaTodosPermissoesChecados();
        }
      }
    };

    $scope.checkAll = function () {

      if ($scope.todos == true) {
        angular.forEach($scope.roles, function (permissao) {
          if (permissao.id != '' && $scope.user.roles.indexOf(permissao.id) == -1) {
            $scope.user.roles.push(permissao.id);
          }
        });
      } else {
        $scope.user.roles = [];
      }

      serviceAcessoCtrl.setPermissoesChecados($scope.user.roles);
    };

    $scope.verificaClick = function () {
      serviceAcessoCtrl.setPermissoesChecados($scope.user.roles);

      if (serviceAcessoCtrl.getPermissoesChecados().length == serviceAcessoCtrl.getItens()) {
        $scope.todos = true;
      } else {
        $scope.todos = false;
      }
    };

    $rootScope.limparLista = function () {
      $scope.user.roles = [];
      $scope.todos = false;
      serviceAcessoCtrl.setPermissoesChecados($scope.user.roles);
    };

    init();
  }]);
})();

/***/ })

},[226]);