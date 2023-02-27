<template>
  <div v-if="option && option.nodes && option.nodes.length">
    <div class="mb-2">
      <strong class="mb-2 text-black"> {{ option.name }} &nbsp;</strong>
      <span
        v-if="option.display_type === 'IMAGE'"
        class="font-[14px] leading-[120%]"
      >
        {{ currentVariant.title }}</span
      >
    </div>

    <BProductOptionItem
      :option="option"
      :product="product"
      @update-variant="updateVariant($event)"
    />
  </div>
</template>

<script>
export default {
  props: ["option", "number", "product", "currentVariant"],

  data() {
    return {
      current: "",
      key: 0,
    };
  },

  methods: {
    getValue(data) {
      this.current = data.name;
    },
    incrementKey() {
      this.key += 1;
    },
    updateVariant(data) {
      this.$emit("update-variant", data);
    },
  },
};
</script>
