<template>
  <FormPage :form="form" :rules="rules">
    <div class="space-y-3 card">
      <Fieldset
        v-model="form.name"
        :disabled="true"
        :field="{ rules: rules['name'], label: 'Name' }"
      />

      <Fieldset
        v-model="form.value"
        :field="{ rules: rules['value'], label: 'Value', type: 'textarea' }"
      />
    </div>
  </FormPage>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";

export default {
  layout: Authenticated,
  props: ["item", "rules"],
  data() {
    return {
      form: null,
    };
  },

  watch: {
    item() {
      this.initForm();
    },
  },

  created() {
    this.initForm();
  },

  methods: {
    initForm() {
      this.form = this.$inertia.form({ ...this.item });
    },
  },
};
</script>
