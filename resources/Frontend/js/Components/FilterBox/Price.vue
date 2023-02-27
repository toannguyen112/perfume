<template>
  <Collapse class="py-4 border-b border-gray-300">
    <template v-slot:collapse-trigger>
      <h3
        class="relative font-bold text-black transition-colors duration-200 ease-in-out hover:text-red-400 group"
      >
        <span class="select-none">Sắp xếp giá cả</span>
        <JIcon
          name="arrow-down"
          class="text-black transition-colors duration-200 ease-in-out collapse-icon group-hover:text-red-400"
        />
      </h3>
    </template>
    <template v-slot:collapse-content>
      <div class="mt-3 space-y-3">
        <template v-for="(itemPrice, idx) in priceRange" :key="idx">
          <div class="radio-control">
            <label
              class="font-medium text-gray-800 relative z-10 inline-block cursor-pointer sm:block pl-[26px] caption hover:text-red-400 transition duration-200 ease-in-out"
              :for="`price-type-${itemPrice.id}`"
            >
              <div class="flex items-center space-x-[8px]">
                <div v-html="itemPrice.label"></div>
              </div>
            </label>
            <input
              class="radio-input"
              type="radio"
              name="price-type"
              :value="itemPrice.value"
              v-model="formPrice"
              :id="`price-type-${itemPrice.id}`"
            />
            <span class="checkmark"></span>
          </div>
        </template>
        <div class="radio-control">
          <label
            class="relative z-10 cursor-pointer flex items-center pl-[26px] mb-4"
            for="price-type-custom-price"
          >
            <span class="flex-1">
              <input
                pattern="[0-9]*"
                v-model="priceFrom"
                id="from-price"
                name="from-price"
                type="number"
                placeholder="Thấp nhất"
                class="w-full px-2 py-1 font-medium text-gray-800 border border-gray-300 rounded-sm placeholder-custom caption focus:outline-none focus:ring-transparent focus:border-strong-blue-400 h-7 cursor-text"
              />
            </span>
            <span class="mx-2 font-semibold text-gray-800"> - </span>
            <span class="flex-1">
              <input
                pattern="[0-9]*"
                v-model="priceTo"
                id="to-price"
                name="to-price"
                type="number"
                placeholder="Cao nhất"
                class="w-full px-2 py-1 font-medium text-gray-800 border border-gray-300 rounded-sm placeholder-custom caption focus:outline-none focus:ring-transparent focus:border-strong-blue-400 h-7 cursor-text"
              />
            </span>
          </label>
          <input
            class="radio-input"
            type="radio"
            name="price-type"
            value="custom-price"
            v-model="formPrice"
            id="price-type-custom-price"
          />
          <span class="checkmark"></span>
        </div>

        <div @click="applyPrice()">
          <button
            class="btn btn-md py-[11px] px-[30px] btn-accent font-semibold"
          >
            <span>Áp dụng</span>
          </button>
        </div>
      </div>
    </template>
  </Collapse>
</template>

<script>
import Collapse from "../jam/Collapse.vue";
import { mappingPrice } from "@/lib/filter.js";
export default {
  components: {
    Collapse,
  },
  pathName: {
    type: String,
    default: function () {
      return "categories-slug";
    },
  },
  data() {
    return {
      priceRange: [
        {
          id: 1,
          label: "Dưới 100.000đ",
          value: "0-99999",
        },
        {
          id: 2,
          label: "Từ 100.000đ - 300.000đ",
          value: "100000-300000",
        },
        {
          id: 3,
          label: "Từ 2 - 5 triệu",
          value: "2000000-5000000",
        },
        {
          id: 4,
          label: "Trên 5 triệu",
          value: "5000000-1000000000",
        },
      ],
      price: {
        from: null,
        to: null,
      },
      priceFrom: this.price ? this.price.from : null,
      priceTo: this.price ? this.price.to : null,
    };
  },

  computed: {
    formPrice: {
      get() {
        return this.price.from && this.price.to
          ? `${this.price.from}-${this.price.to}`
          : null;
      },

      set(newValue) {
        const query = { price: newValue };

        this.price = mappingPrice(query);
      },
    },
  },

  watch: {
    "$page.url"() {
      this.setPrice();
    },
  },

  created() {
    this.setPrice();
  },

  methods: {
    applyPrice() {
      let params = {};
      if (this.formPrice === "custom-price") {
        if (this.priceFrom === "" || this.priceTo === "") {
          return false;
        }
        params = { price: `${this.priceFrom}-${this.priceTo}` };
      }
      if (this.formPrice && this.formPrice !== "custom-price") {
        params = { price: this.formPrice };
      }

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
          // preserveState: true,
        }
      );
    },

    setPrice() {
      this.price = mappingPrice(this.route().params);
      this.checkPrice();
    },

    checkPrice() {
      if (!this.priceRange.find((x) => x.value === this.formPrice)) {
        this.priceFrom = this.price.from ? this.price.from : null;
        this.priceTo = this.price.to ? this.price.to : null;
        this.price.from = this.price.from ? "custom" : null;
        this.price.to = this.price.to ? "price" : null;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../../scss/components/form.scss";
.placeholder-custom {
  &::placeholder {
    /* Chrome, Firefox, Opera, Safari 10.1+ */
    color: theme("colors.gray.400");
    opacity: 1; /* Firefox */
    font-weight: 500;
  }
  &:-ms-input-placeholder,
  &::-ms-input-placeholder {
    /* Microsoft Edge */
    color: theme("colors.gray.400");
    font-weight: 500;
  }
}
</style>
