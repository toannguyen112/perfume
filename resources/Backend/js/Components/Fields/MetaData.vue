<template>
  <div class="space-y-3 card">
    <label class="label">SEO</label>
    <Fieldset
      :model-value="modelValue.slug"
      disabled
      :field="{
        label: 'Slug mặc định',
      }"
    />
    <Fieldset
      :model-value="modelValue.custom_slug"
      :field="{
        label: 'Slug tùy chỉnh',
      }"
      @update:modelValue="
        modelValue.custom_slug = slugify($event);
        $emit('update:modelValue', modelValue);
      "
    />
    <Fieldset
      v-model="modelValue.meta_title"
      :field="{
        rules: 'string|max:170',
        label: 'Meta title',
      }"
    />
    <Fieldset
      v-model="modelValue.meta_description"
      :field="{
        rules: 'string|max:255',
        label: 'Meta description',
        type: 'textarea',
      }"
    />
    <CustomMeta v-model="modelValue.custom_meta" />
  </div>
</template>

<script>
import CustomMeta from "@/Shared/CustomMeta";
export default {
  components: {
    CustomMeta,
  },
  props: ["modelValue"],
  emits: ["update:modelValue"],
  methods: {
    slugify(str, separator = "-") {
      return str
        .toLowerCase()
        .replace(/\t/g, "")
        .replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a")
        .replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e")
        .replace(/ì|í|ị|ỉ|ĩ/g, "i")
        .replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o")
        .replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u")
        .replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y")
        .replace(/đ/g, "d")
        .replace(/\s+/g, separator)
        .replace(/[^A-Za-z0-9_-]/g, "")
        .replace(/-+/g, separator);
    },
  },
};
</script>
