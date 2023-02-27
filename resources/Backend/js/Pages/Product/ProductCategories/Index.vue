<template>
  <div class="grid grid-cols-12 gap-6">
    <div class="col-span-5 p-3 bg-white rounded shadow">
      <Fieldset
        v-if="categories && categories.length"
        :field="{
          type: 'tree',
          maxLevel: 10,
          expandDefaultLevel: 10,
          labelBy: 'name',
          badgeBy: 'products_count',
          childrenBy: 'nodes',
          options: categories,
          draggable: false,
        }"
      />
      <hr class="my-8" />
      <div>
        <Button
          label="Thêm mới"
          size="sm"
          :href="route(`sidebar.product-categories.index`)"
        />
      </div>
    </div>
    <div class="col-span-7">
      <FlashMessages />
      <Form :form="form" :rules="rules">
        <div class="space-y-3 card">
          <Fieldset
            v-model="form.status"
            :field="{
              rules: rules['status'],
              type: 'radio',
              label: 'Trạng thái',
              options: [
                { id: 1, name: 'Hiển thị' },
                { id: 2, name: 'Ẩn' },
              ],
            }"
          />
        </div>
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
          <small v-if="form.id">
            <a :href="form.url" target="_blank" class="link">{{ form.url }}</a>
          </small>
          <Fieldset
            v-model="form.icon"
            :field="{
              rules: rules['icon'],
              label: 'Icon',
              type: 'textarea',
              size: 'md',
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
        <!-- <div class="card">
          <Fieldset
            v-model="form.brands"
            :field="{
              type: 'select_source',
              label: 'Thương hiệu',
              labelBy: 'name',
              source: {
                model: 'Product\\Brand',
                method: 'get',
              },
              keyBy: 'id',
            }"
          />
        </div> -->
        <!-- <div class="space-y-3 card">
          <Fieldset
            v-model="form.options"
            :field="{
              type: 'select_source',
              label: 'Thuộc tính lọc',
              labelBy: 'name',
              source: {
                model: 'Product\\Option',
                method: 'getRootOnly',
                only: ['id', 'name'],
              },
              keyBy: 'id',
            }"
          />
        </div> -->
        <div class="card">
          <Fieldset
            v-model="form.tags"
            :field="{
              type: 'select_source',
              label: 'Tags',
              placeholder: 'Tags',
              labelBy: 'name',
              keyBy: 'id',
              multiple: true,
              source: {
                model: 'Tag',
                method: 'getCategories',
                only: ['id', 'name'],
              },
            }"
          />
        </div>
        <div class="card">
          <Fieldset
            v-model="form.position"
            :field="{
              rules: rules['position'],
              label: 'Số thứ tự',
            }"
          />
        </div>
        <div class="card">
          <Fieldset
            v-model="form.is_hot"
            :field="{
              type: 'checkbox',
              rules: rules['is_hot'],
              label: 'Danh mục nổi bật',
            }"
          />
          <Fieldset
            v-model="form.is_home"
            :field="{
              type: 'checkbox',
              rules: rules['is_home'],
              label: 'Hiển thị ở trang chủ',
            }"
          />
          <Fieldset
            v-model="form.is_menu"
            :field="{
              type: 'checkbox',
              rules: rules['is_home'],
              label: 'Hiển thị trên Menu',
            }"
          />
        </div>
        <div v-if="form.is_home == 1" class="space-y-3 card">
          <label class="label">Banner bên trái</label>
          <Fieldset
            v-model="form.thumbnail_left"
            :field="{
              type: 'media_upload',
              folder: currentModel(),
            }"
          />
          <Fieldset
            v-model="form.link_header_left"
            :field="{
              rules: rules[link_header_left],
              label: 'Link banner trái',
            }"
          />
          <hr class="my-4" />
          <label class="label">Banner ở giữa</label>
          <Fieldset
            v-model="form.thumbnail_center"
            :field="{
              type: 'media_upload',
              folder: currentModel(),
            }"
          />
          <Fieldset
            v-model="form.link_header_center"
            :field="{
              rules: rules[link_header_center],
              label: 'Link banner giữa',
            }"
          />
          <hr class="my-4" />
          <label class="label">Banner bên phải</label>
          <Fieldset
            v-model="form.thumbnail_right"
            :field="{
              type: 'media_upload',
              folder: currentModel(),
            }"
          />
          <Fieldset
            v-model="form.link_header_right"
            :field="{
              rules: rules[link_header_right],
              label: 'Link banner phải',
            }"
          />
          <hr class="my-4" />
        </div>
        <MetaData v-model="form" />
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
      form: {},
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
          method: "getRootProduct",
        })
        .then((res) => {
          this.categories = res.data;

          if (this.route().params.id) {
            const defaultItem = res.data.find(
              (x) => x.id == this.route().params.id
            );
            defaultItem && this.selectedItem(defaultItem);
          }
        });
    },
    store() {
      this.form.parent_id =
        this.form.parent_id == null ? 0 : this.form.parent_id;
      this.form.type = this.item.type ?? "PRODUCT";
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
        brands: [],
        tags_category: [],
        ...this.form,
        type: "PRODUCT",
        ...item,
      };
    },
    initForm() {
      this.form = this.$inertia.form({
        options: [],
        tags_category: [],
        brands: [],
        type: "PRODUCT",
        ...this.item,
      });
    },
  },
};
</script>
