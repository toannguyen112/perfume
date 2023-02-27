<template>
  <FormPage :form="form" :rules="rules" name="Cách sử dụng">
    <div class="space-y-3 card">
      <Fieldset
        v-model="form.name"
        :field="{
          rules: rules['name'],
          label: 'Tên',
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
      this.item.views && this.item.views.length > 0 ? this.item.views : 0;
      this.form = this.$inertia.form({
        ...this.item,
        type: "USE_CASE",
        parent_id: 0,
      });
    },
  },
};
</script>
