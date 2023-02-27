<template>
  <section class="banner-img">
    <div class="xl:py-[142px] md:py-[109px] py-[78px] w-full">
      <div class="container">
        <div class="text-center">
          <div class="font-medium text-black mb-[4px]">Tuyển dụng</div>
          <h1 class="font-bold text-black h2">{{ item.title }}</h1>
        </div>
      </div>
    </div>
  </section>
  <section class="xl:py-[48px] md:py-[34px] py-[24px]">
    <div class="container">
      <div
        class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px] max-lg:gap-y-[22px] max-md:gap-y-[16px]"
      >
        <div
          class="lg:col-span-8 col-span-full xl:space-y-[48px] md:space-y-[34px] space-y-[24px]"
        >
          <div
            v-if="item.description"
            class="space-y-[8px] border-b border-gray-200 xl:pb-[24px] md:pb-[17px] pb-[12px]"
          >
            <div
              class="font-medium prose text-gray-800 lg:pl-[10px] pl-[5px]"
              v-html="item.description"
            ></div>
          </div>
          <div v-if="item.requirements" class="space-y-[8px]">
            <div
              class="font-medium prose text-gray-800 lg:pl-[10px] pl-[5px]"
              v-html="item.requirements"
            ></div>
          </div>
          <div v-if="item.benefit" class="space-y-[8px]">
            <div
              class="font-medium prose text-gray-800 lg:pl-[10px] pl-[5px]"
              v-html="item.benefit"
            ></div>
          </div>
          <div v-if="item.profile" class="space-y-[8px]">
            <div class="font-bold text-gray-800 p3">Hồ sơ xin việc</div>
            <div
              class="font-medium prose text-gray-800 lg:pl-[10px] pl-[5px]"
              v-html="item.profile"
            ></div>
          </div>
        </div>
        <div class="lg:col-span-4 col-span-full">
          <div
            class="border border-gray-300 xl:p-[32px] md:p-[22px] p-[16px] lg:sticky lg:top-0"
          >
            <div
              class="xl:pb-[24px] md:pb-[17px] pb-[12px] border-b border-dashed border-gray-300"
            >
              <div class="xl:mb-[24px] md:mb-[17px] mb-[12px] space-y-[8px]">
                <div
                  v-if="item.title"
                  class="flex justify-between space-x-[8px]"
                >
                  <div class="flex-shrink-0 font-medium text-gray-600">
                    Vị trí
                  </div>
                  <div class="font-semibold text-right text-black">
                    {{ item.title }}
                  </div>
                </div>
                <div
                  v-if="item.count"
                  class="flex justify-between space-x-[8px]"
                >
                  <div class="flex-shrink-0 font-medium text-gray-600">
                    Số lượng
                  </div>
                  <div class="font-semibold text-right text-black">
                    {{ item.count }}
                  </div>
                </div>
                <div
                  v-if="item.formatted_expected_time"
                  class="flex justify-between space-x-[8px]"
                >
                  <div class="flex-shrink-0 font-medium text-gray-600">
                    Hạn nộp
                  </div>
                  <div class="font-semibold text-right text-black">
                    {{ item.formatted_expected_time }}
                  </div>
                </div>
                <div
                  v-if="item.address"
                  class="flex justify-between space-x-[8px]"
                >
                  <div class="flex-shrink-0 font-medium text-gray-600">
                    Địa điểm
                  </div>
                  <div class="font-semibold text-right text-black">
                    {{ item.address }}
                  </div>
                </div>
                <div
                  v-if="item.work_time"
                  class="flex justify-between space-x-[8px]"
                >
                  <div class="flex-shrink-0 font-medium text-gray-600">
                    Giờ làm việc
                  </div>
                  <div class="font-semibold text-right text-black">
                    {{ item.work_time }}
                  </div>
                </div>
                <div
                  v-if="item.salary"
                  class="flex justify-between space-x-[8px]"
                >
                  <div class="flex-shrink-0 font-medium text-gray-600">
                    Mức lương
                  </div>
                  <div class="font-semibold text-right text-black">
                    {{ item.salary }}
                  </div>
                </div>
              </div>
              <div>
                <JButton
                  label="ỨNG TUYỂN NGAY"
                  class="w-full"
                  variant="primary-square"
                  @click="openModal"
                />
              </div>
            </div>
            <div class="xl:pt-[24px] md:pt-[17px] pt-[12px]">
              <div class="text-gray-800 p4 font-bold mb-[8px]">Chia sẻ</div>
              <div class="flex items-center space-x-[8px]">
                <a
                  :href="facebookUrl"
                  class="text-gray-400 duration-150 hover:text-gray-800"
                >
                  <JIcon name="facebook" />
                </a>
                <div
                  class="z-0 flex items-center justify-center w-5 h-5 transition duration-200 ease-in-out rounded-full zalo-share-button hover:opacity-60"
                  onload="this.setAttribute('data-href', window.location.host)"
                  data-oaid="165735188811718133"
                  data-layout="3"
                  data-color="blue"
                  data-customize="false"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section
    v-if="job_data && job_data.length > 0"
    class="xl:py-[48px] md:py-[34px] py-[24px] bg-gray-100 border-t border-gray-200"
  >
    <div class="container">
      <div
        class="p3 font-bold text-black xl:mb-[32px] md:mb-[22px] mb-[16px] text-center"
      >
        VỊ TRÍ TUYỂN DỤNG KHÁC
      </div>
      <div class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px]">
        <div
          class="xl:col-span-10 col-span-full xl:col-start-2 xl:space-y-[16px] md:space-y-[12px] space-y-[8px]"
        >
          <Link
            v-for="(item, idx) in job_data"
            :key="idx"
            :href="route('job.show', { slug: item.slug })"
            class="grid lg:grid-cols-10 grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px] bg-white border border-gray-300 hover:border-gray-800 duration-150"
          >
            <div
              class="xl:px-[32px] md:px-[22px] px-[16px] xl:py-[24px] md:py-[17px] py-[12px] lg:col-span-7 col-span-full"
            >
              <div
                v-if="item.title"
                class="p3 font-bold text-gray-800 mb-[3px] line-clamp-2"
              >
                {{ item.title }}
              </div>
              <div
                v-if="item.work_form"
                class="mb-[4px] flex xl:space-x-[16px] md:space-x-[12px] space-x-[8px]"
              >
                <span class="flex-shrink-0 font-medium text-gray-600 w-[146px]"
                  >Hình thức công việc</span
                >
                <span class="font-medium text-gray-800 line-clamp-2">{{
                  item.work_form
                }}</span>
              </div>
              <div
                v-if="item.address"
                class="mb-[4px] flex xl:space-x-[16px] md:space-x-[12px] space-x-[8px]"
              >
                <span class="flex-shrink-0 font-medium text-gray-600 w-[146px]"
                  >Địa điểm làm việc</span
                >
                <span class="font-medium text-gray-800">{{
                  item.address
                }}</span>
              </div>
              <div
                v-if="item.formatted_expected_time"
                class="flex xl:space-x-[16px] md:space-x-[12px] space-x-[8px]"
              >
                <span class="flex-shrink-0 font-medium text-gray-600 w-[146px]"
                  >Thời hạn nộp hồ sơ</span
                >
                <span class="font-medium text-gray-800">{{
                  item.formatted_expected_time
                }}</span>
              </div>
            </div>
            <div
              class="xl:px-[32px] md:px-[22px] px-[16px] xl:py-[24px] md:py-[17px] py-[12px] lg:col-span-3 col-span-full flex lg:items-end"
            >
              <JButton
                label="ỨNG TUYỂN NGAY"
                variant="primary-square"
                class="max-lg:w-full"
              />
            </div>
          </Link>
        </div>
      </div>
    </div>
  </section>
  <Modal
    class="modal-apply md:flex-col md:flex md:items-center md:justify-center"
    :show="isOpenModal"
    customMaxWidth="md:w-[656px] w-full mx-auto max-md:p-0"
    @close="closeModal"
  >
    <div class="h-full bg-white rounded-sm shadow-xl">
      <div class="relative w-full h-full modal-content">
        <div
          class="absolute top-[8px] right-[8px] flex items-center justify-center w-[32px] h-[32px] cursor-pointer z-[1]"
          @click="closeModal"
        >
          <JIcon name="close-circle" />
        </div>
        <div class="h-full md:p-[40px] p-[20px] overflow-y-auto modal-body">
          <div class="xl:mb-[16px] md:mb-[12px] mb-[8px]">
            <div class="font-medium text-center text-gray-800 p3">
              Bạn đang ứng tuyển vị trí
            </div>
            <div class="font-bold text-center text-gray-800 p2">
              {{ item.title }}
            </div>
          </div>
          <div class="xl:space-y-[28px] md:space-y-[20px] space-y-[14px]">
            <div>
              <JFieldSet
                v-model="form.name"
                :field="{
                  rules: rules,
                  errors: errors,
                  type: 'text',
                  placeholder: 'Nhập họ và tên',
                  name: 'Họ và tên',
                  fieldName: 'name',
                  label: 'Họ và tên',
                }"
                :contactForm="false"
              />
            </div>
            <div
              class="lg:flex items-center xl:space-x-[16px] lg:space-x-[12px] max-lg:space-y-[20px] max-md:space-y-[14px]"
            >
              <div class="w-full">
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
                  :contactForm="false"
                />
              </div>
              <div class="w-full">
                <JFieldSet
                  v-model="form.email"
                  :field="{
                    rules: rules,
                    errors: errors,
                    type: 'email',
                    placeholder: 'email',
                    fieldName: 'email',
                    label: 'Email',
                    name: 'Email',
                  }"
                  :contactForm="false"
                />
              </div>
            </div>
            <div>
              <JFieldSet
                v-model="form.address"
                :field="{
                  type: 'textarea',
                  placeholder: 'Nhập địa chỉ',
                  label: 'Địa chỉ',
                  fieldName: 'address',
                  name: 'Địa chỉ',
                  rules: rules,
                  errors: errors,
                }"
                :contactForm="false"
                :isAddress="true"
              />
            </div>
            <div>
              <label class="font-bold mb-[4px] text-gray-700"> CV </label>
              <div class="relative">
                <div
                  class="mt-0 w-full font-light py-[12px] px-[12px] text-gray-800 bg-white border border-gray-300 focus:ring-0 focus:border-gray-800 outline-none focus:outline-none focus:duration-200 rounded-lg h-[46px] flex items-center justify-start"
                >
                  <span v-if="fileCount > 0" class="font-medium text-gray-500">
                    {{ fileCount }} file đã được chọn
                  </span>
                  <span v-else class="font-medium text-gray-500"
                    >Chưa có file nào được chọn</span
                  >
                </div>
                <div
                  class="absolute right-[12px] -translate-y-1/2 top-1/2 z-10"
                >
                  <div class="w-max">
                    <input
                      type="file"
                      class="hidden"
                      ref="image"
                      @change="onFileChange"
                      multiple
                    />
                    <div class="flex flex-col items-center justify-center">
                      <div
                        class="flex items-center justify-center font-bold cursor-pointer btn-md py-[8px] xl:px-[24px] md:px-[17px] px-[12px] bg-black text-white hover:bg-red-500 duration-150"
                        @click.prevent="browse"
                      >
                        Upload CV
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex justify-end">
              <JButton label="GỬI" variant="primary-submit" @click="apply" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </Modal>
  <SocialMeta
    :title="item.meta_title ? item.meta_title : item.title"
    :description="
      item.meta_description ? item.meta_description : item.description
    "
  />
</template>

<script>
import App from "@/Layouts/App.vue";
import Modal from "../../../Components/jam/Modal.vue";
import { validateForm } from "../../../validator";
export default {
  components: {
    Modal,
  },
  layout: App,
  props: ["item", "jobOther"],
  data() {
    return {
      facebookUrl: null,
      isOpenModal: false,
      form: {
        name: "",
        email: "",
        phone: "",
        address: "",
        files: [],
      },
      fileCount: 0,
      processing: false,
      errors: {},
      rules: {
        name: "required",
        email: "required|email",
        phone: "required|phone",
        address: "required",
        files: "",
      },
      isLoading: false,
      job_data: this.jobOther.data,
    };
  },
  mounted() {
    let Script = document.createElement("script");
    Script.setAttribute("src", "https://sp.zalo.me/plugins/sdk.js");
    document.head.appendChild(Script);

    this.facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${window.location.href}`;
  },
  created() {
    window.addEventListener("scroll", this.scroll);
  },

  unmounted() {
    window.removeEventListener("scroll", this.scroll);
  },
  methods: {
    openModal() {
      this.isOpenModal = !this.isOpenModal;
      document.body.classList.add("overflow-hidden");
    },
    closeModal() {
      this.isOpenModal = !this.isOpenModal;
      document.body.classList.remove("overflow-hidden");
    },
    previewFiles(event) {
      this.form.files = event.target.files;
    },
    browse() {
      this.$refs.image.click();
    },
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.form.files = files;
      this.fileCount = files.length;
    },
    apply() {
      const objContact = {
        contact: {
          data: {
            "Họ và tên": this.form.name,
            Email: this.form.email,
            "Số điện thoại": this.form.phone,
            "Địa chỉ": this.form.address,
            "Hồ sơ": this.form.files,
            "Job" : {
              "id": this.item.id,
              "title": this.item.title
            },
          },
          type: "JOB_FORM",
        },
      };

      this.errors = validateForm(this.form, this.rules);
      if (Object.keys(this.errors).length > 0 || this.isLoading) {
        return false;
      }
      this.$inertia.post(this.route("job-form"), objContact);
      this.closeModal();
    },
    scroll: function () {
      const self = this;
      const footer = document.getElementById("footer_id").scrollHeight;

      window.onscroll = function () {
        if (
          window.innerHeight + window.scrollY >=
          document.body.scrollHeight - footer
        ) {
          const job_links = self.jobOther.links;
          const next = job_links[job_links.length - 1];
          if (next.url) {
            self.$inertia.visit(next.url, {
              preserveScroll: true,
              preserveState: true,
              only: ["jobOther"],
            });
            self.job_data.push(...self.jobOther.data);
          }
        }
      };
    },
  },
};
</script>
<style lang="scss" scoped>
.banner-img {
  background: url("/images/recruitment/banner-slug.jpg");
  background-size: cover;
  background-repeat: no-repeat;
}
:deep(.prose) {
  p {
    @apply font-display font-medium text-[#39393A] mt-0 mb-[1rem];
  }
  h2 {
    @apply font-display mt-0 mb-[12px];
  }
  li {
    @apply font-display font-medium text-[#39393A] my-0;
  }
  ul {
    @apply list-none pl-[0.5rem];
    li::before {
      content: "\2022";
      @apply text-gray-700 inline-block w-[1em] ml-[-1em];
    }
  }
  .content-group__content {
    @apply pl-[8px];
  }
}
</style>
