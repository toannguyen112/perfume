<template>
  <Link
    v-if="item"
    :href="
      route('post.show', {
        categorySlug: item.category.slug,
        slug: item.slug,
      })
    "
    class="relative flex card group"
    :class="[
      isRelated
        ? 'flex-row space-x-[24px] items-center'
        : 'flex-col justify-between xl:space-y-[16px] lg:space-y-[12px] space-y-[8px]',
      isRelatedCurrent ? '' : 'h-full',
    ]"
  >
    <div class="flex-shrink-0">
      <div
        class="overflow-hidden"
        :class="
          isRelated ? 'max-w-[80px] max-h-[80px] aspect-[1/1]' : 'aspect-[3/2]'
        "
      >
        <JImage
          :src="item.thumbnail ? item.thumbnail : '/images/cover.jpg'"
          class="object-cover w-full h-full image"
          :alt="item.title"
        />
      </div>
    </div>
    <div class="flex h-full">
      <div :class="isHot || isRelatedCurrent ? 'shadow' : ''"></div>
      <div
        class="flex w-full"
        :class="[
          isHot
            ? 'absolute bottom-0 xl:pb-[26px]	md:pb-[18px]	pb-[13px] xl:px-[28px]	md:px-[20px]	px-[14px] flex-col-reverse'
            : '',
          isRelatedCurrent
            ? 'absolute bottom-0 flex-col-reverse xl:px-[20px]	md:px-[14px]	px-[10px] xl:pb-[20px]	md:pb-[14px]	pb-[10px]'
            : '',
          isNormal ? 'flex-col lg:space-y-[4px]' : '',
        ]"
      >
        <div
          class="caption xl:space-x-[24px] md:space-x-[17px] space-x-[12px]"
          :class="[
            isRelated ? 'hidden' : 'lg:flex hidden',
            isRelatedCurrent ? 'mt-[8px]' : '',
            isHot ? 'flex' : '',
          ]"
        >
          <div
            class="font-medium"
            :class="isNormal ? 'text-red-400' : 'text-red-200'"
          >
            {{ item.category.title }}
          </div>
          <div
            class="font-medium"
            :class="isNormal ? 'text-gray-800 label' : 'text-white caption'"
          >
            <span v-if="item.author">{{ item.author }} -</span>
            <span>{{ item.reading_time }} phút</span>
          </div>
        </div>
        <Link
          :href="
            route('post.show', {
              categorySlug: item.category.slug,
              slug: item.slug,
            })
          "
          class="inline-block"
          :class="isHot ? ' xl:mb-[12px]	lg:mb-[8px]' : ''"
        >
          <div
            class="hidden font-bold duration-150 lg:line-clamp-2 group-hover:text-red-500 lg:block"
            :class="[
              isNormal ? 'text-black p3' : '',
              isHot ? 'p2 text-white lg:uppercase' : '',
              isRelatedCurrent ? 'text-white lg:uppercase' : '',
            ]"
          >
            {{ item.title }}
          </div>
          <div
            class="font-bold duration-150 max-lg:line-clamp-2 group-hover:text-red-500 lg:hidden"
            :class="[
              isNormal ? 'text-black p3' : '',
              isHot ? 'p2 text-white lg:uppercase' : '',
              isRelatedCurrent ? 'text-white lg:uppercase' : '',
            ]"
          >
            {{ capitalizeFirstLetter(item.title) }}
          </div>
        </Link>
      </div>
    </div>
  </Link>
</template>

<script>
export default {
  props: ["item", "isHot", "isRelatedCurrent", "isNormal", "isRelated"],
  methods: {
    capitalizeFirstLetter(string) {
      const getLowerCase = string.toLowerCase();
      return getLowerCase.charAt(0).toUpperCase() + getLowerCase.slice(1);
    },
  },
};
</script>
<style lang="scss" scoped>
.shadow {
  height: 50%;
  width: 100%;
  position: absolute;
  bottom: 0;
  background: linear-gradient(0deg, #19191a 0%, rgba(25, 25, 26, 0) 100%);
}
.card {
  @apply duration-200;
  .image {
    @apply scale-100 duration-200;
  }
  .title {
    @apply text-white;
  }
  &:hover {
    .image {
      @apply scale-110;
    }
    .title {
    }
  }
}
</style>
