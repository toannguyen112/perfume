<template>
  <FormPage :form="form" :rules="rules" name="Slider">
    <div class="space-y-3 card">
      <Fieldset
        v-model="form.title"
        :field="{
          rules: rules['title'],
          label: 'Tiêu đề',
        }"
      />
      <Fieldset
        v-model="form.link"
        :field="{
          rules: rules['link'],
          label: 'Đường dẫn',
        }"
      />
    </div>
    <template #right>
      <div class="card">
        <Fieldset
          v-model="form.status"
          :field="{
              rules: rules['status'],
              type: 'radio',
              label: 'Trạng thái',
              options: [
                  { id: 1, name: 'Hiên thị' },
                  { id: 2, name: 'Ẩn' },
              ],
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.position"
          :field="{
            type: 'select_source',
            label: 'Vị trí',
            placeholder: 'Vị trí',
            source: {
              model: 'Blog\\Slider',
              const: 'POSITION_LIST',
            },
            multiple: false,
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.position_sort"
          :field="{
            rules: rules['position_sort'],
            label: 'Sắp xếp',
            type: 'number',
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
        <hr class="my-4" />
        <label class="label">Hình ảnh mobile</label>
        <Fieldset
          v-model="form.thumbnail_mobile"
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

  selectedItem(item) {
    this.form = {
      ...this.form,
      ...item,
    };
  },

  methods: {
    initForm() {
      this.form = this.$inertia.form({ ...this.item });
    },
  },
};
</script>
