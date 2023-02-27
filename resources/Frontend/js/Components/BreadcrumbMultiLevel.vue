<template>
  <div class="py-4">
    <div class="container">
      <div class="flex items-center overflow-x-auto breadcrumb">
        <Link
          href="/"
          class="flex-none font-semibold text-black transition duration-200 ease-in-out breadcrumb-item caption hover:text-red-500"
          >Trang chủ</Link
        >
        <template v-for="(breadcrumb, index) in items">
          <Link
            v-if="index < items.length - 1"
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
          <span
            v-else
            :key="`${index}_no_link`"
            class="flex-none font-semibold text-gray-500 caption"
            >{{ breadcrumb.name }}</span
          >
        </template>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["items"],
};
</script>
