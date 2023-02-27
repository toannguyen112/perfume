<template>
  <div v-if="data && data.last_page > 1" class="pagination">
    <template v-for="(link, index) in data.links" :key="index">
      <div
        v-if="link.url === null || link.url === '...' || link.active"
        :class="[link.active ? 'active' : '']"
        class="bg-white page-number"
      >
        <div class="flex items-center justify-center">
          <JIcon
            v-if="index === 0 || index === data.links.length - 1"
            :class="{
              'transform rotate-[180deg]': index === 0,
            }"
            name="next-pagination"
            class="flex items-center justify-center"
          />
          <div v-else>{{ link.label }}</div>
        </div>
      </div>
      <div
        v-else
        class="bg-white cursor-pointer page-number link"
        :class="[link.active ? 'active' : '']"
        @click="changePage(link.label)"
      >
        <div class="flex items-center justify-center">
          <JIcon
            v-if="index === 0 || index === data.links.length - 1"
            :class="{
              'transform rotate-[180deg]': index === 0,
            }"
            name="next-pagination"
            class="flex items-center justify-center"
          />
          <div v-else>{{ link.label }}</div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      default: () => {},
    },
  },
  emits: ["changePage"],

  methods: {
    changePage(label) {
      let page = this.data.current_page;

      switch (label) {
        case "<":
          page = this.data.current_page - 1;
          break;
        case ">":
          page = this.data.current_page + 1;
          break;

        default:
          page = label;
          break;
      }

      this.$emit("changePage", page);
    },
  },
};
</script>

<style lang="scss" scoped>
.pagination {
  @apply flex space-x-[12px] items-center;
}
.page-number {
  @apply duration-300 h-[32px] w-[32px] justify-center flex items-center text-black  rounded-full   text-[14px] font-bold leading-[120%];
}

.page-number.link {
  @apply hover:bg-gray-200;
}

.active {
  @apply bg-black text-white;
}
</style>
