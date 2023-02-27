<template>
  <div v-if="options && options.length > 0" class="variant">
    <template
      v-for="(optionItem, index) of options.slice(0, limit)"
      :key="index"
    >
      <template v-if="optionItem.slug !== 'mau-sac-demo'">
        <Collapse
          v-if="optionItem.children.length"
          :is-open-default="optionItem.active"
          class="py-4 border-b border-gray-300"
        >
          <template v-slot:collapse-trigger>
            <h3
              class="relative font-bold text-black transition-colors duration-200 ease-in-out hover:text-red-400 group"
            >
              <span class="select-none">{{ optionItem.name }}</span>
              <JIcon
                name="arrow-down"
                class="text-black transition-colors duration-200 ease-in-out collapse-icon group-hover:text-red-400"
              />
            </h3>
          </template>
          <template v-slot:collapse-content>
            <ShowMore
              :customClass="[
                optionItem.children.length < 5 ? 'hidden' : '',
                'text-strong-blue-400 text-sm see-more mt-4 inline-block',
              ]"
              collapsedText="Thu gọn"
              expandedText="Xem thêm"
              contentClass="max-h-[165px] mt-3"
              hasIcon="true"
            >
              <div
                v-for="(item, idx) of optionItem.children"
                :key="idx"
                class="text-gray-600 check-control"
                :class="idx < optionItem.children.length ? 'mb-1.5' : ''"
              >
                <input
                  :id="`attribute-${item.id}-${fieldId}`"
                  v-model="item.active"
                  name="check_filter"
                  type="checkbox"
                  @change="$emit('pushToUrl')"
                />
                <JPicture
                  v-if="item.image_url"
                  :src="item.image_url"
                  class="object-cover checkmark image"
                />
                <label
                  :for="`attribute-${item.id}-${fieldId}`"
                  class="relative z-10 flex items-center text-sm label-check caption cursor-pointer pl-[30px]"
                  :class="{ 'pointer-events-none': loading }"
                >
                  {{ item.name }}
                </label>
                <span
                  class="checkmark input"
                  :class="{ hidden: item.image_url }"
                ></span>
              </div>
            </ShowMore>
          </template>
        </Collapse>
      </template>
    </template>
    <div
      v-if="limit < options.length"
      class="text-center btn-primary label xl:px-6 max-w-[197px] w-full h-8 py-2 px-3 sm:px-5 mx-auto mt-[12px] cursor-pointer"
      @click="limit += 15"
    >
      Xem thêm
    </div>
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
    options: {
      type: Array,
      default: () => [],
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
  data() {
    return {
      filterOptions: unserializeOptions(this.route().params),
      limit: 15,
    };
  },

  computed: {
    fieldId() {
      return Math.random().toString(36).substr(2, 9);
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../../scss/components/form.scss";
</style>
