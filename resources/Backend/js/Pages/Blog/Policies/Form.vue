<template>
  <FormPage :form="form" :rules="rules" name="Chính sách">
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
      form: null,
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
      this.form = this.$inertia.form({ ...this.item, type: "POLICY" });
    },
  },
};
</script>
