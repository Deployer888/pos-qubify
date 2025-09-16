<template>
  <div class="form-group col-md-12 col-lg-8">
    <label for="">{{ __("custom.attribute_items") }}</label>
    <div class="table-responsive">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>{{ __("custom.item_name") }}</th>
            <th>{{ __("custom.color") }}</th>
            <th>{{ __("custom.image") }}</th>
            <th>{{ __("custom.action") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in formData.items" :key="index">
            <td width="40%">
              <input
                type="text"
                :name="'item_data[' + index + '][name]'"
                v-model="item.name"
                maxlength="100"
                placeholder="Item name"
                class="form-control"
              />
            </td>
            <td width="20%">
              <input
                type="color"
                :name="'item_data[' + index + '][color]'"
                v-model="item.color"
                class="form-control"
              />
            </td>
            <td>
              <img
                v-if="item.image"
                class="img-32 float-left"
                :src="item.file_url"
                alt=""
              />
              <input
                type="file"
                :name="'item_data[' + index + '][image]'"
                class="form-control"
              />
              <input
                type="hidden"
                :name="'item_data[' + index + '][old_image]'"
                v-model="item.image"
                placeholder="Note"
                class="form-control"
              />
            </td>
            <td>
              <button
                type="button"
                @click="deleteItem(index)"
                class="btn btn-sm btn-outline-danger"
              >
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <button @click="addItem" type="button" class="btn btn-info btn-sm">
      <i class="fa fa-plus"></i> {{ __("custom.add_item") }}
    </button>
  </div>
</template>

<script>
export default {
  props: ["items"],
  data() {
    return {
      formData: {
        items: [],
      },
    };
  },
  computed: {},
  mounted() {
    this.items.map((item) => {
      this.formData.items.push({
        name: item.name,
        color: item.color,
        image: item.image,
        file_url: item.file_url,
      });
    });
  },
  methods: {
    addItem() {
      this.formData.items.push({
        name: "",
        color: "#000",
      });
    },

    deleteItem(index) {
      this.formData.items.splice(index, 1);
    },
  },
};
</script>
