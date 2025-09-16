<template>
    <div class="col-sm-12">
        <table class="table table-bordered">

            <tr>
                <th colspan="5">
                    <img class="img-100-60" :src="product.thumb_url" :alt="product.name"/>
                    <span class="ml-4">
                        <small>{{ __("custom.product_name") }}: </small>
                        {{ product.name }}
                    </span>
                </th>
                <th colspan="2">
                    {{ __("custom.alert_quantity") }}
                    <input type="number" class="form-control" :name="'alert_quantity'" required v-model="stock_alert_quantity" :placeholder='__("custom.alert_quantity")'>
                </th>
            </tr>
            <tr>
                <th>{{ __("custom.warehouse_name") }}</th>
                <th width="20%">{{ __("custom.current_stock") }} <small
                    v-if="product.weight_unit">({{ product.weight_unit.name }})</small></th>
                <th width="20%">{{ __("custom.qty") }} <small v-if="product.weight_unit">({{
                        product.weight_unit.name
                    }})</small></th>
                <th>{{ __("custom.price") }}</th>
                <th>{{ __("custom.customer_buying_price") }}</th>
                <th>{{ __("custom.adjust_type") }} <small>({{ __("custom.stock")}})</small></th>
                <th></th>
            </tr>

            <tr v-for="(item, index) in items" :key="index">
                <td width="30%">
                    <select
                        @change="checkDuplicate(index, $event)"
                        class="form-control"
                        v-model="item.warehouse"
                        :name="'warehouse_stock[' + index + '][warehouse]'"
                    >
                        <option value="" selected>{{ __("custom.select_warehouse") }}</option>
                        <option
                            v-for="(item, index) in warehouses"
                            :key="index"
                            :value="item.id"
                        >
                            {{ item.name }}
                        </option>
                    </select>
                </td>
                <td>
                    <table class="table table-bordered">
                        <tr
                            v-for="(attr_item, attr_index) in product.attributes"
                            :key="attr_index"
                        >
                            <td>
                                {{ attr_item.attribute.name }}: {{ attr_item.attribute_item.name }}
                                <br>
                                <input
                                    :value="
                                        getItemValue(
                                          item.warehouse,
                                          attr_item.attribute_id,
                                          attr_item.attribute_item_id
                                        )
                                    "
                                    type="number"
                                    readonly
                                    class="form-control"
                                    :name="
                                        'warehouse_stock[' +
                                        index +
                                        '][stock][' +
                                        attr_item.attribute_id +
                                        '][' +
                                        attr_item.attribute_item_id +
                                        ']'
                                      "
                                />
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-bordered">
                        <tr
                            v-for="(attr_item, attr_index) in product.attributes"
                            :key="attr_index"
                        >
                            <td>
                                {{ attr_item.attribute.name }}: {{ attr_item.attribute_item.name }}
                                <br>
                                <input
                                    value="0"
                                    type="number"
                                    class="form-control"
                                    :name="
                    'warehouse_stock[' +
                    index +
                    '][quantity][' +
                    attr_item.attribute_id +
                    '][' +
                    attr_item.attribute_item_id +
                    ']'
                  "
                                />
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-bordered">
                        <tr
                            v-for="(attr_item, attr_index) in product.attributes"
                            :key="attr_index"
                        >
                            <td>
                                {{ attr_item.attribute.name }}: {{ attr_item.attribute_item.name }}
                                <br>
                                <input
                                    :value="
                                        getPriceValue(
                                          item.warehouse,
                                          attr_item.attribute_id,
                                          attr_item.attribute_item_id
                                        )
                                    "
                                    type="number"
                                    class="form-control"
                                    :name="
                    'warehouse_stock[' +
                    index +
                    '][price][' +
                    attr_item.attribute_id +
                    '][' +
                    attr_item.attribute_item_id +
                    ']'
                  "
                                />
                            </td>
                        </tr>

                    </table>
                </td>
                <td>
                    <table class="table table-bordered">
                        <tr
                            v-for="(attr_item, attr_index) in product.attributes"
                            :key="attr_index"
                        >
                            <td>
                                {{ attr_item.attribute.name }}: {{ attr_item.attribute_item.name }}
                                <br>
                                <input
                                    :value="
                                        getCustomerBuyingPriceValue(
                                          item.warehouse,
                                          attr_item.attribute_id,
                                          attr_item.attribute_item_id
                                        )
                                    "
                                    type="number"
                                    class="form-control"
                                    :name="
                    'warehouse_stock[' +
                    index +
                    '][customer_buying_price][' +
                    attr_item.attribute_id +
                    '][' +
                    attr_item.attribute_item_id +
                    ']'
                  "
                                />
                            </td>
                        </tr>

                    </table>
                </td>


                <td width="20%" v-if="old_stocks.length >0">
                    <select class="form-control" v-model="item.adjust_type"
                            :name="'warehouse_stock[' + index + '][adjust_type]'">
                        <option value="" selected>{{ __("custom.select_adjust_type") }}</option>
                        <option v-for="(item, index) in adjust_type" :key="index" :value="item">{{ item }}</option>
                    </select>
                </td>
                <td width="20%" v-else>
                    <select class="form-control" v-model="item.adjust_type" :name="'warehouse_stock[' + index + '][adjust_type]'">
                        <option value="" selected>{{ __("custom.select_adjust_type") }}</option>
                        <option value="Add" selected>Add</option>
                    </select>
                </td>
                <td width="5%">
                    <button v-if="item.id"
                        @click="deleteItem(index)"
                        type="button" disabled
                        class="btn btn-sm btn-outline-danger"
                    >
                        <i class="fa fa-trash"></i>
                    </button>

                    <button v-else
                            @click="deleteItem(index)"
                            type="button"
                            class="btn btn-sm btn-outline-danger"
                    >
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>

            <tfoot>
            <tr>
                <td colspan="7">
                    <button @click="addItem" type="button" class="btn btn-sm btn-info mb-4 float-right"
                            :title="__('custom.add_warehouse')">
                        <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>

export default {
    props:
        [
            "product",
            "warehouses",
            "old_stocks",
        ],
    data() {
        return {
            items: [
                {
                    id: "",
                    warehouse: "",
                    quantity: 0,
                    stock: 0,
                    adjust_type: '',
                    customer_buying_price: 0,
                    price: 0,
                },
            ],
            adjust_type: ['Add', 'Subtract'],
            stock_alert_quantity: 0
        };
    },
    mounted() {
        if (this.old_stocks.length > 0) {
            this.items = [];
            this.old_stocks.map((item) => {
                let is_duplicate = this.items.filter((child_item) => {
                    return child_item.warehouse == item.warehouse_id;
                });
                if (is_duplicate.length < 1) {
                    this.items.push({
                        id: item.id,
                        warehouse: item.warehouse_id,
                        stock: item.quantity,
                        quantity: 0,
                        adjust_type: '',
                        customer_buying_price: item.customer_buying_price,
                        price: item.price,
                    });
                }
            });
        }
        this.stock_alert_quantity = this.product.stock_alert_quantity
    },

    methods: {
        getPriceValue(warehouse, attribute, attribtue_item) {
            let find = this.old_stocks.find((item) => {
                if (
                    item.warehouse_id == warehouse &&
                    item.attribute_id == attribute &&
                    item.attribute_item_id == attribtue_item
                ) {
                    return item;
                } else {
                    return false;
                }
            });

            if (find) {
                return find.price
            } else {
                return this.product.price
            };
        },
        getCustomerBuyingPriceValue(warehouse, attribute, attribtue_item) {
            let find = this.old_stocks.find((item) => {
                if (
                    item.warehouse_id == warehouse &&
                    item.attribute_id == attribute &&
                    item.attribute_item_id == attribtue_item
                ) {
                    return item;
                } else {
                    return false;
                }
            });
            if (find) {
                return find.customer_buying_price
            } else {
                return this.product.customer_buying_price
            };
        },

        getItemValue(warehouse, attribute, attribtue_item) {
            let find = this.old_stocks.find((item) => {
                if (
                    item.warehouse_id == warehouse &&
                    item.attribute_id == attribute &&
                    item.attribute_item_id == attribtue_item
                ) {
                    return item;
                } else {
                    return false;
                }
            });

            if (find) {
                return find.quantity
            } else {
                return 0
            };
        },
        checkDuplicate(index, e) {
            let id = e.target.value;
            let is_duplicate = this.items.filter((item) => item.warehouse == id);
            if (is_duplicate.length > 1) {
                this.$swal.fire({
                    icon: "error",
                    text: "Duplicate warehouse selected!",
                });
                this.items.splice(index, 1);
            }
        },
        addItem() {
            this.items.push({
                id: "",
                warehouse: "",
                quantity: 0,
                adjust_type: '',
                customer_buying_price: 0,
                price: 0,
            });
        },
        deleteItem(index) {
            this.items.splice(index, 1);
        },
    },
};
</script>
