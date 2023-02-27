<template>
  <section class="mb-4 product-gift-suggest md:mb-8">
    <div class="container">
      <h2 class="xl:mb-[24px] md:mb-[17px] mb-[12px] p2">
        <span class="font-bold text-black">GỢI Ý QUÀ TẶNG</span>
      </h2>
      <Tabs
        v-model="active"
        tab-class="flex items-center justify-center h-10 px-4 py-3 font-bold border-gray-800 rounded-full md:px-8 btn-md"
        tabActiveState="bg-black border text-white"
        tabNormalState="bg-transparent border border text-black hover:bg-black hover:text-white transition-all duration-200 ease-in-out"
      >
        <Tab
          v-for="(tabItem, idx) of giftProducts"
          :key="idx"
          :title="tabItem.name"
          :slug="tabItem.slug"
        >
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
              <template v-for="(item, index) of tabItem.products" :key="index">
                <swiper-slide class="productGiftCard">
                  <ProductCard :item="item" @setTargetModal="setModal" />
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
        </Tab>
      </Tabs>
      <QuickView v-model="modalData" />
    </div>
  </section>
</template>

<script>
import ProductCard from "./ProductCard.vue";
import Tabs from "./jam/Tabs.vue";
import Tab from "./jam/Tab.vue";
import QuickView from "../Components/QuickView.vue";
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import "swiper/css";

export default {
  components: {
    ProductCard,
    Tabs,
    Tab,
    QuickView,
    Swiper,
    SwiperSlide,
  },
  props: ["giftProducts"],
  data() {
    return {
      active: this.giftProducts[0].slug,
      is_selected_size: null,
      is_selected_color_1: null,
      is_selected_color_2: null,
      is_selected_color_3: null,
      modalData: {},
    };
  },
  watch: {
    active() {
      setTimeout(() => {
        this.setHeightCard();
      }, 100);
    },
  },
  mounted() {
    this.setHeightCard();
  },
  methods: {
    slideNextItem() {
      this.$refs.swiperSlider[0].$el.swiper.slideNext();
    },
    slidePrevItem() {
      this.$refs.swiperSlider[0].$el.swiper.slidePrev();
    },
    setModal(data) {
      this.modalData = data;
    },
    setHeightCard() {
      const items = document.querySelectorAll(".productGiftCard");
      const heights = [];
      items.forEach((element) => {
        heights.push(element.scrollHeight);
      });
      const maxheight = Math.max.apply(null, heights);

      items.forEach((item) => {
        item.style.height = maxheight + "px";
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.slide-btn {
  @apply md:inline-block align-top hover:opacity-60 transition duration-200 ease-in-out hidden;
}
.slider {
  :deep {
    .swiper {
      @apply px-0.5 sm:px-2.5 xl:py-[8px] md:py-[6px] py-[4px];
    }
  }
}
:deep {
  .tabs-header {
    @apply flex items-center mb-6 space-x-3 overflow-x-auto md:space-x-5 whitespace-nowrap;
  }
}
</style>
