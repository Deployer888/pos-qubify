<template>
  <form @submit.prevent="submitData">
    <div class="row">
      <div class="col-sm-4">
        <label for="">{{ __("custom.date") }}</label>
        <datepicker input-class="form-control" v-model="formData.date" format="yyyy-MM-dd" :use-utc=true
          placeholder="Select date" v-model.trim="$v.formData.date.$model"></datepicker>
        <small class="error" v-if="!$v.formData.date.required">
          {{ __("custom.field_is_required") }}
        </small>
      </div>

      <div class="col-sm-4">
        <label for="">{{ __("custom.due_date") }}</label>
        <datepicker input-class="form-control" v-model="formData.due_date" format="yyyy-MM-dd" :use-utc=true
          placeholder="Select due date"></datepicker>
      </div>

      <div class="col-sm-4">
        <label for="">{{ __("custom.customer") }}</label>
        <Select2 v-model="formData.customer_id" v-model.trim="$v.formData.customer_id.$model" :options="customers"
          @select="selectedCustomer($event)" />
        <small class="error" v-if="!$v.formData.customer_id.required">
          {{ __("custom.field_is_required") }}
        </small>
      </div>

      <div class="col-sm-6 mt-3">
        <div class="col-sm-12 p-0">
          <label for="" class="text-muted float-left">{{ __("custom.billing_info") }}
            <a href="#" data-toggle="modal" data-target=".billing-info-edit"><i class="fa fa-edit"></i></a></label>
          <div class="form-check text-right float-right">
            <input v-model="is_shipping_same_billing" class="form-check-input" type="checkbox" id="shippingSameBilling"
              @change="shippingSameBilling($event)" />
            <label class="form-check-label" for="shippingSameBilling">
              {{ __("custom.shipping_address_same_as_billing") }}
            </label>
          </div>
        </div>
        <div class="col-sm-12 mt-1 p-0 float-left" v-if="isCustomerSelected">
          <p class="m-0">
            {{ formData.billing.name }}, {{ formData.billing.email }},
            {{ formData.billing.phone }}
          </p>
          <p>{{ billinAddressFull() }}</p>
        </div>
      </div>

      <div class="col-sm-6 mt-4">
        <label for="" class="text-muted">{{
          __("custom.shipping_info")
        }}</label>
        <div class="row">
          <div class="col-sm-4">
            <label for="">{{ __("custom.name") }}</label>
            <input type="text" class="form-control" v-model="formData.shipping.name" />
          </div>
          <div class="col-sm-4">
            <label for="">{{ __("custom.email") }}</label>
            <input type="email" class="form-control" v-model="formData.shipping.email" />
          </div>
          <div class="col-sm-4">
            <label for="">{{ __("custom.phone") }}</label>
            <input type="text" class="form-control" v-model="formData.shipping.phone" />
          </div>
          <div class="col-sm-4">
            <label for="">{{ __("custom.address_line_1") }}</label>
            <input type="text" class="form-control" v-model="formData.shipping.address_line_1" />
          </div>
          <div class="col-sm-4">
            <label for="">{{ __("custom.address_line_2") }}</label>
            <input type="text" class="form-control" v-model="formData.shipping.address_line_2" />
          </div>
          <div class="col-sm-4">
            <label for="">City</label>
            <input type="text" class="form-control" v-model="formData.shipping.city" />
          </div>
          <div class="col-sm-4">
            <label for="">State</label>
            <input type="text" class="form-control" v-model="formData.shipping.state" />
          </div>
          <div class="col-sm-4">
            <label for="">Postcode/Zip Code</label>
            <input type="text" class="form-control" v-model="formData.shipping.zip" />
          </div>
          <div class="col-sm-4">
            <label for="">Country</label>
            <input type="text" class="form-control" v-model="formData.shipping.country" />
          </div>
        </div>
      </div>

      <div class="col-sm-4">
        <label for="">{{ __("custom.status") }}</label>
        <select class="form-control" v-model="formData.status" v-model.trim="$v.formData.status.$model">
          <option value="">Select status</option>
          <option v-for="(item, index) in all_status" :key="index" :value="index">
            {{ item }}
          </option>
        </select>

        <small class="error" v-if="!$v.formData.status.required">
          Field is required
        </small>
      </div>

      <div class="col-sm-12">
        <label for="">Notes</label>
        <textarea maxlength="250" v-model="formData.notes" class="form-control" name="notes" rows="3"></textarea>
      </div>

      <div class="col-sm-12 mt-4">
        <label for="">Search Product</label>
        <input @keyup="searchSelectSku($event)" class="form-control" placeholder="Search product by Name and SKU"
          type="text" v-model="query" />
        <div>
          <ul class="list-group">
            <li class="list-group-item" v-for="(item, index) in searched_product" :key="index">
              <a href="#;" @click="selectProduct(item.id)">({{ item.sku }}) {{ item.name }}</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="col-12 mt-4">
        <label for="">Products</label>
        <table class="table ic-table-return">
          <thead>
            <tr>
              <th>#</th>
              <th>SKU</th>
              <th>Name</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Sub Total</th>
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
              </td>
              <td>
                <span v-if="item.is_blank">
                  <input type="text" class="form-control" v-model="item.name" />
                </span>
                <span v-else>{{ item.name }}</span>
              </td>
              <td>
                <input class="form-control" type="number" v-model="item.quantity" min="1" />
              </td>
              <td>
                <input class="form-control" type="text" v-model="item.price" min="1" />
              </td>
              <td>
                <input disabled class="form-control" type="number" :value="calculateSubTotal(index)" />
              </td>
              <td>
                <a @click="deleteItem(index)" href="#" class="text-danger"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
            <tr>
              <td colspan="4"></td>
              <td>Discount</td>
              <td>
                <input min="0" type="number" class="form-control" v-model="formData.discount" />
              </td>
              <td>
                <select v-model="formData.discount_type" class="form-control">
                  <option value="percentage">Percentage</option>
                  <option value="fixed">Fixed</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="4"></td>
              <td>Tax(%)</td>
              <td>
                <input min="0" type="number" class="form-control" v-model="formData.tax" />
              </td>
              <td></td>
            </tr>
            <tr>
              <td colspan="4"></td>
              <td>Total</td>
              <td>
                <b>{{ $formatNumber(calculateTotal) }}</b>
              </td>
              <td></td>
            </tr>
          </tbody>
        </table>
        <button @click="addBlankItem" class="btn btn-sm btn-info" type="button">
          <i class="fa fa-plus"></i> Add Blank
        </button>
      </div>

      <div class="col-12 mt-4">
        <hr />
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-save"></i> Submit
        </button>

        <a href="/admin/invoices" class="btn btn-danger waves-effect waves-light ml-1">
          <i class="fa fa-times"></i> Cancel</a>
      </div>
    </div>

    <!-- Billing info edit modal -->
    <div class="modal fade billing-info-edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Billing Info</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-4">
                <label for="">Name</label>
                <input type="text" class="form-control" v-model="formData.billing.name" />
              </div>
              <div class="col-sm-4">
                <label for="">Email</label>
                <input type="email" class="form-control" v-model="formData.billing.email" />
              </div>
              <div class="col-sm-4">
                <label for="">Phone</label>
                <input type="text" class="form-control" v-model="formData.billing.phone" />
              </div>
              <div class="col-sm-4">
                <label for="">Address Line 1</label>
                <input type="text" class="form-control" v-model="formData.billing.address_line_1" />
              </div>
              <div class="col-sm-4">
                <label for="">Address Line 2</label>
                <input type="text" class="form-control" v-model="formData.billing.address_line_2" />
              </div>
              <div class="col-sm-4">
                <label for="">City</label>
                <input type="text" class="form-control" v-model="formData.billing.city" />
              </div>
              <div class="col-sm-4">
                <label for="">State</label>
                <input type="text" class="form-control" v-model="formData.billing.state" />
              </div>
              <div class="col-sm-4">
                <label for="">Postcode/Zip Code</label>
                <input type="text" class="form-control" v-model="formData.billing.zip" />
              </div>
              <div class="col-sm-4">
                <label for="">Country</label>
                <input type="text" class="form-control" v-model="formData.billing.country" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">
              Save & Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import { required } from "vuelidate/lib/validators";

export default {
  props: ["all_status", "customers"],
  components: {
    Datepicker,
  },
  data() {
    return {
      is_shipping_same_billing: false,
      query: "",
      searched_product: [],
      formData: {
        date: "",
        due_date: "",
        customer_id: "",
        billing: {
          name: "",
          email: "",
          phone: "",
          address_line_1: "",
          address_line_2: "",
          city: "",
          state: "",
          zip: "",
          country: "",
        },
        shipping: {
          name: "",
          email: "",
          phone: "",
          address_line_1: "",
          address_line_2: "",
          city: "",
          state: "",
          zip: "",
          country: "",
        },
        items: [],
        status: "",
        notes: "",
        tax: 0,
        discount: 0,
        discount_type: "percentage", // percentage, fixed
      },
    };
  },
  mounted() {
    this.formData.date = new Date().toISOString();
    // this.formData.due_date = new Date().toISOString();
  },
  computed: {
    isCustomerSelected: function () {
      if (this.formData.customer_id) {
        return true;
      } else {
        return false;
      }
    },
    calculateTotal: function () {
      let total = 0,
        discount,
        total_tax;

      // Calculate total
      this.formData.items.map((item) => {
        total += Number(item.price) * Number(item.quantity);
      });

      // Calculate discount
      if (this.formData.discount_type == "percentage") {
        discount = total * (this.formData.discount / 100);
      } else {
        discount = this.formData.discount;
      }

      // Reduce discount form total
      total = total - discount;

      // Calculate tax
      total_tax = total * (this.formData.tax / 100);
      // Reduce tax from total
      return Number(total + total_tax).toFixed(2);
    },
  },
  methods: {
    shippingSameBilling(e) {
      if (this.is_shipping_same_billing) {
        this.formData.shipping = this.formData.billing;
      } else {
        this.formData.shipping = {
          name: "",
          email: "",
          phone: "",
          address_line_1: "",
          address_line_2: "",
          city: "",
          state: "",
          zip: "",
          country: "",
        };
      }
    },
    billinAddressFull() {
      let { address_line_1, address_line_2, city, state, zip, country } =
        this.formData.billing;

      return `${address_line_1}, ${address_line_2}, ${city}, ${state}, ${zip}, ${country}`;
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

    calculateSubTotal: function (index) {
      let item = this.formData.items[index];
      let total = Number(item.quantity) * Number(item.price);
      return Number(total).toFixed(2);
    },
    addBlankItem() {
      this.formData.items.push({
        id: "",
        sku: "",
        name: "",
        price: 0,
        quantity: 1,
        is_blank: true,
      });
    },
    searchSelectSku(e) {
      let query = e.target.value;
      if (query.length > 2) {
        // Search product by sku
        axios
          .get(`/admin/app/api/products/name-sku/search/${query}`)
          .then((res) => {
            this.searched_product = res.data.data;
          })
          .catch((err) => { });
      } else {
        this.searched_product = [];
      }
    },
    selectProduct(id) {
      let product = this.searched_product.find((item) => item.id == id);

      let already_added = this.formData.items.find((i) => i.id == product.id);

      if (already_added) {
        this.$swal.fire({
          icon: "error",
          text: "Product already added to the list!",
        });
      } else {
        this.formData.items.push({
          id: product.id,
          sku: product.sku,
          name: product.name,
          price: product.price,
          quantity: 1,
          is_blank: false,
        });
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
    submitData() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.$swal.fire({
          icon: "error",
          text: "Please fill out all the required field!",
        });
      } else {
        axios
          .post("/admin/invoices", this.formData)
          .then((res) => {
            window.location.href = "/admin/invoices";
          })
          .catch((err) => { });
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
