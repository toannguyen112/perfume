<template>
  <div v-if="item" class="pt-4 pb-2 md:py-4">
    <div class="container">
      <div class="flex items-center overflow-x-auto breadcrumb">
        <Link
          href="/"
          class="flex-none font-semibold text-black transition duration-200 ease-in-out breadcrumb-item caption hover:text-red-500"
          >Trang chủ</Link
        >
        <template v-if="item.breadcrumbs && item.breadcrumbs.length > 0">
          <Link
            v-for="(breadcrumb, index) in item.breadcrumbs"
            :key="index"
            :href="
              route('products.index', {
                slug: breadcrumb.slug,
              })
            "
            class="flex-none font-semibold text-black transition duration-200 ease-in-out breadcrumb-item caption hover:text-red-500"
          >
            {{ breadcrumb.name }}
          </Link>
        </template>
        <!-- <span class="font-semibold text-gray-500 caption">{{ item.name }}</span> -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["item"],
};
</script>
