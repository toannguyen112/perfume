<template>
  <footer id="footer_id">
    <div class="text-black bg-gray-100 extend-footer">
      <div class="top-extend pt-[13px] pb-3 border-b border-gray-300">
        <div class="container">
          <p class="font-medium p3">Mua sắm tuyệt vời tại Muội Muội</p>
        </div>
      </div>
      <div class="bottom-extend xl:py-[28px] md:py-[20px] py-[14px]">
        <div class="container">
          <div
            class="md:flex md:items-start md:justify-center md:flex-wrap xl:flex-nowrap md:-mx-3 2xl:-mx-6"
          >
            <div
              class="flex mb-2 md:justify-center md:w-1/3 xl:w-1/5 2xl:mb-0 md:px-3 2xl:px-6"
            >
              <div class="flex">
                <JIcon name="archive-box" />
                <span class="mt-1.5 lg:mt-1 flex-1 ml-3 font-medium text-black"
                  >100% hàng thật, <br class="hidden xl:block" />nhập khẩu chính
                  hãng</span
                >
              </div>
            </div>
            <div
              class="flex mb-2 md:justify-center md:w-1/3 xl:w-1/5 2xl:mb-0 md:px-3 2xl:px-6"
            >
              <div class="flex">
                <JIcon name="circles-four" />
                <span class="mt-1.5 lg:mt-1 flex-1 ml-3 font-medium text-black"
                  >Hàng hiệu đa dạng mẫu mã</span
                >
              </div>
            </div>
            <div
              class="flex mb-2 md:justify-center md:w-1/3 xl:w-1/5 2xl:mb-0 md:px-3 2xl:px-6"
            >
              <div class="flex">
                <JIcon name="tag" />
                <span class="mt-1.5 lg:mt-1 flex-1 ml-3 font-medium text-black"
                  >Khuyến mãi, ưu đãi hấp dẫn</span
                >
              </div>
            </div>
            <div
              class="flex mb-2 md:justify-center md:w-1/2 xl:w-1/5 md:mb-0 md:px-3 2xl:px-6"
            >
              <div class="flex">
                <JIcon name="truck" class="w-8 h-8 text-black" />
                <span class="mt-1.5 lg:mt-1 flex-1 ml-3 font-medium text-black"
                  >Vận chuyển nhanh trong vòng khoảng 48h
                </span>
              </div>
            </div>
            <div
              class="flex md:justify-center md:w-1/2 xl:w-1/5 md:px-3 2xl:px-6"
            >
              <div class="flex">
                <JIcon name="headset" />
                <span class="mt-1.5 lg:mt-1 flex-1 ml-3 font-medium text-black"
                  >Tư vấn theo nhu cầu khách hàng</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="text-white bg-black main-footer">
      <div class="top-footer">
        <div class="container">
          <div class="border-b border-gray-700 py-6 lg:pt-[68px]">
            <div class="lg:flex">
              <div
                class="mb-8 lg:mb-0 lg:w-[290px] xl:w-[329px] lg:mr-7 xl:mr-[109px]"
              >
                <Link href="/" class="inline-block mb-5 lg:mb-7">
                  <LogoFooter class="text-white" />
                </Link>
                <p class="mb-3 font-medium">
                  <a
                    class="transition-colors duration-200 ease-in-out hover:text-red-500"
                    href="https://goo.gl/maps/9D3sR8z1j5jkAC5NA"
                    target="_blank"
                    >29 Bis Phan Xích Long, Phường 3, Quận Phú Nhuận,
                    <br class="block sm:hidden" />TP Hồ Chí Minh</a
                  >
                </p>
                <p class="mb-3 font-medium">
                  Bảo hành:
                  <a
                    class="transition-colors duration-200 ease-in-out hover:text-red-500"
                    href="tel:0981064444"
                    >098.106.4444</a
                  >
                </p>
                <p class="font-medium">
                  Email:
                  <a
                    class="transition-colors duration-200 ease-in-out hover:text-red-500"
                    href="mailto:emdepemxinh9999@gmail.com"
                    >emdepemxinh9999@gmail.com</a
                  >
                </p>
              </div>
              <div class="flex-1 mb-8 lg:hidden">
                <h3
                  class="mb-1 text-base font-bold uppercase lg:mb-6 text-grey-900"
                >
                  Thông tin khác
                </h3>
                <Accordions>
                  <Accordion class="py-1">
                    <template v-slot:accordion-trigger>
                      <h3
                        class="relative py-1 text-base font-medium capitalize pr-7 text-grey-900"
                      >
                        <span>Hỗ trợ khách hàng</span>
                        <JIcon name="arrow-down-sm" class="accordion-icon" />
                      </h3>
                    </template>
                    <template v-slot:accordion-content>
                      <div
                        class="list-link"
                        v-for="(linkItem, idx) of policies"
                        :key="idx"
                      >
                        <div class="mb-1.5">
                          <Link
                            :href="
                              route('policy.show', { slug: linkItem.slug })
                            "
                            class="font-medium leading-[21px] text-sm text-grey-700 hover:text-maroon-500 transition-colors duration-200 ease-in-out"
                            >{{ linkItem.title }}</Link
                          >
                        </div>
                      </div>
                    </template>
                  </Accordion>
                </Accordions>
                <Accordions>
                  <Accordion
                    v-for="(item, index) of linkMenus"
                    :key="index"
                    class="py-1"
                  >
                    <template v-slot:accordion-trigger>
                      <h3
                        class="relative py-1 text-base font-medium capitalize pr-7 text-grey-900"
                      >
                        <span>{{ item.linkGrpTitle }}</span>
                        <JIcon name="arrow-down-sm" class="accordion-icon" />
                      </h3>
                    </template>
                    <template v-slot:accordion-content>
                      <div
                        class="list-link"
                        v-for="(linkItem, idx) of item.links"
                        :key="idx"
                      >
                        <div class="mb-1.5">
                          <Link
                            :href="route(linkItem.path)"
                            class="font-medium leading-[21px] text-sm text-grey-700 hover:text-maroon-500 transition-colors duration-200 ease-in-out"
                            >{{ linkItem.name }}</Link
                          >
                        </div>
                      </div>
                    </template>
                  </Accordion>
                </Accordions>
              </div>
              <div class="max-w-[285px] lg:mr-7 xl:mr-10 hidden lg:block">
                <h3 class="mb-5 font-medium p3">Hỗ trợ khách hàng</h3>
                <div
                  v-for="(linkItem, idx) of policies"
                  :key="idx"
                  class="font-medium list-link"
                >
                  <div class="mb-3">
                    <Link
                      :href="route('policy.show', { slug: linkItem.slug })"
                      class="transition-colors duration-200 ease-in-out hover:text-red-500"
                      >{{ linkItem.title }}</Link
                    >
                  </div>
                </div>
              </div>
              <div
                class="max-w-[264px] lg:mr-7 xl:mr-10 hidden lg:block"
                v-for="(item, index) of linkMenus"
                :key="index"
              >
                <h3 class="mb-5 font-medium p3">{{ item.linkGrpTitle }}</h3>
                <div
                  class="font-medium list-link"
                  v-for="(linkItem, idx) of item.links"
                  :key="idx"
                >
                  <div class="mb-3">
                    <Link
                      :href="route(linkItem.path)"
                      class="transition-colors duration-200 ease-in-out hover:text-red-500"
                      >{{ linkItem.name }}</Link
                    >
                  </div>
                </div>
              </div>
              <div class="xl:ml-[61px]">
                <h3 class="mb-3 font-medium lg:mb-5 p3">Kết nối</h3>
                <div class="flex items-center space-x-4">
                  <Link
                    href="#"
                    class="transition duration-200 ease-in-out hover:opacity-70"
                  >
                    <JIcon name="facebook-circle" />
                  </Link>
                  <Link
                    href="#"
                    class="transition duration-200 ease-in-out hover:opacity-70"
                  >
                    <JIcon name="youtube-circle" />
                  </Link>
                  <Link
                    href="#"
                    class="transition duration-200 ease-in-out hover:opacity-70"
                  >
                    <JIcon name="zalo" />
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="bottom-footer">
        <div class="container">
          <div
            class="lg:flex text-center lg:justify-between py-6 lg:pb-[52px] font-semibold"
          >
            <p class="mb-2 caption lg:mb-0">
              &copy;{{ this.isYear }} - Bản quyền của Công ty Em Đẹp Em Xinh
            </p>
            <p class="caption lg:mr-[45px]">
              GPĐKKD số: 0103004893 do Sở KHĐT TP HCM cấp ngày 15/07/2004
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
<script>
import LogoFooter from "./LogoFooter.vue";
import Accordions from "./Accordions.vue";
import Accordion from "./Accordion.vue";
export default {
  components: {
    LogoFooter,
    Accordions,
    Accordion,
  },
  data() {
    return {
      isYear: "",
      linkMenus: [
        {
          linkGrpTitle: "Về Muội Muội",
          links: [
            {
              path: "about",
              name: "Giới thiệu",
            },
            {
              path: "job.index",
              name: "Tuyển dụng",
            },
            {
              path: "post.index",
              name: "Blog",
            },
            {
              path: "contact",
              name: "Liên hệ",
            },
          ],
        },
      ],
    };
  },
  computed: {
    policies() {
      return this.$page.props.footer;
    },
  },
  created: function () {
    this.isYear = new Date().getFullYear();
  },
};
</script>
