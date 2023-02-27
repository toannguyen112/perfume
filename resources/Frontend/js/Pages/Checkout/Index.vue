<template>
  <section class="bg-gray-100 xl:pb-[56px] md:pb-[39px] pb-[28px]">
    <form @submit.prevent="submitForm">
      <BreadCrumbOrder />
      <div class="container">
        <div class="p1 font-bold text-black xl:my-[16px] md:my-[12px] my-[8px]">
          Đặt hàng
        </div>
        <div
          class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px] max-lg:gap-y-[22px] max-md:gap-y-[16px]"
        >
          <div class="lg:col-span-8 col-span-full">
            <div
              class="bg-white xl:px-[32px] md:px-[22px] px-[16px] xl:pt-[24px] md:pt-[17px] pt-[12px]"
            >
              <div class="lg:w-1/2">
                <div class="p2 font-bold text-black mb-[8px]">
                  Thông tin đặt hàng
                </div>
                <div
                  class="xl:space-y-[28px] lg:space-y-[20px] mb-[8px] xl:pb-[52px] md:pb-[36px] pb-[28px] max-lg:grid max-lg:grid-cols-2 max-md:grid-cols-1 max-lg:gap-y-[20px] max-lg:gap-x-[20px]"
                >
                  <div>
                    <JFieldSet
                      v-model="form.customer_name"
                      :field="{
                        rules: rules,
                        errors: errors,
                        type: 'text',
                        placeholder: 'Nhập họ và tên',
                        name: 'Họ và tên',
                        fieldName: 'customer_name',
                        label: 'Họ và tên',
                      }"
                      :contact-form="false"
                      :is-order-page="true"
                    />
                  </div>
                  <div>
                    <JFieldSet
                      v-model="form.phone"
                      :field="{
                        rules: rules,
                        errors: errors,
                        type: 'number',
                        placeholder: 'Nhập số điện thoại',
                        label: 'Số điện thoại',
                        fieldName: 'phone',
                        name: 'Số điện thoại',
                      }"
                      :contact-form="false"
                      :is-order-page="true"
                    />
                  </div>
                  <div>
                    <JFieldSet
                      v-model="form.email"
                      :field="{
                        rules: rules,
                        errors: errors,
                        type: 'email',
                        placeholder: 'Nhập email',
                        fieldName: 'email',
                        label: 'Email',
                        name: 'Email',
                      }"
                      :contact-form="false"
                      :is-order-page="true"
                    />
                  </div>
                  <div>
                    <JFieldSet
                      v-model="form.city"
                      :field="{
                        rules: rules,
                        errors: errors,
                        name: 'Tỉnh/ thành phố',
                        type: 'select_single',
                        label: 'Tỉnh/ thành phố',
                        fieldName: 'city',
                        placeholder: 'Nhập tên tỉnh/ thành phố',
                        options: citiesObject,
                      }"
                      :contact-form="false"
                      :is-order-page="true"
                    />
                  </div>
                  <div>
                    <JFieldSet
                      v-model="form.district"
                      :field="{
                        rules: rules,
                        errors: errors,
                        name: 'Quận/ huyện',
                        label: 'Quận/ huyện',
                        type: 'select_single',
                        placeholder: 'Chọn quận/ huyện',
                        fieldName: 'district',
                        options: districtsObject,
                      }"
                      :contact-form="false"
                      :is-order-page="true"
                    />
                  </div>
                  <div>
                    <JFieldSet
                      v-model="form.ward"
                      :field="{
                        rules: rules,
                        errors: errors,
                        name: 'Phường/ xã',
                        label: 'Phường/ xã',
                        type: 'select_single',
                        placeholder: 'Chọn phường/ xã',
                        fieldName: 'ward',
                        options: wardsObject,
                      }"
                      :contact-form="false"
                      :is-order-page="true"
                    />
                  </div>
                  <div>
                    <JFieldSet
                      v-model="form.shipping_address"
                      :field="{
                        rules: rules,
                        errors: errors,
                        name: 'Địa chỉ',
                        type: 'textarea',
                        label: 'Địa chỉ',
                        fieldName: 'shipping_address',
                        placeholder: 'Nhập số nhà, tên đường',
                      }"
                      :contact-form="false"
                      :is-order-page="true"
                      :is-address="true"
                    />
                  </div>
                  <div>
                    <JFieldSet
                      v-model="form.note"
                      :field="{
                        rules: rules,
                        errors: errors,
                        name: 'Ghi chú khi giao hàng',
                        type: 'textarea',
                        label: 'Ghi chú khi giao hàng',
                        fieldName: 'note',
                        placeholder: 'Nhập số nhà, tên đường',
                      }"
                      :contact-form="false"
                      :is-order-page="true"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div
              class="bg-white xl:pt-[32px] md:pt-[22px] pt-[16px] xl:pb-[24px] md:pb-[17px] pb-[12px] xl:px-[24px] md:px-[17px] px-[12px]"
            >
              <div>
                <JFieldSet
                  v-model="form.payment_method"
                  :field="{
                    rules: rules,
                    errors: errors,
                    label: 'Phương thức thanh toán',
                    type: 'radio_custom',
                    fieldName: 'payment_method',
                    options: [
                      {
                        id: 1,
                        label: 'Thanh toán tiền mặt khi nhận hàng (COD)',
                        icon: '/images/order/cod.jpg',
                      },
                      {
                        id: 2,
                        label: 'Chuyển khoản qua ngân hàng ',
                        icon: '/images/order/atm.jpg',
                      },
                      {
                        id: 3,
                        label: 'Thanh toán qua ví Momo',
                        icon: '/images/order/momo.jpg',
                      },
                      {
                        id: 4,
                        label: 'Thanh toán qua ví Paypal',
                        icon: '/images/order/paypal.jpg',
                      },
                    ],
                  }"
                  :contact-form="false"
                />
              </div>
            </div>
          </div>
          <div class="lg:col-span-4 col-span-full">
            <div class="sticky top-0">
              <div class="bg-white mb-[8px]">
                <div
                  class="flex max-lg:justify-between items-center xl:space-x-[12px] space-x-[4px] xl:py-[24px] md:py-[17px] py-[12px] xl:pl-[24px] md:pl-[17px] pl-[12px] xl:pr-[34px] md:pr-[17px] pr-[17px]"
                >
                  <div>
                    <div class="font-bold text-gray-800 p2">
                      Tóm tắt đơn hàng
                    </div>
                    <div class="display-flex">
                      <div class="font-medium text-gray-600">
                        <span
                          >Số lượng sản phẩm:
                          {{ cartData ? cartData.total_quantity : 0 }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="fix-btn">
                    <JButton
                      label="Sửa"
                      variant="secondary-square"
                      class="h-[40px]"
                      size="fix"
                      :href="route('checkout.cart.index')"
                    />
                  </div>
                </div>
                <!-- :class="isShow ? 'max-h-auto' : 'max-h-0'" -->
                <div
                  class="overflow-hidden duration-150 xl:px-[24px] md:px-[17px] px-[12px]"
                >
                  <div
                    v-if="cartData"
                    class="border-t border-dashed border-gray-400 flex flex-col space-y-[16px] xl:pt-[16px] md:pt-[12px] pt-[8px] xl:pb-[24px] md:pb-[17px] pb-[12px]"
                  >
                    <div
                      v-for="(item, index) in cartData.items"
                      :key="index"
                      class="grid grid-cols-12 md:gap-x-[22px] gap-x-[16px]"
                    >
                      <div
                        class="font-medium text-gray-800 hover:text-red-500 duration-150 mb-[4px] col-span-8"
                      >
                        <Link
                          class="font-medium text-gray-800 line-clamp-2"
                          :href="
                            route('product.show', {
                              productSlug: item.product_detail.slug,
                              productId: item.product_detail.id,
                            })
                          "
                          >{{ item.product_detail.name }}</Link
                        >
                        <div class="font-medium text-gray-500">
                          SL x {{ item.qty }}
                        </div>
                      </div>
                      <div class="col-span-4 font-medium text-right text-black">
                        {{ toMoney(item.product_detail.price) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  class="fix-mobile md:px-[17px] px-[12px] md:pb-[17px] pb-[12px]"
                >
                  <JButton
                    label="Sửa"
                    variant="secondary-square"
                    class="h-[40px] w-full"
                    size="fix"
                    :href="route('checkout.cart.index')"
                  />
                </div>
              </div>
              <div
                class="bg-white xl:py-[24px] md:py-[17px] py-[12px] xl:pl-[24px] md:pl-[17px] pl-[12px] xl:pr-[34px] md:pr-[22px] pr-[17px] mb-[8px]"
              >
                <div
                  class="xl:pb-[16px] md:pb-[12px] pb-[8px] border-b border-dashed border-gray-400 xl:mb-[16px] md:mb-[12px] mb-[8px] space-y-[8px]"
                >
                  <div class="flex items-center justify-between">
                    <div class="font-medium text-gray-800">Tạm tính</div>
                    <div
                      v-if="cartData"
                      class="font-medium text-right text-black"
                    >
                      {{ toMoney(cartData.total_sub_price) }}
                    </div>
                  </div>
                  <div
                    v-if="cartData"
                    class="flex items-center justify-between"
                  >
                    <div class="font-medium text-gray-800">Phí vận chuyển</div>
                    <div class="font-medium text-right text-black">
                      {{ toMoney(cartData.shipping_cost) }}
                    </div>
                  </div>
                </div>
                <div class="flex items-center justify-between">
                  <div class="font-medium text-gray-800">Thành tiền</div>
                  <div
                    v-if="cartData"
                    class="font-bold text-right text-black p2"
                  >
                    {{ toMoney(cartData.total_price) }}
                  </div>
                </div>
              </div>
              <div class="w-full">
                <JButton
                  label="THANH TOÁN"
                  variant="primary-submit"
                  class="w-full"
                  @click="submitForm"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
  <YouWillLike :list="suggest_products" />
  <SocialMeta
    title="Muội Muội - Thiên đường mỹ phẩm cao cấp."
    description="Muội Muội cung cấp sản phẩm chất lượng từ những thương hiệu uy tín, được tin dùng. Lấy niềm vui, sự hài lòng của khách hàng để làm động lực, chúng tôi không ngừng tìm kiếm các sản phẩm tốt nhất để mỗi khách hàng đều có thể trở nên tự tin và xinh đẹp hơn."
  />
</template>

<script>
import { validateForm } from "../../validator";
import App from "../../Layouts/App.vue";
import axios from "axios";
import BreadCrumbOrder from "../../Components/BreadCrumbOrder.vue";
import YouWillLike from "../../Components/YouWillLike.vue";
export default {
  components: {
    BreadCrumbOrder,
    YouWillLike,
  },
  layout: App,
  props: ["modelForm", "suggest_products"],
  data() {
    return {
      isShow: false,
      cartData: null,
      rules: this.modelForm.rules,

      form: {
        ...this.modelForm.fillable,
        payment_method: "1",
      },
      errors: {},
      cities: [],
      districts: [],
      wards: [],
      citiesObject: [],
      districtsObject: [],
      wardsObject: [],
      isLoading: false,
    };
  },
  watch: {
    "form.city"() {
      this.getDistrict();
    },
    "form.district"() {
      this.getWard();
    },
  },
  created() {
    this.getCity();
    this.getCart();
  },

  methods: {
    submitForm() {
      this.errors = validateForm(this.form, this.rules);
      if (Object.keys(this.errors).length > 0 || this.isLoading) {
        return false;
      }
      this.isLoading = true;
      axios
        .post(this.route("checkout.payment.checkout"), this.form)
        .then((res) => {
          this.isLoading = false;
          this.$bus.emit("updateCart", {
            redirect: this.route(
              "checkout.payment.tracking",
              res.data.order_number
            ),
          });
        });
    },
    showMore() {
      this.isShow = !this.isShow;
    },
    compactArrayOptions(arr) {
      let options = [];
      arr.forEach((element) => {
        options.push({
          id: element.code,
          label: element.name_with_type,
        });
      });
      return options;
    },
    async getCity() {
      const self = this;
      await axios.get(this.route("regions")).then(function (data) {
        self.cities = data.data;

        self.citiesObject = self.compactArrayOptions(self.cities.data);
      });
    },

    getCart() {
      axios.get(this.route(`checkout.cart.index`)).then((res) => {
        this.cartData = res.data;
      });
    },

    async getDistrict() {
      this.form.district = null;
      const city_id = this.form.city;
      const self = this;
      await axios
        .get(this.route("regions", { code: city_id }))
        .then(function (data) {
          self.districts = data.data;

          self.districtsObject = self.compactArrayOptions(self.districts.data);
        });
    },

    async getWard() {
      this.form.ward = null;
      const district_id = this.form.district;
      const self = this;
      await axios
        .get(this.route("regions", { code: district_id }))
        .then(function (data) {
          self.wards = data.data;

          self.wardsObject = self.compactArrayOptions(self.wards.data);
        });
    },
  },
};
</script>
<style lang="scss" scoped>
@media screen and (max-width: 1439px) {
  .fix-btn {
    @apply hidden;
  }
}
@media screen and (min-width: 1440px) {
  .display-flex {
    @apply flex items-center space-x-[16px];
  }
  .fix-btn {
    @apply block;
  }
  .fix-mobile {
    @apply hidden;
  }
}
</style>
