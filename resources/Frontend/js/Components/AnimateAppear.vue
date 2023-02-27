<template>
  <div
    v-observe-visibility="{
      callback: visibilityChanged,
      threshold: 1.0,
      throttle: delay,
      once,
    }"
    class="animate"
    :class="[
      animate,
      { 'is-visible': isVisible, 'card-animated': cardAnimated },
    ]"
  >
    <slot />
  </div>
</template>

<script>
export default {
  props: {
    once: {
      type: Boolean,
      default: true,
    },
    animate: {
      type: String,
      default: "slideup",
    },
    delay: {
      type: Number,
      default: 300,
    },
  },
  data() {
    return {
      isVisible: false,
      cardAnimated: false,
    };
  },
  methods: {
    visibilityChanged(isVisible, entry) {
      this.isVisible = isVisible;
      if (this.animate === "card-animate" && isVisible) {
        this.cardAnimated = true;
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.animate {
  opacity: 0;
  visibility: hidden;
  &.is-visible {
    opacity: 1;
    visibility: visible;
  }
}

.slideup {
  transition: all 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  transform: translateY(100px);
  &.is-visible {
    transform: translateY(0);
  }
}

.appear {
  transition: all 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  &:nth-of-type(2n) {
    transition-delay: 100ms;
  }
  &:nth-of-type(2n + 1) {
    transition-delay: 50ms;
  }
  transform: translateY(100px);
  &.is-visible {
    transform: translateY(0);
  }
}

.opacity {
  transition: all 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  &:not(.is-visible) {
    opacity: 0;
  }
}

.slideLeft {
  transition: all 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  transform: translateX(-100px);
  &.is-visible {
    transform: translateX(0);
  }
}

.slideRight {
  transition: all 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  transform: translateX(100px);
  &.is-visible {
    transform: translateX(0);
  }
}

.card-animate {
  opacity: 1;
  visibility: visible;
  .text-filter {
    filter: grayscale(1);
  }
  &.is-visible {
    filter: grayscale(0%);
  }
  @screen lg {
    &:not(:hover) {
      filter: grayscale(100%);
      .text-opacity {
        opacity: 1;
      }
    }

    opacity: 0;
    transition: all 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    transform: translateY(100px);
    &.card-animated {
      opacity: 1;
      transform: translateY(0);
    }
  }
}
</style>
