<template>
  <div
    class="xl:py-[16px] md:py-[12px] py-[8px] xl:px-[24px] md:px-[17px] px-[12px] bg-white mb-[2px] grid grid-cols-8 xl:gap-x-[32px] md:gap-x-[22px]"
  >
    <div
      class="md:col-span-6 col-span-full flex items-center justify-between xl:space-x-[38px] md:space-x-[24px]"
    >
      <div
        class="flex md:items-center xl:space-x-[16px] md:space-x-[12px] space-x-[8px]"
      >
        <Link
          v-if="item.product_detail"
          :href="
            route('product.show', {
              productSlug: item.product_detail.slug,
              productId: item.product_detail.id,
            })
          "
          class="aspect-[1/1] max-w-[108px] max-h-[108px] flex-shrink-0"
        >
          <JImage
            :src="
              item.product_detail.image_url
                ? item.product_detail.image_url
                : '/images/cover.jpg'
            "
            :alt="item.product_detail.name"
            class="object-cover w-full h-full"
          />
        </Link>
        <div class="space-y-[4px]">
          <Link
            v-if="item.product_detail"
            :href="
              route('product.show', {
                productSlug: item.product_detail.slug,
                productId: item.product_detail.id,
              })
            "
            class="flex-shrink-0 font-semibold text-gray-800 duration-150 hover:text-red-400 line-clamp-2 caption"
          >
            {{ item.product_detail.name }}
          </Link>
          <div v-if="item.product_detail.price" class="font-bold text-black">
            {{ toMoney(item.product_detail.price) }}
          </div>
          <div class="mt-[4px] text-[14px] leading-[150%] text-gray-500">
            <div
              v-for="(option, index) in item.product_detail.option_values"
              :key="index"
            >
              {{ option }}
            </div>
          </div>

          <QuantityInput
            :number="quantity"
            class="md:hidden"
            :class="isLoading ? 'pointer-events-none' : ''"
            @change-quanity="updateQuantity"
          />
          <div class="font-bold text-black md:hidden">
            <span>Tổng cộng: </span>
            <span> {{ toMoney(item.total_price) }}</span>
          </div>
          <div
            class="md:hidden inline-block font-medium text-red-400 duration-150 cursor-pointer hover:text-gray-800 after:block after:h-[1px] after:w-full after:bg-red-400 hover:after:bg-gray-800 after:mt-[-3px]"
            @click="deleteItem()"
          >
            Xóa
          </div>
        </div>
      </div>
      <div class="flex-shrink-0 hidden md:block">
        <QuantityInput
          :class="isLoading ? 'pointer-events-none' : ''"
          :number="quantity"
          @change-quanity="updateQuantity"
        />
      </div>
    </div>
    <div
      class="col-span-2 items-center justify-between space-x-[32px] xl:pl-[22px] lg:pl-[16px] md:flex hidden"
    >
      <div class="font-bold text-black">
        {{ toMoney(item.total_price) }}
      </div>
      <div
        class="flex-shrink-0 font-medium text-red-400 duration-150 cursor-pointer hover:text-gray-800 after:block after:h-[1px] after:w-full after:bg-red-400 hover:after:bg-gray-800 after:mt-[-3px]"
        @click="deleteItem()"
      >
        Xóa
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import QuantityInput from "../Components/jam/QuantityInput.vue";

export default {
  components: { QuantityInput },

  props: ["item"],
  data() {
    return {
      quantity: this.item.qty,
      isLoading: false,
    };
  },
  methods: {
    async updateQuantity(quantity) {
      if (this.isLoading) return;
      this.isLoading = true;

      const { data } = await axios.put(
        this.route("checkout.cart.update", {
          rowId: this.item.rowId,
          quantity,
        })
      );

      if (data) {
        this.$bus.emit("updateCart", data);
      }
      this.isLoading = false;
    },
    async deleteItem() {
      const { data } = await axios.delete(
        this.route("checkout.cart.destroy", { id: this.item.rowId })
      );

      if (data) {
        this.$bus.emit("updateCart", data);
      }
    },
  },
};
</script>
