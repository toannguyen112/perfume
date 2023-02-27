<template>
  <FormPage :form="form" :rules="rules" name="Sản phẩm">
    <div class="space-y-3 card">
      <Fieldset
        v-model="form.name"
        :field="{
          rules: rules['name'],
          label: 'Tên sản phẩm',
        }"
      />
      <small v-if="form.id">
        <a :href="form.url" target="_blank" class="link">{{ form.url }}</a>
      </small>
      <!-- <Fieldset
        v-model="form.summary"
        :field="{
          rules: rules['summary'],
          label: 'Mô tả',
          type: 'textarea',
        }"
      /> -->
      <Fieldset
        v-model="form.content"
        :field="{
          rules: rules['content'],
          label: 'Thông tin sản phẩm',
          type: 'richtext',
        }"
      />
    </div>
    <div class="card">
      <label class="label">Hình ảnh</label>
      <Fieldset
        v-model="form.images"
        :field="{
          rules: rules['images'],
          type: 'media_upload',
          folder: currentModel(),
          multiple: true,
          perRow: 'grid-cols-8',
        }"
      />
    </div>

    <div class="card">
      <VideoURL v-model="form.video_urls" />
    </div>

    <div class="space-y-3 card">
      <details class="space-y-3">
        <summary class="text-sm font-medium select-none">
          <span>Kích thước</span>
        </summary>
        <Fieldset
          v-model="form.size_guide_table"
          :field="{
            rules: rules['size_guide_table'],
            label: 'Hướng dẫn kích thước',
            type: 'richtext',
            size: 'sm',
          }"
        />
        <Fieldset
          v-model="form.size_guide_tutorial"
          :field="{
            rules: rules['size_guide_tutorial'],
            label: 'Cách đo kích thước',
            type: 'richtext',
            size: 'sm',
          }"
        />
        <Fieldset
          v-model="form.size_guide_image"
          :field="{
            label: 'Hình ảnh minh họa',
            type: 'media_upload',
            folder: currentModel(),
            multiple: false,
            expected: 'url',
          }"
        />
      </details>
    </div>

    <div class="space-y-3 card">
      <details class="space-y-3">
        <summary class="text-sm font-medium select-none">
          <span>Thành phần & cách sử dụng</span>
        </summary>
        <Fieldset
          v-model="form.ingredients"
          :field="{
            rules: rules['ingredients'],
            label: 'Thành phần',
            type: 'richtext',
            size: 'sm',
          }"
        />
        <Fieldset
          v-model="form.use_case_categories"
          :field="{
            type: 'select_source',
            label: 'Cách sử dụng',
            placeholder: 'Cách sử dụng',
            labelBy: 'name',
            keyBy: 'id',
            multiple: true,
            source: {
              model: 'Product\\ProductCategory',
              method: 'getTreeListUseCases',
              only: ['id', 'name'],
            },
          }"
        />
      </details>
    </div>

    <template v-if="route().params.id">
      <ProductOptions
        v-model:options="form.list_option"
        :all-options="allOptions"
      />
      <ProductVariants
        v-model:variants="form.variants"
        :options="form.list_option"
        :all-options="allOptions"
      />
    </template>

    <MetaData v-model="form" />

    <template #right>
      <div class="card">
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
      <div class="space-y-3 card">
        <Fieldset
          v-model="form.categories"
          :field="{
            type: 'select_tree',
            label: 'Danh mục',
            placeholder: 'Danh mục',
            labelBy: 'name',
            keyBy: 'id',
            childrenBy: 'nodes',
            source: {
              model: 'Product\\ProductCategory',
              method: 'getRootProduct',
              only: ['id', 'name', 'nodes'],
            },
          }"
        />
        <Fieldset
          v-model="form.brand_id"
          :field="{
            type: 'select_source',
            label: 'Thương hiệu',
            labelBy: 'name',
            source: {
              model: 'Product\\Brand',
              method: 'get',
            },
            multiple: false,
          }"
        />
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
              method: 'getProduct',
              only: ['id', 'name'],
            },
          }"
        />
      </div>
      <div class="card">
        <Fieldset
          v-model="form.is_sale"
          :field="{
            type: 'checkbox',
            rules: rules['is_sale'],
            label: 'Sale',
          }"
        />
        <Fieldset
          v-model="form.is_hot"
          :field="{
            type: 'checkbox',
            rules: rules['is_hot'],
            label: 'Nổi bật',
          }"
        />
        <Fieldset
          v-model="form.is_new"
          :field="{
            type: 'checkbox',
            rules: rules['is_new'],
            label: 'Mới',
          }"
        />
        <Fieldset
          v-model="form.is_limited"
          :field="{
            type: 'checkbox',
            rules: rules['is_limited'],
            label: 'Giới hạn',
          }"
        />
      </div>
      <div class="card">
        <Fieldset
          v-model="form.sale_categories"
          :field="{
            type: 'select_source',
            label: 'Chương trình sale',
            placeholder: 'Chương trình sale',
            labelBy: 'name',
            keyBy: 'id',
            multiple: true,
            source: {
              model: 'Product\\ProductCategory',
              method: 'getTreeListSales',
              only: ['id', 'name'],
            },
          }"
        />
      </div>
      <div class="card">
        <Fieldset
          v-model="form.views"
          disabled
          :field="{
            rules: rules['views'],
            label: 'Lượt xem',
            type: 'number',
          }"
          :min="0"
        />
      </div>
    </template>
  </FormPage>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
import ProductVariants from "@/Components/Product/Variants";
import ProductOptions from "@/Components/Product/Options";
import VideoURL from "@/Shared/VideoURL";

export default {
  components: {
    ProductOptions,
    ProductVariants,
    VideoURL,
  },
  layout: Authenticated,
  remember: "form",
  props: ["item", "rules"],
  data() {
    return {
      form: {},
      allOptions: [],
    };
  },

  watch: {
    item(value) {
      value.id && this.initForm();
    },
  },

  created() {
    this.initForm();
    this.getAllOption();
  },

  methods: {
    async getAllOption() {
      this.allOptions = await axios
        .post(route("helper.model-data"), {
          model: "Product\\Option",
          method: "getRoot",
        })
        .then((res) => res.data);
    },
    initForm() {
      this.item.custom_meta = this.item.custom_meta ?? [];
      this.item.video_urls = this.item.video_urls ?? [];
      this.item.list_option =
        this.item.list_option && this.item.list_option.length > 0
          ? this.item.list_option
          : [];
      this.item.variants =
        this.item.variants && this.item.variants.length > 0
          ? this.item.variants
          : [];
      this.item.views = Array.isArray(this.item.views) ? 0 : this.item.views;
      this.item.ingredients = Array.isArray(this.item.ingredients) ? null : this.item.ingredients;
      this.form = this.$inertia.form({
        ...this.item,
      });
    },
  },
};
</script>
