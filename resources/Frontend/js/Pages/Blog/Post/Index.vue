<template>
  <main>
    <section class="w-full overflow-hidden relative h-[259px]">
      <div class="absolute left-0 right-0">
        <BreadCrumbPage :item="breadCrumb" />
      </div>
      <div class="absolute left-0 right-0 -translate-y-1/2 top-1/2">
        <div class="container">
          <div class="font-bold text-center text-black h2">
            KIẾN THỨC LÀM ĐẸP HỮU ÍCH
          </div>
        </div>
      </div>
      <div class="h-full">
        <JPicture
          src="/images/blog/banner.jpg"
          class="object-cover w-full h-full"
          alt="banner-blog"
        />
      </div>
    </section>
    <section
      class="xl:pt-[40px] md:pt-[28px] pt-[20px] xl:pb-[56px] md:pb-[39px] pb-[28px]"
    >
      <div class="container">
        <div
          class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px] gap-x-[16px]"
        >
          <div
            class="col-span-full grid grid-cols-12 xl:gap-[32px] md:gap-[22px] gap-[16px]"
          >
            <div class="col-span-full md:col-span-8">
              <AnimateAppear class="h-full">
                <CardBlog :item="topPosts[0]" :isHot="true" />
              </AnimateAppear>
            </div>
            <div
              class="col-span-full md:col-span-4 grid grid-cols-2 md:grid-cols-1 xl:gap-x-[20px] md:gap-x-[14px] gap-x-[10px] xl:gap-y-[20px] md:gap-y-[14px] gap-y-[10px]"
            >
              <AnimateAppear
                v-for="(item, index) in topPosts.slice(1, 3)"
                :key="index"
              >
                <CardBlog :item="item" :isRelatedCurrent="true" />
              </AnimateAppear>
            </div>
          </div>
          <div
            v-if="bannerCenter.thumbnail"
            class="col-span-full xl:py-[56px] md:py-[39px] py-[28px] max-h-[500px]"
          >
            <JImage
              :src="
                bannerCenter.thumbnail
                  ? bannerCenter.thumbnail
                  : '/images/blog/banner.jpg'
              "
              class="object-cover object-center w-full h-full"
              :alt="bannerCenter.thumbnail"
            />
          </div>
          <div
            class="col-span-full grid grid-cols-12 xl:gap-[32px] md:gap-[22px] gap-[16px]"
          >
            <div
              class="col-span-full md:col-span-8 grid md:grid-cols-2 grid-cols-1 xl:gap-[32px] md:gap-[22px] gap-[16px]"
            >
              <AnimateAppear
                :delay="index2 * 50"
                v-for="(item, index2) in postNew"
                :key="index2"
              >
                <CardBlog :item="item" :isNormal="true" />
              </AnimateAppear>
            </div>
            <div
              v-if="bannerRight.thumbnail"
              class="hidden md:block md:col-span-4"
            >
              <JImage
                class="sticky w-full h-auto cursor-pointer top-2"
                :src="
                  bannerRight.thumbnail
                    ? bannerRight.thumbnail
                    : '/images/blog/banner-ads.jpg'
                "
                :alt="bannerRight.title"
              />
            </div>
          </div>
        </div>
      </div>
    </section>
    <SocialMeta
      title="Muội Muội - Cẩm nang chăm sóc sắc đẹp"
      description="Muội Muội cung cấp những kiến thức bổ ích về thời trang, bí quyết làm đẹp và phong cách sống."
    />
  </main>
</template>

<script>
import CardBlog from "../../../Components/jam/CardBlog.vue";
import App from "../../../Layouts/App.vue";
import BreadCrumbPage from "../../../Components/jam/BreadCrumbPage.vue";

export default {
  components: {
    CardBlog,
    BreadCrumbPage,
  },
  layout: App,
  props: ["categories", "topPosts", "newPosts", "bannerCenter", "bannerRight"],

  data() {
    return {
      postNew: this.newPosts.data,
      breadCrumb: [
        {
          name: "Blog",
          link: "post.index",
        },
      ],
    };
  },
  created() {
    window.addEventListener("scroll", this.scroll);
  },
  unmounted() {
    window.removeEventListener("scroll", this.scroll);
  },
  methods: {
    scroll() {
      const self = this;
      const footer = document.getElementById("footer_id").scrollHeight;
      if (
        window.innerHeight + window.scrollY >=
        document.body.scrollHeight - footer
      ) {
        const post_links = self.newPosts.links;
        const next = post_links[post_links.length - 1];
        if (next.url) {
          self.$inertia.visit(next.url, {
            preserveScroll: true,
            preserveState: true,
            only: ["newPosts"],
          });
          self.postNew.push(...self.newPosts.data);
        }
      }
    },
  },
};
</script>
