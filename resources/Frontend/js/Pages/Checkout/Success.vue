<template>
  <section
    class="xl:mt-[48px] md:mt-[34px] mt-[24px] xl:mb-[64px] md:mb-[48px] mb-[32px]"
  >
    <div class="container">
      <div class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px]">
        <div
          class="xl:col-span-8 md:col-span-10 col-span-full xl:col-start-3 md:col-start-2"
        >
          <div class="xl:mb-[32px] md:mb-[22px] mb-[16px]">
            <div
              class="p2 font-extrabold text-black text-center xl:mb-[16px] md:mb-[12px] mb-[8px]"
            >
              ĐẶT HÀNG THÀNH CÔNG
            </div>
            <div class="font-medium text-center text-gray-600">
              Cảm ơn Quý khách hàng đã tin dùng sử dụng<br />
              sản phẩm của Muội Muội
            </div>
          </div>
          <div>
            <div class="xl:mb-[32px] md:mb-[22px] mb-[16px]">
              <div class="p3 font-bold text-black mb-[12px]">
                Thông tin khách hàng
              </div>
              <div class="bg-gray-100 xl:p-[24px] md:p-[17px] p-[12px]">
                <div
                  class="flex md:flex-row flex-col md:items-center xl:space-x-[32px] md:space-x-[22px] max-md:space-y-[2px] md:mb-[12px] mb-[12px]"
                >
                  <div
                    class="font-medium text-gray-700 w-[200px] flex-shrink-0"
                  >
                    Họ và tên
                  </div>
                  <div
                    v-if="order.customer_name"
                    class="font-semibold text-black"
                  >
                    {{ order.customer_name }}
                  </div>
                </div>
                <div
                  class="flex md:flex-row flex-col md:items-center xl:space-x-[32px] md:space-x-[22px] max-md:space-y-[2px] md:mb-[12px] mb-[12px]"
                >
                  <div
                    class="font-medium text-gray-700 w-[200px] flex-shrink-0"
                  >
                    Số điện thoại
                  </div>
                  <div v-if="order.phone" class="font-semibold text-black">
                    {{ order.phone }}
                  </div>
                </div>
                <div
                  class="flex md:flex-row flex-col md:items-center xl:space-x-[32px] md:space-x-[22px] max-md:space-y-[2px] md:mb-[12px] mb-[12px]"
                >
                  <div
                    class="font-medium text-gray-700 w-[200px] flex-shrink-0"
                  >
                    Email
                  </div>
                  <div v-if="order.email" class="font-semibold text-black">
                    {{ order.email }}
                  </div>
                </div>
              </div>
            </div>
            <div class="xl:mb-[32px] md:mb-[22px] mb-[16px]">
              <div class="p3 font-bold text-black mb-[12px]">
                Thông tin đặt hàng
              </div>
              <div class="bg-gray-100 xl:p-[24px] md:p-[17px] p-[12px]">
                <div
                  class="flex md:flex-row flex-col md:items-center xl:space-x-[32px] md:space-x-[22px] max-md:space-y-[2px] md:mb-[12px] mb-[12px]"
                >
                  <div
                    class="font-medium text-gray-700 w-[200px] flex-shrink-0"
                  >
                    Mã đơn hàng
                  </div>
                  <div
                    v-if="order.order_number"
                    class="font-semibold text-black"
                  >
                    {{ order.order_number }}
                  </div>
                </div>
                <div
                  class="flex md:flex-row flex-col md:items-center xl:space-x-[32px] md:space-x-[22px] max-md:space-y-[2px] md:mb-[12px] mb-[12px]"
                >
                  <div
                    class="font-medium text-gray-700 w-[200px] flex-shrink-0"
                  >
                    Địa chỉ nhận hàng
                  </div>
                  <div
                    v-if="order.shipping_address"
                    class="font-semibold text-black"
                  >
                    {{ order.shipping_address }}
                  </div>
                </div>
                <div
                  class="flex md:flex-row flex-col md:items-center xl:space-x-[32px] md:space-x-[22px] max-md:space-y-[2px] md:mb-[12px] mb-[12px]"
                >
                  <div
                    class="font-medium text-gray-700 w-[200px] flex-shrink-0"
                  >
                    Phương thức thanh toán
                  </div>
                  <div
                    v-if="order.formatted_payment_method"
                    class="font-semibold text-black"
                  >
                    {{ order.formatted_payment_method }}
                  </div>
                </div>
                <div
                  class="flex md:flex-row flex-col md:items-center xl:space-x-[32px] md:space-x-[22px] max-md:space-y-[2px] md:mb-[12px] mb-[12px]"
                >
                  <div
                    class="font-medium text-gray-700 w-[200px] flex-shrink-0"
                  >
                    Ghi chú
                  </div>
                  <div v-if="order.note" class="font-semibold text-black">
                    {{ order.note }}
                  </div>
                  <div v-else class="font-semibold text-black">Không có</div>
                </div>
              </div>
            </div>
            <div class="xl:mb-[32px] md:mb-[22px] mb-[16px]">
              <div class="p3 font-bold text-black mb-[12px]">
                Sản phẩm đã đặt
              </div>
              <div class="xl:space-y-[12px] md:space-y-[10px] space-y-[8px]">
                <div
                  v-for="(item, index) in order.line_items"
                  :key="index"
                  class="flex items-center justify-between space-x-[8px] pb-[12px] border-b border-gray-300 last:border-b-0"
                >
                  <div
                    class="flex xl:space-x-[16px] md:space-x-[12px] space-x-[8px]"
                  >
                    <div class="flex-shrink-0">
                      <div class="aspect-[1/1] max-h-[112px] max-w-[112px]">
                        <JImage
                          :src="item.image_url"
                          class="object-cover w-full h-full"
                          :alt="item.title"
                        />
                      </div>
                    </div>
                    <div class="space-y-[4px]">
                      <div
                        class="font-semibold text-gray-800 caption line-clamp-2"
                      >
                        {{ item.title }}
                      </div>
                      <div class="font-medium text-gray-500">
                        SL x {{ item.quantity }}
                      </div>
                      <div class="font-bold text-black md:hidden">
                        {{ toMoney(item.price * item.quantity) }}
                      </div>
                    </div>
                  </div>
                  <div
                    class="flex-shrink-0 hidden font-bold text-black md:block"
                  >
                    {{ toMoney(item.price * item.quantity) }}
                  </div>
                </div>
              </div>
            </div>
            <div
              class="w-full h-[1px] bg-gray-300 xl:mb-[32px] md:mb-[22px] mb-[16px]"
            ></div>
            <div>
              <div class="flex items-center justify-between mb-[10px]">
                <div class="font-medium text-gray-600">Tổng tiền hàng</div>
                <div class="font-medium text-gray-700">
                  {{ toMoney(order.subtotal_price) }}
                </div>
              </div>
              <div class="flex items-center justify-between mb-[10px]">
                <div class="font-medium text-gray-700">Phí vận chuyển</div>
                <div class="font-medium text-gray-700">
                  {{ toMoney(order.total_shipping) }}
                </div>
              </div>
              <div class="flex items-center justify-between mb-[10px]">
                <div class="font-medium text-gray-600">
                  Tổng tiền cần thanh toán
                </div>
                <div class="font-bold text-red-400 p2">
                  {{ toMoney(order.total_price) }}
                </div>
              </div>
              <div
                class="w-full h-[1px] xl:mt-[44px] md:mt-[34px] mt-[24px] xl:mb-[32px] md:mb-[22px] mb-[16px] bg-gray-300"
              ></div>
              <div class="flex justify-center">
                <JButton
                  :href="route('home.index')"
                  label="TIẾP TỤC MUA SẮM"
                  variant="primary-square"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <SocialMeta
    title="Muội Muội - Thiên đường mỹ phẩm cao cấp"
    description="Muội Muội cung cấp sản phẩm chất lượng từ những thương hiệu uy tín, được tin dùng. Lấy niềm vui, sự hài lòng của khách hàng để làm động lực, chúng tôi không ngừng tìm kiếm các sản phẩm tốt nhất để mỗi khách hàng đều có thể trở nên tự tin và xinh đẹp hơn."
  />
</template>
<script>
import App from "../../Layouts/App.vue";
export default {
  props: ["order"],
  layout: App,
};
</script>
