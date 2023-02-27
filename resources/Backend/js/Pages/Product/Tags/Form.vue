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
      <Fieldset
        v-model="form.type"
        :field="{
          type: 'select_source',
          label: 'Thể loại',
          placeholder: 'Thể loại',
          source: {
            model: 'Tag',
            const: 'TYPE_LIST',
          },
          multiple: false,
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
      this.form = this.$inertia.form({
        ...this.item,
      });
    },
  },
};
</script>
