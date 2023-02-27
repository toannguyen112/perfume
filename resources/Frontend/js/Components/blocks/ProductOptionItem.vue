<template>
  <div class="flex overflow-x-auto md:flex-wrap md:overflow-x-visible">
    <template v-if="optionValues.length && option.display_type === 'IMAGE'">
      <div
        v-for="(node, idx) in optionValues"
        :key="idx"
        class="flex flex-wrap items-center"
        :class="[{ 'pointer-events-none': !hasCombination(node) }]"
      >
        <div
          class="relative cursor-pointer"
          @click="updateSelectedVariant(node)"
        >
          <div
            class="p-[4px] border border-transparent rounded-full mr-1"
            :class="
              currentOptions && currentOptions.includes(node.slug)
                ? 'border-black'
                : 'hover:border-gray-400'
            "
          >
            <div class="w-[32px] h-[32px] transition relative">
              <JImage
                v-if="node.image_url"
                :src="node.image_url"
                class="object-cover w-[32px] h-[32px] rounded-full"
                :class="[{ 'opacity-50': !hasCombination(node) }]"
                :alt="node.image_url"
              />
              <span
                v-if="!hasCombination(node)"
                class="absolute rotate-45 border border-solid border-black h-[26px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 shadow-line"
              ></span>
            </div>
          </div>
        </div>
      </div>
    </template>

    <template v-else>
      <div
        v-for="(node, idx) in optionValues"
        :key="idx"
        class="mb-2 mr-2 radio-content"
        :class="[{ 'opacity-50 pointer-events-none': !hasCombination(node) }]"
      >
        <div
          class="relative cursor-pointer"
          @click="updateSelectedVariant(node)"
        >
          <div
            class="px-4 font-medium text-black border hover:border-gray-800 flex space-x-[12px] items-center w-max"
            :class="[
              currentOptions && currentOptions.includes(node.slug)
                ? 'border-gray-800'
                : 'border-gray-300',
              option.display_type === 'ALL'
                ? 'h-[44px] py-[4px] rounded-[10px] max-w-[220px]'
                : 'h-10 py-2 rounded-full',
            ]"
          >
            <div
              v-if="option.display_type === 'ALL' && node.image_url"
              class="w-[34px] h-full flex-none flex justify-center"
            >
              <JImage
                :src="node.image_url"
                class="object-cover w-auto h-full"
                :class="[{ 'opacity-50': !hasCombination(node) }]"
                :alt="node.image_url"
              />
            </div>
            <div
              class="description leading-[100%] font-light line-clamp-2 overflow-hidden"
            >
              {{ node.name }}
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    option: {
      type: Object,
      default: function () {
        return {};
      },
    },
    product: {
      type: Object,
      default: function () {
        return {};
      },
    },
  },
  data() {
    return {
      active: null,
      name: "",
      currentOptionsFirst: [],
      isFirstCurrentOptions: false,
    };
  },

  computed: {
    optionValues() {
      return this.option.nodes;
    },
    optionValueSlugs() {
      return this.optionValues.map((x) => x.slug);
    },
    currentOptions() {
      return this.product.variants.find((x) => x.selected).options;
    },
  },

  methods: {
    updateSelectedVariant(node) {
      const variant = this.findVariantByOption(node);
      const variantId = variant.id;

      if (this.product.variant_id === variantId) {
        return;
      }

      this.product.variants.map((x) => {
        x.selected = x.id === variantId;
        return x;
      });

      this.product.variant_id = variantId;

      this.$emit("update-variant", variantId);
    },

    hasCombination(node) {
      return !!this.findVariantByOption(node);
    },

    getPredictOptions(node) {
      const activeIndex = this.currentOptions.findIndex((x) =>
        this.optionValueSlugs.includes(x)
      );

      if (activeIndex < 0) return [];

      let predict = [...this.currentOptions];
      predict[activeIndex] = node.slug;

      return predict;
    },

    arrayContainsAll(needle, haystack) {
      if (!needle.length) return false;
      for (let i = 0; i < needle.length; i++) {
        if (haystack.indexOf(needle[i]) === -1) {
          return false;
        }
      }

      return true;
    },

    findPredictVariantByOption(node) {
      const predictOptions = this.getPredictOptions(node);
      return this.product.variants.find((x) => {
        return this.arrayContainsAll(predictOptions, x.options);
      });
    },
    findVariantHasOption(node) {
      return this.product.variants.find((x) => {
        return x.options.includes(node.slug);
      });
    },
    findVariantByOption(node) {
      return (
        this.findPredictVariantByOption(node) ||
        this.findVariantHasOption(node) ||
        false
      );
    },
  },
};
</script>
<style lang="scss" scoped>
.shadow-line {
  box-shadow: 0px 0px 2px #ffffff;
}
</style>
