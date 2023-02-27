<template>
  <div class="categories-content">
    <div class="product-content">
      <div class="flex flex-wrap -mx-1 xl:-mx-3">
        <template v-for="(item, index) of list.data" :key="index">
          <ProductCard
            class="w-1/2 px-1 pb-2 sm:w-1/3 lg:w-1/4 2xl:w-1/5 xl:px-3 xl:pb-6"
            :item="item"
            @setTargetModal="setModal"
          />
        </template>
      </div>
      <QuickView v-model="modalData" />
    </div>
  </div>
</template>

<script>
import ProductCard from "../ProductCard.vue";
import QuickView from "../../Components/QuickView.vue";

export default {
  components: {
    ProductCard,
    QuickView,
  },
  props: ["list"],

  data() {
    return {
      modalData: {},
    };
  },

  methods: {
    setModal(data) {
      this.modalData = data;
    },
  },
};
</script>
