<template>
  <div class="pt-4">
    <div class="container">
      <div class="flex items-center breadcrumb">
        <Link
          :href="route('checkout.cart.index')"
          class="font-semibold breadcrumb-item caption"
          :class="
            checkActive('gio-hang')
              ? ' text-black'
              : 'text-gray-500 pointer-events-none select-none'
          "
          >Giỏ hàng</Link
        >
        <Link
          href="/dat-hang"
          class="font-semibold breadcrumb-item caption"
          :class="
            checkActive('dat-hang')
              ? ' text-black'
              : 'text-gray-500 pointer-events-none select-none'
          "
          >Đặt hàng</Link
        >
        <Link
          href="/dat-hang/dat-hang-thanh-cong"
          class="font-semibold breadcrumb-item caption"
          :class="
            checkActive('dat-hang-thanh-cong')
              ? ' text-black'
              : 'text-gray-500 pointer-events-none select-none'
          "
          >Hoàn thành đơn hàng</Link
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    checkActive(url) {
      const fullPath = this.$page.props.ziggy.url;
      const arrPath = fullPath.split("/");
      return url === arrPath[3];
    },
  },
};
</script>
