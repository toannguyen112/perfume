<template>
  <FormPage :form="form" :rules="rules" name="Thương hiệu">
    <div class="space-y-3 card">
      <Fieldset
        v-model="form.name"
        :field="{
          rules: rules['name'],
          label: 'Tên',
        }"
      />
      <small v-if="form.id">
        <a :href="form.url" target="_blank" class="link">{{ form.url }}</a>
      </small>
      <Fieldset
        v-model="form.description"
        :field="{
          rules: rules['description'],
          label: 'Mô tả',
          type: 'textarea'
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
                    { id: 1, name: 'Hiên thị' },
                    { id: 2, name: 'Ẩn' },
                ],
            }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.is_hot"
          :field="{
            type: 'checkbox',
            rules: rules['is_hot'],
            label: 'Thương hiệu nổi bật',
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
  selectedItem(item) {
    this.form = {
      ...this.form
    };
  },
  methods: {
    initForm() {
      this.item.custom_meta = this.item.custom_meta ?? [];
      this.form = this.$inertia.form({
        ...this.item
      });
    },
  },
};
</script>
