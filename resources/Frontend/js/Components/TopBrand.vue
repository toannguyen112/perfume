<template>
  <section
    class="overflow-hidden bg-gray-100 xl:py-[48px] md:py-[34px] py-[24px] top-brand md:mb-12"
    v-if="brands && brands.length > 0"
  >
    <div class="xl:py-[32px] md:py-[22px] py-[16px] bg-white">
      <div class="container">
        <h2 class="xl:mb-[24px] md:mb-[17px] mb-[12px] p2">
          <span class="font-bold text-black">THƯƠNG HIỆU NỔI BẬT</span>
        </h2>
      </div>
      <div class="3xl:container">
        <div class="relative -mx-2">
          <swiper
            class="swiper"
            :slidesPerView="2.5"
            :spaceBetween="16"
            :allowTouchMove="false"
            :loop="true"
            :autoplay="{
              delay: 4000,
              disableOnInteraction: false,
            }"
            :breakpoints="{
              '375': {
                slidesPerView: 3,
                spaceBetween: 16,
              },
              '576': {
                slidesPerView: 3.75,
                spaceBetween: 16,
              },
              '768': {
                slidesPerView: 4.5,
                spaceBetween: 16,
              },
              '1280': {
                slidesPerView: 5.8,
                spaceBetween: 16,
              },
            }"
            :modules="modules"
          >
            <template v-for="(item, index) of brands" :key="index">
              <swiper-slide>
                <Link
                  :href="
                    route('products.index', {
                      slug: item.slug,
                    })
                  "
                  class="block"
                >
                  <JPicture
                    :src="item.thumbnail ? item.thumbnail : '/images/cover.jpg'"
                    class="w-full"
                  />
                </Link>
              </swiper-slide>
            </template>
          </swiper>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import { Autoplay } from "swiper";
import "swiper/css";
export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  props: ["brands"],
  setup() {
    return {
      modules: [Autoplay],
    };
  },
};
</script>

<style lang="scss" scoped>
:deep(.swiper-slide) {
  @apply h-auto flex items-center justify-center;
}
</style>
