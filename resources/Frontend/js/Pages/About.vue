<template>
  <main>
    <BreadCrumbPage :item="aboutBreadcrumb" />
    <section class="about-us pb-12 md:pb-[152px]">
      <div class="container">
        <div class="relative">
          <div
            class="grid grid-cols-12 pt-3 md:pt-5 gap-x-2 gap-y-8 sm:gap-y-16 md:gap-y-0 md:gap-x-9"
          >
            <div class="relative grid grid-cols-4 col-span-full md:col-span-7">
              <div class="relative col-span-3 md:col-span-full">
                <AnimateAppear
                  animate="opacity"
                  class="bg-black h-[126.81%] md:h-[128%] absolute w-[40.67%] md:w-[55.38%] top-1/2 right-0 -translate-y-1/2 z-[2]"
                ></AnimateAppear>
                <AnimateAppear
                  animate="opacity"
                  class="max-w-[77.61%] md:max-w-[70.17%] relative z-[5]"
                >
                  <div class="aspect-w-[104] aspect-h-[69] md:aspect-1">
                    <JPicture
                      src="/images/about/ve-chung-toi.jpg"
                      class="absolute inset-0 object-cover w-full h-full"
                      alt="ve-chung-toi-first"
                    />
                  </div>
                </AnimateAppear>
              </div>
              <AnimateAppear
                animate="opacity"
                class="z-10 block special-about md:hidden"
              >
                <JPicture
                  src="/images/about/ve-chung-toi-pattern.svg"
                  alt="ve-chung-toi-second"
                />
              </AnimateAppear>
            </div>
            <div class="col-span-full md:col-span-5">
              <AnimateAppear animate="opacity" class="xl:px-6">
                <h2 class="mb-4 font-bold text-black md:mb-8 h2">
                  Về chúng tôi
                </h2>
                <p class="font-medium text-gray-800 font-display">
                  Muội Muội là đơn vị uy tín, chuyên bán lẻ mỹ phẩm, dụng cụ làm
                  đẹp, thời trang cao cấp đến từ các thương hiệu nổi tiếng thế
                  giới. Với quy mô và hệ thống phân phối chuyên nghiệp, chúng
                  tôi có thể đáp ứng mọi nhu cầu khách hàng.
                </p>
              </AnimateAppear>
            </div>
          </div>
          <JPicture
            src="/images/about/ve-chung-toi-pattern.svg"
            class="z-10 hidden special-about md:block"
            alt="ve-chung-toi-third"
          />
        </div>
      </div>
    </section>
    <section class="overflow-hidden conquer">
      <div class="container">
        <AnimateAppear animate="opacity">
          <h2 class="mb-10 font-bold text-black md:text-center h2 lg:mb-16">
            Chinh phục <br class="md:hidden" />khách hàng bởi
          </h2>
        </AnimateAppear>
        <div
          class="flex flex-col lg:grid lg:items-end lg:grid-cols-12 lg:gap-x-8"
        >
          <div class="flex flex-col justify-start h-full lg:col-span-3">
            <AnimateAppear class="mb-6 lg:mb-[47px]">
              <JIcon name="bag-2" class="mb-4" />
              <h3 class="mb-3 font-bold text-black p3">
                Thế giới hàng hiệu <br class="hidden lg:block" />đa sắc màu
              </h3>
              <p class="font-medium text-justify text-gray-800 font-display">
                <span>
                  Đến với chúng tôi là bạn đang đến với thế giới làm đẹp muôn
                  sắc. Tại đây, bạn có thể lựa chọn bất kì sản phẩm nào đến từ
                  các thương hiệu nổi tiếng thế giới như Dior, Chanel, Yves
                  Saint Laurent, Christian Louboutin...
                </span>
              </p>
            </AnimateAppear>
            <AnimateAppear class="mb-6 lg:mb-[47px]">
              <JIcon name="bag-2" class="mb-4" />
              <h3 class="mb-3 font-bold text-black p3">
                Phân phối cho nhiều <br class="hidden lg:block" />salon nổi
                tiếng
              </h3>
              <p class="font-medium text-justify text-gray-800 font-display">
                <span>
                  Không chỉ phục vụ khách lẻ, sản phẩm từ thương hiệu chúng tôi
                  được rất nhiều nhà tạo mẫu, chuyên gia trang điểm, làm đẹp tin
                  tưởng và lựa chọn sử dụng trong các salon nổi tiếng.
                </span>
              </p>
            </AnimateAppear>
          </div>
          <AnimateAppear
            animate="opacity"
            class="order-last lg:col-span-6 lg:order-none"
          >
            <div class="text-center">
              <JPicture
                src="/images/about/chinh-phuc.png"
                class="inline-block max-w-full -mr-5 sm:mr-0"
              />
            </div>
          </AnimateAppear>
          <div class="flex flex-col justify-start h-full lg:col-span-3">
            <AnimateAppear class="mb-6 lg:mb-[47px]">
              <JIcon name="bag-2" class="mb-4" />
              <h3 class="mb-3 font-bold text-black p3">
                100% nhập khẩu <br class="hidden lg:block" />chính hãng
              </h3>
              <p class="font-medium text-justify text-gray-800 font-display">
                <span>
                  Toàn bộ sản phẩm phân phối bởi Muội Muội đều là hàng thật,
                  được nhập khẩu chính hãng từ Anh, Mỹ, Pháp,... và vượt qua
                  khâu kiểm định nghiêm ngặt nên hoàn toàn đảm bảo về chất lượng
                </span>
              </p>
            </AnimateAppear>
            <AnimateAppear class="mb-6 lg:mb-[47px]">
              <JIcon name="bag-2" class="mb-4" />
              <h3 class="mb-3 font-bold text-black p3">
                Tư vấn theo nhu cầu <br class="hidden lg:block" />khách hàng
              </h3>
              <p class="font-medium text-justify text-gray-800 font-display">
                <span>
                  Hiểu được không dễ để lựa chọn món đồ mỹ phẩm, chăm sóc phù
                  hợp, đội ngũ tư vấn với kiến thức chuyên môn sẽ giúp quý khách
                  chọn được sản phẩm phù hợp đặc thù làn da, mái tóc, vóc
                  dáng,..
                </span>
              </p>
            </AnimateAppear>
          </div>
        </div>
      </div>
    </section>
    <section class="vision-mission">
      <AnimateAppear
        animate="opacity"
        class="h-[250px] md:h-full md:max-h-[480px] overflow-hidden"
      >
        <div class="relative h-full md:h-auto md:aspect-[3/1]">
          <JPicture
            src="/images/about/vision-mission.jpg"
            class="absolute inset-0 object-cover w-full h-full"
            alt="vision-mission"
          />
        </div>
      </AnimateAppear>
      <div class="bg-black">
        <div class="container">
          <div class="lg:grid lg:grid-cols-12 pt-[60px] pb-9">
            <div
              class="relative lg:flex lg:items-center lg:justify-between lg:col-span-full xl:col-span-10 xl:col-start-2"
            >
              <AnimateAppear
                animate="opacity"
                class="text-center lg:w-1/2 xl:w-full xl:max-w-[464px] md:px-8 xl:px-0"
              >
                <h3 class="mb-5 font-bold text-white h2">Tầm nhìn</h3>
                <p class="text-gray-100">
                  Trở thành địa chỉ hàng đầu khi quý khách cần tìm kiếm mỹ phẩm,
                  thời trang cao cấp. Song song đó, chúng tôi tạo và mở rộng
                  cộng đồng yêu làm đẹp để chia sẻ kiến thức sản phẩm và những
                  bí quyết chăm sóc đến từ các beauty blogger và bác sĩ da liễu
                  nổi tiếng
                </p>
              </AnimateAppear>
              <span
                class="block mx-auto my-6 lg:my-0 lg:absolute lg:left-1/2 lg:-translate-x-1/2 w-[105px] lg:w-px h-px lg:h-[105px] bg-white"
              ></span>
              <AnimateAppear
                animate="opacity"
                class="text-center lg:w-1/2 xl:w-full xl:max-w-[464px] lg:px-8 xl:px-0"
              >
                <h3 class="mb-5 font-bold text-white h2">Sứ mệnh</h3>
                <p class="text-gray-100">
                  Liên tục đa dạng mẫu mã sản phẩm, cập nhật xu hướng mới nhất,
                  hoàn thiện hệ thống mua sắm trực tuyến và tăng cường nội dung
                  hữu ích về làm đẹp trên các nền tảng website, Facebook,..
                </p>
              </AnimateAppear>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="py-8 management-team xl:py-14">
      <div class="container">
        <AnimateAppear
          animate="opacity"
          class="max-w-[656px] mx-auto text-center mb-8 md:mb-12"
        >
          <h2 class="mb-4 font-bold text-black h2">Ban điều hành và quản lý</h2>
          <p class="font-medium text-gray-800">
            Với khối óc và sự tận tâm, đội ngũ ban điều hành và quản lý luôn
            mong muốn tạo ra giá trị dành cho khách hàng, truyền cảm hứng về làm
            đẹp
          </p>
        </AnimateAppear>
        <div
          class="grid grid-cols-12 gap-x-2 gap-y-4 md:gap-6 lg:gap-8 xl:gap-y-12"
        >
          <AnimateAppear :delay="100" class="col-span-6">
            <div class="pb-[100%] md:pb-[55.64%] relative mb-2 md:mb-4">
              <JPicture
                src="/images/about/mr-hoang-thien.jpg"
                class="absolute inset-0 object-cover w-full h-full"
                alt="mr-hoang-thien"
              />
            </div>
            <h3 class="font-bold text-black sm:mb-1 font-display">
              Mr. Hoàng Thiện
            </h3>
            <span class="font-semibold text-gray-800 caption"
              >Giám đốc điều hành</span
            >
          </AnimateAppear>
          <AnimateAppear :delay="200" class="col-span-6">
            <div class="pb-[100%] md:pb-[55.64%] relative mb-2 md:mb-4">
              <JPicture
                src="/images/about/mr-tran-vinh-thinh.jpg"
                class="absolute inset-0 object-cover w-full h-full"
                alt="mr-tran-vinh-thinh"
              />
            </div>
            <h3 class="font-bold text-black sm:mb-1 font-display">
              Mr. Trần Vĩnh Thịnh
            </h3>
            <span class="font-semibold text-gray-800 caption"
              >Giám đốc Công nghệ thông tin</span
            >
          </AnimateAppear>
          <AnimateAppear :delay="100" class="col-span-6 md:col-span-4">
            <div class="pb-[100%] md:pb-[85.48%] relative mb-2 md:mb-4">
              <JPicture
                src="/images/about/ms-nguyen-thi-thanh-tuyen.jpg"
                class="absolute inset-0 object-cover w-full h-full"
                alt="mr-nguyen-thi-thanh-tuyen"
              />
            </div>
            <h3 class="font-bold text-black sm:mb-1 font-display">
              Ms. Nguyễn Thị Thanh Tuyền
            </h3>
            <span class="font-semibold text-gray-800 caption"
              >Giám đốc Nhân sự</span
            >
          </AnimateAppear>
          <AnimateAppear :delay="200" class="col-span-6 md:col-span-4">
            <div class="pb-[100%] md:pb-[85.48%] relative mb-2 md:mb-4">
              <JPicture
                src="/images/about/ms-nguyen-thi-nga.jpg"
                class="absolute inset-0 object-cover w-full h-full"
                alt="ms-nguyen-thi-nga"
              />
            </div>
            <h3 class="font-bold text-black sm:mb-1 font-display">
              Ms. Nguyễn Thị Nga
            </h3>
            <span class="font-semibold text-gray-800 caption"
              >Trưởng phòng CSKH</span
            >
          </AnimateAppear>
          <AnimateAppear :delay="300" class="col-span-6 md:col-span-4">
            <div class="pb-[100%] md:pb-[85.48%] relative mb-2 md:mb-4">
              <JPicture
                src="/images/about/ms-nguyen-thi-loan.jpg"
                class="absolute inset-0 object-cover w-full h-full"
                alt="ms-nguyen-thi-loan"
              />
            </div>
            <h3 class="font-bold text-black sm:mb-1 font-display">
              Ms. Nguyễn Thị Loan
            </h3>
            <span class="font-semibold text-gray-800 caption"
              >Giám đốc tài chính</span
            >
          </AnimateAppear>
        </div>
      </div>
    </section>
    <SocialMeta
      title="Muội Muội - Bí quyết cho sắc đẹp toàn diện."
      description="Không chỉ cung cấp những sản phẩm làm đẹp chất lượng, Muội Muội còn mong muốn đem đến cho quý khách những xu hướng làm đẹp, phong cách thời trang cao cấp."
    />
  </main>
</template>

<script>
import App from "@/Layouts/App.vue";
import BreadCrumbPage from "../Components/jam/BreadCrumbPage.vue";
export default {
  layout: App,
  components: {
    BreadCrumbPage,
  },
  data() {
    return {
      aboutBreadcrumb: [
        {
          name: "Giới thiệu",
          link: "about",
        },
      ],
    };
  },
};
</script>

<style lang="scss" scoped>
.special-about {
  @apply absolute bottom-4 left-0 max-w-full;
}

@screen md {
  .special-about {
    @apply bottom-6 max-w-[60%];
  }
}

@screen lg {
  .special-about {
    @apply bottom-6 max-w-full;
  }
}

@screen xl {
  .special-about {
    @apply bottom-12;
  }
}

@screen 2xl {
  .special-about {
    @apply left-auto max-w-[1440px];
    right: calc((100% - 1440px) / 2 + 10px);
  }
}
</style>
