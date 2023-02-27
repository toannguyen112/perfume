<template>
  <div class="accordion-item">
    <div
      class="accordion-trigger"
      :class="[
        { 'accordion-trigger-active': visible },
        { 'col-span-full': type === 'custom' },
      ]"
      @click="open"
    >
      <!-- This slot will handle the title/header of the accordion and is the part you click on -->
      <slot name="accordion-trigger"></slot>
    </div>

    <Transition
      name="accordion"
      @enter="start"
      @after-enter="end"
      @before-leave="start"
      @after-leave="end"
    >
      <!-- <Transition name="accordion"> -->
      <div
        v-show="visible"
        class="accordion-content"
        :class="{
          'col-span-full lg:col-span-8 lg:col-start-3': type === 'custom',
        }"
      >
        <slot name="accordion-content"></slot>
      </div>
    </Transition>
  </div>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: "normal",
    },
  },
  inject: ["Accordion"],
  data() {
    return {
      index: null,
    };
  },
  computed: {
    visible() {
      return this.index == this.Accordion.active;
    },
  },
  methods: {
    open() {
      if (this.visible) {
        this.Accordion.active = null;
      } else {
        this.Accordion.active = this.index;
      }
    },
    start(el) {
      el.style.height = el.scrollHeight + "px";
    },
    end(el) {
      el.style.height = "";
    },
  },
  created() {
    this.index = this.Accordion.count++;
  },
};
</script>

<style lang="scss" scoped>
.accordion-item {
  @apply relative cursor-pointer border-b border-gray-300;
}

.accordion-trigger {
  :deep .accordion-icon {
    @apply absolute right-0.5 top-1/2 -translate-y-1/2 transition-all duration-200 ease-in-out bg-no-repeat bg-center w-2.5 h-1.5;
  }
  :deep .accordion-icon-2 {
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

  :deep .plus {
    @apply block;
  }
  :deep .minus {
    @apply hidden;
  }
}

.accordion-trigger-active {
  :deep .accordion-icon {
    @apply -rotate-180;
  }
  :deep .accordion-icon-2 {
    div {
      &:last-child {
        @apply -rotate-90 opacity-0;
      }
    }
  }

  :deep .plus {
    @apply hidden;
  }
  :deep .minus {
    @apply block;
  }
}

// Temp comment animate
.accordion-enter-active,
.accordion-leave-active {
  will-change: height, opacity;
  transition: height 0.3s ease, opacity 0.3s ease;
  overflow: hidden;
}

.accordion-enter-from,
.accordion-leave-to {
  height: 0 !important;
  opacity: 0;
}
</style>
