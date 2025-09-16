"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_admin_products_ProductAttributeEdit_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ["attributes", "product", "old_attribute_data"],
  data: function data() {
    return {
      old_attribute_id: [],
      items: []
    };
  },
  mounted: function mounted() {
    var _this = this;
    Object.keys(this.old_attribute_data).map(function (key) {
      axios.get("/admin/api/attribute-items/" + key).then(function (res) {
        _this.items.push({
          attribute: key,
          old_items: _this.old_attribute_data[key],
          attribute_items: res.data
        });
      })["catch"](function (err) {});
    });
  },
  methods: {
    isOldSelected: function isOldSelected(id, dataArray) {
      return dataArray.includes(id);
    },
    getAttributeItem: function getAttributeItem(index, e) {
      var _this2 = this;
      // Check already selected or not
      var already_added = this.items.filter(function (item) {
        return item.attribute == e.target.value;
      });
      if (already_added.length > 1) {
        this.deleteItem(index);
        this.$swal("Error!!!", "Already added.", "warning");
        return;
      }
      axios.get("/admin/api/attribute-items/" + e.target.value).then(function (res) {
        _this2.items[index].attribute_items = [];
        _this2.items[index].attribute_items = res.data;
      })["catch"](function (err) {});
    },
    addItem: function addItem() {
      this.items.push({
        attribute: "",
        attribute_items: [],
        old_items: []
      });
    },
    deleteItem: function deleteItem(index) {
      this.items.splice(index, 1);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=template&id=7568b372&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=template&id=7568b372& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-sm-12"
  }, [_c("table", {
    staticClass: "table table-bordered"
  }, _vm._l(_vm.items, function (item, index) {
    return _c("tr", {
      key: index
    }, [_c("td", {
      attrs: {
        width: "40%"
      }
    }, [_c("select", {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: item.attribute,
        expression: "item.attribute"
      }],
      staticClass: "form-control",
      attrs: {
        name: "attribute_data[" + index + "][attribute]"
      },
      on: {
        change: [function ($event) {
          var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
            return o.selected;
          }).map(function (o) {
            var val = "_value" in o ? o._value : o.value;
            return val;
          });
          _vm.$set(item, "attribute", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
        }, function ($event) {
          return _vm.getAttributeItem(index, $event);
        }]
      }
    }, [_c("option", {
      attrs: {
        value: ""
      }
    }, [_vm._v("Select")]), _vm._v(" "), _vm._l(_vm.attributes, function (item, index) {
      return _c("option", {
        key: index,
        domProps: {
          value: item.id
        }
      }, [_vm._v("\n              " + _vm._s(item.name) + "\n            ")]);
    })], 2)]), _vm._v(" "), _c("td", {
      staticClass: "p-4"
    }, [_c("div", {
      staticClass: "col-sm-12"
    }, [_c("div", {
      staticClass: "row"
    }, _vm._l(item.attribute_items, function (item2, index2) {
      return _c("div", {
        key: index2,
        staticClass: "col-sm-4 custom-control custom-checkbox"
      }, [_vm.isOldSelected(item2.id, item.old_items) ? _c("input", {
        staticClass: "form-check-input custom-control-input",
        attrs: {
          disabled: "",
          name: "attribute_data[" + index + "][attribute_items][]",
          type: "checkbox",
          id: "attribute_item_" + index + index2
        },
        domProps: {
          value: item2.id,
          checked: _vm.isOldSelected(item2.id, item.old_items)
        }
      }) : _c("input", {
        staticClass: "form-check-input custom-control-input",
        attrs: {
          name: "attribute_data[" + index + "][attribute_items][]",
          type: "checkbox",
          id: "attribute_item_" + index + index2
        },
        domProps: {
          value: item2.id,
          checked: _vm.isOldSelected(item2.id, item.old_items)
        }
      }), _vm._v(" "), _c("label", {
        staticClass: "form-check-label custom-control-label checkbox-label",
        attrs: {
          "for": "attribute_item_" + index + index2
        }
      }, [_vm._v("\n                  " + _vm._s(item2.name) + "\n                ")])]);
    }), 0)])]), _vm._v(" "), _c("td", {
      attrs: {
        width: "5%"
      }
    }, [item.old_items.length > 0 ? _c("button", {
      staticClass: "btn btn-sm btn-outline-danger",
      attrs: {
        type: "button",
        disabled: ""
      },
      on: {
        click: function click($event) {
          return _vm.deleteItem(index);
        }
      }
    }, [_c("i", {
      staticClass: "fa fa-trash"
    })]) : _c("button", {
      staticClass: "btn btn-sm btn-outline-danger",
      attrs: {
        type: "button"
      },
      on: {
        click: function click($event) {
          return _vm.deleteItem(index);
        }
      }
    }, [_c("i", {
      staticClass: "fa fa-trash"
    })])])]);
  }), 0), _vm._v(" "), _c("button", {
    staticClass: "btn btn-sm btn-info",
    attrs: {
      type: "button"
    },
    on: {
      click: _vm.addItem
    }
  }, [_c("i", {
    staticClass: "fa fa-plus"
  }), _vm._v(" " + _vm._s(_vm.__("custom.add_attribute")) + "\n    ")])])]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/admin/products/ProductAttributeEdit.vue":
/*!*************************************************************************!*\
  !*** ./resources/js/components/admin/products/ProductAttributeEdit.vue ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ProductAttributeEdit_vue_vue_type_template_id_7568b372___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProductAttributeEdit.vue?vue&type=template&id=7568b372& */ "./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=template&id=7568b372&");
/* harmony import */ var _ProductAttributeEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProductAttributeEdit.vue?vue&type=script&lang=js& */ "./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProductAttributeEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProductAttributeEdit_vue_vue_type_template_id_7568b372___WEBPACK_IMPORTED_MODULE_0__.render,
  _ProductAttributeEdit_vue_vue_type_template_id_7568b372___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/admin/products/ProductAttributeEdit.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductAttributeEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ProductAttributeEdit.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductAttributeEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=template&id=7568b372&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=template&id=7568b372& ***!
  \********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductAttributeEdit_vue_vue_type_template_id_7568b372___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductAttributeEdit_vue_vue_type_template_id_7568b372___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ProductAttributeEdit_vue_vue_type_template_id_7568b372___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ProductAttributeEdit.vue?vue&type=template&id=7568b372& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/admin/products/ProductAttributeEdit.vue?vue&type=template&id=7568b372&");


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