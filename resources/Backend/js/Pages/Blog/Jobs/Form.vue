<template>
  <FormPage :form="form" :rules="rules" name="Công việc">
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
        v-model="form.description"
        :field="{
          rules: rules['description'],
          label: 'Mô tả công việc',
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
          v-model="form.work_form"
          :field="{
            rules: rules['work_form'],
            label: 'Hình thức công việc',
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.position"
          :field="{
            rules: rules['position'],
            label: 'Vị trí',
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.address"
          :field="{
            rules: rules['address'],
            label: 'Địa điểm',
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.posted_at"
          :field="{
            rules: rules['posted_at'],
            label: 'Ngày đăng',
            type: 'date',
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.expected_time"
          :field="{
            rules: rules['expected_time'],
            label: 'Hạn nộp',
            type: 'date',
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.count"
          :field="{
            rules: rules['count'],
            label: 'Số lượng',
            type: 'number',
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.work_time"
          :field="{
            rules: rules['work_time'],
            label: 'Giờ làm việc',
          }"
        />
        <hr class="my-4" />
        <Fieldset
          v-model="form.salary"
          :field="{
            rules: rules['salary'],
            label: 'Mức lương',
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
      this.form = this.$inertia.form({ ...this.item });
    },
  },
};
</script>
