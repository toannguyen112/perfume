<template>
  <FormPage name="Bình luận" :form="form" :rules="rules">
    <div class="space-y-3 card">
      <Fieldset
        v-model="form.name"
        :field="{
          rules: rules['name'],
          label: 'Tên ',
          readonly: true,
        }"
      />

      <Fieldset
        v-model="form.content"
        :field="{
          rules: rules['content'],
          label: 'Nội dung',
          readonly: true,
        }"
      />
      <Fieldset
        v-model="form.rate_score"
        :field="{
          rules: rules['rate_score'],
          label: 'Đánh giá',
          readonly: true,
        }"
      />
      <Fieldset
        v-model="form.formatted_created_at"
        :field="{
          rules: rules['formatted_created_at'],
          label: 'Ngày bình luận',
          readonly: true,
        }"
      />
    </div>
    <div class="card" v-if="form.images.length > 0">
      <label for="">Hình ảnh</label>
      <div class="flex space-x-3 flex-nowrap">
        <div
          v-for="(file, index) in form.images"
          class="w-[200px] aspect-square"
          :key="index"
        >
          <img
            class="object-contain pointer-events-none w-full h-full"
            :src="staticUrl(file.image_url)"
            loading="lazy"
          />
        </div>
      </div>
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
              { id: 'ACTIVE', name: 'Duyệt' },
              { id: 'INACTIVE', name: 'Chưa duyệt' },
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
  props: ["item", "rules"],
  data() {
    return {
      form: null,
    };
  },

  created() {
    this.initForm();
  },

  watch: {
    item() {
      this.initForm();
    },
  },
  methods: {
    initForm() {
      this.form = this.$inertia.form({ ...this.item });
    },
  },
};
</script>
