<template>
  <section
    class="mb-4 product-sale-shock md:mb-8"
    v-if="list && list.length > 0"
  >
    <div class="container">
      <h2 class="xl:mb-[24px] md:mb-[17px] mb-[12px] p2">
        <span class="font-bold text-black">SẢN PHẨM&nbsp;</span>
        <span class="font-[900] text-red-450">SALE SỐC</span>
      </h2>
      <div class="relative slider -mx-0.5 sm:-mx-2.5">
        <swiper
          :slidesPerView="1.25"
          :spaceBetween="8"
          :allowTouchMove="true"
          :breakpoints="{
            '375': {
              slidesPerView: 1.75,
              spaceBetween: 8,
            },
            '576': {
              slidesPerView: 2.5,
              spaceBetween: 8,
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
            <swiper-slide class="productSaleShockCard">
              <ProductCard
                :item="item"
                class="h-full"
                @setTargetModal="setModal"
              />
            </swiper-slide>
          </template>
        </swiper>
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
      currentProduct: {},
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
      const items = document.querySelectorAll(".productSaleShockCard");
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
.slider {
  :deep {
    .swiper {
      @apply px-0.5 sm:px-2.5 xl:py-[8px] md:py-[6px] py-[4px];
    }
  }
}
</style>
