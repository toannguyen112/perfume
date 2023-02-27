<template>
  <div class="categories-content">
    <!-- <h2
      class="text-[28px] leading-[33.6px] font-semibold capitalize text-gray-700 pt-6 pb-3 hidden lg:block"
    >
      <template v-if="$route.query.keyword">
        <span v-if="!!isSearchPage">{{ `Kết quả tìm kiếm cho "${category}"` }}</span>
        <span v-else>{{ category.name }}</span>
      </template>
    </h2> -->
    <!-- Categories mobile -->
    <div
      class="lg:hidden relative flex items-center justify-between py-3.5 border-b border-gray-300 mb-4"
    >
      <a
        href="javascript:;"
        class="flex items-center space-x-2"
        @click="toggleDropdown()"
      >
        <span class="text-sm leading-[21px] text-gray-700">Sắp xếp theo</span>
        <JIcon name="triangle-down" class="text-gray-500" />
      </a>
      <a
        href="javascript:;"
        class="flex items-center space-x-2"
        @click="toggleSidebar()"
      >
        <span class="text-sm leading-[21px] text-gray-700">Bộ lọc</span>
        <JIcon name="filter" class="text-gray-500" />
      </a>
      <div
        class="absolute left-0 w-[250px] z-20 top-full border border-gray-300"
        :class="[showDropdown ? 'block' : 'hidden']"
      >
        <div
          class="fixed z-[1] top-[95px] md:top-[118px] inset-x-0 bottom-0"
          @click="toggleDropdown()"
        ></div>
        <div class="bg-white relative z-[2]">
          <!-- <div
            class="block relative p-4 text-sm leading-[21px] text-gray-600"
            :class="[idx < tabList.length - 1 ? 'border-b border-gray-300' : '']"
            @click="setSortTag(item)"
            v-for="(item, idx) of tabList"
            :key="idx"
          > -->
          <div
            v-for="(item, idx) of sortData"
            :key="idx"
            class="block relative p-4 text-sm leading-[21px] text-gray-600 cursor-pointer"
            :class="[
              idx < tabList.length - 1 ? 'border-b border-gray-300' : '',
            ]"
            @click="(sortValue = item.value), toggleDropdown(), sort()"
          >
            <span>{{ item.name }}</span>
            <JIcon
              v-if="item.value === sortValue"
              name="tick"
              class="text-green-500 absolute right-[18px] top-1/2 -translate-y-1/2"
            />
          </div>
        </div>
      </div>
    </div>
    <div
      class="p-4 border-b border-gray-300 lg:hidden"
      v-if="categoryList.children && categoryList.children.length"
    >
      <span class="block mb-3 text-base text-gray-800"
        >Mua sắm theo danh mục:</span
      >
      <!-- <JShowMore
        customClass="text-strong-blue-400 text-base font-medium see-more mt-4 inline-block"
        collapsedText="Thu gọn"
        :expandedText="`Xem thêm ${categoryList.children.length - 4} danh mục`"
        contentClass="max-h-[120px]"
        hasIcon="true"
        :wrapBtnClass="['text-center', categoryList.children.length <= 4 && 'hidden']"
      >
      </JShowMore> -->
      <div class="grid grid-cols-2 gap-2">
        <Link href="/">
          <!-- <Link
          :href="{ name: 'categories-slug', params: { id: item.id, slug: item.slug } }"
          class="flex items-center bg-gray-100 h-14"
          v-for="(item, index) of categoryList.children"
          :key="index"
        > -->
          <JImage
            :url="!!item.image_url ? item.image_url : '/images/placeholder.jpg'"
            class="object-cover w-14 h-14"
            :alt="item.name"
          />
          <span class="flex-1 px-2 py-3 text-xs text-gray-600 xs:px-4">
            <span class="line-clamp-2">{{ item.name }}</span>
          </span>
        </Link>
      </div>
    </div>
    <div
      v-if="tags && tags.length > 0"
      class="overflow-y-auto whitespace-nowrap flex space-x-[15px] mb-4"
    >
      <div
        v-for="(item, idx) of tags"
        class="border border-gray-300 h-12 min-w-[188px] px-3 py-2 link-bubble-color flex items-center cursor-pointer"
        :key="idx"
        :class="[listTagIds.includes(`${item.id}`) ? 'active' : '']"
        :style="{ '--bg-color': item.color }"
        @click="setSortTag(item.id)"
      >
        <div class="flex-1 flex items-center relative z-[2]">
          <span class="flex-1 mr-3 font-semibold text-black caption">{{
            item.name
          }}</span>
          <span v-html="item.icon"></span>
        </div>
      </div>
    </div>
    <!-- <div class="justify-start hidden border-b border-gray-300 lg:flex tab-list lg:mb-4">
      <div
        class="menu-tab relative flex-1 flex lg:flex-none cursor-pointer justify-center lg:mr-6 py-4 px-0.5 lg:p-0"
        v-for="(item, idx) of tabList"
        :key="idx"
      >
        <a
          class="flex items-center justify-center"
          :class="[tabId === item.id ? 'active' : '']"
          @click="setSortTag(item)"
        >
          <span>{{ item.name }}</span>
        </a>
      </div>
    </div> -->
    <FilterBoxTags
      v-model:options="options"
      :ratings="ratings"
      :category="category"
      @pushToUrl="$emit('pushToUrl')"
    />
    <div class="flex items-center justify-between mb-4">
      <span class="font-semibold text-gray-500 caption"
        >{{ list.total }} sản phẩm</span
      >
      <div class="items-center hidden space-x-4 lg:flex">
        <label
          class="hidden font-medium text-gray-800 sm:block caption"
          for="product-order"
          >Sắp xếp theo</label
        >
        <div class="select-control">
          <JIcon
            name="arrow-down-sm"
            class="absolute text-gray-800 -translate-y-1/2 top-1/2 right-4"
          />
          <select
            id="product-order"
            v-model="sortValue"
            name="product-order"
            class="text-gray-700 font-medium sm:w-[178px] appearance-none bg-transparent"
            @change="sort"
          >
            <option
              v-for="(item, index) in sortData"
              :key="index"
              :value="item.value"
            >
              {{ item.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="product-content">
      <div class="flex flex-wrap -mx-1 xl:-mx-3">
        <template v-for="(item, index) of list.data" :key="index">
          <AnimateAppear
            :delay="index * 50"
            class="w-1/2 px-1 pb-2 sm:w-1/3 2xl:w-1/4 xl:px-3 xl:pb-6"
          >
            <ProductCard :item="item" @setTargetModal="setModal" />
          </AnimateAppear>
        </template>
        <!-- <AdsCard
          :item="adsItem"
          class="w-1/2 px-1 pb-2 sm:w-1/3 2xl:w-1/4 xl:px-3 xl:pb-6"
        /> -->
      </div>
      <QuickView v-model="modalData" />
    </div>
  </div>
</template>

<script>
import ProductCard from "../ProductCard.vue";
import QuickView from "../../Components/QuickView.vue";
import AdsCard from "../AdsCard.vue";
import FilterBoxTags from "../FilterBox/Tags.vue";

export default {
  components: {
    ProductCard,
    AdsCard,
    FilterBoxTags,
    QuickView,
  },
  props: ["category", "list", "isSearchPage", "options", "tags", "ratings"],
  data() {
    return {
      modalSelected: undefined,
      is_selected_size: null,
      is_selected_color_1: null,
      is_selected_color_2: null,
      is_selected_color_3: null,
      adsItem: {
        slug: "test-ads",
        id: 123,
        image_url: "https://via.placeholder.com/340x434",
      },
      categoryList: {},
      variantFilter: [],
      showCompare: false,
      tabId: 1,
      breadcrumb: {
        title: "Đèn LED",
      },
      openFilterbar: false,
      showDropdown: false,
      filterItem: {
        id: 1,
        name: "Phổ biến",
      },
      tagAttribute: [],
      activePrice: null,
      priceSelected: {},
      watSelected: null,
      tabList: [
        {
          id: "new",
          name: "Mới",
          style: "red",
          icon: "confetti-red",
        },
        {
          id: "top_seller",
          name: "Bán chạy",
          style: "yellow",
          icon: "best-seller-yellow",
        },
        {
          id: "mini_size",
          name: "Mini Size",
          style: "blue",
          icon: "cosmetics-blue",
        },
        {
          id: "natural",
          name: "Thiên nhiên",
          style: "green",
          icon: "leaf-green",
        },
        {
          id: "makeup",
          name: "Nền makeup",
          style: "orange",
          icon: "foundation-orange",
        },
      ],
      sortData: [
        { value: "default", name: "Mới nhất" },
        { value: "popular", name: "Phổ biến nhất" },
        { value: "discount", name: "Bán chạy" },
        { value: "price_desc", name: "Giá cao" },
        { value: "price_asc", name: "Giá thấp" },
      ],
      sortValue: this.route().params.sort
        ? this.route().params.sort
        : "default",
      listTagIds: this.route().params.tags
        ? this.route().params.tags.split(",")
        : [],

      modalData: {},
    };
  },

  computed: {
    cssVars() {
      return {
        "--bg-color": this.bgColor,
      };
    },
  },

  methods: {
    setSortTag(id) {
      let params = {};

      if (!this.listTagIds.includes(`${id}`)) {
        this.listTagIds.push(`${id}`);
      } else {
        this.listTagIds = this.listTagIds.filter((x) => x !== `${id}`);
      }

      const tags = this.listTagIds.join(",");

      params = { tags: tags };

      this.pushToUrl(params);
    },

    sort() {
      let params = {};

      params = { sort: this.sortValue };

      this.pushToUrl(params);
    },

    pushToUrl(params) {
      const routeName = this.route().current();

      let allParams = { ...this.route().params, ...params };

      this.$inertia.get(
        this.route(routeName, { ...allParams }),
        {},
        {
          replace: true,
          preserveScroll: true,
          preserveState: true,
        }
      );
    },

    toggleSidebar() {
      this.openFilterbar = true;
      this.$emit("openFilterbar", this.openFilterbar);
    },
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
      if (this.showDropdown) {
        this.toggleBodyClass("addClass", "overflow-hidden");
      } else {
        this.toggleBodyClass("removeClass", "overflow-hidden");
      }
    },
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
  },
};
</script>

<style lang="scss" scoped>
@import "../../../scss/components/form.scss";
.link-bubble-color {
  @apply relative overflow-hidden;
  &:before,
  &:after {
    @apply absolute transition-all duration-200 ease-in-out w-[131px] h-[131px] rounded-full;
    content: "";
  }
  &:before {
    @apply left-[-5px] top-2;
  }
  &:after {
    @apply right-[-81px] bottom-[5px];
  }
  &:hover {
    &:before,
    &:after {
      @apply w-[300px] h-[300px];
    }
    &:before {
      @apply -top-5 -left-8;
    }
    &:after {
      @apply -bottom-5 -right-8;
    }
  }
  &:before,
  &:after {
    background-color: var(--bg-color);
  }
}

.active {
  &:before,
  &:after {
    @apply w-[300px] h-[300px];
  }
  &:before {
    @apply -top-5 -left-8;
  }
  &:after {
    @apply -bottom-5 -right-8;
  }
}

// @screen lg {
//   .categories-content {
//     width: calc(100% - 274px);
//   }
// }
</style>
