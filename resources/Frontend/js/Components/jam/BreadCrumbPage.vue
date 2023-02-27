<template>
  <div v-if="item" class="pt-4">
    <div class="container">
      <div class="flex items-center breadcrumb">
        <Link
          href="/"
          class="font-semibold text-black transition duration-200 ease-in-out breadcrumb-item caption hover:text-red-500"
          >Trang chủ</Link
        >
        <template v-if="item && item.length > 0">
          <Link
            v-for="(breadcrumb, index) in item"
            :key="index"
            :href="route(breadcrumb.link)"
            class="font-semibold caption"
            :class="
              index < item.length - 1
                ? 'text-black hover:text-red-500 transition duration-200 ease-in-out breadcrumb-item'
                : ' text-gray-500 select-none pointer-events-none'
            "
          >
            {{ breadcrumb.name }}
          </Link>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["item"],
};
</script>
