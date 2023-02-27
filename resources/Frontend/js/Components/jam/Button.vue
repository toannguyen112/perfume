<template>
  <button
    v-if="href === null"
    :type="type"
    :disabled="loading || disabled"
    class="btn"
    :class="buttonClass"
  >
    <i v-if="loading" class="gg-spinner mr-[3px]"></i>
    <template v-if="label">
      <span>{{ label }} </span>
    </template>
    <div v-else class="flex items-center space-x-2">
      <slot></slot>
    </div>
  </button>
  <Link
    v-else
    :href="href"
    :disabled="loading || disabled"
    class="btn"
    :class="buttonClass"
  >
    <template v-if="label">
      <span>{{ label }} </span>
    </template>
    <div v-else class="flex items-center space-x-2">
      <slot></slot>
    </div>
  </Link>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: "button",
    },
    variant: {
      type: String,
      default: "primary",
    },
    size: {
      type: String,
      default: "md",
    },
    href: {
      type: String,
      default: null,
      required: false,
    },
    label: {
      type: String,
      default: "",
    },
    loading: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    buttonClass() {
      const variant = {
        primary: "btn btn-primary",
        "primary-disable": "btn btn-primary-disable",
        "primary-square": "btn btn-primary-square",
        "primary-square-disable": "btn btn-primary-square-disable",
        "secondary-square": "btn btn-secondary-square",
        "secondary-square-disable": "btn btn-secondary-square-disable",
        secondary: "btn btn-secondary",
        "secondary-disable": "btn btn-secondary-disable",
        "primary-submit": "btn btn-primary-submit",
      }[this.variant];
      const size = {
        sm: "btn-sm py-[11px] px-[30px]",
        md: "btn-md py-[11px] px-[30px]",
        lg: "btn-lg py-[11px] px-[30px]",
        fix: "btn-md py-[12px] px-[20px]",
      }[this.size];
      return ` ${variant} ${size} `;
    },
  },
};
</script>
<style lang="scss" scoped>
.gg-spinner {
  transform: scale(var(--ggs, 1));
}

.gg-spinner,
.gg-spinner::after,
.gg-spinner::before {
  box-sizing: border-box;
  position: relative;
  display: block;
  width: 1rem;
  height: 1rem;
}

.gg-spinner::after,
.gg-spinner::before {
  content: "";
  position: absolute;
  border-radius: 100px;
}

.gg-spinner::before {
  animation: spinner 1s cubic-bezier(0.6, 0, 0.4, 1) infinite;
  border: 3px solid transparent;
  border-top-color: currentColor;
}

.gg-spinner::after {
  border: 3px solid;
  opacity: 0.2;
}

@keyframes spinner {
  0% {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(359deg);
  }
}
</style>
