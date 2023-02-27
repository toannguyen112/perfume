<template>
  <FormPage :form="form" :rules="rules" name="Bài viết">
    <div class="space-y-3 card">
      <Fieldset
        v-model="form.title"
        :field="{
          rules: rules['title'],
          label: 'Tiêu đề',
        }"
      />
      <small v-if="form.id">
        <a :href="form.url" target="_blank" class="link">{{ form.url }}</a>
      </small>
      <Fieldset
        v-model="form.author"
        :field="{
          rules: rules['author'],
          label: 'Tác giả',
        }"
      />
      <Fieldset
        v-model="form.description"
        :field="{
          rules: rules['description'],
          label: 'Mô tả',
          type: 'textarea',
        }"
      />
      <Fieldset
        v-model="form.content"
        :field="{
          rules: rules['content'],
          label: 'Nội dung',
          type: 'richtext',
        }"
      />
    </div>
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
              { id: 2, name: 'Ẩn' },
            ],
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.is_home"
          :field="{
            type: 'checkbox',
            rules: rules['is_home'],
            label: 'Hiển thị ở trang chủ',
          }"
        />
        <Fieldset
          v-model="form.is_featured"
          :field="{
            type: 'checkbox',
            rules: rules['is_featured'],
            label: 'Bài viết nổi bật',
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.post_related_ids"
          :field="{
            type: 'select_source',
            label: 'Bài viết liên quan',
            placeholder: 'Bài viết liên quan',
            labelBy: 'title',
            keyBy: 'id',
            multiple: 'true',
            source: {
              model: 'Blog\\Post',
              method: 'getPost',
              only: ['id', 'title'],
            },
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.categories"
          :field="{
            type: 'select_source',
            label: 'Danh mục',
            placeholder: 'Danh mục',
            labelBy: 'title',
            keyBy: 'id',
            multiple: 'true',
            source: {
              model: 'Blog\\PostCategory',
              method: 'get',
              only: ['id', 'title'],
            },
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.tags"
          :field="{
            type: 'select_source',
            label: 'Tags',
            placeholder: 'Tags',
            labelBy: 'name',
            keyBy: 'id',
            multiple: 'true',
            source: {
              model: 'Tag',
              method: 'getPost',
              only: ['id', 'name'],
            },
          }"
        />
        <hr class="my-4" />
        <label class="label">Hình ảnh banner</label>
        <Fieldset
          v-model="form.banner_thumbnail"
          :field="{
            type: 'media_upload',
            folder: currentModel(),
          }"
        />
        <hr class="my-4" />
        <label class="label">Hình ảnh</label>
        <Fieldset
          v-model="form.thumbnail"
          :field="{
            type: 'media_upload',
            folder: currentModel(),
          }"
        />
      </div>
    </template>
  </FormPage>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";

export default {
  layout: Authenticated,
  remember: "form",
  props: ["item", "rules"],
  data() {
    return {
      form: {},
    };
  },

  watch: {
    item(value) {
      value.id && this.initForm();
    },
  },

  created() {
    this.initForm();
  },

  methods: {
    initForm() {
      this.item.custom_meta = this.item.custom_meta ?? [];
      this.form = this.$inertia.form({ ...this.item, type: "POST" });
    },
  },
};
</script>
