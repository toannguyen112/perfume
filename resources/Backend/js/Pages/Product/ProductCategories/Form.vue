<template>
  <div class="grid grid-cols-12 gap-6">
    <div class="col-span-5 p-3 bg-white rounded shadow">
      <Fieldset
        v-if="categories && categories.length"
        :field="{
          type: 'tree',
          maxLevel: 4,
          expandDefaultLevel: 2,
          labelBy: 'name',
          childrenBy: 'nodes',
          options: categories,
          draggable: false,
        }"
      />
      <hr class="my-8" />
      <Button label="Thêm mới" size="sm" @click="initForm()" />
    </div>
    <div class="col-span-7">
      <FlashMessages />
      <Form :form="form" :rules="rules">
        <div class="space-y-3 card">
          <Fieldset
            v-model="form.parent_id"
            :field="{
              label: 'Menu cha',
              type: 'select_source',
              source: {
                model: 'Product\\ProductCategory',
                method: 'get',
                only: ['id', 'name'],
              },
              emptyLabel: '-- Danh mục gốc --',
              keyBy: 'id',
              labelBy: 'name',
              multiple: false,
            }"
          />
          <Fieldset
            v-if="form.id"
            v-model="form.id"
            disabled
            :field="{
              rules: rules['id'],
              label: 'ID',
            }"
          />
          <Fieldset
            v-model="form.name"
            :field="{
              rules: rules['name'],
              label: 'Tên danh mục',
            }"
          />
          <Fieldset
            v-model="form.icon"
            :field="{
              rules: rules['icon'],
              label: 'Icon',
            }"
          />
          <Fieldset
            v-model="form.content"
            :field="{
              rules: rules['content'],
              label: 'Mô tả',
              type: 'richtext',
            }"
          />
        </div>

        <div class="space-y-3 card">
          <Fieldset
            v-model="form.position"
            :field="{
              rules: rules['position'],
              label: 'Số thứ tự',
            }"
          />
          <Fieldset
            v-model="form.views"
            :field="{
              rules: rules['views'],
              label: 'Lượt xem',
            }"
          />
        </div>

        <MetaData v-model="form" />

        <div class="space-y-3 card">
          <Fieldset
            v-model="form.status"
            :field="{
              rules: rules['status'],
              type: 'radio',
              label: 'Trạng thái',
              options: [
                { id: 1, name: 'Hiển thị' },
                { id: 0, name: 'Ẩn' },
              ],
            }"
          />
        </div>

        <div class="flex mt-6">
          <Button label="Lưu" @click="store()" />
          <Button
            v-if="form.id"
            variant="link-danger"
            label="Xóa"
            class="ml-auto"
            @click="destroy()"
          />
        </div>
      </Form>
    </div>
  </div>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
import FlashMessages from "@/Components/FlashMessages";

export default {
  components: {
    FlashMessages,
  },
  layout: Authenticated,
  props: ["item", "rules"],

  data() {
    return {
      form: null,
      categories: [],
    };
  },

  watch: {
    item() {
      this.initForm();
    },
  },

  created() {
    this.getData();
    this.initForm();
  },

  mounted() {
    this.$bus.on("treeSelectedItem", (item) => {
      this.selectedItem(item);
    });
  },

  beforeUnmount() {
    this.$bus.off("treeSelectedItem");
  },

  methods: {
    getData() {
      axios
        .post(route(`helper.model-data`), {
          model: "Product\\ProductCategory",
          method: "getRoot",
        })
        .then((res) => {
          this.categories = res.data;
        });
    },
    store() {
      this.form.parent_id =
        this.form.parent_id == null ? 0 : this.form.parent_id;
      const url = route(`sidebar.${this.currentModel()}.store`, {
        id: this.form.id,
      });
      this.form.post(url, { onSuccess: () => this.getData() });
    },
    destroy() {
      if (confirm("Bạn chắc chắn muốn xoá đối tượng này?")) {
        const url = route(`sidebar.${this.currentModel()}.destroy`, {
          id: this.form.id,
        });
        this.$inertia.post(url, {}, { onSuccess: () => this.getData() });
      }
    },
    selectedItem(item) {
      this.form = {
        options: [],
        ...this.form,
        ...item,
      };
    },
    initForm() {
      this.item.custom_meta = this.item.custom_meta ?? [];
      this.form = this.$inertia.form({
        options: [],
        ...this.item,
      });
    },
  },
};
</script>
