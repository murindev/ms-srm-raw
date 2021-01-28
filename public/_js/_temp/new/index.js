/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

module.exports = {
  '#': { pattern: /\d/ },
  'X': { pattern: /[0-9a-zA-Z]/ },
  'S': { pattern: /[a-zA-Z]/ },
  'A': { pattern: /[a-zA-Z]/, transform: function transform(v) {
      return v.toLocaleUpperCase();
    } },
  'a': { pattern: /[a-zA-Z]/, transform: function transform(v) {
      return v.toLocaleLowerCase();
    } },
  '!': { escape: true }

  // https://github.com/fernandofleury/vanilla-masker/blob/master/lib/vanilla-masker.js
  // DIGIT = "9",
  // ALPHA = "A",
  // ALPHANUM = "S"

  // https://github.com/niksmr/vue-masked-input
  // 1 - number
  // a - letter
  // A - letter, forced to upper case when entered
  // * - alphanumeric
  // # - alphanumeric, forced to upper case when entered
  // + - any character

  // https://github.com/probil/v-mask
  // #	Number (0-9)
  // A	Letter in any case (a-z,A-Z)
  // N	Number or letter
  // X	Any symbol

  // https://github.com/igorescobar/jQuery-Mask-Plugin/blob/master/src/jquery.mask.js#L518
  // '0': {pattern: /\d/},
  // '9': {pattern: /\d/, optional: true},
  // '#': {pattern: /\d/, recursive: true},
  // 'A': {pattern: /[a-zA-Z0-9]/},
  // 'S': {pattern: /[a-zA-Z]/}

  // https://github.com/the-darc/string-mask
  // 0	Any numbers
  // 9	Any numbers (Optional)
  // #	Any numbers (recursive)
  // A	Any alphanumeric character
  // a	Any alphanumeric character (Optional) Not implemented yet
  // S	Any letter
  // U	Any letter (All lower case character will be mapped to uppercase)
  // L	Any letter (All upper case character will be mapped to lowercase)
  // $	Escape character, used to escape any of the special formatting characters.

};

/***/ }),
/* 1 */,
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__masker__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__tokens__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__tokens___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__tokens__);



// https://developer.mozilla.org/en-US/docs/Web/Guide/Events/Creating_and_triggering_events#The_old-fashioned_way
function event(name) {
  var evt = document.createEvent('Event');
  evt.initEvent(name, true, true);
  return evt;
}

/* harmony default export */ __webpack_exports__["a"] = (function (el, binding) {
  var config = binding.value;
  if (Array.isArray(config) || typeof config === 'string') {
    config = {
      mask: config,
      tokens: __WEBPACK_IMPORTED_MODULE_1__tokens___default.a
    };
  }

  if (el.tagName.toLocaleUpperCase() !== 'INPUT') {
    var els = el.getElementsByTagName('input');
    if (els.length !== 1) {
      throw new Error("v-mask directive requires 1 input, found " + els.length);
    } else {
      el = els[0];
    }
  }

  el.oninput = function (evt) {
    if (!evt.isTrusted) return; // avoid infinite loop
    /* other properties to try to diferentiate InputEvent of Event (custom)
    InputEvent (native)
      cancelable: false
      isTrusted: true
       composed: true
      isComposing: false
      which: 0
     Event (custom)
      cancelable: true
      isTrusted: false
    */
    // by default, keep cursor at same position as before the mask
    var position = el.selectionEnd;
    // save the character just inserted
    var digit = el.value[position - 1];
    el.value = Object(__WEBPACK_IMPORTED_MODULE_0__masker__["a" /* default */])(el.value, config.mask, true, config.tokens);
    // if the digit was changed, increment position until find the digit again
    while (position < el.value.length && el.value.charAt(position - 1) !== digit) {
      position++;
    }
    if (el === document.activeElement) {
      el.setSelectionRange(position, position);
      setTimeout(function () {
        el.setSelectionRange(position, position);
      }, 0);
    }
    el.dispatchEvent(event('input'));
  };

  var newDisplay = Object(__WEBPACK_IMPORTED_MODULE_0__masker__["a" /* default */])(el.value, config.mask, true, config.tokens);
  if (newDisplay !== el.value) {
    el.value = newDisplay;
    el.dispatchEvent(event('input'));
  }
});

/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__maskit__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__dynamic_mask__ = __webpack_require__(7);


// Facade to maskit/dynamicMask when mask is String or Array
/* harmony default export */ __webpack_exports__["a"] = (function (value, mask) {
       var masked = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;
       var tokens = arguments[3];

       return Array.isArray(mask) ? Object(__WEBPACK_IMPORTED_MODULE_1__dynamic_mask__["a" /* default */])(__WEBPACK_IMPORTED_MODULE_0__maskit__["a" /* default */], mask, tokens)(value, mask, masked, tokens) : Object(__WEBPACK_IMPORTED_MODULE_0__maskit__["a" /* default */])(value, mask, masked, tokens);
});

/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(5);


/***/ }),
/* 5 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "version", function() { return version; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__tokens__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__tokens___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__tokens__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__directive__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__component_vue__ = __webpack_require__(13);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__component_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__component_vue__);
/* harmony reexport (default from non-hamory) */ __webpack_require__.d(__webpack_exports__, "TheMask", function() { return __WEBPACK_IMPORTED_MODULE_2__component_vue___default.a; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "mask", function() { return __WEBPACK_IMPORTED_MODULE_1__directive__["a"]; });
/* harmony reexport (default from non-hamory) */ __webpack_require__.d(__webpack_exports__, "tokens", function() { return __WEBPACK_IMPORTED_MODULE_0__tokens___default.a; });
/*
import tokens from './tokens'
import mask from './directive'


import Vue from 'vue';
import TheMask from './component.vue'
// Vue.use(tokens);
// Vue.use(mask);

Vue.component('TheMask',TheMask);
const eventBus = new Vue({
    el:'#mask'
});
*/





function install(Vue) {
    Vue.component(__WEBPACK_IMPORTED_MODULE_2__component_vue___default.a.name, __WEBPACK_IMPORTED_MODULE_2__component_vue___default.a);
    Vue.directive('mask', __WEBPACK_IMPORTED_MODULE_1__directive__["a" /* default */]);
}

var version = proccess.env.VERSION;

/* harmony default export */ __webpack_exports__["default"] = (install);


if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(install);
}

/***/ }),
/* 6 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = maskit;
function maskit(value, mask) {
  var masked = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;
  var tokens = arguments[3];

  value = value || '';
  mask = mask || '';
  var iMask = 0;
  var iValue = 0;
  var output = '';
  while (iMask < mask.length && iValue < value.length) {
    var cMask = mask[iMask];
    var masker = tokens[cMask];
    var cValue = value[iValue];
    if (masker && !masker.escape) {
      if (masker.pattern.test(cValue)) {
        output += masker.transform ? masker.transform(cValue) : cValue;
        iMask++;
      }
      iValue++;
    } else {
      if (masker && masker.escape) {
        iMask++; // take the next mask char and treat it as char
        cMask = mask[iMask];
      }
      if (masked) output += cMask;
      if (cValue === cMask) iValue++; // user typed the same char
      iMask++;
    }
  }

  // fix mask that ends with a char: (#)
  var restOutput = '';
  while (iMask < mask.length && masked) {
    var cMask = mask[iMask];
    if (tokens[cMask]) {
      restOutput = '';
      break;
    }
    restOutput += cMask;
    iMask++;
  }

  return output + restOutput;
}

/***/ }),
/* 7 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = dynamicMask;
function dynamicMask(maskit, masks, tokens) {
  masks = masks.sort(function (a, b) {
    return a.length - b.length;
  });
  return function (value, mask) {
    var masked = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;

    var i = 0;
    while (i < masks.length) {
      var currentMask = masks[i];
      i++;
      var nextMask = masks[i];
      if (!(nextMask && maskit(value, nextMask, true, tokens).length > currentMask.length)) {
        return maskit(value, currentMask, masked, tokens);
      }
    }
    return ''; // empty masks
  };
}

/***/ }),
/* 8 */,
/* 9 */,
/* 10 */,
/* 11 */,
/* 12 */,
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(14)
/* script */
var __vue_script__ = __webpack_require__(15)
/* template */
var __vue_template__ = __webpack_require__(16)
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
Component.options.__file = "resources/newjs/Components/utils/mask/component.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-59aa62c3", Component.options)
  } else {
    hotAPI.reload("data-v-59aa62c3", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 14 */
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
/* 15 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__directive__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__tokens__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__tokens___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__tokens__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__masker__ = __webpack_require__(3);
//
//
//
//
//
//
//





/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'TheMask',
  props: {
    value: [String, Number],
    mask: {
      type: [String, Array],
      required: true
    },
    masked: { // by default emits the value unformatted, change to true to format with the mask
      type: Boolean,
      default: false // raw
    },
    tokens: {
      type: Object,
      default: function _default() {
        return __WEBPACK_IMPORTED_MODULE_1__tokens___default.a;
      }
    }
  },
  directives: { mask: __WEBPACK_IMPORTED_MODULE_0__directive__["a" /* default */] },
  data: function data() {
    return {
      lastValue: null, // avoid unecessary emit when has no change
      display: this.value
    };
  },

  watch: {
    value: function value(newValue) {
      if (newValue !== this.lastValue) {
        this.display = newValue;
      }
    },
    masked: function masked() {
      this.refresh(this.display);
    }
  },
  computed: {
    config: function config() {
      return {
        mask: this.mask,
        tokens: this.tokens,
        masked: this.masked
      };
    }
  },
  methods: {
    onInput: function onInput(e) {
      if (e.isTrusted) return; // ignore native event
      this.refresh(e.target.value);
    },
    refresh: function refresh(value) {
      this.display = value;
      var value = Object(__WEBPACK_IMPORTED_MODULE_2__masker__["a" /* default */])(value, this.mask, this.masked, this.tokens);
      if (value !== this.lastValue) {
        this.lastValue = value;
        console.log(value);
        if (value.length === 18) this.$emit('focusout', value);
      }
    }
  }
});

/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("input", {
      directives: [
        {
          name: "mask",
          rawName: "v-mask",
          value: _vm.config,
          expression: "config"
        }
      ],
      attrs: { type: "text", rel: "yy" },
      domProps: { value: _vm.display },
      on: { input: _vm.onInput }
    })
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-59aa62c3", module.exports)
  }
}

/***/ })
/******/ ]);