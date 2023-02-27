<template>
  <div>
    <h1 class="mt-4 mb-4 font-bold text-black p2 lg:mt-0">
      {{ category.name }}
    </h1>
    <div
      v-if="categories && categories.length > 0"
      class="border-b border-gray-300"
    >
      <h3 class="mb-2 font-bold text-gray-700">DANH MỤC</h3>
      <div class="mb-4 category-menus">
        <div v-for="(item, index) of categories" :key="index" class="mb-1">
          <Link
            :href="
              route('products.index', {
                slug:
                  category.page_type === 'BRAND'
                    ? `${slug}/${item.slug}`
                    : item.slug,
              })
            "
            class="font-medium text-gray-600 transition-colors duration-200 ease-in-out caption hover:text-red-400"
            :class="is_selected == item.id ? 'text-red-400' : ''"
            @click="is_selected = item.id"
          >
            {{ item.name }}
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["categories", "category"],
  data() {
    return {
      is_selected: null,
    };
  },

  computed: {
    slug() {
      return this.route().params.slug;
    },
  },
};
</script>
<style lang="scss" scoped>
.nuxt-link-active {
  @apply text-[#00A8E2] border-b border-b-[#00A8E2];
}
</style>
