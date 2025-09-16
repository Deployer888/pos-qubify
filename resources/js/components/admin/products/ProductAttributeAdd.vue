<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="row">
        <div class="col-sm-10">
          <table class="table table-bordered">
            <tr v-for="(item, index) in items" :key="index">
              <td width="30%">
                <select
                  @change="getAttributeItem(index, $event)"
                  class="form-control"
                  v-model="item.attribute"
                  :name="'attribute_data[' + index + '][attribute]'"
                >
                  <option value="">Select</option>
                  <option
                    v-for="(item, index) in attributes"
                    :key="index"
                    :value="item.id"
                  >
                    {{ item.name }}
                  </option>
                </select>
              </td>
              <td>
                <div class="ic-select-content">
                  <div class="row">
                    <div
                      class="col-sm-4"
                      v-for="(item2, index2) in item.attribute_items"
                      :key="index2"
                    >
                      <div class="custom-control custom-checkbox">
                        <input
                          :name="
                            'attribute_data[' + index + '][attribute_items][]'
                          "
                          class="form-check-input custom-control-input"
                          type="checkbox"
                          :value="item2.id"
                          :id="'attribute_item_' + index + index2"
                        />
                        <label
                          class="
                            form-check-label
                            custom-control-label
                            checkbox-label
                          "
                          :for="'attribute_item_' + index + index2"
                        >
                          {{ item2.name }}
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td width="5%">
                <button
                  @click="deleteItem(index)"
                  type="button"
                  class="btn btn-sm btn-outline-danger"
                >
                  <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
          </table>
        </div>
        <div class="col-sm-2">
          <button @click="addItem" type="button" class="btn btn-sm btn-info">
            <i class="fa fa-plus"></i> <span>{{ __('custom.add') }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["attributes"],
  data() {
    return {
      items: [
        {
          attribute: "",
          attribute_items: [],
        },
      ],
    };
  },
  methods: {
    getAttributeItem(index, e) {
      // Check already selected or not
      let already_added = this.items.filter(
        (item) => item.attribute == e.target.value
      );

      if (already_added.length > 1) {
        this.deleteItem(index);
        this.$swal("Error!!!", "Already added.", "warning");
        return;
      }

      axios
        .get("/admin/api/attribute-items/" + e.target.value)
        .then((res) => {
          this.items[index].attribute_items = [];
          this.items[index].attribute_items = res.data;
        })
        .catch((err) => {});
    },
    addItem() {
      this.items.push({
        attribute: "",
        attribute_items: [],
      });
    },
    deleteItem(index) {
      this.items.splice(index, 1);
    },
  },
};
</script>
