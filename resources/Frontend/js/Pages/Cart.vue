<template>
  <section class="bg-gray-100 xl:pb-[56px] md:pb-[39px] pb-[28px]">
    <BreadCrumbOrder />
    <div class="container">
      <div
        v-if="cartData"
        class="p1 font-bold text-black xl:mb-[16px] md:mb-[12px] mb-[8px] xl:mt-[16px] md:mt-[12px] mt-[8px]"
      >
        Giỏ hàng ({{ cartData.total_quantity }})
      </div>
      <div class="grid grid-cols-12 xl:gap-[32px] md:gap-[22px] gap-[16px]">
        <div class="xl:col-span-8 col-span-full">
          <div
            class="xl:p-[24px] md:p-[17px] p-[12px] bg-white xl:mb-[16px] md:mb-[12px] mb-[8px]"
          >
            <div
              class="bg-[#F2FAF3] xl:p-[16px] md:p-[12px] p-[8px] md:flex justify-between max-md:space-y-[10px]"
            >
              <div>
                <div class="flex items-center space-x-[4px] mb-[4px]">
                  <div>
                    <JIcon name="ship" />
                  </div>
                  <div class="font-bold text-gray-800 p3">Phí vận chuyển</div>
                </div>
                <div class="font-medium text-gray-700">
                  Miễn phí vận chuyển cho đơn hàng trên 700k!
                </div>
              </div>
              <div
                v-if="cartData && cartData.total_sub_price < shipping_cost"
                class="md:self-end"
              >
                <Link
                  href="/"
                  class="px-[20px] py-[14px] bg-transparent border border-gray-500 rounded-sm inline-block max-md:w-full btn-cart text-gray-800 hover:text-white"
                >
                  <div
                    class="flex items-center max-md:justify-center space-x-[9px]"
                  >
                    <div>
                      <JIcon name="shop" />
                    </div>
                    <div class="font-bold btn-md">Mua thêm</div>
                  </div>
                </Link>
              </div>
            </div>
          </div>
          <div>
            <div
              class="grid grid-cols-8 xl:gap-x-[32px] md:gap-x-[22px] xl:pl-[24px] md:pl-[17px] pl-[12px] xl:pr-[22px] md:pr-[17px] pr-[12px] py-[16px] bg-white mb-[2px]"
            >
              <div
                class="md:flex justify-between md:col-span-6 col-span-full xl:pr-[18px] md:pr-[12px] pr-[9px]"
              >
                <div class="font-bold text-gray-800 p4">Sản phẩm</div>
                <div class="hidden font-bold text-gray-800 p4 md:block">
                  Số lượng
                </div>
              </div>
              <div
                class="items-center col-span-2 space-x-[33px] xl:pl-[22px] lg:pl-[16px] justify-between md:flex hidden"
              >
                <div class="font-bold text-gray-800 p4">Tổng cộng</div>
                <div class="font-bold text-gray-800 p4">Xóa</div>
              </div>
            </div>
            <div v-if="cartData && cartData.items.length > 0">
              <CartItem
                v-for="(item, index) in cartData.items"
                :key="index"
                :item="item"
                :item-quantity="item.quantity"
              />
            </div>
            <CartEmpty v-else />
          </div>
        </div>
        <div v-if="cartData" class="xl:col-span-4 col-span-full">
          <div class="sticky top-0">
            <div
              class="bg-white xl:py-[24px] md:py-[17px] py-[12px] xl:pl-[24px] md:pl-[17px] pl-[12px] xl:pr-[34px] md:pr-[24px] pr-[17px] mb-[8px]"
            >
              <div class="pb-[12px] border-b border-dashed border-gray-400">
                <div class="font-bold text-black p2">Tóm tắt đơn hàng</div>
                <div>
                  <span class="font-bold text-gray-600 p3">Số lượng: </span
                  ><span class="font-medium text-gray-600"
                    >{{ cartData.total_quantity }}
                  </span>
                </div>
              </div>
              <div class="pt-[12px]">
                <div class="flex items-center justify-between">
                  <div class="font-medium text-gray-800">Tổng tiền hàng</div>
                  <div class="font-bold text-black p1">
                    {{ toMoney(cartData.total_sub_price) }}
                  </div>
                </div>
              </div>
            </div>
            <div class="w-full">
              <JButton
                :href="route('checkout.order.index')"
                label="THANH TOÁN"
                variant="primary-submit"
                class="w-full"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <ProductYouWillLike :items="suggest_products" />
  <SocialMeta
    title="Muội Muội - Thiên đường mỹ phẩm cao cấp."
    description="Muội Muội cung cấp sản phẩm chất lượng từ những thương hiệu uy tín, được tin dùng. Lấy niềm vui, sự hài lòng của khách hàng để làm động lực, chúng tôi không ngừng tìm kiếm các sản phẩm tốt nhất để mỗi khách hàng đều có thể trở nên tự tin và xinh đẹp hơn."
  />
</template>

<script>
import axios from "axios";
import CartItem from "../Components/CartItem.vue";
import CartEmpty from "../Components/CartEmpty.vue";
import App from "../Layouts/App.vue";
import ProductYouWillLike from "../Components/ProductYouWillLike.vue";
import BreadCrumbOrder from "../Components/BreadCrumbOrder.vue";

export default {
  components: {
    ProductYouWillLike,
    BreadCrumbOrder,
    CartItem,
    CartEmpty,
  },
  layout: App,
  props: ["suggest_products"],
  data() {
    return {
      cartData: null,
      shipping_cost: 700000,
    };
  },
  created() {
    this.getCart();
  },
  mounted() {
    const self = this;
    this.$bus.on("updateCart", (data) => {
      self.cartData = data;
    });
  },
  methods: {
    async getCart() {
      const { data } = await axios.get(this.route(`checkout.cart.index`));
      if (data) {
        this.cartData = data;
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.btn-cart {
  @apply overflow-hidden relative z-10;
  transition: all 0.4s ease;
  display: -ms-flexbox;
  -ms-flex-direction: row;
  -ms-flex-align: center;
  -ms-flex-pack: center;
  &::after {
    content: "";
    @apply w-0 h-0 absolute right-0 bottom-[-10px] z-[-1] bg-red-500;
    transition: all 0.6s ease;
    border-radius: 100% 0% 0% 100% / 100% 30% 70% 0%;
  }
  &::before {
    content: "";
    @apply w-full h-full absolute top-0 left-0 z-[-2] bg-transparent;
  }
  &:hover,
  &:focus {
    @apply border-red-500;
    &::after {
      @apply w-[150%] h-[250px] right-0;
    }
  }
}
</style>
