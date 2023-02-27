<template>
  <section class="relative banner-img">
    <div class="xl:py-[156px] md:py-[109px] py-[78px] w-full">
      <div class="container">
        <div class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px]">
          <div
            class="
              xl:col-span-6
              lg:col-span-8
              md:col-span-10
              col-span-full
              xl:col-start-4
              lg:col-start-3
              md:col-start-2
            "
          >
            <h1
              class="
                text-center
                h2
                font-bold
                text-black
                xl:mb-[24px]
                md:mb-[17px]
                mb-[12px]
              "
            >
              Lấy con người làm trọng tâm
            </h1>
            <div class="font-medium text-center text-gray-800">
              Hiểu được nhân tài có vai trò chiến lược trong sự phát triển,
              chúng tôi lấy sự hài lòng của nhân viên làm kim chỉ nam để xây
              dựng văn hoá, môi trường làm việc. Muội Muội sẵn sàng tạo điều
              kiện để bạn khai phá hết tiềm năng của chính mình và đưa ra mức
              đãi ngộ hấp dẫn xứng đáng nỗ lực bạn bỏ ra.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="xl:mt-[113px] mt-[57px]">
    <div class="container">
      <div class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px] relative">
        <div class="absolute top-0 right-0 hidden img-size md:block">
          <JPicture
            src="/images/recruitment/section-first-right.jpg"
            class="object-cover w-full h-full"
            alt="section-first-right"
          />
        </div>
        <div
          class="
            xl:col-span-10
            col-span-full
            xl:col-start-2
            grid grid-cols-10
            xl:gap-x-[32px]
            md:gap-x-[22px]
            xl:gap-y-[56px]
            md:gap-y-[39px]
            gap-y-[28px]
            relative
            z-[1]
          "
        >
          <div class="font-bold text-black md:col-span-4 col-span-full h2">
            Giá trị mang đến <br class="hidden lg:block" />cho mỗi nhân viên
          </div>
          <div class="grid grid-cols-2 col-span-10 lg:grid-cols-4">
            <div
              v-for="(item, index) in list"
              :key="index"
              class="bg-black xl:p-[40px] md:p-[28px] p-[20px]"
            >
              <div
                class="
                  text-[72px]
                  leading-[120%]
                  text-white
                  xl:mb-[96px]
                  lg:mb-[67px]
                  md:mb-[48px]
                  mb-[12px]
                "
              >
                {{ item.number }}
              </div>
              <div class="font-bold text-white p4" v-html="item.title"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section
    class="
      xl:mt-[103px]
      md:mt-[72px]
      mt-[52px]
      bg-img
      xl:pt-[427px]
      md:pt-[299px]
      xl:pb-[49px]
      md:pb-[35px]
    "
  >
    <div class="block md:hidden mb-[24px]">
      <JPicture
        src="/images/recruitment/section-second.jpg"
        class="object-cover w-full h-full"
        alt="job-bg"
      />
    </div>
    <div class="container">
      <div class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px]">
        <div
          class="
            xl:col-span-5
            lg:col-span-6
            md:col-span-8
            col-span-full
            xl:col-start-8
            lg:col-start-7
            md:col-start-5
            xl:p-[18px]
            md:p-[12px]
            p-[9px]
            relative
          "
        >
          <div
            class="
              w-[62px]
              h-[62px]
              bg-transparent
              border-t-[3px] border-l-[3px] border-black
              absolute
              top-0
              left-0
            "
          ></div>
          <div
            class="
              w-[62px]
              h-[62px]
              bg-transparent
              border-b-[3px] border-r-[3px] border-black
              absolute
              bottom-0
              right-0
            "
          ></div>
          <div
            class="
              bg-black
              xl:p-[56px]
              md:p-[39px]
              p-[24px]
              text-white
              p1
              font-bold
            "
          >
            "Đừng bỏ lỡ bất kỳ cơ hội <br class="show-break" />
            nào đến<br class="show-breakLine" />
            với bạn"
          </div>
        </div>
      </div>
    </div>
  </section>
  <section
    class="
      xl:mt-[72px]
      md:mt-[50px]
      mt-[36px]
      xl:mb-[72px]
      md:mb-[50px]
      mb-[36px]
    "
  >
    <div class="container">
      <div class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px]">
        <div
          class="
            xl:mb-[48px]
            md:mb-[34px]
            mb-[24px]
            xl:col-span-6
            lg:col-span-8
            md:col-span-10
            col-span-full
            xl:col-start-4
            lg:col-start-3
            md:col-start-2
          "
        >
          <div
            class="
              h2
              font-bold
              text-black
              xl:mb-[24px]
              md:mb-[17px]
              mb-[12px]
              text-center
            "
          >
            Vị trí đang tuyển
          </div>
          <div class="font-medium text-center text-gray-800">
            Hiểu được nhân tài có vai trò chiến lược trong sự phát triển, chúng
            tôi lấy sự hài lòng của nhân viên làm kim chỉ nam để xây dựng văn
            hoá, môi trường làm việc. Muội Muội sẵn sàng tạo điều kiện để bạn
            khai phá hết tiềm năng của chính mình và đưa ra mức đãi ngộ hấp dẫn
            xứng đáng nỗ lực bạn bỏ ra.
          </div>
        </div>
        <div
          class="
            xl:col-span-10
            col-span-full
            xl:col-start-2 xl:space-y-[16px]
            md:space-y-[12px]
            space-y-[8px]
          "
        >
          <Link
            v-for="(item, idx) in job_data"
            :key="idx"
            :href="route('job.show', { slug: item.slug })"
            class="
              grid
              lg:grid-cols-10
              grid-cols-12
              xl:gap-x-[32px]
              md:gap-x-[22px]
              border border-gray-300
              hover:border-gray-800
              duration-150
            "
          >
            <div
              class="
                xl:px-[32px]
                md:px-[22px]
                px-[16px]
                xl:py-[24px]
                md:py-[17px]
                py-[12px]
                lg:col-span-7
                col-span-full
              "
            >
              <div class="p3 font-bold text-gray-800 mb-[3px] line-clamp-2">
                {{ item.title }}
              </div>
              <div
                class="
                  md:mb-[4px]
                  mb-[10px]
                  flex
                  max-md:flex-col
                  xl:space-x-[16px]
                  md:space-x-[12px]
                  max-md:space-y-[2px]
                "
              >
                <span class="flex-shrink-0 font-medium text-gray-600 w-[146px]"
                  >Hình thức công việc</span
                >
                <span class="font-medium text-gray-800 line-clamp-2">{{
                  item.work_form
                }}</span>
              </div>
              <div
                class="
                  md:mb-[4px]
                  mb-[10px]
                  flex
                  max-md:flex-col
                  xl:space-x-[16px]
                  md:space-x-[12px]
                  max-md:space-y-[2px]
                "
              >
                <span
                  class="flex-shrink-0 font-medium text-gray-600 md:w-[146px]"
                  >Địa điểm làm việc</span
                >
                <span class="font-medium text-gray-800">{{
                  item.address
                }}</span>
              </div>
              <div
                class="
                  flex
                  max-md:flex-col
                  xl:space-x-[16px]
                  md:space-x-[12px]
                  max-md:space-y-[2px]
                "
              >
                <span
                  class="flex-shrink-0 font-medium text-gray-600 md:w-[146px]"
                  >Thời hạn nộp hồ sơ</span
                >
                <span class="font-medium text-gray-800">{{
                  item.formatted_expected_time
                }}</span>
              </div>
            </div>
            <div
              class="
                pr-[16px]
                xl:pl-[65px]
                md:pl-[22px]
                pl-[16px]
                xl:py-[24px]
                md:py-[17px]
                py-[12px]
                lg:col-span-3
                col-span-full
                flex
                lg:items-end
              "
            >
              <JButton
                label="ỨNG TUYỂN NGAY"
                variant="primary-square"
                class="w-full xl:py-[26px] md:py-[20px] py-[14px]"
              />
            </div>
          </Link>
        </div>
      </div>
    </div>
  </section>
  <SocialMeta
    title="Muội Muội - Cơ hội thử thách trong môi trường sáng tạo."
    description="Muội Muội tìm kiếm những người trẻ, tài năng và sẵn sàng cống hiến hết mình cho công việc mà tại đó, môi trường luôn phù hợp để học hỏi và phát triển bản thân."
  />
</template>

<script>
import App from "@/Layouts/App.vue";
export default {
  layout: App,
  props: ["jobs"],
  data() {
    return {
      list: [
        {
          number: "01",
          title: "Môi trường làm việc chuyên nghiệp",
        },
        {
          number: "02",
          title:
            "Văn hoá doanh<br class='hidden lg:block'/> nghiệp cởi mở, <br class='hidden lg:block'/>sáng tạo",
        },
        {
          number: "03",
          title:
            "Lộ trình thăng tiến <br class='hidden md:block xl:hidden'/> rõ ràng",
        },
        {
          number: "04",
          title:
            "Thu nhập cạnh tranh, hoa hồng <br class='block xl:hidden'/> hấp dẫn",
        },
      ],
      job_data: this.jobs.data,
    };
  },
  created() {
    window.addEventListener("scroll", this.scroll);
  },

  unmounted() {
    window.removeEventListener("scroll", this.scroll);
  },
  methods: {
    scroll: function () {
      const self = this;
      const footer = document.getElementById("footer_id").scrollHeight;

      window.onscroll = function () {
        if (
          window.innerHeight + window.scrollY >=
          document.body.scrollHeight - footer
        ) {
          const job_links = self.jobs.links;
          const next = job_links[job_links.length - 1];
          if (next.url) {
            self.$inertia.visit(next.url, {
              preserveScroll: true,
              preserveState: true,
              only: ["jobs"],
            });
            self.job_data.push(...self.jobs.data);
          }
        }
      };
    },
  },
};
</script>
<style lang="scss" scoped>
.bg-img {
  background: url("/images/recruitment/section-second.jpg");
  @apply md:bg-cover max-md:bg-none;
  background-repeat: no-repeat;
}
.banner-img {
  background: url("/images/recruitment/banner.jpg");
  background-size: cover;
  background-repeat: no-repeat;
}
.show-break {
  @apply hidden;
}
.show-breakLine {
  @apply hidden;
}

@media screen and (min-width: 1280px) {
  .show-breakLine {
    @apply block;
  }
}

@media screen and (min-width: 1440px) {
  .show-break {
    @apply block;
  }
  .show-breakLine {
    @apply hidden;
  }
}

@media screen and (max-width: 1366px) {
  .img-size {
    @apply max-w-[450px];
  }
}
</style>
