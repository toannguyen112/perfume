<template>
  <div
    v-if="
      dataOptions.length > 0 ||
      (dataPrice && dataPrice.from !== null && dataPrice.to !== null) ||
      (dataRating && dataRating.rate_value !== null && dataRating.text !== null)
    "
    class="items-center mb-3 border-b lg:flex border-grey-100 xl:border-b-0"
  >
    <div
      class="flex flex-1 overflow-x-auto lg:flex-wrap whitespace-nowrap lg:whitespace-normal"
    >
      <template
        v-if="dataPrice && dataPrice.from !== null && dataPrice.to !== null"
      >
        <div
          class="flex items-center rounded bg-gray-100 px-3 py-1.5 mr-2 mb-3"
        >
          <span class="mr-1.5 text-xs sm:text-sm text-gray-600">
            {{ generatePriceLabel() }}
          </span>
          <div class="cursor-pointer" @click="removePriceTag()">
            <JIcon name="close" class="w-4 h-4" />
          </div>
        </div>
      </template>
      <template
        v-if="
          dataRating &&
          dataRating.rate_value !== null &&
          dataRating.text !== null
        "
      >
        <div
          class="flex items-center rounded bg-gray-100 px-3 py-1.5 mr-2 mb-3"
        >
          <span class="mr-1.5 text-xs sm:text-sm text-gray-600">
            {{ dataRating.text }}
          </span>
          <div class="cursor-pointer" @click="removeRatingTag()">
            <JIcon name="close" class="w-4 h-4" />
          </div>
        </div>
      </template>
      <div
        v-for="item in dataOptions"
        :key="item.id"
        class="flex items-center rounded bg-gray-100 px-3.5 py-2 mr-2 mb-3"
      >
        <span class="mr-1.5 text-gray-700 caption font-medium">
          {{ item.name }}
        </span>
        <div
          class="transition-opacity duration-200 ease-in-out cursor-pointer hover:opacity-50"
          @click="removeOptionTag(item)"
        >
          <JIcon name="close" class="w-4 h-4" />
        </div>
      </div>
      <div
        v-if="
          dataOptions.length > 0 ||
          (dataPrice && dataPrice.from !== null && dataPrice.to !== null) ||
          (dataRating &&
            dataRating.rate_value !== null &&
            dataRating.text !== null)
        "
        class="caption text-red-400 hover:opacity-50 duration-200 ease-in-out transition-opacity px-3.5 py-2 cursor-pointer"
        @click="removeAll"
      >
        Xoá tất cả
      </div>
    </div>
  </div>
</template>

<script>
import { convertOptionsTagsSlug } from "@/lib/filter.js";
import { removeOptionsTag } from "@/lib/filter.js";
export default {
  props: {
    options: {
      type: Array,
      default: function () {
        return [];
      },
    },

    category: {
      type: Object,
      default: function () {
        return {};
      },
    },

    ratings: {
      type: Array,
      default: function () {
        return [];
      },
    },

    pathName: {
      type: String,
      default: function () {
        return "categories-slug";
      },
    },
  },

  data() {
    return {
      dataOptions: [],
      dataRating: {
        rate_value: null,
        text: null,
      },
      dataPrice: {
        from: null,
        to: null,
      },
    };
  },

  watch: {
    "$page.url"() {
      this.initOptions();
      this.initPrice();
      this.initRateScore();
    },
  },
  created() {
    this.initOptions();
    this.initPrice();
    this.initRateScore();
  },

  methods: {
    initOptions() {
      let slug = this.route().params.slug;
      let options = slug ? slug.split("/") : [];

      options.splice(options.indexOf(this.category.slug), 1);

      this.dataOptions = convertOptionsTagsSlug(this.options, options);
      // this.dataOptions = convertOptionsTags(this.options, this.route().params);
    },

    initPrice() {
      const { price } = this.route().params;
      if (price) {
        const priceArr = price.split("-");
        if (priceArr && priceArr.length > 1) {
          this.dataPrice.from = priceArr[0];
          this.dataPrice.to = priceArr[1];
        }
      } else {
        this.dataPrice = { from: null, to: null };
      }
    },

    initRateScore() {
      const { rate_score } = this.route().params;
      if (rate_score) {
        this.dataRating = this.ratings.find((x) => x.rate_value === rate_score);
      } else {
        this.dataRating = { rate_value: null, text: null };
      }
    },

    removeRatingTag() {
      const params = { rate_score: null };
      this.pushToUrl(params);
    },

    removePriceTag() {
      const params = { price: null };
      this.pushToUrl(params);
    },

    removeOptionTag(item) {
      // let params = removeOptionsTag(item, this.route().params);
      // console.log(this.route().params);
      // let slug = this.route().params.slug;
      // let params = slug.split("/");
      // params.splice(params.indexOf(item.slug), 1);

      // params = params.join("/");

      // console.log(params);

      this.options.map((option) => {
        option.children.map((child) => {
          if (child.id === item.id) {
            child.active = false;
          }
        });
      });

      this.$emit("pushToUrl");

      // this.pushToUrl(params);
    },

    removeOptionTags() {
      const url = this.$page.props.ziggy.url;
      this.$inertia.get(
        url,
        {},
        {
          preserveScroll: true,
          // preserveState: true,
        }
      );
    },

    removeAll() {
      this.removeOptionTags();
    },

    generatePriceLabel() {
      const from = this.formatPrice(this.dataPrice.from);
      const to = this.formatPrice(this.dataPrice.to);

      return `Từ ${from} đến ${to}`;
    },

    formatPrice(value) {
      const formatter = new Intl.NumberFormat("vi", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 0,
      });
      return formatter.format(value).replace("₫", "").trim();
    },

    pushToUrl(params) {
      const routeName = this.route().current();

      let allParams = { ...this.route().params, ...params };

      this.$inertia.get(
        this.route(routeName, { ...allParams }),
        {},
        {
          preserveScroll: true,
          // preserveState: true,
        }
      );
    },
  },
};
</script>
