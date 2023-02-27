<template>
  <section
    class="absolute xl:top-[183px] lg:top-[138px] md:top-[114px] top-[90px] left-0 right-0"
  >
    <div class="absolute top-[16px] left-0">
      <div class="container">
        <div class="flex items-center">
          <Link
            href="/"
            class="font-semibold text-black transition duration-200 ease-in-out caption hover:text-red-500"
            >Trang chủ</Link
          >
          <div class="mx-[12px] text-gray-500">/</div>
          <Link
            :href="route('post.index')"
            class="font-semibold text-black transition duration-200 ease-in-out caption hover:text-red-500"
            >Blog</Link
          >
          <div class="mx-[12px] text-gray-500">/</div>
          <div
            class="font-semibold text-gray-500 capitalize pointer-events-none select-none caption"
          >
            Bài viết
          </div>
        </div>
      </div>
    </div>
    <div class="max-h-[480px] h-full w-full">
      <JImage
        :src="
          item.banner_thumbnail
            ? item.banner_thumbnail
            : '/images/blog/banner-show.jpg'
        "
        :alt="item.title"
        class="object-cover w-full h-full xl:max-h-[480px] md:max-h-[340px] max-h-[240px]"
      />
    </div>
  </section>
  <section
    class="xl:pb-[56px] md:pb-[39px] pb-[28px] xl:pt-[480px] md:pt-[340px] pt-[240px]"
  >
    <div
      class="container grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px] gap-x-[16px] relative bg-white"
    >
      <div
        class="grid grid-cols-8 bg-white col-span-full md:col-span-8 mt-[-82px]"
      >
        <div
          class="col-span-full md:col-span-8 border-2 border-gray-200 xl:p-[32px] md:p-[22px] p-[16px]"
        >
          <div
            v-if="item.title"
            class="p1 font-bold uppercase xl:mb-[12px] md:mb-[8px] mb-[6px]"
          >
            {{ item.title }}
          </div>
          <div
            class="flex xl:space-x-[24px] md:space-x-[17px] space-x-[12px] caption xl:mb-[32px] md:mb-[22px] mb-[16px]"
          >
            <div v-if="item.category.title" class="text-red-200">
              {{ item.category.title }}
            </div>
            <div v-if="item.formatted_created_at">
              <span v-if="item.author">{{ item.author }} -</span>
              <span>{{ item.reading_time }} phút</span>
            </div>
          </div>
          <div
            class="prose after:block after:w-full after:h-[1px] after:bg-gray-300 after:mt-[32px] after:mb-[12px] overflow-hidden"
            v-html="item.content"
          ></div>
          <div
            class="flex lg:justify-start justify-between xl:space-x-[32px] md:space-x-[22px] space-x-[16px] items-center"
          >
            <div class="font-medium text-gray-700">Chia sẻ</div>
            <div>
              <SocialList />
            </div>
          </div>
        </div>
        <div
          class="xl:mt-[56px] md:mt-[39px] mt-[28px] col-span-full md:block hidden"
        >
          <div class="p2 font-bold xl:mb-[32px] md:mb-[22px] mb-[16px]">
            BÀI VIẾT LIÊN QUAN
          </div>
          <div
            class="grid md:grid-cols-2 grid-cols-1 xl:gap-[32px] md:gap-[22px] gap-[16px]"
          >
            <CardBlog
              v-for="(item, index) in postNews.slice(0, 4)"
              :key="index"
              :item="item"
              :isNormal="true"
            />
          </div>
        </div>
      </div>
      <div
        class="md:col-span-4 col-span-full md:mt-0 mt-[8px] bg-white xl:pt-[32px] md:pt-[22px] pt-[16px]"
      >
        <div class="md:sticky xl:top-[32px] md:top-[22px]">
          <div class="p2 font-bold xl:mb-[32px] md:mb-[22px] mb-[16px]">
            BÀI VIẾT MỚI NHẤT
          </div>
          <div class="flex flex-col h-full">
            <div
              v-for="(item, index) in postNews.slice(0, 4)"
              :key="index"
              class="after:block after:w-full after:h-[1px] after:bg-gray-300 last:after:hidden after:xl:my-[30px] after:md:my-[21px] after:my-[15px]"
            >
              <CardBlog :item="item" :isRelated="true" />
            </div>
          </div>
        </div>
      </div>
      <div
        class="col-span-full md:col-span-8 xl:mt-[56px] md:mt-[39px] mt-[28px] md:hidden"
      >
        <div class="p2 font-bold xl:mb-[32px] md:mb-[22px] mb-[16px]">
          BÀI VIẾT LIÊN QUAN
        </div>
        <div
          class="grid md:grid-cols-2 grid-cols-1 xl:gap-[32px] md:gap-[22px] gap-[16px]"
        >
          <CardBlog
            v-for="(item, index) in postNews.slice(0, 4)"
            :key="index"
            :item="item"
            :isNormal="true"
          />
        </div>
      </div>
    </div>
  </section>
  <SocialMeta
    :title="item.meta_title ? item.meta_title : item.title"
    :description="
      item.meta_description ? item.meta_description : item.description
    "
    :image="item.thumbnail ? item.thumbnail : '/images/blog/blog-card.jpg'"
  />
</template>

<script>
import SocialList from "../../../Components/SocialList.vue";
import CardBlog from "../../../Components/jam/CardBlog.vue";

import App from "../../../Layouts/App.vue";

export default {
  props: ["item", "postNews", "relatedPosts"],
  components: {
    CardBlog,
    SocialList,
  },
  layout: App,
};
</script>
//
<style scoped lang="scss">
:deep {
  .prose {
    @apply max-w-full m-0 p-0;
    h2 {
      @apply text-[20px] leading-[150%] m-0 p-0 text-[#000] font-semibold font-display mb-[12px];
    }
    h3 {
      @apply text-[1rem] font-semibold leading-[150%] m-0 p-0 text-black mb-[16px] mt-[20px];
    }
    h4 {
      @apply text-[1rem] font-semibold leading-[150%] m-0 p-0 text-black mb-[12px] mt-[20px];
    }
    p {
      @apply lg:text-[1rem] text-[0.875rem] font-medium leading-[150%] text-gray-700 font-display m-0;
    }
    li {
      @apply font-display font-medium;
    }
    img {
      @apply w-full h-full object-cover my-[16px];
    }
  }
}
</style>
