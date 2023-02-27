<template>
  <section class="relative z-[1000]">
    <div class="z-[100] relative">
      <div
        class="fixed inset-x-0 inset-y-0 z-[9998] bg-black opacity-60"
        @click="$emit('close')"
      ></div>

      <div
        class="fixed top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 bg-transparent w-full width-responsive h-[90vh] z-[9999] px-[20px] lg:px-0"
      >
        <div
          class="absolute md:right-[-4%] right-[2%] md:top-[-1.5%] top-[-1%]"
        >
          <div
            class="bg-gray-50 flex items-center justify-center cursor-pointer rounded-full md:w-[40px] w-[30px] md:h-[40px] h-[30px]"
            @click="$emit('close')"
          >
            <JIcon name="close" />
          </div>
        </div>
        <div class="bg-yellow-50 overflow-hidden rounded h-full">
          <div class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px]">
            <div class="md:hidden h-[300px] block col-span-full">
              <JPicture
                src="/images/about/popup-img.jpg"
                class="picture-cover"
                alt="popup-img"
              />
            </div>
            <div
              id="overflow-scrollbar"
              class="overflow-hidden md:col-span-6 col-span-full xl:pl-[124px] md:pl-[62px] pl-[40px] lg:py-[32px] md:py-[22px] py-[16px] relative"
            >
              <div
                class="height-popup overflow-y-auto xl:pr-[48px] lg:pr-[34px] pr-[24px]"
              >
                <JIcon
                  name="quote"
                  class="absolute xl:left-[48px] md:left-[10px] left-[5px] max-md:w-[30px] max-md:h-[30px]"
                />
                <div class="text-green-700 font-medium body-14">
                  <div>Chào các bạn</div>
                  <br />
                  <div>
                    Cô tên là Phan Thị Thu Hà, hiện tại cô đang sinh sống và làm
                    việc cùng gia đình tại XOM Organic Farm. Khu vườn rộng nửa
                    hecta là nơi gia đình ba thế hệ của cô cùng nhau chung sống
                    và làm việc. Trước khi về hưu và gắn bó hoàn toàn với mảnh
                    vườn này cô chú là những Dược sĩ với nhiều năm công tác
                    trong nghề. Xuất phát từ niềm yêu thích tìm tòi học hỏi các
                    loài cây thuốc và cây thảo dược, dần dần hình thành trong cô
                    một tình yêu vô hạn đối với thiên nhiên, cây cỏ. Thiên nhiên
                    luôn đem đến cho cô nguồn cảm hứng bất tận và luôn thúc đẩy
                    niềm đam mê, sáng tạo trong công việc của cô. Tình yêu đó
                    cũng đã ảnh hưởng rất nhiều đến thói quen, tính cách của cô
                    trong cuộc sống hàng ngày. Căn phòng cô ở đơn sơ giản dị,
                    cửa sổ luôn mở đón gió và ánh nắng, giường ngủ nhìn ra luôn
                    thấy cây cối, xanh tươi bốn mùa. Dường như mỗi khi hít thở
                    không khí trong lành ở nơi này là mỗi lần cô được hít thở
                    ngập tràn năng lượng sống tích cực hàng ngày. Mặc dù cuộc
                    sống vẫn có những việc phải lo toan nhưng với một tâm thế
                    như vậy cùng gia đình ở bên, việc khó khăn nào cô cũng đều
                    vượt qua.
                  </div>
                  <br />
                  <div>
                    Cô biết rằng, ở đâu đấy trên khắp lãnh thổ Việt Nam còn có
                    rất nhiều bạn yêu thiên nhiên giống cô nhưng cuộc đời xô đẩy
                    nhiều bề khiến bạn phải trải qua cuộc sống ngột ngạt mỗi
                    ngày với bốn bức tường. Không sao, khi mệt mỏi quá cứ về đây
                    cùng cô! Cô xin làm người truyền cảm hứng vô điều kiện cho
                    tất cả những bạn nào miễn là bạn ấy có cùng đam mê và sở
                    thích giống như cô.
                  </div>
                  <br />
                  <div>
                    Cô luôn mong muốn có thể làm ra những sản phẩm từ chính bàn
                    tay của mình cùng với những kiến thức về nghề nghiệp Dược đã
                    gắn bó với cô trong suốt quãng đời tuổi trẻ cho đến khi về
                    hưu như hiện nay. Sản phẩm của cô sẽ là những sản phẩm mang
                    tính Organic thuần túy nhất có thể. Cô luôn tâm niệm khi làm
                    bất cứ việc gì dù lớn dù nhỏ, cô cũng đều tập trung cao độ,
                    mang hết nhiệt huyết, đam mê vào công việc. Các bạn có biết
                    chăng, cô đã có một thương hiệu cá nhân mang tên “Cô Hà” do
                    người thân và bạn bè đặt cho đấy các bạn ạ.
                  </div>
                  <br />
                  <div>
                    Hiện nay cô đã 56 tuổi, sức khỏe cô vẫn rất tốt, huyết áp
                    vẫn giữ như xưa không thay đổi. Cô luôn lạc quan, vui vẻ,
                    yêu đời, yêu âm nhạc, yêu Yoga cổ điển, thích hát, đọc sách
                    và học tập các lớp ngắn hạn nâng cấp nhận thức cho bản thân.
                    Bên cạnh đó cô cũng rất yêu lao động chân tay, cuốc đất, làm
                    cỏ, tưới cây là những công việc yêu thích và thật gần gũi
                    đối với cô.
                  </div>
                  <br />
                  <div>
                    Đầu năm 2020 cô đã mày mò nghiên cứu, nấu thử mẻ dầu gội Bồ
                    Kết đầu tiên cho bản thân và gia đình dùng thử. Đến nay sản
                    phẩm của cô đã hoàn thiện và cô đã đa dạng hóa thêm nhiều
                    dòng sản phẩm khác. Cô đã viết quy trình và đăng ký chất
                    lượng cũng như đăng ký bản quyền thương hiệu cho các sản
                    phẩm với ước mong sản phẩm của mình đến được tay các bạn gần
                    xa trên mọi vùng miền của đất nước thân yêu.
                  </div>
                  <br />
                  <div>
                    Cô mong các bạn hãy yên tâm tuyệt đối với các sản phẩm do cô
                    làm ra. Cô cam kết chịu trách nhiệm hoàn toàn về chất lượng
                    của sản phẩm đến khâu cuối cùng là khâu dùng tại nhà của các
                    bạn.
                  </div>
                  <br />
                  <div>Cô yêu các bạn!</div>
                  <div>Cô yêu cuộc đời này!</div>
                  <div>Vô điều kiện.</div>
                  <div>~</div>
                  <div>Chủ nhân của XOM</div>
                  <div>CÔ HÀ</div>
                </div>
              </div>
            </div>
            <div class="col-span-6 md:block hidden">
              <JPicture
                src="/images/about/popup-img.jpg"
                class="picture-cover"
                alt="popup-img"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<style lang="scss" scoped>
#overflow-scrollbar {
  ::-webkit-scrollbar {
    @apply w-[8px];
  }
  ::-webkit-scrollbar-track-piece {
    @apply bg-white h-[75px];
  }
  ::-webkit-scrollbar-thumb {
    @apply bg-gray-300;
  }
}
.width-responsive {
  width: calc(375px - 2rem);
}

.height-popup {
  @apply h-[40vh];
}

@media screen and (min-width: 768px) {
  .width-responsive {
    width: calc(768px - 3rem - 20px);
  }
  .height-popup {
    @apply h-[84vh];
  }
}

@media screen and (min-width: 1024px) {
  .width-responsive {
    width: calc(1024px - 3rem - 100px);
  }
  .height-popup {
    @apply h-[83vh];
  }
}

@media screen and (min-width: 1280px) {
  .width-responsive {
    width: calc(1280px - 3.5rem - 100px);
  }
  .height-popup {
    @apply h-[81vh];
  }
}

@media screen and (min-width: 1440px) {
  .width-responsive {
    width: calc(1280px - 3.5rem);
  }
  .height-popup {
    @apply h-[84vh];
  }
}
</style>
