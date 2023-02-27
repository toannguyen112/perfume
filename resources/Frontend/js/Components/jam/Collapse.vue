<template>
  <div class="collapse">
    <div
      class="collapse-trigger"
      :class="{ 'collapse-trigger-active': isOpen }"
      @click="toggleCollapse"
    >
      <slot name="collapse-trigger"></slot>
    </div>

    <Transition
      name="collapse"
      @enter="start"
      @after-enter="end"
      @before-leave="start"
      @after-leave="end"
    >
      <div class="collapse-content" v-show="isOpen">
        <slot name="collapse-content"></slot>
      </div>
    </Transition>
  </div>
</template>

<script>
export default {
  props: {
    isOpenDefault: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      isOpen: this.isOpenDefault,
    };
  },
  methods: {
    toggleCollapse() {
      this.isOpen = !this.isOpen;
    },
    start(el) {
      el.style.height = el.scrollHeight + "px";
    },
    end(el) {
      el.style.height = "";
    },
  },
};
</script>

<style lang="scss" scoped>
.collapse {
  @apply relative cursor-pointer border-b border-gray-200;
}
.collapse-trigger {
  @apply cursor-pointer;
  :deep .collapse-icon {
    @apply absolute right-[5px] top-1/2 -translate-y-1/2 transition-all duration-200 ease-in-out w-[15px] h-2.5;
  }
  :deep .collapse-icon-2 {
    @apply relative;
    div {
      @apply bg-gray-500 transition-all ease-out duration-200 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full;
      &:first-child {
        @apply w-3 h-0.5;
      }
      &:last-child {
        @apply w-0.5 h-3;
      }
    }
  }
}
.collapse-trigger-active {
  :deep .collapse-icon {
    @apply -rotate-180;
  }
  :deep .collapse-icon-2 {
    div {
      &:last-child {
        @apply -rotate-90 opacity-0;
      }
    }
  }
}

.collapse-enter-active,
.collapse-leave-active {
  will-change: height, opacity;
  transition: height 0.3s ease, opacity 0.3s ease;
  overflow: hidden;
}
.collapse-enter-from,
.collapse-leave-to {
  height: 0 !important;
  opacity: 0;
}
</style>
