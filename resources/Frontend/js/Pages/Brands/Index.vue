<template>
  <main>
    <section
      class="2xl:py-[20px] md:py-[14px] py-[10px] border-b border-solid border-gray-300 2xl:mb-[30px] md:mb-[21px] mb-[15px]"
    >
      <div class="container">
        <ul
          class="flex flex-wrap items-center 2xl:justify-between max-md:justify-start"
        >
          <li
            v-for="(cate, index) in categories"
            :key="index"
            :class="[
              key === cate.key ? 'bg-gray-200' : '',
              cate.items && cate.items.length > 0
                ? 'hover:bg-gray-200 cursor-pointer'
                : 'bg-gray-200',
            ]"
            class="p2 font-extrabold md:w-[36px] md:h-[36px] w-[28px] h-[28px] leading-[150%] text-gray-800 flex items-center justify-center rounded-[1px] transition-all duration-200 2xl:mr-[12px] md:mr-[8px] mr-[6px] 2xl:my-[6px] md:my-[4px] my-[3px]"
            @click="cate.items.length > 0 ? filterCategories(cate) : false"
          >
            {{ cate.key }}
          </li>
        </ul>
      </div>
    </section>

    <section>
      <div class="container">
        <template v-for="(category, index) in categories">
          <div
            v-if="
              category.items && category.items.length > 0 && checkKey(category)
            "
            :key="index"
            class="grid grid-cols-12 2xl:gap-x-[32px] md:gap-x-[22px] gap-x-[16px] 2xl:py-[32px] md:py-[22px] py-[16px] border-b border-solid border-gray-300 last:border-none"
          >
            <div
              class="2xl:col-span-11 col-span-full grid 2xl:grid-cols-11 grid-cols-12 2xl:gap-x-[32px] md:gap-x-[22px] gap-x-[16px]"
            >
              <div
                class="col-span-2 text-center h2 text-gray-800 leading-[120%] font-[900]"
              >
                {{ category.key }}
              </div>
              <div class="col-span-10 2xl:col-span-9">
                <div
                  class="grid lg:grid-cols-4 md:grid-cols-3 grid-cols-2 gap-x-[24px] gap-y-[8px]"
                >
                  <Link
                    v-for="(itemChild, indexChild) in category.items"
                    :key="indexChild"
                    :href="
                      route('products.index', {
                        slug: itemChild.slug,
                      })
                    "
                    class="font-medium leading-[150%] text-black brand-item"
                  >
                    <p>
                      {{ itemChild.name }}
                    </p>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </section>

    <SocialMeta
      title="Dễ dàng tiếp cận hàng hiệu tại thiên đường mua sắm Muội Muội."
      description="Mua sắm đơn giản, tiện lợi tại Muội Muội cùng danh mục sản phẩm thời trang & làm đẹp từ các thương hiệu đẳng cấp hàng đầu thế giới."
    />
  </main>
</template>

<script>
import App from "@/Layouts/App.vue";
export default {
  layout: App,
  props: ["items"],

  data() {
    return {
      categories: [
        { key: "A" },
        { key: "B" },
        { key: "C" },
        { key: "D" },
        { key: "E" },
        { key: "F" },
        { key: "G" },
        { key: "H" },
        { key: "I" },
        { key: "J" },
        { key: "K" },
        { key: "L" },
        { key: "M" },
        { key: "N" },
        { key: "O" },
        { key: "P" },
        { key: "Q" },
        { key: "R" },
        { key: "S" },
        { key: "T" },
        { key: "U" },
        { key: "V" },
        { key: "W" },
        { key: "X" },
        { key: "Y" },
        { key: "Z" },
        { key: "#" },
      ],

      key: "",

      categoriesLv2: [],
    };
  },

  created() {
    this.getCategoriesLv2();
  },

  methods: {
    filterCategories(key) {
      this.key = key.key;
      // this.dataLv2Show = key.items;
    },

    getCategoriesLv2() {
      this.categoriesLv2 = this.items;
      this.setCategories();
    },

    setCategories() {
      this.categories = this.categories.map((category) => {
        if (category.key !== "#") {
          let items = this.categoriesLv2.filter(
            (x) => x.name.charAt(0) === category.key
          );
          category["items"] = items;
        } else {
          let items = this.categoriesLv2.filter(
            (x) => !this.categories.some((key) => key.key === x.name.charAt(0))
          );
          category["items"] = items;
        }
        return category;
      });
    },

    checkKey(category) {
      if (category.key === this.key || this.key === "") {
        return true;
      }

      return false;
    },
  },
};
</script>
<style lang="scss" scoped>
.brand-item {
  p {
    background-position: bottom left;
    background-size: 0% 2px;
    background-image: linear-gradient(#19191a, #19191a);
    padding-bottom: 2px;
    &:hover {
      background-size: 100% 2px;
    }

    display: inline;
    background-repeat: no-repeat;
    transition: all 200ms ease-in-out;
  }
}
</style>
