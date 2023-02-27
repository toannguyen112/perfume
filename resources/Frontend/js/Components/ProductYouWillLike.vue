<template>
  <section class="xl:pt-[48px] md:pt-[34px] pt-[24px]">
    <div class="container">
      <div class="p2 font-bold text-black xl:mb-[24px] md:mb-[17px] mb-[12px]">
        CÓ THỂ BẠN SẼ THÍCH
      </div>
      <div class="relative">
        <div
          class="absolute z-20 -left-2 md:-left-5 xl:left-[-38px] top-1/2 transform translate-y-[-100%] md:block hidden"
        >
          <button class="slide-btn btn-prev" @click="slidePrevItem()">
            <JIcon name="arrow-slider" />
          </button>
        </div>
        <swiper
          class="overflow-hidden swiper"
          ref="swiperSlider"
          :observer="true"
          :observeParents="true"
          :slidesPerView="1.25"
          :spaceBetween="16"
          :allowTouchMove="false"
          :loop="true"
          :breakpoints="{
            '576': {
              slidesPerView: 2.5,
              spaceBetween: 16,
              allowTouchMove: true,
            },
            '768': {
              slidesPerView: 2.75,
              spaceBetween: 16,
              allowTouchMove: true,
            },
            '1024': {
              slidesPerView: 3.5,
              spaceBetween: 16,
            },
            '1280': {
              slidesPerView: 4,
              spaceBetween: 24,
            },
            '1440': {
              slidesPerView: 5,
              spaceBetween: 24,
            },
          }"
        >
          <template v-for="(item, index) of items" :key="index">
            <swiper-slide class="bg-white">
              <ProductCard :item="item" @setTargetModal="setModal" />
            </swiper-slide>
          </template>
        </swiper>
        <div
          class="absolute z-20 -right-2 md:-right-5 xl:right-[-38px] top-1/2 transform translate-y-[-100%] md:block hidden"
        >
          <button class="slide-btn btn-next" @click="slideNextItem()">
            <JIcon name="arrow-slider" class="rotate-180" />
          </button>
        </div>
      </div>
      <QuickView v-model="modalData" />
    </div>
  </section>
</template>
<script>
import ProductCard from "../Components/ProductCard.vue";
import QuickView from "../Components/QuickView.vue";
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import "swiper/css";
export default {
  components: { Swiper, SwiperSlide, ProductCard, QuickView },
  props: {
    items: {
      type: Array,
      default: [],
    },
  },
  data() {
    return {
      modalSelected: undefined,
      is_selected_size: null,
      is_selected_color_1: null,
      is_selected_color_2: null,
      is_selected_color_3: null,
      modalData: {},
    };
  },
  methods: {
    toggleBodyClass(addRemoveClass, className) {
      const el = document.body;
      if (addRemoveClass === "addClass") {
        el.classList.add(className);
      } else {
        el.classList.remove(className);
      }
    },

    setModal(data) {
      this.modalData = data;
    },

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
.slider {
  :deep {
    .swiper {
      @apply px-0.5 sm:px-2.5;
    }
  }
}
.slide-btn {
  @apply md:inline-block align-top hover:opacity-60 transition duration-200 ease-in-out hidden;
}
</style>
