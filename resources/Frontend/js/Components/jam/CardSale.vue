<template>
  <div v-if="item" class="flex flex-col h-full group">
    <Link
      :href="route('sale.show', { saleSlug: item.slug, saleId: item.id })"
      class="overflow-hidden xl:mb-[16px] md:mb-[12px] mb-[8px] rounded-t"
    >
      <div class="aspect-[3/2] group-hover:scale-105 scale-100 duration-150">
        <JImage
          :src="item.thumbnail ? item.thumbnail : '/images/cover.jpg'"
          :alt="item.name"
          class="object-cover w-full h-full"
        />
      </div>
    </Link>
    <div>
      <Link
        v-if="item.name"
        :href="route('sale.show', { saleSlug: item.slug, saleId: item.id })"
        class="p3 font-bold text-black mb-[4px] line-clamp-2 group-hover:text-red-400 duration-150"
      >
        {{ item.name }}
      </Link>
      <div
        v-if="item.discount"
        class="font-medium text-red-400 mb-[4px] lg:block hidden"
      >
        {{ item.discount }}
      </div>
      <div
        v-if="item.start_day || item.end_day"
        class="hidden font-medium text-gray-500 caption lg:block"
      >
        <span>{{ item.start_day }}</span
        ><span v-if="item.start_day && item.end_day"> - </span
        ><span>{{ item.end_day }}</span>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    item: {
      type: Object,
      default: {},
    },
  },
};
</script>
