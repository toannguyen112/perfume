<template>
  <div
    v-if="product.options && product.options.length"
    class="md:space-y-[12px] space-y-[6px]"
  >
    <div v-for="(option, index) in product.options" :key="index">
      <BProductOption
        :option="option"
        :product="product"
        :current-variant="currentVariant"
        :number="index"
        @update-variant="updateVariant($event)"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    product: {
      type: Object,
      default: function () {
        return {};
      },
    },
    currentVariant: {
      type: Object,
      default: function () {
        return {};
      },
    },
  },
  data() {
    return {
      current: "",
    };
  },
  methods: {
    getValue(data) {
      this.$emit("value", data);
    },
    updateVariant(id) {
      this.$emit("update-variant", id);
    },
  },
};
</script>
