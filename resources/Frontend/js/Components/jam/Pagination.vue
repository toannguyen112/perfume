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
      <Link
        :href="link.url"
        v-else
        class="bg-white page-number link"
        :class="[link.active ? 'active' : '']"
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
      </Link>
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
