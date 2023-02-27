<template>
  <section class="mb-8 md:mb-12 beauty-tip">
    <div class="container">
      <h2 class="flex items-center justify-between mb-2 p2">
        <span class="font-bold text-black">BÍ KÍP LÀM ĐẸP</span>
        <div class="text-blue-400 md:hidden p4">
          <Link
            :href="route('post.index')"
            class="h-10 px-8 py-3 btn-md btn-secondary-square"
          >
            Xem tất cả
          </Link>
        </div>
      </h2>
      <div class="mb-4 md:flex md:items-end md:justify-between md:mb-6">
        <p class="mb-3 text-gray-800 md:mb-0 max-w-[406px]">
          Tập hợp những kiến thức bổ ích về thế giới làm đẹp từ các beauty
          blogger và bác sĩ da liễu hàng đầu.
        </p>
        <div class="hidden md:block">
          <Link
            :href="route('post.index')"
            class="h-10 px-8 py-3 btn-md btn-secondary-square"
          >
            Xem tất cả
          </Link>
        </div>
      </div>
      <div class="relative slider">
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
          :allowTouchMove="true"
          :loop="true"
          :breakpoints="{
            '320': {
              slidesPerView: 1.4,
              spaceBetween: 16,
            },
            '576': {
              slidesPerView: 2.5,
              spaceBetween: 16,
            },
            '768': {
              slidesPerView: 2.5,
              spaceBetween: 24,
            },
            '1280': {
              slidesPerView: 4,
              spaceBetween: 32,
            },
          }"
        >
          <template v-for="(post, index) in posts" :key="index">
            <swiper-slide class="bg-white">
              <Link
                :href="
                  route('post.show', {
                    categorySlug: post.category.slug,
                    slug: post.slug,
                  })
                "
                class="block group"
              >
                <div class="mb-4 overflow-hidden aspect-w-3 aspect-h-2">
                  <JPicture
                    :src="post.thumbnail ? post.thumbnail : '/images/cover.jpg'"
                    class="object-cover w-full h-full transition duration-200 ease-in-out group-hover:scale-110"
                  />
                </div>
                <span class="block font-bold text-gray-600 caption">{{
                  post.category && post.category.title
                    ? post.category.title
                    : "SỨC KHỎE"
                }}</span>
                <h3
                  class="mb-1 font-bold text-black p3 min-h-[60px] group-hover:text-red-400 transition-colors duration-200 ease-in-out"
                >
                  <span class="line-clamp-2">{{ post.title }}</span>
                </h3>
                <div class="flex items-center mb-2 text-gray-500 caption">
                  <span v-if="post.author" class="mr-3">{{ post.author }}</span>
                  <span>{{ post.formatted_created_at }}</span>
                </div>
                <p class="font-semibold text-gray-800">
                  <span class="line-clamp-3"> {{ post.description }}</span>
                </p>
              </Link>
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
    </div>
  </section>
</template>

<script>
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import "swiper/css";

export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  props: ["posts"],
  methods: {
    slideNextItem() {
      this.$refs.swiperSlider.$el.swiper.slideNext();
    },
    slidePrevItem() {
      this.$refs.swiperSlider.$el.swiper.slidePrev();
    },
  },
  data() {
    return {
      options: {
        prevNextButtons: false,
        cellAlign: "left",
        pageDots: false,
        wrapAround: true,
      },
      list: [
        {
          url: "/",
          image_url: "https://via.placeholder.com/249",
          type: "new",
          name: "Kem dưỡng ẩm Obagi Hydrate Facial Moisturizer",
          rateValue: 4,
          price: "1.150.000 đ",
          tags: ["Phổ biến nhất"],
        },
        {
          url: "/",
          image_url: "https://via.placeholder.com/249",
          type: "hot",
          name: "Kem dưỡng ẩm Obagi Hydrate Facial Moisturizer",
          rateValue: 5,
          price: "1.150.000 đ",
          tags: ["Phổ biến nhất"],
        },
        {
          url: "/",
          image_url: "https://via.placeholder.com/249",
          type: null,
          name: "Tinh chất dưỡng da Shiseido Future Solution LX Intensive...",
          rateValue: 3,
          price: "6.750.000 đ",
          tags: ["Phổ biến nhất"],
        },
        {
          url: "/",
          image_url: "https://via.placeholder.com/249",
          type: "limited",
          name: "Diorific - The Atelier Of Dreams Limited Edition",
          rateValue: 4,
          price: "5.600.000 đ",
          tags: ["Phổ biến nhất"],
          color: [
            "#B10E01",
            "#DD5D00",
            "#B81D4C",
            "#F33232",
            "#B8551D",
            "#B81D4C",
            "#F33232",
            "#B8551D",
          ],
        },
        {
          url: "/",
          image_url: "https://via.placeholder.com/249",
          type: "limited",
          name: "Tế bào gốc dưỡng trắng và nâng cơ Bqcell Re-Cell Cure...",
          rateValue: 5,
          price: "1.250.000 đ",
          tags: ["Phổ biến nhất"],
        },
      ],
    };
  },
};
</script>
