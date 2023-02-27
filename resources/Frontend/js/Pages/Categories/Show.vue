<template>
  <main>
    <BreadcrumbMultiLevel :items="breadcrumb" class="pt-5 pb-2 md:py-6" />
    <div
      v-if="currentRoute === 'product.search'"
      class="container xl:mb-[40px] md:mb-[28px] mb-[20px]"
    >
      <div
        class="xl:pb-[20px] md:pb-[14px] pb-[10px] border-b border-solid border-gray-200"
      >
        <div v-if="products.total > 0" class="p2 leading-[150%] text-gray-700">
          Có
          <span class="font-semibold text-black">{{ products.total }}</span> kết
          quả tìm kiếm cho
          <span class="font-semibold text-black"
            >“{{ route().params.keyword }}”</span
          >
        </div>
        <div v-else class="p2 leading-[150%] text-gray-700">
          <div>
            Không có kết quả tìm kiếm nào cho
            <span class="font-semibold text-black"
              >“{{ route().params.keyword }}”</span
            >
          </div>
          <div class="caption leading-[150%] font-bold text-gray-500">
            Vui lòng nhập từ khóa khác hoặc thử những sản phẩm chúng tôi gợi ý
            dưới đây.
          </div>
        </div>
      </div>
    </div>
    <template
      v-if="
        currentRoute !== 'product.search' ||
        (currentRoute === 'product.search' && products.total > 0)
      "
    >
      <section
        class="border-b border-gray-300 xl:pb-[56px] md:pb-[39px] pb-[28px] categories"
      >
        <div class="container">
          <div class="bg-white lg:flex">
            <!-- Sidebar -->
            <div
              class="max-lg:z-[10002] fixed top-0 right-0 lg:static lg:w-[274px]"
              :class="[openFilterBar ? 'w-full h-full z-10' : '']"
            >
              <!-- <div
              class="fixed inset-0 bg-white z-[100] lg:hidden"
              :class="$fetchState.pending ? 'opacity-70 cursor-wait' : 'hidden'"
            ></div> -->
              <Transition name="fade">
                <!-- Dimmer -->
                <div
                  class="absolute inset-0 bg-black lg:hidden bg-opacity-70 active:outline-none"
                  @click="closeSidebar()"
                ></div>
              </Transition>
              <div
                class="absolute lg:static top-0 w-[270px] sm:w-[320px] lg:w-[274px] h-screen lg:h-auto bg-white transition-all duration-200 ease-in-out overflow-y-auto lg:pr-6"
                :class="[
                  openFilterBar
                    ? 'right-0'
                    : 'right-[-270px] sm:right-[-320px]',
                ]"
              >
                <div class="px-4 lg:px-0">
                  <!-- <div class="block md:hidden" :class="{ 'pointer-events-none': $fetchState.pending }">
                  <MobileHeaderOrder title="Lọc sản phẩm" :cart="false">
                    <div @click="closeSidebar()">
                      <JIcon name="arrow-left" class="text-white cursor-pointer" />
                    </div>
                  </MobileHeaderOrder>
                </div> -->
                  <FilterBoxCategories
                    :categories="categories || brand_categories"
                    :category="meta"
                  />
                  <FilterBoxBrand
                    v-if="product_category"
                    :brands="brands"
                    :category="meta"
                    @pushToUrl="pushToUrl"
                  />
                  <FilterBoxPrice />
                  <FilterBoxRating :ratings="ratings" />
                  <FilterBoxVariant
                    @pushToUrl="pushToUrl"
                    v-model:options="optionsData"
                  />
                </div>
                <div
                  class="sticky bottom-0 z-10 block bg-white border-t border-gray-300 lg:hidden"
                >
                  <div class="grid grid-cols-2 px-4 py-2 gap-x-2">
                    <div @click="reset()">
                      <JButton
                        label="Thiết lập lại"
                        size="sm"
                        variant="secondary"
                        class="w-full btn-full py-[11px] px-[16px]"
                      />
                    </div>
                    <!-- <div @click="applyMobile()">
                    <JButton
                      label="Áp dụng"
                      size="sm"
                      class="w-full btn-full py-[11px] px-[16px]"
                    />
                  </div> -->
                  </div>
                </div>
              </div>
            </div>
            <div class="w-full">
              <FilterBoxContent
                :list="products"
                :ratings="ratings"
                :category="meta"
                :tags="tags"
                :options="optionsData"
                @openFilterbar="handleOpenFilter($event)"
                @pushToUrl="pushToUrl"
              />
              <Pagination
                :data="products"
                class="justify-end xl:pt-[32px] md:pt-[22px] pt-[16px]"
              />
            </div>
          </div>
        </div>
      </section>
      <section
        v-if="meta"
        class="xl:pt-[32px] md:pt-[22px] pt-[16px] xl:pb-[50px] md:pb-[35px] pb-[25px]"
      >
        <div class="container">
          <h3 class="font-bold text-black uppercase p2">
            {{ meta.meta_title || meta.name }}
          </h3>
          <p
            v-if="meta.content"
            class="xl:mt-[24px] md:mt-[17px] mt-[12px] font-medium text-gray-800"
          >
            <span
              v-if="!!meta.content"
              :class="isShowFull ? '' : 'line-clamp-5'"
              v-html="meta.content"
            ></span>
          </p>
          <a
            v-if="!!meta.content"
            href="javascript:;"
            class="flex items-center xl:mt-[24px] md:mt-[17px] mt-[12px] space-x-2 text-red-500"
            @click="isShowFull = !isShowFull"
          >
            <span>{{ isShowFull ? "Thu gọn" : "Xem thêm" }}</span>
            <JIcon
              name="arrow-down-sm"
              :class="isShowFull ? 'rotate-180' : ''"
            />
          </a>
        </div>
      </section>
    </template>
    <template v-else>
      <section
        class="border-b border-gray-300 xl:pb-[56px] md:pb-[39px] pb-[28px] categories"
      >
        <div class="container">
          <ContentSearchEmpty :list="topProducts" />
          <Pagination :data="topProducts" class="justify-end pt-8" />
        </div>
      </section>
    </template>
    <SocialMeta
      v-if="currentRoute === 'product.search'"
      title="Tìm kiếm dễ dàng hơn 1000 sản phẩm làm đẹp tại Muội Muội."
      description="
        Thỏa thích mua sắm, xóa bỏ trở ngại khi tìm kiếm công cụ làm đẹp yêu thích tại website Muội Muội nhờ danh mục sản phẩm khoa học cùng bộ lọc chi tiết.
      "
    />
    <SocialMeta
      v-else-if="currentRoute === 'new.index'"
      title="Top 10 sản phẩm làm đẹp theo danh mục mới nhất tại Muội Muội."
      description="
        Muội Muội mời quý khách cùng cập nhật xu hướng làm đẹp tươi mới thông qua những sản phẩm 'nóng hổi' vừa ra mắt trên thị trường.
      "
    />
    <SocialMeta
      v-else
      :title="meta.meta_title || meta.name"
      :description="meta.meta_description || meta.summary"
    />
  </main>
</template>

<script>
import BreadcrumbMultiLevel from "../../Components/BreadcrumbMultiLevel.vue";
import FilterBoxCategories from "../../Components/FilterBox/Categories.vue";
import App from "@/Layouts/App.vue";
import FilterBoxPrice from "../../Components/FilterBox/Price.vue";
import FilterBoxContent from "../../Components/FilterBox/Content.vue";
import ContentSearchEmpty from "../../Components/FilterBox/ContentSearchEmpty.vue";
import FilterBoxVariant from "../../Components/FilterBox/Variant.vue";
import FilterBoxBrand from "../../Components/FilterBox/Brand.vue";
import FilterBoxRating from "../../Components/FilterBox/Rating.vue";
import Pagination from "../../Components/jam/Pagination.vue";
import { serializeQuery, mappingOptions } from "@/lib/filter.js";

export default {
  components: {
    BreadcrumbMultiLevel,
    FilterBoxContent,
    ContentSearchEmpty,
    FilterBoxCategories,
    FilterBoxPrice,
    FilterBoxVariant,
    FilterBoxBrand,
    FilterBoxRating,
    Pagination,
  },
  layout: App,
  props: [
    "meta",
    "options",
    "products",
    "categories",
    "brand_categories",
    "tags",
    "breadcrumb",
    "topProducts",
    "brands",
    "product_category",
    "brand",
  ],
  data() {
    return {
      isShowFull: false,
      pagination: {},
      modalSelected: undefined,
      showCompare: false,
      openFilterBar: false,
      showDropdown: false,
      optionsData: [],
      ratings: [
        {
          rate_value: "5",
          text: "5 sao",
        },
        {
          rate_value: "4",
          text: "Từ 4 sao",
        },
        {
          rate_value: "3",
          text: "Từ 3 sao",
        },
        {
          rate_value: "2",
          text: "Từ 2 sao",
        },
      ],
    };
  },

  computed: {
    currentRoute() {
      return this.route().current();
    },
  },

  watch: {
    "$page.url"() {
      this.setOptions();
    },
  },
  created() {
    this.setOptions();
  },
  methods: {
    handleOpenFilter(data) {
      this.openFilterBar = data;
    },
    closeSidebar() {
      this.openFilterBar = false;
    },
    toggleBodyClass(addRemoveClass, className) {
      const el = document.body;

      if (addRemoveClass === "addClass") {
        el.classList.add(className);
      } else {
        el.classList.remove(className);
      }
    },

    setOptions() {
      // this.optionsData = mappingOptions(this.options, this.route().params);
      this.optionsData = this.options;
    },

    reset() {
      const url = this.$page.props.ziggy.url;
      this.$inertia.get(
        url,
        {},
        {
          preserveScroll: true,
        }
      );
    },

    pushToUrl(brand = null) {
      let params = serializeQuery(this.route().params, this.optionsData);

      params = {
        ...params,
        category: this.product_category?.slug,
        brand: brand,
      };

      this.$inertia.visit(
        this.route("route-filters", { ...params }),
        {},
        {
          replace: true,
          preserveScroll: true,
          preserveState: true,
        }
      );
    },
  },
};
</script>
