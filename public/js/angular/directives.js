webpackJsonp([12],{

/***/ 217:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(218);
__webpack_require__(219);
__webpack_require__(220);
__webpack_require__(221);
__webpack_require__(222);
__webpack_require__(223);
__webpack_require__(224);
module.exports = __webpack_require__(225);


/***/ }),

/***/ 218:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').directive('modalConfirm', function () {

        return {

            restrict: 'E',
            controller: 'modalConfirmCtrl'
        };
    });
})();

/***/ }),

/***/ 219:
/***/ (function(module, exports) {

(function () {

	'use strict';

	angular.module('app').directive('msgSuccess', function () {

		return {
			restrict: 'E',
			templateUrl: '/templates/msg-success.html'
		};
	});
})();

/***/ }),

/***/ 220:
/***/ (function(module, exports) {

(function () {

	'use strict';

	angular.module('app').directive('msgError', function () {

		return {
			restrict: 'E',
			replace: true,
			templateUrl: '/templates/msg-error.html'
		};
	});
})();

/***/ }),

/***/ 221:
/***/ (function(module, exports) {

/**
 * Checklist-model
 * AngularJS directive for list of checkboxes
 * https://github.com/vitalets/checklist-model
 * License: MIT http://opensource.org/licenses/MIT
 */

/* commonjs package manager support (eg componentjs) */
if (typeof module !== "undefined" && typeof exports !== "undefined" && module.exports === exports) {
  module.exports = 'checklist-model';
}

angular.module('checklist-model', []).directive('checklistModel', ['$parse', '$compile', function ($parse, $compile) {
  // contains
  function contains(arr, item, comparator) {
    if (angular.isArray(arr)) {
      for (var i = arr.length; i--;) {
        if (comparator(arr[i], item)) {
          return true;
        }
      }
    }
    return false;
  }

  // add
  function add(arr, item, comparator) {
    arr = angular.isArray(arr) ? arr : [];
    if (!contains(arr, item, comparator)) {
      arr.push(item);
    }
    return arr;
  }

  // remove
  function remove(arr, item, comparator) {
    if (angular.isArray(arr)) {
      for (var i = arr.length; i--;) {
        if (comparator(arr[i], item)) {
          arr.splice(i, 1);
          break;
        }
      }
    }
    return arr;
  }

  // http://stackoverflow.com/a/19228302/1458162
  function postLinkFn(scope, elem, attrs) {
    // exclude recursion, but still keep the model
    var checklistModel = attrs.checklistModel;
    attrs.$set("checklistModel", null);
    // compile with `ng-model` pointing to `checked`
    $compile(elem)(scope);
    attrs.$set("checklistModel", checklistModel);

    // getter for original model
    var checklistModelGetter = $parse(checklistModel);
    var checklistChange = $parse(attrs.checklistChange);
    var checklistBeforeChange = $parse(attrs.checklistBeforeChange);
    var ngModelGetter = $parse(attrs.ngModel);

    var comparator = function comparator(a, b) {
      if (!isNaN(a) && !isNaN(b)) {
        return String(a) === String(b);
      } else {
        return angular.equals(a, b);
      }
    };

    if (attrs.hasOwnProperty('checklistComparator')) {
      if (attrs.checklistComparator[0] == '.') {
        var comparatorExpression = attrs.checklistComparator.substring(1);
        comparator = function comparator(a, b) {
          return a[comparatorExpression] === b[comparatorExpression];
        };
      } else {
        comparator = $parse(attrs.checklistComparator)(scope.$parent);
      }
    }

    // watch UI checked change
    var unbindModel = scope.$watch(attrs.ngModel, function (newValue, oldValue) {
      if (newValue === oldValue) {
        return;
      }

      if (checklistBeforeChange && checklistBeforeChange(scope) === false) {
        ngModelGetter.assign(scope, contains(checklistModelGetter(scope.$parent), getChecklistValue(), comparator));
        return;
      }

      setValueInChecklistModel(getChecklistValue(), newValue);

      if (checklistChange) {
        checklistChange(scope);
      }
    });

    // watches for value change of checklistValue
    var unbindCheckListValue = scope.$watch(getChecklistValue, function (newValue, oldValue) {
      if (newValue != oldValue && angular.isDefined(oldValue) && scope[attrs.ngModel] === true) {
        var current = checklistModelGetter(scope.$parent);
        checklistModelGetter.assign(scope.$parent, remove(current, oldValue, comparator));
        checklistModelGetter.assign(scope.$parent, add(current, newValue, comparator));
      }
    }, true);

    var unbindDestroy = scope.$on('$destroy', destroy);

    function destroy() {
      unbindModel();
      unbindCheckListValue();
      unbindDestroy();
    }

    function getChecklistValue() {
      return attrs.checklistValue ? $parse(attrs.checklistValue)(scope.$parent) : attrs.value;
    }

    function setValueInChecklistModel(value, checked) {
      var current = checklistModelGetter(scope.$parent);
      if (angular.isFunction(checklistModelGetter.assign)) {
        if (checked === true) {
          checklistModelGetter.assign(scope.$parent, add(current, value, comparator));
        } else {
          checklistModelGetter.assign(scope.$parent, remove(current, value, comparator));
        }
      }
    }

    // declare one function to be used for both $watch functions
    function setChecked(newArr, oldArr) {
      if (checklistBeforeChange && checklistBeforeChange(scope) === false) {
        setValueInChecklistModel(getChecklistValue(), ngModelGetter(scope));
        return;
      }
      ngModelGetter.assign(scope, contains(newArr, getChecklistValue(), comparator));
    }

    // watch original model change
    // use the faster $watchCollection method if it's available
    if (angular.isFunction(scope.$parent.$watchCollection)) {
      scope.$parent.$watchCollection(checklistModel, setChecked);
    } else {
      scope.$parent.$watch(checklistModel, setChecked, true);
    }
  }

  return {
    restrict: 'A',
    priority: 1000,
    terminal: true,
    scope: true,
    compile: function compile(tElement, tAttrs) {

      if (!tAttrs.checklistValue && !tAttrs.value) {
        throw 'You should provide `value` or `checklist-value`.';
      }

      // by default ngModel is 'checked', so we set it if not specified
      if (!tAttrs.ngModel) {
        // local scope var storing individual checkbox model
        tAttrs.$set("ngModel", "checked");
      }

      return postLinkFn;
    }
  };
}]);

/***/ }),

/***/ 222:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').directive('migalha', function () {

        return {

            restrict: 'E',
            replace: true,
            controller: 'migalhaDePaoCtrl',
            templateUrl: '/templates/migalhas-de-pao.html',
            scope: {
                titulo: "@",
                descricao: "@"
            }
        };
    });
})();

/***/ }),

/***/ 223:
/***/ (function(module, exports) {

(function () {

  'use strict';

  angular.module('app').directive('customOnChange', function () {
    return {
      restrict: 'A',
      link: function link(scope, element, attrs) {
        var onChangeHandler = scope.$eval(attrs.customOnChange);
        element.bind('change', onChangeHandler);
      }
    };
  });
})();

/***/ }),

/***/ 224:
/***/ (function(module, exports) {

(function () {

    'use strict';

    angular.module('app').directive('acessoPermissao', function () {
        return {
            restrict: 'E',
            controller: 'acessoPermissaoCtrl',
            templateUrl: '/templates/acesso-permissao.html',
            scope: { pagina: '@' }
        };
    });
})();

/***/ }),

/***/ 225:
/***/ (function(module, exports) {

(function () {
    'use strict';

    angular.module('app').directive('starRating', function () {
        return {
            restrict: 'A',
            template: '<ul class="rating">' + '<li ng-repeat="star in stars" ng-class="star" ng-click="toggle($index)">' + '<i class="fa fa-star"></i>' + '</li>' + '</ul>',
            scope: {
                ratingValue: '=',
                max: '=',
                onRatingSelected: '&'
            },
            link: function link(scope, elem, attrs) {

                var updateStars = function updateStars() {
                    scope.stars = [];
                    for (var i = 0; i < scope.max; i++) {
                        scope.stars.push({
                            filled: i < scope.ratingValue
                        });
                    }
                };

                scope.toggle = function (index) {
                    scope.ratingValue = index + 1;
                    scope.onRatingSelected({
                        rating: index + 1
                    });
                };

                scope.$watch('ratingValue', function (oldVal, newVal) {
                    if (newVal) {
                        updateStars();
                    }
                });
            }
        };
    });
})();

/***/ })

},[217]);