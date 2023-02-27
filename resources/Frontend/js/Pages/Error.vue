<template>
  <section class="flex items-center h-screen bg-img">
    <div class="container">
      <div class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px]">
        <div
          class="xl:col-span-4 lg:col-span-6 md:col-span-8 col-span-full xl:col-start-5 lg:col-start-4 md:col-start-3"
        >
          <div>
            <JPicture
              src="/images/404/error.png"
              class="object-cover w-full h-full"
              alt="bg-error"
            />
          </div>
          <div>
            <div class="p2 font-bold text-black mb-[8px] text-center">
              KHÔNG TÌM THẤY TRANG NÀY
            </div>
            <div class="font-medium text-center text-gray-800">
              Địa chỉ trang không tồn tại hoặc đã xảy ra lỗi sự cố.
            </div>
            <div
              class="flex justify-center xl:mt-[32px] md:mt-[22px] mt-[16px]"
            >
              <JButton
                label="QUAY LẠI TRANG CHỦ"
                variant="primary-square"
                path="/"
              />
            </div>
          </div>
        </div>
      </div>

      <details class="mt-10 text-sm opacity-0">
        <summary class="mb-4">More information</summary>
        <code>
          {{ error }}
        </code>
      </details>
    </div>
  </section>
</template>
<style lang="scss" scoped>
.bg-img {
  background: url("/images/404/bg-error.jpg");
  background-size: cover;
  background-repeat: no-repeat;
}
</style>
