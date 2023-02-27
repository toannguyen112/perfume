<template>
  <main>
    <HomeBannerSlider :items="sliders" class="pt-2 md:pt-4" />
    <section class="xl:mb-[48px] md:mb-[34px] mb-[24px] category-slide">
      <div class="container">
        <h2 class="xl:mb-[24px] md:mb-[17px] mb-[12px] p2">
          <span class="font-bold text-black">DANH MỤC SẢN PHẨM</span>
        </h2>
        <div class="overflow-x-auto grid-custom-item">
          <div
            class="flex flex-col flex-wrap items-center justify-center h-full"
          >
            <div
              class="w-[259px] xl:w-1/5 wrap-link"
              v-for="(item, idx) of productCategories"
              :key="idx"
            >
              <Link
                :href="
                  route('products.index', {
                    slug: item.slug,
                  })
                "
                class="relative flex items-center h-full px-4 py-3 link-bubble md:px-6 md:py-4 bg-red-0"
              >
                <span
                  class="z-[2] absolute top-0 right-0 px-2 py-0.5 text-white description bg-red-400"
                  v-if="item.is_hot"
                  >HOT</span
                >
                <div class="flex items-center relative z-[2]">
                  <div
                    v-html="item.icon"
                    class="w-7 lg:w-[44px] h-7 lg:h-[44px] svg-custom"
                  />
                  <span class="flex-1 ml-4 font-semibold text-black">{{
                    item.name
                  }}</span>
                </div>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>
    <ProductSaleShock :list="saleProducts" />
    <section class="xl:mb-[38px] md:mb-[27px] mb-[24px] md:py-[10px]">
      <div class="container">
        <div class="grid grid-cols-12 gap-4 md:gap-2">
          <div
            v-if="top_side_left && top_side_left.thumbnail"
            class="col-span-full md:col-span-8"
          >
            <a
              :href="top_side_left.link"
              class="block transition duration-200 ease-in-out hover:opacity-70"
            >
              <div class="aspect-w-[885] aspect-h-[313]">
                <div class="picture-cover">
                  <JPicture
                    class="hidden md:block"
                    :src="
                      top_side_left.thumbnail
                        ? top_side_left.thumbnail
                        : '/images/cover.jpg'
                    "
                  />
                  <JPicture
                    class="md:hidden"
                    :src="
                      top_side_left.thumbnail_mobile
                        ? top_side_left.thumbnail_mobile
                        : top_side_left.thumbnail || '/images/cover.jpg'
                    "
                  />
                </div>
              </div>
            </a>
          </div>
          <div
            v-if="top_side_right && top_side_right.thumbnail"
            class="col-span-full md:col-span-4"
          >
            <a
              :href="top_side_right.link"
              class="block transition duration-200 ease-in-out hover:opacity-70"
            >
              <div class="aspect-w-[451] aspect-h-[322]">
                <div class="picture-cover">
                  <JPicture
                    class="hidden md:block"
                    :src="
                      top_side_right.thumbnail
                        ? top_side_right.thumbnail
                        : '/images/cover.jpg'
                    "
                  />
                  <JPicture
                    class="md:hidden"
                    :src="
                      top_side_right.thumbnail_mobile
                        ? top_side_right.thumbnail_mobile
                        : top_side_right.thumbnail || '/images/cover.jpg'
                    "
                  />
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>
    <ProductTopSearch :list="topViewProducts" />
    <ProductNew :list="newProducts" />
    <ProductGiftSuggest :gift-products="giftProducts" />
    <TopBrand :brands="brands" />
    <section class="mb-8 md:mb-12">
      <div class="container">
        <div class="grid grid-cols-12 gap-4 md:gap-6">
          <div
            v-if="footer_above && footer_above.thumbnail"
            class="col-span-full"
          >
            <a
              :href="footer_above.link"
              class="block transition duration-200 ease-in-out hover:opacity-70"
            >
              <div class="aspect-w-3 aspect-h-1">
                <div class="picture-cover">
                  <JPicture
                    class="hidden md:block"
                    :src="
                      footer_above.thumbnail
                        ? footer_above.thumbnail
                        : '/images/cover.jpg'
                    "
                  />
                  <JPicture
                    class="md:hidden"
                    :src="
                      footer_above.thumbnail_mobile
                        ? footer_above.thumbnail_mobile
                        : footer_above.thumbnail || '/images/cover.jpg'
                    "
                  />
                </div>
              </div>
            </a>
          </div>
          <div
            v-if="footer_bottom_side_left && footer_bottom_side_left.thumbnail"
            class="col-span-full md:col-span-4"
          >
            <a
              :href="footer_bottom_side_left.link"
              class="block transition duration-200 ease-in-out hover:opacity-70"
            >
              <div class="aspect-w-4 aspect-h-3">
                <div class="picture-cover">
                  <JPicture
                    class="hidden md:block"
                    :src="
                      footer_bottom_side_left.thumbnail
                        ? footer_bottom_side_left.thumbnail
                        : '/images/cover.jpg'
                    "
                  />
                  <JPicture
                    class="md:hidden"
                    :src="
                      footer_bottom_side_left.thumbnail_mobile
                        ? footer_bottom_side_left.thumbnail_mobile
                        : footer_bottom_side_left.thumbnail ||
                          '/images/cover.jpg'
                    "
                  />
                </div>
              </div>
            </a>
          </div>
          <div
            v-if="footer_center && footer_center.thumbnail"
            class="col-span-full md:col-span-4"
          >
            <a
              :href="footer_center.link"
              class="block transition duration-200 ease-in-out hover:opacity-70"
            >
              <div class="aspect-w-4 aspect-h-3">
                <div class="picture-cover">
                  <JPicture
                    class="hidden md:block"
                    :src="
                      footer_center.thumbnail
                        ? footer_center.thumbnail
                        : '/images/cover.jpg'
                    "
                  />
                  <JPicture
                    class="md:hidden"
                    :src="
                      footer_center.thumbnail_mobile
                        ? footer_center.thumbnail_mobile
                        : footer_center.thumbnail || '/images/cover.jpg'
                    "
                  />
                </div>
              </div>
            </a>
          </div>
          <div
            v-if="
              footer_bottom_side_right && footer_bottom_side_right.thumbnail
            "
            class="col-span-full md:col-span-4"
          >
            <a
              :href="footer_bottom_side_right.link"
              class="block transition duration-200 ease-in-out hover:opacity-70"
            >
              <div class="aspect-w-4 aspect-h-3">
                <div class="picture-cover">
                  <JPicture
                    class="hidden md:block"
                    :src="
                      footer_bottom_side_right.thumbnail
                        ? footer_bottom_side_right.thumbnail
                        : '/images/cover.jpg'
                    "
                  />
                  <JPicture
                    class="md:hidden"
                    :src="
                      footer_bottom_side_right.thumbnail_mobile
                        ? footer_bottom_side_right.thumbnail_mobile
                        : footer_bottom_side_right.thumbnail ||
                          '/images/cover.jpg'
                    "
                  />
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>
    <BeautyTip :posts="posts" />
    <SocialMeta
      title="Muội Muội - Thiên đường mỹ phẩm cao cấp."
      description="Muội Muội cung cấp sản phẩm chất lượng từ những thương hiệu uy tín, được tin dùng. Lấy niềm vui, sự hài lòng của khách hàng để làm động lực, chúng tôi không ngừng tìm kiếm các sản phẩm tốt nhất để mỗi khách hàng đều có thể trở nên tự tin và xinh đẹp hơn."
    />
  </main>
</template>

<script>
import App from "@/Layouts/App.vue";
import HomeBannerSlider from "../Components/HomeBannerSlider.vue";
import ProductSaleShock from "../Components/ProductSaleShock.vue";
import ProductTopSearch from "../Components/ProductTopSearch.vue";
import ProductNew from "../Components/ProductNew.vue";
import ProductGiftSuggest from "../Components/ProductGiftSuggest.vue";
import TopBrand from "../Components/TopBrand.vue";
import BeautyTip from "../Components/BeautyTip.vue";

export default {
  components: {
    HomeBannerSlider,
    ProductSaleShock,
    ProductTopSearch,
    ProductNew,
    ProductGiftSuggest,
    TopBrand,
    BeautyTip,
  },
  layout: App,
  props: [
    "productCategories",
    "brands",
    "posts",
    "saleProducts",
    "topViewProducts",
    "newProducts",
    "sliders",
    "top_side_left",
    "top_side_right",
    "footer_above",
    "footer_bottom_side_left",
    "footer_center",
    "footer_bottom_side_right",
    "giftProducts",
  ],
  data() {
    return {
      categories: [
        {
          url: "/",
          is_hot: true,
          name: "Makeup",
          iconName: "make-up",
        },
        {
          url: "/",
          is_hot: false,
          name: "Chăm sóc da",
          iconName: "skin-care",
        },
        {
          url: "/",
          is_hot: false,
          name: "Nước hoa",
          iconName: "perfume",
        },
        {
          url: "/",
          is_hot: false,
          name: "Dụng cụ làm đẹp",
          iconName: "makeup-tool",
        },
        {
          url: "/",
          is_hot: false,
          name: "Sản phẩm cho Nam",
          iconName: "man",
        },
        {
          url: "/",
          is_hot: false,
          name: "Tóc",
          iconName: "hair-cream",
        },
        {
          url: "/",
          is_hot: false,
          name: "Thể thao",
          iconName: "sport",
        },
        {
          url: "/",
          is_hot: false,
          name: "Giày",
          iconName: "shoes",
        },
        {
          url: "/",
          is_hot: false,
          name: "Túi",
          iconName: "bag",
        },
        {
          url: "/",
          is_hot: false,
          name: "Trang sức",
          iconName: "jewel",
        },
      ],
    };
  },
};
</script>

<style lang="scss" scoped>
.link-bubble {
  @apply relative overflow-hidden;
  &:before,
  &:after {
    @apply absolute z-[1] bg-red-50 bg-opacity-[41] w-[131px] h-[131px] transition-all ease-in-out duration-500 rounded-full;
    content: "";
  }
  &:before {
    @apply top-1/2 left-[-78px] -translate-y-1/2;
  }
  &:after {
    @apply bottom-[-93px] right-[-41px];
  }
  &:hover {
    &:before,
    &:after {
      @apply w-[300px] h-[300px];
    }
  }
}
.category-slide {
  .grid-custom-item {
    @apply h-[120px] sm:h-[160px] -mx-1 sm:-mx-1.5;
  }
  .wrap-link {
    height: calc((100% - 8px) / 2);
    .link-bubble {
      @apply mx-1 sm:mx-1.5;
    }
    &:nth-child(odd) {
      @apply pb-1;
    }
    &:nth-child(even) {
      @apply pt-1;
    }
  }
}
:deep(.svg-custom) {
  svg {
    @apply w-full h-full;
  }
}
</style>
