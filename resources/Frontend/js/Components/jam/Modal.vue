<template>
  <transition leave-active-class="duration-200">
    <div
      v-show="show"
      class="fixed inset-0 z-[100] px-4 py-6 overflow-y-auto lg:px-0"
    >
      <transition
        enter-active-class="duration-300 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-show="show"
          class="fixed inset-0 transition-all transform"
          @click="close(), offBody()"
        >
          <div class="absolute inset-0 bg-black opacity-80"></div>
        </div>
      </transition>

      <transition
        enter-active-class="duration-300 ease-out"
        enter-from-class="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
        enter-to-class="translate-y-0 opacity-100 sm:scale-100"
        leave-active-class="duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100 sm:scale-100"
        leave-to-class="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
      >
        <div
          v-show="show"
          class="h-full max-h-full transition-all transform pt-9 md:py-10 sm:h-auto sm:mx-auto"
          :class="customMaxWidth ? customMaxWidth : maxWidthClass"
        >
          <slot></slot>
        </div>
      </transition>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
    },
    maxWidth: {
      default: "2xl",
    },
    customMaxWidth: "",
  },

  computed: {
    maxWidthClass() {
      return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
      }[this.maxWidth];
    },
  },

  mounted() {
    document.addEventListener("keydown", this.closeOnEscape);
  },
  unmounted() {
    document.removeEventListener("keydown", this.closeOnEscape);
    document.body.style.overflow = null;
  },

  methods: {
    close() {
      this.$emit("close");
    },
    offBody() {
      this.$emit("offBody");
    },
    closeOnEscape(e) {
      if (e.key === "Escape" && this.show) {
        this.close();
      }
    },
  },
};
</script>
