<template>
    <div class="row">

        <div class="col-12" v-if="isExistsValidationErrors">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger alert-important alert-dismissible fade show mb-0" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <li v-for="(validationError, index) in validationErrors.errors" :key="index">
                            {{ validationError[0] }}
                        </li>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row">
                <div class="col-lg-5 col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-lg-6 col-md-6">
                                    <form @submit.prevent="submitBarcode()">
                                        <div class="form-group">
                                            <input v-focus v-model="search" type="text" class="form-control"
                                                placeholder="Search Product or Scan Barcode"
                                                @keyup="searchSelectSku($event)" />
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-6 col-lg-6 col-md-6">
                                    <Select2 v-model="selected_category" :options="categories"
                                        :settings="{ placeholder: 'Select Category' }" @select="selectCategory($event)">
                                    </Select2>
                                </div>
                            </div>
                            <div class="ic-product-head" v-if="showProduct">
                                <div v-for="(product_stock, index) in product_list" :key="index"
                                    class="product-items pt-0">
                                    <div class="product-item" @click="addNewItem(product_stock)">
                                        <div class="ic-images-out-of-stock">
                                            <img class="img-fluid list-image card-img-top"
                                                :src="product_stock.product.thumb_url" alt="Product" />
                                            <div v-if="product_stock.product.stock < 1" class="ic-stock-overlay">
                                                <p>{{ __("custom.stock_out") }}</p>
                                            </div>
                                        </div>
                                        <div class="product-item-body p-2">
                                            <label class="m-0">{{ product_stock.product.name }}</label>
                                            <p class="card-text p-0 m-0" v-if="product_stock.product.is_variant == 1">
                                                {{ product_stock.attribute.name }}: {{ product_stock.attribute_item.name
                                                }}
                                            </p>
                                            <p class="card-text p-0 m-0">
                                                {{ __("custom.price") }}: {{ currency_symbol }} {{
                                                    product_stock.price_for_sale }}
                                            </p>
                                            <p class="card-text p-0 m-0">
                                                {{ __("custom.stock") }}: {{ product_stock.quantity }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <p class="text-white m-0">{{ __("custom.no_product_found") }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="from-group mt-3 mb-3">
                                <label for="">{{ __("custom.date") }}</label>
                                <datepicker input-class="form-control" v-model="formData.date" format="yyyy-MM-dd"
                                    placeholder="Select date" v-model.trim="$v.formData.date.$model"></datepicker>
                                <small class="error" v-if="!$v.formData.date.required">
                                    {{ __("custom.required") }}
                                </small>
                            </div>

                            <div class="from-group mt-3 mb-3">
                                <label for="">{{ __("custom.due_date") }}</label>
                                <datepicker input-class="form-control" v-model="formData.due_date" format="yyyy-MM-dd"
                                    placeholder="Select due date"></datepicker>
                            </div>
                            <div class="table-responsive ic-table-responsive-heading">
                                <table class="
                table table-hover table-sm table-borderedless table-striped
              ">
                                    <thead>
                                        <tr>
                                            <th>{{ __("custom.item") }}</th>
                                            <th class="text-center">{{ __("custom.price") }} ({{ currency_symbol }})
                                            </th>
                                            <th class="text-center">{{ __("custom.qty") }}</th>
                                            <th class="text-center">{{ __("custom.dis") }}</th>
                                            <th class="text-center">{{ __("custom.dis_type") }}</th>
                                            <th class="text-center">{{ __("custom.sub_total") }}</th>
                                            <th class="text-center">{{ __("custom.action") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in formData.items" :key="index">
                                            <td>
                                                <p class="p-0 m-0" v-if="item.is_variant && item.is_variant == 1">{{
                                                    item.name }} ({{ item.attribute.name + ':' +
                                                        item.attribute_item.name }})</p>
                                                <p class="p-0 m-0" v-else>{{ item.name }}</p>
                                            </td>
                                            <td>
                                                <input min="1" type="number" v-model="item.price" readonly
                                                    @input="updatePrice($event, index)"
                                                    class="form-control text-center" />
                                            </td>
                                            <td>
                                                <input min="1" type="number" v-model="item.quantity"
                                                    @input="updateQuantity($event, index)"
                                                    class="form-control text-center" />
                                            </td>
                                            <td>
                                                <input min="0" type="number" readonly v-model="item.discount"
                                                    class="form-control text-center" />
                                            </td>
                                            <td>
                                                <select v-model="item.discount_type" class="form-control" disabled>
                                                    <option value="percent">%</option>
                                                    <option value="fixed">{{ __("custom.fixed") }}</option>
                                                </select>
                                            </td>
                                            <td>{{ currency_symbol }} {{ formatNumber(calculateSubtotal(index)) }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    @click.prevent="deleteItem(index)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <b>{{ __("custom.sub_total") }}</b>
                                            </td>
                                            <td>
                                                <b>{{ currency_symbol }} {{ formatNumber(calculateTotalWithOutTax())
                                                    }}</b>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <!--                                    <tr v-if="cartNotEmpty">-->
                                        <!--                                        <td colspan="2">-->
                                        <!--                                            <b>{{ __("custom.discount") }}</b>-->
                                        <!--                                        </td>-->
                                        <!--                                        <td>-->
                                        <!--                                            <input-->
                                        <!--                                                type="number"-->
                                        <!--                                                v-model="formData.discount"-->
                                        <!--                                                class="form-control"-->
                                        <!--                                            />-->
                                        <!--                                        </td>-->
                                        <!--                                        <td colspan="2">-->
                                        <!--                                            <select-->
                                        <!--                                                v-model="formData.discount_type"-->
                                        <!--                                                class="form-control"-->
                                        <!--                                            >-->
                                        <!--                                                <option value="percent">%</option>-->
                                        <!--                                                <option value="fixed">{{ __("custom.fixed") }}</option>-->
                                        <!--                                            </select>-->
                                        <!--                                        </td>-->
                                        <!--                                        <td>-->
                                        <!--                                            <b>{{ currency_symbol }} {{ calculateGlobalDiscount() }}</b>-->
                                        <!--                                        </td>-->
                                        <!--                                        <td></td>-->
                                        <!--                                    </tr>-->

                                        <tr v-if="cartNotEmpty">
                                            <td colspan="5">
                                                <b>{{ __("custom.total_discount") }}</b>
                                            </td>
                                            <td>
                                                <b>{{ currency_symbol }} {{ formatNumber(calculateTotalDiscount())
                                                    }}</b>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <b>{{ __("custom.tax") }}</b>
                                            </td>
                                            <td>
                                                <b>{{ currency_symbol }} {{ formatNumber(totalTax()) }}</b>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <b>{{ __("custom.total") }}</b>
                                            </td>
                                            <td>
                                                <b>{{ currency_symbol }} {{ formatNumber(calculateTotalWithTax()) }}</b>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="form-grou">
                                <div class="row">
                                    <div class="col-8">
                                        <label for="">{{ __("custom.coupon") }}</label>
                                        <input class="form-control" name="coupon" v-model="formData.coupon.code" />
                                    </div>
                                    <div class="col-4">
                                        <label for="">&nbsp;</label>
                                        <button type="button" class="btn btn-primary btn-block" @click="applyCoupon">
                                            {{ __("custom.apply") }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 p-0">
                                <label for="" class="w-100">{{ __("custom.payment") }}</label>
                                <div class="ic-payment-method">
                                    <div class="payment-method mr-1">
                                        <input v-model="formData.payment_type" type="radio" value="online" id="paypal"
                                            name="payment_type" />
                                        <label class="radio-inline radio-image" for="paypal">
                                            <span></span>
                                            <img :src="asset('admin/images/online.png')" class="ic-paypal"
                                                alt="images" />
                                        </label>
                                    </div>
                                </div>

                                <div class="from-group">
                                    <label for="">{{ __("custom.total_paid") }}</label>
                                    <input v-model="formData.total_paid" type="number" min="0" class="form-control" />
                                </div>
                            </div>
                            <div class="form-grou">
                                <label for="">{{ __("custom.note") }}</label>
                                <textarea v-model="formData.notes" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                            <div class="col-sm-12 p-0 mt-3">
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <button v-if="cartNotEmpty" type="button" class="btn btn-light btn-block"
                                            @click="submitInvoiceToDraft">
                                            {{ __("custom.update_draft") }}
                                        </button>
                                    </div>
                                    <div class="col-6 text-center">
                                        <button v-if="cartNotEmpty" type="button" class="btn btn-dark btn-block"
                                            @click="submitInvoice">
                                            {{ __("custom.confirm") }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import moment from "moment";
import axios from "axios";
import Datepicker from "vuejs-datepicker";
import { required } from "vuelidate/lib/validators";

export default {
    props: [
        "app_name",
        "product_stocks",
        "categories",
        "customers",
        "user",
        "default_tax",
        "invoice",
        "warehouse_id",
        "currency_symbol"
    ],
    components: {
        Datepicker,
    },
    data() {
        return {
            validationErrors: [],
            isExistsValidationErrors: 0,
            product_list: {},
            selected_category: "all",
            search: "",
            char_limit: 200,
            is_shipping_same_billing: false,
            formData: {
                warehouse_id: this.warehouse_id,
                payment_type: "cash",
                is_walkin_customer: false,
                walkin_customer: {
                    full_name: "",
                    phone: "",
                },
                bank_info: {
                    ac_no: "",
                    t_no: "",
                    date: "",
                },
                date: "",
                due_date: "",
                customer_id: "",
                billing: {
                    name: this.user.b_first_name + " " + this.user.b_last_name,
                    email: this.user.b_email,
                    phone: this.user.b_phone,
                    address_line_1: this.user.b_address_line_1,
                    address_line_2: this.user.b_address_line_2,
                    city: this.user.b_city,
                    state: this.user.state,
                    zip: this.user.b_zipcode,
                    country: this.user.b_country,
                },
                shipping: {
                    name: this.user.b_first_name + " " + this.user.b_last_name,
                    email: this.user.b_email,
                    phone: this.user.b_phone,
                    address_line_1: this.user.b_address_line_1,
                    address_line_2: this.user.b_address_line_2,
                    city: this.user.b_city,
                    state: this.user.state,
                    zip: this.user.b_zipcode,
                    country: this.user.b_country,
                },
                items: [],
                status: "",
                notes: "",
                tax: 0,
                discount: 0,
                discount_type: "percent", // percent, fixed
                total_paid: 0,
                coupon: {
                    code: "",
                    discount: 0,
                    discount_type: "percent", // percent, fixed
                },
            },
        };
    },
    mounted() {
        console.log('hii')
        // Set old data
        this.formData.customer_id = this.invoice.customer_id;
        this.formData.billing = this.invoice.billing_info;
        this.formData.shipping = this.invoice.shipping_info;
        this.formData.date = this.invoice.date;
        this.formData.due_date = this.invoice.due_date;
        this.formData.items = this.invoice.items_data;
        this.formData.payment_type = this.invoice.payment_type;
        this.formData.total_paid = this.invoice.last_paid;
        this.formData.notes = this.invoice.notes;
        this.formData.bank_info = this.invoice.bank_info;
        this.formData.discount = this.invoice.global_discount;
        this.formData.discount_type = this.invoice.global_discount_type;
        this.product_list = this.product_stocks;

        if (!this.invoice.customer_id) {
            this.formData.is_walkin_customer = true;
            this.formData.walkin_customer = this.invoice.customer;
        }
    },
    computed: {
        showProduct: function () {
            return this.product_stocks.length > 0 ? true : false;
        },
        cartNotEmpty: function () {
            return this.formData.items.length;
        },
        charactersLeft() {
            let char = this.form.note.length;
            return this.char_limit - char + " / " + this.char_limit;
        },
        isCustomerSelected: function () {
            if (this.formData.customer_id) {
                return true;
            } else {
                return false;
            }
        },
    },
    methods: {
        searchSelectSku(e) {
            let query = e.target.value;
            if (query.length > 2) {
                // Search product by sku
                axios
                    .get(`/customer/app/api/product-stocks/name-sku/search/${query}/${this.warehouse_id}`)
                    .then((res) => {
                        this.product_list = res.data.data;
                    })
                    .catch((err) => {
                    });
            } else if (query.length == 0) {
                let warehouse_id = this.warehouse_id;
                axios
                    .get(`/customer/app/api/product-stocks/warehouse/search/${warehouse_id}`)
                    .then((res) => {
                        this.product_list = res.data.data;
                    })
                    .catch((err) => {
                    });
                // this.searched_product = [];
            } else {
                this.searched_product = [];
            }
        },
        submitBarcode() {
            axios
                .get(`/customer/app/api/product-stocks/barcode/${this.search}`)
                .then((res) => {
                    let product_stock = res.data.data;

                    if (product_stock) {
                        if (product_stock.stock < 1) {
                            this.$swal("Error!!!", `Out of stock`, "warning");
                            // Empty search field
                            this.search = "";
                            return;
                        }

                        let already_added = this.formData.items.find(
                            (i) => i.id == product_stock.id
                        );

                        if (already_added) {
                            already_added.quantity = already_added.quantity + 1;
                        } else {
                            if (product_stock.product.is_variant == 1) {
                                this.formData.items.push({
                                    id: product_stock.id,
                                    attribute: {
                                        id: product_stock.attribute.id,
                                        name: product_stock.attribute.name,
                                    },
                                    attribute_item: {
                                        id: product_stock.attribute_item.id,
                                        name: product_stock.attribute_item.name,
                                    },
                                    is_variant: product_stock.product.is_variant,
                                    product_id: product_stock.product.id,
                                    split_sale: product_stock.product.split_sale,
                                    sku: product_stock.product.sku,
                                    name: product_stock.product.name,
                                    price: product_stock.price_for_sale,
                                    stock: product_stock.quantity,
                                    quantity: 1,
                                    tax_status: product_stock.product.tax_status,
                                    custom_tax: product_stock.product.custom_tax,
                                    discount: 0,
                                    discount_type: "percent",
                                });
                            } else {
                                this.formData.items.push({
                                    id: product_stock.id,
                                    attribute: {
                                        id: '',
                                        name: '',
                                    },
                                    attribute_item: {
                                        id: '',
                                        name: '',
                                    },
                                    is_variant: product_stock.product.is_variant,
                                    product_id: product_stock.product.id,
                                    split_sale: product_stock.product.split_sale,
                                    sku: product_stock.product.sku,
                                    name: product_stock.product.name,
                                    price: product_stock.price_for_sale,
                                    stock: product_stock.quantity,
                                    quantity: 1,
                                    tax_status: product_stock.product.tax_status,
                                    custom_tax: product_stock.product.custom_tax,
                                    discount: 0,
                                    discount_type: "percent",
                                });
                            }
                        }
                    }
                    let warehouse_id = this.warehouse_id;
                    axios
                        .get(`/customer/app/api/product-stocks/warehouse/search/${warehouse_id}`)
                        .then((res) => {
                            this.product_list = res.data.data;
                        })
                        .catch((err) => {
                        });
                })
                .catch((err) => {
                });
            // Empty search field
            this.search = "";
        },
        selectCategory({ id }) {
            axios
                .get(`/customer/app/api/product-stocks/category/${id}/${this.warehouse_id}`)
                .then((res) => {
                    this.product_list = res.data.data;
                })
                .catch((err) => {
                });
        },
        shippingSameBilling(e) {
            if (this.is_shipping_same_billing) {
                this.formData.shipping.name = this.formData.billing.name;
                this.formData.shipping.email = this.formData.billing.email;
                this.formData.shipping.phone = this.formData.billing.phone;
                this.formData.shipping.address_line_1 =
                    this.formData.billing.address_line_1;
                this.formData.shipping.address_line_2 =
                    this.formData.billing.address_line_2;
                this.formData.shipping.city = this.formData.billing.city;
                this.formData.shipping.state = this.formData.billing.state;
                this.formData.shipping.zip = this.formData.billing.zip;
                this.formData.shipping.country = this.formData.billing.country;
            }
        },
        billinAddressFull() {
            let { address_line_1, address_line_2, city, state, zip, country } =
                this.formData.billing;

            return `${address_line_1 != null ? address_line_1 + ', ' : ''} ${address_line_2 != null ? address_line_2 + ', ' : ''}
                    ${city != null ? city + ', ' : ''} ${state != null ? state + ', ' : ''}
                    ${zip != null ? zip + ', ' : ''} ${country != null ? country + ', ' : ''}`;
        },
        shippingAddressFull() {
            let { address_line_1, address_line_2, city, state, zip, country } =
                this.formData.shipping;

            return `${address_line_1 != null ? address_line_1 + ', ' : ''} ${address_line_2 != null ? address_line_2 + ', ' : ''}
                    ${city != null ? city + ', ' : ''} ${state != null ? state + ', ' : ''}
                    ${zip != null ? zip + ', ' : ''} ${country != null ? country + ', ' : ''}`;

            // return `${address_line_1}, ${address_line_2}, ${city}, ${state}, ${zip}, ${country}`;
        },
        selectedCustomer({ id }) {
            let customer = this.customers.find((item) => item.id == id);
            // Set billing address
            this.formData.billing = {
                name: customer.full_name,
                email: customer.email,
                phone: customer.b_phone,
                address_line_1: customer.b_address_line_1,
                address_line_2: customer.b_address_line_2,
                city: customer.b_city_data ? customer.b_city_data.name : "",
                state: customer.b_state_data ? customer.b_state_data.name : "",
                zip: customer.b_zipcode,
                country: customer.b_country_data ? customer.b_country_data.name : "",
            };
        },

        addNewItem(product_stock) {
            let found = this.formData.items.findIndex((p) => p.id == product_stock.id);
            if (found >= 0) {
                let item = this.formData.items[found];
                if (item.quantity >= product_stock.quantity) {
                    item.quantity = product_stock.quantity;
                    this.$swal("Error!!!", `Out of stock`, "warning");
                    // Empty search field
                    this.search = "";
                    return;
                }
                item.quantity = Number(item.quantity) + 1;
            } else {
                if (product_stock.stock < 1) {
                    this.$swal("Error!!!", `Out of stock`, "warning");
                    // Empty search field
                    this.search = "";
                    return;
                }

                if (product_stock.product.is_variant == 1) {
                    this.formData.items.push({
                        id: product_stock.id,
                        attribute: {
                            id: product_stock.attribute.id,
                            name: product_stock.attribute.name,
                        },
                        attribute_item: {
                            id: product_stock.attribute_item.id,
                            name: product_stock.attribute_item.name,
                        },
                        is_variant: product_stock.product.is_variant,
                        product_id: product_stock.product.id,
                        split_sale: product_stock.product.split_sale,
                        sku: product_stock.product.sku,
                        name: product_stock.product.name,
                        price: product_stock.price_for_sale,
                        stock: product_stock.quantity,
                        quantity: 1,
                        tax_status: product_stock.product.tax_status,
                        custom_tax: product_stock.product.custom_tax,
                        discount: 0,
                        discount_type: "percent",
                    });
                } else {
                    this.formData.items.push({
                        id: product_stock.id,
                        attribute: {
                            id: '',
                            name: '',
                        },
                        attribute_item: {
                            id: '',
                            name: '',
                        },
                        is_variant: product_stock.product.is_variant,
                        product_id: product_stock.product.id,
                        split_sale: product_stock.product.split_sale,
                        sku: product_stock.product.sku,
                        name: product_stock.product.name,
                        price: product_stock.price_for_sale,
                        stock: product_stock.quantity,
                        quantity: 1,
                        tax_status: product_stock.product.tax_status,
                        custom_tax: product_stock.product.custom_tax,
                        discount: 0,
                        discount_type: "percent",
                    });
                }
            }
        },
        deleteItem: function (index) {
            this.formData.items.splice(index, 1);
        },
        updatePrice(event, index) {
            const value = event.target.valueAsNumber;
            let item = this.formData.items[index];
        },
        updateQuantity(event, index) {
            const value = event.target.valueAsNumber;
            let item = this.formData.items[index];
            if (value > item.stock) {
                item.quantity = item.stock;
                this.$swal("Error!!!", `Out of stock`, "warning");
                return;
            }
        },

        calculateSubtotal(index) {
            let item = this.formData.items[index];
            const total = item.quantity * (item.price - this.calculateDiscount(item));
            // const total =
            //   (Number(item.price) + Number(this.calculateTax(item))) * item.quantity;
            return total.toFixed(2);
        },
        calculateTotalWithOutTax() {
            const total = this.itemsTotal() - this.totalTax();
            return Number(total).toFixed(2);
        },
        calculateTotalWithTax() {
            const total = this.itemsTotal() - this.calculateGlobalDiscount();
            return Number(total).toFixed(2);
        },
        itemsTotal() {
            if (this.formData.items.length > 0) {
                let total = 0;
                this.formData.items.map((item) => {
                    total =
                        (Number(item.price - this.calculateDiscount(item)) +
                            Number(this.calculateTax(item))) *
                        item.quantity +
                        Number(total);
                });

                return total.toFixed(2);
            }
            return 0;
        },

        totalTax() {
            let total = 0;
            this.formData.items.map((item) => {
                total = Number(this.calculateTax(item)) * item.quantity + Number(total);
            });

            return total.toFixed(2);
        },
        calculateTax(item) {
            let tax = 0;
            // Tax include
            if (item.tax_status == "included") {
                if (item.custom_tax) {
                    tax = item.price * (item.custom_tax / 100);
                } else {
                    tax = item.price * (Number(this.default_tax) / 100);
                }
            }

            return tax;
        },
        calculateDiscount(item) {
            if (item.discount_type == "percent") {
                const total = item.price * (item.discount / 100);
                return total.toFixed(2);
            } else {
                return item.discount;
            }
        },
        calculateGlobalDiscount() {
            if (this.formData.discount_type == "percent") {
                const total =
                    this.calculateTotalWithOutTax() * (this.formData.discount / 100);
                return total.toFixed(2);
            } else {
                return this.formData.discount;
            }
        },
        calculateAllDiscount() {
            let total = 0;
            this.formData.items.map((item) => {
                total += item.quantity * this.calculateDiscount(item);
            });
            return total.toFixed(2);
        },
        calculateTotalDiscount() {
            let total = 0;
            this.formData.items.map((item) => {
                total += item.quantity * this.calculateDiscount(item);
            });

            if (Number(this.formData.discount) > 0) {
                total = Number(total) + Number(this.calculateGlobalDiscount());
            }
            return Number(total).toFixed(2);
        },
        calculateDue() {
            let due = this.calculateTotalWithOutTax() - this.formData.total_paid;
            if (due <= 0) return 0;
            return due.toFixed(2);
        },
        calculateExchange() {
            let exchange = this.formData.total_paid - this.calculateTotalWithOutTax();
            if (exchange > 0) {
                this.form.exchange = exchange;
                return exchange.toFixed(2);
            } else {
                this.form.exchange = 0;
                return false;
            }
        },

        submitInvoice() {
            axios
                .post(`/customer/invoices/store/from-draft/${this.invoice.id}`, this.formData)
                .then((res) => {
                    window.location.href = "/customer/invoices/" + res.data.invoice;
                })
                .catch((err) => {
                    if (err.response.data.success == false) {
                        this.isExistsValidationErrors = 0
                        this.$swal("Error!!!", "Some thing went wrong!", "error");
                    } else {
                        this.validationErrors = err.response.data
                        this.isExistsValidationErrors = Object.keys(this.validationErrors.errors).length
                        this.$swal("Error!!!", "Some thing went wrong!", "error");
                    }
                });
        },
        print() {
            this.$htmlToPaper("invoice-print");
        },
        applyCoupon() {
            if (this.formData.coupon.code) {
                axios
                    .get("/customer/app/api/active-coupon/" + this.formData.coupon.code)
                    .then((res) => {
                        let abailable_product = false;
                        if (res.data.status == true) {
                            let coupon_product_ids = res.data.coupon_product_ids;

                            for (let i = 0; i < this.formData.items.length; i++) {
                                if (coupon_product_ids.includes(this.formData.items[i].product_id)) {
                                    if (this.formData.items[i].quantity >= res.data.coupon.minimum_shopping) {
                                        this.formData.items[i].discount = res.data.coupon.discount;
                                        this.formData.items[i].discount_type = res.data.coupon.discount_type;
                                        abailable_product = true;
                                    }
                                }
                            }
                            this.formData.coupon.discount = res.data.coupon.discount;
                            this.formData.coupon.discount_type = res.data.coupon.discount_type;

                            this.$swal("Success!", "Coupon applied successfully!", "success");

                            if (abailable_product == false) {
                                this.$swal("Error!!!", "This coupon is not applicable for this product!", "error");
                            } else {
                                this.$swal("Success!!!", "Coupon applied successfully!", "success");
                            }
                        } else {
                            this.$swal("Error!!!", "Some thing went wrong! Maybe coupon expire or invalid", "error");
                        }
                    })
                    .catch((err) => {
                        this.$swal("Error!!!", "Some thing went wrong!", "error");
                    });
            }
        },
        submitInvoiceToDraft() {
            if (this.formData.payment_type != "bank") {

                axios
                    .put(`/customer/draft-invoices/${this.invoice.id}`, this.formData)
                    .then((res) => {
                        window.location.href = "/customer/draft-invoices/" + res.data.invoice;
                    })
                    .catch(err => {
                        if (err.response.data.success == false) {
                            this.isExistsValidationErrors = 0
                            this.$swal("Error!!!", "Some thing went wrong!", "error");
                        } else {
                            this.validationErrors = err.response.data
                            this.isExistsValidationErrors = Object.keys(this.validationErrors.errors).length
                            this.$swal("Error!!!", "Some thing went wrong!", "error");
                        }
                    });

            } else if (this.formData.payment_type == "bank"
                && this.formData.bank_info.ac_no != ""
                && this.formData.bank_info.t_no != ""
                && this.formData.bank_info.date != "") {
                axios
                    .post("/customer/draft-invoices", this.formData)
                    .then((res) => {
                        window.location.href = "/customer/draft-invoices/" + res.data.invoice;
                    })
                    .catch((err) => {
                        if (err.response.data.success == false) {
                            this.isExistsValidationErrors = 0
                            this.$swal("Error!!!", "Some thing went wrong!", "error");
                        } else {
                            this.validationErrors = err.response.data
                            this.isExistsValidationErrors = Object.keys(this.validationErrors.errors).length
                            this.$swal("Error!!!", "Some thing went wrong!", "error");
                        }
                    });
            } else {
                alert("Please fill up all this three (Account Number, Transaction No, Transaction Date) field!")
            }
        },
    },
    filters: {
        custom_date: function (value) {
            if (value) {
                return moment(String(value)).format("YYYY-MM-DD hh:mm a");
            }
        },
    },
    validations: {
        formData: {
            date: {
                required,
            },
            customer_id: {
                required,
            },
            status: {
                required,
            },
        },
    },
};
</script>

<style scoped>
.list-image {
    height: 100px;
    object-fit: cover;
    object-position: center;
}

.product-item:hover {
    cursor: pointer;
}

.modal-footer {
    display: -ms-flexbox;
    display: block;
    -ms-flex-align: center;
    align-items: center;
    -ms-flex-pack: end;
    justify-content: flex-end;
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    border-bottom-right-radius: 0.3rem;
    border-bottom-left-radius: 0.3rem;
}
</style>
