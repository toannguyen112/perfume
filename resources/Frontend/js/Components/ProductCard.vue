<template>
  <div class="h-full">
    <div class="card group">
      <div class="relative border border-gray-200">
        <Link
          :href="
            route('product.show', {
              productSlug: item.slug,
              productId: item.id,
              id: currentVariant.id,
            })
          "
        >
          <div class="aspect-[1/1]">
            <JPicture
              :src="
                currentVariant.image_url
                  ? currentVariant.image_url
                  : item.image_url
              "
              class="object-cover w-full h-full"
            />
          </div>
        </Link>
        <div
          class="absolute bottom-0 left-0 right-0 flex-col items-center justify-end hidden p-2 space-y-1 transition duration-200 ease-in-out opacity-0 lg:flex h-max group-hover:opacity-100"
        >
          <div
            class="text-center btn-secondary label xl:px-6 max-w-[197px] w-full h-8 py-2 px-3 sm:px-5 cursor-pointer"
            data-target="modal-product"
            @click="openModal($event.target, item)"
          >
            <span>XEM NHANH SẢN PHẨM</span>
          </div>
          <div
            class="cursor-pointer text-center btn-primary label xl:px-6 max-w-[197px] w-full h-8 py-2 px-3 sm:px-5"
            @click="addToCart()"
          >
            <span>THÊM VÀO GIỎ HÀNG</span>
          </div>
        </div>
        <template
          v-if="item.is_hot == 1 || item.is_limited == 1 || item.is_new == 1"
        >
          <span
            v-if="item.is_hot == 1"
            class="absolute top-0 left-0 px-2 py-0.5 font-bold text-white uppercase description bg-red-400"
            >Hot</span
          >
          <span
            v-else-if="item.is_limited == 1"
            class="absolute top-0 left-0 px-2 py-0.5 font-bold text-white uppercase description bg-black"
            >Limited</span
          >
          <span
            v-else
            class="absolute top-0 left-0 px-2 py-0.5 font-bold text-white uppercase description bg-black"
            >New</span
          >
        </template>
      </div>
      <div class="flex-1 md:px-3 px-1.5 py-4 space-y-1.5 md:space-y-1">
        <Link
          class="font-semibold text-gray-800 transition-colors duration-200 ease-in-out caption group-hover:text-red-400"
          :href="
            route('product.show', {
              productSlug: item.slug,
              productId: item.id,
              id: currentVariant.id,
            })
          "
        >
          <span class="line-clamp-2">{{ item.name }}</span>
        </Link>
        <div
          v-if="item.comments_count > 0"
          class="flex items-center space-x-2 ratings"
        >
          <template v-for="idx in 5" :key="idx">
            <JIcon
              v-if="idx <= item.rate_score"
              name="star-active"
              class="text-black star star-active"
            />
            <JIcon v-else name="star" class="text-gray-700 star star-grey" />
          </template>
        </div>
        <span class="block font-bold text-black price">{{
          currentVariant.formatted_price
        }}</span>
        <div class="flex tags space-x-1.5">
          <span
            v-for="(tag, idx) of item.tags"
            :key="idx"
            class="label bg-gray-200 text-gray-800 px-1.5 py-1"
            >{{ tag }}</span
          >
        </div>
        <div
          v-if="item.colors && item.colors.length > 1"
          class="flex-wrap items-center hidden md:flex"
        >
          <Link
            v-for="(itemGrp, idx) of item.colors.slice(0, 5)"
            :key="idx"
            :href="
              route('product.show', {
                productSlug: item.slug,
                productId: item.id,
                id: itemGrp.variant_id,
              })
            "
            class="w-6 h-6 md:mr-1 mr-0.5 transition-all duration-200 ease-in-out border border-transparent rounded-full cursor-pointer hover:border-gray-600"
            :class="
              itemGrp.variant_id === currentVariant.id ? 'border-gray-600' : ''
            "
            @mouseover="setCurrentVariant(itemGrp.variant_id)"
          >
            <JPicture
              :src="itemGrp.default_thumbnail_url"
              class="h-[18px] w-[18px] rounded-full m-0.5"
            />
          </Link>
          <template v-if="item.colors.length > 5">
            <span class="font-medium text-gray-700 caption"
              >+{{ item.colors.length - 5 }}</span
            >
          </template>
        </div>
        <div
          v-if="item.colors && item.colors.length > 1"
          class="flex flex-wrap items-center md:hidden"
        >
          <div
            v-for="(itemGrp, idx) of item.colors.slice(0, 5)"
            :key="idx"
            class="w-6 h-6 md:mr-1 mr-0.5 transition-all duration-200 ease-in-out border border-transparent rounded-full cursor-pointer hover:border-gray-600"
            :class="
              itemGrp.variant_id === currentVariant.id ? 'border-gray-600' : ''
            "
            @click="setCurrentVariant(itemGrp.variant_id)"
          >
            <JPicture
              :src="itemGrp.default_thumbnail_url"
              class="h-[18px] w-[18px] rounded-full m-0.5"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["item"],
  data() {
    return {
      is_selected_color: 0,
      isLoadingAddCart: false,
      currentVariant:
        this.item.variants && this.item.variants.length > 0
          ? this.item.variants.find((x) => x.selected)
          : {},
    };
  },

  methods: {
    async addToCart() {
      this.isLoadingAddCart = true;
      const { data } = await axios.post(this.route("checkout.cart.add"), {
        product_id: this.item.id,
        variant_id: this.currentVariant.id,
        quantity: 1,
      });

      this.isLoadingAddCart = false;

      if (data) {
        this.$bus.emit("updateCart", data);
        this.$bus.emit("cartSuccess", true);
      }
    },
    toggleBodyClass(addRemoveClass, className) {
      const el = document.body;
      if (addRemoveClass === "addClass") {
        el.classList.add(className);
      } else {
        el.classList.remove(className);
      }
    },
    openModal(target, item) {
      let currentTarget = target.closest("[data-target]");
      let currentModal = currentTarget.getAttribute("data-target");
      this.setModal(currentModal, item);
      this.toggleBodyClass("addClass", "overflow-hidden");
    },
    setModal(target, item) {
      this.$emit("setTargetModal", { target, item });
    },

    setCurrentVariant(variant_id) {
      this.currentVariant = this.item.variants.find((x) => x.id === variant_id);
    },
  },
};
</script>

<style lang="scss" scoped>
.card {
  @apply flex flex-col xl:min-h-[424px] shadow-0 h-full;
}
</style>
