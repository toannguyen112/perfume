<template>
  <section
    class="mb-4 product-top-search md:mb-[22px]"
    v-if="list && list.length > 0"
  >
    <div class="container">
      <h2 class="xl:mb-[24px] md:mb-[17px] mb-[12px] p2">
        <span class="font-bold text-black">TÌM KIẾM HÀNG ĐẦU</span>
      </h2>
      <div class="relative slider -mx-0.5 sm:-mx-2.5">
        <div
          class="absolute z-20 -left-1 sm:-left-2 md:-left-5 xl:left-[-38px] top-1/2 -translate-y-1/2"
        >
          <button class="slide-btn btn-prev" @click="slidePrevItem()">
            <JIcon name="arrow-slider" />
          </button>
        </div>
        <swiper
          ref="swiperSlider"
          :slidesPerView="1.25"
          :spaceBetween="8"
          :allowTouchMove="false"
          :loop="true"
          :breakpoints="{
            '375': {
              slidesPerView: 2.4,
              spaceBetween: 8,
              allowTouchMove: true,
            },
            '576': {
              slidesPerView: 2.5,
              spaceBetween: 8,
              allowTouchMove: true,
            },
            '768': {
              slidesPerView: 3.25,
              spaceBetween: 16,
            },
            '1024': {
              slidesPerView: 3.5,
              spaceBetween: 16,
            },
            '1280': {
              slidesPerView: 5,
              spaceBetween: 16,
            },
          }"
        >
          <template v-for="(item, index) of list.slice(0, 5)" :key="index">
            <swiper-slide class="productTopSearchCard">
              <ProductCard
                :item="item"
                class="h-full"
                @setTargetModal="setModal"
              />
            </swiper-slide>
          </template>
        </swiper>
        <div
          class="absolute z-20 -right-1 sm:-right-2 md:-right-5 xl:right-[-38px] top-1/2 -translate-y-1/2"
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
import ProductCard from "./ProductCard.vue";
import QuickView from "../Components/QuickView.vue";
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import "swiper/css";

export default {
  components: {
    ProductCard,
    Swiper,
    SwiperSlide,
    QuickView,
  },
  props: ["list"],
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

  mounted() {
    this.setHeightCard();
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
    setHeightCard() {
      const items = document.querySelectorAll(".productTopSearchCard");
      const heights = [];
      items.forEach((element) => {
        heights.push(element.scrollHeight);
      });
      const maxheight = Math.max.apply(null, heights);

      items.forEach((item) => {
        item.style.height = maxheight + "px";
      });
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
      @apply px-0.5 sm:px-2.5 xl:py-[8px] md:py-[6px] py-[4px];
    }
  }
}

.slide-btn {
  @apply md:inline-block align-top hover:opacity-60 transition duration-200 ease-in-out hidden;
}
</style>
