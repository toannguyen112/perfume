<template>
  <div class="pb-4">
    <Link
      :href="
        route('product.show', { productSlug: item.slug, productId: item.id })
      "
      class="card group"
    >
      <JPicture
        :src="item.image_url"
        class="object-cover w-full h-full rounded-lg"
      />
    </Link>
  </div>
</template>

<script>
export default {
  props: ["item"],
  data() {
    return {};
  },
};
</script>

<style lang="scss" scoped>
.card {
  @apply block h-full xl:min-h-[424px] hover:opacity-70 ease-in-out duration-200 transition-opacity;
}
</style>
