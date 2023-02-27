<template>
  <transition name="fade">
    <div v-if="open" class="md:overflow-y-auto lightbox">
      <div
        class="w-[1000px] max-w-[100vw] my-auto z-10 md:py-[24px] md:px-[16px]"
      >
        <div class="h-screen bg-white md:h-auto">
          <div
            class="block text-black p3 font-bold py-3.5 px-4 text-center relative"
          >
            <div class="line-clamp-1 pr-[16px]">{{ itemName }}</div>
            <div
              class="absolute top-0 right-0 md:p-[14px] p-[6px] cursor-pointer"
              @click.stop="close"
              @wheel.prevent
              @touchmove.prevent
            >
              <JIcon name="close-circle" />
            </div>
          </div>
          <div
            class="relative md:h-[600px] h-custom max-w-[620px] mx-auto hidden md:block"
          >
            <img
              v-if="!checkIsVideo(images[index])"
              :key="images[index] || images[index] || ''"
              :src="images[index] || images[index] || ''"
              :alt="images[index].id"
              class="absolute inset-0 object-contain w-full h-auto my-auto object-cotain"
              @click.stop="next"
            />
            <iframe
              v-else
              class="absolute inset-0 w-full h-auto min-h-[400px] lightbox__content__video my-auto"
              width="100%"
              height="100%"
              :src="`${getLinkVideo(images[index])}`"
            ></iframe>

            <div
              v-if="prevImage !== index"
              class="lightbox__content__control lightbox__content__control--prev"
              @click.stop="prev"
            >
              <JIcon name="arrow-slider-white" />
            </div>

            <div
              v-if="nextImage !== index"
              class="lightbox__content__control lightbox__content__control--next"
              @click.stop="next"
            >
              <JIcon name="arrow-slider-white" class="rotate-180" />
            </div>
          </div>

          <div
            v-if="!noThumbs"
            class="py-[16px] lightbox__thumbs hidden md:block"
            @touchmove.stop
            @wheel.stop
          >
            <div
              v-for="(image, idx) in images"
              :key="idx"
              class="lightbox__thumbs__item"
              :class="{ 'lightbox__thumbs__item--active': index === idx }"
              @click.stop="goto(idx)"
            >
              <img v-if="!checkIsVideo(image)" :src="image" />
              <iframe
                v-else
                class="lightbox__thumbs__item"
                :class="{ 'lightbox__thumbs__item--active': index === idx }"
                width="100%"
                height="100%"
                :src="`${getLinkVideo(image)}?autoplay=0&showinfo=0&controls=0`"
              ></iframe>
              <span
                v-if="checkIsVideo(image)"
                class="absolute top-0 left-0 z-10 flex items-center justify-center bg-black rounded-full bg-opacity-30 w-[42px] h-[42px] m-[5px]"
              >
                <JIcon name="play-gallery" />
              </span>
            </div>
          </div>

          <Slider
            class="md:hidden"
            :config="{
              cols: 1,
              gutter: '0px',
              total: images.length,
            }"
          >
            <template #default="{ current }">
              <Slide
                v-for="(image, idx) of images"
                :key="idx"
                class="relative h-custom"
                :class="{ active: idx === current }"
              >
                <img
                  v-if="!checkIsVideo(image)"
                  :src="image"
                  class="absolute inset-0 object-contain w-full h-auto my-auto object-cotain"
                />
                <iframe
                  v-else
                  class="absolute inset-0 w-full h-auto min-h-[400px] lightbox__content__video my-auto"
                  width="100%"
                  height="100%"
                  :src="`${getLinkVideo(image)}`"
                ></iframe>
              </Slide>
            </template>
            <template #dots="{ navigate, current, dots }">
              <div
                ref="thumbs"
                class="py-[16px] lightbox__thumbs"
                @touchmove.stop
                @wheel.stop
              >
                <div
                  v-for="(dot, index_dot) in dots"
                  :key="index_dot"
                  ref="thumbItems"
                  class="slide-dot timeline-box lightbox__thumbs__item"
                  :class="{
                    'lightbox__thumbs__item--active': index_dot === current,
                  }"
                  @click="navigate(index_dot), goto(index_dot)"
                >
                  <img
                    v-if="!checkIsVideo(images[index_dot])"
                    :src="images[index_dot]"
                  />
                  <iframe
                    v-else
                    class="lightbox__thumbs__item"
                    :class="{ 'lightbox__thumbs__item--active': index === idx }"
                    width="100%"
                    height="100%"
                    :src="`${getLinkVideo(
                      images[index_dot]
                    )}?autoplay=0&showinfo=0&controls=0`"
                  ></iframe>
                  <span
                    v-if="checkIsVideo(images[index_dot])"
                    class="absolute top-0 left-0 z-10 flex items-center justify-center bg-black rounded-full bg-opacity-30 w-[42px] h-[42px] m-[5px]"
                  >
                    <JIcon name="play-gallery" />
                  </span>
                </div>
              </div>
            </template>
          </Slider>
        </div>
      </div>

      <div
        class="fixed w-full h-full bg-black opacity-70"
        @click.stop="close"
        @wheel.prevent
        @touchmove.prevent
      ></div>
    </div>
  </transition>
</template>

<script>
/**
 * The lightbox component
 *
 * @event change - the index has been changed. The current index is sent as payload
 */
export default {
  name: "lightbox",
  model: {
    prop: "index",
    event: "change",
  },
  props: {
    itemName: {
      type: String,
      default: "",
    },
    /**
     * List of images to display in the lightbox
     *
     * Any array item can be either a string containing the image URL or an object.
     * The object fields are the following:
     * - `src` - image URL
     * - `thumbnail` - thumbnail URL. If omitted, the image URL will be used
     * - `caption` - caption text to be overlayed on the image
     * - `alt` - alt text. If omitted, the caption will be used
     */
    images: {
      type: Array,
      default: () => [],
    },

    /**
     * The index of the image to be opened in the lightbox
     */
    index: {
      type: Number,
      default: null,
    },

    /**
     * Indicates whether the images carousel should loop around itself
     */
    loop: {
      type: Boolean,
      default: false,
    },

    /**
     * When enabled, the thumbnails are hidden
     */
    noThumbs: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      /**
       * Transition name to be used on photo switch
       *
       * @type string
       */
      slide: "next",

      /**
       * Indication that the swipe action has been executed
       *
       * @type boolean
       */
      swipeDone: false,

      /**
       * The swipe distance or null if no swipe action is being executed
       *
       * @type null|number
       */
      swipeX: null,
    };
  },
  computed: {
    /**
     * Indicates whether the lightbox is open
     *
     * @returns {boolean} open state
     */
    open() {
      return this.index != null;
    },

    /**
     * Index of the image _previous_ to the one being open
     *
     * @returns {number} index
     */
    prevImage() {
      if (this.index > 0) {
        return this.index - 1;
      }
      if (this.loop) {
        return this.images.length - 1;
      }
      return this.index;
    },

    /**
     * Index of the image _next_ to the one being open
     *
     * @returns {number} index
     */
    nextImage() {
      if (this.index < this.images.length - 1) {
        return this.index + 1;
      }
      if (this.loop) {
        return 0;
      }
      return this.index;
    },
  },
  watch: {
    open(value) {
      if (value) {
        window.addEventListener("keyup", this.keyup);
      } else {
        window.removeEventListener("keyup", this.keyup);
      }
    },
    /*
     * Center the thumbnails' scrollbar to the clicked image
     */
    index(newIndex) {
      if (!this.noThumbs && newIndex != null) {
        this.$nextTick(() => {
          const { thumbs, thumbItems } = this.$refs;
          const curThumb = thumbItems[newIndex];
          // If the thumbnail's center X position is bigger than the half of the screen
          // then scroll the thumbs scrollbar to center the image
          if (
            curThumb.offsetLeft + curThumb.clientWidth / 2 >
            window.innerWidth / 2
          ) {
            const distance = curThumb.offsetLeft - window.innerWidth / 2;
            // if there's space to scroll to center the image, then center it
            // otherwise use the maximum scroll width
            if (distance < thumbs.scrollWidth) {
              thumbs.scrollLeft = distance + curThumb.clientWidth / 2;
            } else {
              thumbs.scrollLeft = thumbs.scrollWidth;
            }
          } else {
            thumbs.scrollLeft = 0;
          }
        });
      }
    },
  },
  mounted() {
    this.setVh();
    window.addEventListener("resize", this.setVh);
  },
  methods: {
    setVh() {
      let vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty("--vh", `${vh}px`);
    },
    /**
     * Closes the lightbox
     */
    close() {
      const oldIndex = this.index;
      this.goto(null);
      this.$emit("close", oldIndex);
      document.body.classList.remove("overflow-hidden");
    },

    /**
     * Navigates to the previous image
     */
    prev() {
      this.$emit("prev", this.prevImage);
      this.goto(this.prevImage, "prev");
    },

    /**
     * Navigates to the next image
     */
    next() {
      this.$emit("next", this.nextImage);
      this.goto(this.nextImage, "next");
    },

    /**
     * Navigates to the image with a specific index
     * @param {null|number} idx image index
     * @param {string} [slide] name of the transition to be used
     */
    goto(idx, slide) {
      this.slide = slide || (this.index < idx ? "next" : "prev");

      this.$emit("change", idx);
    },

    /**
     * Handles the `keyup` event
     *
     * @param {KeyboardEvent} e event
     */
    keyup(e) {
      if (
        e.code === "ArrowRight" ||
        e.key === "ArrowRight" ||
        e.key === "Right" ||
        e.keyCode === 39
      ) {
        this.next();
      } else if (
        e.code === "ArrowLeft" ||
        e.key === "ArrowLeft" ||
        e.key === "Left" ||
        e.keyCode === 37
      ) {
        this.prev();
      } else if (
        e.code === "Escape" ||
        e.key === "Escape" ||
        e.key === "Esc" ||
        e.keyCode === 27
      ) {
        this.close();
      }
    },

    /**
     * Handles the `touchstart` event
     *
     * The `touchstart` event on the image indicates the beginning of the swipe action.
     *
     * @param {TouchEvent} e event
     */
    swipeStart(e) {
      this.swipeDone = false;
      if (e.changedTouches.length === 1) {
        this.swipeX = e.changedTouches[0].screenX;
      }
    },

    /**
     * Handles the `touch` event
     *
     * The `touch` event registered after the `touchstart` event indicates the swipe being in action
     *
     * @param {TouchEvent} e event
     */
    swipe(e) {
      if (!this.swipeDone && e.changedTouches.length === 1) {
        const swipeDistance = e.changedTouches[0].screenX - this.swipeX;

        if (swipeDistance >= 50) {
          this.prev();
          this.swipeDone = true;
        } else if (swipeDistance <= -50) {
          this.next();
          this.swipeDone = true;
        }
      }
    },

    checkIsVideo(url) {
      return (
        url.includes("https://www.youtube.com") ||
        url.includes("https://vimeo.com/")
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
.lightbox {
  // background-color: rgba(25, 25, 26, 0.8);
  bottom: 0;
  left: 0;
  position: fixed;
  right: 0;
  text-align: center;
  top: 0;
  z-index: 3000;
  @apply flex items-center flex-col;
}

.lightbox__content {
  height: 80%;
  position: relative;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.lightbox__content--no-thumbs {
  height: 100%;
}

.lightbox__content__image__caption {
  position: absolute;
  bottom: 0;
  padding: 0.5rem 0.75rem;
  border-radius: 5px;
  color: white;
  background-color: rgba(25, 25, 26, 0.8);
  opacity: 0.75;
  font-family: sans-serif;
  font-weight: lighter;
  font-size: 1.2rem;
}

.lightbox__content__control {
  position: absolute;
  opacity: 1;
  cursor: pointer;
  transition: opacity 300ms ease;
  user-select: none;
}

.lightbox__content__control:hover {
  opacity: 1;
}

.lightbox__content__control--prev,
.lightbox__content__control--next {
  top: 50%;
  transform: translateY(-50%);
  svg {
    @apply w-12 h-12;
  }
}
.lightbox__content__control--prev {
  @apply left-2 sm:left-[-56px] lg:left-[-72px];
}

.lightbox__content__control--next {
  @apply right-2 sm:right-[-56px] lg:right-[-72px];
}

.lightbox__content__control--close {
  @apply -top-11 right-0 sm:top-[-18px] sm:-right-11;
}

.lightbox__thumbs {
  @apply mx-auto leading-[0] px-2.5 overflow-hidden scroll-smooth whitespace-nowrap;
}

.lightbox__thumbs__item {
  @apply relative inline-block overflow-hidden cursor-pointer  w-[52px]  h-[52px] mx-[3px];
  img {
    @apply w-[42px]  h-[42px] object-cover rounded-full m-[5px];
  }
  iframe {
    @apply w-[42px]  h-[42px] object-cover rounded-full m-[5px];
  }
  &:before {
    @apply absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2  w-[52px]  h-[52px] border border-transparent transition-all ease-in-out duration-200;
    content: "";
  }
}

.lightbox__thumbs__item--active {
  &:before {
    @apply border-black border-[2px] border-solid rounded-full;
  }
}

/*******************/
/*   TRANSITIONS   */
/*******************/

.fade-enter,
.next-enter,
.prev-enter,
.fade-leave-active,
.prev-leave-active,
.next-leave-active {
  opacity: 0;
}

.fade-enter-active,
.fade-leave-active,
.prev-leave-active,
.next-leave-active {
  transition: opacity 300ms ease;
}

.prev-enter {
  transform: translateX(-40px);
}

.next-enter {
  transform: translateX(40px);
}

.next-enter-active,
.prev-enter-active {
  transition: opacity 300ms ease, transform 300ms ease;
}

.h-custom {
  @screen max-md {
    height: calc(calc(var(--vh, 1vh) * 100) - 143px);
  }
}
</style>
