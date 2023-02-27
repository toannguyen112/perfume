<template>
  <Collapse class="py-4 border-b border-gray-300">
    <template v-slot:collapse-trigger>
      <h3
        class="relative font-bold text-black transition-colors duration-200 ease-in-out hover:text-red-400 group"
      >
        <span class="select-none">Đánh giá</span>
        <JIcon
          name="arrow-down"
          class="text-black transition-colors duration-200 ease-in-out collapse-icon group-hover:text-red-400"
        />
      </h3>
    </template>
    <template v-slot:collapse-content>
      <div class="mt-3 space-y-2">
        <div
          v-for="(item, index) of ratings"
          :key="index"
          class="inline-flex items-center p-3 space-x-3 transition rounded-full hover:bg-gray-200"
          :class="{ 'bg-gray-200': rate_score === item.rate_value }"
          @click="applyRating(item.rate_value)"
        >
          <div class="flex items-center space-x-2 ratings">
            <template v-for="idx in 5" :key="idx">
              <JIcon
                name="star-active"
                class="star star-active"
                v-if="idx <= item.rate_value"
              />
              <JIcon name="star" class="star star-grey" v-else />
            </template>
          </div>
          <span class="font-medium text-gray-800 select-none caption">{{
            item.text
          }}</span>
        </div>
      </div>
    </template>
  </Collapse>
</template>

<script>
import Collapse from "../jam/Collapse.vue";
export default {
  components: {
    Collapse,
  },
  props: ["ratings"],
  data() {
    return {
      rate_score: this.route().params.rate_score
        ? this.route().params.rate_score
        : null,
    };
  },

  watch: {
    "$page.url"() {
      this.rate_score = this.route().params.rate_score
        ? this.route().params.rate_score
        : null;
    },
  },

  methods: {
    applyRating(rate_value) {
      let params = {};
      if (this.rate_score !== rate_value) {
        this.rate_score = rate_value;
      } else {
        this.rate_score = null;
      }

      params = { rate_score: this.rate_score };

      this.pushToUrl(params);
    },

    pushToUrl(params) {
      const routeName = this.route().current();

      let allParams = { ...this.route().params, ...params };

      this.$inertia.get(
        this.route(routeName, { ...allParams }),
        {},
        {
          replace: true,
          preserveScroll: true,
          // preserveState: true,
        }
      );
    },
  },
};
</script>
