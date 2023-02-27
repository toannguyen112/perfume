<template>
  <details open>
    <summary class="text-sm font-medium select-none">
      <span>Thẻ Meta tùy biến</span>
    </summary>
    <div v-for="(item, index) in items" :key="index" class="mt-3 space-y-2 bg-white rounded-md">
      <div class="flex items-center text-sm font-semibold uppercase">Meta {{ index + 1 }}</div>
      <div class="flex space-x-2">
        <Fieldset
          v-model="item.key"
          class="w-full"
          :field="{
            placeholder: 'Key',
          }"
        />
        <Fieldset
          v-model="item.value"
          class="w-full"
          :field="{
            placeholder: 'Value',
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
            key: null,
            value: null,
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
      default: 'Custom meta',
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
