<template>
  <section class="xl:mb-[48px] md:mb-[34px] mb-[24px]">
    <div class="container">
      <div class="relative">
        <div class="slider">
          <div
            class="absolute z-20 hidden -translate-y-1/2 left-3 top-1/2 xl:block"
          >
            <button class="slide-btn btn-prev" @click="slidePrevItem()">
              <JIcon name="arrow-slider" />
            </button>
          </div>
          <swiper
            class="overflow-hidden swiper"
            ref="swiperSlider"
            :modules="modules"
            :pagination="pagination"
            :observer="true"
            :observeParents="true"
            :slidesPerView="1"
            :spaceBetween="0"
            :allowTouchMove="true"
            :loop="true"
            :autoplay="{
              delay: 4000,
              disableOnInteraction: false,
            }"
          >
            <template v-for="(item, index) in items" :key="index">
              <swiper-slide class="bg-white">
                <Link
                  :href="item.link"
                  class="w-full transition-opacity hover:opacity-70"
                >
                  <div class="pb-[37.36%] relative">
                    <JPicture
                      :src="
                        item.thumbnail
                          ? item.thumbnail
                          : '/images/banner-cover.jpg'
                      "
                      class="absolute inset-0 hidden object-cover w-full h-full md:block"
                    />
                    <JPicture
                      :src="
                        item.thumbnail_mobile
                          ? item.thumbnail_mobile
                          : item.thumbnail || '/images/banner-cover.jpg'
                      "
                      class="absolute inset-0 object-cover w-full h-full md:hidden"
                    />
                  </div>
                </Link>
              </swiper-slide>
            </template>
          </swiper>
          <div
            class="absolute z-20 hidden -translate-y-1/2 right-3 top-1/2 xl:block"
          >
            <button class="slide-btn btn-next" @click="slideNextItem()">
              <JIcon name="arrow-slider" class="rotate-180" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import { Autoplay, Pagination } from "swiper";
import "swiper/css";
import "swiper/css/pagination";

export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  props: ["items"],
  setup() {
    return {
      modules: [Autoplay, Pagination],
      pagination: {
        clickable: true,
      },
    };
  },
  methods: {
    slideNextItem() {
      this.$refs.swiperSlider.$el.swiper.slideNext();
    },
    slidePrevItem() {
      this.$refs.swiperSlider.$el.swiper.slidePrev();
    },
  },
};
</script>

<style lang="scss" scoped>
.slide-btn {
  @apply md:inline-block align-top hover:opacity-60 transition duration-200 ease-in-out hidden;
}
.swiper {
  :deep {
    .swiper-pagination {
      @apply bottom-0.5;
    }
    .swiper-pagination-bullet {
      @apply w-3 h-3 bg-white bg-opacity-60 hover:opacity-100 transition duration-200 ease-in-out;
    }
    .swiper-pagination-bullet-active {
      @apply opacity-100;
    }
  }
}
</style>
