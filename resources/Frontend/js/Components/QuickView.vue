<template>
  <Modal
    class="modal-product sm:flex-col sm:flex sm:items-center sm:justify-center"
    :show="modelValue.target === 'modal-product'"
    custom-max-width="max-w-[1114px] w-full mx-auto lg:w-[90%] xl:w-full"
    @close="closeModal"
    @offBody="toggleBodyClass('removeClass', 'overflow-hidden')"
  >
    <div class="h-full p-2 bg-white shadow-xl sm:p-4">
      <div class="relative w-full h-full modal-content">
        <div
          class="absolute top-[-54px] -right-3 sm:top-[-62px] sm:-right-5 lg:-top-5 lg:right-[-62px] flex items-center justify-center w-10 h-10 cursor-pointer"
          @click="closeModal"
        >
          <JIcon name="close-circle" />
        </div>
        <div
          v-if="modelValue.item"
          class="h-full p-2 overflow-y-auto xl:p-4 modal-body"
        >
          <div class="sm:flex">
            <div
              class="sm:max-w-[250px] md:max-w-[310px] lg:max-w-[387px] w-full mb-6 sm:mb-0"
            >
              <div class="pb-[100%] relative mb-3">
                <JImage
                  :src="currentVariant.image_url || modelValue.item.image_url"
                  class="absolute inset-0 object-cover w-full h-full"
                />
              </div>
              <div
                v-if="modelValue.item.comments_count > 0"
                class="flex items-center justify-center"
              >
                <div
                  class="flex items-center space-x-2 ratings mr-2.5 text-black"
                >
                  <template v-for="idx in 5" :key="idx">
                    <JIcon
                      v-if="idx <= modelValue.item.rate_score"
                      name="star-active"
                      class="text-black star star-active"
                    />
                    <JIcon
                      v-else
                      name="star"
                      class="text-gray-700 star star-grey"
                    />
                  </template>
                </div>
                <span class="mt-0.5 font-medium text-gray-700 caption"
                  >({{ modelValue.item.comments_count }} đánh giá)</span
                >
              </div>
            </div>
            <div class="flex-1 sm:ml-8">
              <div class="pb-2">
                <h3 class="mb-1 font-bold text-gray-800 p2">
                  <span class="line-clamp-2">{{ modelValue.item.name }}</span>
                </h3>
                <span class="font-medium text-gray-700 caption"
                  >SKU: {{ currentVariant.sku }}</span
                >
                <div class="flex items-center">
                  <strong class="font-extrabold text-black p2">{{
                    currentVariant.formatted_price
                  }}</strong>
                  <span
                    v-if="old_price"
                    class="ml-2 font-medium text-black line-through p3"
                    >{{ currentVariant.formatted_old_price }}</span
                  >
                </div>
              </div>
              <!-- <div class="pb-2">
                <div class="mb-[18px]">
                  <strong class="block mb-2 text-black"
                    >Kích thước - trọng lượng</strong
                  >
                  <div class="flex flex-wrap">
                    <a
                      v-for="idx in 3"
                      :key="idx"
                      href="javascript:;"
                      class="flex items-center h-10 px-4 py-2 mb-2 mr-2 font-medium text-black border rounded-full hover:border-gray-800"
                      :class="
                        is_selected_size == idx
                          ? 'border-gray-800'
                          : 'border-gray-300'
                      "
                      @click="is_selected_size = idx"
                    >
                      <JPicture
                        src="/images/products/lipstick-thumb.png"
                        class="object-cover w-5 h-5"
                      />
                      <span class="ml-2">0.12 oz/ 3.5 g</span>
                    </a>
                  </div>
                </div>
                <div class="block">
                  <strong class="block text-black mb-1.5">Màu sắc</strong>
                  <div class="mb-3">
                    <span class="block mb-0.5 font-medium text-black"
                      >Metallic finish</span
                    >
                    <div class="flex flex-wrap items-center">
                      <a
                        v-for="idx in 6"
                        :key="idx"
                        href="javascript:;"
                        class="w-10 h-10 p-[3px] mr-1 transition border border-transparent rounded-full hover:border-gray-400"
                        :class="is_selected_color_1 == idx && 'border-black'"
                        @click="is_selected_color_1 = idx"
                      >
                        <span
                          class="block h-full rounded-full"
                          :style="`background-color: #ea4a4c;`"
                        ></span>
                      </a>
                    </div>
                  </div>
                  <div class="mb-3">
                    <span class="block mb-0.5 font-medium text-black"
                      >Metallic finish</span
                    >
                    <div class="flex flex-wrap items-center">
                      <template v-for="idx in 10" :key="idx">
                        <a
                          v-if="idx < 10"
                          href="javascript:;"
                          class="w-10 h-10 p-[3px] mr-1 transition border border-transparent rounded-full hover:border-gray-400"
                          :class="is_selected_color_2 == idx && 'border-black'"
                          @click="is_selected_color_2 = idx"
                        >
                          <span
                            class="block h-full rounded-full"
                            :style="`background-color: #ea4a4c;`"
                          ></span>
                        </a>
                        <a
                          v-else
                          href="javascript:;"
                          class="relative w-10 h-10 p-[3px] mr-1 transition border border-transparent rounded-full hover:border-gray-400"
                          :class="is_selected_color_2 == idx && 'border-black'"
                          @click="is_selected_color_2 = idx"
                        >
                          <span
                            class="absolute rotate-45 w-0.5 h-[26px] bg-black top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                          ></span>
                          <span
                            class="block h-full rounded-full"
                            :style="`background-color: #ea4a4c;`"
                          ></span>
                        </a>
                      </template>
                    </div>
                  </div>
                  <div>
                    <span class="block mb-0.5 font-medium text-black"
                      >Metallic finish</span
                    >
                    <div class="flex items-center">
                      <a
                        href="javascript:;"
                        class="w-10 h-10 p-[3px] mr-1 transition border border-transparent rounded-full hover:border-gray-400"
                        :class="is_selected_color_3 == idx && 'border-black'"
                        v-for="idx in 6"
                        :key="idx"
                        @click="is_selected_color_3 = idx"
                      >
                        <span
                          class="block h-full rounded-full"
                          :style="`background-color: #ea4a4c;`"
                        ></span>
                      </a>
                    </div>
                  </div>
                </div>
              </div> -->
              <div class="pb-2">
                <BlocksProductOptions
                  :product="modelValue.item"
                  @update-variant="updateVariant($event)"
                />
              </div>
              <div class="flex items-center mb-8 xl:mb-[66px]">
                <strong class="mr-6 text-black">Số lượng</strong>
                <QuantityInput
                  :number="qty"
                  @change-quanity="onChangeQuantity"
                />
              </div>
              <div class="md:flex md:justify-end">
                <JButton
                  label="THÊM VÀO GIỎ HÀNG"
                  variant="primary-square"
                  size="lg"
                  class="h-[50px] sm:max-w-[310px] w-full"
                  @click="addToCart()"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Modal>
</template>
<script>
import Modal from "../Components/jam/Modal.vue";
import QuantityInput from "../Components/jam/QuantityInput.vue";
import BlocksProductOptions from "@/Components/blocks/ProductOptions.vue";
import axios from "axios";

export default {
  components: {
    Modal,
    QuantityInput,
    BlocksProductOptions,
  },
  props: ["modelValue", "onUpdate:modelValue"],
  emits: ["update:modelValue"],

  data() {
    return {
      currentVariant: {},
      currentOptions: [],
      currentOptionsHover: [],
      qty: 1,
    };
  },

  watch: {
    modelValue: {
      deep: true,
      handler(value) {
        if (value.item) {
          this.setCurrentOptions();
          this.setCurrentVariant();
        }
      },
    },
  },

  methods: {
    onChangeQuantity(quantity) {
      this.qty = quantity;
    },
    closeModal() {
      this.$emit("update:modelValue", {
        ...this.modelValue,
        target: undefined,
      });
      this.toggleBodyClass("removeClass", "overflow-hidden");
    },

    toggleBodyClass(addRemoveClass, className) {
      const el = document.body;
      if (addRemoveClass === "addClass") {
        el.classList.add(className);
      } else {
        el.classList.remove(className);
      }
    },

    setCurrentOptions() {
      this.currentOptions = [
        ...this.modelValue.item.variants.find((x) => x.selected === true)
          .options,
      ];
      this.currentOptionsHover = [
        ...this.modelValue.item.variants.find((x) => x.selected === true)
          .options,
      ];
    },

    setCurrentVariant() {
      this.currentVariant =
        this.modelValue.item.variants &&
        this.modelValue.item.variants.length > 0
          ? this.modelValue.item.variants.find((x) => x.selected)
          : {};
    },

    setCurrentVariantWithVariantId(variant_id) {
      this.currentVariant =
        this.modelValue.item.variants &&
        this.modelValue.item.variants.length > 0
          ? this.modelValue.item.variants.find((x) => x.id === variant_id)
          : {};
    },

    updateVariant(id) {
      this.setCurrentVariantWithVariantId(id);
    },

    async addToCart() {
      const { data } = await axios.post(this.route("checkout.cart.add"), {
        product_id: this.modelValue.item.id,
        variant_id: this.currentVariant.id,
        quantity: this.qty,
      });

      if (data) {
        this.closeModal();
        this.$bus.emit("updateCart", data);
        this.$bus.emit("cartSuccess", data);
      }
    },
  },
};
</script>
