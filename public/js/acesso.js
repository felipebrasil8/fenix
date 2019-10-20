webpackJsonp([6],{

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var bind = __webpack_require__(6);

/*global toString:true*/

// utils is a library of generic helper functions non-specific to axios

var toString = Object.prototype.toString;

/**
 * Determine if a value is an Array
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Array, otherwise false
 */
function isArray(val) {
  return toString.call(val) === '[object Array]';
}

/**
 * Determine if a value is an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
 */
function isArrayBuffer(val) {
  return toString.call(val) === '[object ArrayBuffer]';
}

/**
 * Determine if a value is a FormData
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an FormData, otherwise false
 */
function isFormData(val) {
  return (typeof FormData !== 'undefined') && (val instanceof FormData);
}

/**
 * Determine if a value is a view on an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
 */
function isArrayBufferView(val) {
  var result;
  if ((typeof ArrayBuffer !== 'undefined') && (ArrayBuffer.isView)) {
    result = ArrayBuffer.isView(val);
  } else {
    result = (val) && (val.buffer) && (val.buffer instanceof ArrayBuffer);
  }
  return result;
}

/**
 * Determine if a value is a String
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a String, otherwise false
 */
function isString(val) {
  return typeof val === 'string';
}

/**
 * Determine if a value is a Number
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Number, otherwise false
 */
function isNumber(val) {
  return typeof val === 'number';
}

/**
 * Determine if a value is undefined
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if the value is undefined, otherwise false
 */
function isUndefined(val) {
  return typeof val === 'undefined';
}

/**
 * Determine if a value is an Object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Object, otherwise false
 */
function isObject(val) {
  return val !== null && typeof val === 'object';
}

/**
 * Determine if a value is a Date
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Date, otherwise false
 */
function isDate(val) {
  return toString.call(val) === '[object Date]';
}

/**
 * Determine if a value is a File
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a File, otherwise false
 */
function isFile(val) {
  return toString.call(val) === '[object File]';
}

/**
 * Determine if a value is a Blob
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Blob, otherwise false
 */
function isBlob(val) {
  return toString.call(val) === '[object Blob]';
}

/**
 * Determine if a value is a Function
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Function, otherwise false
 */
function isFunction(val) {
  return toString.call(val) === '[object Function]';
}

/**
 * Determine if a value is a Stream
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Stream, otherwise false
 */
function isStream(val) {
  return isObject(val) && isFunction(val.pipe);
}

/**
 * Determine if a value is a URLSearchParams object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
 */
function isURLSearchParams(val) {
  return typeof URLSearchParams !== 'undefined' && val instanceof URLSearchParams;
}

/**
 * Trim excess whitespace off the beginning and end of a string
 *
 * @param {String} str The String to trim
 * @returns {String} The String freed of excess whitespace
 */
function trim(str) {
  return str.replace(/^\s*/, '').replace(/\s*$/, '');
}

/**
 * Determine if we're running in a standard browser environment
 *
 * This allows axios to run in a web worker, and react-native.
 * Both environments support XMLHttpRequest, but not fully standard globals.
 *
 * web workers:
 *  typeof window -> undefined
 *  typeof document -> undefined
 *
 * react-native:
 *  typeof document.createElement -> undefined
 */
function isStandardBrowserEnv() {
  return (
    typeof window !== 'undefined' &&
    typeof document !== 'undefined' &&
    typeof document.createElement === 'function'
  );
}

/**
 * Iterate over an Array or an Object invoking a function for each item.
 *
 * If `obj` is an Array callback will be called passing
 * the value, index, and complete array for each item.
 *
 * If 'obj' is an Object callback will be called passing
 * the value, key, and complete object for each property.
 *
 * @param {Object|Array} obj The object to iterate
 * @param {Function} fn The callback to invoke for each item
 */
function forEach(obj, fn) {
  // Don't bother if no value provided
  if (obj === null || typeof obj === 'undefined') {
    return;
  }

  // Force an array if not already something iterable
  if (typeof obj !== 'object' && !isArray(obj)) {
    /*eslint no-param-reassign:0*/
    obj = [obj];
  }

  if (isArray(obj)) {
    // Iterate over array values
    for (var i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // Iterate over object keys
    for (var key in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, key)) {
        fn.call(null, obj[key], key, obj);
      }
    }
  }
}

/**
 * Accepts varargs expecting each argument to be an object, then
 * immutably merges the properties of each object and returns result.
 *
 * When multiple objects contain the same key the later object in
 * the arguments list will take precedence.
 *
 * Example:
 *
 * ```js
 * var result = merge({foo: 123}, {foo: 456});
 * console.log(result.foo); // outputs 456
 * ```
 *
 * @param {Object} obj1 Object to merge
 * @returns {Object} Result of all merge properties
 */
function merge(/* obj1, obj2, obj3, ... */) {
  var result = {};
  function assignValue(val, key) {
    if (typeof result[key] === 'object' && typeof val === 'object') {
      result[key] = merge(result[key], val);
    } else {
      result[key] = val;
    }
  }

  for (var i = 0, l = arguments.length; i < l; i++) {
    forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Extends object a by mutably adding to it the properties of object b.
 *
 * @param {Object} a The object to be extended
 * @param {Object} b The object to copy properties from
 * @param {Object} thisArg The object to bind function to
 * @return {Object} The resulting value of object a
 */
function extend(a, b, thisArg) {
  forEach(b, function assignValue(val, key) {
    if (thisArg && typeof val === 'function') {
      a[key] = bind(val, thisArg);
    } else {
      a[key] = val;
    }
  });
  return a;
}

module.exports = {
  isArray: isArray,
  isArrayBuffer: isArrayBuffer,
  isFormData: isFormData,
  isArrayBufferView: isArrayBufferView,
  isString: isString,
  isNumber: isNumber,
  isObject: isObject,
  isUndefined: isUndefined,
  isDate: isDate,
  isFile: isFile,
  isBlob: isBlob,
  isFunction: isFunction,
  isStream: isStream,
  isURLSearchParams: isURLSearchParams,
  isStandardBrowserEnv: isStandardBrowserEnv,
  forEach: forEach,
  merge: merge,
  extend: extend,
  trim: trim
};


/***/ }),

/***/ 10:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * A `Cancel` is an object that is thrown when an operation is canceled.
 *
 * @class
 * @param {string=} message The message.
 */
function Cancel(message) {
  this.message = message;
}

Cancel.prototype.toString = function toString() {
  return 'Cancel' + (this.message ? ': ' + this.message : '');
};

Cancel.prototype.__CANCEL__ = true;

module.exports = Cancel;


/***/ }),

/***/ 12:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return filtroBus; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue__ = __webpack_require__(155);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_vue__);

var filtroBus = new __WEBPACK_IMPORTED_MODULE_0_vue___default.a();

/***/ }),

/***/ 13:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(14);

/***/ }),

/***/ 14:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);
var bind = __webpack_require__(6);
var Axios = __webpack_require__(15);
var defaults = __webpack_require__(5);

/**
 * Create an instance of Axios
 *
 * @param {Object} defaultConfig The default config for the instance
 * @return {Axios} A new instance of Axios
 */
function createInstance(defaultConfig) {
  var context = new Axios(defaultConfig);
  var instance = bind(Axios.prototype.request, context);

  // Copy axios.prototype to instance
  utils.extend(instance, Axios.prototype, context);

  // Copy context to instance
  utils.extend(instance, context);

  return instance;
}

// Create the default instance to be exported
var axios = createInstance(defaults);

// Expose Axios class to allow class inheritance
axios.Axios = Axios;

// Factory for creating new instances
axios.create = function create(instanceConfig) {
  return createInstance(utils.merge(defaults, instanceConfig));
};

// Expose Cancel & CancelToken
axios.Cancel = __webpack_require__(10);
axios.CancelToken = __webpack_require__(29);
axios.isCancel = __webpack_require__(9);

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};
axios.spread = __webpack_require__(30);

module.exports = axios;

// Allow use of default import syntax in TypeScript
module.exports.default = axios;


/***/ }),

/***/ 15:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var defaults = __webpack_require__(5);
var utils = __webpack_require__(1);
var InterceptorManager = __webpack_require__(24);
var dispatchRequest = __webpack_require__(25);
var isAbsoluteURL = __webpack_require__(27);
var combineURLs = __webpack_require__(28);

/**
 * Create a new instance of Axios
 *
 * @param {Object} instanceConfig The default config for the instance
 */
function Axios(instanceConfig) {
  this.defaults = instanceConfig;
  this.interceptors = {
    request: new InterceptorManager(),
    response: new InterceptorManager()
  };
}

/**
 * Dispatch a request
 *
 * @param {Object} config The config specific for this request (merged with this.defaults)
 */
Axios.prototype.request = function request(config) {
  /*eslint no-param-reassign:0*/
  // Allow for axios('example/url'[, config]) a la fetch API
  if (typeof config === 'string') {
    config = utils.merge({
      url: arguments[0]
    }, arguments[1]);
  }

  config = utils.merge(defaults, this.defaults, { method: 'get' }, config);

  // Support baseURL config
  if (config.baseURL && !isAbsoluteURL(config.url)) {
    config.url = combineURLs(config.baseURL, config.url);
  }

  // Hook up interceptors middleware
  var chain = [dispatchRequest, undefined];
  var promise = Promise.resolve(config);

  this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
    chain.unshift(interceptor.fulfilled, interceptor.rejected);
  });

  this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
    chain.push(interceptor.fulfilled, interceptor.rejected);
  });

  while (chain.length) {
    promise = promise.then(chain.shift(), chain.shift());
  }

  return promise;
};

// Provide aliases for supported request methods
utils.forEach(['delete', 'get', 'head'], function forEachMethodNoData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url
    }));
  };
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, data, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url,
      data: data
    }));
  };
});

module.exports = Axios;


/***/ }),

/***/ 154:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var carregandoStore = {
  _carregando: true,
  set carregando(str) {
    this._carregando = str;
  },
  get carregando() {
    return this._carregando;
  }
};
/* harmony default export */ __webpack_exports__["a"] = (carregandoStore);

/***/ }),

/***/ 156:
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),

/***/ 158:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(2)
/* script */
var __vue_script__ = __webpack_require__(159)
/* template */
var __vue_template__ = __webpack_require__(160)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/vue/modulos/util/Migalha.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-0efcd1fe", Component.options)
  } else {
    hotAPI.reload("data-v-0efcd1fe", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 159:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['titulo', 'migalha', 'descricao'],
  data: function data() {
    return {
      data: []

    };
  }
});

/***/ }),

/***/ 16:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

module.exports = function normalizeHeaderName(headers, normalizedName) {
  utils.forEach(headers, function processHeader(value, name) {
    if (name !== normalizedName && name.toUpperCase() === normalizedName.toUpperCase()) {
      headers[normalizedName] = value;
      delete headers[name];
    }
  });
};


/***/ }),

/***/ 160:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "section",
    { staticClass: "content-header", attrs: { titulo: _vm.titulo } },
    [
      _c("h1", [
        _vm._v("\n\t\t\t" + _vm._s(_vm.titulo) + "\t\t\t\n\t\t\t"),
        _c("small", [_vm._v(" " + _vm._s(_vm.descricao))])
      ]),
      _vm._v(" "),
      _c(
        "ol",
        { staticClass: "breadcrumb" },
        _vm._l(_vm.migalha, function(item) {
          return _c("li", [
            _c("a", { attrs: { href: item.url } }, [
              _c("i", { class: "fa fa-" + item.icone }),
              _vm._v(_vm._s(item.nome) + "\n\t\t\t\t")
            ])
          ])
        })
      )
    ]
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-0efcd1fe", module.exports)
  }
}

/***/ }),

/***/ 161:
/***/ (function(module, exports, __webpack_require__) {

!function(root, factory) {
     true ? module.exports = factory() : "function" == typeof define && define.amd ? define([], factory) : "object" == typeof exports ? exports["vue-js-modal"] = factory() : root["vue-js-modal"] = factory();
}(this, function() {
    return function(modules) {
        function __webpack_require__(moduleId) {
            if (installedModules[moduleId]) return installedModules[moduleId].exports;
            var module = installedModules[moduleId] = {
                i: moduleId,
                l: !1,
                exports: {}
            };
            return modules[moduleId].call(module.exports, module, module.exports, __webpack_require__), 
            module.l = !0, module.exports;
        }
        var installedModules = {};
        return __webpack_require__.m = modules, __webpack_require__.c = installedModules, 
        __webpack_require__.i = function(value) {
            return value;
        }, __webpack_require__.d = function(exports, name, getter) {
            __webpack_require__.o(exports, name) || Object.defineProperty(exports, name, {
                configurable: !1,
                enumerable: !0,
                get: getter
            });
        }, __webpack_require__.n = function(module) {
            var getter = module && module.__esModule ? function() {
                return module.default;
            } : function() {
                return module;
            };
            return __webpack_require__.d(getter, "a", getter), getter;
        }, __webpack_require__.o = function(object, property) {
            return Object.prototype.hasOwnProperty.call(object, property);
        }, __webpack_require__.p = "/dist/", __webpack_require__(__webpack_require__.s = 4);
    }([ function(module, exports) {
        module.exports = function(rawScriptExports, compiledTemplate, scopeId, cssModules) {
            var esModule, scriptExports = rawScriptExports = rawScriptExports || {}, type = typeof rawScriptExports.default;
            "object" !== type && "function" !== type || (esModule = rawScriptExports, scriptExports = rawScriptExports.default);
            var options = "function" == typeof scriptExports ? scriptExports.options : scriptExports;
            if (compiledTemplate && (options.render = compiledTemplate.render, options.staticRenderFns = compiledTemplate.staticRenderFns), 
            scopeId && (options._scopeId = scopeId), cssModules) {
                var computed = options.computed || (options.computed = {});
                Object.keys(cssModules).forEach(function(key) {
                    var module = cssModules[key];
                    computed[key] = function() {
                        return module;
                    };
                });
            }
            return {
                esModule: esModule,
                exports: scriptExports,
                options: options
            };
        };
    }, function(module, exports, __webpack_require__) {
        "use strict";
        Object.defineProperty(exports, "__esModule", {
            value: !0
        });
        var _extends = Object.assign || function(target) {
            for (var i = 1; i < arguments.length; i++) {
                var source = arguments[i];
                for (var key in source) Object.prototype.hasOwnProperty.call(source, key) && (target[key] = source[key]);
            }
            return target;
        }, generateId = exports.generateId = function() {
            var index = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0;
            return function() {
                return (index++).toString();
            };
        }();
        exports.inRange = function(from, to, value) {
            return value < from ? from : value > to ? to : value;
        }, exports.createModalEvent = function() {
            var args = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {};
            return _extends({
                id: generateId(),
                timestamp: Date.now(),
                canceled: !1
            }, args);
        }, exports.getMutationObserver = function() {
            if ("undefined" != typeof window) for (var prefixes = [ "", "WebKit", "Moz", "O", "Ms" ], i = 0; i < prefixes.length; i++) {
                var name = prefixes[i] + "MutationObserver";
                if (name in window) return window[name];
            }
            return !1;
        };
    }, function(module, exports) {
        module.exports = function() {
            var list = [];
            return list.toString = function() {
                for (var result = [], i = 0; i < this.length; i++) {
                    var item = this[i];
                    item[2] ? result.push("@media " + item[2] + "{" + item[1] + "}") : result.push(item[1]);
                }
                return result.join("");
            }, list.i = function(modules, mediaQuery) {
                "string" == typeof modules && (modules = [ [ null, modules, "" ] ]);
                for (var alreadyImportedModules = {}, i = 0; i < this.length; i++) {
                    var id = this[i][0];
                    "number" == typeof id && (alreadyImportedModules[id] = !0);
                }
                for (i = 0; i < modules.length; i++) {
                    var item = modules[i];
                    "number" == typeof item[0] && alreadyImportedModules[item[0]] || (mediaQuery && !item[2] ? item[2] = mediaQuery : mediaQuery && (item[2] = "(" + item[2] + ") and (" + mediaQuery + ")"), 
                    list.push(item));
                }
            }, list;
        };
    }, function(module, exports, __webpack_require__) {
        function addStylesToDom(styles) {
            for (var i = 0; i < styles.length; i++) {
                var item = styles[i], domStyle = stylesInDom[item.id];
                if (domStyle) {
                    domStyle.refs++;
                    for (var j = 0; j < domStyle.parts.length; j++) domStyle.parts[j](item.parts[j]);
                    for (;j < item.parts.length; j++) domStyle.parts.push(addStyle(item.parts[j]));
                    domStyle.parts.length > item.parts.length && (domStyle.parts.length = item.parts.length);
                } else {
                    for (var parts = [], j = 0; j < item.parts.length; j++) parts.push(addStyle(item.parts[j]));
                    stylesInDom[item.id] = {
                        id: item.id,
                        refs: 1,
                        parts: parts
                    };
                }
            }
        }
        function createStyleElement() {
            var styleElement = document.createElement("style");
            return styleElement.type = "text/css", head.appendChild(styleElement), styleElement;
        }
        function addStyle(obj) {
            var update, remove, styleElement = document.querySelector('style[data-vue-ssr-id~="' + obj.id + '"]');
            if (styleElement) {
                if (isProduction) return noop;
                styleElement.parentNode.removeChild(styleElement);
            }
            if (isOldIE) {
                var styleIndex = singletonCounter++;
                styleElement = singletonElement || (singletonElement = createStyleElement()), update = applyToSingletonTag.bind(null, styleElement, styleIndex, !1), 
                remove = applyToSingletonTag.bind(null, styleElement, styleIndex, !0);
            } else styleElement = createStyleElement(), update = applyToTag.bind(null, styleElement), 
            remove = function() {
                styleElement.parentNode.removeChild(styleElement);
            };
            return update(obj), function(newObj) {
                if (newObj) {
                    if (newObj.css === obj.css && newObj.media === obj.media && newObj.sourceMap === obj.sourceMap) return;
                    update(obj = newObj);
                } else remove();
            };
        }
        function applyToSingletonTag(styleElement, index, remove, obj) {
            var css = remove ? "" : obj.css;
            if (styleElement.styleSheet) styleElement.styleSheet.cssText = replaceText(index, css); else {
                var cssNode = document.createTextNode(css), childNodes = styleElement.childNodes;
                childNodes[index] && styleElement.removeChild(childNodes[index]), childNodes.length ? styleElement.insertBefore(cssNode, childNodes[index]) : styleElement.appendChild(cssNode);
            }
        }
        function applyToTag(styleElement, obj) {
            var css = obj.css, media = obj.media, sourceMap = obj.sourceMap;
            if (media && styleElement.setAttribute("media", media), sourceMap && (css += "\n/*# sourceURL=" + sourceMap.sources[0] + " */", 
            css += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + " */"), 
            styleElement.styleSheet) styleElement.styleSheet.cssText = css; else {
                for (;styleElement.firstChild; ) styleElement.removeChild(styleElement.firstChild);
                styleElement.appendChild(document.createTextNode(css));
            }
        }
        var hasDocument = "undefined" != typeof document;
        if ("undefined" != typeof DEBUG && DEBUG && !hasDocument) throw new Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");
        var listToStyles = __webpack_require__(24), stylesInDom = {}, head = hasDocument && (document.head || document.getElementsByTagName("head")[0]), singletonElement = null, singletonCounter = 0, isProduction = !1, noop = function() {}, isOldIE = "undefined" != typeof navigator && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase());
        module.exports = function(parentId, list, _isProduction) {
            isProduction = _isProduction;
            var styles = listToStyles(parentId, list);
            return addStylesToDom(styles), function(newList) {
                for (var mayRemove = [], i = 0; i < styles.length; i++) {
                    var item = styles[i], domStyle = stylesInDom[item.id];
                    domStyle.refs--, mayRemove.push(domStyle);
                }
                newList ? (styles = listToStyles(parentId, newList), addStylesToDom(styles)) : styles = [];
                for (var i = 0; i < mayRemove.length; i++) {
                    var domStyle = mayRemove[i];
                    if (0 === domStyle.refs) {
                        for (var j = 0; j < domStyle.parts.length; j++) domStyle.parts[j]();
                        delete stylesInDom[domStyle.id];
                    }
                }
            };
        };
        var replaceText = function() {
            var textStore = [];
            return function(index, replacement) {
                return textStore[index] = replacement, textStore.filter(Boolean).join("\n");
            };
        }();
    }, function(module, exports, __webpack_require__) {
        "use strict";
        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {
                default: obj
            };
        }
        function getModalsContainer(Vue, options, root) {
            if (!root._dynamicContainer && options.injectModalsContainer) {
                var modalsContainer = document.createElement("div");
                document.body.appendChild(modalsContainer), new Vue({
                    parent: root,
                    render: function(h) {
                        return h(_ModalsContainer2.default);
                    }
                }).$mount(modalsContainer);
            }
            return root._dynamicContainer;
        }
        Object.defineProperty(exports, "__esModule", {
            value: !0
        });
        var _Modal = __webpack_require__(6), _Modal2 = _interopRequireDefault(_Modal), _Dialog = __webpack_require__(5), _Dialog2 = _interopRequireDefault(_Dialog), _ModalsContainer = __webpack_require__(7), _ModalsContainer2 = _interopRequireDefault(_ModalsContainer), Plugin = {
            install: function(Vue) {
                var options = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                this.installed || (this.installed = !0, this.event = new Vue(), this.rootInstance = null, 
                this.componentName = options.componentName || "modal", Vue.prototype.$modal = {
                    show: function(modal, paramsOrProps, params) {
                        var events = arguments.length > 3 && void 0 !== arguments[3] ? arguments[3] : {};
                        if ("string" == typeof modal) return void Plugin.event.$emit("toggle", modal, !0, paramsOrProps);
                        var root = params && params.root ? params.root : Plugin.rootInstance, container = getModalsContainer(Vue, options, root);
                        if (container) return void container.add(modal, paramsOrProps, params, events);
                        console.warn("[vue-js-modal] In order to render dynamic modals, a <modals-container> component must be present on the page");
                    },
                    hide: function(name, params) {
                        Plugin.event.$emit("toggle", name, !1, params);
                    },
                    toggle: function(name, params) {
                        Plugin.event.$emit("toggle", name, void 0, params);
                    }
                }, Vue.component(this.componentName, _Modal2.default), options.dialog && Vue.component("v-dialog", _Dialog2.default), 
                options.dynamic && (Vue.component("modals-container", _ModalsContainer2.default), 
                Vue.mixin({
                    beforeMount: function() {
                        null === Plugin.rootInstance && (Plugin.rootInstance = this.$root);
                    }
                })));
            }
        };
        exports.default = Plugin;
    }, function(module, exports, __webpack_require__) {
        __webpack_require__(21);
        var Component = __webpack_require__(0)(__webpack_require__(8), __webpack_require__(18), null, null);
        Component.options.__file = "/Users/yev.vlasenko2/Projects/vue/vue-js-modal/src/Dialog.vue", 
        Component.esModule && Object.keys(Component.esModule).some(function(key) {
            return "default" !== key && "__esModule" !== key;
        }) && console.error("named exports are not supported in *.vue files."), Component.options.functional && console.error("[vue-loader] Dialog.vue: functional components are not supported with templates, they should use render functions."), 
        module.exports = Component.exports;
    }, function(module, exports, __webpack_require__) {
        __webpack_require__(22);
        var Component = __webpack_require__(0)(__webpack_require__(9), __webpack_require__(19), null, null);
        Component.options.__file = "/Users/yev.vlasenko2/Projects/vue/vue-js-modal/src/Modal.vue", 
        Component.esModule && Object.keys(Component.esModule).some(function(key) {
            return "default" !== key && "__esModule" !== key;
        }) && console.error("named exports are not supported in *.vue files."), Component.options.functional && console.error("[vue-loader] Modal.vue: functional components are not supported with templates, they should use render functions."), 
        module.exports = Component.exports;
    }, function(module, exports, __webpack_require__) {
        var Component = __webpack_require__(0)(__webpack_require__(10), __webpack_require__(17), null, null);
        Component.options.__file = "/Users/yev.vlasenko2/Projects/vue/vue-js-modal/src/ModalsContainer.vue", 
        Component.esModule && Object.keys(Component.esModule).some(function(key) {
            return "default" !== key && "__esModule" !== key;
        }) && console.error("named exports are not supported in *.vue files."), Component.options.functional && console.error("[vue-loader] ModalsContainer.vue: functional components are not supported with templates, they should use render functions."), 
        module.exports = Component.exports;
    }, function(module, exports, __webpack_require__) {
        "use strict";
        Object.defineProperty(exports, "__esModule", {
            value: !0
        }), exports.default = {
            name: "VueJsDialog",
            props: {
                width: {
                    type: [ Number, String ],
                    default: 400
                },
                clickToClose: {
                    type: Boolean,
                    default: !0
                },
                transition: {
                    type: String,
                    default: "fade"
                }
            },
            data: function() {
                return {
                    params: {},
                    defaultButtons: [ {
                        title: "CLOSE"
                    } ]
                };
            },
            computed: {
                buttons: function() {
                    return this.params.buttons || this.defaultButtons;
                },
                buttonStyle: function() {
                    return {
                        flex: "1 1 " + 100 / this.buttons.length + "%"
                    };
                }
            },
            methods: {
                beforeOpened: function(event) {
                    window.addEventListener("keyup", this.onKeyUp), this.params = event.params || {}, 
                    this.$emit("before-opened", event);
                },
                beforeClosed: function(event) {
                    window.removeEventListener("keyup", this.onKeyUp), this.params = {}, this.$emit("before-closed", event);
                },
                click: function(i, event) {
                    var source = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : "click", button = this.buttons[i];
                    button && "function" == typeof button.handler ? button.handler(i, event, {
                        source: source
                    }) : this.$modal.hide("dialog");
                },
                onKeyUp: function(event) {
                    if (13 === event.which && this.buttons.length > 0) {
                        var buttonIndex = 1 === this.buttons.length ? 0 : this.buttons.findIndex(function(button) {
                            return button.default;
                        });
                        -1 !== buttonIndex && this.click(buttonIndex, event, "keypress");
                    }
                }
            }
        };
    }, function(module, exports, __webpack_require__) {
        "use strict";
        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {
                default: obj
            };
        }
        Object.defineProperty(exports, "__esModule", {
            value: !0
        });
        var _extends = Object.assign || function(target) {
            for (var i = 1; i < arguments.length; i++) {
                var source = arguments[i];
                for (var key in source) Object.prototype.hasOwnProperty.call(source, key) && (target[key] = source[key]);
            }
            return target;
        }, _index = __webpack_require__(4), _index2 = _interopRequireDefault(_index), _Resizer = __webpack_require__(16), _Resizer2 = _interopRequireDefault(_Resizer), _util = __webpack_require__(1), _parser = __webpack_require__(12);
        exports.default = {
            name: "VueJsModal",
            props: {
                name: {
                    required: !0,
                    type: String
                },
                delay: {
                    type: Number,
                    default: 0
                },
                resizable: {
                    type: Boolean,
                    default: !1
                },
                adaptive: {
                    type: Boolean,
                    default: !1
                },
                draggable: {
                    type: [ Boolean, String ],
                    default: !1
                },
                scrollable: {
                    type: Boolean,
                    default: !1
                },
                reset: {
                    type: Boolean,
                    default: !1
                },
                overlayTransition: {
                    type: String,
                    default: "overlay-fade"
                },
                transition: {
                    type: String
                },
                clickToClose: {
                    type: Boolean,
                    default: !0
                },
                classes: {
                    type: [ String, Array ],
                    default: "v--modal"
                },
                minWidth: {
                    type: Number,
                    default: 0,
                    validator: function(value) {
                        return value >= 0;
                    }
                },
                minHeight: {
                    type: Number,
                    default: 0,
                    validator: function(value) {
                        return value >= 0;
                    }
                },
                maxWidth: {
                    type: Number,
                    default: 1 / 0
                },
                maxHeight: {
                    type: Number,
                    default: 1 / 0
                },
                width: {
                    type: [ Number, String ],
                    default: 600,
                    validator: _parser.validateNumber
                },
                height: {
                    type: [ Number, String ],
                    default: 300,
                    validator: function(value) {
                        return "auto" === value || (0, _parser.validateNumber)(value);
                    }
                },
                pivotX: {
                    type: Number,
                    default: .5,
                    validator: function(value) {
                        return value >= 0 && value <= 1;
                    }
                },
                pivotY: {
                    type: Number,
                    default: .5,
                    validator: function(value) {
                        return value >= 0 && value <= 1;
                    }
                }
            },
            components: {
                Resizer: _Resizer2.default
            },
            data: function() {
                return {
                    visible: !1,
                    visibility: {
                        modal: !1,
                        overlay: !1
                    },
                    shift: {
                        left: 0,
                        top: 0
                    },
                    modal: {
                        width: 0,
                        widthType: "px",
                        height: 0,
                        heightType: "px",
                        renderedHeight: 0
                    },
                    window: {
                        width: 0,
                        height: 0
                    },
                    mutationObserver: null
                };
            },
            created: function() {
                this.setInitialSize();
            },
            beforeMount: function() {
                var _this = this;
                if (_index2.default.event.$on("toggle", this.handleToggleEvent), window.addEventListener("resize", this.handleWindowResize), 
                this.handleWindowResize(), this.scrollable && !this.isAutoHeight && console.warn('Modal "' + this.name + '" has scrollable flag set to true but height is not "auto" (' + this.height + ")"), 
                this.isAutoHeight) {
                    var MutationObserver = (0, _util.getMutationObserver)();
                    MutationObserver && (this.mutationObserver = new MutationObserver(function(mutations) {
                        _this.updateRenderedHeight();
                    }));
                }
                this.clickToClose && window.addEventListener("keyup", this.handleEscapeKeyUp);
            },
            beforeDestroy: function() {
                _index2.default.event.$off("toggle", this.handleToggleEvent), window.removeEventListener("resize", this.handleWindowResize), 
                this.clickToClose && window.removeEventListener("keyup", this.handleEscapeKeyUp), 
                this.scrollable && document.body.classList.remove("v--modal-block-scroll");
            },
            computed: {
                isAutoHeight: function() {
                    return "auto" === this.modal.heightType;
                },
                position: function() {
                    var window = this.window, shift = this.shift, pivotX = this.pivotX, pivotY = this.pivotY, trueModalWidth = this.trueModalWidth, trueModalHeight = this.trueModalHeight, maxLeft = window.width - trueModalWidth, maxTop = window.height - trueModalHeight, left = shift.left + pivotX * maxLeft, top = shift.top + pivotY * maxTop;
                    return {
                        left: parseInt((0, _util.inRange)(0, maxLeft, left)),
                        top: parseInt((0, _util.inRange)(0, maxTop, top))
                    };
                },
                trueModalWidth: function() {
                    var window = this.window, modal = this.modal, adaptive = this.adaptive, minWidth = this.minWidth, maxWidth = this.maxWidth, value = "%" === modal.widthType ? window.width / 100 * modal.width : modal.width, max = Math.min(window.width, maxWidth);
                    return adaptive ? (0, _util.inRange)(minWidth, max, value) : value;
                },
                trueModalHeight: function() {
                    var window = this.window, modal = this.modal, isAutoHeight = this.isAutoHeight, adaptive = this.adaptive, maxHeight = this.maxHeight, value = "%" === modal.heightType ? window.height / 100 * modal.height : modal.height;
                    if (isAutoHeight) return this.modal.renderedHeight;
                    var max = Math.min(window.height, maxHeight);
                    return adaptive ? (0, _util.inRange)(this.minHeight, max, value) : value;
                },
                overlayClass: function() {
                    return {
                        "v--modal-overlay": !0,
                        scrollable: this.scrollable && this.isAutoHeight
                    };
                },
                modalClass: function() {
                    return [ "v--modal-box", this.classes ];
                },
                modalStyle: function() {
                    return {
                        top: this.position.top + "px",
                        left: this.position.left + "px",
                        width: this.trueModalWidth + "px",
                        height: this.isAutoHeight ? "auto" : this.trueModalHeight + "px"
                    };
                }
            },
            methods: {
                handleToggleEvent: function(name, state, params) {
                    if (this.name === name) {
                        var nextState = void 0 === state ? !this.visible : state;
                        this.toggle(nextState, params);
                    }
                },
                setInitialSize: function() {
                    var modal = this.modal, width = (0, _parser.parseNumber)(this.width), height = (0, 
                    _parser.parseNumber)(this.height);
                    modal.width = width.value, modal.widthType = width.type, modal.height = height.value, 
                    modal.heightType = height.type;
                },
                handleEscapeKeyUp: function(event) {
                    27 === event.which && this.visible && this.$modal.hide(this.name);
                },
                handleWindowResize: function() {
                    this.window.width = window.innerWidth, this.window.height = window.innerHeight;
                },
                createModalEvent: function() {
                    var args = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {};
                    return (0, _util.createModalEvent)(_extends({
                        name: this.name,
                        ref: this.$refs.modal
                    }, args));
                },
                handleModalResize: function(event) {
                    this.modal.widthType = "px", this.modal.width = event.size.width, this.modal.heightType = "px", 
                    this.modal.height = event.size.height;
                    var size = this.modal.size;
                    this.$emit("resize", this.createModalEvent({
                        size: size
                    }));
                },
                toggle: function(nextState, params) {
                    var reset = this.reset, scrollable = this.scrollable, visible = this.visible;
                    if (visible !== nextState) {
                        var beforeEventName = visible ? "before-close" : "before-open";
                        "before-open" === beforeEventName ? (document.activeElement && "BODY" !== document.activeElement.tagName && document.activeElement.blur && document.activeElement.blur(), 
                        reset && (this.setInitialSize(), this.shift.left = 0, this.shift.top = 0), scrollable && document.body.classList.add("v--modal-block-scroll")) : scrollable && document.body.classList.remove("v--modal-block-scroll");
                        var stopEventExecution = !1, stop = function() {
                            stopEventExecution = !0;
                        }, beforeEvent = this.createModalEvent({
                            stop: stop,
                            state: nextState,
                            params: params
                        });
                        this.$emit(beforeEventName, beforeEvent), stopEventExecution || (this.visible = nextState, 
                        this.visible ? this.startOpeningModal() : this.startClosingModal());
                    }
                },
                getDraggableElement: function() {
                    var selector = "string" != typeof this.draggable ? ".v--modal-box" : this.draggable;
                    return selector ? this.$refs.overlay.querySelector(selector) : null;
                },
                handleBackgroundClick: function() {
                    this.clickToClose && this.toggle(!1);
                },
                startOpeningModal: function() {
                    var _this2 = this;
                    this.visibility.overlay = !0, setTimeout(function() {
                        _this2.visibility.modal = !0;
                    }, this.delay);
                },
                startClosingModal: function() {
                    var _this3 = this;
                    this.visibility.modal = !1, setTimeout(function() {
                        _this3.visibility.overlay = !1;
                    }, this.delay);
                },
                addDraggableListeners: function() {
                    var _this4 = this;
                    if (this.draggable) {
                        var dragger = this.getDraggableElement();
                        if (dragger) {
                            var startX = 0, startY = 0, cachedShiftX = 0, cachedShiftY = 0, getPosition = function(event) {
                                return event.touches && event.touches.length > 0 ? event.touches[0] : event;
                            }, handleDraggableMousedown = function(event) {
                                var target = event.target;
                                if (!target || "INPUT" !== target.nodeName) {
                                    var _getPosition = getPosition(event), clientX = _getPosition.clientX, clientY = _getPosition.clientY;
                                    document.addEventListener("mousemove", _handleDraggableMousemove), document.addEventListener("touchmove", _handleDraggableMousemove), 
                                    document.addEventListener("mouseup", _handleDraggableMouseup), document.addEventListener("touchend", _handleDraggableMouseup), 
                                    startX = clientX, startY = clientY, cachedShiftX = _this4.shift.left, cachedShiftY = _this4.shift.top;
                                }
                            }, _handleDraggableMousemove = function(event) {
                                var _getPosition2 = getPosition(event), clientX = _getPosition2.clientX, clientY = _getPosition2.clientY;
                                _this4.shift.left = cachedShiftX + clientX - startX, _this4.shift.top = cachedShiftY + clientY - startY, 
                                event.preventDefault();
                            }, _handleDraggableMouseup = function _handleDraggableMouseup(event) {
                                document.removeEventListener("mousemove", _handleDraggableMousemove), document.removeEventListener("touchmove", _handleDraggableMousemove), 
                                document.removeEventListener("mouseup", _handleDraggableMouseup), document.removeEventListener("touchend", _handleDraggableMouseup), 
                                event.preventDefault();
                            };
                            dragger.addEventListener("mousedown", handleDraggableMousedown), dragger.addEventListener("touchstart", handleDraggableMousedown);
                        }
                    }
                },
                removeDraggableListeners: function() {},
                updateRenderedHeight: function() {
                    this.$refs.modal && (this.modal.renderedHeight = this.$refs.modal.getBoundingClientRect().height);
                },
                connectObserver: function() {
                    this.mutationObserver && this.mutationObserver.observe(this.$refs.overlay, {
                        childList: !0,
                        attributes: !0,
                        subtree: !0
                    });
                },
                disconnectObserver: function() {
                    this.mutationObserver && this.mutationObserver.disconnect();
                },
                beforeTransitionEnter: function() {
                    this.connectObserver();
                },
                afterTransitionEnter: function() {
                    this.addDraggableListeners(), this.$emit("opened", this.createModalEvent({
                        state: !0
                    }));
                },
                afterTransitionLeave: function() {
                    this.removeDraggableListeners(), this.disconnectObserver(), this.$emit("closed", this.createModalEvent({
                        state: !1
                    }));
                }
            }
        };
    }, function(module, exports, __webpack_require__) {
        "use strict";
        Object.defineProperty(exports, "__esModule", {
            value: !0
        });
        var _extends = Object.assign || function(target) {
            for (var i = 1; i < arguments.length; i++) {
                var source = arguments[i];
                for (var key in source) Object.prototype.hasOwnProperty.call(source, key) && (target[key] = source[key]);
            }
            return target;
        }, _util = __webpack_require__(1);
        exports.default = {
            data: function() {
                return {
                    modals: []
                };
            },
            created: function() {
                this.$root._dynamicContainer = this;
            },
            methods: {
                add: function(component) {
                    var componentAttrs = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {}, _this = this, modalAttrs = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {}, modalListeners = arguments[3], id = (0, 
                    _util.generateId)(), name = modalAttrs.name || "_dynamic_modal_" + id;
                    this.modals.push({
                        id: id,
                        modalAttrs: _extends({}, modalAttrs, {
                            name: name
                        }),
                        modalListeners: modalListeners,
                        component: component,
                        componentAttrs: componentAttrs
                    }), this.$nextTick(function() {
                        _this.$modal.show(name);
                    });
                },
                remove: function(id) {
                    for (var i in this.modals) if (this.modals[i].id === id) return void this.modals.splice(i, 1);
                }
            }
        };
    }, function(module, exports, __webpack_require__) {
        "use strict";
        Object.defineProperty(exports, "__esModule", {
            value: !0
        });
        var _util = __webpack_require__(1);
        exports.default = {
            name: "VueJsModalResizer",
            props: {
                minHeight: {
                    type: Number,
                    default: 0
                },
                minWidth: {
                    type: Number,
                    default: 0
                }
            },
            data: function() {
                return {
                    clicked: !1,
                    size: {}
                };
            },
            mounted: function() {
                this.$el.addEventListener("mousedown", this.start, !1);
            },
            computed: {
                className: function() {
                    return {
                        "vue-modal-resizer": !0,
                        clicked: this.clicked
                    };
                }
            },
            methods: {
                start: function(event) {
                    this.clicked = !0, window.addEventListener("mousemove", this.mousemove, !1), window.addEventListener("mouseup", this.stop, !1), 
                    event.stopPropagation(), event.preventDefault();
                },
                stop: function() {
                    this.clicked = !1, window.removeEventListener("mousemove", this.mousemove, !1), 
                    window.removeEventListener("mouseup", this.stop, !1), this.$emit("resize-stop", {
                        element: this.$el.parentElement,
                        size: this.size
                    });
                },
                mousemove: function(event) {
                    this.resize(event);
                },
                resize: function(event) {
                    var el = this.$el.parentElement;
                    if (el) {
                        var width = event.clientX - el.offsetLeft, height = event.clientY - el.offsetTop;
                        width = (0, _util.inRange)(this.minWidth, window.innerWidth, width), height = (0, 
                        _util.inRange)(this.minHeight, window.innerHeight, height), this.size = {
                            width: width,
                            height: height
                        }, el.style.width = width + "px", el.style.height = height + "px", this.$emit("resize", {
                            element: el,
                            size: this.size
                        });
                    }
                }
            }
        };
    }, function(module, exports, __webpack_require__) {
        "use strict";
        Object.defineProperty(exports, "__esModule", {
            value: !0
        });
        var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(obj) {
            return typeof obj;
        } : function(obj) {
            return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
        }, types = [ {
            name: "px",
            regexp: new RegExp("^[-+]?[0-9]*.?[0-9]+px$")
        }, {
            name: "%",
            regexp: new RegExp("^[-+]?[0-9]*.?[0-9]+%$")
        }, {
            name: "px",
            regexp: new RegExp("^[-+]?[0-9]*.?[0-9]+$")
        } ], getType = function(value) {
            if ("auto" === value) return {
                type: value,
                value: 0
            };
            for (var i = 0; i < types.length; i++) {
                var type = types[i];
                if (type.regexp.test(value)) return {
                    type: type.name,
                    value: parseFloat(value)
                };
            }
            return {
                type: "",
                value: value
            };
        }, parseNumber = exports.parseNumber = function(value) {
            switch (void 0 === value ? "undefined" : _typeof(value)) {
              case "number":
                return {
                    type: "px",
                    value: value
                };

              case "string":
                return getType(value);

              default:
                return {
                    type: "",
                    value: value
                };
            }
        };
        exports.validateNumber = function(value) {
            if ("string" == typeof value) {
                var _value = parseNumber(value);
                return ("%" === _value.type || "px" === _value.type) && _value.value > 0;
            }
            return value >= 0;
        };
    }, function(module, exports, __webpack_require__) {
        exports = module.exports = __webpack_require__(2)(), exports.push([ module.i, "\n.vue-dialog div {\n  box-sizing: border-box;\n}\n.vue-dialog .dialog-flex {\n  width: 100%;\n  height: 100%;\n}\n.vue-dialog .dialog-content {\n  flex: 1 0 auto;\n  width: 100%;\n  padding: 15px;\n  font-size: 14px;\n}\n.vue-dialog .dialog-c-title {\n  font-weight: 600;\n  padding-bottom: 15px;\n}\n.vue-dialog .dialog-c-text {\n}\n.vue-dialog .vue-dialog-buttons {\n  display: flex;\n  flex: 0 1 auto;\n  width: 100%;\n  border-top: 1px solid #eee;\n}\n.vue-dialog .vue-dialog-buttons-none {\n  width: 100%;\n  padding-bottom: 15px;\n}\n.vue-dialog-button {\n  font-size: 12px !important;\n  background: transparent;\n  padding: 0;\n  margin: 0;\n  border: 0;\n  cursor: pointer;\n  box-sizing: border-box;\n  line-height: 40px;\n  height: 40px;\n  color: inherit;\n  font: inherit;\n  outline: none;\n}\n.vue-dialog-button:hover {\n  background: rgba(0, 0, 0, 0.01);\n}\n.vue-dialog-button:active {\n  background: rgba(0, 0, 0, 0.025);\n}\n.vue-dialog-button:not(:first-of-type) {\n  border-left: 1px solid #eee;\n}\n", "" ]);
    }, function(module, exports, __webpack_require__) {
        exports = module.exports = __webpack_require__(2)(), exports.push([ module.i, "\n.v--modal-block-scroll {\n  overflow: hidden;\n  width: 100vw;\n}\n.v--modal-overlay {\n  position: fixed;\n  box-sizing: border-box;\n  left: 0;\n  top: 0;\n  width: 100%;\n  height: 100vh;\n  background: rgba(0, 0, 0, 0.2);\n  z-index: 999;\n  opacity: 1;\n}\n.v--modal-overlay.scrollable {\n  height: 100%;\n  min-height: 100vh;\n  overflow-y: auto;\n  -webkit-overflow-scrolling: touch;\n}\n.v--modal-overlay .v--modal-background-click {\n  min-height: 100%;\n  width: 100%;\n}\n.v--modal-overlay .v--modal-box {\n  position: relative;\n  overflow: hidden;\n  box-sizing: border-box;\n}\n.v--modal-overlay.scrollable .v--modal-box {\n  margin-bottom: 2px;\n}\n.v--modal {\n  background-color: white;\n  text-align: left;\n  border-radius: 3px;\n  box-shadow: 0 20px 60px -2px rgba(27, 33, 58, 0.4);\n  padding: 0;\n}\n.v--modal.v--modal-fullscreen {\n  width: 100vw;\n  height: 100vh;\n  margin: 0;\n  left: 0;\n  top: 0;\n}\n.v--modal-top-right {\n  display: block;\n  position: absolute;\n  right: 0;\n  top: 0;\n}\n.overlay-fade-enter-active,\n.overlay-fade-leave-active {\n  transition: all 0.2s;\n}\n.overlay-fade-enter,\n.overlay-fade-leave-active {\n  opacity: 0;\n}\n.nice-modal-fade-enter-active,\n.nice-modal-fade-leave-active {\n  transition: all 0.4s;\n}\n.nice-modal-fade-enter,\n.nice-modal-fade-leave-active {\n  opacity: 0;\n  transform: translateY(-20px);\n}\n", "" ]);
    }, function(module, exports, __webpack_require__) {
        exports = module.exports = __webpack_require__(2)(), exports.push([ module.i, "\n.vue-modal-resizer {\n  display: block;\n  overflow: hidden;\n  position: absolute;\n  width: 12px;\n  height: 12px;\n  right: 0;\n  bottom: 0;\n  z-index: 9999999;\n  background: transparent;\n  cursor: se-resize;\n}\n.vue-modal-resizer::after {\n  display: block;\n  position: absolute;\n  content: '';\n  background: transparent;\n  left: 0;\n  top: 0;\n  width: 0;\n  height: 0;\n  border-bottom: 10px solid #ddd;\n  border-left: 10px solid transparent;\n}\n.vue-modal-resizer.clicked::after {\n  border-bottom: 10px solid #369be9;\n}\n", "" ]);
    }, function(module, exports, __webpack_require__) {
        __webpack_require__(23);
        var Component = __webpack_require__(0)(__webpack_require__(11), __webpack_require__(20), null, null);
        Component.options.__file = "/Users/yev.vlasenko2/Projects/vue/vue-js-modal/src/Resizer.vue", 
        Component.esModule && Object.keys(Component.esModule).some(function(key) {
            return "default" !== key && "__esModule" !== key;
        }) && console.error("named exports are not supported in *.vue files."), Component.options.functional && console.error("[vue-loader] Resizer.vue: functional components are not supported with templates, they should use render functions."), 
        module.exports = Component.exports;
    }, function(module, exports, __webpack_require__) {
        module.exports = {
            render: function() {
                var _vm = this, _h = _vm.$createElement, _c = _vm._self._c || _h;
                return _c("div", {
                    attrs: {
                        id: "modals-container"
                    }
                }, _vm._l(_vm.modals, function(modal) {
                    return _c("modal", _vm._g(_vm._b({
                        key: modal.id,
                        on: {
                            closed: function($event) {
                                _vm.remove(modal.id);
                            }
                        }
                    }, "modal", modal.modalAttrs, !1), modal.modalListeners), [ _c(modal.component, _vm._g(_vm._b({
                        tag: "component",
                        on: {
                            close: function($event) {
                                _vm.$modal.hide(modal.modalAttrs.name);
                            }
                        }
                    }, "component", modal.componentAttrs, !1), _vm.$listeners)) ], 1);
                }));
            },
            staticRenderFns: []
        }, module.exports.render._withStripped = !0;
    }, function(module, exports, __webpack_require__) {
        module.exports = {
            render: function() {
                var _vm = this, _h = _vm.$createElement, _c = _vm._self._c || _h;
                return _c("modal", {
                    attrs: {
                        name: "dialog",
                        height: "auto",
                        classes: [ "v--modal", "vue-dialog", this.params.class ],
                        width: _vm.width,
                        "pivot-y": .3,
                        adaptive: !0,
                        clickToClose: _vm.clickToClose,
                        transition: _vm.transition
                    },
                    on: {
                        "before-open": _vm.beforeOpened,
                        "before-close": _vm.beforeClosed,
                        opened: function($event) {
                            _vm.$emit("opened", $event);
                        },
                        closed: function($event) {
                            _vm.$emit("closed", $event);
                        }
                    }
                }, [ _c("div", {
                    staticClass: "dialog-content"
                }, [ _vm.params.title ? _c("div", {
                    staticClass: "dialog-c-title",
                    domProps: {
                        innerHTML: _vm._s(_vm.params.title || "")
                    }
                }) : _vm._e(), _vm._v(" "), _c("div", {
                    staticClass: "dialog-c-text",
                    domProps: {
                        innerHTML: _vm._s(_vm.params.text || "")
                    }
                }) ]), _vm._v(" "), _vm.buttons ? _c("div", {
                    staticClass: "vue-dialog-buttons"
                }, _vm._l(_vm.buttons, function(button, i) {
                    return _c("button", {
                        key: i,
                        class: button.class || "vue-dialog-button",
                        style: _vm.buttonStyle,
                        attrs: {
                            type: "button"
                        },
                        domProps: {
                            innerHTML: _vm._s(button.title)
                        },
                        on: {
                            click: function($event) {
                                $event.stopPropagation(), _vm.click(i, $event);
                            }
                        }
                    }, [ _vm._v("\n      " + _vm._s(button.title) + "\n    ") ]);
                })) : _c("div", {
                    staticClass: "vue-dialog-buttons-none"
                }) ]);
            },
            staticRenderFns: []
        }, module.exports.render._withStripped = !0;
    }, function(module, exports, __webpack_require__) {
        module.exports = {
            render: function() {
                var _vm = this, _h = _vm.$createElement, _c = _vm._self._c || _h;
                return _c("transition", {
                    attrs: {
                        name: _vm.overlayTransition
                    }
                }, [ _vm.visibility.overlay ? _c("div", {
                    ref: "overlay",
                    class: _vm.overlayClass,
                    attrs: {
                        "aria-expanded": _vm.visibility.overlay.toString(),
                        "data-modal": _vm.name
                    }
                }, [ _c("div", {
                    staticClass: "v--modal-background-click",
                    on: {
                        mousedown: function($event) {
                            if ($event.target !== $event.currentTarget) return null;
                            _vm.handleBackgroundClick($event);
                        },
                        touchstart: function($event) {
                            if ($event.target !== $event.currentTarget) return null;
                            _vm.handleBackgroundClick($event);
                        }
                    }
                }, [ _c("div", {
                    staticClass: "v--modal-top-right"
                }, [ _vm._t("top-right") ], 2), _vm._v(" "), _c("transition", {
                    attrs: {
                        name: _vm.transition
                    },
                    on: {
                        "before-enter": _vm.beforeTransitionEnter,
                        "after-enter": _vm.afterTransitionEnter,
                        "after-leave": _vm.afterTransitionLeave
                    }
                }, [ _vm.visibility.modal ? _c("div", {
                    ref: "modal",
                    class: _vm.modalClass,
                    style: _vm.modalStyle
                }, [ _vm._t("default"), _vm._v(" "), _vm.resizable && !_vm.isAutoHeight ? _c("resizer", {
                    attrs: {
                        "min-width": _vm.minWidth,
                        "min-height": _vm.minHeight
                    },
                    on: {
                        resize: _vm.handleModalResize
                    }
                }) : _vm._e() ], 2) : _vm._e() ]) ], 1) ]) : _vm._e() ]);
            },
            staticRenderFns: []
        }, module.exports.render._withStripped = !0;
    }, function(module, exports, __webpack_require__) {
        module.exports = {
            render: function() {
                var _vm = this, _h = _vm.$createElement;
                return (_vm._self._c || _h)("div", {
                    class: _vm.className
                });
            },
            staticRenderFns: []
        }, module.exports.render._withStripped = !0;
    }, function(module, exports, __webpack_require__) {
        var content = __webpack_require__(13);
        "string" == typeof content && (content = [ [ module.i, content, "" ] ]), content.locals && (module.exports = content.locals);
        __webpack_require__(3)("237a7ca4", content, !1);
    }, function(module, exports, __webpack_require__) {
        var content = __webpack_require__(14);
        "string" == typeof content && (content = [ [ module.i, content, "" ] ]), content.locals && (module.exports = content.locals);
        __webpack_require__(3)("2790b368", content, !1);
    }, function(module, exports, __webpack_require__) {
        var content = __webpack_require__(15);
        "string" == typeof content && (content = [ [ module.i, content, "" ] ]), content.locals && (module.exports = content.locals);
        __webpack_require__(3)("02ec91af", content, !1);
    }, function(module, exports) {
        module.exports = function(parentId, list) {
            for (var styles = [], newStyles = {}, i = 0; i < list.length; i++) {
                var item = list[i], id = item[0], css = item[1], media = item[2], sourceMap = item[3], part = {
                    id: parentId + ":" + i,
                    css: css,
                    media: media,
                    sourceMap: sourceMap
                };
                newStyles[id] ? newStyles[id].parts.push(part) : styles.push(newStyles[id] = {
                    id: id,
                    parts: [ part ]
                });
            }
            return styles;
        };
    } ]);
});

/***/ }),

/***/ 162:
/***/ (function(module, exports, __webpack_require__) {

/*!
 * JavaScript Cookie v2.1.4
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
(function () {

  const Cookies = __webpack_require__(163)

  Number.isInteger = Number.isInteger || function(value) {
      return typeof value === 'number' && isFinite(value) &&
        Math.floor(value) === value
    }

  const VueCookie = {
    install: function(Vue, option) {
      Vue.prototype.$cookie = this
      Vue.cookie = this
    },
    set: function(name, value, expires, path) {
        expires = expires || 7
      if (Number.isInteger(expires)) {
        return Cookies.set(name, value, {expires: expires, path: path || '/'})
      } else {
        console.error('Expires in VueCookie: Expected an integer value')
      }

    },
    get: function(name) {
      return Cookies.get(name)
    },
    remove: function(name, path) {
      this.set(name, '', -1, path)
    }
  }
  if (true) {
    module.exports = VueCookie;
  } else if (typeof define == "function" && define.amd) {
    define([], function(){ return VueCookie; })
  } else if (window.Vue) {
    window.VueCookie = VueCookie;
    Vue.use(VueCookie);
  }
})()




/***/ }),

/***/ 163:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * JavaScript Cookie v2.2.0
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
;(function (factory) {
	var registeredInModuleLoader = false;
	if (true) {
		!(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
		registeredInModuleLoader = true;
	}
	if (true) {
		module.exports = factory();
		registeredInModuleLoader = true;
	}
	if (!registeredInModuleLoader) {
		var OldCookies = window.Cookies;
		var api = window.Cookies = factory();
		api.noConflict = function () {
			window.Cookies = OldCookies;
			return api;
		};
	}
}(function () {
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function init (converter) {
		function api (key, value, attributes) {
			var result;
			if (typeof document === 'undefined') {
				return;
			}

			// Write

			if (arguments.length > 1) {
				attributes = extend({
					path: '/'
				}, api.defaults, attributes);

				if (typeof attributes.expires === 'number') {
					var expires = new Date();
					expires.setMilliseconds(expires.getMilliseconds() + attributes.expires * 864e+5);
					attributes.expires = expires;
				}

				// We're using "expires" because "max-age" is not supported by IE
				attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

				try {
					result = JSON.stringify(value);
					if (/^[\{\[]/.test(result)) {
						value = result;
					}
				} catch (e) {}

				if (!converter.write) {
					value = encodeURIComponent(String(value))
						.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);
				} else {
					value = converter.write(value, key);
				}

				key = encodeURIComponent(String(key));
				key = key.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent);
				key = key.replace(/[\(\)]/g, escape);

				var stringifiedAttributes = '';

				for (var attributeName in attributes) {
					if (!attributes[attributeName]) {
						continue;
					}
					stringifiedAttributes += '; ' + attributeName;
					if (attributes[attributeName] === true) {
						continue;
					}
					stringifiedAttributes += '=' + attributes[attributeName];
				}
				return (document.cookie = key + '=' + value + stringifiedAttributes);
			}

			// Read

			if (!key) {
				result = {};
			}

			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all. Also prevents odd result when
			// calling "get()"
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			var rdecode = /(%[0-9A-Z]{2})+/g;
			var i = 0;

			for (; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				var cookie = parts.slice(1).join('=');

				if (!this.json && cookie.charAt(0) === '"') {
					cookie = cookie.slice(1, -1);
				}

				try {
					var name = parts[0].replace(rdecode, decodeURIComponent);
					cookie = converter.read ?
						converter.read(cookie, name) : converter(cookie, name) ||
						cookie.replace(rdecode, decodeURIComponent);

					if (this.json) {
						try {
							cookie = JSON.parse(cookie);
						} catch (e) {}
					}

					if (key === name) {
						result = cookie;
						break;
					}

					if (!key) {
						result[name] = cookie;
					}
				} catch (e) {}
			}

			return result;
		}

		api.set = api;
		api.get = function (key) {
			return api.call(api, key);
		};
		api.getJSON = function () {
			return api.apply({
				json: true
			}, [].slice.call(arguments));
		};
		api.defaults = {};

		api.remove = function (key, attributes) {
			api(key, '', extend(attributes, {
				expires: -1
			}));
		};

		api.withConverter = init;

		return api;
	}

	return init(function () {});
}));


/***/ }),

/***/ 17:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var createError = __webpack_require__(8);

/**
 * Resolve or reject a Promise based on response status.
 *
 * @param {Function} resolve A function that resolves the promise.
 * @param {Function} reject A function that rejects the promise.
 * @param {object} response The response.
 */
module.exports = function settle(resolve, reject, response) {
  var validateStatus = response.config.validateStatus;
  // Note: status is not exposed by XDomainRequest
  if (!response.status || !validateStatus || validateStatus(response.status)) {
    resolve(response);
  } else {
    reject(createError(
      'Request failed with status code ' + response.status,
      response.config,
      null,
      response
    ));
  }
};


/***/ }),

/***/ 172:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(2)
/* script */
var __vue_script__ = __webpack_require__(178)
/* template */
var __vue_template__ = __webpack_require__(179)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/vue/modulos/util/ModalConfirmeEvent.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3db22be6", Component.options)
  } else {
    hotAPI.reload("data-v-3db22be6", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 173:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(174)
}
var normalizeComponent = __webpack_require__(2)
/* script */
var __vue_script__ = __webpack_require__(176)
/* template */
var __vue_template__ = __webpack_require__(177)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/vue/modulos/util/FiltroPesquisa.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-af8dee8a", Component.options)
  } else {
    hotAPI.reload("data-v-af8dee8a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 174:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(175);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(4)("92418eee", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-af8dee8a\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./FiltroPesquisa.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-af8dee8a\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./FiltroPesquisa.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 175:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)(false);
// imports


// module
exports.push([module.i, "\n.escondeFiltro{\n   display: none;\n}\n.apareceFiltro{\n   displ\n   ay: block;\n}\n\n", ""]);

// exports


/***/ }),

/***/ 176:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__filtroDataTable_js__ = __webpack_require__(12);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__carregandoStore_js__ = __webpack_require__(154);
var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['source', 'filtro', 'filtroPadrao', 'cookiePrefixo', 'url', 'semCookie'],
    data: function data() {
        return {
            pages: [],
            filtroAberto: '',
            carregandoStore: __WEBPACK_IMPORTED_MODULE_1__carregandoStore_js__["a" /* default */]
        };
    },

    methods: {
        pesquisar: function pesquisar(ev) {
            this.$cookie.set(this.cookiePrefixo + 'filtro_aberto', true, 1);
            this.filtro.pagina = this.filtroPadrao.pagina;
            this.setCookies();
            this.search();
            this.$emit('pesquisabotton');
        },
        limpar: function limpar(ev) {
            this.$cookie.set(this.cookiePrefixo + 'filtro_aberto', false, 1);
            this.setValorPadrao();
            this.setCookies();
            this.search();
            this.$emit('limpar');
        },
        filtroOpen: function filtroOpen() {
            this.filtroAberto = !this.filtroAberto;
        },
        setCookies: function setCookies() {
            for (var prop in this.filtro) {
                var valor = this.filtro[prop] == null ? '' : this.filtro[prop];
                this.$cookie.set(this.cookiePrefixo + prop, valor, 1);
            }
        },
        verificaCoockies: function verificaCoockies() {

            for (var prop in this.filtro) {

                if (typeof this.$cookie.get(this.cookiePrefixo + prop) !== "undefined" && this.$cookie.get(this.cookiePrefixo + prop) != this.filtroPadrao[prop]) {
                    if (prop != 'por_pagina' && prop != 'pagina' && prop != 'sort') {
                        if (this.getCookieFiltroAberto()) {
                            this.filtroAberto = true;
                        }
                    }
                    this.getCookies();
                    this.$emit('getCookies', false);
                    return true;
                }
            }
            this.limpar();
        },
        setValorPadrao: function setValorPadrao() {
            for (var prop in this.filtro) {
                var s = _typeof(this.filtro[prop]);

                if (s == "object") {
                    if (new Date(this.filtroPadrao[prop]) != "Invalid Date") {
                        this.filtro[prop] = this.filtroPadrao[prop];
                    } else {
                        this.filtro[prop] = JSON.parse(this.filtroPadrao[prop]);
                    }
                } else if (s == "boolean") {
                    this.filtro[prop] = this.filtroPadrao[prop] == "true" ? true : false;
                } else {
                    this.filtro[prop] = this.filtroPadrao[prop];
                }
            }
        },
        getCookies: function getCookies() {
            for (var prop in this.filtro) {
                var s = _typeof(this.filtro[prop]);

                if (s == "boolean") {
                    this.filtro[prop] = this.$cookie.get(this.cookiePrefixo + prop) == "true" ? true : false;
                } else if (s == "object") {
                    if (new Date(this.$cookie.get(this.cookiePrefixo + prop)) != "Invalid Date") {
                        this.filtro[prop] = this.$cookie.get(this.cookiePrefixo + prop);
                    } else {
                        this.filtro[prop] = JSON.parse(this.$cookie.get(this.cookiePrefixo + prop));
                    }
                } else {
                    this.filtro[prop] = this.$cookie.get(this.cookiePrefixo + prop);
                }
            }
            this.search();
        },
        search: function search() {
            var _this = this;

            this.carregandoStore.carregando = true;
            window.axios.post(this.url + 'search?page=' + this.filtro.pagina, this.filtro).then(function (response) {
                _this.carregandoStore.carregando = false;
                _this.$emit('pesquisar', response.data);
            });
        },
        getCookieFiltroAberto: function getCookieFiltroAberto() {
            return typeof this.$cookie.get(this.cookiePrefixo + 'filtro_aberto') !== "undefined" && this.$cookie.get(this.cookiePrefixo + 'filtro_aberto') == 'true' ? true : false;
        }
    },
    mounted: function mounted() {
        var _this2 = this;

        this.verificaCoockies();
        __WEBPACK_IMPORTED_MODULE_0__filtroDataTable_js__["a" /* filtroBus */].$on('editTable', function (event) {
            _this2.search();
        });
    }
});

/***/ }),

/***/ 177:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    {
      staticClass: "box box-default ",
      class: _vm.filtroAberto == true ? "" : "collapsed-box"
    },
    [
      _c("form", [
        _c(
          "div",
          {
            staticClass: "box-header with-border cursor-pointer",
            attrs: { "data-widget": "collapse" },
            on: { click: _vm.filtroOpen }
          },
          [
            _c("i", {
              staticClass: "fa fa-search cursor-pointer fa-minus",
              staticStyle: { display: "none" }
            }),
            _vm._v(" "),
            _c("i", { staticClass: "fa fa-search cursor-pointer" }),
            _vm._v(" "),
            _c(
              "h3",
              {
                staticClass: "box-title cursor-pointer",
                attrs: { "data-widget": "collapse" }
              },
              [_vm._v("Filtrar resultado")]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "box-tools pull-right" }, [
              _c(
                "button",
                {
                  staticClass: "btn btn-box-tool",
                  attrs: { type: "button", "data-widget": "collapse" }
                },
                [
                  _c("i", {
                    staticClass: "fa ",
                    class:
                      _vm.filtroAberto == true
                        ? "fa-chevron-up"
                        : "fa-chevron-down"
                  })
                ]
              )
            ])
          ]
        ),
        _vm._v(" "),
        _c(
          "div",
          {
            staticClass: "box-body ",
            class: _vm.filtroAberto == true ? "apareceFiltro" : "escondeFiltro"
          },
          [_vm._t("default")],
          2
        ),
        _vm._v(" "),
        _c(
          "div",
          {
            staticClass: "box-footer ",
            class: _vm.filtroAberto == true ? "apareceFiltro" : "escondeFiltro"
          },
          [
            _c("div", { staticClass: "pull-left" }, [
              _c(
                "button",
                {
                  staticClass: "btn btn-primary",
                  attrs: { type: "submit" },
                  on: { click: _vm.pesquisar }
                },
                [_vm._v("Pesquisar")]
              ),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "btn btn-default",
                  attrs: { type: "button" },
                  on: { click: _vm.limpar }
                },
                [_vm._v("Limpar")]
              )
            ]),
            _vm._v(" "),
            _vm._t("exportacao")
          ],
          2
        )
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-af8dee8a", module.exports)
  }
}

/***/ }),

/***/ 178:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var MODAL_WIDTH = 600;
/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['erros'],
    name: 'ModalConfirm',
    data: function data() {
        return {
            modalWidth: MODAL_WIDTH,
            mensagem: "",
            errors: [],
            edit: false,
            titulo: '',
            id: ''
        };
    },

    watch: {
        erros: function erros() {
            this.errors = this.erros;
        }
    },
    methods: {
        beforeOpen: function beforeOpen(event) {
            this.limpaResponse();
            this.titulo = event.params.titulo;
            this.id = event.params.id;
        },
        opened: function opened() {
            this.$el.querySelector('.btn-sim').focus();
        },
        beforeClose: function beforeClose() {
            this.titulo = '';
            this.id = '';
        },
        limpaResponse: function limpaResponse() {
            this.errors = [];
            this.mensagem = "";
        },
        confirma: function confirma() {
            if (this.id != '') {
                this.$emit('confirma', this.id);
            }
        }
    },
    created: function created() {
        this.modalWidth = window.innerWidth < MODAL_WIDTH ? MODAL_WIDTH / 2 : MODAL_WIDTH;
    }
});

/***/ }),

/***/ 179:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "modal",
    {
      attrs: {
        name: "modal-confirm",
        transition: "pop-out",
        width: _vm.modalWidth,
        height: "auto",
        "before-close": "beforeClose"
      },
      on: { "before-open": _vm.beforeOpen, opened: _vm.opened }
    },
    [
      _c("div", { staticClass: "modal-content" }, [
        _c("div", { staticClass: "modal-header" }, [
          _c(
            "button",
            {
              staticClass: "close",
              attrs: { type: "button" },
              on: {
                click: function($event) {
                  _vm.$modal.hide("modal-confirm")
                }
              }
            },
            [_c("span", { attrs: { "aria-hidden": "true" } }, [_vm._v("×")])]
          ),
          _vm._v(" "),
          _c(
            "h4",
            { staticClass: "modal-title", attrs: { id: "modal-title" } },
            [_vm._v(_vm._s(_vm.titulo))]
          )
        ]),
        _vm._v(" "),
        _vm.errors.length > 0 || _vm.mensagem != ""
          ? _c("div", { staticClass: "modal-body" }, [
              _c("div", { staticClass: "row" }, [
                _c(
                  "div",
                  { staticClass: "col-md-12" },
                  _vm._l(_vm.errors, function(value) {
                    return _c(
                      "div",
                      {
                        staticClass: "callout no-margin-bottom callout-danger",
                        staticStyle: { "margin-top": "15px" }
                      },
                      [_c("div", [_vm._v(_vm._s(value))])]
                    )
                  })
                )
              ]),
              _vm._v(" "),
              _vm.mensagem
                ? _c("div", { staticClass: "row" }, [
                    _c("div", { staticClass: "col-md-12" }, [
                      _c(
                        "div",
                        {
                          staticClass:
                            "callout no-margin-bottom callout-success",
                          staticStyle: { "margin-top": "15px" }
                        },
                        [_c("div", [_vm._v(_vm._s(_vm.mensagem))])]
                      )
                    ])
                  ])
                : _vm._e()
            ])
          : _vm._e(),
        _vm._v(" "),
        _c("div", { staticClass: "modal-footer" }, [
          _c(
            "button",
            {
              staticClass: "btn btn-danger",
              staticStyle: { "margin-right": "10px" },
              attrs: { type: "button" },
              on: {
                click: function($event) {
                  _vm.$modal.hide("modal-confirm")
                }
              }
            },
            [_vm._v("NÃO")]
          ),
          _vm._v(" "),
          _c(
            "button",
            {
              staticClass: "btn btn-primary btn-sim",
              attrs: { type: "button", autofocus: "" },
              on: { click: _vm.confirma }
            },
            [_vm._v("SIM")]
          )
        ])
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-3db22be6", module.exports)
  }
}

/***/ }),

/***/ 18:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Update an Error with the specified config, error code, and response.
 *
 * @param {Error} error The error to update.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 @ @param {Object} [response] The response.
 * @returns {Error} The error.
 */
module.exports = function enhanceError(error, config, code, response) {
  error.config = config;
  if (code) {
    error.code = code;
  }
  error.response = response;
  return error;
};


/***/ }),

/***/ 180:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(181)
}
var normalizeComponent = __webpack_require__(2)
/* script */
var __vue_script__ = __webpack_require__(183)
/* template */
var __vue_template__ = __webpack_require__(184)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/vue/modulos/util/Datatable.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-368af136", Component.options)
  } else {
    hotAPI.reload("data-v-368af136", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 181:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(182);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(4)("12b7091d", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-368af136\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Datatable.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-368af136\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Datatable.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 182:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)(false);
// imports


// module
exports.push([module.i, "\n.datatebleVue > td\n{\n    padding-bottom: 3px !important;\n}\n.paginateDatatebleVue\n{\n    display: inline-block;\n    padding-left: 0;\n}\n.paginateDatatebleVue > li\n{\n    display: inline;\n}\n.paginateDatatebleVue>li>a, \n.paginateDatatebleVue>li>span \n{\n    position: relative;\n    float: left;\n    padding: 4px 6px;\n    line-height: 1.42857143;\n    border-radius: 4px;\n}\n.paginateDatatebleVue>.active>a, \n.paginateDatatebleVue>.active>a:focus, \n.paginateDatatebleVue>.active>a:hover, \n.paginateDatatebleVue>.active>span, \n.paginateDatatebleVue>.active>span:focus, \n.paginateDatatebleVue>.active>span:hover \n{\n    z-index: 3;\n    background-color: #F9F9F9;\n    border: 1px solid #979797;\n}\n.paginateDatatebleVue>li>.desabled,\n.paginateDatatebleVue>li>.desabled:hover \n{\n    color: #898989 !important;\n}\n.paginateDatatebleVue>li>a>i\n{\n    color: #333;\n}\n.paginateDatatebleVue>li>a:hover, \n.paginateDatatebleVue>li>a>i:hover, \n.paginateDatatebleVue>li>span:hover \n{\n    color: #367fa9;\n}\n.sortSelectd\n{\n    color: #3c8dbc;\n    text-shadow: 0px 0px 3px #65b7e7;\n}\n.sortDisablede\n{\n    color: #000;\n}\n\n", ""]);

// exports


/***/ }),

/***/ 183:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__filtroDataTable_js__ = __webpack_require__(12);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__carregandoStore_js__ = __webpack_require__(154);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ __webpack_exports__["default"] = ({

    props: ['dados', 'url', 'filtro', 'canObj', 'cookiePrefixo', 'thead', 'status', 'acoes'],

    data: function data() {
        return {
            pages: [],
            configuracaoPadrao: {
                por_pagina: 15,
                page: 1
            },
            configuracao: {
                por_pagina: 15,
                page: 1
            },
            erros: '',
            carregandoStore: __WEBPACK_IMPORTED_MODULE_1__carregandoStore_js__["a" /* default */]
        };
    },

    watch: {
        dados: function dados() {
            this.pages = this.generatePagesArray(this.dados.current_page, this.dados.total, this.dados.per_page, 5);
        }
    },
    methods: {
        nextPrev: function nextPrev(ev, page) {

            if (page == 0 || page == this.dados.last_page + 1) {
                return;
            }

            this.navigate(ev, page);
        },


        generatePagesArray: function generatePagesArray(currentPage, collectionLength, rowsPerPage, paginationRange) {
            var pages = [];
            var totalPages = Math.ceil(collectionLength / rowsPerPage);
            var halfWay = Math.ceil(paginationRange / 2);
            var position;

            if (currentPage <= halfWay) {
                position = 'start';
            } else if (totalPages - halfWay < currentPage) {
                position = 'end';
            } else {
                position = 'middle';
            }

            var ellipsesNeeded = paginationRange < totalPages;
            var i = 1;
            while (i <= totalPages && i <= paginationRange) {
                var pageNumber = this.calculatePageNumber(i, currentPage, paginationRange, totalPages);
                var openingEllipsesNeeded = i === 2 && (position === 'middle' || position === 'end');
                var closingEllipsesNeeded = i === paginationRange - 1 && (position === 'middle' || position === 'start');
                if (ellipsesNeeded && (openingEllipsesNeeded || closingEllipsesNeeded)) {
                    pages.push('...');
                } else {
                    pages.push(pageNumber);
                }
                i++;
            }
            return pages;
        },

        calculatePageNumber: function calculatePageNumber(i, currentPage, paginationRange, totalPages) {
            var halfWay = Math.ceil(paginationRange / 2);
            if (i === paginationRange) {
                return totalPages;
            } else if (i === 1) {
                return i;
            } else if (paginationRange < totalPages) {
                if (totalPages - halfWay < currentPage) {
                    return totalPages - paginationRange + i;
                } else if (halfWay < currentPage) {
                    return currentPage - halfWay + i;
                } else {
                    return i;
                }
            } else {
                return i;
            }
        },
        setPerPage: function setPerPage() {
            this.filtro.por_pagina = this.dados.per_page;
            this.filtro.pagina = 1;
            this.$cookie.set(this.cookiePrefixo + 'por_pagina', this.dados.per_page, 1);
            this.$cookie.set(this.cookiePrefixo + 'pagina', 1, 1);
            __WEBPACK_IMPORTED_MODULE_0__filtroDataTable_js__["a" /* filtroBus */].$emit('editTable');
        },
        navigate: function navigate(ev, page) {
            this.filtro.pagina = page;
            this.$cookie.set(this.cookiePrefixo + 'pagina', page, 1);
            __WEBPACK_IMPORTED_MODULE_0__filtroDataTable_js__["a" /* filtroBus */].$emit('editTable');
        },
        showConfirmeModal: function showConfirmeModal(titulo, id) {
            this.$modal.show('modal-confirm', { titulo: titulo, id: id });
        },
        ordenacao: function ordenacao(ev, collun) {
            ev.preventDefault();

            if (ev.shiftKey == false) {
                if (this.filtro.sort.indexOf(collun + ' asc') != -1 || this.filtro.sort.indexOf(collun + ' desc') != -1) {
                    var ordens = this.filtro.sort.split(',');

                    for (var i = 0; i < ordens.length; i++) {
                        var ordem = ordens[i].split(' ');

                        if (ordem[0] == collun) {
                            if (ordem[1] == 'asc') {
                                this.sort = collun + ' ' + 'desc';
                            } else {
                                this.sort = collun + ' ' + 'asc';
                            }
                        }
                    }
                } else {
                    this.sort = collun + ' ' + 'asc';
                }
            } else {
                if (this.filtro.sort.indexOf(collun + ' asc') != -1 || this.filtro.sort.indexOf(collun + ' desc') != -1) {
                    var ordens = this.filtro.sort.split(',');

                    for (var i = 0; i < ordens.length; i++) {
                        if (ordens[i].indexOf(collun + ' asc') != -1) {
                            ordens[i] = collun + ' ' + 'desc';
                        } else if (ordens[i].indexOf(collun + ' desc') != -1) {
                            ordens[i] = collun + ' ' + 'asc';
                        }
                    }
                    this.sort = ordens.join(',');
                } else {
                    this.sort = this.sort + ',' + collun + ' ' + 'asc';
                }
            }

            this.filtro.sort = this.sort;
            this.$cookie.set(this.cookiePrefixo + 'sort', this.filtro.sort, 1);
            __WEBPACK_IMPORTED_MODULE_0__filtroDataTable_js__["a" /* filtroBus */].$emit('editTable');
        },
        ordenacaoIcone: function ordenacaoIcone(collun) {
            if (this.filtro.sort.indexOf(collun) != -1) {
                var ordens = this.filtro.sort.split(',');

                for (var i = 0; i < ordens.length; i++) {
                    var ordem = ordens[i].split(' ');

                    if (ordem[0] == collun) {
                        return ordem[1];
                    }
                }
            }

            return false;
        },
        setStatus: function setStatus(id) {
            var _this = this;

            if (id != '') {
                window.axios.delete(this.url + id).then(function (response) {
                    for (var x = 0; x < _this.dados.data.length; x++) {
                        if (_this.dados.data[x].id == id) {
                            _this.dados.data[x].ativo = !_this.dados.data[x].ativo;
                            _this.id = '';
                            _this.$modal.hide('modal-confirm');
                            return true;
                        }
                    }
                }).catch(function (error) {
                    _this.erros = error.response.data.errors;
                    return 0;
                });
            }
        }
    },
    mounted: function mounted() {
        this.sort = this.filtro.sort;
    }
});

/***/ }),

/***/ 184:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "box box-default" },
    [
      _c("div", { staticClass: "table-responsive" }, [
        _c(
          "table",
          {
            staticClass: "table table-striped table-hover",
            staticStyle: { "table-layout": "fixed", "min-width": "1000px" }
          },
          [
            _c("thead", [
              _c(
                "tr",
                [
                  _vm._l(_vm.thead, function(item) {
                    return _c(
                      "th",
                      {
                        class: item.ordena
                          ? "pointer " + item.class
                          : item.class,
                        attrs: { width: item.width },
                        on: {
                          click: function($event) {
                            item.ordena
                              ? _vm.ordenacao($event, item.coluna)
                              : ""
                          }
                        }
                      },
                      [
                        item.ordena
                          ? _c("span", { staticClass: "naoSelecionavel" }, [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(item.text) +
                                  "\n"
                              ),
                              _vm._v(" "),
                              _c(
                                "span",
                                {
                                  staticStyle: {
                                    "vertical-align": "super",
                                    display: "inline-grid"
                                  }
                                },
                                [
                                  _c("i", {
                                    staticClass: "fa fa-caret-up ",
                                    class:
                                      _vm.ordenacaoIcone(item.coluna) == "asc"
                                        ? "sortSelectd"
                                        : "sortDisablede",
                                    staticStyle: { height: "7px" }
                                  }),
                                  _vm._v(" "),
                                  _c("i", {
                                    staticClass: "fa fa-caret-down ",
                                    class:
                                      _vm.ordenacaoIcone(item.coluna) == "desc"
                                        ? "sortSelectd"
                                        : "sortDisablede",
                                    staticStyle: { height: "7px" }
                                  })
                                ]
                              )
                            ])
                          : _c(
                              "span",
                              {
                                class: item.class,
                                attrs: { width: item.width }
                              },
                              [
                                _vm._v(
                                  "\n                                " +
                                    _vm._s(item.text) +
                                    "\n                            "
                                )
                              ]
                            )
                      ]
                    )
                  }),
                  _vm._v(" "),
                  _vm.status
                    ? _c(
                        "th",
                        {
                          staticClass: "text-center pointer",
                          attrs: { width: "120px" },
                          on: {
                            click: function($event) {
                              _vm.ordenacao($event, "2")
                            }
                          }
                        },
                        [
                          _c("span", { staticClass: "naoSelecionavel" }, [
                            _vm._v(
                              "\n                                Status\n                                "
                            ),
                            _vm._v(" "),
                            _c(
                              "span",
                              {
                                staticStyle: {
                                  "vertical-align": "super",
                                  display: "inline-grid"
                                }
                              },
                              [
                                _c("i", {
                                  staticClass: "fa fa-caret-up ",
                                  class:
                                    _vm.ordenacaoIcone("2") == "asc"
                                      ? "sortSelectd"
                                      : "sortDisablede",
                                  staticStyle: { height: "7px" }
                                }),
                                _vm._v(" "),
                                _c("i", {
                                  staticClass: "fa fa-caret-down",
                                  class:
                                    _vm.ordenacaoIcone("2") == "desc"
                                      ? "sortSelectd"
                                      : "sortDisablede",
                                  staticStyle: { height: "7px" }
                                })
                              ]
                            )
                          ])
                        ]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.acoes
                    ? _c(
                        "th",
                        {
                          staticClass: "text-center naoSelecionavel",
                          attrs: { width: "150px" }
                        },
                        [
                          _vm._v(
                            "\n                            Ações\n                        "
                          )
                        ]
                      )
                    : _vm._e()
                ],
                2
              )
            ]),
            _vm._v(" "),
            _c(
              "tbody",
              _vm._l(_vm.dados.data, function(item) {
                return _c(
                  "tr",
                  { staticClass: "datatebleVue" },
                  [
                    _vm._t("tbody", null, {
                      showConfirmeModal: _vm.showConfirmeModal,
                      item: item
                    }),
                    _vm._v(" "),
                    _vm.status
                      ? _c("td", { staticClass: "text-center" }, [
                          _vm.canObj.status
                            ? _c("div", [
                                _c(
                                  "a",
                                  {
                                    staticClass: "pointer",
                                    attrs: {
                                      title: item.ativo
                                        ? "Ativo, clique para desativar."
                                        : "Inativo, clique para ativar."
                                    },
                                    on: {
                                      click: function($event) {
                                        _vm.showConfirmeModal(
                                          "Confirma a alteração de status de " +
                                            item.nome,
                                          item.id
                                        )
                                      }
                                    }
                                  },
                                  [
                                    _c("i", {
                                      staticClass: "fa ",
                                      class: item.ativo
                                        ? "fa-check-square-o"
                                        : "fa-square-o"
                                    })
                                  ]
                                )
                              ])
                            : _c("div", [
                                _c("i", {
                                  staticClass: "fa ",
                                  class: item.ativo
                                    ? "fa-check-square-o"
                                    : "fa-square-o"
                                })
                              ])
                        ])
                      : _vm._e(),
                    _vm._v(" "),
                    _vm.acoes
                      ? _c("td", { staticClass: "text-center" }, [
                          _vm.canObj.editar
                            ? _c(
                                "a",
                                {
                                  attrs: {
                                    href: _vm.url + item.id + "/edit",
                                    title: "Editar"
                                  }
                                },
                                [
                                  _c("i", {
                                    staticClass: "fa fa-pencil-square-o"
                                  })
                                ]
                              )
                            : _vm._e(),
                          _vm._v(
                            "\n                             \n                            "
                          ),
                          _vm.canObj.copiar
                            ? _c(
                                "a",
                                {
                                  attrs: {
                                    href: _vm.url + item.id + "/copy",
                                    title: "Copiar"
                                  }
                                },
                                [_c("i", { staticClass: "fa fa-files-o" })]
                              )
                            : _vm._e(),
                          _vm._v(
                            "\n                             \n                            "
                          ),
                          _c(
                            "a",
                            {
                              attrs: {
                                href: _vm.url + item.id,
                                title: "Visualizar"
                              }
                            },
                            [_c("i", { staticClass: "fa fa-file-text-o" })]
                          )
                        ])
                      : _vm._e()
                  ],
                  2
                )
              })
            )
          ]
        )
      ]),
      _vm._v(" "),
      _vm.dados.data && _vm.dados.data.length > 0
        ? _c("div", { attrs: { id: "rodape-da-tabela" } }, [
            _c("div", { staticClass: "total col-md-4" }, [
              _vm._v(
                "\n                Total: (" +
                  _vm._s(_vm.dados.from) +
                  " - " +
                  _vm._s(_vm.dados.to) +
                  " de " +
                  _vm._s(_vm.dados.total) +
                  ")\n            "
              )
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "lista-de-paginas col-md-4 text-center" },
              [
                _c("nav", [
                  _c(
                    "ul",
                    {
                      staticClass: "paginateDatatebleVue",
                      staticStyle: { margin: "6px 0" }
                    },
                    [
                      _c(
                        "li",
                        { class: { disable: _vm.dados.current_page == 1 } },
                        [
                          _c(
                            "a",
                            {
                              attrs: { href: "#", "aria-label": "Previous" },
                              on: {
                                click: function($event) {
                                  _vm.nextPrev(
                                    $event,
                                    _vm.dados.current_page - 1
                                  )
                                }
                              }
                            },
                            [
                              _c("i", {
                                staticClass: "fa fa-angle-double-left",
                                attrs: { "aria-hidden": "true" }
                              })
                            ]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _vm._l(_vm.pages, function(page) {
                        return _c(
                          "li",
                          {
                            class: { active: _vm.dados.current_page == page },
                            attrs: { "track-by": "$index" }
                          },
                          [
                            page == "..."
                              ? _c("span", { staticClass: "desabled" }, [
                                  _vm._v(_vm._s(page))
                                ])
                              : _c(
                                  "span",
                                  {
                                    staticClass: "pointer",
                                    on: {
                                      click: function($event) {
                                        _vm.navigate($event, page)
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(page))]
                                )
                          ]
                        )
                      }),
                      _vm._v(" "),
                      _c(
                        "li",
                        {
                          class: {
                            disable:
                              _vm.dados.current_page == _vm.dados.last_page
                          }
                        },
                        [
                          _c(
                            "a",
                            {
                              attrs: { href: "#" },
                              on: {
                                click: function($event) {
                                  _vm.nextPrev(
                                    $event,
                                    _vm.dados.current_page + 1
                                  )
                                }
                              }
                            },
                            [
                              _c("i", {
                                staticClass: "fa fa-angle-double-right",
                                attrs: { "aria-hidden": "true" }
                              })
                            ]
                          )
                        ]
                      )
                    ],
                    2
                  )
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "limite-de-items-por-pagina col-md-4 text-right" },
              [
                _c("label", [
                  _vm._v(
                    "\n                    Registro por página\n                "
                  )
                ]),
                _vm._v(" "),
                _c(
                  "select",
                  {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.dados.per_page,
                        expression: "dados.per_page"
                      }
                    ],
                    on: {
                      change: [
                        function($event) {
                          var $$selectedVal = Array.prototype.filter
                            .call($event.target.options, function(o) {
                              return o.selected
                            })
                            .map(function(o) {
                              var val = "_value" in o ? o._value : o.value
                              return val
                            })
                          _vm.$set(
                            _vm.dados,
                            "per_page",
                            $event.target.multiple
                              ? $$selectedVal
                              : $$selectedVal[0]
                          )
                        },
                        _vm.setPerPage
                      ]
                    }
                  },
                  [
                    _c("option", { attrs: { value: "15" } }, [_vm._v("15")]),
                    _vm._v(" "),
                    _c("option", { attrs: { value: "30" } }, [_vm._v("30")]),
                    _vm._v(" "),
                    _c("option", { attrs: { value: "50" } }, [_vm._v("50")]),
                    _vm._v(" "),
                    _c("option", { attrs: { value: "100" } }, [_vm._v("100")])
                  ]
                )
              ]
            )
          ])
        : _c("div", { attrs: { id: "rodape-da-tabela" } }, [
            _c("div", { staticClass: "total col-md-4" }, [
              _vm._v(
                "\n                 Nenhuma informação encontrada!!! \n            "
              )
            ])
          ]),
      _vm._v(" "),
      _vm.carregandoStore.carregando
        ? _c("div", { staticClass: "overlay" }, [
            _c("i", { staticClass: "fa fa-refresh fa-spin" })
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("vc-modal-confirme", {
        attrs: { erros: _vm.erros },
        on: { confirma: _vm.setStatus }
      })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-368af136", module.exports)
  }
}

/***/ }),

/***/ 19:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

function encode(val) {
  return encodeURIComponent(val).
    replace(/%40/gi, '@').
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, '+').
    replace(/%5B/gi, '[').
    replace(/%5D/gi, ']');
}

/**
 * Build a URL by appending params to the end
 *
 * @param {string} url The base of the url (e.g., http://www.google.com)
 * @param {object} [params] The params to be appended
 * @returns {string} The formatted url
 */
module.exports = function buildURL(url, params, paramsSerializer) {
  /*eslint no-param-reassign:0*/
  if (!params) {
    return url;
  }

  var serializedParams;
  if (paramsSerializer) {
    serializedParams = paramsSerializer(params);
  } else if (utils.isURLSearchParams(params)) {
    serializedParams = params.toString();
  } else {
    var parts = [];

    utils.forEach(params, function serialize(val, key) {
      if (val === null || typeof val === 'undefined') {
        return;
      }

      if (utils.isArray(val)) {
        key = key + '[]';
      }

      if (!utils.isArray(val)) {
        val = [val];
      }

      utils.forEach(val, function parseValue(v) {
        if (utils.isDate(v)) {
          v = v.toISOString();
        } else if (utils.isObject(v)) {
          v = JSON.stringify(v);
        }
        parts.push(encode(key) + '=' + encode(v));
      });
    });

    serializedParams = parts.join('&');
  }

  if (serializedParams) {
    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
  }

  return url;
};


/***/ }),

/***/ 2:
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ 20:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

/**
 * Parse headers into an object
 *
 * ```
 * Date: Wed, 27 Aug 2014 08:58:49 GMT
 * Content-Type: application/json
 * Connection: keep-alive
 * Transfer-Encoding: chunked
 * ```
 *
 * @param {String} headers Headers needing to be parsed
 * @returns {Object} Headers parsed into an object
 */
module.exports = function parseHeaders(headers) {
  var parsed = {};
  var key;
  var val;
  var i;

  if (!headers) { return parsed; }

  utils.forEach(headers.split('\n'), function parser(line) {
    i = line.indexOf(':');
    key = utils.trim(line.substr(0, i)).toLowerCase();
    val = utils.trim(line.substr(i + 1));

    if (key) {
      parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
    }
  });

  return parsed;
};


/***/ }),

/***/ 21:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs have full support of the APIs needed to test
  // whether the request URL is of the same origin as current location.
  (function standardBrowserEnv() {
    var msie = /(msie|trident)/i.test(navigator.userAgent);
    var urlParsingNode = document.createElement('a');
    var originURL;

    /**
    * Parse a URL to discover it's components
    *
    * @param {String} url The URL to be parsed
    * @returns {Object}
    */
    function resolveURL(url) {
      var href = url;

      if (msie) {
        // IE needs attribute set twice to normalize properties
        urlParsingNode.setAttribute('href', href);
        href = urlParsingNode.href;
      }

      urlParsingNode.setAttribute('href', href);

      // urlParsingNode provides the UrlUtils interface - http://url.spec.whatwg.org/#urlutils
      return {
        href: urlParsingNode.href,
        protocol: urlParsingNode.protocol ? urlParsingNode.protocol.replace(/:$/, '') : '',
        host: urlParsingNode.host,
        search: urlParsingNode.search ? urlParsingNode.search.replace(/^\?/, '') : '',
        hash: urlParsingNode.hash ? urlParsingNode.hash.replace(/^#/, '') : '',
        hostname: urlParsingNode.hostname,
        port: urlParsingNode.port,
        pathname: (urlParsingNode.pathname.charAt(0) === '/') ?
                  urlParsingNode.pathname :
                  '/' + urlParsingNode.pathname
      };
    }

    originURL = resolveURL(window.location.href);

    /**
    * Determine if a URL shares the same origin as the current location
    *
    * @param {String} requestURL The URL to test
    * @returns {boolean} True if URL shares the same origin, otherwise false
    */
    return function isURLSameOrigin(requestURL) {
      var parsed = (utils.isString(requestURL)) ? resolveURL(requestURL) : requestURL;
      return (parsed.protocol === originURL.protocol &&
            parsed.host === originURL.host);
    };
  })() :

  // Non standard browser envs (web workers, react-native) lack needed support.
  (function nonStandardBrowserEnv() {
    return function isURLSameOrigin() {
      return true;
    };
  })()
);


/***/ }),

/***/ 211:
/***/ (function(module, exports, __webpack_require__) {

!function(e,A){ true?module.exports=A():"function"==typeof define&&define.amd?define("vue-jstree",[],A):"object"==typeof exports?exports["vue-jstree"]=A():e["vue-jstree"]=A()}(this,function(){return function(e){function A(r){if(t[r])return t[r].exports;var n=t[r]={i:r,l:!1,exports:{}};return e[r].call(n.exports,n,n.exports,A),n.l=!0,n.exports}var t={};return A.m=e,A.c=t,A.i=function(e){return e},A.d=function(e,t,r){A.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:r})},A.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return A.d(t,"a",t),t},A.o=function(e,A){return Object.prototype.hasOwnProperty.call(e,A)},A.p="dist/",A(A.s=4)}([function(e,A){e.exports=function(e,A,t,r,n){var o,a=e=e||{},l=typeof e.default;"object"!==l&&"function"!==l||(o=e,a=e.default);var i="function"==typeof a?a.options:a;A&&(i.render=A.render,i.staticRenderFns=A.staticRenderFns),r&&(i._scopeId=r);var d;if(n?(d=function(e){e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext,e||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),t&&t.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(n)},i._ssrRegister=d):t&&(d=t),d){var g=i.functional,s=g?i.render:i.beforeCreate;g?i.render=function(e,A){return d.call(A),s(e,A)}:i.beforeCreate=s?[].concat(s,d):[d]}return{esModule:o,exports:a,options:i}}},function(e,A,t){function r(e){t(10)}var n=t(0)(t(3),t(9),r,null,null);e.exports=n.exports},function(e,A,t){"use strict";function r(e,A,t){return A in e?Object.defineProperty(e,A,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[A]=t,e}Object.defineProperty(A,"__esModule",{value:!0}),A.default={name:"TreeItem",props:{data:{type:Object,required:!0},textFieldName:{type:String},valueFieldName:{type:String},childrenFieldName:{type:String},itemEvents:{type:Object},wholeRow:{type:Boolean,default:!1},showCheckbox:{type:Boolean,default:!1},allowTransition:{type:Boolean,default:!0},height:{type:Number,default:24},parentItem:{type:Array},draggable:{type:Boolean,default:!1},dragOverBackgroundColor:{type:String},onItemClick:{type:Function,default:function(){return!1}},onItemToggle:{type:Function,default:function(){return!1}},onItemDragStart:{type:Function,default:function(){return!1}},onItemDragEnd:{type:Function,default:function(){return!1}},onItemDrop:{type:Function,default:function(){return!1}},klass:String},data:function(){return{isHover:!1,isDragEnter:!1,model:this.data,maxHeight:0,events:{}}},watch:{isDragEnter:function(e){this.$el.style.backgroundColor=e?this.dragOverBackgroundColor:"inherit"},data:function(e){this.model=e},"model.opened":{handler:function(e,A){this.onItemToggle(this,this.model),this.handleGroupMaxHeight()},deep:!0}},computed:{isFolder:function(){return this.model[this.childrenFieldName]&&this.model[this.childrenFieldName].length},classes:function(){return[{"tree-node":!0},{"tree-open":this.model.opened},{"tree-closed":!this.model.opened},{"tree-leaf":!this.isFolder},{"tree-loading":!!this.model.loading},{"tree-drag-enter":this.isDragEnter},r({},this.klass,!!this.klass)]},anchorClasses:function(){return[{"tree-anchor":!0},{"tree-disabled":this.model.disabled},{"tree-selected":this.model.selected},{"tree-hovered":this.isHover}]},wholeRowClasses:function(){return[{"tree-wholerow":!0},{"tree-wholerow-clicked":this.model.selected},{"tree-wholerow-hovered":this.isHover}]},themeIconClasses:function(){return[{"tree-icon":!0},{"tree-themeicon":!0},r({},this.model.icon,!!this.model.icon),{"tree-themeicon-custom":!!this.model.icon}]},isWholeRow:function(){if(this.wholeRow)return void 0===this.$parent.model||!0===this.$parent.model.opened},groupStyle:function(){return{position:this.model.opened?"":"relative","max-height":this.allowTransition?this.maxHeight+"px":"","transition-duration":this.allowTransition?300*Math.ceil(this.model[this.childrenFieldName].length/100)+"ms":"","transition-property":this.allowTransition?"max-height":"",display:this.allowTransition?"block":this.model.opened?"block":"none"}}},methods:{handleItemToggle:function(e){this.isFolder&&(this.model.opened=!this.model.opened,this.onItemToggle(this,this.model))},handleGroupMaxHeight:function(){if(this.allowTransition){var e=0,A=0;if(this.model.opened){e=this.$children.length;var t=!0,r=!1,n=void 0;try{for(var o,a=this.$children[Symbol.iterator]();!(t=(o=a.next()).done);t=!0){A+=o.value.maxHeight}}catch(e){r=!0,n=e}finally{try{!t&&a.return&&a.return()}finally{if(r)throw n}}}this.maxHeight=e*this.height+A,"tree-item"===this.$parent.$options._componentTag&&this.$parent.handleGroupMaxHeight()}},handleItemClick:function(e){this.model.disabled||(this.model.selected=!this.model.selected,this.onItemClick(this,this.model,e))},handleItemMouseOver:function(){this.isHover=!0},handleItemMouseOut:function(){this.isHover=!1},handleItemDrop:function(e,A,t){this.$el.style.backgroundColor="inherit",this.onItemDrop(e,A,t)}},created:function(){var e=this,A=this,t={click:this.handleItemClick,mouseover:this.handleItemMouseOver,mouseout:this.handleItemMouseOut};for(var r in this.itemEvents)!function(r){var n=e.itemEvents[r];if(t.hasOwnProperty(r)){var o=t[r];t[r]=function(e){o(A,A.model,e),n(A,A.model,e)}}else t[r]=function(e){n(A,A.model,e)}}(r);this.events=t},mounted:function(){this.handleGroupMaxHeight()}}},function(e,A,t){"use strict";function r(e,A,t){return A in e?Object.defineProperty(e,A,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[A]=t,e}Object.defineProperty(A,"__esModule",{value:!0});var n=t(7),o=t.n(n),a="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},l=0;A.default={name:"VJstree",props:{data:{type:Array},size:{type:String,validator:function(e){return["large","small"].indexOf(e)>-1}},showCheckbox:{type:Boolean,default:!1},wholeRow:{type:Boolean,default:!1},noDots:{type:Boolean,default:!1},collapse:{type:Boolean,default:!1},multiple:{type:Boolean,default:!1},allowBatch:{type:Boolean,default:!1},allowTransition:{type:Boolean,default:!0},textFieldName:{type:String,default:"text"},valueFieldName:{type:String,default:"value"},childrenFieldName:{type:String,default:"children"},itemEvents:{type:Object,default:function(){return{}}},async:{type:Function},loadingText:{type:String,default:"Loading..."},draggable:{type:Boolean,default:!1},dragOverBackgroundColor:{type:String,default:"#C9FDC9"},klass:String},data:function(){return{draggedItem:void 0,draggedElm:void 0}},computed:{classes:function(){return[{tree:!0},{"tree-default":!this.size},r({},"tree-default-"+this.size,!!this.size),{"tree-checkbox-selection":!!this.showCheckbox},r({},this.klass,!!this.klass)]},containerClasses:function(){return[{"tree-container-ul":!0},{"tree-children":!0},{"tree-wholerow-ul":!!this.wholeRow},{"tree-no-dots":!!this.noDots}]},sizeHeight:function(){switch(this.size){case"large":return 32;case"small":return 18;default:return 24}}},methods:{initializeData:function(e){if(e&&e.length>0)for(var A in e){var t=this.initializeDataItem(e[A]);e[A]=t,this.initializeData(e[A][this.childrenFieldName])}},initializeDataItem:function(e){function A(e,A,t,r,n){this.id=e.id||l++,this[A]=e[A]||"",this[t]=e[t]||e[A],this.icon=e.icon||"",this.opened=e.opened||n,this.selected=e.selected||!1,this.disabled=e.disabled||!1,this.loading=e.loading||!1,this[r]=e[r]||[]}var t=Object.assign(new A(e,this.textFieldName,this.valueFieldName,this.childrenFieldName,this.collapse),e),r=this;return t.addBefore=function(e,A){var n=r.initializeDataItem(e),o=A.parentItem.findIndex(function(e){return e.id===t.id});A.parentItem.splice(o,0,n)},t.addAfter=function(e,A){var n=r.initializeDataItem(e),o=A.parentItem.findIndex(function(e){return e.id===t.id})+1;A.parentItem.splice(o,0,n)},t.addChild=function(e){var A=r.initializeDataItem(e);t.opened=!0,t[r.childrenFieldName].push(A)},t.openChildren=function(){t.opened=!0,r.handleRecursionNodeChildren(t,function(e){e.opened=!0})},t.closeChildren=function(){t.opened=!1,r.handleRecursionNodeChildren(t,function(e){e.opened=!1})},t},initializeLoading:function(){var e={};return e[this.textFieldName]=this.loadingText,e.disabled=!0,e.loading=!0,this.initializeDataItem(e)},handleRecursionNodeChilds:function(e,A){if(!1!==A(e)&&e.$children&&e.$children.length>0){var t=!0,r=!1,n=void 0;try{for(var o,a=e.$children[Symbol.iterator]();!(t=(o=a.next()).done);t=!0){var l=o.value;l.disabled||this.handleRecursionNodeChilds(l,A)}}catch(e){r=!0,n=e}finally{try{!t&&a.return&&a.return()}finally{if(r)throw n}}}},handleRecursionNodeChildren:function(e,A){if(!1!==A(e)&&e[this.childrenFieldName]&&e[this.childrenFieldName].length>0){var t=!0,r=!1,n=void 0;try{for(var o,a=e[this.childrenFieldName][Symbol.iterator]();!(t=(o=a.next()).done);t=!0){var l=o.value;this.handleRecursionNodeChildren(l,A)}}catch(e){r=!0,n=e}finally{try{!t&&a.return&&a.return()}finally{if(r)throw n}}}},onItemClick:function(e,A,t){this.multiple?this.allowBatch&&this.handleBatchSelectItems(e,A):this.handleSingleSelectItems(e,A),this.$emit("item-click",e,A,t)},handleSingleSelectItems:function(e,A){this.handleRecursionNodeChilds(this,function(e){e.model&&(e.model.selected=!1)}),e.model.selected=!0},handleBatchSelectItems:function(e,A){this.handleRecursionNodeChilds(e,function(A){A.model.disabled||(A.model.selected=e.model.selected)})},onItemToggle:function(e,A,t){e.model.opened&&this.handleAsyncLoad(e.model[this.childrenFieldName],e,A),this.$emit("item-toggle",e,A,t)},handleAsyncLoad:function(e,A,t){var r=this;this.async&&e[0].loading&&this.async(A,function(t){if(t.length>0)for(var n in t){t[n].isLeaf||"object"!==a(t[n][r.childrenFieldName])&&(t[n][r.childrenFieldName]=[r.initializeLoading()]);var o=r.initializeDataItem(t[n]);r.$set(e,n,o)}else A.model[r.childrenFieldName]=[]})},onItemDragStart:function(e,A,t){if(!this.draggable||t.dragDisabled)return!1;e.dataTransfer.effectAllowed="move",e.dataTransfer.setData("text",null),this.draggedElm=e.target,this.draggedItem={item:t,parentItem:A.parentItem,index:A.parentItem.findIndex(function(e){return e.id===t.id})},this.$emit("item-drag-start",A,t,e)},onItemDragEnd:function(e,A,t){this.draggedItem=void 0,this.draggedElm=void 0,this.$emit("item-drag-end",A,t,e)},onItemDrop:function(e,A,t){var r=this;if(!this.draggable||t.dropDisabled)return!1;if(this.$emit("item-drop-before",A,t,this.draggedItem?this.draggedItem.item:void 0,e),this.draggedElm&&this.draggedElm!==e.target&&!this.draggedElm.contains(e.target)&&this.draggedItem){if(this.draggedItem.parentItem===t[this.childrenFieldName]||this.draggedItem.item===t||t[this.childrenFieldName]&&-1!==t[this.childrenFieldName].findIndex(function(e){return e.id===r.draggedItem.item.id}))return;t[this.childrenFieldName]?t[this.childrenFieldName].push(this.draggedItem.item):t[this.childrenFieldName]=[this.draggedItem.item],t.opened=!0;var n=this.draggedItem;this.$nextTick(function(){n.parentItem.splice(n.index,1)}),this.$emit("item-drop",A,t,n.item,e)}}},created:function(){this.initializeData(this.data)},mounted:function(){this.async&&(this.$set(this.data,0,this.initializeLoading()),this.handleAsyncLoad(this.data,this))},components:{TreeItem:o.a}}},function(e,A,t){"use strict";Object.defineProperty(A,"__esModule",{value:!0});var r=t(1),n=t.n(r);n.a.install=function(e){e.component(n.a.name,n.a)},"undefined"!=typeof window&&window.Vue&&window.Vue.use(n.a),A.default=n.a},function(e,A,t){A=e.exports=t(6)(),A.push([e.i,'.tree-children,.tree-container-ul,.tree-node{display:block;margin:0;padding:0;list-style-type:none;list-style-image:none}.tree-children{overflow:hidden}.tree-anchor,.tree-node{white-space:nowrap}.tree-anchor{display:inline-block;color:#000;padding:0 4px 0 1px;margin:0;vertical-align:top;font-size:14px;cursor:pointer}.tree-anchor:focus{outline:0}.tree-anchor,.tree-anchor:active,.tree-anchor:hover,.tree-anchor:link,.tree-anchor:visited{text-decoration:none;color:inherit}.tree-icon,.tree-icon:empty{display:inline-block;text-decoration:none;margin:0;padding:0;vertical-align:top;text-align:center}.tree-ocl{cursor:pointer}.tree-leaf>.tree-ocl{cursor:default}.tree-anchor>.tree-themeicon{margin-right:2px}.tree-anchor>.tree-themeicon-hidden,.tree-hidden,.tree-no-icons .tree-themeicon,.tree-node.tree-hidden{display:none}.tree-rtl .tree-anchor{padding:0 1px 0 4px}.tree-rtl .tree-anchor>.tree-themeicon{margin-left:2px;margin-right:0}.tree-rtl .tree-node{margin-left:0}.tree-rtl .tree-container-ul>.tree-node{margin-right:0}.tree-wholerow-ul{position:relative;display:inline-block;min-width:100%}.tree-wholerow-ul .tree-leaf>.tree-ocl{cursor:pointer}.tree-wholerow-ul .tree-anchor,.tree-wholerow-ul .tree-icon{position:relative}.tree-wholerow-ul .tree-wholerow{width:100%;cursor:pointer;z-index:-1;position:absolute;left:0;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.tree{text-align:left}.tree-default .tree-icon,.tree-default .tree-node{background-repeat:no-repeat;background-color:transparent}.tree-default .tree-anchor,.tree-default .tree-animated,.tree-default .tree-wholerow{transition:background-color .15s,box-shadow .15s}.tree-default .tree-context,.tree-default .tree-hovered{background:#eee;border:0;box-shadow:none}.tree-default .tree-selected{background:#e1e1e1;border:0;box-shadow:none}.tree-default .tree-no-icons .tree-anchor>.tree-themeicon{display:none}.tree-default .tree-disabled{color:#666}.tree-default .tree-disabled.tree-hovered{box-shadow:none}.tree-default .tree-disabled>.tree-icon{opacity:.8;filter:url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'tree-grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#tree-grayscale");filter:gray;-webkit-filter:grayscale(100%)}.tree-default .tree-search{font-style:italic;color:#8b0000;font-weight:700}.tree-default .tree-no-checkboxes .tree-checkbox{display:none!important}.tree-default.tree-checkbox-no-clicked .tree-selected{background:transparent;box-shadow:none}.tree-default.tree-checkbox-no-clicked .tree-selected.tree-hovered{background:#eee}.tree-default.tree-checkbox-no-clicked>.tree-wholerow-ul .tree-wholerow-clicked{background:transparent}.tree-default.tree-checkbox-no-clicked>.tree-wholerow-ul .tree-wholerow-clicked.tree-wholerow-hovered{background:#eee}.tree-default>.tree-striped{min-width:100%;display:inline-block;background:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAkCAMAAAB/qqA+AAAABlBMVEUAAAAAAAClZ7nPAAAAAnRSTlMNAMM9s3UAAAAXSURBVHjajcEBAQAAAIKg/H/aCQZ70AUBjAATb6YPDgAAAABJRU5ErkJggg==") 0 0 repeat}.tree-default>.tree-wholerow-ul .tree-hovered,.tree-default>.tree-wholerow-ul .tree-selected{background:transparent;box-shadow:none;border-radius:0}.tree-default .tree-wholerow{box-sizing:border-box}.tree-default .tree-wholerow-hovered{background:#eee}.tree-default .tree-wholerow-clicked{background:#e1e1e1}.tree-default .tree-node{min-height:24px;line-height:24px;margin-left:30px;min-width:24px}.tree-default .tree-anchor,.tree-default .tree-icon{line-height:24px;height:24px}.tree-default .tree-icon{width:24px}.tree-default .tree-icon:empty{width:24px;height:24px;line-height:24px}.tree-default.tree-rtl .tree-node{margin-right:24px}.tree-default .tree-wholerow{height:24px}.tree-default .tree-icon,.tree-default .tree-node{background-image:url("data:image/png;base64,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")}.tree-default .tree-node{background-position:-292px -4px;background-repeat:repeat-y}.tree-default .tree-last{background:transparent}.tree-default .tree-open>.tree-ocl{background-position:-132px -4px}.tree-default .tree-closed>.tree-ocl{background-position:-100px -4px}.tree-default .tree-leaf>.tree-ocl{background-position:-68px -4px}.tree-default .tree-themeicon{background-position:-260px -4px}.tree-default>.tree-no-dots .tree-leaf>.tree-ocl,.tree-default>.tree-no-dots .tree-node{background:transparent}.tree-default>.tree-no-dots .tree-open>.tree-ocl{background-position:-36px -4px}.tree-default>.tree-no-dots .tree-closed>.tree-ocl{background-position:-4px -4px}.tree-default .tree-disabled,.tree-default .tree-disabled.tree-hovered{background:transparent}.tree-default .tree-disabled.tree-selected{background:#efefef}.tree-default .tree-checkbox{background-position:-164px -4px}.tree-default .tree-checkbox:hover{background-position:-164px -36px}.tree-default.tree-checkbox-selection .tree-selected>.tree-checkbox,.tree-default .tree-checked>.tree-checkbox{background-position:-228px -4px}.tree-default.tree-checkbox-selection .tree-selected>.tree-checkbox:hover,.tree-default .tree-checked>.tree-checkbox:hover{background-position:-228px -36px}.tree-default .tree-anchor>.tree-undetermined{background-position:-196px -4px}.tree-default .tree-anchor>.tree-undetermined:hover{background-position:-196px -36px}.tree-default .tree-checkbox-disabled{opacity:.8;filter:url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'tree-grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#tree-grayscale");filter:gray;-webkit-filter:grayscale(100%)}.tree-default>.tree-striped{background-size:auto 48px}.tree-default.tree-rtl .tree-node{background-position:100% 1px;background-repeat:repeat-y}.tree-default.tree-rtl .tree-open>.tree-ocl{background-position:-132px -36px}.tree-default.tree-rtl .tree-closed>.tree-ocl{background-position:-100px -36px}.tree-default.tree-rtl .tree-leaf>.tree-ocl{background-position:-68px -36px}.tree-default.tree-rtl>.tree-no-dots .tree-leaf>.tree-ocl,.tree-default.tree-rtl>.tree-no-dots .tree-node{background:transparent}.tree-default.tree-rtl>.tree-no-dots .tree-open>.tree-ocl{background-position:-36px -36px}.tree-default.tree-rtl>.tree-no-dots .tree-closed>.tree-ocl{background-position:-4px -36px}.tree-default .tree-themeicon-custom{background-color:transparent;background-image:none;background-position:0 0}.tree-default .tree-node.tree-loading{background:none}.tree-default>.tree-container-ul .tree-loading>.tree-ocl{background:url("data:image/gif;base64,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") 50% no-repeat}.tree-default .tree-file{background:url("data:image/png;base64,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") -100px -68px no-repeat}.tree-default .tree-folder{background:url("data:image/png;base64,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") -260px -4px no-repeat}.tree-default>.tree-container-ul>.tree-node{margin-left:0;margin-right:0}.tree-default .tree-ellipsis{overflow:hidden}.tree-default .tree-ellipsis .tree-anchor{width:calc(100% - 29px);text-overflow:ellipsis;overflow:hidden}.tree-default .tree-ellipsis.tree-no-icons .tree-anchor{width:calc(100% - 5px)}.tree-default.tree-rtl .tree-node{background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAACAQMAAAB49I5GAAAABlBMVEUAAAAdHRvEkCwcAAAAAXRSTlMAQObYZgAAAAxJREFUCNdjAAMOBgAAGAAJMwQHdQAAAABJRU5ErkJggg==")}.tree-default.tree-rtl .tree-last{background:transparent}.tree-default-small .tree-node{min-height:18px;line-height:18px;margin-left:24px;min-width:18px}.tree-default-small .tree-anchor{line-height:18px;height:18px}.tree-default-small .tree-icon,.tree-default-small .tree-icon:empty{width:18px;height:18px;line-height:18px}.tree-default-small.tree-rtl .tree-node{margin-right:18px}.tree-default-small .tree-wholerow{height:18px}.tree-default-small .tree-icon,.tree-default-small .tree-node{background-image:url("data:image/png;base64,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")}.tree-default-small .tree-node{background-position:-295px -7px;background-repeat:repeat-y}.tree-default-small .tree-last{background:transparent}.tree-default-small .tree-open>.tree-ocl{background-position:-135px -7px}.tree-default-small .tree-closed>.tree-ocl{background-position:-103px -7px}.tree-default-small .tree-leaf>.tree-ocl{background-position:-71px -7px}.tree-default-small .tree-themeicon{background-position:-263px -7px}.tree-default-small>.tree-no-dots .tree-leaf>.tree-ocl,.tree-default-small>.tree-no-dots .tree-node{background:transparent}.tree-default-small>.tree-no-dots .tree-open>.tree-ocl{background-position:-39px -7px}.tree-default-small>.tree-no-dots .tree-closed>.tree-ocl{background-position:-7px -7px}.tree-default-small .tree-disabled,.tree-default-small .tree-disabled.tree-hovered{background:transparent}.tree-default-small .tree-disabled.tree-selected{background:#efefef}.tree-default-small .tree-checkbox{background-position:-167px -7px}.tree-default-small .tree-checkbox:hover{background-position:-167px -39px}.tree-default-small.tree-checkbox-selection .tree-selected>.tree-checkbox,.tree-default-small .tree-checked>.tree-checkbox{background-position:-231px -7px}.tree-default-small.tree-checkbox-selection .tree-selected>.tree-checkbox:hover,.tree-default-small .tree-checked>.tree-checkbox:hover{background-position:-231px -39px}.tree-default-small .tree-anchor>.tree-undetermined{background-position:-199px -7px}.tree-default-small .tree-anchor>.tree-undetermined:hover{background-position:-199px -39px}.tree-default-small .tree-checkbox-disabled{opacity:.8;filter:url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'tree-grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#tree-grayscale");filter:gray;-webkit-filter:grayscale(100%)}.tree-default-small>.tree-striped{background-size:auto 36px}.tree-default-small.tree-rtl .tree-node{background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAACAQMAAAB49I5GAAAABlBMVEUAAAAdHRvEkCwcAAAAAXRSTlMAQObYZgAAAAxJREFUCNdjAAMOBgAAGAAJMwQHdQAAAABJRU5ErkJggg==");background-position:100% 1px;background-repeat:repeat-y}.tree-default-small.tree-rtl .tree-open>.tree-ocl{background-position:-135px -39px}.tree-default-small.tree-rtl .tree-closed>.tree-ocl{background-position:-103px -39px}.tree-default-small.tree-rtl .tree-leaf>.tree-ocl{background-position:-71px -39px}.tree-default-small.tree-rtl>.tree-no-dots .tree-leaf>.tree-ocl,.tree-default-small.tree-rtl>.tree-no-dots .tree-node{background:transparent}.tree-default-small.tree-rtl>.tree-no-dots .tree-open>.tree-ocl{background-position:-39px -39px}.tree-default-small.tree-rtl>.tree-no-dots .tree-closed>.tree-ocl{background-position:-7px -39px}.tree-default-small .tree-themeicon-custom{background-color:transparent;background-image:none;background-position:0 0}.tree-default-small .tree-node.tree-loading{background:none}.tree-default-small>.tree-container-ul .tree-loading>.tree-ocl{background:url("data:image/gif;base64,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") 50% no-repeat}.tree-default-small .tree-file{background:url("data:image/png;base64,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") -103px -71px no-repeat}.tree-default-small .tree-folder{background:url("data:image/png;base64,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") -263px -7px no-repeat}.tree-default-small>.tree-container-ul>.tree-node{margin-left:0;margin-right:0}.tree-default-small .tree-ellipsis{overflow:hidden}.tree-default-small .tree-ellipsis .tree-anchor{width:calc(100% - 23px);text-overflow:ellipsis;overflow:hidden}.tree-default-small .tree-ellipsis.tree-no-icons .tree-anchor{width:calc(100% - 5px)}.tree-default-small.tree-rtl .tree-node{background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAACAQMAAABv1h6PAAAABlBMVEUAAAAdHRvEkCwcAAAAAXRSTlMAQObYZgAAAAxJREFUCNdjAAMHBgAAiABBI4gz9AAAAABJRU5ErkJggg==")}.tree-default-small.tree-rtl .tree-last{background:transparent}.tree-default-large .tree-node{min-height:32px;line-height:32px;margin-left:38px;min-width:32px}.tree-default-large .tree-anchor{line-height:32px;height:32px}.tree-default-large .tree-icon,.tree-default-large .tree-icon:empty{width:32px;height:32px;line-height:32px}.tree-default-large.tree-rtl .tree-node{margin-right:32px}.tree-default-large .tree-wholerow{height:32px}.tree-default-large .tree-icon,.tree-default-large .tree-node{background-image:url("data:image/png;base64,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")}.tree-default-large .tree-node{background-position:-288px 0;background-repeat:repeat-y}.tree-default-large .tree-last{background:transparent}.tree-default-large .tree-open>.tree-ocl{background-position:-128px 0}.tree-default-large .tree-closed>.tree-ocl{background-position:-96px 0}.tree-default-large .tree-leaf>.tree-ocl{background-position:-64px 0}.tree-default-large .tree-themeicon{background-position:-256px 0}.tree-default-large>.tree-no-dots .tree-leaf>.tree-ocl,.tree-default-large>.tree-no-dots .tree-node{background:transparent}.tree-default-large>.tree-no-dots .tree-open>.tree-ocl{background-position:-32px 0}.tree-default-large>.tree-no-dots .tree-closed>.tree-ocl{background-position:0 0}.tree-default-large .tree-disabled,.tree-default-large .tree-disabled.tree-hovered{background:transparent}.tree-default-large .tree-disabled.tree-selected{background:#efefef}.tree-default-large .tree-checkbox{background-position:-160px 0}.tree-default-large .tree-checkbox:hover{background-position:-160px -32px}.tree-default-large.tree-checkbox-selection .tree-selected>.tree-checkbox,.tree-default-large .tree-checked>.tree-checkbox{background-position:-224px 0}.tree-default-large.tree-checkbox-selection .tree-selected>.tree-checkbox:hover,.tree-default-large .tree-checked>.tree-checkbox:hover{background-position:-224px -32px}.tree-default-large .tree-anchor>.tree-undetermined{background-position:-192px 0}.tree-default-large .tree-anchor>.tree-undetermined:hover{background-position:-192px -32px}.tree-default-large .tree-checkbox-disabled{opacity:.8;filter:url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'tree-grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#tree-grayscale");filter:gray;-webkit-filter:grayscale(100%)}.tree-default-large>.tree-striped{background-size:auto 64px}.tree-default-large.tree-rtl .tree-node{background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAACAQMAAAB49I5GAAAABlBMVEUAAAAdHRvEkCwcAAAAAXRSTlMAQObYZgAAAAxJREFUCNdjAAMOBgAAGAAJMwQHdQAAAABJRU5ErkJggg==");background-position:100% 1px;background-repeat:repeat-y}.tree-default-large.tree-rtl .tree-open>.tree-ocl{background-position:-128px -32px}.tree-default-large.tree-rtl .tree-closed>.tree-ocl{background-position:-96px -32px}.tree-default-large.tree-rtl .tree-leaf>.tree-ocl{background-position:-64px -32px}.tree-default-large.tree-rtl>.tree-no-dots .tree-leaf>.tree-ocl,.tree-default-large.tree-rtl>.tree-no-dots .tree-node{background:transparent}.tree-default-large.tree-rtl>.tree-no-dots .tree-open>.tree-ocl{background-position:-32px -32px}.tree-default-large.tree-rtl>.tree-no-dots .tree-closed>.tree-ocl{background-position:0 -32px}.tree-default-large .tree-themeicon-custom{background-color:transparent;background-image:none;background-position:0 0}.tree-default-large .tree-node.tree-loading{background:none}.tree-default-large>.tree-container-ul .tree-loading>.tree-ocl{background:url("data:image/gif;base64,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") 50% no-repeat}.tree-default-large .tree-file{background:url("data:image/png;base64,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") -96px -64px no-repeat}.tree-default-large .tree-folder{background:url("data:image/png;base64,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") -256px 0 no-repeat}.tree-default-large>.tree-container-ul>.tree-node{margin-left:0;margin-right:0}.tree-default-large .tree-ellipsis{overflow:hidden}.tree-default-large .tree-ellipsis .tree-anchor{width:calc(100% - 37px);text-overflow:ellipsis;overflow:hidden}.tree-default-large .tree-ellipsis.tree-no-icons .tree-anchor{width:calc(100% - 5px)}.tree-default-large.tree-rtl .tree-node{background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAACAQMAAAAD0EyKAAAABlBMVEUAAAAdHRvEkCwcAAAAAXRSTlMAQObYZgAAAAxJREFUCNdjgIIGBgABCgCBvVLXcAAAAABJRU5ErkJggg==")}.tree-default-large.tree-rtl .tree-last{background:transparent}',""])},function(e,A){e.exports=function(){var e=[];return e.toString=function(){for(var e=[],A=0;A<this.length;A++){var t=this[A];t[2]?e.push("@media "+t[2]+"{"+t[1]+"}"):e.push(t[1])}return e.join("")},e.i=function(A,t){"string"==typeof A&&(A=[[null,A,""]]);for(var r={},n=0;n<this.length;n++){var o=this[n][0];"number"==typeof o&&(r[o]=!0)}for(n=0;n<A.length;n++){var a=A[n];"number"==typeof a[0]&&r[a[0]]||(t&&!a[2]?a[2]=t:t&&(a[2]="("+a[2]+") and ("+t+")"),e.push(a))}},e}},function(e,A,t){var r=t(0)(t(2),t(8),null,null,null);e.exports=r.exports},function(e,A){e.exports={render:function(){var e=this,A=e.$createElement,t=e._self._c||A;return t("li",{class:e.classes,attrs:{role:"treeitem",draggable:e.draggable},on:{dragstart:function(A){A.stopPropagation(),e.onItemDragStart(A,e._self,e._self.model)},dragend:function(A){A.stopPropagation(),A.preventDefault(),e.onItemDragEnd(A,e._self,e._self.model)},dragover:function(A){A.stopPropagation(),A.preventDefault(),e.isDragEnter=!0},dragenter:function(A){A.stopPropagation(),A.preventDefault(),e.isDragEnter=!0},dragleave:function(A){A.stopPropagation(),A.preventDefault(),e.isDragEnter=!1},drop:function(A){A.stopPropagation(),A.preventDefault(),e.handleItemDrop(A,e._self,e._self.model)}}},[e.isWholeRow?t("div",{class:e.wholeRowClasses,attrs:{role:"presentation"}},[e._v(" ")]):e._e(),e._v(" "),t("i",{staticClass:"tree-icon tree-ocl",attrs:{role:"presentation"},on:{click:e.handleItemToggle}}),e._v(" "),t("div",e._g({class:e.anchorClasses},e.events),[e.showCheckbox&&!e.model.loading?t("i",{staticClass:"tree-icon tree-checkbox",attrs:{role:"presentation"}}):e._e(),e._v(" "),e._t("default",[e.model.loading?e._e():t("i",{class:e.themeIconClasses,attrs:{role:"presentation"}}),e._v(" "),t("span",{domProps:{innerHTML:e._s(e.model[e.textFieldName])}})],{vm:this,model:e.model})],2),e._v(" "),e.isFolder?t("ul",{ref:"group",staticClass:"tree-children",style:e.groupStyle,attrs:{role:"group"}},e._l(e.model[e.childrenFieldName],function(A,r){return t("tree-item",{key:r,attrs:{data:A,"text-field-name":e.textFieldName,"value-field-name":e.valueFieldName,"children-field-name":e.childrenFieldName,"item-events":e.itemEvents,"whole-row":e.wholeRow,"show-checkbox":e.showCheckbox,"allow-transition":e.allowTransition,height:e.height,"parent-item":e.model[e.childrenFieldName],draggable:e.draggable,"drag-over-background-color":e.dragOverBackgroundColor,"on-item-click":e.onItemClick,"on-item-toggle":e.onItemToggle,"on-item-drag-start":e.onItemDragStart,"on-item-drag-end":e.onItemDragEnd,"on-item-drop":e.onItemDrop,klass:r===e.model[e.childrenFieldName].length-1?"tree-last":""},scopedSlots:e._u([{key:"default",fn:function(A){return[e._t("default",[e.model.loading?e._e():t("i",{class:A.vm.themeIconClasses,attrs:{role:"presentation"}}),e._v(" "),t("span",{domProps:{innerHTML:e._s(A.model[e.textFieldName])}})],{vm:A.vm,model:A.model})]}}])})})):e._e()])},staticRenderFns:[]}},function(e,A){e.exports={render:function(){var e=this,A=e.$createElement,t=e._self._c||A;return t("div",{class:e.classes,attrs:{role:"tree",onselectstart:"return false"}},[t("ul",{class:e.containerClasses,attrs:{role:"group"}},e._l(e.data,function(A,r){return t("tree-item",{key:r,attrs:{data:A,"text-field-name":e.textFieldName,"value-field-name":e.valueFieldName,"children-field-name":e.childrenFieldName,"item-events":e.itemEvents,"whole-row":e.wholeRow,"show-checkbox":e.showCheckbox,"allow-transition":e.allowTransition,height:e.sizeHeight,"parent-item":e.data,draggable:e.draggable,"drag-over-background-color":e.dragOverBackgroundColor,"on-item-click":e.onItemClick,"on-item-toggle":e.onItemToggle,"on-item-drag-start":e.onItemDragStart,"on-item-drag-end":e.onItemDragEnd,"on-item-drop":e.onItemDrop,klass:r===e.data.length-1?"tree-last":""},scopedSlots:e._u([{key:"default",fn:function(A){return[e._t("default",[A.model.loading?e._e():t("i",{class:A.vm.themeIconClasses,attrs:{role:"presentation"}}),e._v(" "),t("span",{domProps:{innerHTML:e._s(A.model[e.textFieldName])}})],{vm:A.vm,model:A.model})]}}])})}))])},staticRenderFns:[]}},function(e,A,t){var r=t(5);"string"==typeof r&&(r=[[e.i,r,""]]),r.locals&&(e.exports=r.locals);t(11)("134662b0",r,!0,{})},function(e,A,t){function r(e){for(var A=0;A<e.length;A++){var t=e[A],r=g[t.id];if(r){r.refs++;for(var n=0;n<r.parts.length;n++)r.parts[n](t.parts[n]);for(;n<t.parts.length;n++)r.parts.push(o(t.parts[n]));r.parts.length>t.parts.length&&(r.parts.length=t.parts.length)}else{for(var a=[],n=0;n<t.parts.length;n++)a.push(o(t.parts[n]));g[t.id]={id:t.id,refs:1,parts:a}}}}function n(){var e=document.createElement("style");return e.type="text/css",s.appendChild(e),e}function o(e){var A,t,r=document.querySelector("style["+m+'~="'+e.id+'"]');if(r){if(f)return h;r.parentNode.removeChild(r)}if(B){var o=u++;r=c||(c=n()),A=a.bind(null,r,o,!1),t=a.bind(null,r,o,!0)}else r=n(),A=l.bind(null,r),t=function(){r.parentNode.removeChild(r)};return A(e),function(r){if(r){if(r.css===e.css&&r.media===e.media&&r.sourceMap===e.sourceMap)return;A(e=r)}else t()}}function a(e,A,t,r){var n=t?"":r.css;if(e.styleSheet)e.styleSheet.cssText=w(A,n);else{var o=document.createTextNode(n),a=e.childNodes;a[A]&&e.removeChild(a[A]),a.length?e.insertBefore(o,a[A]):e.appendChild(o)}}function l(e,A){var t=A.css,r=A.media,n=A.sourceMap;if(r&&e.setAttribute("media",r),p.ssrId&&e.setAttribute(m,A.id),n&&(t+="\n/*# sourceURL="+n.sources[0]+" */",t+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(n))))+" */"),e.styleSheet)e.styleSheet.cssText=t;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(t))}}var i="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!i)throw new Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var d=t(12),g={},s=i&&(document.head||document.getElementsByTagName("head")[0]),c=null,u=0,f=!1,h=function(){},p=null,m="data-vue-ssr-id",B="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());e.exports=function(e,A,t,n){f=t,p=n||{};var o=d(e,A);return r(o),function(A){for(var t=[],n=0;n<o.length;n++){var a=o[n],l=g[a.id];l.refs--,t.push(l)}A?(o=d(e,A),r(o)):o=[];for(var n=0;n<t.length;n++){var l=t[n];if(0===l.refs){for(var i=0;i<l.parts.length;i++)l.parts[i]();delete g[l.id]}}}};var w=function(){var e=[];return function(A,t){return e[A]=t,e.filter(Boolean).join("\n")}}()},function(e,A){e.exports=function(e,A){for(var t=[],r={},n=0;n<A.length;n++){var o=A[n],a=o[0],l=o[1],i=o[2],d=o[3],g={id:e+":"+n,css:l,media:i,sourceMap:d};r[a]?r[a].parts.push(g):t.push(r[a]={id:a,parts:[g]})}return t}}])});
//# sourceMappingURL=vue-jstree.js.map

/***/ }),

/***/ 22:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


// btoa polyfill for IE<10 courtesy https://github.com/davidchambers/Base64.js

var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';

function E() {
  this.message = 'String contains an invalid character';
}
E.prototype = new Error;
E.prototype.code = 5;
E.prototype.name = 'InvalidCharacterError';

function btoa(input) {
  var str = String(input);
  var output = '';
  for (
    // initialize result and counter
    var block, charCode, idx = 0, map = chars;
    // if the next str index does not exist:
    //   change the mapping table to "="
    //   check if d has no fractional digits
    str.charAt(idx | 0) || (map = '=', idx % 1);
    // "8 - idx % 1 * 8" generates the sequence 2, 4, 6, 8
    output += map.charAt(63 & block >> 8 - idx % 1 * 8)
  ) {
    charCode = str.charCodeAt(idx += 3 / 4);
    if (charCode > 0xFF) {
      throw new E();
    }
    block = block << 8 | charCode;
  }
  return output;
}

module.exports = btoa;


/***/ }),

/***/ 23:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs support document.cookie
  (function standardBrowserEnv() {
    return {
      write: function write(name, value, expires, path, domain, secure) {
        var cookie = [];
        cookie.push(name + '=' + encodeURIComponent(value));

        if (utils.isNumber(expires)) {
          cookie.push('expires=' + new Date(expires).toGMTString());
        }

        if (utils.isString(path)) {
          cookie.push('path=' + path);
        }

        if (utils.isString(domain)) {
          cookie.push('domain=' + domain);
        }

        if (secure === true) {
          cookie.push('secure');
        }

        document.cookie = cookie.join('; ');
      },

      read: function read(name) {
        var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
        return (match ? decodeURIComponent(match[3]) : null);
      },

      remove: function remove(name) {
        this.write(name, '', Date.now() - 86400000);
      }
    };
  })() :

  // Non standard browser env (web workers, react-native) lack needed support.
  (function nonStandardBrowserEnv() {
    return {
      write: function write() {},
      read: function read() { return null; },
      remove: function remove() {}
    };
  })()
);


/***/ }),

/***/ 24:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

function InterceptorManager() {
  this.handlers = [];
}

/**
 * Add a new interceptor to the stack
 *
 * @param {Function} fulfilled The function to handle `then` for a `Promise`
 * @param {Function} rejected The function to handle `reject` for a `Promise`
 *
 * @return {Number} An ID used to remove interceptor later
 */
InterceptorManager.prototype.use = function use(fulfilled, rejected) {
  this.handlers.push({
    fulfilled: fulfilled,
    rejected: rejected
  });
  return this.handlers.length - 1;
};

/**
 * Remove an interceptor from the stack
 *
 * @param {Number} id The ID that was returned by `use`
 */
InterceptorManager.prototype.eject = function eject(id) {
  if (this.handlers[id]) {
    this.handlers[id] = null;
  }
};

/**
 * Iterate over all the registered interceptors
 *
 * This method is particularly useful for skipping over any
 * interceptors that may have become `null` calling `eject`.
 *
 * @param {Function} fn The function to call for each interceptor
 */
InterceptorManager.prototype.forEach = function forEach(fn) {
  utils.forEach(this.handlers, function forEachHandler(h) {
    if (h !== null) {
      fn(h);
    }
  });
};

module.exports = InterceptorManager;


/***/ }),

/***/ 25:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);
var transformData = __webpack_require__(26);
var isCancel = __webpack_require__(9);
var defaults = __webpack_require__(5);

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }
}

/**
 * Dispatch a request to the server using the configured adapter.
 *
 * @param {object} config The config that is to be used for the request
 * @returns {Promise} The Promise to be fulfilled
 */
module.exports = function dispatchRequest(config) {
  throwIfCancellationRequested(config);

  // Ensure headers exist
  config.headers = config.headers || {};

  // Transform request data
  config.data = transformData(
    config.data,
    config.headers,
    config.transformRequest
  );

  // Flatten headers
  config.headers = utils.merge(
    config.headers.common || {},
    config.headers[config.method] || {},
    config.headers || {}
  );

  utils.forEach(
    ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
    function cleanHeaderConfig(method) {
      delete config.headers[method];
    }
  );

  var adapter = config.adapter || defaults.adapter;

  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);

    // Transform response data
    response.data = transformData(
      response.data,
      response.headers,
      config.transformResponse
    );

    return response;
  }, function onAdapterRejection(reason) {
    if (!isCancel(reason)) {
      throwIfCancellationRequested(config);

      // Transform response data
      if (reason && reason.response) {
        reason.response.data = transformData(
          reason.response.data,
          reason.response.headers,
          config.transformResponse
        );
      }
    }

    return Promise.reject(reason);
  });
};


/***/ }),

/***/ 26:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

/**
 * Transform the data for a request or a response
 *
 * @param {Object|String} data The data to be transformed
 * @param {Array} headers The headers for the request or response
 * @param {Array|Function} fns A single function or Array of functions
 * @returns {*} The resulting transformed data
 */
module.exports = function transformData(data, headers, fns) {
  /*eslint no-param-reassign:0*/
  utils.forEach(fns, function transform(fn) {
    data = fn(data, headers);
  });

  return data;
};


/***/ }),

/***/ 27:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Determines whether the specified URL is absolute
 *
 * @param {string} url The URL to test
 * @returns {boolean} True if the specified URL is absolute, otherwise false
 */
module.exports = function isAbsoluteURL(url) {
  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
  // by any combination of letters, digits, plus, period, or hyphen.
  return /^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(url);
};


/***/ }),

/***/ 28:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Creates a new URL by combining the specified URLs
 *
 * @param {string} baseURL The base URL
 * @param {string} relativeURL The relative URL
 * @returns {string} The combined URL
 */
module.exports = function combineURLs(baseURL, relativeURL) {
  return baseURL.replace(/\/+$/, '') + '/' + relativeURL.replace(/^\/+/, '');
};


/***/ }),

/***/ 29:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var Cancel = __webpack_require__(10);

/**
 * A `CancelToken` is an object that can be used to request cancellation of an operation.
 *
 * @class
 * @param {Function} executor The executor function.
 */
function CancelToken(executor) {
  if (typeof executor !== 'function') {
    throw new TypeError('executor must be a function.');
  }

  var resolvePromise;
  this.promise = new Promise(function promiseExecutor(resolve) {
    resolvePromise = resolve;
  });

  var token = this;
  executor(function cancel(message) {
    if (token.reason) {
      // Cancellation has already been requested
      return;
    }

    token.reason = new Cancel(message);
    resolvePromise(token.reason);
  });
}

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
CancelToken.prototype.throwIfRequested = function throwIfRequested() {
  if (this.reason) {
    throw this.reason;
  }
};

/**
 * Returns an object that contains a new `CancelToken` and a function that, when called,
 * cancels the `CancelToken`.
 */
CancelToken.source = function source() {
  var cancel;
  var token = new CancelToken(function executor(c) {
    cancel = c;
  });
  return {
    token: token,
    cancel: cancel
  };
};

module.exports = CancelToken;


/***/ }),

/***/ 3:
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),

/***/ 30:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Syntactic sugar for invoking a function and expanding an array for arguments.
 *
 * Common use case would be to use `Function.prototype.apply`.
 *
 *  ```js
 *  function f(x, y, z) {}
 *  var args = [1, 2, 3];
 *  f.apply(null, args);
 *  ```
 *
 * With `spread` this example can be re-written.
 *
 *  ```js
 *  spread(function(x, y, z) {})([1, 2, 3]);
 *  ```
 *
 * @param {Function} callback
 * @returns {Function}
 */
module.exports = function spread(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
};


/***/ }),

/***/ 4:
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(156)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}
var options = null
var ssrIdKey = 'data-vue-ssr-id'

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction, _options) {
  isProduction = _isProduction

  options = _options || {}

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[' + ssrIdKey + '~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }
  if (options.ssrId) {
    styleElement.setAttribute(ssrIdKey, obj.id)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),

/***/ 459:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(460);


/***/ }),

/***/ 460:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue__ = __webpack_require__(155);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vue_js_modal__ = __webpack_require__(161);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vue_js_modal___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_vue_js_modal__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_cookie_in_vue__ = __webpack_require__(162);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_cookie_in_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_cookie_in_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_vue_jstree__ = __webpack_require__(211);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_vue_jstree___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_vue_jstree__);


// https://github.com/euvl/vue-js-modal


// https://www.npmjs.com/package/cookie-in-vue


// https://github.com/zdy1988/vue-jstree


window.axios = __webpack_require__(13);

__WEBPACK_IMPORTED_MODULE_0_vue___default.a.use(__WEBPACK_IMPORTED_MODULE_1_vue_js_modal___default.a, {
  dialog: true
});
__WEBPACK_IMPORTED_MODULE_0_vue___default.a.use(__WEBPACK_IMPORTED_MODULE_2_cookie_in_vue___default.a);
__WEBPACK_IMPORTED_MODULE_0_vue___default.a.use(__WEBPACK_IMPORTED_MODULE_3_vue_jstree___default.a);

// Vue.component('VcCategoria', require('./Categoria.vue'));
__WEBPACK_IMPORTED_MODULE_0_vue___default.a.component('VcIndex', __webpack_require__(461));
__WEBPACK_IMPORTED_MODULE_0_vue___default.a.component('VcCadastrar', __webpack_require__(464));
__WEBPACK_IMPORTED_MODULE_0_vue___default.a.component('VcEditar', __webpack_require__(467));
__WEBPACK_IMPORTED_MODULE_0_vue___default.a.component('VcVisualizar', __webpack_require__(470));
__WEBPACK_IMPORTED_MODULE_0_vue___default.a.component('VcCopiar', __webpack_require__(473));

__WEBPACK_IMPORTED_MODULE_0_vue___default.a.component('VcFiltroPesquisa', __webpack_require__(173));

__WEBPACK_IMPORTED_MODULE_0_vue___default.a.component('VcDatatable', __webpack_require__(180));

__WEBPACK_IMPORTED_MODULE_0_vue___default.a.component('VcMigalha', __webpack_require__(158));

__WEBPACK_IMPORTED_MODULE_0_vue___default.a.component('VcModalConfirme', __webpack_require__(172));

var app = new __WEBPACK_IMPORTED_MODULE_0_vue___default.a({
  el: '#app_acesso'
});

/***/ }),

/***/ 461:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(2)
/* script */
var __vue_script__ = __webpack_require__(462)
/* template */
var __vue_template__ = __webpack_require__(463)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/vue/modulos/configuracao/acesso/index.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-343c41c0", Component.options)
  } else {
    hotAPI.reload("data-v-343c41c0", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 462:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['can'],
    data: function data() {
        return {
            nome: '',
            status: 'true',
            filtro: {
                nome: '',
                status: 'true',
                por_pagina: 15,
                pagina: 1,
                sort: ''
            },
            filtroPadrao: {
                nome: '',
                status: 'true',
                por_pagina: 15,
                pagina: 1,
                sort: '3 asc'
            },
            thead: [{
                text: 'Nome',
                coluna: 3,
                ordena: true,
                class: '',
                width: ''
            }, {
                text: 'Permissões vinculadas',
                coluna: 4,
                ordena: true,
                class: 'text-center',
                width: '200px'
            }],
            cookiePrefixo: 'configuracao_acesso_',
            url: '/configuracao/acesso/',
            dados: {
                data: []
            },

            filtroAtivo: false,
            erros: '',
            carregando: false,
            canObj: {}

        };
    },

    methods: {
        search: function search(value) {
            this.dados = value.acessos;
        }
    },
    mounted: function mounted() {
        this.canObj = JSON.parse(this.can);
        // this.verificaCoockies()
    }
});

/***/ }),

/***/ 463:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("div", { staticClass: "row" }, [
      _c(
        "div",
        { staticClass: "col-md-12" },
        [
          _c(
            "vc-filtro-pesquisa",
            {
              attrs: {
                filtro: _vm.filtro,
                filtroPadrao: _vm.filtroPadrao,
                cookiePrefixo: _vm.cookiePrefixo,
                url: _vm.url
              },
              on: { pesquisar: _vm.search }
            },
            [
              _vm._t("default", [
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "form-group col-md-3" }, [
                    _c("label", { attrs: { for: "nome" } }, [_vm._v("Nome:")]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.filtro.nome,
                          expression: "filtro.nome"
                        }
                      ],
                      staticClass: "form-control input-sm",
                      staticStyle: { "text-transform": "uppercase" },
                      attrs: { type: "text", placeholder: "Nome" },
                      domProps: { value: _vm.filtro.nome },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.filtro, "nome", $event.target.value)
                        }
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "form-group col-md-3" }, [
                    _c("label", [_c("strong", [_vm._v("Status:")])]),
                    _vm._v(" "),
                    _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-12" }, [
                        _c("label", [
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.filtro.status,
                                expression: "filtro.status"
                              }
                            ],
                            attrs: {
                              type: "radio",
                              name: "5",
                              value: "true",
                              checked: "checked"
                            },
                            domProps: {
                              checked: _vm._q(_vm.filtro.status, "true")
                            },
                            on: {
                              change: function($event) {
                                _vm.$set(_vm.filtro, "status", "true")
                              }
                            }
                          }),
                          _vm._v(" Ativo\n                                    ")
                        ]),
                        _vm._v(
                          "\n                                     \n                                    "
                        ),
                        _c("label", [
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.filtro.status,
                                expression: "filtro.status"
                              }
                            ],
                            attrs: { type: "radio", name: "6", value: "false" },
                            domProps: {
                              checked: _vm._q(_vm.filtro.status, "false")
                            },
                            on: {
                              change: function($event) {
                                _vm.$set(_vm.filtro, "status", "false")
                              }
                            }
                          }),
                          _vm._v(
                            " Inativo\n                                    "
                          )
                        ]),
                        _vm._v(
                          "\n                                     \n                                    "
                        ),
                        _c("label", [
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.filtro.status,
                                expression: "filtro.status"
                              }
                            ],
                            attrs: { type: "radio", name: "6", value: "todos" },
                            domProps: {
                              checked: _vm._q(_vm.filtro.status, "todos")
                            },
                            on: {
                              change: function($event) {
                                _vm.$set(_vm.filtro, "status", "todos")
                              }
                            }
                          }),
                          _vm._v(" Todos\n                                    ")
                        ])
                      ])
                    ])
                  ])
                ])
              ])
            ],
            2
          )
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c(
        "div",
        { staticClass: "col-md-12" },
        [
          _c("vc-datatable", {
            attrs: {
              dados: _vm.dados,
              url: _vm.url,
              filtro: _vm.filtro,
              canObj: _vm.canObj,
              erros: _vm.erros,
              carregando: _vm.carregando,
              cookiePrefixo: _vm.cookiePrefixo,
              thead: _vm.thead,
              status: "true",
              acoes: true
            },
            scopedSlots: _vm._u([
              {
                key: "tbody",
                fn: function(ref) {
                  var showConfirmeModal = ref.showConfirmeModal
                  var item = ref.item
                  return [
                    _c("td", [
                      _c(
                        "span",
                        {
                          staticClass: "truncate",
                          attrs: { title: item.nome }
                        },
                        [
                          _vm._v(
                            "\n                            " +
                              _vm._s(item.nome) +
                              "\n                        "
                          )
                        ]
                      )
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "text-center" }, [
                      _vm._v(
                        "\n                        " +
                          _vm._s(item.permissoes) +
                          "\n                    "
                      )
                    ])
                  ]
                }
              }
            ])
          })
        ],
        1
      )
    ]),
    _vm._v(" "),
    _vm.canObj.cadastrar ? _c("div", [_vm._m(0)]) : _vm._e()
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "a",
      { attrs: { href: "/configuracao/acesso/create", title: "Cadastrar" } },
      [
        _c(
          "button",
          { staticClass: "btn btn-primary", attrs: { type: "button" } },
          [_vm._v("Cadastrar")]
        )
      ]
    )
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-343c41c0", module.exports)
  }
}

/***/ }),

/***/ 464:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(2)
/* script */
var __vue_script__ = __webpack_require__(465)
/* template */
var __vue_template__ = __webpack_require__(466)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/vue/modulos/configuracao/acesso/cadastrar.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-16b02275", Component.options)
  } else {
    hotAPI.reload("data-v-16b02275", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 465:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['permissoes'],
    data: function data() {
        return {
            datas: [],
            nome: '',
            permission: [],
            validaBotao: false,
            item: {
                nome: '',
                permissoes: []
            },
            mensagem: "",
            url: '',
            errors: []
        };
    },

    methods: {
        itemClick: function itemClick(node) {
            this.permission = [];
            this.setPermissaoSelecionada(this.datas);
        },
        nArrayPermissoes: function nArrayPermissoes(permissoes) {
            var self = this;
            permissoes.filter(function (item) {
                item.selected = false;
                if (item.text != 'SELECIONAR TODOS') {
                    item.opened = false;
                }

                if (item.hasOwnProperty("children")) {
                    self.nArrayPermissoes(item.children);
                }
                if (item.hasOwnProperty("permissoes") && item.permissoes.length > 0) {
                    item.children = [];
                    for (var permissao in item.permissoes) {
                        item.permissoes[permissao].selected = false;
                        item.children.push(item.permissoes[permissao]);
                    }
                }
            });

            return permissoes;
        },
        setPermissaoSelecionada: function setPermissaoSelecionada(permissoes) {
            var self = this;

            permissoes.filter(function (item) {
                var countPermissaoFilho = 0;
                var countPaiFilho = 0;

                if (item.hasOwnProperty("children")) {
                    self.setPermissaoSelecionada(item.children);
                    for (var filho in item.children) {
                        if (item.children[filho].selected == true) {
                            countPaiFilho++;
                        }
                    }

                    if (item.children.length == countPaiFilho) {
                        item.selected = true;
                    } else {
                        item.selected = false;
                    }
                }
                if (item.hasOwnProperty("permissoes") && item.permissoes.length > 0) {
                    for (var permissao in item.permissoes) {
                        if (item.permissoes[permissao].selected == true) {
                            self.permission.push(item.permissoes[permissao].id);
                        }
                    }
                }
            });
        },
        store: function store() {
            var _this = this;

            if (this.nome != '' && this.permission.length > 0 && !this.validaBotao) {
                this.limpaResponse();
                this.validaBotao = true;
                this.item.nome = this.nome.toUpperCase();
                this.item.permissoes = this.permission;

                window.axios.post('/configuracao/acesso', this.item).then(function (response) {
                    _this.mensagem = response.data.mensagem;
                    _this.url = '/configuracao/acesso/' + response.data.id;

                    _this.datas = _this.nArrayPermissoes(_this.permissionAll);
                    _this.nome = '';
                    _this.validaBotao = false;
                }).catch(function (error) {
                    _this.errors = error.response.data.errors;
                    _this.validaBotao = false;
                    return 0;
                });
            }
        },
        limpaResponse: function limpaResponse() {
            this.errors = [];
            this.mensagem = "";
            this.url = "";
        }
    },
    computed: {
        isDisabled: function isDisabled() {
            if (this.nome != '' && this.permission.length > 0 && !this.validaBotao) {
                return false;
            }
            return true;
        }
    },
    mounted: function mounted() {

        this.permissionAll = [{
            "text": "SELECIONAR TODOS",
            "children": this.permissoes,
            "opened": true,
            "icon": "fa fa-check icon-state-success",
            "selected": false
        }];

        this.datas = this.nArrayPermissoes(this.permissionAll);
    }
});

/***/ }),

/***/ 466:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "box box-default" }, [
          _c(
            "form",
            {
              attrs: { role: "form", "data-toggle": "validator", name: "form" }
            },
            [
              _c(
                "div",
                {
                  staticClass: "box-body row",
                  staticStyle: { "padding-bottom": "0" }
                },
                [
                  _c("div", { staticClass: "form-group col-md-3" }, [
                    _c("label", [_vm._v("Nome:")]),
                    _vm._v(" "),
                    _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-11 col-xs-10" }, [
                        _c("input", {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.nome,
                              expression: "nome"
                            }
                          ],
                          staticClass: "form-control input-sm",
                          staticStyle: { "text-transform": "uppercase" },
                          attrs: {
                            type: "text",
                            name: "nome",
                            maxlength: "100",
                            required: ""
                          },
                          domProps: { value: _vm.nome },
                          on: {
                            input: function($event) {
                              if ($event.target.composing) {
                                return
                              }
                              _vm.nome = $event.target.value
                            }
                          }
                        }),
                        _vm._v(" "),
                        _c("div", { staticClass: "help-block with-errors" })
                      ]),
                      _vm._v(" "),
                      _vm._m(0)
                    ])
                  ])
                ]
              ),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass: "box-body row",
                  staticStyle: { "padding-top": "0" }
                },
                [
                  _c("div", { staticClass: "form-group col-md-12" }, [
                    _c("label", [_vm._v("Permissões:")]),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "form-group" },
                      [
                        _c("v-jstree", {
                          attrs: {
                            data: _vm.datas,
                            "show-checkbox": "",
                            multiple: "",
                            "allow-batch": "",
                            "whole-row": ""
                          },
                          on: { "item-click": _vm.itemClick }
                        })
                      ],
                      1
                    )
                  ])
                ]
              ),
              _vm._v(" "),
              _c("div", { staticClass: "box-footer" }, [
                _c(
                  "button",
                  {
                    staticClass: "btn btn-primary",
                    attrs: { disabled: _vm.isDisabled },
                    on: { click: _vm.store }
                  },
                  [_vm._v("Salvar")]
                )
              ])
            ]
          )
        ])
      ])
    ]),
    _vm._v(" "),
    _vm.errors || _vm.mensagem
      ? _c("div", {}, [
          _c("div", { staticClass: "row" }, [
            _c(
              "div",
              { staticClass: "col-md-12" },
              _vm._l(_vm.errors, function(value) {
                return _c(
                  "div",
                  {
                    staticClass: "callout no-margin-bottom callout-danger",
                    staticStyle: { "margin-top": "15px" }
                  },
                  [_c("div", [_vm._v(_vm._s(value[0]))])]
                )
              })
            )
          ]),
          _vm._v(" "),
          _vm.mensagem
            ? _c("div", { staticClass: "row" }, [
                _c("div", { staticClass: "col-md-12" }, [
                  _c(
                    "div",
                    {
                      staticClass: "callout no-margin-bottom callout-success",
                      staticStyle: { "margin-top": "15px" }
                    },
                    [
                      _c("div", [
                        _vm._v(_vm._s(_vm.mensagem) + "\n                    "),
                        _vm.url
                          ? _c(
                              "span",
                              { staticStyle: { "padding-left": "5px" } },
                              [
                                _c("a", { attrs: { href: _vm.url } }, [
                                  _c("i", {
                                    staticClass: "fa fa-external-link",
                                    attrs: { "aria-hidden": "true" }
                                  })
                                ])
                              ]
                            )
                          : _vm._e()
                      ])
                    ]
                  )
                ])
              ])
            : _vm._e()
        ])
      : _vm._e()
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "col-md-1 col-xs-2",
        staticStyle: { "padding-left": "0" }
      },
      [_c("i", { staticClass: "fa fa-asterisk" })]
    )
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-16b02275", module.exports)
  }
}

/***/ }),

/***/ 467:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(2)
/* script */
var __vue_script__ = __webpack_require__(468)
/* template */
var __vue_template__ = __webpack_require__(469)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/vue/modulos/configuracao/acesso/editar.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-37c956dd", Component.options)
  } else {
    hotAPI.reload("data-v-37c956dd", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 468:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['permissoes', 'acesso', 'acessopermissao'],
    data: function data() {
        return {
            datas: [],
            nome: '',
            permission: [],
            validaBotao: false,
            item: {
                nome: '',
                permissoes: []
            },
            mensagem: "",
            url: '',
            errors: [],
            permissionAll: []
        };
    },

    methods: {
        itemClick: function itemClick(node) {
            this.permission = [];
            this.setPermissaoSelecionada(this.datas);
        },
        nArrayPermissoes: function nArrayPermissoes(permissoes) {
            var self = this;
            permissoes.filter(function (item) {
                item.selected = false;

                var countPermissaoFilho = 0;
                var countPaiFilho = 0;

                if (item.hasOwnProperty("children")) {
                    self.nArrayPermissoes(item.children);

                    for (var filho in item.children) {
                        if (item.children[filho].selected == true) {
                            countPaiFilho++;
                        }
                        if (item.children[filho].opened == true) {
                            item.opened = true;
                        }
                    }

                    if (item.children.length == countPaiFilho) {
                        item.selected = true;
                    }
                }
                if (item.hasOwnProperty("permissoes") && item.permissoes.length > 0) {
                    item.children = [];
                    for (var permissao in item.permissoes) {
                        if (self.acessopermissao.includes(item.permissoes[permissao].id)) {
                            item.permissoes[permissao].selected = true;
                            countPermissaoFilho++;
                        } else {
                            item.permissoes[permissao].selected = false;
                        }

                        item.children.push(item.permissoes[permissao]);
                    }
                }

                if (countPermissaoFilho > 0) {
                    item.opened = true;
                }

                if (countPermissaoFilho > 0 && countPermissaoFilho == item.children.length) {
                    item.selected = true;
                }
            });

            return permissoes;
        },
        setPermissaoSelecionada: function setPermissaoSelecionada(permissoes) {
            var self = this;

            permissoes.filter(function (item) {
                var countPermissaoFilho = 0;
                var countPaiFilho = 0;

                if (item.hasOwnProperty("children")) {
                    self.setPermissaoSelecionada(item.children);

                    for (var filho in item.children) {
                        if (item.children[filho].selected == true) {
                            countPaiFilho++;
                        }
                    }

                    if (item.children.length == countPaiFilho) {
                        item.selected = true;
                    } else {
                        item.selected = false;
                    }
                }
                if (item.hasOwnProperty("permissoes") && item.permissoes.length > 0) {
                    for (var permissao in item.permissoes) {
                        if (item.permissoes[permissao].selected == true) {
                            self.permission.push(item.permissoes[permissao].id);
                        }
                    }
                }
            });
        },
        store: function store() {
            var _this = this;

            if (this.nome != '' && this.permission.length > 0 && !this.validaBotao) {
                this.limpaResponse();
                this.validaBotao = true;
                this.item.nome = this.nome;
                this.item.permissoes = this.permission;

                window.axios.put('/configuracao/acesso/' + this.acesso.id, this.item).then(function (response) {
                    _this.mensagem = response.data.mensagem;
                    _this.url = '/configuracao/acesso/' + response.data.id;
                    _this.validaBotao = false;
                }).catch(function (error) {
                    _this.errors = error.response.data.errors;
                    _this.validaBotao = false;
                    return 0;
                });
            }
        },
        limpaResponse: function limpaResponse() {
            this.errors = [];
            this.mensagem = "";
            this.url = "";
        }
    },
    computed: {
        isDisabled: function isDisabled() {
            if (this.nome != '' && this.permission.length > 0 && !this.validaBotao) {
                return false;
            }
            return true;
        }
    },
    mounted: function mounted() {
        this.nome = this.acesso.nome;
        this.permission = this.acessopermissao;
        this.permissionAll = [{
            "text": "SELECIONAR TODOS",
            "children": this.permissoes,
            "opened": true,
            "icon": "fa fa-check icon-state-success",
            "selected": false
        }];

        this.datas = this.nArrayPermissoes(this.permissionAll);
    }
});

/***/ }),

/***/ 469:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "box box-default" }, [
          _c(
            "form",
            {
              attrs: { role: "form", "data-toggle": "validator", name: "form" }
            },
            [
              _c(
                "div",
                {
                  staticClass: "box-body row",
                  staticStyle: { "padding-bottom": "0" }
                },
                [
                  _c("div", { staticClass: "form-group col-md-3" }, [
                    _c("label", [_vm._v("Nome:")]),
                    _vm._v(" "),
                    _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-11 col-xs-10" }, [
                        _c("input", {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.nome,
                              expression: "nome"
                            }
                          ],
                          staticClass: "form-control input-sm",
                          staticStyle: { "text-transform": "uppercase" },
                          attrs: {
                            type: "text",
                            name: "nome",
                            maxlength: "100",
                            required: ""
                          },
                          domProps: { value: _vm.nome },
                          on: {
                            input: function($event) {
                              if ($event.target.composing) {
                                return
                              }
                              _vm.nome = $event.target.value
                            }
                          }
                        }),
                        _vm._v(" "),
                        _c("div", { staticClass: "help-block with-errors" })
                      ]),
                      _vm._v(" "),
                      _vm._m(0)
                    ])
                  ])
                ]
              ),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass: "box-body row",
                  staticStyle: { "padding-top": "0" }
                },
                [
                  _c("div", { staticClass: "form-group col-md-12" }, [
                    _c("label", [_vm._v("Permissões:")]),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "form-group" },
                      [
                        _c("v-jstree", {
                          attrs: {
                            data: _vm.datas,
                            "show-checkbox": "",
                            multiple: "",
                            "allow-batch": "",
                            "whole-row": ""
                          },
                          on: { "item-click": _vm.itemClick }
                        })
                      ],
                      1
                    )
                  ])
                ]
              ),
              _vm._v(" "),
              _c("div", { staticClass: "box-footer" }, [
                _c(
                  "button",
                  {
                    staticClass: "btn btn-primary",
                    attrs: { disabled: _vm.isDisabled },
                    on: { click: _vm.store }
                  },
                  [_vm._v("Salvar")]
                )
              ])
            ]
          )
        ])
      ])
    ]),
    _vm._v(" "),
    _vm.errors || _vm.mensagem
      ? _c("div", {}, [
          _c("div", { staticClass: "row" }, [
            _c(
              "div",
              { staticClass: "col-md-12" },
              _vm._l(_vm.errors, function(value) {
                return _c(
                  "div",
                  {
                    staticClass: "callout no-margin-bottom callout-danger",
                    staticStyle: { "margin-top": "15px" }
                  },
                  [_c("div", [_vm._v(_vm._s(value[0]))])]
                )
              })
            )
          ]),
          _vm._v(" "),
          _vm.mensagem
            ? _c("div", { staticClass: "row" }, [
                _c("div", { staticClass: "col-md-12" }, [
                  _c(
                    "div",
                    {
                      staticClass: "callout no-margin-bottom callout-success",
                      staticStyle: { "margin-top": "15px" }
                    },
                    [
                      _c("div", [
                        _vm._v(_vm._s(_vm.mensagem) + "\n                    "),
                        _vm.url
                          ? _c(
                              "span",
                              { staticStyle: { "padding-left": "5px" } },
                              [
                                _c("a", { attrs: { href: _vm.url } }, [
                                  _c("i", {
                                    staticClass: "fa fa-external-link",
                                    attrs: { "aria-hidden": "true" }
                                  })
                                ])
                              ]
                            )
                          : _vm._e()
                      ])
                    ]
                  )
                ])
              ])
            : _vm._e()
        ])
      : _vm._e()
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "col-md-1 col-xs-2",
        staticStyle: { "padding-left": "0" }
      },
      [_c("i", { staticClass: "fa fa-asterisk" })]
    )
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-37c956dd", module.exports)
  }
}

/***/ }),

/***/ 470:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(2)
/* script */
var __vue_script__ = __webpack_require__(471)
/* template */
var __vue_template__ = __webpack_require__(472)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/vue/modulos/configuracao/acesso/visualizar.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-b1f021f8", Component.options)
  } else {
    hotAPI.reload("data-v-b1f021f8", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 471:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['permissoes', 'acesso', 'acessopermissao', 'can'],
    data: function data() {
        return {
            datas: [],
            nome: '',
            permission: [],
            validaBotao: false,
            item: {
                nome: '',
                permissoes: []
            },
            mensagem: "",
            url: '',
            errors: [],
            permissionAll: [],
            canObj: []
        };
    },

    methods: {
        nArrayPermissoes: function nArrayPermissoes(permissoes) {
            var self = this;
            permissoes.filter(function (item) {
                item.selected = false;
                item.disabled = true;

                var countPermissaoFilho = 0;
                var countPaiFilho = 0;

                if (item.hasOwnProperty("children")) {
                    self.nArrayPermissoes(item.children);

                    for (var filho in item.children) {
                        if (item.children[filho].selected == true) {
                            countPaiFilho++;
                        }
                        if (item.children[filho].opened == true) {
                            item.opened = true;
                        }
                    }

                    if (item.children.length == countPaiFilho) {
                        item.selected = true;
                    }
                }
                if (item.hasOwnProperty("permissoes") && item.permissoes.length > 0) {
                    item.children = [];
                    for (var permissao in item.permissoes) {
                        item.permissoes[permissao].disabled = true;
                        if (self.acessopermissao.includes(item.permissoes[permissao].id)) {
                            item.permissoes[permissao].selected = true;
                            countPermissaoFilho++;
                        } else {
                            item.permissoes[permissao].selected = false;
                        }

                        item.children.push(item.permissoes[permissao]);
                    }
                }

                if (countPermissaoFilho > 0) {
                    item.opened = true;
                }

                if (countPermissaoFilho > 0 && countPermissaoFilho == item.children.length) {
                    item.selected = true;
                }
            });

            return permissoes;
        }
    },

    mounted: function mounted() {
        this.nome = this.acesso.nome;
        this.canObj = JSON.parse(this.can);

        this.permissionAll = [{
            "text": "SELECIONAR TODOS",
            "children": this.permissoes,
            "opened": true,
            "icon": "fa fa-check icon-state-success",
            "selected": false
        }];

        this.datas = this.nArrayPermissoes(this.permissionAll);
    }
});

/***/ }),

/***/ 472:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "vue-visualizar" }, [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "box box-default" }, [
          _c(
            "div",
            {
              staticClass: "box-body row",
              staticStyle: { "padding-bottom": "0" }
            },
            [
              _c("div", { staticClass: "form-group col-md-6" }, [
                _c("label", [_vm._v("Nome:")]),
                _vm._v(" "),
                _c("div", { staticClass: "row" }, [
                  _c(
                    "div",
                    {
                      staticClass: "col-md-11 col-xs-10 truncate",
                      attrs: { title: _vm.acesso.nome }
                    },
                    [
                      _vm._v(
                        "\n                            " +
                          _vm._s(_vm.acesso.nome) +
                          "\n                        "
                      )
                    ]
                  )
                ])
              ])
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "box-body row",
              staticStyle: { "padding-bottom": "0" }
            },
            [
              _c("div", { staticClass: "form-group col-md-3" }, [
                _c("strong", [_vm._v("Data de inclusão:")]),
                _vm._v(" "),
                _vm.acesso.created_at
                  ? _c("p", { staticClass: "text-muted" }, [
                      _vm._v(
                        "\n                        " +
                          _vm._s(_vm.acesso.created_at) +
                          "\n                        "
                      ),
                      _vm.acesso.usuario_inclusao
                        ? _c("span", [
                            _vm._v(
                              "\n                          (" +
                                _vm._s(_vm.acesso.usuario_inclusao) +
                                ") \n                        "
                            )
                          ])
                        : _vm._e()
                    ])
                  : _c("p", { staticClass: "text-muted" }, [_vm._v("-")])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "form-group col-md-3" }, [
                _c("strong", [_vm._v("Data de alteração:")]),
                _vm._v(" "),
                _vm.acesso.updated_at
                  ? _c("p", { staticClass: "text-muted" }, [
                      _vm._v(
                        "\n                           " +
                          _vm._s(_vm.acesso.updated_at) +
                          " \n                        "
                      ),
                      _vm.acesso.usuario_alteracao
                        ? _c("span", [
                            _vm._v(
                              "\n                            (" +
                                _vm._s(_vm.acesso.usuario_alteracao) +
                                ")\n                        "
                            )
                          ])
                        : _vm._e()
                    ])
                  : _c("p", { staticClass: "text-muted" }, [_vm._v("-")])
              ])
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "box-body row",
              staticStyle: { "padding-top": "0" }
            },
            [
              _c("div", { staticClass: "form-group col-md-12" }, [
                _c("label", [_vm._v("Permissões:")]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "form-group" },
                  [
                    _c("v-jstree", {
                      attrs: {
                        data: _vm.datas,
                        "show-checkbox": "",
                        multiple: "",
                        "allow-batch": "",
                        "whole-row": ""
                      }
                    })
                  ],
                  1
                )
              ])
            ]
          ),
          _vm._v(" "),
          _c("div", { staticClass: "box-footer" }, [
            _vm.canObj.editar
              ? _c(
                  "a",
                  {
                    staticClass: "btn btn-primary",
                    attrs: {
                      href: "/configuracao/acesso/" + _vm.acesso.id + "/edit"
                    }
                  },
                  [_vm._v("Editar")]
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.canObj.copiar
              ? _c(
                  "a",
                  {
                    staticClass: "btn btn-primary",
                    attrs: {
                      href: "/configuracao/acesso/" + _vm.acesso.id + "/copy"
                    }
                  },
                  [_vm._v("Copiar")]
                )
              : _vm._e()
          ])
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-b1f021f8", module.exports)
  }
}

/***/ }),

/***/ 473:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(474)
}
var normalizeComponent = __webpack_require__(2)
/* script */
var __vue_script__ = __webpack_require__(476)
/* template */
var __vue_template__ = __webpack_require__(477)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/vue/modulos/configuracao/acesso/copiar.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3f6ae7f8", Component.options)
  } else {
    hotAPI.reload("data-v-3f6ae7f8", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 474:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(475);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(4)("27dca8b4", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../../node_modules/css-loader/index.js!../../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-3f6ae7f8\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./copiar.vue", function() {
     var newContent = require("!!../../../../../../node_modules/css-loader/index.js!../../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-3f6ae7f8\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./copiar.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 475:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(3)(false);
// imports


// module
exports.push([module.i, "\n.vue-visualizar .tree-anchor{\n    cursor: default !important;\n}\n", ""]);

// exports


/***/ }),

/***/ 476:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['permissoes', 'acesso', 'acessopermissao'],
    data: function data() {
        return {
            datas: [],
            nome: '',
            permission: [],
            validaBotao: false,
            item: {
                nome: '',
                permissoes: []
            },
            mensagem: "",
            url: '',
            errors: [],
            permissionAll: []
        };
    },

    methods: {
        itemClick: function itemClick(node) {
            this.permission = [];
            this.setPermissaoSelecionada(this.datas);
        },
        nArrayPermissoes: function nArrayPermissoes(permissoes) {
            var self = this;
            permissoes.filter(function (item) {
                item.selected = false;
                item.disabled = true;

                var countPermissaoFilho = 0;
                var countPaiFilho = 0;

                if (item.hasOwnProperty("children")) {
                    self.nArrayPermissoes(item.children);

                    for (var filho in item.children) {
                        if (item.children[filho].selected == true) {
                            countPaiFilho++;
                        }
                        if (item.children[filho].opened == true) {
                            item.opened = true;
                        }
                    }

                    if (item.children.length == countPaiFilho) {
                        item.selected = true;
                    }
                }
                if (item.hasOwnProperty("permissoes") && item.permissoes.length > 0) {
                    item.children = [];
                    for (var permissao in item.permissoes) {
                        item.permissoes[permissao].disabled = true;
                        if (self.acessopermissao.includes(item.permissoes[permissao].id)) {
                            item.permissoes[permissao].selected = true;
                            countPermissaoFilho++;
                        } else {
                            item.permissoes[permissao].selected = false;
                        }

                        item.children.push(item.permissoes[permissao]);
                    }
                }

                if (countPermissaoFilho > 0) {
                    item.opened = true;
                }

                if (countPermissaoFilho > 0 && countPermissaoFilho == item.children.length) {
                    item.selected = true;
                }
            });

            return permissoes;
        },
        setPermissaoSelecionada: function setPermissaoSelecionada(permissoes) {
            var self = this;

            permissoes.filter(function (item) {
                if (item.hasOwnProperty("children")) {
                    self.setPermissaoSelecionada(item.children);
                }
                if (item.hasOwnProperty("permissoes") && item.permissoes.length > 0) {
                    for (var permissao in item.permissoes) {
                        if (item.permissoes[permissao].selected == true) {
                            self.permission.push(item.permissoes[permissao].id);
                        }
                    }
                }
            });
        },
        store: function store() {
            var _this = this;

            if (this.nome != '' && !this.validaBotao) {
                this.limpaResponse();
                this.validaBotao = true;
                this.item.nome = this.nome;
                this.item.permissoes = this.acessopermissao;
                this.item.id = this.acesso.id;

                window.axios.post('/configuracao/acesso', this.item).then(function (response) {
                    _this.mensagem = response.data.mensagem;
                    _this.url = '/configuracao/acesso/' + response.data.id;
                    _this.validaBotao = false;
                }).catch(function (error) {
                    _this.errors = error.response.data.errors;
                    _this.validaBotao = false;
                    return 0;
                });
            }
        },
        limpaResponse: function limpaResponse() {
            this.errors = [];
            this.mensagem = "";
            this.url = "";
        }
    },
    computed: {
        isDisabled: function isDisabled() {
            if (this.nome != '' && !this.validaBotao) {
                return false;
            }
            return true;
        }
    },
    mounted: function mounted() {
        this.nome = this.acesso.nome;

        this.permissionAll = [{
            "text": "SELECIONAR TODOS",
            "children": this.permissoes,
            "opened": true,
            "icon": "fa fa-check icon-state-success",
            "selected": false
        }];

        this.datas = this.nArrayPermissoes(this.permissionAll);
    }
});

/***/ }),

/***/ 477:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "vue-visualizar" }, [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("div", { staticClass: "box box-default" }, [
          _c(
            "form",
            {
              attrs: { role: "form", "data-toggle": "validator", name: "form" }
            },
            [
              _c(
                "div",
                {
                  staticClass: "box-body row",
                  staticStyle: { "padding-bottom": "0" }
                },
                [
                  _c("div", { staticClass: "form-group col-md-3" }, [
                    _c("label", [_vm._v("Nome:")]),
                    _vm._v(" "),
                    _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-11 col-xs-10" }, [
                        _c("input", {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.nome,
                              expression: "nome"
                            }
                          ],
                          staticClass: "form-control input-sm",
                          staticStyle: { "text-transform": "uppercase" },
                          attrs: {
                            type: "text",
                            name: "nome",
                            maxlength: "100",
                            required: ""
                          },
                          domProps: { value: _vm.nome },
                          on: {
                            input: function($event) {
                              if ($event.target.composing) {
                                return
                              }
                              _vm.nome = $event.target.value
                            }
                          }
                        }),
                        _vm._v(" "),
                        _c("div", { staticClass: "help-block with-errors" })
                      ]),
                      _vm._v(" "),
                      _vm._m(0)
                    ])
                  ])
                ]
              ),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass: "box-body row",
                  staticStyle: { "padding-top": "0" }
                },
                [
                  _c("div", { staticClass: "form-group col-md-12" }, [
                    _c("label", [_vm._v("Permissões:")]),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "form-group" },
                      [
                        _c("v-jstree", {
                          attrs: {
                            data: _vm.datas,
                            "show-checkbox": "",
                            multiple: "",
                            "allow-batch": "",
                            "whole-row": ""
                          },
                          on: { "item-click": _vm.itemClick }
                        })
                      ],
                      1
                    )
                  ])
                ]
              ),
              _vm._v(" "),
              _c("div", { staticClass: "box-footer" }, [
                _c(
                  "button",
                  {
                    staticClass: "btn btn-primary",
                    attrs: { disabled: _vm.isDisabled },
                    on: { click: _vm.store }
                  },
                  [_vm._v("Salvar")]
                )
              ])
            ]
          )
        ])
      ])
    ]),
    _vm._v(" "),
    _vm.errors || _vm.mensagem
      ? _c("div", {}, [
          _c("div", { staticClass: "row" }, [
            _c(
              "div",
              { staticClass: "col-md-12" },
              _vm._l(_vm.errors, function(value) {
                return _c(
                  "div",
                  {
                    staticClass: "callout no-margin-bottom callout-danger",
                    staticStyle: { "margin-top": "15px" }
                  },
                  [_c("div", [_vm._v(_vm._s(value[0]))])]
                )
              })
            )
          ]),
          _vm._v(" "),
          _vm.mensagem
            ? _c("div", { staticClass: "row" }, [
                _c("div", { staticClass: "col-md-12" }, [
                  _c(
                    "div",
                    {
                      staticClass: "callout no-margin-bottom callout-success",
                      staticStyle: { "margin-top": "15px" }
                    },
                    [
                      _c("div", [
                        _vm._v(
                          _vm._s(_vm.mensagem) + "\n                        "
                        ),
                        _vm.url
                          ? _c(
                              "span",
                              { staticStyle: { "padding-left": "5px" } },
                              [
                                _c("a", { attrs: { href: _vm.url } }, [
                                  _c("i", {
                                    staticClass: "fa fa-external-link",
                                    attrs: { "aria-hidden": "true" }
                                  })
                                ])
                              ]
                            )
                          : _vm._e()
                      ])
                    ]
                  )
                ])
              ])
            : _vm._e()
        ])
      : _vm._e()
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "col-md-1 col-xs-2",
        staticStyle: { "padding-left": "0" }
      },
      [_c("i", { staticClass: "fa fa-asterisk" })]
    )
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-3f6ae7f8", module.exports)
  }
}

/***/ }),

/***/ 5:
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(process) {

var utils = __webpack_require__(1);
var normalizeHeaderName = __webpack_require__(16);

var PROTECTION_PREFIX = /^\)\]\}',?\n/;
var DEFAULT_CONTENT_TYPE = {
  'Content-Type': 'application/x-www-form-urlencoded'
};

function setContentTypeIfUnset(headers, value) {
  if (!utils.isUndefined(headers) && utils.isUndefined(headers['Content-Type'])) {
    headers['Content-Type'] = value;
  }
}

function getDefaultAdapter() {
  var adapter;
  if (typeof XMLHttpRequest !== 'undefined') {
    // For browsers use XHR adapter
    adapter = __webpack_require__(7);
  } else if (typeof process !== 'undefined') {
    // For node use HTTP adapter
    adapter = __webpack_require__(7);
  }
  return adapter;
}

var defaults = {
  adapter: getDefaultAdapter(),

  transformRequest: [function transformRequest(data, headers) {
    normalizeHeaderName(headers, 'Content-Type');
    if (utils.isFormData(data) ||
      utils.isArrayBuffer(data) ||
      utils.isStream(data) ||
      utils.isFile(data) ||
      utils.isBlob(data)
    ) {
      return data;
    }
    if (utils.isArrayBufferView(data)) {
      return data.buffer;
    }
    if (utils.isURLSearchParams(data)) {
      setContentTypeIfUnset(headers, 'application/x-www-form-urlencoded;charset=utf-8');
      return data.toString();
    }
    if (utils.isObject(data)) {
      setContentTypeIfUnset(headers, 'application/json;charset=utf-8');
      return JSON.stringify(data);
    }
    return data;
  }],

  transformResponse: [function transformResponse(data) {
    /*eslint no-param-reassign:0*/
    if (typeof data === 'string') {
      data = data.replace(PROTECTION_PREFIX, '');
      try {
        data = JSON.parse(data);
      } catch (e) { /* Ignore */ }
    }
    return data;
  }],

  timeout: 0,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  maxContentLength: -1,

  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  }
};

defaults.headers = {
  common: {
    'Accept': 'application/json, text/plain, */*'
  }
};

utils.forEach(['delete', 'get', 'head'], function forEachMehtodNoData(method) {
  defaults.headers[method] = {};
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  defaults.headers[method] = utils.merge(DEFAULT_CONTENT_TYPE);
});

module.exports = defaults;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(171)))

/***/ }),

/***/ 6:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function bind(fn, thisArg) {
  return function wrap() {
    var args = new Array(arguments.length);
    for (var i = 0; i < args.length; i++) {
      args[i] = arguments[i];
    }
    return fn.apply(thisArg, args);
  };
};


/***/ }),

/***/ 7:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);
var settle = __webpack_require__(17);
var buildURL = __webpack_require__(19);
var parseHeaders = __webpack_require__(20);
var isURLSameOrigin = __webpack_require__(21);
var createError = __webpack_require__(8);
var btoa = (typeof window !== 'undefined' && window.btoa && window.btoa.bind(window)) || __webpack_require__(22);

module.exports = function xhrAdapter(config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    var requestData = config.data;
    var requestHeaders = config.headers;

    if (utils.isFormData(requestData)) {
      delete requestHeaders['Content-Type']; // Let the browser set it
    }

    var request = new XMLHttpRequest();
    var loadEvent = 'onreadystatechange';
    var xDomain = false;

    // For IE 8/9 CORS support
    // Only supports POST and GET calls and doesn't returns the response headers.
    // DON'T do this for testing b/c XMLHttpRequest is mocked, not XDomainRequest.
    if ("development" !== 'test' &&
        typeof window !== 'undefined' &&
        window.XDomainRequest && !('withCredentials' in request) &&
        !isURLSameOrigin(config.url)) {
      request = new window.XDomainRequest();
      loadEvent = 'onload';
      xDomain = true;
      request.onprogress = function handleProgress() {};
      request.ontimeout = function handleTimeout() {};
    }

    // HTTP basic authentication
    if (config.auth) {
      var username = config.auth.username || '';
      var password = config.auth.password || '';
      requestHeaders.Authorization = 'Basic ' + btoa(username + ':' + password);
    }

    request.open(config.method.toUpperCase(), buildURL(config.url, config.params, config.paramsSerializer), true);

    // Set the request timeout in MS
    request.timeout = config.timeout;

    // Listen for ready state
    request[loadEvent] = function handleLoad() {
      if (!request || (request.readyState !== 4 && !xDomain)) {
        return;
      }

      // The request errored out and we didn't get a response, this will be
      // handled by onerror instead
      // With one exception: request that using file: protocol, most browsers
      // will return status as 0 even though it's a successful request
      if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
        return;
      }

      // Prepare the response
      var responseHeaders = 'getAllResponseHeaders' in request ? parseHeaders(request.getAllResponseHeaders()) : null;
      var responseData = !config.responseType || config.responseType === 'text' ? request.responseText : request.response;
      var response = {
        data: responseData,
        // IE sends 1223 instead of 204 (https://github.com/mzabriskie/axios/issues/201)
        status: request.status === 1223 ? 204 : request.status,
        statusText: request.status === 1223 ? 'No Content' : request.statusText,
        headers: responseHeaders,
        config: config,
        request: request
      };

      settle(resolve, reject, response);

      // Clean up request
      request = null;
    };

    // Handle low level network errors
    request.onerror = function handleError() {
      // Real errors are hidden from us by the browser
      // onerror should only fire if it's a network error
      reject(createError('Network Error', config));

      // Clean up request
      request = null;
    };

    // Handle timeout
    request.ontimeout = function handleTimeout() {
      reject(createError('timeout of ' + config.timeout + 'ms exceeded', config, 'ECONNABORTED'));

      // Clean up request
      request = null;
    };

    // Add xsrf header
    // This is only done if running in a standard browser environment.
    // Specifically not if we're in a web worker, or react-native.
    if (utils.isStandardBrowserEnv()) {
      var cookies = __webpack_require__(23);

      // Add xsrf header
      var xsrfValue = (config.withCredentials || isURLSameOrigin(config.url)) && config.xsrfCookieName ?
          cookies.read(config.xsrfCookieName) :
          undefined;

      if (xsrfValue) {
        requestHeaders[config.xsrfHeaderName] = xsrfValue;
      }
    }

    // Add headers to the request
    if ('setRequestHeader' in request) {
      utils.forEach(requestHeaders, function setRequestHeader(val, key) {
        if (typeof requestData === 'undefined' && key.toLowerCase() === 'content-type') {
          // Remove Content-Type if data is undefined
          delete requestHeaders[key];
        } else {
          // Otherwise add header to the request
          request.setRequestHeader(key, val);
        }
      });
    }

    // Add withCredentials to request if needed
    if (config.withCredentials) {
      request.withCredentials = true;
    }

    // Add responseType to request if needed
    if (config.responseType) {
      try {
        request.responseType = config.responseType;
      } catch (e) {
        if (request.responseType !== 'json') {
          throw e;
        }
      }
    }

    // Handle progress if needed
    if (typeof config.onDownloadProgress === 'function') {
      request.addEventListener('progress', config.onDownloadProgress);
    }

    // Not all browsers support upload events
    if (typeof config.onUploadProgress === 'function' && request.upload) {
      request.upload.addEventListener('progress', config.onUploadProgress);
    }

    if (config.cancelToken) {
      // Handle cancellation
      config.cancelToken.promise.then(function onCanceled(cancel) {
        if (!request) {
          return;
        }

        request.abort();
        reject(cancel);
        // Clean up request
        request = null;
      });
    }

    if (requestData === undefined) {
      requestData = null;
    }

    // Send the request
    request.send(requestData);
  });
};


/***/ }),

/***/ 8:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var enhanceError = __webpack_require__(18);

/**
 * Create an Error with the specified message, config, error code, and response.
 *
 * @param {string} message The error message.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 @ @param {Object} [response] The response.
 * @returns {Error} The created error.
 */
module.exports = function createError(message, config, code, response) {
  var error = new Error(message);
  return enhanceError(error, config, code, response);
};


/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function isCancel(value) {
  return !!(value && value.__CANCEL__);
};


/***/ })

},[459]);