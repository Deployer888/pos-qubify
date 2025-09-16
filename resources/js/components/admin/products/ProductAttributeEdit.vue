<template>
  <div class="row">
    <div class="col-sm-12">
      <table class="table table-bordered">
        <tr v-for="(item, index) in items" :key="index">
          <td width="40%">
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
          <td class="p-4">
            <div class="col-sm-12">
              <div class="row">
                <div
                  class="col-sm-4 custom-control custom-checkbox"
                  v-for="(item2, index2) in item.attribute_items"
                  :key="index2"
                >
                  <input v-if="isOldSelected(item2.id, item.old_items)" disabled
                    :name="'attribute_data[' + index + '][attribute_items][]'"
                    class="form-check-input custom-control-input"
                    type="checkbox"
                    :value="item2.id"
                    :id="'attribute_item_' + index + index2"
                    :checked="isOldSelected(item2.id, item.old_items)"
                  />
                    <input v-else
                           :name="'attribute_data[' + index + '][attribute_items][]'"
                           class="form-check-input custom-control-input"
                           type="checkbox"
                           :value="item2.id"
                           :id="'attribute_item_' + index + index2"
                           :checked="isOldSelected(item2.id, item.old_items)"
                    />
                  <label
                    class="form-check-label custom-control-label checkbox-label"
                    :for="'attribute_item_' + index + index2"
                  >
                    {{ item2.name }}
                  </label>
                </div>
              </div>
            </div>
          </td>
          <td width="5%">
              <button v-if="item.old_items.length > 0"
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
      </table>
      <button @click="addItem" type="button" class="btn btn-sm btn-info">
        <i class="fa fa-plus"></i> {{ __('custom.add_attribute') }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["attributes", "product", "old_attribute_data"],
  data() {
    return {
      old_attribute_id: [],
      items: [],
    };
  },
  mounted() {
    Object.keys(this.old_attribute_data).map((key) => {
      axios
        .get("/admin/api/attribute-items/" + key)
        .then((res) => {
          this.items.push({
            attribute: key,
            old_items: this.old_attribute_data[key],
            attribute_items: res.data,
          });
        })
        .catch((err) => {});
    });
  },
  methods: {
    isOldSelected(id, dataArray) {
      return dataArray.includes(id);
    },
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
        old_items: [],
      });
    },
    deleteItem(index) {
      this.items.splice(index, 1);
    },
  },
};
</script>
