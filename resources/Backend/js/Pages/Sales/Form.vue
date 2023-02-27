<template>
  <FormPage :form="form" :rules="rules" name="Chương trình sale">
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
        v-model="form.thumbnail"
        :field="{
          type: 'media_upload',
          folder: currentModel(),
          label: 'Hình ảnh',
        }"
      />
      <Fieldset
        v-model="form.discount"
        :field="{
          rules: rules['discount'],
          label: 'Giảm giá',
        }"
      />
      <div class="flex space-x-12">
        <Fieldset
          v-model="form.start_day"
          :field="{
            rules: rules['start_day'],
            label: 'Ngày bắt đầu',
            type: 'date',
          }"
        />
        <Fieldset
          v-model="form.end_day"
          :field="{
            rules: rules['end_day'],
            label: 'Ngày kết thúc',
            type: 'date',
          }"
        />
      </div>
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
    <MetaData v-model="form" />
  </FormPage>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
const empty = {
  name: null,
  start_day: null,
  end_day: null,
};
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
    };
  },
  methods: {
    initForm() {
      this.item.custom_meta = this.item.custom_meta ?? [];
      this.item.views =
        this.item.views && this.item.views.length > 0 ? this.item.views : 0;
      this.form = this.$inertia.form({
        ...this.item,
        type: "SALE",
        parent_id: 0,
      });
    },
  },
};
</script>
