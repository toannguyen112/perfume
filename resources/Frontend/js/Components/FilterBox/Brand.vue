<template>
  <div v-if="brands && brands.length > 0" class="variant">
    <Collapse
      :is-open-default="isActive()"
      class="py-4 border-b border-gray-300"
    >
      <template v-slot:collapse-trigger>
        <h3
          class="relative font-bold text-black transition-colors duration-200 ease-in-out hover:text-red-400 group"
        >
          <span class="select-none">Thương hiệu</span>
          <JIcon
            name="arrow-down"
            class="text-black transition-colors duration-200 ease-in-out collapse-icon group-hover:text-red-400"
          />
        </h3>
      </template>
      <template v-slot:collapse-content>
        <ShowMore
          :custom-class="[
            brands.length < 5 ? 'hidden' : '',
            'text-strong-blue-400 text-sm see-more mt-4 inline-block',
          ]"
          collapsed-text="Thu gọn"
          expanded-text="Xem thêm"
          content-class="max-h-[165px] mt-3"
          has-icon="true"
        >
          <div
            class="text-gray-600 check-control"
            :class="idx < brands.length ? 'mb-1.5' : ''"
            v-for="(item, idx) of brands"
            :key="idx"
          >
            <input
              :id="`attribute-brand-${item.id}`"
              v-model="item.active"
              name="check_filter"
              type="checkbox"
              @change="$emit('pushToUrl', item.active ? item.slug : '')"
            />
            <div
              class="relative z-10 flex items-center text-sm label-check caption"
            >
              <label
                :for="`attribute-brand-${item.id}`"
                class="cursor-pointer pl-[30px]"
                :class="{ 'pointer-events-none': loading }"
                >{{ item.name }}</label
              >
            </div>
            <span class="checkmark input"></span>
          </div>
        </ShowMore>
      </template>
    </Collapse>
    <!-- <div
      v-if="limit < brands.length"
      class="text-center btn-primary label xl:px-6 max-w-[197px] w-full h-8 py-2 px-3 sm:px-5 mx-auto mt-[12px] cursor-pointer"
      @click="limit += 15"
    >
      Xem thêm
    </div> -->
  </div>
</template>

<script>
import Collapse from "../jam/Collapse.vue";
import ShowMore from "../jam/ShowMore.vue";
import { unserializeOptions } from "@/lib/filter.js";
export default {
  components: {
    Collapse,
    ShowMore,
  },
  props: {
    brands: {
      type: Array,
      default: () => [],
    },
    category: {
      type: Object,
      default: () => {},
    },
    pathName: {
      type: String,
      default: function () {
        return "categories-slug";
      },
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
  emits: ["updateBrand"],
  data() {
    return {
      filterOptions: unserializeOptions(this.route().params),
      limit: 15,
    };
  },

  methods: {
    isActive() {
      return !!this.brands.find((x) => x.active);
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../../scss/components/form.scss";
</style>
