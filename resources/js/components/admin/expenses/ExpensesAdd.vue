<template>
  <div>
    <table class="table table-bordered table-striped table-sm">
      <thead>
        <tr>
          <th>{{ __("custom.item_name") }}</th>
          <th>{{ __("custom.quantity") }}</th>
          <th>{{ __("custom.amount") }}({{ currency_symbol }})</th>
          <th>{{ __("custom.note") }}</th>
          <th>{{ __("custom.total") }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in formData.items" :key="index">
          <td>
            <input
              type="text"
              :name="'data[' + index + '][item_name]'"
              v-model="item.item_name"
              maxlength="100"
              placeholder="Item name"
              class="form-control"
            />
          </td>
            <td>
                <input
                    type="number"
                    :name="'data[' + index + '][item_qty]'"
                    v-model="item.item_qty"
                    maxlength="100"
                    placeholder="Item quantity"
                    class="form-control"
                />
            </td>
          <td>
            <input
              min="0"
              type="number"
              :name="'data[' + index + '][amount]'"
              v-model="item.amount"
              placeholder="Amount"
              class="form-control"
            />
          </td>
          <td>
            <input
              type="text"
              :name="'data[' + index + '][note]'"
              v-model="item.note"
              maxlength="100"
              placeholder="Note"
              class="form-control"
            />
          </td>
          <td>
            <button
              @click="deleteItem(index)"
              class="btn btn-sm btn-outline-danger"
            >
              <i class="fa fa-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4">
            <label for="">{{ __("custom.total") }}</label>
          </td>
          <td>
            <p>
              <b>{{ currency_symbol }}{{ $formatNumber(calculateTotal) }}</b>
            </p>
          </td>
        </tr>
      </tfoot>
    </table>

    <button @click="addItem" type="button" class="btn btn-info btn-sm">
      <i class="fa fa-plus"></i> {{ __("custom.add_item") }}
    </button>
  </div>
</template>

<script>
export default {
  props: ["currency_symbol"],
  data() {
    return {
      formData: {
        items: [
          {
            item_name: "",
            amount: 0,
            item_qty: 1,
            note: "",
          },
        ],
      },
    };
  },
  computed: {
    calculateTotal: function () {
      let total = 0;
      this.formData.items.map((item) => {
        total += Number(item.amount);
      });

      return total;
    },
  },
  methods: {
    addItem() {
      this.formData.items.push({
        item_name: "",
        item_qty: 1,
        amount: 0,
        note: "",
      });
    },

    deleteItem(index) {
      this.formData.items.splice(index, 1);
    },
  },
};
</script>
