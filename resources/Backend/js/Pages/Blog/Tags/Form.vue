<template>
  <FormPage :form="form" :rules="rules" name="Tag">
    <div class="space-y-3 card">
      <Fieldset
        v-model="form.name"
        :field="{
          rules: rules['name'],
          label: 'Tên',
        }"
      />
      <Fieldset
        v-model="form.color"
        :field="{
          rules: rules['color'],
          label: 'Mã màu',
        }"
      />
      <Fieldset
        v-model="form.icon"
        :field="{
          rules: rules['icon'],
          label: 'Icon',
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
  selectedItem(item) {
    this.form = {
      ...this.form
    };
  },
  methods: {
    initForm() {
      this.form = this.$inertia.form({
        ...this.item,
        type: "POST"
      });
    },
  },
};
</script>
