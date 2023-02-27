<template>
  <div class="hamburger xl:hidden h-[32px] w-[32px] flex items-center justify-center" @click="toggleMenu()">
    <div class="hamburger__icon h-[24px] w-[24px]">
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
</template>

<script>

export default {
  methods: {
    toggleMenu() {
      const el = document.body
      
      this.isOpened = !this.isOpened
      if (this.isOpened) {
        el.classList.add('overflow-hidden', 'menu-is-opened')
      } else {
        el.classList.remove('overflow-hidden', 'menu-is-opened')
      }
    },
  },
  data() {
    return {
      isOpened: false
    }
  }
}
</script>

<style lang="scss" scoped>
.hamburger {
  &__icon {
    @apply flex flex-col justify-center cursor-pointer;

    div {
      width: 100%;
      height: 2px;
      margin-bottom: 3px;
      margin-top: 3px;
      @apply bg-gray-700 transition ease-out duration-200;
    }

    .menu-is-opened & {
      div {
        margin-bottom: 0;
        margin-top: 0;

        &:first-child {
          transform: translateY(calc(50% + 2px)) rotate(45deg);
        }

        &:nth-child(2) {
          opacity: 0;
          width: 0px;
        }

        &:last-child {
          transform: translateY(calc(50% - 2px)) rotate(-45deg);
        }
      }
    }
  }
}
</style>
