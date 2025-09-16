<template>
    <div class="row">
      <div class="col-sm-12 mb-5">
        <label>{{ __("custom.search_product") }}</label>
        <input @keyup="searchSelectSku($event)" class="form-control" placeholder="Search product by Name and SKU"
          type="text" v-model="query" />
        <div>
          <ul class="list-group">
            <li @click="selectProduct(item.id)" class="list-group-item set_poniter"
              v-for="(item, index) in searched_product" :key="index">
              <a v-if="item.product.is_variant == 1" href="javascript:void(0)">({{ item.product.sku }}) {{
                item.product.name }} ({{ item.attribute.name }} : {{ item.attribute_item.name }})</a>
              <a v-else href="javascript:void(0)">({{ item.product.sku }}) {{ item.product.name }}</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="col-12">
        <label>{{ __("custom.product") }} <span class="error">*</span></label>
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __("custom.sku") }}</th>
              <th>{{ __("custom.name") }}</th>
              <th>{{ __("custom.quantity") }}</th>
              <th>{{ __("custom.price") }}</th>
              <th>{{ __("custom.note") }}</th>
              <th>{{ __("custom.sub_total") }}</th>
              <th>
                <a @click="deleteAllItem" href="#" class="text-danger"><i class="fa fa-trash"></i></a>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in formData.items" :key="index">
              <td>{{ index + 1 }}</td>
              <td>
                <span v-if="item.is_blank">
                  <input type="text" class="form-control" v-model="item.sku" />
                </span>
                <span v-else>{{ item.sku }}</span>

                <input type="hidden" name="product_stock_id[]" :value="item.id" />
                <input type="hidden" name="product_id[]" :value="item.product_id" />
              </td>
              <td>
                <span v-if="item.is_blank">
                  <input type="text" class="form-control" v-model="item.name" />
                </span>
                <span v-else>
                  <span v-if="item.is_variant == 1">
                    {{ item.name }} {{ item.attribute + ':' + item.attribute_item }}
                  </span>
                  <span v-else>
                    {{ item.name }}
                  </span>
                </span>
              </td>
              <td>
                <input class="form-control" type="number" v-model="item.quantity" min="1" name="quantity[]" />
              </td>
              <td>
                <input class="form-control" type="text" v-model="item.price" min="1" name="price[]" />
              </td>
              <td>
                <input type="text" class="form-control" name="product_note[]" />
              </td>
              <td>
                <input readonly class="form-control" type="number" :value="calculateSubTotal(index)" name="sub_total[]" />
              </td>
              <td>
                <a @click="deleteItem(index)" href="#" class="text-danger"><i class="fa fa-trash"></i></a>
              </td>
            </tr>

            <tr>
              <td colspan="5"></td>
              <td>{{ __("custom.total") }}</td>
              <td>
                <b>{{ currency_symbol }}{{ $formatNumber(calculateTotal) }}</b>
                <input type="hidden" name="total" :value="calculateTotal" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>

  <script>
  import { trans } from "../../../helpers";
  import { StreamBarcodeReader } from "vue-barcode-reader";

  export default {
    name: "PurchaseAdd",
    props: ["currency_symbol"],
    components: {
      StreamBarcodeReader,
    },
    data() {
      return {
        trans,
        query: "",
        barcodeScanner: false,
        searched_product: [],
        formData: {
          items: [],
          status: "",
          notes: "",
          tax: 0,
          discount: 0,
          discount_type: "percentage", // percentage, fixed,
          total: "",
        },
      };
    },
    mounted() { },
    methods: {
      calculateSubTotal: function (index) {
        let item = this.formData.items[index];
        let total = Number(item.quantity) * Number(item.price);
        return Number(total).toFixed(2);
      },

      searchSelectSku(e) {
        let query = e.target.value;
        if (query.length > 1) {
          // Search product by sku
          axios
            .get(`/admin/api/product-stock/search/name-sku/${query}`)
            .then((res) => {
              this.searched_product = res.data;
            })
            .catch((err) => { });
        } else {
          this.searched_product = [];
        }
      },
      selectProduct(id) {
        let product_stock = this.searched_product.find((item) => item.id == id);

        let already_added = this.formData.items.find((i) => i.id == product_stock.id);

        if (already_added) {
          this.$swal.fire({
            icon: "error",
            text: "Product already added.",
          });
        } else {
          if (product_stock.product.is_variant == 1) {
            this.formData.items.push({
              id: product_stock.id,
              product_id: product_stock.product_id,
              sku: product_stock.product.sku,
              attribute: product_stock.attribute.name,
              attribute_item: product_stock.attribute_item.name,
              name: product_stock.product.name,
              price: product_stock.price_for_sale,
              quantity: 1,
              is_variant: product_stock.product.is_variant,
              is_blank: false,
            });
          } else {
            this.formData.items.push({
              id: product_stock.id,
              product_id: product_stock.product.id,
              sku: product_stock.product.sku,
              attribute: null,
              attributeItem: null,
              name: product_stock.product.name,
              price: product_stock.price_for_sale,
              quantity: 1,
              is_variant: product_stock.product.is_variant,
              is_blank: false,
            });
          }
        }

        this.searched_product = [];
        this.query = "";
      },

      deleteItem(index) {
        this.formData.items.splice(index, 1);
      },
      deleteAllItem() {
        this.formData.items = [];
      },
    },
    computed: {
      calculateTotal: function () {
        let total = 0;

        // Calculate total
        this.formData.items.map((item) => {
          total += Number(item.price) * Number(item.quantity);
        });

        // Reduce tax from total
        return Number(total).toFixed(2);
      },
    },
  };
  </script>

  <style scoped>
  .set_poniter {
    cursor: pointer;
  }
  </style>
