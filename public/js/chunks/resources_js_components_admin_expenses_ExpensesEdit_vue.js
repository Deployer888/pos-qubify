"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_admin_expenses_ExpensesEdit_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ["item_data", "currency_symbol"],
  data: function data() {
    return {
      formData: {
        items: [{
          item_name: "",
          item_qty: 1,
          amount: 0,
          note: ""
        }]
      }
    };
  },
  mounted: function mounted() {
    var _this = this;
    this.formData.items = [];
    this.item_data.map(function (item) {
      _this.formData.items.push({
        item_name: item.item_name,
        item_qty: item.item_qty,
        amount: item.amount,
        note: item.note
      });
    });
  },
  computed: {
    calculateTotal: function calculateTotal() {
      var total = 0;
      this.formData.items.map(function (item) {
        total += Number(item.amount);
      });
      return total;
    }
  },
  methods: {
    addItem: function addItem() {
      this.formData.items.push({
        item_name: "",
        item_qty: 1,
        amount: 0,
        note: ""
      });
    },
    deleteItem: function deleteItem(index) {
      this.formData.items.splice(index, 1);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=template&id=2c002c1e&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=template&id=2c002c1e& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", [_c("table", {
    staticClass: "table table-bordered table-striped table-sm"
  }, [_c("thead", [_c("tr", [_c("th", [_vm._v(_vm._s(_vm.__("custom.item_name")))]), _vm._v(" "), _c("th", [_vm._v(_vm._s(_vm.__("custom.item_qty")))]), _vm._v(" "), _c("th", [_vm._v(_vm._s(_vm.__("custom.amount")) + "(" + _vm._s(_vm.currency_symbol) + ")")]), _vm._v(" "), _c("th", [_vm._v(_vm._s(_vm.__("custom.note")))]), _vm._v(" "), _c("th", [_vm._v(_vm._s(_vm.__("custom.total")))])])]), _vm._v(" "), _c("tbody", _vm._l(_vm.formData.items, function (item, index) {
    return _c("tr", {
      key: index
    }, [_c("td", [_c("input", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: item.item_name,
        expression: "item.item_name"
      }],
      staticClass: "form-control",
      attrs: {
        type: "text",
        name: "data[" + index + "][item_name]",
        maxlength: "100",
        placeholder: "Item name"
      },
      domProps: {
        value: item.item_name
      },
      on: {
        input: function input($event) {
          if ($event.target.composing) return;
          _vm.$set(item, "item_name", $event.target.value);
        }
      }
    })]), _vm._v(" "), _c("td", [_c("input", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: item.item_qty,
        expression: "item.item_qty"
      }],
      staticClass: "form-control",
      attrs: {
        type: "text",
        name: "data[" + index + "][item_qty]",
        placeholder: "Item quantity"
      },
      domProps: {
        value: item.item_qty
      },
      on: {
        input: function input($event) {
          if ($event.target.composing) return;
          _vm.$set(item, "item_qty", $event.target.value);
        }
      }
    })]), _vm._v(" "), _c("td", [_c("input", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: item.amount,
        expression: "item.amount"
      }],
      staticClass: "form-control",
      attrs: {
        type: "number",
        name: "data[" + index + "][amount]",
        placeholder: "Amount"
      },
      domProps: {
        value: item.amount
      },
      on: {
        input: function input($event) {
          if ($event.target.composing) return;
          _vm.$set(item, "amount", $event.target.value);
        }
      }
    })]), _vm._v(" "), _c("td", [_c("input", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: item.note,
        expression: "item.note"
      }],
      staticClass: "form-control",
      attrs: {
        type: "text",
        name: "data[" + index + "][note]",
        maxlength: "100",
        placeholder: "Note"
      },
      domProps: {
        value: item.note
      },
      on: {
        input: function input($event) {
          if ($event.target.composing) return;
          _vm.$set(item, "note", $event.target.value);
        }
      }
    })]), _vm._v(" "), _c("td", [_c("button", {
      staticClass: "btn btn-sm btn-outline-danger",
      on: {
        click: function click($event) {
          return _vm.deleteItem(index);
        }
      }
    }, [_c("i", {
      staticClass: "fa fa-trash"
    })])])]);
  }), 0), _vm._v(" "), _c("tfoot", [_c("tr", [_c("td", {
    attrs: {
      colspan: "4"
    }
  }, [_c("label", {
    attrs: {
      "for": ""
    }
  }, [_vm._v(_vm._s(_vm.__("custom.total")))])]), _vm._v(" "), _c("td", [_c("p", [_c("b", [_vm._v(_vm._s(_vm.currency_symbol) + _vm._s(_vm.calculateTotal))])])])])])]), _vm._v(" "), _c("button", {
    staticClass: "btn btn-info btn-sm",
    attrs: {
      type: "button"
    },
    on: {
      click: _vm.addItem
    }
  }, [_c("i", {
    staticClass: "fa fa-plus"
  }), _vm._v(" " + _vm._s(_vm.__("custom.add_item")) + "\n  ")])]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/admin/expenses/ExpensesEdit.vue":
/*!*****************************************************************!*\
  !*** ./resources/js/components/admin/expenses/ExpensesEdit.vue ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ExpensesEdit_vue_vue_type_template_id_2c002c1e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ExpensesEdit.vue?vue&type=template&id=2c002c1e& */ "./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=template&id=2c002c1e&");
/* harmony import */ var _ExpensesEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ExpensesEdit.vue?vue&type=script&lang=js& */ "./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ExpensesEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ExpensesEdit_vue_vue_type_template_id_2c002c1e___WEBPACK_IMPORTED_MODULE_0__.render,
  _ExpensesEdit_vue_vue_type_template_id_2c002c1e___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/admin/expenses/ExpensesEdit.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ExpensesEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ExpensesEdit.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ExpensesEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=template&id=2c002c1e&":
/*!************************************************************************************************!*\
  !*** ./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=template&id=2c002c1e& ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ExpensesEdit_vue_vue_type_template_id_2c002c1e___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ExpensesEdit_vue_vue_type_template_id_2c002c1e___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ExpensesEdit_vue_vue_type_template_id_2c002c1e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ExpensesEdit.vue?vue&type=template&id=2c002c1e& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/expenses/ExpensesEdit.vue?vue&type=template&id=2c002c1e&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ normalizeComponent)
/* harmony export */ });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent(
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */,
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options =
    typeof scriptExports === 'function' ? scriptExports.options : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) {
    // server build
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
    hook = shadowMode
      ? function () {
          injectStyles.call(
            this,
            (options.functional ? this.parent : this).$root.$options.shadowRoot
          )
        }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection(h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing ? [].concat(existing, hook) : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ })

}]);