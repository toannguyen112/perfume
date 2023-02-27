<template>
  <main>
    <Breadcrumb :item="item" class="mb-2" />
    <section
      class="border-b border-gray-300 product-top xl:pb-[48px] md:pb-[34px] pb-[24px]"
    >
      <div class="container">
        <div class="md:grid md:grid-cols-12 md:gap-x-8 xl:mx-[70px]">
          <div
            class="mb-4 md:col-span-6 lg:col-span-7 xl:col-span-6 md:mb-0 lg:mr-[28px] xl:mr-0"
          >
            <Gallery
              v-if="galleryData && galleryData.length > 0"
              :name="item.name"
              :images="galleryData"
              @location-image="currentImage = $event"
            />
            <div
              v-else
              class="aspect-w-1 aspect-h-1 xl:mr-[16px] xl:mb-[58px] xl:ml-[88px]"
            >
              <JImage
                v-if="currentVariant.images_url"
                :url="currentVariant.images_url"
                class="object-cover w-full h-full"
                alt="placeholder"
              />
              <JImage
                v-else
                url="/images/cover.jpg"
                alt="placeholder"
                class="object-contain"
              />
            </div>
          </div>
          <div class="md:col-span-6 lg:col-span-5 xl:col-span-6">
            <div class="2xl:pl-6">
              <div
                class="xl:pb-[20px] md:pb-[14px] pb-[10px] border-b border-gray-400 border-dashed"
              >
                <Link
                  v-if="item.brand"
                  :href="route('products.index', { slug: item.brand.slug })"
                  class="block mb-1 font-bold text-black underline underline-offset-2 text-decoration-gray-800"
                  >{{ item.brand.name }}</Link
                >
                <h3 class="mb-1 font-bold text-black p3">
                  <span class="line-clamp-2">{{ item.name }}</span>
                </h3>
                <div v-if="comment.total > 0" class="flex items-center mb-3">
                  <!-- <span
                    v-if="currentVariant.sku"
                    class="block mb-1 font-medium text-gray-700 lg:mb-0 caption"
                    >SKU: {{ currentVariant.sku }}</span
                  >
                  <span
                    v-if="comment.total > 0 && currentVariant.sku"
                    class="hidden lg:block w-px h-[13px] mx-4 bg-black"
                  ></span> -->
                  <div
                    v-if="comment.total > 0"
                    class="flex lg:mb-0.5 items-center mb-1 space-x-2 ratings mr-2.5"
                  >
                    <template v-for="idx in 5" :key="idx">
                      <JIcon
                        v-if="idx <= comment.rateScore"
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
                  <span
                    v-if="comment.total > 0"
                    class="block font-medium text-gray-700 caption"
                    >({{ comment.total }} đánh giá)</span
                  >
                </div>
                <div class="flex items-center">
                  <strong
                    v-if="currentVariant.price"
                    class="font-extrabold text-black p2"
                    >{{ currentVariant.formatted_price }}</strong
                  >
                  <span
                    v-if="currentVariant.old_price"
                    class="ml-2 font-medium text-black line-through p3"
                    >{{ currentVariant.formatted_old_price }}</span
                  >
                </div>
                <!-- <div class="font-[14px] leading-[120%]">
                  {{ currentVariant.title }}
                </div> -->
              </div>
              <div
                class="xl:py-[20px] md:py-[14px] py-[10px] border-b border-gray-400 border-dashed"
              >
                <BlocksProductOptions
                  :product="item"
                  :current-variant="currentVariant"
                  @update-variant="updateVariant($event)"
                />
                <a
                  v-if="
                    item.size_guide &&
                    (item.size_guide.guide ||
                      item.size_guide.image ||
                      item.size_guide.table)
                  "
                  href="javascript:;"
                  class="flex items-center mt-3"
                  data-target="modal-clothing"
                  @click="openModal($event.target)"
                >
                  <JIcon name="size" />
                  <span
                    class="ml-3 font-semibold text-red-500 transition-colors hover:text-black"
                    >Hướng dẫn kích thước</span
                  >
                </a>
                <Modal
                  class="modal-clothing md:flex-col md:flex md:items-center md:justify-center"
                  :show="modalSelected === 'modal-clothing'"
                  custom-max-width="max-w-[886px] mx-auto w-full"
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
                      <div class="h-full p-2 overflow-y-auto xl:p-4 modal-body">
                        <h3 class="font-bold text-black p2 mb-2.5 text-center">
                          Hướng dẫn kích thước(cm)
                        </h3>
                        <div
                          class="mb-3 overflow-x-auto size-table whitespace-nowrap"
                        >
                          <div class="" v-html="item.size_guide.table"></div>
                        </div>
                        <p
                          class="pb-3 mb-3 font-medium text-gray-600 border-b border-gray-300 caption"
                        >
                          *Dữ liệu này có được bằng cách đo thủ công sản phẩm ,
                          các phép đo có thể bị thay đổi 1-2 CM
                        </p>
                        <div class="md:flex">
                          <div class="max-w-[333px] mx-auto md:order-2">
                            <JPicture :src="item.size_guide.image" />
                          </div>
                          <div class="flex-1 lg:mr-6">
                            <h4 class="mb-3 font-bold text-black">
                              Cách đo kích thước của sản phẩm
                            </h4>
                            <div
                              class="guide-detail caption"
                              v-html="item.size_guide.guide"
                            ></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </Modal>
              </div>
              <div
                class="flex items-center xl:py-[20px] md:py-[14px] py-[10px]"
              >
                <strong class="mr-6 text-black">Số lượng</strong>
                <QuantityInput
                  :number="quantity"
                  @change-quanity="changeQuantity"
                />
              </div>
              <div
                class="xl:flex xl:space-x-2.5 md:space-y-2.5 space-y-1.5 xl:space-y-0"
              >
                <JButton
                  label="THÊM VÀO GIỎ HÀNG"
                  variant="primary"
                  size="lg"
                  class="h-[50px] w-full xl:w-1/2 rounded-full"
                  @click="addToCart()"
                />
                <JButton
                  label="MUA NGAY"
                  variant="secondary"
                  size="lg"
                  class="h-[50px] w-full xl:w-1/2 rounded-full"
                  @click="buyNow()"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="product-info">
      <div class="container">
        <div class="grid grid-cols-12">
          <div
            class="border-b border-gray-300 col-span-full lg:col-span-10 lg:col-start-2"
          >
            <div
              v-if="
                item.specs &&
                item.specs.NOT_HUONG &&
                item.specs.NOT_HUONG.length > 0
              "
              class="grid grid-cols-12 xl:py-[32px] md:py-[22px] py-[16px] border-b border-gray-300 lg:grid-cols-10 lg:gap-x-8"
            >
              <div class="col-span-full lg:col-span-2">
                <h3 class="md:mb-3 mb-1.5 font-bold text-black p3 lg:mb-0">
                  NỐT HƯƠNG
                </h3>
              </div>
              <div
                class="col-span-full lg:col-span-8 space-y-[16px] text-black"
              >
                <div v-for="(incense, idx) of item.specs.NOT_HUONG" :key="idx">
                  <div class="mb-2">
                    <strong>
                      {{ incense.name }}
                    </strong>
                  </div>
                  <div
                    class="relative flex flex-1 overflow-x-auto scroll-smooth product-info-scroll text-[14px] leading-[120%]"
                  >
                    <div
                      v-for="(fg, index) of incense.nodes"
                      :key="index"
                      class="flex flex-col items-center p-2 w-[33.33%] shrink-0"
                    >
                      <JPicture
                        v-if="fg.image_url"
                        :src="fg.image_url"
                        class="mb-1 lg:w-[150px] lg:h-[150px] w-[120px] h-[120px] object-cover rounded-full"
                      />
                      <div class="text-center mt-[6px]">{{ fg.name }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div
              v-if="hightLights && hightLights.length > 0"
              class="grid grid-cols-12 xl:py-[32px] md:py-[22px] py-[16px] border-b border-gray-300 lg:grid-cols-10 lg:gap-x-8"
            >
              <div class="col-span-full lg:col-span-2">
                <h3
                  class="md:mb-3 mb-1.5 font-bold text-black p3 lg:mb-0 uppercase"
                >
                  Highlights
                </h3>
              </div>
              <div
                class="col-span-full lg:col-span-8 space-y-[16px] text-[14px] leading-[120%] text-black"
              >
                <div>
                  <div
                    class="grid md:grid-cols-3 grid-cols-2 xl:gap-[32px] md:gap-[22px] gap-[16px]"
                  >
                    <template v-for="(hightLight, index) in hightLights">
                      <div
                        v-if="hightLight.image_url"
                        :key="index"
                        class="flex md:space-x-[12px] space-x-[8px] items-center"
                      >
                        <JPicture
                          v-if="hightLight.image_url"
                          :src="hightLight.image_url"
                          class="lg:w-[48px] lg:h-[48px] w-[40px] h-[40px] object-cover rounded-full"
                        />
                        <div>
                          {{ hightLight.name }}
                        </div>
                      </div>
                    </template>
                  </div>
                </div>
              </div>
            </div>
            <!-- SON -->
            <div
              v-if="
                item.specs &&
                item.specs.THONG_SO &&
                item.specs.THONG_SO.length > 0
              "
              class="grid grid-cols-12 xl:py-[32px] md:py-[22px] py-[16px] border-b border-gray-300 lg:grid-cols-10 lg:gap-x-8"
            >
              <div class="col-span-full lg:col-span-2">
                <h3 class="md:mb-3 mb-1.5 font-bold text-black p3 lg:mb-0">
                  THÔNG SỐ <br class="hidden lg:block" />SẢN PHẨM
                </h3>
              </div>
              <div class="text-black col-span-full lg:col-span-8 size-table">
                <template v-for="(optionValues, index) in item.specs.THONG_SO">
                  <div
                    v-if="optionValues.nodes && optionValues.nodes.length > 0"
                    :key="index"
                  >
                    <div class="mb-2">
                      <strong class="text-black">
                        {{ optionValues.name }} &nbsp;</strong
                      >
                    </div>
                    <div
                      class="flex overflow-x-auto md:flex-wrap md:overflow-x-visible"
                    >
                      <template v-if="optionValues.display_type === 'IMAGE'">
                        <div
                          v-for="(node, idx) in optionValues.nodes"
                          :key="idx"
                          class="flex flex-wrap items-center"
                        >
                          <div class="relative cursor-pointer">
                            <div
                              class="p-[4px] border border-transparent rounded-full mr-1"
                            >
                              <div
                                class="w-[32px] h-[32px] transition relative"
                              >
                                <JImage
                                  v-if="node.image_url"
                                  :src="node.image_url"
                                  class="object-cover w-[32px] h-[32px] rounded-full"
                                  :alt="node.image_url"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </template>

                      <template v-else>
                        <div
                          v-for="(node, idx) in optionValues.nodes"
                          :key="idx"
                          class="mb-2 mr-2 radio-content"
                        >
                          <div class="relative cursor-pointer">
                            <div
                              class="px-4 font-medium text-black border hover:border-gray-800 flex space-x-[12px] items-center w-max"
                              :class="[
                                optionValues.display_type === 'ALL'
                                  ? 'h-[44px] py-[4px] rounded-[10px] max-w-[220px]'
                                  : 'h-10 py-2 rounded-full',
                              ]"
                            >
                              <div
                                v-if="
                                  optionValues.display_type === 'ALL' &&
                                  node.image_url
                                "
                                class="w-[34px] h-full flex-none flex justify-center"
                              >
                                <JImage
                                  :src="node.image_url"
                                  class="object-cover w-auto h-full"
                                  :alt="node.image_url"
                                />
                              </div>
                              <div
                                class="description leading-[100%] font-light line-clamp-2 overflow-hidden"
                              >
                                {{ node.name }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </template>
                    </div>
                  </div>
                </template>
              </div>
            </div>

            <div
              v-if="item.content"
              class="grid grid-cols-12 xl:pt-[32px] md:pt-[22px] pt-[16px] lg:grid-cols-10 lg:gap-x-8 border-b border-gray-300"
            >
              <div class="col-span-full lg:col-span-2">
                <h3 class="md:mb-3 mb-1.5 font-bold text-black p3 lg:mb-0">
                  THÔNG TIN <br class="hidden lg:block" />SẢN PHẨM
                </h3>
              </div>
              <div
                class="text-black col-span-full lg:col-span-8 text-[14px] leading-[120%]"
              >
                <ShowMore
                  custom-class="btn-sm flex items-center justify-center no-underline cursor-pointer overflow-hidden z-10 relative text-black font-bold border border-gray-800 bg-transparent transition-all duration-[400] ease-in-out h-8 rounded-sm hover:border-red-400 hover:text-red-400 px-3 py-2"
                  collapsed-text="ẨN BỚT"
                  expanded-text="XEM THÊM"
                  content-class="max-h-[300px]"
                  :has-overlay="item.content.length > 1500"
                  wrap-btn-class="inline-block"
                  class="mb-2 md:mb-4"
                >
                  <div class="mb-3 md:mb-6" v-html="item.content"></div>
                </ShowMore>
              </div>
            </div>
            <div
              v-if="item.brand && item.brand.description"
              class="grid grid-cols-10 pt-6 lg:gap-x-8 lg:pt-8 pb-[32px] border-b border-gray-300"
            >
              <div class="col-span-full lg:col-span-2">
                <h3 class="md:mb-3 mb-1.5 font-bold text-black p3 lg:mb-0">
                  THÔNG TIN <br class="hidden lg:block" />THƯƠNG HIỆU
                </h3>
              </div>
              <div
                class="col-span-full lg:col-span-8"
                v-html="item.brand.description"
              ></div>
            </div>
            <div v-if="item && item.ingredients">
              <Accordions>
                <Accordion
                  type="custom"
                  class="grid grid-cols-10 pt-3 lg:gap-x-8 lg:pt-[20px] lg:pb-[20px] pb-3"
                >
                  <template #accordion-trigger>
                    <h3
                      class="flex items-center justify-between md:py-3 py-1.5 font-bold text-black p3 lg:mb-0"
                    >
                      <span>THÀNH PHẦN</span>
                      <JIcon
                        name="plus"
                        class="flex-none accordion-icon-3 plus"
                      />
                      <JIcon
                        name="minus"
                        class="flex-none accordion-icon-3 minus"
                      />
                    </h3>
                  </template>
                  <template #accordion-content>
                    <div v-html="item.ingredients"></div>
                  </template>
                </Accordion>
              </Accordions>
            </div>
            <div v-if="item.use_cases && item.use_cases.length > 0">
              <Accordions>
                <Accordion
                  type="custom"
                  class="grid grid-cols-10 pt-3 lg:gap-x-8 lg:pt-[20px] lg:pb-[20px] pb-3"
                >
                  <template #accordion-trigger>
                    <h3
                      class="flex items-center justify-between md:py-3 py-1.5 font-bold text-black p3 lg:mb-0"
                    >
                      <span>CÁCH SỬ DỤNG</span>
                      <JIcon
                        name="plus"
                        class="flex-none accordion-icon-3 plus"
                      />
                      <JIcon
                        name="minus"
                        class="flex-none accordion-icon-3 minus"
                      />
                    </h3>
                  </template>
                  <template #accordion-content>
                    <div
                      v-for="(userCase, index) in item.use_cases"
                      :key="index"
                      class="mb-[16px] last:mb-0"
                    >
                      <div class="p4 mb-[8px] font-semibold">
                        {{ userCase.name }}
                      </div>
                      <div v-html="userCase.content"></div>
                    </div>
                  </template>
                </Accordion>
              </Accordions>
            </div>
          </div>
        </div>
      </div>
    </section>
    <Comment :comment="comment" :product="item" />
    <YouWillLike :list="[...related_products, ...suggest_products].concat(10)" />
    <SocialMeta
      :title="item.meta_title ? item.meta_title : item.name"
      :description="
        item.meta_description ? item.meta_description : item.summary
      "
      :image="item.image_url ? item.image_url : '/images/cover.jpg'"
    />
  </main>
</template>

<script>
import App from "@/Layouts/App.vue";
import ShowMore from "../../Components/jam/ShowMore.vue";
import Gallery from "../../Components/gallery/Gallery";
import QuantityInput from "../../Components/jam/QuantityInput.vue";
import Breadcrumb from "../../Components/Breadcrumb.vue";
import Modal from "../../Components/jam/Modal.vue";
import YouWillLike from "../../Components/YouWillLike.vue";
import BlocksProductOptions from "@/Components/blocks/ProductOptions.vue";
import Comment from "@/Components/Product/Comment.vue";
import axios from "axios";

import Accordions from "@/Components/jam/Accordions.vue";
import Accordion from "@/Components/jam/Accordion.vue";

export default {
  components: {
    ShowMore,
    Gallery,
    QuantityInput,
    Breadcrumb,
    Modal,
    YouWillLike,
    BlocksProductOptions,
    Comment,
    Accordions,
    Accordion,
  },
  layout: App,
  props: ["item", "suggest_products", "related_products", "comment"],
  data() {
    return {
      quantity: 1,
      modalSelected: undefined,
      is_selected_size: null,
      is_selected_clt: null,
      is_selected_color_1: null,
      is_selected_color_2: null,
      is_selected_color_3: null,

      currentVariant: {},

      currentOptions: [],
      currentOptionsHover: [],
      show: false,

      galleryData: [],

      hightLights: [],
    };
  },
  created() {
    this.setCurrentOptions();
    this.setCurrentVariant();

    this.setGalleryData();

    this.getListHightlight();
  },
  methods: {
    changeQuantity(quantity) {
      this.quantity = quantity;
    },
    async addToCart() {
      this.show = !this.show;

      axios
        .post(this.route("checkout.cart.add"), {
          product_id: this.item.id,
          variant_id: this.currentVariant.id,
          quantity: this.quantity,
        })
        .then((res) => {
          this.$bus.emit("updateCart", res.data);
          this.$bus.emit("cartSuccess", res.data);
          this.$bus.emit("open-alert", "Thêm vào giỏ hàng thành công");
          setTimeout(() => (this.show = false), 2000);
        });
    },
    async buyNow() {
      axios
        .post(this.route("checkout.cart.add"), {
          product_id: this.item.id,
          variant_id: this.currentVariant.id,
          quantity: this.quantity,
        })
        .then((res) => {
          this.$bus.emit("updateCart", {
            ...res.data,
            redirect: this.route("checkout.cart.index"),
          });
        });
    },
    toggleBodyClass(addRemoveClass, className) {
      const el = document.body;
      if (addRemoveClass === "addClass") {
        el.classList.add(className);
      } else {
        el.classList.remove(className);
      }
    },
    openModal(target) {
      let currentTarget = target.closest("[data-target]");
      let currentModal = currentTarget.getAttribute("data-target");
      this.modalSelected = currentModal;
      this.toggleBodyClass("addClass", "overflow-hidden");
    },
    closeModal() {
      this.modalSelected = undefined;
      this.toggleBodyClass("removeClass", "overflow-hidden");
    },

    mappingSpecs(items) {
      let arr = [];
      items.forEach((item) => {
        arr.push(item.name);
      });

      return arr.join(" / ");
    },

    setCurrentOptions() {
      this.currentOptions = [
        ...this.item.variants.find((x) => x.selected === true).options,
      ];
      this.currentOptionsHover = [
        ...this.item.variants.find((x) => x.selected === true).options,
      ];
    },

    setCurrentVariant() {
      this.currentVariant =
        this.item.variants && this.item.variants.length > 0
          ? this.item.variants.find((x) => x.selected)
          : {};
    },

    setCurrentVariantWithVariantId(variant_id) {
      this.currentVariant =
        this.item.variants && this.item.variants.length > 0
          ? this.item.variants.find((x) => x.id === variant_id)
          : {};
    },

    updateVariant(id) {
      this.setCurrentVariantWithVariantId(id);
      const url = this.$page.props.ziggy.url;
      this.$inertia.get(
        url,
        { id: id },
        { preserveScroll: true, preserveState: true }
      );

      this.setGalleryData();
    },

    setGalleryData() {
      this.galleryData = this.mergeArray(
        this.currentVariant.image_urls,
        this.currentVariant.video_urls
      );
    },

    mergeArray(arrOne, arrTwo) {
      let dataArrOne = [...arrOne];
      let dataArrTwo = [...arrTwo];
      if (dataArrTwo && dataArrTwo.length > 0) {
        if (dataArrTwo[0]) {
          dataArrOne.splice(2, 0, dataArrTwo[0]);
        }
        dataArrTwo.shift();
      }

      let arr = [...dataArrOne, ...dataArrTwo];
      return arr;
    },

    getListHightlight() {
      if (
        this.item.specs &&
        this.item.specs.NOI_BAT &&
        this.item.specs.NOI_BAT.length > 0
      ) {
        this.item.specs.NOI_BAT.forEach((item) => {
          this.hightLights.push(...item.nodes);
        });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.product-info-scroll {
  /* Scrollbar width */
  &::-webkit-scrollbar {
    -webkit-appearance: none;
    height: 4px;
  }
  /* Track */
  &::-webkit-scrollbar-track {
    @apply bg-gray-300;
  }
  /* Handle */
  &::-webkit-scrollbar-thumb {
    @apply bg-gray-500;
  }
}
.size-table {
  :deep {
    table {
      @apply table-fixed min-w-full;
      td {
        @apply border border-gray-300 p-2.5 text-center;
      }
    }
  }
}

.guide-detail {
  :deep {
    h6 {
      @apply font-bold text-black mb-1;
      em {
        @apply inline-block mr-0.5 not-italic;
        &:after {
          @apply inline-block w-0.5 h-0.5 bg-black ml-0.5;
          content: "";
        }
      }
    }
    p {
      @apply font-medium text-gray-600 mb-3;
    }
  }
}

.text-left-custom {
  text-align: left !important;
}
</style>
