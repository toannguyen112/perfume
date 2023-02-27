<template>
  <section class="xl:mb-[77px] md:mb-[54px] mb-[39px]">
    <BreadCrumbPage :item="breadCrumb" />
    <div class="container">
      <h1
        class="p2 font-bold text-black xl:mb-[16px] md:mb-[12px] mb-[8px] xl:mt-[24px] md:mt-[17px] mt-[12px]"
      >
        Sale sốc
      </h1>
      <div class="grid grid-cols-12 xl:gap-[32px] md:gap-[22px] gap-[10px]">
        <CardSale
          v-for="(item, index) in items.data"
          :key="index"
          class="col-span-6 md:col-span-4"
          :item="item"
        />
      </div>
      <Pagination
        :data="items"
        class="xl:mt-[32px] md:mt-[22px] mt-[16px] justify-center"
      />
    </div>
  </section>
  <SocialMeta
    title="Muội Muội - Cẩm nang chăm sóc sắc đẹp"
    description="Muội Muội cung cấp những kiến thức bổ ích về thời trang, bí quyết làm đẹp và phong cách sống."
  />
</template>

<script>
import App from "../../Layouts/App.vue";
import CardSale from "../../Components/jam/CardSale.vue";
import Pagination from "../../Components/jam/Pagination.vue";
import BreadCrumbPage from "../../Components/jam/BreadCrumbPage.vue";
export default {
  components: { CardSale, Pagination, BreadCrumbPage },
  layout: App,
  props: ["items"],
  data() {
    return {
      breadCrumb: [
        {
          name: "Sale sốc",
          link: "sale.index",
        },
      ],
    };
  },
};
</script>
