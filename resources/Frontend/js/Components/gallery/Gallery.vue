<template>
  <div class="relative hidden xl:pl-24 lg:pl-20 md:block">
    <div class="mb-2 gallery__preview lg:mb-0">
      <button
        class="gallery__control gallery__control--prev"
        :class="currentPreview == 0 && 'opacity-70 pointer-events-none'"
        @click="previous()"
      >
        <JIcon name="arrow-slider-white" />
      </button>
      <button
        class="gallery__control gallery__control--next"
        :class="
          currentPreview == thumbPerCol - 2 && 'opacity-70 pointer-events-none'
        "
        @click="next()"
      >
        <JIcon name="arrow-slider-white" class="rotate-180" />
      </button>
      <div class="relative">
        <div
          class="fake-click absolute inset-0 z-[2]"
          @click="openLightBox"
        ></div>
        <div class="aspect-w-1 aspect-h-1">
          <JImage
            v-if="!checkIsVideo(previewImage)"
            :src="previewImage"
            class="absolute inset-0 object-cover w-full h-full"
            :alt="previewImage"
          />
          <iframe
            v-else
            width="100%"
            height="100%"
            :src="`${getLinkVideo(previewImage)}`"
          ></iframe>
        </div>
      </div>
      <div v-show="!checkIsVideo(previewImage)" class="gallery__lens"></div>
      <div
        v-show="!checkIsVideo(previewImage)"
        class="gallery__zoomview"
        :style="{
          backgroundImage: `url('${previewImage}')`,
        }"
      ></div>
    </div>

    <div
      class="gallery__thumbs lg:w-[88px] lg:absolute lg:-left-1 lg:inset-y-0 overflow-hidden"
    >
      <div
        id="thumbs-wrap"
        class="flex lg:flex-col lg:space-y-1.5 transition-all duration-200 ease-in-out"
      >
        <div
          v-for="(thumb, index) in thumbs"
          :key="index"
          class="flex-1 thumbs xl:max-w-[70px] lg:max-w-[68px] max-w-[70px]"
          :class="currentPreview === index ? 'thumbs-active' : ''"
        >
          <span
            v-if="index == thumbPerCol - 1 && images.length > thumbPerCol"
            class="absolute inset-0.5 z-10 flex items-center justify-center font-bold text-white bg-black bg-opacity-60 p4 pointer-events-none"
            >+{{ images.length - (thumbPerCol - 1) }}</span
          >
          <div
            v-if="
              index != thumbPerCol - 1 ||
              (index == thumbPerCol - 1 && images.length <= thumbPerCol)
            "
            @click="handleClick(index)"
            @mouseover="currentPreviewHover = index"
            @mouseleave="currentPreviewHover = null"
          >
            <div
              v-if="
                checkIsVideo(thumb) &&
                (images.length <= thumbPerCol || index < thumbPerCol - 1)
              "
              class="absolute inset-0.5 z-10 flex items-center justify-center bg-black bg-opacity-60"
            >
              <JIcon name="play" />
            </div>
            <div class="aspect-w-1 aspect-h-1">
              <JImage
                v-if="!checkIsVideo(thumb)"
                :src="thumb"
                class="absolute inset-0 object-cover w-full h-full"
                :alt="thumb"
              />
              <iframe
                v-else
                width="100%"
                height="100%"
                :src="`${getLinkVideo(thumb)}`"
              ></iframe>
            </div>
          </div>
          <div v-else @click="openLightBox" class="aspect-w-1 aspect-h-1">
            <JImage
              v-if="!checkIsVideo(thumb)"
              :src="thumb"
              class="absolute inset-0 object-cover w-full h-full"
              :alt="thumb"
            />
            <iframe
              v-else
              width="100%"
              height="100%"
              :src="`${getLinkVideo(thumb)}`"
            ></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
  <GalleryLightBox
    :index="lightboxIndex"
    :item-name="name"
    :images="images"
    @change="
      (i) => {
        lightboxIndex = i;
      }
    "
  />
  <div class="relative md:hidden">
    <Slider
      :config="{
        cols: '1.3',
        gutter: '8px',
        total: images.length,
        align: 'start',
      }"
    >
      <Slide
        v-for="(item, index) of images"
        :key="item"
        :data-slideIndex="index"
      >
        <div class="relative aspect-w-1 aspect-h-1">
          <JImage
            v-if="!checkIsVideo(item)"
            :src="item"
            class="absolute inset-0 object-cover w-full h-full"
            :alt="item"
            @click="openLightBoxIndex(index)"
          />
          <iframe
            v-else
            width="100%"
            height="100%"
            :src="`${getLinkVideo(item)}`"
          ></iframe>
          <div
            v-if="checkIsVideo(item)"
            class="absolute w-full h-full z=10"
            @click="openLightBoxIndex(index)"
          ></div>
        </div>
      </Slide>
    </Slider>
  </div>
</template>

<script>
import GalleryLightBox from "./LightBox.vue";
export default {
  components: {
    GalleryLightBox,
  },
  props: {
    images: {
      type: Array,
      required: true,
    },
    scale: {
      type: Object,
      default() {
        return {
          lens: 0.3,
          zoomview: 1.2,
        };
      },
    },
    thumbPerCol: {
      type: Number,
      default: 6,
    },
    name: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      thumbs: [],
      preview: null,
      currentPreview: 0,
      currentPreviewHover: null,
      lightboxIndex: null,
      thumbsWrapEle: null,
      currentStep: 0,
    };
  },

  computed: {
    previewImage() {
      return this.currentPreviewHover
        ? this.thumbs[this.currentPreviewHover]
        : this.thumbs[this.currentPreview];
    },
  },

  watch: {
    images: {
      deep: true,
      handler() {
        this.thumbs = this.images.slice(0, this.thumbPerCol);
      },
    },
  },

  created() {
    this.thumbs = this.images.slice(0, this.thumbPerCol);
  },

  mounted() {
    this.thumbsWrapEle = document.getElementById("thumbs-wrap");
    this.preview = document.querySelector(".gallery__preview");
    this.zoomview = document.querySelector(".gallery__zoomview");
    this.lens = document.querySelector(".gallery__lens");
    this.initGallery();
  },

  methods: {
    openLightBox() {
      this.lightboxIndex = this.currentPreview;
      const el = document.body;
      if (this.lightboxIndex == null) {
        el.classList.remove("overflow-hidden");
      } else {
        el.classList.add("overflow-hidden");
      }
    },
    openLightBoxIndex(index) {
      this.lightboxIndex = index;
      const el = document.body;
      if (this.lightboxIndex == null) {
        el.classList.remove("overflow-hidden");
      } else {
        el.classList.add("overflow-hidden");
      }
    },
    handleClick(index) {
      this.currentPreview = index;
      this.$emit("location-image", index + 1);
    },
    initGallery() {
      // const zoomviewSize = this.preview.offsetWidth * this.scale.zoomview
      // if (matchMedia('screen and (min-width: 1280px)').matches) {
      //   this.zoomview.style.width = zoomviewSize + 'px'
      //   this.zoomview.style.height = zoomviewSize + 'px'
      // } else {
      //   this.zoomview.style.width = (zoomviewSize / 2) + 'px'
      //   this.zoomview.style.height = (zoomviewSize / 2) + 'px'
      // }
      this.zoomview.style.width = "100%";
      this.zoomview.style.height = "100%";

      const lensSize = this.preview.offsetWidth * this.scale.lens;
      this.lens.style.width = lensSize + "px";
      this.lens.style.height = lensSize + "px";

      this.calcX = this.zoomview.offsetWidth / this.lens.offsetWidth;
      this.calcY = this.zoomview.offsetHeight / this.lens.offsetHeight;

      this.zoomview.style.backgroundSize =
        this.preview.offsetWidth * this.calcX +
        "px " +
        this.preview.offsetHeight * this.calcY +
        "px";

      this.lens.addEventListener("mousemove", this.moveLens);
      this.preview.addEventListener("mousemove", this.moveLens);
    },
    moveLens(e) {
      let x, y;
      e.preventDefault();
      const position = this.getCursorPosition(e);
      x = position.x - this.lens.offsetWidth / 2;
      y = position.y - this.lens.offsetHeight / 2;

      if (x > this.preview.offsetWidth - this.lens.offsetWidth) {
        x = this.preview.offsetWidth - this.lens.offsetWidth;
      }
      if (x < 0) {
        x = 0;
      }
      if (y > this.preview.offsetHeight - this.lens.offsetHeight) {
        y = this.preview.offsetHeight - this.lens.offsetHeight;
      }
      if (y < 0) {
        y = 0;
      }

      this.lens.style.left = x + "px";
      this.lens.style.top = y + "px";

      this.zoomview.style.backgroundPosition =
        "-" + x * this.calcX + "px -" + y * this.calcY + "px";
    },
    getCursorPosition(e) {
      e = e || window.event;

      let x = 0;
      let y = 0;
      const a = this.preview.getBoundingClientRect();
      x = e.pageX - a.left;
      y = e.pageY - a.top;
      x = x - window.pageXOffset;
      y = y - window.pageYOffset;

      return { x, y };
    },
    hasMore(index) {
      return this.images.length > 6 && index === 5;
    },
    next() {
      if (this.currentPreview < this.thumbs.length - 1) {
        this.currentPreview++;
        // this.slideThumb()
      }
    },
    previous() {
      if (this.currentPreview > 0) {
        this.currentPreview--;
        // this.slideThumb()
      }
    },
    // slideThumb() {
    //   this.currentStep = Math.floor(this.currentPreview / this.thumbPerCol)
    //   this.thumbsWrapEle.style.transform = 'translateY(' + (this.currentStep * (-100)) + '%)'
    // }
    checkIsVideo(url) {
      return (
        (url && url.includes("https://www.youtube.com")) ||
        (url && url.includes("https://vimeo.com/"))
      );
    },

    getLinkVideo(link) {
      if (link) {
        let video_id = "";
        if (link.includes("https://www.youtube.com")) {
          if (link.includes("?v=")) {
            video_id = link.split("?v=").pop();
            let ampersandPosition = video_id.indexOf("&");
            if (ampersandPosition != -1) {
              video_id = video_id.substring(0, ampersandPosition);
            }
          }
          if (link.includes("embed")) {
            video_id = link.split("/").pop();
          }
          return "https://www.youtube.com/embed/" + video_id;
        } else {
          video_id = link.split("/").pop();
          return "https://player.vimeo.com/video/" + video_id;
        }
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.gallery {
  &__preview {
    position: relative;
    cursor: zoom-in;
    img {
      position: absolute;
      height: 100%;
      width: 100%;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      object-fit: contain;
    }
  }

  &__lens {
    position: absolute;
    z-index: 1;
    background-color: rgba(255, 255, 255, 0.2);
    border: 1px solid lightgray;
    transition: opacity 150ms linear;
    opacity: 0;
    display: none;
  }

  &__zoomview {
    position: absolute;
    z-index: 15;
    right: 0;
    top: 0;
    transform: translateX(calc(100% + 1rem));
    background-color: #f5f5f5;
    border: 1px solid gray;
    visibility: hidden;
    display: none;
  }

  &__control {
    @apply absolute top-1/2 -translate-y-1/2 text-black transition duration-200 ease-in-out z-[3];
    &--prev {
      @apply left-2.5;
    }
    &--next {
      @apply right-2.5;
    }
    &:hover {
      @apply opacity-70;
    }
  }

  &__thumbs {
    .thumbs {
      @apply transition-all duration-300 ease-in-out cursor-pointer relative p-0.5;
      &:before {
        @apply absolute left-0 top-0 w-full h-full border border-transparent;
        content: "";
      }
      &:hover,
      &.thumbs-active {
        @apply lg:p-1;
        &:before {
          @apply border-black;
        }
      }
      // &:last-child {
      //   @apply pointer-events-none;
      // }
    }
  }

  // @screen sm {
  //   &__control {
  //     &--prev {
  //       @apply left-0;
  //     }
  //     &--next {
  //       @apply right-0;
  //     }
  //   }
  // }

  @screen md {
    &__lens,
    &__zoomview {
      display: block;
    }
    &__preview:hover {
      .gallery__lens {
        opacity: 1;
      }
      .gallery__zoomview {
        visibility: visible;
      }
    }
  }
}
</style>
