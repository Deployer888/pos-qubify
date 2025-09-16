/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
import "sweetalert2/dist/sweetalert2.min.css";

import Vue from "vue";
import VueSweetalert2 from "vue-sweetalert2";
import Vuelidate from "vuelidate";
import Select2 from "v-select2-component";
import _ from "lodash";
window._ = _;

Vue.mixin(require("./trans"));
const formatNumber = require("./helpers").formatNumber;
Vue.prototype.$formatNumber = formatNumber;
Vue.use(VueSweetalert2);
Vue.use(Vuelidate);
Vue.config.productionTip = false;

Vue.component("Select2", Select2);

Vue.directive("focus", {
    // When the bound element is inserted into the DOM...
    inserted: function (el) {
        // Focus the element
        el.focus();
    },
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', () =>
//     import('./components/ExampleComponent.vue'));

Vue.component("attribute-add", () =>
    import("./components/admin/attributes/AttributeAdd.vue")
);
Vue.component("attribute-edit", () =>
    import("./components/admin/attributes/AttributeEdit.vue")
);
Vue.component("product-attribute-add", () =>
    import("./components/admin/products/ProductAttributeAdd.vue")
);
Vue.component("product-attribute-edit", () =>
    import("./components/admin/products/ProductAttributeEdit.vue")
);
Vue.component("normal-product-stock-update", () =>
    import("./components/admin/product_stocks/NormalProductStockUpdate.vue")
);
Vue.component("variant-product-stock-update", () =>
    import("./components/admin/product_stocks/VariantProductStockUpdate.vue")
);
Vue.component("expenses-add", () =>
    import("./components/admin/expenses/ExpensesAdd.vue")
);
Vue.component("expenses-edit", () =>
    import("./components/admin/expenses/ExpensesEdit.vue")
);
Vue.component("invoice-add", () =>
    import("./components/admin/invoices/InvoiceAdd.vue")
);
Vue.component("invoice-edit", () =>
    import("./components/admin/invoices/InvoiceEdit.vue")
);
Vue.component("sale-add", () => import("./components/admin/sales/SaleAdd.vue"));
/*Purchase*/
Vue.component("purchase-add", () =>
    import("./components/admin/purchase/PurchaseAdd.vue")
);
Vue.component("purchase-edit", () =>
    import("./components/admin/purchase/PurchaseEdit.vue")
);

// Customer
Vue.component("customer-invoice", () =>
    import("./components/customer/invoice/invoiceAdd.vue")
);
Vue.component("customer-invoice-edit", () =>
    import("./components/customer/invoice/invoiceEdit.vue")
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
});
