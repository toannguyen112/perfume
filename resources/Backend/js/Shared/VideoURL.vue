<template>
  <details>
    <summary class="text-sm font-medium select-none">
      <span>Video URL</span>
    </summary>
    <div v-for="(item, index) in items" :key="index" class="mt-3 space-y-2 bg-white rounded-md">
      <div class="flex items-center text-sm font-medium">Video URL {{ index + 1 }}</div>
      <div class="flex space-x-2">
        <Fieldset
          v-model="item.static_url"
          class="w-full"
          :field="{
            placeholder: 'Video URL',
          }"
        />
        <div
          class="p-2 ml-auto border rounded cursor-pointer text-gray500 hover:text-gray-700 hover:bg-gray-100"
          @click="confirmRemoveItem(index)"
        >
          <Icon name="trash" />
        </div>
      </div>
    </div>
    <div class="mt-4 mb-6">
      <Button
        label="Thêm "
        variant="white"
        @click="
          items.push({
            url: null,
          })
        "
      />
    </div>
  </details>
</template>

<script>
export default {
  props: {
    modelValue: {
      type: Array,
      default: () => [],
    },
    title: {
      type: String,
      default: 'Video URL',
    },
  },
  emits: ['update:modelValue'],
  data() {
    return {
      items: this.modelValue,
    };
  },
  methods: {
    confirmRemoveItem(index) {
      if (confirm('Bạn có thực sự muốn xoá đối tượng này?')) {
        this.items.splice(index, 1);
      }
    },
  },
};
</script>
