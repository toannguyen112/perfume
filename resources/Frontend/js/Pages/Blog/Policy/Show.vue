<template>
  <main>
    <BreadcrumbPage :item="policyBreadcrumb" />
    <section
      class="xl:mb-[56px] md:mb-[39px] mb-[28px] xl:mt-[32px] md:mt-[22px] mt-[16px]"
    >
      <div class="container">
        <div class="grid grid-cols-12 md:gap-x-[32px] gap-y-[16px]">
          <div class="col-span-full md:col-span-3 xl:col-span-2 xl:col-start-2">
            <div class="sticky top-0 hidden md:block">
              <h1 class="font-bold p3">Chính sách</h1>
              <div class="w-full h-[1px] bg-gray-300 my-[12px]"></div>
              <div class="flex flex-col space-y-[12px]">
                <Link
                  v-for="(item, index) in list_sidebar"
                  :key="index"
                  :href="route('policy.show', item.slug)"
                  class="font-medium duration-200"
                  :class="
                    checkActive(item.slug)
                      ? ' text-red-500'
                      : ' text-gray-600 hover:text-black'
                  "
                >
                  {{ item.title }}
                  <br />
                </Link>
              </div>
            </div>
            <button class="block md:hidden" @click="togglePolicyMobile">
              <div
                class="text-center py-[8px] px-[32px] uppercase tracking-[1px] cursor-pointer tab select-none relative bg-red-500 text-white font-semibold"
              >
                Chính sách
              </div>
            </button>
          </div>
          <div class="col-span-full md:col-span-9 xl:col-span-8 xl:ml-[32px]">
            <div class="xl:mb-[20px] md:mb-[14px] mb-[10px]">
              <div
                class="font-bold text-black border-b border-gray-300 p1 leading-title"
              >
                {{ content.title }}
              </div>
              <div class="w-full h-[2px] bg-orange-linear mt-[4px]"></div>
            </div>
            <div class="prose" v-html="content.content"></div>
          </div>
        </div>
      </div>
    </section>

    <div
      class="lg:hidden block w-full max-h-[100vh] fixed inset-0 z-[10000]"
      :class="isOpenMobile ? 'h-[100vh]' : 'h-0'"
    >
      <div
        class="bg-black w-full h-full max-h-[100vh] duration-200 relative z-10"
        :class="isOpenMobile ? 'opacity-70' : 'opacity-0'"
        @click="togglePolicyMobile"
      ></div>
      <div
        class="h-full fixed overflow-y-auto top-0 left-0 transform duration-200 bg-white md:w-[40vw] w-[75vw] z-20"
        :class="isOpenMobile ? 'translate-x-0' : '-translate-x-full'"
      >
        <div
          v-if="list_sidebar.length > 0"
          class="grid grid-cols-1 tabs-header"
        >
          <ul
            v-for="(item, index) in list_sidebar"
            :key="index"
            class="flex flex-col justify-between border-b border-gray-200 collapsibleMb max-lg:border-l max-lg:border-r max-lg:border-t"
          >
            <li
              class="relative uppercase transition ease-out outline-none cursor-pointer select-none group caption"
              data-cursor-size
            >
              <Link
                :href="route('policy.show', item.slug)"
                class="px-[12px] py-[16px] w-full block font-semibold"
                :class="checkActive(item.slug) ? 'link-active' : ''"
              >
                {{ item.title }}
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <SocialMeta
      :title="content.meta_title ? content.meta_title : content.title"
      :description="
        content.meta_description
          ? content.meta_description
          : content.description
      "
    />
  </main>
</template>

<script>
import BreadcrumbPage from "../../../Components/jam/BreadCrumbPage.vue";
import App from "../../../Layouts/App.vue";
export default {
  components: {
    BreadcrumbPage,
  },
  layout: App,
  props: ["list_sidebar", "content"],

  data() {
    return {
      policyBreadcrumb: [
        {
          name: this.content.title,
          link: "policy.index",
        },
      ],
      isOpenMobile: false,
    };
  },
  computed: {
    currentRouteName() {
      return this.$page.url;
    },
  },
  methods: {
    checkActive(url) {
      const fullPath = this.$page.url;
      const arrPath = fullPath.split("/");
      return url == arrPath[2];
    },
    togglePolicyMobile() {
      this.isOpenMobile = !this.isOpenMobile;
      if (this.isOpenMobile) {
        document.body.classList.add("lg:overflow-hidden");
      } else {
        document.body.classList.remove("lg:overflow-hidden");
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.leading-title {
  @apply leading-[48px];
}
.link-active {
  @apply bg-red-500 text-white;
}
:deep(.prose) {
  @apply max-w-full w-full;
  h1,
  h2,
  h3,
  h4,
  h5,
  p,
  ul,
  li {
    @apply xl:my-[8px] md:my-[6px] my-[4px] font-display;
  }
  h4 {
    @apply xl:mt-[20px]	md:mt-[14px] mt-[10px] text-[1rem]  md:text-[1.125rem] leading-[150%] font-bold;
  }
  h4,
  h5 {
    @apply text-black font-display;
  }
  p,
  ul {
    @apply text-gray-700 text-[1rem] font-medium font-display;
  }
  ul {
    @apply list-none xl:mt-[16px]	md:mt-[11px] mt-[8px] pl-[1rem];
    li::before {
      content: "\2022";
      @apply text-gray-700 inline-block w-[1em] ml-[-1em];
    }
  }
  .prose :where(ul > li):not(:where([class~="not-prose"] *)) {
    @apply pl-0;
  }
}
</style>
