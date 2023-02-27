<template>
  <section>
    <div class="container grid grid-cols-12">
      <div
        class="xl:pt-[32px] md:pt-[22px] pt-[16px] col-span-full lg:col-span-10 lg:col-start-2"
      >
        <h3 class="xl:mb-[20px] md:mb-[14px] mb-[10px] font-bold text-black p3">
          ĐÁNH GIÁ SẢN PHẨM
        </h3>
        <div
          class="grid grid-cols-12 xl:mb-[32px] md:mb-[22px] mb-[16px] xl:grid-cols-10 2xl:gap-x-8 xl:gap-x-4"
        >
          <div class="mb-4 col-span-full xl:col-span-4 xl:mb-0">
            <div class="flex xl:mb-[24px] md:mb-[17px] mb-[12px]">
              <div
                class="md:max-w-[313px] max-w-[305px] w-full flex flex-col-reverse"
              >
                <div
                  v-for="(rating, idx) of comment.ratings"
                  :key="idx"
                  class="flex items-center space-y-1"
                >
                  <div class="flex items-center text-black w-[34px]">
                    <strong class="p4">{{ idx + 1 }}</strong>
                    <JIcon name="star-active" class="ml-2" />
                  </div>
                  <div
                    class="relative flex-1 h-2 ml-6 bg-gray-200 rounded-full"
                  >
                    <span
                      class="absolute inset-y-0 left-0 bg-gray-700 rounded-full"
                      :style="{ width: `${rating}%` }"
                    ></span>
                  </div>
                </div>
              </div>
              <div class="flex-1 ml-2 md:ml-4">
                <div class="flex items-center mb-2 text-black">
                  <strong class="mr-2 p1">{{ comment.rateScore }}</strong>
                  <JIcon name="star-active-2" />
                </div>
                <strong class="block text-black p1">{{ comment.total }}</strong>
                <span class="font-medium text-gray-700 caption">đánh giá</span>
              </div>
            </div>
            <p class="mb-2 font-semibold text-black md:mb-4 p3">
              Bạn đã dùng sản phẩm này?
            </p>
            <JButton
              size="md"
              label="GỬI ĐÁNH GIÁ"
              variant="primary-square"
              @click="openModal('modal-review')"
            />
          </div>
          <div class="col-span-full xl:col-span-6">
            <div
              v-if="comment.images && comment.images.length > 0"
              class="mb-2"
            >
              <strong class="block text-black">Tất cả hình ảnh</strong>
              <div class="flex mb-6 space-x-4">
                <template v-if="comment.images.length > 5">
                  <div
                    class="flex-1"
                    v-for="(item, index) in comment.images.slice(0, 4)"
                    :key="index"
                  >
                    <div class="relative pb-[100%]">
                      <JPicture
                        :src="item"
                        class="absolute inset-0 object-cover w-full h-full"
                      />
                    </div>
                  </div>
                  <div class="flex-1">
                    <div class="relative pb-[100%]">
                      <JPicture
                        :src="comment.images[4]"
                        class="absolute inset-0 object-cover w-full h-full"
                      />
                      <span
                        class="absolute inset-0 z-10 flex items-center justify-center font-bold text-white bg-black bg-opacity-60 p2"
                        >+{{ comment.images.length - 4 }}</span
                      >
                    </div>
                  </div>
                </template>
                <template v-else>
                  <div
                    v-for="(item, index) in comment.images"
                    :key="index"
                    class="flex-1"
                  >
                    <div class="relative aspect-[1/1] max-w-[120px]">
                      <JPicture
                        :src="item"
                        class="absolute inset-0 object-cover w-full h-full"
                      />
                    </div>
                  </div>
                </template>
              </div>
            </div>
            <strong class="block mb-2 text-black">Lọc theo xem:</strong>
            <div class="flex flex-wrap">
              <a
                href="javascript:;"
                class="2xl:mr-[16px] xl:mr-[6px] md:mr-[11px] mr-[8px] xl:mb-[16px] md:mb-[11px] mb-[8px] btn-md btn-secondary px-[15px] py-[7px] font-medium"
                :class="{ active: is_new === 1 }"
                @click="setSort('is_new')"
                >Mới nhất</a
              >
              <a
                href="javascript:;"
                class="2xl:mr-[16px] xl:mr-[6px] md:mr-[11px] mr-[8px] xl:mb-[16px] md:mb-[11px] mb-[8px] btn-md btn-secondary px-[15px] py-[7px] font-medium"
                :class="{ active: has_image === 1 }"
                @click="setSort('has_image')"
                >Có hình ảnh</a
              >
              <a
                href="javascript:;"
                class="2xl:mr-[16px] xl:mr-[6px] md:mr-[11px] mr-[8px] xl:mb-[16px] md:mb-[11px] mb-[8px] btn-md btn-secondary px-[15px] py-[7px] font-medium"
                :class="{ active: rate_score === 5 }"
                @click="setSort('rate_score', 5)"
              >
                <span class="mr-2">5</span>
                <JIcon name="star-active" />
              </a>
              <a
                href="javascript:;"
                class="2xl:mr-[16px] xl:mr-[6px] md:mr-[11px] mr-[8px] xl:mb-[16px] md:mb-[11px] mb-[8px] btn-md btn-secondary px-[15px] py-[7px] font-medium"
                :class="{ active: rate_score === 4 }"
                @click="setSort('rate_score', 4)"
              >
                <span class="mr-2">4</span>
                <JIcon name="star-active" />
              </a>
              <a
                href="javascript:;"
                class="2xl:mr-[16px] xl:mr-[6px] md:mr-[11px] mr-[8px] xl:mb-[16px] md:mb-[11px] mb-[8px] btn-md btn-secondary px-[15px] py-[7px] font-medium"
                :class="{ active: rate_score === 3 }"
                @click="setSort('rate_score', 3)"
              >
                <span class="mr-2">3</span>
                <JIcon name="star-active" />
              </a>
              <a
                href="javascript:;"
                class="2xl:mr-[16px] xl:mr-[6px] md:mr-[11px] mr-[8px] xl:mb-[16px] md:mb-[11px] mb-[8px] btn-md btn-secondary px-[15px] py-[7px] font-medium"
                :class="{ active: rate_score === 2 }"
                @click="setSort('rate_score', 2)"
              >
                <span class="mr-2">2</span>
                <JIcon name="star-active" />
              </a>
              <a
                href="javascript:;"
                class="2xl:mr-[16px] xl:mr-[6px] md:mr-[11px] mr-[8px] xl:mb-[16px] md:mb-[11px] mb-[8px] btn-md btn-secondary px-[15px] py-[7px] font-medium"
                :class="{ active: rate_score === 1 }"
                @click="setSort('rate_score', 1)"
              >
                <span class="mr-2">1</span>
                <JIcon name="star-active" />
              </a>
            </div>
          </div>
        </div>
        <div v-if="comments.total > 0" class="feedback">
          <span class="block font-medium text-gray-600"
            >Nhận xét của khách hàng</span
          >
          <div class="block">
            <div
              v-for="(item, index) of comments.data"
              :key="index"
              class="grid grid-cols-12 py-6 border-t border-gray-300 xl:grid-cols-10 xl:gap-x-8"
            >
              <div class="col-span-full xl:col-span-2">
                <strong class="block text-gray-800 p3">{{ item.name }}</strong>
                <span class="block font-medium text-gray-600">{{
                  item.formatted_created_at
                }}</span>
              </div>
              <div class="col-span-full xl:col-span-8">
                <div class="flex items-center mb-1 space-x-2 ratings">
                  <template v-for="idx in 5" :key="idx">
                    <JIcon
                      v-if="idx <= item.rate_score"
                      name="star-active-3"
                      class="text-gray-800 star star-active"
                    />
                    <JIcon
                      v-else
                      name="star-3"
                      class="text-gray-700 star star-grey"
                    />
                  </template>
                </div>
                <strong class="block mb-4 text-gray-800 p3">{{
                  starLabels[item.rate_score - 1]
                }}</strong>
                <p class="mb-4 font-medium text-black">
                  {{ item.content }}
                </p>
                <div class="flex flex-wrap">
                  <div
                    v-for="(image, imgIdx) of item.images"
                    :key="imgIdx"
                    class="aspect-[1/1] h-[80px] mb-2 mr-2"
                  >
                    <JPicture :src="image" class="object-cover w-full h-full" />
                  </div>
                </div>
              </div>
            </div>

            <PaginationAxios :data="comments" @changePage="changePage" />
          </div>
        </div>
      </div>
    </div>
    <Modal
      :show="modalSelected === 'modal-review'"
      custom-max-width="max-w-[886px] mx-auto w-full"
      @offBody="toggleBodyClass('removeClass', 'overflow-hidden')"
      @close="closeModal"
    >
      <div class="modal-dialog max-w-[753px] modal-dialog-center mx-auto">
        <div
          class="relative w-full overflow-hidden bg-white modal-content rounded-[2px]"
        >
          <div class="absolute top-[12px] right-[12px]" @click="closeModal">
            <svg
              width="26"
              height="26"
              viewBox="0 0 26 26"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M13 0C10.4288 0 7.91543 0.762437 5.77759 2.1909C3.63975 3.61935 1.97351 5.64968 0.989572 8.02512C0.0056327 10.4006 -0.251811 13.0144 0.249797 15.5362C0.751405 18.0579 1.98953 20.3743 3.80762 22.1924C5.6257 24.0105 7.94208 25.2486 10.4638 25.7502C12.9856 26.2518 15.5995 25.9944 17.9749 25.0104C20.3503 24.0265 22.3807 22.3603 23.8091 20.2224C25.2376 18.0846 26 15.5712 26 13C25.9934 9.55422 24.6216 6.25145 22.1851 3.81491C19.7486 1.37837 16.4458 0.00660747 13 0ZM17.7125 16.2875C17.9003 16.4771 18.0056 16.7332 18.0056 17C18.0056 17.2668 17.9003 17.5229 17.7125 17.7125C17.5214 17.8973 17.2659 18.0006 17 18.0006C16.7341 18.0006 16.4786 17.8973 16.2875 17.7125L13 14.4125L9.71251 17.7125C9.52137 17.8973 9.26589 18.0006 9.00001 18.0006C8.73412 18.0006 8.47865 17.8973 8.28751 17.7125C8.09972 17.5229 7.99438 17.2668 7.99438 17C7.99438 16.7332 8.09972 16.4771 8.28751 16.2875L11.5875 13L8.28751 9.7125C8.12804 9.5182 8.04655 9.27154 8.05888 9.02049C8.07121 8.76944 8.17648 8.53195 8.35422 8.35421C8.53195 8.17648 8.76944 8.07121 9.02049 8.05888C9.27155 8.04655 9.51821 8.12804 9.71251 8.2875L13 11.5875L16.2875 8.2875C16.4818 8.12804 16.7285 8.04655 16.9795 8.05888C17.2306 8.07121 17.4681 8.17648 17.6458 8.35421C17.8235 8.53195 17.9288 8.76944 17.9411 9.02049C17.9535 9.27154 17.872 9.5182 17.7125 9.7125L14.4125 13L17.7125 16.2875Z"
                fill="#19191A"
              />
            </svg>
          </div>
          <div class="p-[48px]">
            <Rating
              :star="form.rate_score"
              :starLabels="starLabels"
              @onSetRating="onSetRating"
            />

            <JFieldSet
              v-model="form.name"
              class="mt-[16px]"
              :field="{
                rules: rules,
                errors: errors,
                name: 'Tên',
                type: 'text',
                label: 'Tên của bạn',
                fieldName: 'name',
                placeholder: 'Nhập tên của bạn',
              }"
            />

            <JFieldSet
              v-model="form.content"
              class="mt-[16px]"
              :field="{
                rules: rules,
                errors: errors,
                type: 'textarea',
                label: 'Nhận xét của bạn',
                fieldName: 'content',
                placeholder: 'Những chia sẻ của bạn về sản phẩm là gì?',
              }"
            />

            <JFieldSet
              v-model="form.images"
              class="mt-[28px]"
              :field="{
                type: 'media_upload',
                label: 'Đánh giá',
                placeholder: 'Nhập đánh giá',
                config: {
                  multiple: true,
                },
                fieldName: 'images',
                rules: rules,
                errors: errors,
              }"
              @changeImages="changeImages"
            />

            <JButton
              class="mt-[16px] ml-auto"
              size="md"
              label="GỬI ĐÁNH GIÁ"
              variant="primary-square"
              @click="submit"
            />
          </div>
        </div>
      </div>
    </Modal>
  </section>
</template>
<script>
import Modal from "../jam/Modal.vue";
import Rating from "./Rating.vue";
import { validateForm } from "../../validator.js";
import axios from "axios";
import PaginationAxios from "./PaginationAxios.vue";

const formEmpty = {
  content: "",
  name: "",
  rate_score: "5",
  images: [],
};

export default {
  components: { Modal, Rating, PaginationAxios },
  props: ["comment", "product"],
  data() {
    return {
      totalImages: [
        "https://via.placeholder.com/200",
        "https://via.placeholder.com/300",
        "https://via.placeholder.com/400",
        "https://via.placeholder.com/150",
        "https://via.placeholder.com/600",
        "https://via.placeholder.com/550",
        "https://via.placeholder.com/320",
        "https://via.placeholder.com/650",
        "https://via.placeholder.com/255",
      ],
      ratingBoard: [
        {
          value: 5,
          totalScore: "w-[88.28%]",
        },
        {
          value: 4,
          totalScore: "w-[75.39%]",
        },
        {
          value: 3,
          totalScore: "w-[20.31%]",
        },
        {
          value: 2,
          totalScore: "w-[34.77%]",
        },
        {
          value: 1,
          totalScore: "w-[16.8%]",
        },
      ],
      feedbacks: [
        {
          userName: "Ngọc Lan",
          date: "2 tháng trước",
          rate_value: 4,
          name: "Hài lòng",
          comment:
            "Lần đầu đặt shop và rất là ưng luôn á. Trước giờ bạn kêu thử lên đặt mà chưa thử giờ lên xong mê luôn . Ship hoả tốc rất là nhanh luôn. Đặt chiều tầm tối nhận được. Shop đóng gói chưa kĩ lắm nhưng lúc nhận son không bị gì. Mùi son kiểu trà sữa socola. Màu son mình rất ưng luôn . Kiểu hơi cam đất.  Mình mua shop ở đây rồi đồ lúc nào cũng ưng hết. Sẽ còn ủng hộ tiếp",
          images: ["https://via.placeholder.com/224x300"],
        },
        {
          userName: "Nguyễn Ngọc Lan Nguyên",
          date: "2 tháng trước",
          rate_value: 5,
          name: "Cực kì hài lòng",
          comment:
            "Shop thân thiện , giao hàng nhanh Đóng hàng rất đẹp chặt chẽ Son lên màu rất đẹp, vỏ son thiết kế giống ly trà sữa nhìn rất bắt mắt *lưu ý muốn son lên màu đẹp thì phải bôi dưỡng cho môi mịn nha mọi người!",
          images: [
            "https://via.placeholder.com/224x300",
            "https://via.placeholder.com/279x300",
            "https://via.placeholder.com/278x300",
          ],
        },
        {
          userName: "Nguyễn Ngọc Lan Nguyên",
          date: "2 tháng trước",
          rate_value: 3,
          name: "Bình thường",
          comment:
            "Shop thân thiện , giao hàng nhanh Đóng hàng rất đẹp chặt chẽ Son lên màu rất đẹp, vỏ son thiết kế giống ly trà sữa nhìn rất bắt mắt *lưu ý muốn son lên màu đẹp thì phải bôi dưỡng cho môi mịn nha mọi người!",
        },
      ],
      modalSelected: undefined,

      rules: { name: "required" },
      errors: {},
      form: { ...formEmpty },
      starLabels: [
        "Rất không hài lòng",
        "Không hài lòng",
        "Bình thường",
        "Hài lòng",
        "Cực kì hài lòng",
      ],

      loading: false,
      comments: {},
      is_new: null,
      has_image: null,
      rate_score: null,
    };
  },

  created() {
    this.getComments();
  },

  methods: {
    openModal(target) {
      let currentModal = target;
      this.modalSelected = currentModal;
      this.toggleBodyClass("addClass", "overflow-hidden");
    },
    closeModal() {
      this.modalSelected = undefined;
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
    onSetRating(value) {
      this.form.rate_score = value.toString();
    },

    submit() {
      // try {
      if (this.loading) return;
      this.loading = true;

      this.errors = validateForm(this.form, this.rules);

      if (Object.keys(this.errors).length > 0) {
        this.loading = false;
        return false;
      } else {
        const slug = this.product.slug;
        const id = this.product.id;

        let images = [];
        if (this.form.images.length > 0) {
          for (let index = 0; index < this.form.images.length; index++) {
            images.push(this.form.images[index].obj);
          }
        }
        const submitForm = { ...this.form, images };
        console.log(submitForm);

        this.$inertia.post(
          this.route("product.comment.store", { slug, id }),
          {
            ...submitForm,
          },
          {
            onFinish: () => {
              this.form = formEmpty;

              this.closeModal();
              this.loading = false;
              // this.modalSelected = "modal-success-review";
            },
          }
        );
      }
      // } catch (e) {}
    },

    changePage(page) {
      this.getComments(page);
    },

    async getComments(page = 1) {
      const slug = this.product.slug;
      const id = this.product.id;

      let params = {
        page,
        is_new: this.is_new,
        has_image: this.has_image,
        rate_score: this.rate_score,
      };

      // try {

      const { data } = await axios.get(
        this.route("product.comment.show", { slug, id }),
        {
          params,
        }
      );

      if (data && data.success) {
        this.comments = data.data;
      }
    },

    setSort(key, value) {
      switch (key) {
        case "is_new":
          if (this.is_new === null) {
            this.is_new = 1;
          } else {
            this.is_new = null;
          }
          break;
        case "has_image":
          if (this.has_image === null) {
            this.has_image = 1;
          } else {
            this.has_image = null;
          }
          break;
        case "rate_score":
          if (this.rate_score === value) {
            this.rate_score = null;
          } else {
            this.rate_score = value;
          }
          break;

        default:
          break;
      }

      this.getComments();
    },
  },
};
</script>
<style lang="scss" scoped>
.modal-dialog {
  // .modal-header-gradient {
  //   background-image: theme("colors.linear.red-orange");
  // }
}

.modal-dialog-center {
  display: flex;
  align-items: center;
  min-height: 100%;
}

@screen xl {
  .modal-dialog-center {
    min-height: calc(100% - (24px * 2));
  }
}
</style>
