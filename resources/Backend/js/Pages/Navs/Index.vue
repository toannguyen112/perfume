<template>
  <div class="grid grid-cols-12 gap-6">
    <div class="col-span-5 space-y-6">
      <div class="card">
        <label class="label">Menu</label>

        <Fieldset
          v-if="navs.length"
          :field="{
            type: 'tree',
            maxLevel: 4,
            expandDefaultLevel: 2,
            labelBy: 'title',
            badgeBy: 'type',
            childrenBy: 'nodes',
            options: navs,
            draggable: false,
          }"
        />
      </div>
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
                model: 'Nav',
                method: 'getMenuPrimary',
                only: ['id', 'title', 'parent_id'],
              },
              emptyLabel: '-- Danh mục gốc --',
              keyBy: 'id',
              labelBy: 'title',
              multiple: false,
            }"
          />

          <Fieldset
            v-if="form && form.id"
            v-model="form.id"
            disabled
            :field="{
              label: 'ID',
            }"
          />

          <Fieldset
            v-model="form.type"
            :field="{
              type: 'select_source',
              label: 'Loại',
              placeholder: 'Loại',
              source: {
                model: 'Nav',
                const: 'TYPE_LIST',
              },
              multiple: false,
            }"
          />

          <Fieldset
            v-model="form.title"
            :field="{
              rules: rules['title'],
              label: 'Tên hiển thị',
            }"
          />

          <small v-if="form.id">
            <a :href="form.url" target="_blank" class="link">{{ form.url }}</a>
          </small>
          <Fieldset
            v-if="form.type == 'PRODUCT'"
            v-model="form.type_id"
            :field="{
              label: 'Sản phẩm',
              type: 'select_source',
              source: {
                model: 'Product\\Product',
                method: 'lazySearch',
                only: ['id', 'name'],
              },
              keyBy: 'id',
              labelBy: 'name',
              multiple: false,
            }"
          />
          <Fieldset
            v-else-if="form.type == 'PRODUCT_CATEGORY'"
            v-model="form.type_id"
            :field="{
              label: 'Danh mục',
              type: 'select_source',
              source: {
                model: 'Product\\ProductCategory',
                method: 'lazySearch',
                only: ['id', 'name'],
              },
              keyBy: 'id',
              labelBy: 'name',
              multiple: false,
            }"
          />

          <Fieldset
            v-else-if="form.type == 'BRAND'"
            v-model="form.type_id"
            :field="{
              label: 'Thương hiệu',
              type: 'select_source',
              source: {
                model: 'Product\\Brand',
                method: 'lazySearch',
                only: ['id', 'name'],
              },
              keyBy: 'id',
              labelBy: 'name',
              multiple: false,
            }"
          />

          <Fieldset
            v-else-if="form.type == 'BLOG'"
            v-model="form.type_id"
            :field="{
              label: 'Blog',
              type: 'select_source',
              source: {
                model: 'Blog\\Post',
                method: 'lazySearch',
                only: ['id', 'title'],
              },
              keyBy: 'id',
              labelBy: 'title',
              multiple: false,
            }"
          />

          <Fieldset
            v-else-if="form.type == 'BLOG_CATEGORY'"
            v-model="form.type_id"
            :field="{
              label: 'Danh mục bài viết',
              type: 'select_source',
              source: {
                model: 'Blog\\PostCategory',
                method: 'lazySearch',
                only: ['id', 'title'],
              },
              keyBy: 'id',
              labelBy: 'title',
              multiple: false,
            }"
          />

          <template v-else-if="form.type == 'LINK'">
            <Fieldset
              v-model="form.link"
              :field="{
                rules: rules['link'],
                label: 'URL tùy chỉnh',
              }"
            />
            <Fieldset
              v-model="form.target"
              :field="{
                type: 'select_single',
                rules: rules['target'],
                label: 'Mục tiêu',
                options: [
                  { id: '_self', name: 'Mở tab hiện tại' },
                  { id: '_target', name: 'Mở tab mới' },
                ],
              }"
            />
          </template>

          <template v-if="form.parent_id == 0" class="space-y-3 card">
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
              v-model="form.left_banner_url"
              :field="{
                label: 'Banner bên trái',
                type: 'media_upload',
                folder: currentModel(),
                multiple: false,
                expected: 'url',
              }"
            />
            <Fieldset
              v-model="form.link_header_left"
              :field="{
                rules: rules['link_header_left'],
                label: 'Link banner trái',
              }"
            />
            <Fieldset
              v-model="form.right_banner_url"
              :field="{
                label: 'Banner ở giữa',
                type: 'media_upload',
                folder: currentModel(),
                multiple: false,
                expected: 'url',
              }"
            />
            <Fieldset
              v-model="form.link_header_center"
              :field="{
                rules: rules['link_header_center'],
                label: 'Link banner giữa',
              }"
            />
            <Fieldset
              v-model="form.center_banner_url"
              :field="{
                label: 'Banner bên phải',
                type: 'media_upload',
                folder: currentModel(),
                multiple: false,
                expected: 'url',
              }"
            />
            <Fieldset
              v-model="form.link_header_right"
              :field="{
                rules: rules['link_header_right'],
                label: 'Link banner phải',
              }"
            />
          </template>

          <Fieldset
            v-model="form.position"
            class="w-[200px]"
            :field="{
              rules: rules['position'],
              label: 'Thứ tự',
            }"
          />
        </div>
      </Form>

      <div class="flex mt-6">
        <Button label="Lưu" @click="store()" />
        <Button
          label="Thêm mới"
          class="ml-3"
          variant="white"
          @click="initForm()"
        />
        <Button
          variant="link-danger"
          label="Xóa"
          class="ml-auto"
          @click="destroy()"
        />
      </div>
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
      navs: [],
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
        .post(this.route(`helper.model-data`), {
          model: "Nav",
          method: "getRoot",
        })
        .then((res) => {
          this.navs = res.data;

          if (this.route().params.id) {
            const defaultItem = res.data.find(
              (x) => x.id == this.route().params.id
            );
            defaultItem && this.selectedItem(defaultItem);
          }
        });
    },
    store() {
      const url = this.route(`sidebar.${this.currentModel()}.store`, {
        id: this.form.id,
      });
      this.form.post(url, {
        onSuccess: () => this.getData(),
      });
    },
    destroy() {
      if (confirm("Bạn chắc chắn muốn xoá đối tượng này?")) {
        const url = this.route(`sidebar.${this.currentModel()}.destroy`, {
          id: this.form.id,
        });
        this.$inertia.post(url, {}, { onSuccess: () => this.getData() });
      }
    },
    selectedItem(item) {
      this.form = {
        ...this.form,
        ...item,
      };
    },
    initForm() {
      this.form = this.$inertia.form({
        ...this.item,
        type: this.item.type ?? "PRODUCT_CATEGORY",
      });
    },
  },
};
</script>
