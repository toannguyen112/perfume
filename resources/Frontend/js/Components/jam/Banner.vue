<template>
  <section>
    <div
      v-if="isPolicyPage"
      class="w-full md:h-[100vh] h-[70vh] relative overflow-hidden z-20 max-h-[360px]"
    >
      <slot></slot>
      <JPicture :src="banner.image" class="relative z-0 picture-cover" />
    </div>
    <div
      v-else
      class="w-full md:h-[100vh] h-[70vh] relative overflow-hidden z-20"
      :class="isPage ? 'max-h-[810px]' : 'max-h-[540px]'"
    >
      <slot></slot>
      <JPicture :src="banner.image" class="relative z-0 picture-cover" />
    </div>
  </section>
</template>

<script>
export default {
  props: {
    banner: {
      type: Object,
    },
  },
  computed: {
    isPage() {
      return (
        this.$route.name.startsWith("contact") ||
        this.$route.name.startsWith("about") ||
        this.$route.name.startsWith("index")
      );
    },
    isPolicyPage() {
      return this.$route.name.startsWith("policy");
    },
  },
};
</script>
