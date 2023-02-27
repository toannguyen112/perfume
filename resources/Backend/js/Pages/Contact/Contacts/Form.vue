<template>
  <FormPage :form="form" :rules="[]">
    <div class="space-y-3 card">
      <template v-for="(key, field) in form.data_contact" :key="field">
        <div v-if="typeof form.data_contact[field] === 'object'">
          <div class="font-bold">{{ field }}:</div>
          <Fieldset
            v-for="(subkey, subfield) in form.data_contact[field]"
            :key="subkey"
            :modelValue="form.data_contact[field][subfield]"
            :field="{
              placeholder: '',
              label: subfield,
              readonly: true,
            }"
          />
        </div>
        <div v-else>
          <Fieldset
            :key="field"
            :modelValue="form.data_contact[field]"
            :field="{
              placeholder: '',
              label: field,
              readonly: true,
            }"
          />
        </div>
      </template>

      <Fieldset
        class="w-[200px]"
        v-model="form.formatted_created_at"
        :field="{
          label: 'Ngày gửi',
          readonly: true,
        }"
      />

      <Fieldset
        class="w-[200px]"
        v-model="form.status"
        :field="{
          label: 'Trạng thái',
          type: 'select_single',
          options: statusList,
        }"
      />
    </div>
  </FormPage>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
import Form from "@/Components/Form";
import Fieldset from "@/Components/Fields/Fieldset";
export default {
  components: {
    Form,
    Fieldset,
  },
  layout: Authenticated,
  props: ["item", "rules"],
  watch: {
    item() {
      this.initForm();
    },
  },
  data() {
    return {
      form: null,
      statusList: [],
    };
  },
  created() {
    this.getStatusList();
    this.initForm();
  },
  methods: {
    initForm() {
      this.form = this.$inertia.form({ ...this.item });
    },
    getStatusList() {
      axios
        .post(
          route("helper.model-data", {
            model: "Contact\\Contact",
            method: "getStatusList",
          })
        )
        .then((res) => {
          this.statusList = res.data;
        });
    },
  },
};
</script>
