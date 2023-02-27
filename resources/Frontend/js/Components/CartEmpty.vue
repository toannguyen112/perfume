<template>
  <div
    class="
      bg-white
      grid
      lg:grid-cols-8
      grid-cols-12
      xl:gap-x-[32px]
      md:gap-x-[22px]
      xl:py-[48px]
      md:py-[34px]
      py-[24px]
    "
  >
    <div
      class="
        col-span-10 col-start-2
        md:col-span-8 md:col-start-3
        lg:col-start-3
        xl:col-start-3
        lg:col-span-4
      "
    >
      <div class="xl:mb-[40px] md:mb-[28px] mb-[20px]">
        <JIcon name="empty-cart" class="mx-auto" />
      </div>
      <div
        class="
          xl:mb-[20px]
          md:mb-[14px]
          mb-[10px]
          p2
          font-bold
          text-black text-center
        "
      >
        GIỎ HÀNG TRỐNG
      </div>
      <div
        class="
          xl:mb-[20px]
          md:mb-[14px]
          mb-[10px]
          font-medium
          text-gray-700 text-center
        "
      >
        Hiện tại chưa có sản phẩm nào trong giỏ hàng của bạn
      </div>
      <div class="flex justify-center">
        <JButton
          :href="route('home.index')"
          label="MUA SẮM NGAY"
          variant="primary-submit"
        />
      </div>
    </div>
  </div>
</template>